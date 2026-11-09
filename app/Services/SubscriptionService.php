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

        $distributorId = $context['distributor_id'] ?? $hotel->distributor_id;
        $sale = null;

        // Record DistributorSale only if the hotel belongs to a distributor franchise
        if (!empty($distributorId)) {
            $sale = DistributorSale::create([
                'distributor_id' => $distributorId,
                'hotel_id' => $hotel->id,
                'plan_id' => $plan->id,
                'amount' => $context['amount'] ?? $plan->price,
                'payment_status' => 'completed',
                'payment_method' => $context['payment_method'] ?? 'hotel_direct',
                'license_key_issued' => $licenseKey,
                'notes' => $context['notes'] ?? ucfirst($action) . ": {$plan->name} ({$durationMonths} months)",
            ]);
        }

        // Generate Tax-Compliant Invoice
        $invoice = null;
        try {
            $tvCount = (int) ($context['tv_count'] ?? $hotel->room_count ?? $roomCount ?? 1);
            $unitRate = (float) $plan->effective_price_per_tv;
            $isYearly = ($plan->billing_cycle === 'yearly');
            $multiplier = $isYearly ? max(1, round($durationMonths / 12, 2)) : $durationMonths;
            $baseSubtotal = round($unitRate * $tvCount * $multiplier, 2);

            $tax = $plan->tax;
            $taxType = $plan->tax_type ?? 'exclusive';
            $taxRate = $tax ? (float) $tax->rate : 0.0;

            $taxAmount = 0.00;
            $totalAmount = $baseSubtotal;
            $cgst = 0.00;
            $sgst = 0.00;

            if ($taxRate > 0) {
                if ($taxType === 'inclusive') {
                    $taxAmount = round($baseSubtotal - ($baseSubtotal / (1 + ($taxRate / 100))), 2);
                    $totalAmount = $baseSubtotal;
                    $baseSubtotal = round($baseSubtotal - $taxAmount, 2);
                } else {
                    $taxAmount = round($baseSubtotal * ($taxRate / 100), 2);
                    $totalAmount = round($baseSubtotal + $taxAmount, 2);
                }
                if ($tax && $tax->is_split) {
                    $cgst = round($taxAmount / 2, 2);
                    $sgst = round($taxAmount - $cgst, 2);
                }
            }

            if (!empty($context['amount'])) {
                $totalAmount = (float) $context['amount'];
            }

            $invoice = \App\Models\Invoice::create([
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'hotel_admin_id' => $hotel->id,
                'distributor_id' => $distributorId,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'billing_cycle' => $plan->billing_cycle ?? 'monthly',
                'tv_count' => $tvCount,
                'price_per_tv' => $plan->effective_price_per_tv,
                'subtotal' => $baseSubtotal,
                'tax_id' => $plan->tax_id,
                'tax_name' => $tax ? $tax->name : null,
                'tax_rate' => $tax ? $tax->rate : 0.00,
                'tax_type' => $plan->tax_type ?? 'exclusive',
                'tax_amount' => $taxAmount,
                'cgst_amount' => $cgst,
                'sgst_amount' => $sgst,
                'total_amount' => $totalAmount,
                'payment_status' => 'paid',
                'payment_method' => $context['payment_method'] ?? 'hotel_direct',
                'paid_at' => $now,
                'period_start' => $now,
                'period_end' => $newExpiry,
                'notes' => ucfirst($action) . " subscription for {$tvCount} Connected TVs ({$plan->name})"
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Invoice auto-generation skipped or failed: " . $e->getMessage());
        }

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

        // Automated Subscription Purchase / Renewal / Upgrade Notification Email
        try {
            $mailSetting = \App\Models\MailSetting::getSettings();
            if ($mailSetting->notify_subscription_purchased && !empty($hotel->email)) {
                $mailSetting->applyConfig();
                $details = [
                    'action' => $action,
                    'amount' => $context['amount'] ?? ($invoice ? $invoice->total_amount : $plan->price),
                    'payment_method' => $context['payment_method'] ?? 'Online / Gateway',
                    'transaction_id' => $context['transaction_id'] ?? null,
                    'duration_months' => $durationMonths,
                ];
                \Illuminate\Support\Facades\Mail::to($hotel->email)
                    ->send(new \App\Mail\SubscriptionPurchasedMail($hotel->fresh(['plan']), $plan, $details));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send Subscription confirmation email to {$hotel->email}: " . $e->getMessage());
        }

        return [
            'hotel' => $hotel->fresh(['plan']),
            'sale' => $sale,
            'new_expiry' => $newExpiry,
            'action' => $action,
        ];
    }

    /**
     * Calculate prorated pricing for adding extra TV screens to an active subscription.
     */
    public function calculateAddonPricing(HotelAdmin $hotel, int $additionalTvs): array
    {
        $additionalTvs = max(1, $additionalTvs);
        $hotel->loadMissing('plan.tax');
        $plan = $hotel->plan;

        if (!$plan) {
            throw new \InvalidArgumentException("Hotel does not have an active subscription plan assigned.");
        }

        $now = now();
        $hasActivePlan = $hotel->expiry_date && $hotel->expiry_date->gt($now);
        $remainingDays = $hasActivePlan ? max(1, (int) $now->diffInDays($hotel->expiry_date, false)) : 30;

        $cycleDays = ($plan->billing_cycle === 'yearly') ? 365 : 30;
        $dailyRatePerTv = $plan->effective_price_per_tv / $cycleDays;

        $subtotal = round($dailyRatePerTv * $additionalTvs * $remainingDays, 2);

        $tax = $plan->tax;
        $taxType = $plan->tax_type ?? 'exclusive';

        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            return [
                'hotel_id' => $hotel->id,
                'hotel_name' => $hotel->hotel_name,
                'current_tv_count' => (int) ($hotel->room_count ?: 0),
                'additional_tvs' => $additionalTvs,
                'new_tv_count' => (int) ($hotel->room_count ?: 0) + $additionalTvs,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'rate_per_tv' => $plan->effective_price_per_tv,
                'daily_rate_per_tv' => round($dailyRatePerTv, 4),
                'remaining_days' => $remainingDays,
                'expiry_date' => $hotel->expiry_date ? $hotel->expiry_date->format('Y-m-d') : null,
                'subtotal' => $subtotal,
                'tax_id' => $tax->id,
                'tax_name' => $tax->name,
                'tax_rate' => (float) $tax->rate,
                'tax_type' => $taxType,
                'tax_amount' => $taxCalc['tax_amount'],
                'cgst_amount' => $taxCalc['cgst_amount'],
                'sgst_amount' => $taxCalc['sgst_amount'],
                'total_amount' => $taxCalc['total_amount'],
            ];
        }

        return [
            'hotel_id' => $hotel->id,
            'hotel_name' => $hotel->hotel_name,
            'current_tv_count' => (int) ($hotel->room_count ?: 0),
            'additional_tvs' => $additionalTvs,
            'new_tv_count' => (int) ($hotel->room_count ?: 0) + $additionalTvs,
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'rate_per_tv' => $plan->effective_price_per_tv,
            'daily_rate_per_tv' => round($dailyRatePerTv, 4),
            'remaining_days' => $remainingDays,
            'expiry_date' => $hotel->expiry_date ? $hotel->expiry_date->format('Y-m-d') : null,
            'subtotal' => $subtotal,
            'tax_id' => null,
            'tax_name' => 'No Tax',
            'tax_rate' => 0.0,
            'tax_type' => $taxType,
            'tax_amount' => 0.0,
            'cgst_amount' => 0.0,
            'sgst_amount' => 0.0,
            'total_amount' => $subtotal,
        ];
    }

    /**
     * Apply TV screen capacity Add-on to an existing hotel subscription.
     */
    public function addTvAddon(HotelAdmin $hotel, int $additionalTvs, array $context = []): array
    {
        $hotel->loadMissing('plan.tax');
        $plan = $hotel->plan;

        if (!$plan) {
            throw new \InvalidArgumentException("Hotel does not have an active subscription plan assigned.");
        }

        $pricing = $this->calculateAddonPricing($hotel, $additionalTvs);
        $isComplimentary = !empty($context['is_complimentary']);

        $now = now();
        $oldTvCount = (int) ($hotel->room_count ?: 0);
        $newTvCount = $oldTvCount + $additionalTvs;

        // Update hotel TV capacity without modifying expiry_date
        $hotel->update([
            'room_count' => $newTvCount,
        ]);

        // Evict cached allowed TV IDs immediately
        \Illuminate\Support\Facades\Cache::forget("hotel_{$hotel->id}_allowed_tv_ids");

        $totalAmount = $isComplimentary ? 0.00 : ($context['amount'] ?? $pricing['total_amount']);
        $subtotalAmount = $isComplimentary ? 0.00 : $pricing['subtotal'];
        $taxAmount = $isComplimentary ? 0.00 : $pricing['tax_amount'];
        $cgstAmount = $isComplimentary ? 0.00 : $pricing['cgst_amount'];
        $sgstAmount = $isComplimentary ? 0.00 : $pricing['sgst_amount'];

        $notes = $context['notes'] ?? ("TV Add-On: +{$additionalTvs} Screens ({$oldTvCount} -> {$newTvCount} TVs, {$pricing['remaining_days']} days co-termed)");

        $distributorId = $context['distributor_id'] ?? $hotel->distributor_id;
        $sale = null;

        // Record DistributorSale only if the hotel belongs to a distributor franchise
        if (!empty($distributorId)) {
            $sale = DistributorSale::create([
                'distributor_id' => $distributorId,
                'hotel_id' => $hotel->id,
                'plan_id' => $plan->id,
                'amount' => $totalAmount,
                'payment_status' => 'completed',
                'payment_method' => $context['payment_method'] ?? 'hotel_direct',
                'license_key_issued' => $hotel->license_key,
                'notes' => $notes,
            ]);
        }

        // Generate Tax Invoice
        $invoice = null;
        try {
            $tax = $plan->tax;
            $invoice = \App\Models\Invoice::create([
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'hotel_admin_id' => $hotel->id,
                'distributor_id' => $context['distributor_id'] ?? $hotel->distributor_id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name . " (TV Add-On: +{$additionalTvs} Screens)",
                'billing_cycle' => $plan->billing_cycle ?? 'monthly',
                'tv_count' => $additionalTvs,
                'price_per_tv' => $plan->effective_price_per_tv,
                'subtotal' => $subtotalAmount,
                'tax_id' => $plan->tax_id,
                'tax_name' => $tax ? $tax->name : null,
                'tax_rate' => $tax ? (float) $tax->rate : 0.00,
                'tax_type' => $plan->tax_type ?? 'exclusive',
                'tax_amount' => $taxAmount,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
                'total_amount' => $totalAmount,
                'payment_status' => 'paid',
                'payment_method' => $context['payment_method'] ?? 'hotel_direct',
                'transaction_id' => $context['transaction_id'] ?? null,
                'paid_at' => $now,
                'period_start' => $now,
                'period_end' => $hotel->expiry_date ?: $now->copy()->addDays(30),
                'notes' => $notes,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Addon invoice auto-generation failed: " . $e->getMessage());
        }

        if (!empty($context['gateway_code'])) {
            PaymentTransaction::create([
                'distributor_id' => $context['distributor_id'] ?? $hotel->distributor_id,
                'hotel_admin_id' => $hotel->id,
                'gateway_code' => $context['gateway_code'],
                'transaction_id' => (string) ($context['transaction_id'] ?? ('txn_' . Str::random(16))),
                'amount' => $totalAmount,
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
            'invoice' => $invoice,
            'pricing' => $pricing,
            'old_tv_count' => $oldTvCount,
            'new_tv_count' => $newTvCount,
            'action' => 'addon',
        ];
    }
}