<?php

namespace App\Observers;

use App\Models\HotelAdmin;
use App\Services\TvVersionCacheService;

class HotelAdminObserver
{
    /**
     * Handle the HotelAdmin "saved" event.
     */
    public function saved(HotelAdmin $hotelAdmin): void
    {
        TvVersionCacheService::clearHotelCache((int) $hotelAdmin->id);
    }

    /**
     * Handle the HotelAdmin "deleted" event.
     */
    public function deleted(HotelAdmin $hotelAdmin): void
    {
        TvVersionCacheService::clearHotelCache((int) $hotelAdmin->id);
    }
}
