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
        $hotelId = $device->hotel_admin_id ?? $device->hotel_id;
        if ($hotelId) {
            TvVersionCacheService::clearHotelCache((int) $hotelId, 'DEVICE', $device->room_no);
        }
    }

    /**
     * Handle the ConnectedDevice "deleted" event.
     */
    public function deleted(ConnectedDevice $device): void
    {
        $hotelId = $device->hotel_admin_id ?? $device->hotel_id;
        if ($hotelId) {
            TvVersionCacheService::clearHotelCache((int) $hotelId, 'DEVICE', $device->room_no);
        }
    }
}
