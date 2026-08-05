<?php

namespace App\Observers;

use App\Models\ConnectedDevice;
use App\Services\TvVersionCacheService;

class ConnectedDeviceObserver
{
    /**
     * Handle the ConnectedDevice "saved" event.
     */
    public function saved(ConnectedDevice $device): void
    {
        if ($device->hotel_id) {
            TvVersionCacheService::clearHotelCache((int) $device->hotel_id);
        }
    }

    /**
     * Handle the ConnectedDevice "deleted" event.
     */
    public function deleted(ConnectedDevice $device): void
    {
        if ($device->hotel_id) {
            TvVersionCacheService::clearHotelCache((int) $device->hotel_id);
        }
    }
}
