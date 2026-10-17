<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Models\PaymentTransaction;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function activate(HotelAdmin $hotel, Plan $plan, int $durationMonths, array $context = []): array
    {
        $now = now();
        $durationDays = $durationMonths * 30;

        $isFirstPurchase = !$hotel->payment_status || $hotel->payment_status !== 'paid' || !$hotel->expiry_date;
        $hasActivePlan = $hotel->expiry_date && $hotel->expiry_date->gt($now);

        $baseDate = $hasActivePlan ? $hotel->expiry_date : $now;
        $newExpiry = $baseDate->copy()->addDays($durationDays);

        if ($isFirstPurchase) {
            $action = 'purchase';
        } elseif (!$hasActivePlan) {
            $action = 'renewal';
        } elseif ($hotel->plan_id && $hotel->plan_id !== $plan->id) {
            $action = 'upgrade';
        } else {
            $action = 'renewal';
        }

        $roomCount = (new HotelService())->resolveRoomCount($hotel->room_count, $plan, (int) $plan->room_count);

        $licenseKey = $hotel->license_key;
        if (empty($licenseKey)) {
            $licenseKey = sprintf(
                '%s-%s-%s-%s',
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        }
        $hotel->update([
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'purchase_date' => $now,
            'expiry_date' => $newExpiry,
            'license_key' => $licenseKey,
            'room_count' => $roomCount,
            'auto_renew' => $context['auto_renew'] ?? false,
            'renewal_type' => ($context['auto_renew'] ?? false) ? 'auto' : 'manual',
        ]);

        $sale = DistributorSale::create([
            'distributor_id' => $context['distributor_id'] ?? $hotel->distributor_id,
            'hotel_id' => $hotel->id,
            'plan_id' => $plan->id,
            'amount' => $context['amount'] ?? $plan->price,
            'payment_status' => 'completed',
            'payment_method' => $context['payment_method'] ?? 'hotel_direct',
            'license_key_issued' => $licenseKey,
            'notes' => $context['notes'] ?? ucfirst($action) . ": {$plan->name} ({$durationMonths} months)",
        ]);

        if (!empty($context['gateway_code'])) {
            PaymentTransaction::create([
                'distributor_id' => $context['distributor_id'] ?? $hotel->distributor_id,
                'hotel_admin_id' => $hotel->id,
                'gateway_code' => $context['gateway_code'],
                'transaction_id' => (string) ($context['transaction_id'] ?? ('txn_' . Str::random(16))),
                'amount' => $context['amount'] ?? $plan->price,
                'currency' => 'INR',
                'status' => 'success',
                'payload' => $context['payload'] ?? null,
                'plan_id' => $plan->id,
                'paid_at' => now(),
                'idempotency_checked' => true,
            ]);
        }

        return [
            'hotel' => $hotel->fresh(['plan']),
            'sale' => $sale,
            'new_expiry' => $newExpiry,
            'action' => $action,
        ];
    }
}