<?php

namespace App\Observers;

use App\Models\DistributorSale;
use App\Services\WalletService;

class DistributorSaleObserver
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function created(DistributorSale $sale): void
    {
        // Automatically credit distributor wallet when a sale is recorded
        $this->walletService->creditCommission($sale);
    }
}
