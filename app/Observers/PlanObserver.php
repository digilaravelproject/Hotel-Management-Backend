<?php

namespace App\Observers;

use App\Models\Plan;
use App\Services\TvVersionCacheService;

class PlanObserver
{
    /**
     * Handle the Plan "saved" event.
     */
    public function saved(Plan $plan): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }

    /**
     * Handle the Plan "deleted" event.
     */
    public function deleted(Plan $plan): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }
}
