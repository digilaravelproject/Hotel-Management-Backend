<?php

namespace App\Observers;

use App\Models\OttPlatform;
use App\Services\TvVersionCacheService;

class OttPlatformObserver
{
    /**
     * Handle the OttPlatform "saved" event.
     */
    public function saved(OttPlatform $platform): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }

    /**
     * Handle the OttPlatform "deleted" event.
     */
    public function deleted(OttPlatform $platform): void
    {
        TvVersionCacheService::clearAllHotelsCache();
    }
}
