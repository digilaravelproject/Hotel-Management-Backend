<?php

namespace App\Listeners;

use App\Events\TvConfigUpdatedEvent;
use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;
use App\Http\Resources\TvLoginResource;
use App\Services\FirebaseFcmService;
use App\Services\FirebaseFirestoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendFcmTvSyncNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected FirebaseFcmService $fcmService;
    protected FirebaseFirestoreService $firestoreService;

    /**
     * Inject dependencies.
     */
    public function __construct(FirebaseFcmService $fcmService, FirebaseFirestoreService $firestoreService)
    {
        $this->fcmService = $fcmService;
        $this->firestoreService = $firestoreService;
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
            'updated_at' => (string) now()->toIso8601String(),
        ], $event->extraData);

        // 1. FIRESTORE REALTIME SYNC (No Notifications, Native Stream)
        if ($event->hotelId) {
            $hotel = HotelAdmin::with('plan')->find($event->hotelId);
            if ($hotel) {
                // Fetch a sample device for resource building or sync hotel-wide info
                $sampleDevice = ConnectedDevice::where('hotel_admin_id', $hotel->id)->first();
                if ($sampleDevice) {
                    $resourceArray = (new TvLoginResource([
                        'device' => $sampleDevice,
                        'hotel' => $hotel,
                        'message' => 'Realtime Firestore Config Update',
                    ]))->resolve(request());

                    // Sync to Firestore document: collection "hotels", document "hotel_{hotelId}"
                    $this->firestoreService->syncDocument('hotels', 'hotel_' . $hotel->id, [
                        'scope' => $event->scope,
                        'updated_at' => now()->toIso8601String(),
                        'data' => $resourceArray['data'] ?? [],
                    ]);
                }
            }
        } else {
            // Global TvTemplate version change - Sync to "global_config" collection
            $this->firestoreService->syncDocument('global_config', 'tv_template', [
                'scope' => 'TEMPLATE',
                'updated_at' => now()->toIso8601String(),
                'version_stamp' => (string) now()->timestamp,
            ]);
        }

        // 2. FCM PUSH NOTIFICATION BACKUP
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

        if ($event->hotelId) {
            $topic = 'hotel_' . $event->hotelId;
            $this->fcmService->sendToTopic($topic, $dataPayload);
        } else {
            $this->fcmService->sendToTopic('all_tvs', $dataPayload);
        }
    }
}
