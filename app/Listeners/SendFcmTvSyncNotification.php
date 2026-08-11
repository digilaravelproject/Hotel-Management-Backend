<?php

namespace App\Listeners;

use App\Events\TvConfigUpdatedEvent;
use App\Models\ConnectedDevice;
use App\Services\FirebaseFcmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendFcmTvSyncNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected FirebaseFcmService $fcmService;

    /**
     * Inject dependencies.
     */
    public function __construct(FirebaseFcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Handle the event.
     */
    public function handle(TvConfigUpdatedEvent $event): void
    {
        $dataPayload = array_merge([
            'scope' => $event->scope,
            'hotel_id' => (string) ($event->hotelId ?? ''),
            'room_no' => (string) ($event->roomNo ?? ''),
        ], $event->extraData);

        // Case 1: Targeted Push to specific room device (e.g. Guest Check-in/Check-out)
        if ($event->hotelId && $event->roomNo) {
            $devices = ConnectedDevice::query()
                ->where('hotel_admin_id', $event->hotelId)
                ->where('room_no', $event->roomNo)
                ->whereNotNull('fcm_token')
                ->get();

            foreach ($devices as $device) {
                if ($device->fcm_token) {
                    $this->fcmService->sendToToken($device->fcm_token, $dataPayload);
                }
            }
        }

        // Case 2: Hotel-wide Topic Push (e.g. Hotel info, Menu, Amenity, OTT changes)
        if ($event->hotelId) {
            $topic = 'hotel_' . $event->hotelId;
            $this->fcmService->sendToTopic($topic, $dataPayload);
        } else {
            // Case 3: Global Topic Push to all TVs (e.g. TvTemplate version update)
            $this->fcmService->sendToTopic('all_tvs', $dataPayload);
        }
    }
}
