<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\HotelAdmin;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentOrderService
{
    /**
     * Create a gateway checkout order and persist a server-bound pending transaction
     * so amount, plan, TV count, duration, and target entity cannot be tampered later.
     */
    public function createPendingOrder(
        PaymentGatewayInterface $gateway,
        float $amount,
        string $receiptPrefix,
        array $customerDetails,
        array $context
    ): array {
        $amount = round($amount, 2);
        $receiptId = $receiptPrefix . strtoupper(Str::random(10));

        $order = $gateway->createOrder($amount, $receiptId, $customerDetails);
        $orderId = (string) ($order['order_id'] ?? $receiptId);

        PaymentTransaction::create([
            'hotel_admin_id' => $context['hotel_admin_id'] ?? null,
            'distributor_id' => $context['distributor_id'] ?? null,
            'gateway_code' => $gateway->getCode(),
            'transaction_id' => $orderId,
            'amount' => $amount,
            'currency' => $order['currency'] ?? 'INR',
            'status' => 'pending',
            'plan_id' => isset($context['plan_id']) ? (string) $context['plan_id'] : null,
            'idempotency_checked' => false,
            'payload' => array_merge($context, [
                'order_id' => $orderId,
                'receipt_id' => $receiptId,
                'expected_amount' => $amount,
            ]),
        ]);

        return $order;
    }

    /**
     * Atomically verify gateway signature, bind against any server-created pending order,
     * block replay/cross-tenant/cross-purpose reuse, and mark the transaction consumed/succeeded.
     *
     * Must be executed before activating subscriptions, issuing licenses, or crediting commissions.
     *
     * @return array{transaction_id: string, order_id: string, amount: float, payload: array, pending_payload: array, already_recorded: bool}
     */
    public function consumeVerifiedPayment(
        PaymentGatewayInterface $gateway,
        string $orderId,
        string $paymentReference,
        array $verificationPayload,
        string $expectedPurpose,
        array $expectations = []
    ): array {
        $orderId = trim($orderId);
        $paymentReference = trim($paymentReference);
        $gatewayCode = $gateway->getCode();

        if ($orderId === '' && $paymentReference === '') {
            throw new RuntimeException('Missing payment gateway order or transaction reference.');
        }

        // Check if gateway verification passes (allowing explicit mock orders ONLY in local/testing)
        $isDevMock = app()->environment(['local', 'testing']) && (
            str_starts_with($orderId, 'order_mock_') ||
            str_starts_with($orderId, 'txnid_mock_')
        );

        if ($gateway->isEnabled()) {
            $isVerified = $gateway->verifyPayment($verificationPayload);
            if (!$isVerified && !$isDevMock) {
                throw new RuntimeException('Payment signature verification failed via ' . $gateway->getName() . '.');
            }
        } elseif (!$isDevMock) {
            throw new RuntimeException('Payment gateway is not enabled.');
        }

        return DB::transaction(function () use (
            $gatewayCode,
            $orderId,
            $paymentReference,
            $verificationPayload,
            $expectedPurpose,
            $expectations
        ) {
            $lookupIds = array_values(array_unique(array_filter([$orderId, $paymentReference])));

            $lockedRows = PaymentTransaction::whereIn('transaction_id', $lookupIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('transaction_id');

            foreach ($lockedRows as $row) {
                if (in_array($row->status, ['success', 'consumed'], true) || $row->idempotency_checked) {
                    throw new RuntimeException('This payment transaction has already been processed.');
                }
            }

            /** @var ?PaymentTransaction $pendingTx */
            $pendingTx = $orderId !== '' ? ($lockedRows->get($orderId) ?? null) : null;
            if (!$pendingTx && $paymentReference !== '') {
                $pendingTx = $lockedRows->get($paymentReference);
            }

            if ($pendingTx && $pendingTx->status !== 'pending') {
                throw new RuntimeException('This payment order is no longer pending (status: ' . $pendingTx->status . ').');
            }

            // Require a server-created pending order in all non-testing environments
            if (!$pendingTx && !app()->environment('testing')) {
                throw new RuntimeException('Invalid or expired payment order reference. Please start a fresh checkout.');
            }

            $pendingPayload = is_array($pendingTx?->payload) ? $pendingTx->payload : [];

            if ($pendingTx) {
                if ($pendingTx->gateway_code && $pendingTx->gateway_code !== $gatewayCode) {
                    throw new RuntimeException('Payment gateway mismatch for this order.');
                }

                if (!empty($pendingPayload['purpose']) && $pendingPayload['purpose'] !== $expectedPurpose) {
                    throw new RuntimeException('Payment order purpose mismatch.');
                }

                if (isset($expectations['hotel_admin_id']) && $pendingTx->hotel_admin_id !== null) {
                    if ((int) $pendingTx->hotel_admin_id !== (int) $expectations['hotel_admin_id']) {
                        throw new RuntimeException('Payment order belongs to a different hotel account.');
                    }
                }

                if (isset($expectations['distributor_id']) && $pendingTx->distributor_id !== null) {
                    if ((int) $pendingTx->distributor_id !== (int) $expectations['distributor_id']) {
                        throw new RuntimeException('Payment order belongs to a different distributor account.');
                    }
                }

                if (isset($expectations['plan_id']) && $pendingTx->plan_id !== null) {
                    if ((int) $pendingTx->plan_id !== (int) $expectations['plan_id']) {
                        throw new RuntimeException('Selected plan does not match the initialized payment order.');
                    }
                }

                $authoritativeAmount = (float) $pendingTx->amount;
                if (isset($expectations['expected_amount'])) {
                    if (abs((float) $expectations['expected_amount'] - $authoritativeAmount) > 0.05) {
                        throw new RuntimeException('Checkout parameters were tampered with after order initialization.');
                    }
                }

                if (isset($verificationPayload['amount']) && is_numeric($verificationPayload['amount'])) {
                    if ((float) $verificationPayload['amount'] + 0.05 < $authoritativeAmount) {
                        throw new RuntimeException('Gateway paid amount is less than the required order total.');
                    }
                }
            } else {
                // Testing environment direct-signature fallback (e.g. direct callback/unit test without createOrder)
                $authoritativeAmount = isset($expectations['expected_amount'])
                    ? (float) $expectations['expected_amount']
                    : (float) ($verificationPayload['amount'] ?? 0);

                if (isset($verificationPayload['amount']) && is_numeric($verificationPayload['amount']) && isset($expectations['expected_amount'])) {
                    if ((float) $verificationPayload['amount'] + 0.05 < (float) $expectations['expected_amount']) {
                        throw new RuntimeException('Gateway paid amount is less than the required order total.');
                    }
                }
            }

            $finalTransactionId = $paymentReference !== '' ? $paymentReference : $orderId;
            $safePayload = array_merge($pendingPayload, $verificationPayload, [
                'order_id' => $orderId,
                'payment_id' => $finalTransactionId,
                'purpose' => $expectedPurpose,
            ]);

            if ($pendingTx) {
                if ($pendingTx->transaction_id === $finalTransactionId) {
                    $pendingTx->update([
                        'status' => 'success',
                        'idempotency_checked' => true,
                        'paid_at' => now(),
                        'payload' => $safePayload,
                    ]);
                } else {
                    // Mark pending order row consumed so the Razorpay order_id cannot be replayed,
                    // while recording one single 'success' row under the payment reference.
                    $pendingTx->update([
                        'status' => 'consumed',
                        'idempotency_checked' => true,
                        'paid_at' => now(),
                        'payload' => $safePayload,
                    ]);

                    PaymentTransaction::create([
                        'hotel_admin_id' => $expectations['hotel_admin_id'] ?? $pendingTx->hotel_admin_id,
                        'distributor_id' => $expectations['distributor_id'] ?? $pendingTx->distributor_id,
                        'gateway_code' => $gatewayCode,
                        'transaction_id' => $finalTransactionId,
                        'amount' => $authoritativeAmount,
                        'currency' => $pendingTx->currency ?: 'INR',
                        'status' => 'success',
                        'plan_id' => (string) ($expectations['plan_id'] ?? $pendingTx->plan_id ?? ''),
                        'paid_at' => now(),
                        'idempotency_checked' => true,
                        'payload' => $safePayload,
                    ]);
                }
            } else {
                PaymentTransaction::create([
                    'hotel_admin_id' => $expectations['hotel_admin_id'] ?? null,
                    'distributor_id' => $expectations['distributor_id'] ?? null,
                    'gateway_code' => $gatewayCode,
                    'transaction_id' => $finalTransactionId,
                    'amount' => $authoritativeAmount,
                    'currency' => 'INR',
                    'status' => 'success',
                    'plan_id' => isset($expectations['plan_id']) ? (string) $expectations['plan_id'] : null,
                    'paid_at' => now(),
                    'idempotency_checked' => true,
                    'payload' => $safePayload,
                ]);
            }

            return [
                'transaction_id' => $finalTransactionId,
                'order_id' => $orderId,
                'amount' => $authoritativeAmount,
                'payload' => $safePayload,
                'pending_payload' => $pendingPayload,
                'already_recorded' => true,
            ];
        });
    }

    /**
     * Record a failed payment attempt safely without overwriting an already succeeded order
     * or colliding on UNIQUE(transaction_id).
     */
    public function recordFailedAttempt(
        string $gatewayCode,
        string $referenceId,
        float $amount,
        array $payload = [],
        ?int $hotelAdminId = null,
        ?int $distributorId = null,
        $planId = null
    ): void {
        $referenceId = trim($referenceId);
        if ($referenceId === '') {
            $referenceId = 'failed_' . Str::random(12);
        }

        $existing = PaymentTransaction::where('transaction_id', $referenceId)->first();
        if ($existing) {
            if (in_array($existing->status, ['success', 'consumed'], true)) {
                return;
            }
            if ($existing->status === 'pending') {
                $existing->update([
                    'status' => 'failed',
                    'payload' => array_merge((array) $existing->payload, $payload),
                ]);
                return;
            }
            // Previous failed attempt already exists with same referenceId — append suffix
            $referenceId = $referenceId . '_retry_' . Str::random(6);
        }

        PaymentTransaction::create([
            'hotel_admin_id' => $hotelAdminId,
            'distributor_id' => $distributorId,
            'gateway_code' => $gatewayCode,
            'transaction_id' => $referenceId,
            'amount' => round($amount, 2),
            'currency' => 'INR',
            'status' => 'failed',
            'payload' => $payload,
            'plan_id' => $planId !== null ? (string) $planId : null,
        ]);
    }

    /**
     * Authoritative subscription pricing calculation shared across checkout endpoints.
     *
     * @return array{subtotal: float, tax_amount: float, total_amount: float, rate_per_tv: float, tax: ?Tax, tax_type: string}
     */
    public function calculateSubscriptionTotal(Plan $plan, int $tvCount, int $durationMonths): array
    {
        $tvCount = max(1, $tvCount);
        $durationMonths = max(1, $durationMonths);
        $plan->loadMissing('tax');

        $ratePerTv = (float) $plan->effective_price_per_tv;
        $isYearly = ($plan->billing_cycle === 'yearly');
        $multiplier = $isYearly ? max(1, round($durationMonths / 12, 2)) : $durationMonths;
        $billedPerTv = (float) $plan->billed_amount_per_tv;
        $subtotal = round($billedPerTv * $tvCount * $multiplier, 2);

        $tax = $plan->tax ?: Tax::where('status', true)->where('is_default', true)->first();
        $taxType = $plan->tax_type ?? 'exclusive';

        if ($tax && (float) $tax->rate > 0) {
            $calc = $tax->calculateTax($subtotal, $taxType);
            return [
                'subtotal' => (float) $calc['subtotal'],
                'tax_amount' => (float) $calc['tax_amount'],
                'total_amount' => (float) $calc['total_amount'],
                'rate_per_tv' => $ratePerTv,
                'tax' => $tax,
                'tax_type' => $taxType,
            ];
        }

        return [
            'subtotal' => $subtotal,
            'tax_amount' => 0.0,
            'total_amount' => $subtotal,
            'rate_per_tv' => $ratePerTv,
            'tax' => null,
            'tax_type' => $taxType,
        ];
    }
}
