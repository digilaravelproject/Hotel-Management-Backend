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
            event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['action' => $action]));
        } elseif ($model instanceof HotelAdmin) {
            // Hotel profile / media / configuration change
            event(new TvConfigUpdatedEvent($model->id, 'HOTEL_INFO', null, ['action' => $action]));
        } elseif ($model instanceof Guest) {
            // Room guest check-in / check-out change
            event(new TvConfigUpdatedEvent($model->hotel_id, 'GUEST', $model->room_number, ['action' => $action]));
        } elseif ($model instanceof Amenity) {
            // Hotel amenity list change
            event(new TvConfigUpdatedEvent($model->hotel_admin_id, 'AMENITY', null, ['action' => $action]));
        } elseif ($model instanceof \App\Models\RoomInfo) {
            // Hotel room info list change
            event(new TvConfigUpdatedEvent($model->hotel_admin_id, 'ROOM_INFO', null, ['action' => $action]));
        }
    }
}
