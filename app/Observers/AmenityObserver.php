<?php

namespace App\Observers;

use App\Models\Amenity;
use App\Services\TvVersionCacheService;

class AmenityObserver
{
    /**
     * Handle the Amenity "saved" event.
     */
    public function saved(Amenity $amenity): void
    {
        if ($amenity->hotel_admin_id) {
            TvVersionCacheService::clearHotelCache((int) $amenity->hotel_admin_id);
        }
    }

    /**
     * Handle the Amenity "deleted" event.
     */
    public function deleted(Amenity $amenity): void
    {
        if ($amenity->hotel_admin_id) {
            TvVersionCacheService::clearHotelCache((int) $amenity->hotel_admin_id);
        }
    }
}
