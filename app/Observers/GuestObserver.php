<?php

namespace App\Observers;

use App\Models\Guest;
use App\Services\TvVersionCacheService;

class GuestObserver
{
    /**
     * Handle the Guest "saved" event.
     */
    public function saved(Guest $guest): void
    {
        if ($guest->hotel_id) {
            TvVersionCacheService::clearHotelCache((int) $guest->hotel_id);
        }
    }

    /**
     * Handle the Guest "deleted" event.
     */
    public function deleted(Guest $guest): void
    {
        if ($guest->hotel_id) {
            TvVersionCacheService::clearHotelCache((int) $guest->hotel_id);
        }
    }
}
