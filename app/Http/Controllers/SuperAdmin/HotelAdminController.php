<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\User;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelAdminController extends Controller
{
    /**
     * Inject reusable HotelService layer.
     */
    public function __construct(
        protected HotelService $hotelService
    ) {}

    /**
     * Display a listing of the hotel admins.
     */
    public function index()
    {
        $hotels = HotelAdmin::with(['plan', 'distributor', 'connectedDevices'])
            ->orderBy('created_at', 'desc')
            ->get();

        try {
            $distributors = User::role('distributor')->orderBy('name', 'asc')->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->orderBy('name', 'asc')->get();
        }

        return view('super_admin.hotels.index', compact('hotels', 'distributors'));
    }

    /**
     * Show the form for creating a new hotel admin.
     */
    public function create()
    {
        $plans = Plan::with('tax')->where('status', '=', true)->orderBy('price', 'asc')->get();
        try {
            $distributors = User::role('distributor')->where('status', true)->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->where('status', true)->get();
        }
        $themes = \App\Models\TvTemplate::select('theme_id', 'theme_name')->orderBy('theme_id', 'asc')->get()->unique('theme_id')->values();
        $defaultThemeId = (int) app_setting('default_theme_id', 1);

        return view('super_admin.hotels.create', compact('plans', 'distributors', 'themes', 'defaultThemeId'));
    }

    /**
     * Store a newly created hotel admin in storage using FormRequest and Service.
     */
    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelService->createHotel(
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image')
        );

        if ($request->input('payment_action') === 'pay_now' && $hotel->plan_id) {
            return redirect()->route('super-admin.hotels.pay', $hotel->id);
        }

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Vendor registered successfully! (Status: Waiting for Payment)');
    }

    /**
     * Display the specified hotel admin.
     */
    public function show(int $id)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor', 'connectedDevices'])->findOrFail($id);

        return view('super_admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel admin.
     */
    public function edit(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $plans = Plan::with('tax')->where('status', '=', true)->orderBy('price', 'asc')->get();
        try {
            $distributors = User::role('distributor')->where('status', true)->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->where('status', true)->get();
        }
        $themes = \App\Models\TvTemplate::select('theme_id', 'theme_name')->orderBy('theme_id', 'asc')->get()->unique('theme_id')->values();
        $defaultThemeId = (int) app_setting('default_theme_id', 1);

        return view('super_admin.hotels.edit', compact('hotel', 'plans', 'distributors', 'themes', 'defaultThemeId'));
    }

    /**
     * Update the specified hotel admin in storage using FormRequest and Service.
     */
    public function update(UpdateHotelRequest $request, int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        $updatedHotel = $this->hotelService->updateHotel(
            $hotel,
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image'),
            $request->file('slider_images', [])
        );

        if ($request->input('payment_action') === 'pay_now' && $updatedHotel->plan_id) {
            return redirect()->route('super-admin.hotels.pay', ['id' => $updatedHotel->id, 'autostart' => 1]);
        }

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel details updated successfully!');
    }

    /**
     * Show Super Admin instant checkout for a hotel subscription renewal.
     */
    public function showPayment(int $id)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor', 'connectedDevices'])->findOrFail($id);

        if (!$hotel->plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign a subscription plan first.');
        }

        $allPlans = \App\Models\Plan::with('tax')
            ->where('status', 1)
            ->where('is_hidden', false)
            ->get();

        if ($allPlans->isEmpty() || !$allPlans->contains('id', $hotel->plan_id)) {
            $allPlans = \App\Models\Plan::with('tax')->get();
        }
        $activeGateway = \App\Models\PaymentGateway::where('is_enabled', true)->first();

        $latestInv = \App\Models\Invoice::where('hotel_admin_id', $hotel->id)->latest('id')->first();
        $tvCount = max(
            (int) ($hotel->room_count ?? 0),
            (int) ($latestInv ? $latestInv->tv_count : 0),
            (int) ($hotel->connectedDevices->count() ?? 0)
        );
        if ($tvCount <= 0 && $hotel->plan && $hotel->plan->room_count > 0) {
            $tvCount = (int) $hotel->plan->room_count;
        }
        if ($tvCount <= 0) {
            $tvCount = 10;
        }

        $pricing = $hotel->plan->calculatePricing($tvCount);

        return view('super_admin.hotels.pay', compact('hotel', 'tvCount', 'pricing', 'activeGateway', 'allPlans'));
    }

    /**
     * Create online payment gateway order (AJAX) for Super Admin renewal checkout.
     */
    public function createPaymentOrder(Request $request, int $id)
    {
        $hotel = HotelAdmin::with(['plan.tax'])->findOrFail($id);

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tv_count' => 'required|integer|min:1|max:10000',
            'duration_months' => 'required|integer|in:1,3,6,12,24,36',
        ]);

        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);
        $tvCount = max(1, (int) $validated['tv_count']);
        $durationMonths = (int) $validated['duration_months'];

        $paymentOrderService = app(\App\Services\PaymentOrderService::class);
        $pricing = $paymentOrderService->calculateSubscriptionTotal($plan, $tvCount, $durationMonths);
        $amount = $pricing['total_amount'];

        try {
            $gateway = \App\Services\PaymentManager::active();

            if (!$gateway->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No online payment gateway is currently enabled. Please enable a payment gateway in Gateway Settings.',
                ], 422);
            }

            $order = $paymentOrderService->createPendingOrder(
                $gateway,
                $amount,
                'HRENEW-',
                [
                    'name' => $hotel->owner_name ?: 'Hotel Admin',
                    'email' => $hotel->email ?: 'admin@pax.tv',
                    'phone' => $hotel->phone ?: '9999999999',
                    'product_info' => 'Hotel Renewal: ' . $plan->name . ' (' . $tvCount . ' TVs, ' . $durationMonths . ' mo)',
                    'udf1' => (string) $hotel->id,
                    'udf2' => (string) $plan->id,
                    'udf3' => (string) $durationMonths,
                    'udf4' => (string) $tvCount,
                    'udf5' => 'super_admin_renewal',
                ],
                [
                    'purpose' => 'super_admin_renewal',
                    'hotel_admin_id' => $hotel->id,
                    'plan_id' => $plan->id,
                    'tv_count' => $tvCount,
                    'duration_months' => $durationMonths,
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment gateway. Please check gateway configuration.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'gateway' => $gateway->getCode(),
            'key_id' => $order['key_id'] ?? null,
            'order_id' => $order['order_id'],
            'amount' => $order['amount'] ?? $amount,
            'currency' => $order['currency'] ?? 'INR',
            'checkout_url' => $order['checkout_url'] ?? null,
            'payu_params' => $order['payu_params'] ?? null,
            'name' => $hotel->owner_name ?: 'Hotel Admin',
            'email' => $hotel->email ?: '',
            'phone' => $hotel->phone ?: '',
        ]);
    }

    /**
     * Process Super Admin instant checkout and generate license + invoice after STRICT payment gateway verification.
     */
    public function processPayment(Request $request, int $id, \App\Services\SubscriptionService $subService)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor'])->findOrFail($id);
        
        $planId = $request->input('plan_id', $hotel->plan_id);
        $plan = \App\Models\Plan::with('tax')->find($planId) ?: $hotel->plan;

        if (!$plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign a subscription plan first.');
        }

        $activeGateway = \App\Models\PaymentGateway::where('is_enabled', true)->first();
        if (!$activeGateway) {
            return back()->with('error', 'No active payment gateway configured. Please enable an online gateway first.');
        }

        $gateway = \App\Services\PaymentManager::active();
        $code = $gateway->getCode();

        $tvCount = max(1, (int) ($request->input('tv_count', $hotel->room_count ?: 1)));
        $durationMonths = (int) ($request->input('duration_months', ($plan->billing_cycle === 'yearly' ? 12 : 1)));
        if ($durationMonths < 1) {
            $durationMonths = ($plan->billing_cycle === 'yearly' ? 12 : 1);
        }

        $paymentOrderService = app(\App\Services\PaymentOrderService::class);
        $pricing = $paymentOrderService->calculateSubscriptionTotal($plan, $tvCount, $durationMonths);
        $totalAmount = $pricing['total_amount'];

        if ($code === 'payu') {
            $payload = $request->only(['status', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'hash', 'udf1', 'udf2', 'udf3', 'udf4', 'udf5', 'additionalCharges']);
            $orderId = (string) ($payload['txnid'] ?? '');
            $paymentReference = $orderId;
        } else {
            $payload = $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
            $orderId = (string) ($payload['razorpay_order_id'] ?? '');
            $paymentReference = (string) ($payload['razorpay_payment_id'] ?? '');
        }

        if ($orderId === '' && $paymentReference === '') {
            return back()->with('error', 'Payment was NOT completed on the payment gateway. Renewal was cancelled.');
        }

        try {
            $consumed = \Illuminate\Support\Facades\DB::transaction(function () use (
                $paymentOrderService,
                $gateway,
                $code,
                $orderId,
                $paymentReference,
                $payload,
                $hotel,
                $plan,
                $tvCount,
                $durationMonths,
                $totalAmount,
                $subService
            ) {
                $consumed = $paymentOrderService->consumeVerifiedPayment(
                    $gateway,
                    $orderId,
                    $paymentReference,
                    $payload,
                    'super_admin_renewal',
                    [
                        'hotel_admin_id' => $hotel->id,
                        'plan_id' => $plan->id,
                        'expected_amount' => $totalAmount,
                    ]
                );

                $subService->activate($hotel, $plan, $durationMonths, [
                    'tv_count' => $tvCount,
                    'amount' => $consumed['amount'],
                    'payment_method' => 'online_gateway',
                    'gateway_code' => $code,
                    'transaction_id' => $consumed['transaction_id'],
                    'payload' => $consumed['payload'],
                    'skip_payment_tx_record' => true,
                    'notes' => "Super Admin verified online renewal via {$gateway->getName()} ({$consumed['transaction_id']})",
                ]);

                return $consumed;
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e instanceof \RuntimeException ? $e->getMessage() : ('Payment verification failed via ' . $gateway->getName() . '. Transaction was not trusted, renewal was NOT activated.'));
        }

        return redirect()->route('super-admin.hotels.show', $hotel->id)
            ->with('success', "Payment of ₹" . number_format($consumed['amount'], 2) . " verified via {$gateway->getName()}! Hotel renewed for {$durationMonths} months and GST Tax Invoice generated.");
    }

    /**
     * Show Super Admin TV Capacity Add-on screen for an active hotel.
     */
    public function showAddon(int $id, \App\Services\SubscriptionService $subService)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor', 'connectedDevices'])->findOrFail($id);

        if (!$hotel->plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign an active subscription plan first before adding TV slots.');
        }

        $now = now();
        $hasActivePlan = $hotel->expiry_date && $hotel->expiry_date->gt($now);
        $remainingDays = $hasActivePlan ? max(1, (int) $now->diffInDays($hotel->expiry_date, false)) : 30;

        $defaultAdditionalTvs = 10;
        $pricing = $subService->calculateAddonPricing($hotel, $defaultAdditionalTvs);

        return view('super_admin.hotels.addon', compact('hotel', 'defaultAdditionalTvs', 'pricing', 'remainingDays'));
    }

    /**
     * Process Super Admin TV Add-on and update hotel device quota.
     */
    public function processAddon(Request $request, int $id, \App\Services\SubscriptionService $subService)
    {
        $request->validate([
            'additional_tvs' => 'required|integer|min:1|max:5000',
            'is_complimentary' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $hotel = HotelAdmin::with(['plan.tax', 'distributor'])->findOrFail($id);
        $additionalTvs = (int) $request->input('additional_tvs');
        $isComplimentary = $request->boolean('is_complimentary');

        $result = $subService->addTvAddon($hotel, $additionalTvs, [
            'is_complimentary' => $isComplimentary,
            'payment_method' => $isComplimentary ? 'complimentary_grant' : 'super_admin_manual',
            'notes' => $request->input('notes') ?: ($isComplimentary ? "Super Admin complimentary TV add-on (+{$additionalTvs} TVs)" : "Super Admin TV Add-On (+{$additionalTvs} TVs)"),
        ]);

        return redirect()->route('super-admin.hotels.show', $hotel->id)
            ->with('success', "TV Add-On applied! {$additionalTvs} extra TV slots authorized. Total capacity is now {$result['new_tv_count']} TVs.");
    }

    /**
     * Toggle the status of a hotel admin (active/inactive).
     */
    public function toggleStatus(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $newStatus = $this->hotelService->toggleStatus($hotel);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status updated to ' . ($newStatus ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Update the approval status of a hotel admin.
     */
    public function toggleApproval(Request $request, int $id)
    {
        $request->validate([
            'approval_status' => 'required|in:pending,approved,disapproved'
        ]);

        $hotel = HotelAdmin::findOrFail($id);
        $approvalStatus = $this->hotelService->updateApprovalStatus($hotel, $request->approval_status);

        return response()->json([
            'success' => true,
            'approval_status' => $approvalStatus,
            'message' => 'Approval status updated to ' . ucfirst($approvalStatus)
        ]);
    }

    /**
     * Remove the specified hotel admin from storage.
     */
    public function destroy(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $this->hotelService->deleteHotel($hotel);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin deleted successfully!');
    }
}
