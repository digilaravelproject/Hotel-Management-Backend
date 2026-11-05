<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Models\ConnectedDevice;
use App\Models\PaymentTransaction;
use App\Services\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DistributorController extends Controller
{
    /**
     * Inject reusable service layer.
     */
    public function __construct(
        protected \App\Services\DashboardService $dashboardService,
        protected \App\Services\HotelService $hotelService
    ) {}

    /**
     * Distributor dashboard with key metrics and recent records.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $data = $this->dashboardService->getDistributorDashboardData($user->id);

        $totalHotels = $data['metrics']['total_hotels'];
        $activeHotels = $data['metrics']['active_hotels'];
        $totalSalesCount = $data['metrics']['sales_count'];
        $totalRevenue = $data['metrics']['total_revenue'];
        $recentHotels = $data['recentHotels'];
        $recentSales = $data['recentTransactions'];
        $metrics = $data['metrics'];
        $planDistribution = $data['planDistribution'];
        $cityDistribution = $data['cityDistribution'];
        $trends = $data['trends'];
        $alerts = $data['alerts'];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'metrics' => [
                    'total_hotels' => $totalHotels,
                    'active_hotels' => $activeHotels,
                    'total_sales' => $totalSalesCount,
                    'total_revenue' => $totalRevenue,
                ],
                'recent_hotels' => $recentHotels,
                'recent_sales' => $recentSales,
                'plan_distribution' => $planDistribution,
                'city_distribution' => $cityDistribution,
                'trends' => $trends,
            ]);
        }

        return view('distributor.dashboard', compact(
            'totalHotels',
            'activeHotels',
            'totalSalesCount',
            'totalRevenue',
            'recentHotels',
            'recentSales',
            'metrics',
            'planDistribution',
            'cityDistribution',
            'trends',
            'alerts'
        ));
    }

    /**
     * List all hotels onboarded by this distributor.
     */
    public function hotels(Request $request)
    {
        $user = auth()->user();
        $query = $user->hotels()->with('plan')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('hotel_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('license_key', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'active') {
                $query->where('status', true);
            } elseif ($filter === 'paid') {
                $query->where('payment_status', 'paid');
            } elseif ($filter === 'expiring') {
                $query->whereNotNull('expiry_date')->whereBetween('expiry_date', [now(), now()->addDays(30)]);
            } elseif ($filter === 'expired') {
                $query->whereNotNull('expiry_date')->where('expiry_date', '<', now());
            } elseif ($filter === 'unpaid') {
                $query->where(function ($q) {
                    $q->whereNull('payment_status')->orWhere('payment_status', '!=', 'paid');
                });
            }
        }

        $stats = [
            'total' => $user->hotels()->count(),
            'active' => $user->hotels()->where('status', true)->count(),
            'paid' => $user->hotels()->where('payment_status', 'paid')->count(),
            'expiring' => $user->hotels()->whereNotNull('expiry_date')->whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
            'expired' => $user->hotels()->whereNotNull('expiry_date')->where('expiry_date', '<', now())->count(),
        ];

        $hotels = $query->paginate(12)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'data' => $hotels,
            ]);
        }

        return view('distributor.hotels.index', compact('hotels', 'stats'));
    }

    /**
     * Show form to onboard a new hotel client.
     */
    public function createHotel()
    {
        $plans = Plan::where('status', 1)->where('is_hidden', false)->get();
        return view('distributor.hotels.create', compact('plans'));
    }

    /**
     * Register a new hotel under this distributor using HotelService.
     */
    public function storeHotel(\App\Http\Requests\StoreHotelRequest $request)
    {
        $user = auth()->user();
        $hotel = $this->hotelService->createHotelForDistributor($user->id, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Hotel '{$hotel->hotel_name}' onboarded successfully.",
                'data' => $hotel->load('plan'),
            ], 201);
        }

        return redirect()->route('distributor.hotels.index')
                         ->with('success', "Hotel '{$hotel->hotel_name}' registered successfully! License Key: {$hotel->license_key}");
    }

    /**
     * List all sales and packages sold by this distributor.
     */
    public function sales(Request $request)
    {
        $user = auth()->user();
        $query = $user->distributorSales()->with(['hotel', 'plan'])->latest();

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->input('hotel_id'));
        }

        $sales = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $sales,
            ]);
        }

        return view('distributor.sales.index', compact('sales'));
    }

    /**
     * Show form to sell/assign a package to an onboarded hotel.
     */
    public function createSale(Request $request)
    {
        $user = auth()->user();
        $hotels = $user->hotels()
            ->with(['plan', 'invoices' => function($q) { $q->latest('id'); }])
            ->withCount('connectedDevices')
            ->orderBy('hotel_name')
            ->get();

        foreach ($hotels as $hotel) {
            $latestInv = $hotel->invoices ? $hotel->invoices->first() : null;
            $hotel->existing_tv_count = max(
                (int) ($hotel->room_count ?? 0),
                (int) ($latestInv ? $latestInv->tv_count : 0),
                (int) ($hotel->connected_devices_count ?? 0)
            );
            if ($hotel->existing_tv_count <= 0 && $hotel->plan && $hotel->plan->room_count > 0) {
                $hotel->existing_tv_count = (int) $hotel->plan->room_count;
            }
            if ($hotel->existing_tv_count <= 0) {
                $hotel->existing_tv_count = 10;
            }
        }

        $plans = Plan::with('tax')->where('status', 1)->where('is_hidden', false)->get();
        $selectedHotelId = $request->query('hotel_id');
        $selectedHotel = null;

        if ($selectedHotelId) {
            $selectedHotel = $hotels->firstWhere('id', (int) $selectedHotelId);
            if (!$selectedHotel) {
                $selectedHotel = $user->hotels()
                    ->with(['plan', 'invoices' => function($q) { $q->latest('id'); }])
                    ->withCount('connectedDevices')
                    ->find($selectedHotelId);
                if ($selectedHotel) {
                    $latestInv = $selectedHotel->invoices ? $selectedHotel->invoices->first() : null;
                    $selectedHotel->existing_tv_count = max(
                        (int) ($selectedHotel->room_count ?? 0),
                        (int) ($latestInv ? $latestInv->tv_count : 0),
                        (int) ($selectedHotel->connected_devices_count ?? 0)
                    );
                    if ($selectedHotel->existing_tv_count <= 0 && $selectedHotel->plan && $selectedHotel->plan->room_count > 0) {
                        $selectedHotel->existing_tv_count = (int) $selectedHotel->plan->room_count;
                    }
                    if ($selectedHotel->existing_tv_count <= 0) {
                        $selectedHotel->existing_tv_count = 10;
                    }
                }
            }
        }

        $defaultTax = \App\Models\Tax::where('status', true)->where('is_default', true)->first();

        $activeGatewayRecord = \App\Models\PaymentGateway::where('is_enabled', true)->first();
        $gateway = null;
        try {
            $gateway = PaymentManager::active();
        } catch (\Exception $e) {
            $gateway = null;
        }

        return view('distributor.sales.create', compact('hotels', 'plans', 'selectedHotelId', 'selectedHotel', 'activeGatewayRecord', 'gateway', 'defaultTax'));
    }

    /**
     * Create a payment gateway order (AJAX) for online gateway sales.
     * Returns the gateway order id + key needed to open Razorpay Checkout on the frontend.
     */
    public function createSaleOrder(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotel_admins,id',
            'plan_id' => 'required|exists:plans,id',
            'tv_count' => 'required|integer|min:1|max:10000',
            'duration_months' => 'required|integer|in:1,3,6,12',
        ]);

        // Security check: hotel must belong to the authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);

        $tvCount = max(1, (int) $validated['tv_count']);
        $durationMonths = (int) $validated['duration_months'];
        $ratePerTv = $plan->effective_price_per_tv;
        $subtotal = round($ratePerTv * $tvCount * $durationMonths, 2);

        $tax = $plan->tax ?: \App\Models\Tax::where('status', true)->where('is_default', true)->first();
        $taxType = $plan->tax_type ?? 'exclusive';
        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            $amount = $taxCalc['total_amount'];
            $taxAmount = $taxCalc['tax_amount'];
        } else {
            $amount = $subtotal;
            $taxAmount = 0.00;
            $taxCalc = [
                'subtotal' => $subtotal,
                'tax_amount' => 0.0,
                'cgst_amount' => 0.0,
                'sgst_amount' => 0.0,
                'total_amount' => $subtotal,
            ];
        }

        try {
            $gateway = PaymentManager::active();

            if (!$gateway->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No online payment gateway is currently enabled. Please contact support.',
                ], 422);
            }

            $order = $gateway->createOrder($amount, 'DSALE-' . strtoupper(Str::random(10)), [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'product_info' => 'Plan: ' . $plan->name . ' (' . $tvCount . ' TVs)',
                'udf1' => (string) $hotel->id,
                'udf2' => (string) $plan->id,
                'udf3' => (string) $durationMonths,
                'udf4' => (string) $user->id,
                'udf5' => (string) $tvCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage(),
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
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'phone' => $user->phone ?? '',
            'breakdown' => [
                'tv_count' => $tvCount,
                'price_per_tv' => $ratePerTv,
                'duration_months' => $durationMonths,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $amount,
            ],
        ]);
    }

    /**
     * Process package sale and update hotel license/validity using HotelService.
     */
    public function storeSale(\App\Http\Requests\StoreDistributorSaleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Security check: ensure hotel belongs to authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);

        $tvCount = max(1, (int) ($validated['tv_count'] ?? $hotel->room_count ?: 1));
        $durationMonths = (int) $validated['duration_months'];
        $ratePerTv = $plan->effective_price_per_tv;
        $subtotal = round($ratePerTv * $tvCount * $durationMonths, 2);

        $tax = $plan->tax ?: \App\Models\Tax::where('status', true)->where('is_default', true)->first();
        $taxType = $plan->tax_type ?? 'exclusive';
        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            $computedAmount = $taxCalc['total_amount'];
            $taxAmount = $taxCalc['tax_amount'];
            $cgst = $taxCalc['cgst_amount'];
            $sgst = $taxCalc['sgst_amount'];
        } else {
            $computedAmount = $subtotal;
            $taxAmount = 0.00;
            $cgst = 0.00;
            $sgst = 0.00;
        }

        // Strict enforcement: Only verified online gateway payment is accepted
        if (($validated['payment_method'] ?? '') !== 'online_gateway') {
            return $this->gatewaySaleResponse(
                $request,
                false,
                'Offline or manual payment methods are disabled. All purchases must be completed via the active online payment gateway.'
            );
        }

        $gateway = PaymentManager::active();
        $code = $gateway->getCode();

        if ($code === 'payu') {
            $payload = $request->only(['status', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'hash']);
            $transactionId = $payload['txnid'] ?? '';
        } else {
            $payload = $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
            $transactionId = $payload['razorpay_payment_id'] ?? '';
        }

        if (empty($transactionId) && empty($payload['razorpay_order_id'])) {
            return $this->gatewaySaleResponse(
                $request,
                false,
                'Online payment was not completed. No payment reference received from the gateway.'
            );
        }

        if (!$gateway->verifyPayment($payload)) {
            PaymentTransaction::create([
                'distributor_id' => $user->id,
                'hotel_admin_id' => $hotel->id,
                'gateway_code' => $code,
                'transaction_id' => (string) ($transactionId ?: 'failed_' . time()),
                'amount' => $computedAmount,
                'currency' => 'INR',
                'status' => 'failed',
                'payload' => $payload,
                'plan_id' => $plan->id,
            ]);

            return $this->gatewaySaleResponse(
                $request,
                false,
                'Payment verification failed via ' . $gateway->getName() . '. The transaction was not trusted, license was not activated.'
            );
        }

        // Prevent duplicate processing of the same gateway payment
        if ($transactionId) {
            $alreadyProcessed = PaymentTransaction::where('transaction_id', $transactionId)
                ->where('status', 'success')
                ->exists();

            if ($alreadyProcessed) {
                return $this->gatewaySaleResponse(
                    $request,
                    false,
                    'This payment was already processed. Duplicate submission blocked.'
                );
            }
        }

        // Use the verified gateway amount (in rupees) instead of client-sent value
        $validated['amount'] = $computedAmount;
        $validated['tv_count'] = $tvCount;
        $validated['notes'] = trim(
            ($validated['notes'] ?? '') .
            " | {$gateway->getName()} Verified: " . ($transactionId ?? $payload['razorpay_order_id'])
        );

        $result = $this->hotelService->assignPlanAndRecordSale(
            $hotel,
            $plan,
            $validated['duration_months'],
            $validated,
            $user->id
        );

        // Update hotel TV capacity to matched purchased quota
        $hotel->update(['room_count' => $tvCount]);

        // Auto-generate Tax-Compliant Invoice for Distributor and Hotel Client
        $invoice = null;
        try {
            $invoice = \App\Models\Invoice::create([
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'hotel_admin_id' => $hotel->id,
                'distributor_id' => $user->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'billing_cycle' => $plan->billing_cycle ?? 'monthly',
                'tv_count' => $tvCount,
                'price_per_tv' => $ratePerTv,
                'subtotal' => $subtotal,
                'tax_id' => $tax ? $tax->id : null,
                'tax_name' => $tax ? $tax->name : null,
                'tax_rate' => $tax ? (float) $tax->rate : 0.00,
                'tax_type' => $taxType,
                'tax_amount' => $taxAmount,
                'cgst_amount' => $cgst,
                'sgst_amount' => $sgst,
                'total_amount' => $computedAmount,
                'payment_status' => 'paid',
                'payment_method' => 'online_gateway',
                'transaction_id' => (string) ($transactionId ?: ($payload['razorpay_order_id'] ?? '')),
                'paid_at' => now(),
                'period_start' => now(),
                'period_end' => $result['new_expiry'],
                'notes' => "Distributor sale for {$tvCount} TVs ({$durationMonths} months) - {$plan->name}",
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Distributor Invoice generation skipped or failed: " . $e->getMessage());
        }

        // Record successful gateway transaction in the ledger
        PaymentTransaction::create([
            'distributor_id' => $user->id,
            'hotel_admin_id' => $hotel->id,
            'gateway_code' => $gateway->getCode(),
            'transaction_id' => (string) ($transactionId ?? $payload['razorpay_order_id']),
            'amount' => $computedAmount,
            'currency' => 'INR',
            'status' => 'success',
            'payload' => $payload,
            'plan_id' => $plan->id,
            'paid_at' => now(),
            'idempotency_checked' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Package '{$plan->name}' for {$tvCount} TVs successfully sold to '{$hotel->hotel_name}'.",
                'sale' => $result['sale']->load(['hotel', 'plan']),
                'invoice_id' => $invoice ? $invoice->id : null,
                'invoice_number' => $invoice ? $invoice->invoice_number : null,
                'invoice_url' => $invoice ? route('distributor.invoices.show', $invoice->id) : null,
                'new_expiry' => $result['new_expiry']->format('Y-m-d'),
            ], 201);
        }

        $invMsg = $invoice ? " Tax Invoice #{$invoice->invoice_number} generated." : "";
        return redirect()->route('distributor.invoices.index')
                         ->with('success', "Package '{$plan->name}' successfully activated for '{$hotel->hotel_name}' ({$tvCount} TVs)! Valid until {$result['new_expiry']->format('M d, Y')}.{$invMsg}");
    }

    /**
     * Display a listing of tax invoices for the distributor.
     */
    public function invoices(Request $request)
    {
        $user = auth()->user();
        $query = \App\Models\Invoice::with(['hotel', 'plan', 'tax'])
            ->where('distributor_id', $user->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('hotel', fn($hq) => $hq->where('hotel_name', 'LIKE', "%{$search}%"));
            });
        }

        $invoices = $query->paginate(15);
        $totalPaidAmount = \App\Models\Invoice::where('distributor_id', $user->id)->where('payment_status', 'paid')->sum('total_amount');
        $totalInvoicesCount = \App\Models\Invoice::where('distributor_id', $user->id)->count();

        return view('distributor.invoices.index', compact('invoices', 'totalPaidAmount', 'totalInvoicesCount'));
    }

    /**
     * Display the specified invoice detail / print view for distributor.
     */
    public function showInvoice($id)
    {
        $user = auth()->user();
        $invoice = \App\Models\Invoice::with(['hotel', 'plan', 'tax'])
            ->where('distributor_id', $user->id)
            ->findOrFail($id);

        return view('distributor.invoices.show', compact('invoice'));
    }

    /**
     * Uniform response helper for gateway payment failures during a sale.
     */
    private function gatewaySaleResponse(Request $request, bool $success, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $success ? 201 : 422);
        }

        return redirect()->route('distributor.sales.create', $request->only('hotel_id'))
                         ->with('error', $message);
    }

    /**
     * List connected TV devices for hotels registered by this distributor.
     */
    public function devices(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $hotelsQuery = $isSuperAdmin ? HotelAdmin::query() : $user->hotels();
        $hotelIds = $hotelsQuery->pluck('id');

        $query = ConnectedDevice::whereIn('hotel_admin_id', $hotelIds)
            ->with(['hotelAdmin.plan', 'hotelAdmin.distributor']);

        if ($request->filled('hotel_id')) {
            $hotelId = (int) $request->input('hotel_id');
            // Strict authorization check: Only allowed if the hotel belongs to this distributor
            if (!$hotelIds->contains($hotelId)) {
                abort(403, 'Unauthorized. You do not have access to TV devices for this hotel.');
            }
            $query->where('hotel_admin_id', $hotelId);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('room_no', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('hotelAdmin', function ($hq) use ($search) {
                      $hq->where('hotel_name', 'like', "%{$search}%")
                        ->orWhere('license_key', 'like', "%{$search}%");
                  });
            });
        }

        $devices = $query->latest()->paginate(20)->withQueryString();
        $hotels = $hotelsQuery->orderBy('hotel_name')->get();
        $selectedHotel = $request->filled('hotel_id') ? $hotels->firstWhere('id', (int) $request->input('hotel_id')) : null;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $devices,
            ]);
        }

        return view('distributor.devices.index', compact('devices', 'hotels', 'selectedHotel'));
    }

    /**
     * Disconnect a device belonging to a hotel onboarded by this distributor.
     */
    public function destroyDevice(int $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $hotelIds = $isSuperAdmin ? HotelAdmin::pluck('id') : $user->hotels()->pluck('id');

        // Strict authorization: Ensure device belongs to a hotel managed by this distributor
        $device = ConnectedDevice::whereIn('hotel_admin_id', $hotelIds)->findOrFail($id);
        $roomNo = $device->room_no;
        $device->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Device for Room {$roomNo} disconnected successfully."
            ]);
        }

        return redirect()->back()->with('success', "Device for Room {$roomNo} disconnected successfully.");
    }
}
