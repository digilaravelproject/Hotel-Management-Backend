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
        $txnid = trim((string) ($payload['txnid'] ?? $request->input('txnid') ?? ''));
        $status = strtolower((string) ($payload['status'] ?? ''));

        if ($txnid === '' || $status !== 'success') {
            return $this->failure($request, 'Payment verification failed. Invalid callback status or transaction ID.');
        }

        $pendingTx = PaymentTransaction::where('transaction_id', $txnid)->first();
        if ($pendingTx && in_array($pendingTx->status, ['success', 'consumed'], true)) {
            $type = (string) ($pendingTx->payload['purpose'] ?? $payload['udf5'] ?? 'distributor_sale');
            return $this->redirectToSource($type, 'This transaction was already processed successfully.');
        }

        $type = (string) ($pendingTx?->payload['purpose'] ?? $payload['udf5'] ?? 'distributor_sale');
        $hotelId = (int) ($pendingTx?->hotel_admin_id ?? $payload['udf1'] ?? 0);
        $planId = (int) ($pendingTx?->plan_id ?? $payload['udf2'] ?? 0);
        $durationMonths = max(1, (int) ($pendingTx?->payload['duration_months'] ?? $payload['udf3'] ?? 1));
        $distributorId = (int) ($pendingTx?->distributor_id ?? $payload['udf4'] ?? 0);

        $hotel = $hotelId > 0 ? HotelAdmin::find($hotelId) : null;
        $plan = $planId > 0 ? Plan::with('tax')->find($planId) : null;

        if (!$hotel || !$plan) {
            return $this->failure($request, 'Unable to match payment callback to a valid hotel and subscription plan.');
        }

        $paymentOrderService = app(\App\Services\PaymentOrderService::class);
        $tvCount = max(1, (int) ($pendingTx?->payload['tv_count'] ?? $hotel->room_count ?: ($plan->room_count ?: 1)));
        $expectedAmount = $pendingTx ? (float) $pendingTx->amount : null;

        try {
            $gateway = PaymentManager::resolve('payu');

            \Illuminate\Support\Facades\DB::transaction(function () use (
                $paymentOrderService,
                $gateway,
                $txnid,
                $payload,
                $type,
                $hotel,
                $plan,
                $durationMonths,
                $distributorId,
                $expectedAmount,
                $pendingTx
            ) {
                $expectations = [
                    'hotel_admin_id' => $hotel->id,
                    'plan_id' => $plan->id,
                ];
                if ($expectedAmount !== null) {
                    $expectations['expected_amount'] = $expectedAmount;
                }
                if ($type === 'distributor_sale' && $distributorId > 0) {
                    $expectations['distributor_id'] = $distributorId;
                }

                $consumed = $paymentOrderService->consumeVerifiedPayment(
                    $gateway,
                    $txnid,
                    $txnid,
                    $payload,
                    $type,
                    $expectations
                );

                if ($type === 'hotel_tv_addon') {
                    $additionalTvs = max(1, (int) ($pendingTx?->payload['additional_tvs'] ?? $payload['udf4'] ?? 1));
                    $this->subscriptionService->addTvAddon($hotel, $additionalTvs, [
                        'amount' => $consumed['amount'],
                        'payment_method' => 'online_gateway',
                        'gateway_code' => 'payu',
                        'transaction_id' => $txnid,
                        'payload' => $payload,
                        'skip_payment_tx_record' => true,
                        'notes' => "PayU Online TV Add-On: {$txnid}",
                    ]);
                } else {
                    $this->subscriptionService->activate($hotel, $plan, $durationMonths, [
                        'tv_count' => max(1, (int) ($pendingTx?->payload['tv_count'] ?? $hotel->room_count ?: ($plan->room_count ?: 1))),
                        'distributor_id' => ($type === 'distributor_sale' && $distributorId > 0) ? $distributorId : null,
                        'amount' => $consumed['amount'],
                        'payment_method' => 'online_gateway',
                        'gateway_code' => 'payu',
                        'transaction_id' => $txnid,
                        'payload' => $payload,
                        'skip_payment_tx_record' => true,
                        'notes' => ($type === 'distributor_sale' ? 'PayU Verified: ' : 'PayU Online Payment: ') . $txnid,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::warning('PayU Payment Callback Rejected', [
                'txnid' => $txnid,
                'status' => $status,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            return $this->failure($request, 'Payment verification failed. Security hash or order validation mismatch.');
        }

        return $this->redirectToSource(
            $type,
            "Payment successful via PayU! Package '{$plan->name}' has been activated."
        );
    }

    /**
     * Handle PayU / External Gateway failed payment callback.
     */
    public function failure(Request $request, ?string $customMsg = null)
    {
        $payload = $request->all();
        $txnid = trim((string) ($payload['txnid'] ?? $request->input('txnid') ?? ''));
        $type = (string) ($payload['udf5'] ?? 'distributor_sale');
        $errorMsg = $customMsg ?: ($payload['error_Message'] ?? $payload['error'] ?? 'Payment failed or cancelled. No plan was activated.');

        if ($txnid !== '') {
            app(\App\Services\PaymentOrderService::class)->recordFailedAttempt(
                'payu',
                $txnid,
                (float) ($payload['amount'] ?? 0),
                $payload,
                !empty($payload['udf1']) ? (int) $payload['udf1'] : null,
                !empty($payload['udf4']) ? (int) $payload['udf4'] : null,
                $payload['udf2'] ?? null
            );
        }

        if ($type === 'hotel_subscription' || $type === 'hotel_tv_addon') {
            return redirect()->route('hotel.subscription')->with('error', $errorMsg);
        }

        return redirect()->route('distributor.sales.index')->with('error', $errorMsg);
    }

    protected function redirectToSource(string $type, string $message)
    {
        if ($type === 'hotel_subscription' || $type === 'hotel_tv_addon') {
            return redirect()->route('hotel.subscription')->with('success', $message);
        }

        return redirect()->route('distributor.sales.index')->with('success', $message);
    }
}
