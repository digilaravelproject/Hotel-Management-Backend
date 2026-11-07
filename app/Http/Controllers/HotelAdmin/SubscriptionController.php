<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PaymentTransaction;
use App\Services\PaymentManager;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $hotel->loadMissing('plan.tax');
        $plans = Plan::with('tax')
            ->where('status', true)
            ->where(function ($q) use ($hotel) {
                $q->where('is_hidden', false);
                if ($hotel && $hotel->plan_id) {
                    $q->orWhere('id', $hotel->plan_id);
                }
            })
            ->orderBy('billing_cycle', 'asc')
            ->orderBy('price_per_tv', 'asc')
            ->get();
        $gateway = PaymentManager::active();

        $hasActivePlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());
        $remainingDays = ($hasActivePlan && $hotel->expiry_date) ? max(1, (int) now()->diffInDays($hotel->expiry_date, false)) : 0;
        $pairedDeviceCount = \App\Models\ConnectedDevice::where('hotel_admin_id', $hotel->id)->count();
        $addonPricing = null;
        if ($hasActivePlan) {
            try {
                $addonPricing = $this->subscriptionService->calculateAddonPricing($hotel, 10);
            } catch (\Throwable $e) {
                $addonPricing = null;
            }
        }

        $recentInvoices = \App\Models\Invoice::with(['plan', 'tax'])
            ->where('hotel_admin_id', $hotel->id)
            ->latest('id')
            ->take(5)
            ->get();

        return view('hotel_admin.subscription', compact('hotel', 'plans', 'gateway', 'hasActivePlan', 'remainingDays', 'addonPricing', 'recentInvoices', 'pairedDeviceCount'));
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tv_count' => 'required|integer|min:1|max:1000',
            'duration_months' => 'required|integer|in:1,3,6,12,24,36',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);
        $tvCount = (int) $validated['tv_count'];
        $durationMonths = (int) $validated['duration_months'];

        // Dynamic Per-TV Tax-compliant calculation
        $calc = $plan->calculatePricing($tvCount);
        $isYearly = ($plan->billing_cycle === 'yearly');
        $multiplier = $isYearly ? max(1, round($durationMonths / 12, 2)) : $durationMonths;
        $amount = round($calc['total_amount'] * $multiplier, 2);

        try {
            $gateway = PaymentManager::active();
            if (!$gateway->isEnabled()) {
                return response()->json(['success' => false, 'message' => 'No payment gateway is enabled. Please contact support.'], 422);
            }
            $order = $gateway->createOrder($amount, 'HPLAN-' . strtoupper(Str::random(10)), [
                'name' => $hotel->hotel_name ?? '',
                'email' => $hotel->email ?? '',
                'phone' => $hotel->phone ?? '',
                'product_info' => 'Plan: ' . $plan->name . " ({$tvCount} TVs)",
                'udf1' => (string) $hotel->id,
                'udf2' => (string) $plan->id,
                'udf3' => (string) $durationMonths,
                'udf4' => (string) $tvCount,
                'udf5' => 'hotel_subscription',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment init failed: ' . $e->getMessage()], 422);
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
            'name' => $hotel->hotel_name ?? '',
            'email' => $hotel->email ?? '',
            'phone' => $hotel->phone ?? '',
        ]);
    }

    // Verify payment & activate / upgrade / renew the plan
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tv_count' => 'nullable|integer|min:1',
            'duration_months' => 'required|integer|in:1,3,6,12,24,36',
            'razorpay_order_id' => 'required_without:txnid|nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
            'txnid' => 'required_without:razorpay_order_id|nullable|string',
            'hash' => 'nullable|string',
            'status' => 'nullable|string',
            'amount' => 'nullable|string',
            'productinfo' => 'nullable|string',
            'firstname' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);
        $tvCount = (int) ($validated['tv_count'] ?? $hotel->room_count ?? 1);
        $durationMonths = (int) $validated['duration_months'];

        $calc = $plan->calculatePricing($tvCount);
        $isYearly = ($plan->billing_cycle === 'yearly');
        $multiplier = $isYearly ? max(1, round($durationMonths / 12, 2)) : $durationMonths;
        $computedAmount = round($calc['total_amount'] * $multiplier, 2);

        $gateway = PaymentManager::active();
        $gatewayCode = $gateway->getCode();

        // Build a gateway-agnostic payload for the strategy to verify
        $payload = $gatewayCode === 'payu'
            ? $request->only(['status', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'hash'])
            : $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);

        $transactionId = $payload['razorpay_payment_id'] ?? $payload['txnid'] ?? '';

        if (empty($transactionId)) {
            return response()->json(['success' => false, 'message' => 'No payment reference received from gateway.'], 422);
        }

        // Duplicate protection
        $already = PaymentTransaction::where('transaction_id', $transactionId)
            ->where('status', 'success')->exists();
        if ($already) {
            return response()->json(['success' => false, 'message' => 'This payment was already processed.'], 422);
        }

        if (!$gateway->verifyPayment($payload)) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed. Plan not activated.'], 422);
        }

        $result = $this->subscriptionService->activate($hotel, $plan, $durationMonths, [
            'amount' => $computedAmount,
            'tv_count' => $tvCount,
            'payment_method' => 'online_gateway',
            'gateway_code' => $gatewayCode,
            'transaction_id' => $transactionId,
            'payload' => $payload,
            'notes' => "Online {$gatewayCode} payment: {$transactionId} for {$tvCount} Connected TVs",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription ' . $result['action'] . 'd successfully!',
            'action' => $result['action'],
            'new_expiry' => $result['new_expiry']->format('Y-m-d'),
        ]);
    }

    /**
     * Create payment gateway order for TV Screen Add-On.
     */
    public function createAddonOrder(Request $request)
    {
        $validated = $request->validate([
            'additional_tvs' => 'required|integer|min:1|max:1000',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        if (!$hotel->plan || !$hotel->expiry_date || $hotel->expiry_date->isPast()) {
            return response()->json(['success' => false, 'message' => 'You do not have an active subscription to add TV screens to.'], 422);
        }

        $additionalTvs = (int) $validated['additional_tvs'];
        $pricing = $this->subscriptionService->calculateAddonPricing($hotel, $additionalTvs);
        $amount = (float) $pricing['total_amount'];

        try {
            $gateway = PaymentManager::active();
            if (!$gateway->isEnabled()) {
                return response()->json(['success' => false, 'message' => 'No payment gateway is enabled. Please contact support.'], 422);
            }
            $order = $gateway->createOrder($amount, 'HADDON-' . strtoupper(Str::random(10)), [
                'name' => $hotel->hotel_name ?? '',
                'email' => $hotel->email ?? '',
                'phone' => $hotel->phone ?? '',
                'product_info' => "TV Add-On: +{$additionalTvs} Screens",
                'udf1' => (string) $hotel->id,
                'udf2' => (string) $hotel->plan_id,
                'udf3' => (string) $pricing['remaining_days'],
                'udf4' => (string) $additionalTvs,
                'udf5' => 'hotel_tv_addon',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment init failed: ' . $e->getMessage()], 422);
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
            'name' => $hotel->hotel_name ?? '',
            'email' => $hotel->email ?? '',
            'phone' => $hotel->phone ?? '',
            'additional_tvs' => $additionalTvs,
        ]);
    }

    /**
     * Verify payment and apply TV Screen Add-on for Hotel Admin.
     */
    public function purchaseAddon(Request $request)
    {
        $validated = $request->validate([
            'additional_tvs' => 'required|integer|min:1',
            'razorpay_order_id' => 'required_without:txnid|nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
            'txnid' => 'required_without:razorpay_order_id|nullable|string',
            'hash' => 'nullable|string',
            'status' => 'nullable|string',
            'amount' => 'nullable|string',
            'productinfo' => 'nullable|string',
            'firstname' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        if (!$hotel->plan || !$hotel->expiry_date || $hotel->expiry_date->isPast()) {
            return response()->json(['success' => false, 'message' => 'Active plan required.'], 422);
        }

        $additionalTvs = (int) $validated['additional_tvs'];
        $pricing = $this->subscriptionService->calculateAddonPricing($hotel, $additionalTvs);
        $computedAmount = (float) $pricing['total_amount'];

        $gateway = PaymentManager::active();
        $gatewayCode = $gateway->getCode();

        $payload = $gatewayCode === 'payu'
            ? $request->only(['status', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'hash'])
            : $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);

        $transactionId = $payload['razorpay_payment_id'] ?? $payload['txnid'] ?? '';

        if (empty($transactionId)) {
            return response()->json(['success' => false, 'message' => 'No payment reference received from gateway.'], 422);
        }

        $already = PaymentTransaction::where('transaction_id', $transactionId)
            ->where('status', 'success')->exists();
        if ($already) {
            return response()->json(['success' => false, 'message' => 'This payment was already processed.'], 422);
        }

        if (!$gateway->verifyPayment($payload)) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 422);
        }

        $result = $this->subscriptionService->addTvAddon($hotel, $additionalTvs, [
            'amount' => $computedAmount,
            'payment_method' => 'online_gateway',
            'gateway_code' => $gatewayCode,
            'transaction_id' => $transactionId,
            'payload' => $payload,
            'notes' => "Hotel self-service TV Add-On (+{$additionalTvs} TVs, txn: {$transactionId})",
        ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully added +{$additionalTvs} TV screens! Your property TV capacity is now {$result['new_tv_count']} TVs.",
            'new_tv_count' => $result['new_tv_count'],
        ]);
    }

    /**
     * List all tax invoices for the authenticated hotel admin.
     */
    public function invoices(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        // Auto-sync any legacy sales for this hotel
        $this->syncMissingInvoicesForHotel($hotel);

        $query = \App\Models\Invoice::with(['plan', 'tax', 'distributor'])
            ->where('hotel_admin_id', $hotel->id)
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('invoice_number', 'LIKE', "%{$term}%")
                  ->orWhere('plan_name', 'LIKE', "%{$term}%")
                  ->orWhere('transaction_id', 'LIKE', "%{$term}%");
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        $allHotelInvoices = \App\Models\Invoice::where('hotel_admin_id', $hotel->id)->get(['id', 'total_amount', 'payment_status']);
        $stats = [
            'total_invoiced' => (float) $allHotelInvoices->sum('total_amount'),
            'total_paid' => (float) $allHotelInvoices->where('payment_status', 'paid')->sum('total_amount'),
            'invoices_count' => $allHotelInvoices->count(),
        ];

        return view('hotel_admin.invoices.index', compact('hotel', 'invoices', 'stats'));
    }

    /**
     * View and print a specific tax invoice for the authenticated hotel admin.
     */
    public function showInvoice($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $invoice = \App\Models\Invoice::with(['hotel', 'plan', 'tax', 'distributor'])
            ->where('hotel_admin_id', $hotel->id)
            ->findOrFail($id);

        return view('hotel_admin.invoices.show', compact('hotel', 'invoice'));
    }

    /**
     * Automatically backfill missing invoices for past sales belonging to this hotel.
     */
    private function syncMissingInvoicesForHotel($hotel): void
    {
        try {
            $sales = \App\Models\DistributorSale::where('hotel_id', $hotel->id)->with(['plan.tax'])->get();
            if ($sales->isEmpty()) return;

            $existingInvoices = \App\Models\Invoice::where('hotel_admin_id', $hotel->id)->get();

            foreach ($sales as $sale) {
                $hasInvoice = $existingInvoices->first(function ($inv) use ($sale) {
                    if ($inv->transaction_id === ('DSALE-' . $sale->id)) return true;
                    if ((float) $inv->total_amount == (float) $sale->amount && abs($inv->created_at->diffInHours($sale->created_at)) <= 48) {
                        return true;
                    }
                    return false;
                });

                if (!$hasInvoice) {
                    $plan = $sale->plan;
                    $tvCount = (int) ($hotel->room_count ?: 1);
                    $ratePerTv = $plan ? (float) $plan->effective_price_per_tv : 150.00;
                    $tax = $plan ? $plan->tax : \App\Models\Tax::where('is_default', true)->first();
                    $taxRate = $tax ? (float) $tax->rate : 18.00;
                    $taxName = $tax ? $tax->name : 'GST';
                    $taxType = $plan ? ($plan->tax_type ?? 'exclusive') : 'exclusive';

                    $total = (float) $sale->amount;
                    $subtotal = $taxType === 'inclusive' ? round($total / (1 + ($taxRate / 100)), 2) : $total;
                    $taxAmount = round($total - $subtotal, 2);
                    $cgst = round($taxAmount / 2, 2);
                    $sgst = round($taxAmount - $cgst, 2);

                    $newInv = \App\Models\Invoice::create([
                        'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                        'hotel_admin_id' => $hotel->id,
                        'distributor_id' => $sale->distributor_id,
                        'plan_id' => $sale->plan_id,
                        'plan_name' => $plan ? $plan->name : 'Smart TV Licensing Tier',
                        'billing_cycle' => $plan ? ($plan->billing_cycle ?? 'monthly') : 'monthly',
                        'tv_count' => $tvCount,
                        'price_per_tv' => $ratePerTv,
                        'subtotal' => $subtotal,
                        'tax_id' => $tax ? $tax->id : null,
                        'tax_name' => $taxName,
                        'tax_rate' => $taxRate,
                        'tax_type' => $taxType,
                        'tax_amount' => $taxAmount,
                        'cgst_amount' => $cgst,
                        'sgst_amount' => $sgst,
                        'total_amount' => $total,
                        'payment_status' => in_array($sale->payment_status, ['failed']) ? 'failed' : 'paid',
                        'payment_method' => $sale->payment_method ?? 'online_gateway',
                        'transaction_id' => 'DSALE-' . $sale->id,
                        'paid_at' => $sale->created_at,
                        'period_start' => $sale->created_at,
                        'period_end' => $hotel->expiry_date ?: $sale->created_at->copy()->addMonth(),
                        'notes' => $sale->notes ?: "Package sale: " . ($plan ? $plan->name : 'Plan'),
                        'created_at' => $sale->created_at,
                    ]);

                    $existingInvoices->push($newInv);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Hotel Invoice backfill skipped: " . $e->getMessage());
        }
    }
}
