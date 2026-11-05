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
            TvVersionCacheService::clearHotelCache((int) $hotelId, 'DEVICE', $device->room_no, true);
            \Illuminate\Support\Facades\Cache::forget("hotel_{$hotelId}_allowed_tv_ids");
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
            \Illuminate\Support\Facades\Cache::forget("hotel_{$hotelId}_allowed_tv_ids");

            if ($device->device_id) {
                $firestore = app(\App\Services\FirebaseFirestoreService::class);
                $collectionPath = 'hotels/hotel_' . $hotelId . '/rooms';
                $documentId = 'device_' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $device->device_id);
                $firestore->deleteDocument($collectionPath, $documentId);
            }
        }
    }
}
