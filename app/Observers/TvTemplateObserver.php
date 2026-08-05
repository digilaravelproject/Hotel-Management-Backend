<?php

namespace App\Observers;

use App\Models\TvTemplate;
use App\Services\TvVersionCacheService;

class TvTemplateObserver
{
    /**
     * Handle the TvTemplate "saved" event.
     */
    public function saved(TvTemplate $template): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }

    /**
     * Handle the TvTemplate "deleted" event.
     */
    public function deleted(TvTemplate $template): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }
}
