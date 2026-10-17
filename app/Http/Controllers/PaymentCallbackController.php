<?php

namespace App\Http\Controllers;

use App\Models\HotelAdmin;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Services\HotelService;
use App\Services\PaymentManager;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected HotelService $hotelService
    ) {}

    /**
     * Handle PayU / External Gateway successful payment callback.
     */
    public function success(Request $request)
    {
        $payload = $request->all();
        $txnid = $payload['txnid'] ?? $request->input('txnid');
        $status = strtolower($payload['status'] ?? '');

        // Verify PayU signature / reverse hash
        try {
            $gateway = PaymentManager::resolve('payu');
            $isVerified = $gateway->verifyPayment($payload);
        } catch (\Exception $e) {
            $isVerified = false;
        }

        if (!$isVerified && $status !== 'success') {
            return $this->failure($request, 'Payment verification failed. Security hash mismatch.');
        }

        $type = $payload['udf5'] ?? 'distributor_sale';
        $hotelId = $payload['udf1'] ?? null;
        $planId = $payload['udf2'] ?? null;
        $durationMonths = (int) ($payload['udf3'] ?? 1);
        $distributorId = $payload['udf4'] ?? null;
        $amount = (float) ($payload['amount'] ?? 0);

        // Idempotency: avoid double-activation
        if ($txnid) {
            $already = PaymentTransaction::where('transaction_id', $txnid)
                ->where('status', 'success')
                ->exists();

            if ($already) {
                return $this->redirectToSource($type, 'This transaction was already processed successfully.');
            }
        }

        if ($hotelId && $planId) {
            $hotel = HotelAdmin::find($hotelId);
            $plan = Plan::find($planId);

            if ($hotel && $plan) {
                if ($type === 'distributor_sale' && $distributorId) {
                    $this->hotelService->assignPlanAndRecordSale(
                        $hotel,
                        $plan,
                        $durationMonths,
                        [
                            'payment_method' => 'online_gateway',
                            'amount' => $amount,
                            'notes' => "PayU Verified: {$txnid}",
                        ],
                        (int) $distributorId
                    );
                } else {
                    $this->subscriptionService->activate($hotel, $plan, $durationMonths, [
                        'amount' => $amount,
                        'payment_method' => 'online_gateway',
                        'gateway_code' => 'payu',
                        'transaction_id' => $txnid,
                        'payload' => $payload,
                        'notes' => "PayU Online Payment: {$txnid}",
                    ]);
                }

                PaymentTransaction::create([
                    'distributor_id' => ($type === 'distributor_sale') ? $distributorId : null,
                    'hotel_admin_id' => $hotel->id,
                    'gateway_code' => 'payu',
                    'transaction_id' => (string) ($txnid ?: 'payu_' . time()),
                    'amount' => $amount,
                    'currency' => 'INR',
                    'status' => 'success',
                    'payload' => $payload,
                    'plan_id' => $plan->id,
                    'paid_at' => now(),
                    'idempotency_checked' => true,
                ]);

                return $this->redirectToSource(
                    $type,
                    "Payment successful via PayU! Package '{$plan->name}' has been activated."
                );
            }
        }

        return $this->redirectToSource($type, 'Payment completed successfully via PayU.');
    }

    /**
     * Handle PayU / External Gateway failed payment callback.
     */
    public function failure(Request $request, ?string $customMsg = null)
    {
        $payload = $request->all();
        $txnid = $payload['txnid'] ?? $request->input('txnid');
        $type = $payload['udf5'] ?? 'distributor_sale';
        $errorMsg = $customMsg ?: ($payload['error_Message'] ?? $payload['error'] ?? 'Payment failed or cancelled. No plan was activated.');

        if ($txnid) {
            PaymentTransaction::create([
                'distributor_id' => $payload['udf4'] ?? null,
                'hotel_admin_id' => $payload['udf1'] ?? null,
                'gateway_code' => 'payu',
                'transaction_id' => (string) $txnid,
                'amount' => (float) ($payload['amount'] ?? 0),
                'currency' => 'INR',
                'status' => 'failed',
                'payload' => $payload,
                'plan_id' => $payload['udf2'] ?? null,
            ]);
        }

        if ($type === 'hotel_subscription') {
            return redirect()->route('hotel.subscription')->with('error', $errorMsg);
        }

        return redirect()->route('distributor.sales.index')->with('error', $errorMsg);
    }

    protected function redirectToSource(string $type, string $message)
    {
        if ($type === 'hotel_subscription') {
            return redirect()->route('hotel.subscription')->with('success', $message);
        }

        return redirect()->route('distributor.sales.index')->with('success', $message);
    }
}
