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
        $hotel->loadMissing('plan');
        $plans = Plan::where('status', true)->orderBy('price')->get();
        $gateway = PaymentManager::active();

        return view('hotel_admin.subscription', compact('hotel', 'plans', 'gateway'));
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'required|integer|in:1,3,6,12',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        $plan = Plan::findOrFail($validated['plan_id']);
        $amount = round((float) $plan->price * (int) $validated['duration_months'], 2);

        try {
            $gateway = PaymentManager::active();
            if (!$gateway->isEnabled()) {
                return response()->json(['success' => false, 'message' => 'No payment gateway is enabled. Please contact support.'], 422);
            }
            $order = $gateway->createOrder($amount, 'HPLAN-' . strtoupper(Str::random(10)), [
                'name' => $hotel->hotel_name ?? '',
                'email' => $hotel->email ?? '',
                'phone' => $hotel->phone ?? '',
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
            'duration_months' => 'required|integer|in:1,3,6,12',
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
        $plan = Plan::findOrFail($validated['plan_id']);
        $computedAmount = round((float) $plan->price * (int) $validated['duration_months'], 2);

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

        $result = $this->subscriptionService->activate($hotel, $plan, (int) $validated['duration_months'], [
            'amount' => $computedAmount,
            'payment_method' => 'online_gateway',
            'gateway_code' => $gatewayCode,
            'transaction_id' => $transactionId,
            'payload' => $payload,
            'notes' => "Online {$gatewayCode} payment: {$transactionId}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription ' . $result['action'] . 'd successfully!',
            'action' => $result['action'],
            'new_expiry' => $result['new_expiry']->format('Y-m-d'),
        ]);
    }
}
