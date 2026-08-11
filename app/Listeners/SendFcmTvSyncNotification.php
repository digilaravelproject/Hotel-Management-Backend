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

        // 1. FIRESTORE REALTIME SYNC (Sub-collection per device/room)
        if ($event->hotelId) {
            $hotel = HotelAdmin::with('plan')->find($event->hotelId);
            if ($hotel) {
                // If a specific room number was changed (e.g., Guest Check-in for Room 105)
                if ($event->roomNo) {
                    $device = ConnectedDevice::where('hotel_admin_id', $hotel->id)
                        ->where('room_no', $event->roomNo)
                        ->first();
                    if ($device) {
                        $this->syncDeviceToFirestore($hotel, $device, $event->scope);
                    }
                } else {
                    // Hotel-wide change (Hotel info, Menu, Amenity): Update all connected room devices
                    $devices = ConnectedDevice::where('hotel_admin_id', $hotel->id)->get();
                    foreach ($devices as $device) {
                        $this->syncDeviceToFirestore($hotel, $device, $event->scope);
                    }
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

    /**
     * Helper to sync individual room device data to Firestore sub-collection:
     * Path: hotels/hotel_{hotelId}/devices/room_{roomNo}
     */
    protected function syncDeviceToFirestore(HotelAdmin $hotel, ConnectedDevice $device, string $scope): void
    {
        $resourceArray = (new TvLoginResource([
            'device' => $device,
            'hotel' => $hotel,
            'message' => 'Realtime Firestore Device Config Update',
        ]))->resolve(request());

        $collectionPath = 'hotels/hotel_' . $hotel->id . '/devices';
        $documentId = 'room_' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $device->room_no);

        $this->firestoreService->syncDocument($collectionPath, $documentId, [
            'scope' => $scope,
            'room_no' => (string) $device->room_no,
            'device_id' => (string) $device->device_id,
            'updated_at' => now()->toIso8601String(),
            'data' => $resourceArray['data'] ?? [],
        ]);
    }
}
