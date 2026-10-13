<?php

namespace App\Services;

use App\Models\User;
use App\Models\DistributorSale;
use App\Models\CommissionSetting;
use App\Models\DistributorWalletTransaction;

class CommissionService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Resolve effective commission percentage and calculate amount.
     */
    public function calculateCommission(User $distributor, float $saleAmount): array
    {
        $isOverride = ($distributor->commission_percentage !== null);
        $rate = $isOverride
            ? (float) $distributor->commission_percentage
            : (float) CommissionSetting::getSetting()->default_percentage;

        $commissionAmount = round(($saleAmount * $rate) / 100, 2);

        return [
            'rate' => $rate,
            'is_override' => $isOverride,
            'sale_amount' => $saleAmount,
            'commission_amount' => $commissionAmount,
        ];
    }

    /**
     * Calculate and credit commission for a completed distributor sale.
     */
    public function processSaleCommission(DistributorSale $sale): ?DistributorWalletTransaction
    {
        if (empty($sale->distributor_id) || (float) $sale->amount <= 0) {
            return null;
        }

        $distributor = $sale->distributor;
        if (!$distributor) {
            $distributor = User::find($sale->distributor_id);
        }

        if (!$distributor) {
            return null;
        }

        $calc = $this->calculateCommission($distributor, (float) $sale->amount);
        $commissionAmount = $calc['commission_amount'];

        if ($commissionAmount <= 0) {
            return null;
        }

        $hotelName = $sale->hotel ? $sale->hotel->hotel_name : "Hotel #{$sale->hotel_id}";
        $planName = $sale->plan ? $sale->plan->name : "Plan #{$sale->plan_id}";
        $rate = $calc['rate'];

        $description = "Earned {$rate}% commission on {$hotelName} ({$planName})";

        return $this->walletService->credit(
            distributorId: $distributor->id,
            amount: $commissionAmount,
            referenceType: 'sale',
            referenceId: $sale->id,
            description: $description,
            saleId: $sale->id
        );
    }
}
