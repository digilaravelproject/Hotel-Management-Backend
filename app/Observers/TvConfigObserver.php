<?php

namespace App\Observers;

use App\Events\TvConfigUpdatedEvent;
use App\Models\Amenity;
use App\Models\Guest;
use App\Models\HotelAdmin;
use App\Models\TvTemplate;

class TvConfigObserver
{
    /**
     * Handle created events.
     */
    public function created($model): void
    {
        $this->dispatchConfigEvent($model, 'created');
    }

    /**
     * Handle updated events.
     */
    public function updated($model): void
    {
        // STRICT CHANGE DETECTION: Only dispatch event if fields actually changed!
        if (! $model->wasChanged()) {
            return;
        }

        $this->dispatchConfigEvent($model, 'updated');
    }

    /**
     * Handle deleted events.
     */
    public function deleted($model): void
    {
        $this->dispatchConfigEvent($model, 'deleted');
    }

    /**
     * Dispatch domain event based on model type and scope.
     */
    protected function dispatchConfigEvent($model, string $action): void
    {
        if ($model instanceof TvTemplate) {
            // Global APK / Template version change
            \App\Services\TvVersionCacheService::clearAllHotelsCache();
        } elseif ($model instanceof HotelAdmin) {
            // Ignore if only theme changed (handled explicitly with TEMPLATE scope)
            if ($model->wasChanged('selected_theme_id') && count($model->getChanges()) <= 2) {
                return;
            }
            // Hotel profile / media / configuration change
            \App\Services\TvVersionCacheService::clearHotelCache((int) $model->id, 'HOTEL_INFO', null, true, ['action' => $action]);
        } elseif ($model instanceof Guest) {
            // Room guest check-in / check-out change
            \App\Services\TvVersionCacheService::clearHotelCache((int) $model->hotel_id, 'GUEST', $model->room_number, true, ['action' => $action]);
        } elseif ($model instanceof Amenity) {
            // Hotel amenity list change
            \App\Services\TvVersionCacheService::clearHotelCache((int) $model->hotel_admin_id, 'AMENITY', null, true, ['action' => $action]);
        } elseif ($model instanceof \App\Models\RoomInfo) {
            // Hotel room info list change
            \App\Services\TvVersionCacheService::clearHotelCache((int) $model->hotel_admin_id, 'ROOM_INFO', null, true, ['action' => $action]);
        } elseif ($model instanceof \App\Models\OurCity) {
            // Hotel our city / attractions change
            \App\Services\TvVersionCacheService::clearHotelCache((int) $model->hotel_admin_id, 'OUR_CITY', null, true, ['action' => $action]);
        }
    }
}
