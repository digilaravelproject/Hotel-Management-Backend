<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\TvLoginResource;

class TvTemplateController extends Controller
{
    /**
     * Fetch the latest template version check info.
     * Securely verifies user via Auth Token and returns the synchronized payload.
     */
    public function checkVersion(Request $request)
    {
        $device = $request->input('current_device');
        $hotel = $request->input('current_hotel');

        if (!$device || !$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Invalid token or associated hotel admin not found.'
            ], 401);
        }

        // Dynamically update FCM Token if passed during check-version call
        $incomingFcmToken = $request->input('fcm_token') ?? $request->input('fcmToken') ?? $request->query('fcm_token');
        if ($incomingFcmToken && $device->fcm_token !== $incomingFcmToken) {
            $device->update(['fcm_token' => $incomingFcmToken]);
        }

        $clientVersion = $request->query('version');

        $responseData = \App\Services\TvVersionCacheService::rememberCheckVersion(
            (int) $hotel->id,
            (int) $device->id,
            $clientVersion,
            function () use ($hotel, $device, $clientVersion, $request) {
                // Fetch latest active template version for hotel's selected theme
                $selectedThemeId = (int) ($hotel->selected_theme_id ?? 1);
                $latest = \App\Models\TvTemplate::query()
                    ->where('theme_id', '=', $selectedThemeId)
                    ->where('is_active', '=', true)
                    ->orderBy('id', 'desc')
                    ->first();

                // Fallback to Theme 1 if selected theme has no active build
                if (!$latest && $selectedThemeId !== 1) {
                    $latest = \App\Models\TvTemplate::query()
                        ->where('theme_id', '=', 1)
                        ->where('is_active', '=', true)
                        ->orderBy('id', 'desc')
                        ->first();
                }

                $isUpdateAvailable = false;
                $clientThemeId = $request->query('theme_id') ?? $request->query('template_id');

                if ($latest) {
                    if ($clientThemeId !== null && (int) $clientThemeId !== (int) $latest->theme_id) {
                        // Hotel theme switched - force update so TV downloads the new theme
                        $isUpdateAvailable = true;
                    } elseif ($clientVersion !== null) {
                        // Check if server version is greater than client's version
                        $isUpdateAvailable = version_compare($latest->version, $clientVersion, '>');
                    }
                }

                // Eager load the plan relation to avoid N+1 query issues
                $hotel->loadMissing('plan');

                // Merge version comparison result into request for the resource to read
                $request->merge([
                    'is_update_available' => $isUpdateAvailable,
                ]);

                return (new TvLoginResource([
                    'device' => $device,
                    'hotel' => $hotel,
                    'message' => 'Template version details fetched successfully.',
                ]))->resolve();
            }
        );

        // Always inject dynamic real-time timestamp and timezone reference
        $now = now();
        $responseData['server_time'] = $now->toIso8601String();
        $responseData['timestamp'] = (int) (microtime(true) * 1000);
        $responseData['timezone'] = config('app.timezone', 'Asia/Kolkata');
        $responseData['utc_offset_seconds'] = $now->offset;

        if (isset($responseData['data']['hotel'])) {
            $responseData['data']['hotel']['timezone'] = config('app.timezone', 'Asia/Kolkata');
            if (empty($responseData['data']['hotel']['city'])) {
                $responseData['data']['hotel']['city'] = $hotel->city ?? $hotel->hotel_location ?? 'New Delhi';
            }
        }

        return response()->json($responseData);
    }

    /**
     * TV Device Heartbeat & Clock Sync.
     */
    public function heartbeat(Request $request)
    {
        $device = $request->input('current_device');
        $hotel = $request->input('current_hotel');

        if (!$device || !$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Dynamically update device telemetry if provided in ping
        $updates = [];
        if ($ip = $request->input('ip_address') ?? $request->ip()) {
            $updates['ip_address'] = $ip;
        }
        if ($fcm = $request->input('fcm_token')) {
            $updates['fcm_token'] = $fcm;
        }
        if (!empty($updates)) {
            $device->update($updates);
        }

        $now = now();
        return response()->json([
            'status' => true,
            'message' => 'Heartbeat acknowledged.',
            'server_time' => $now->toIso8601String(),
            'timestamp' => (int) (microtime(true) * 1000),
            'timezone' => config('app.timezone', 'Asia/Kolkata'),
            'utc_offset_seconds' => $now->offset,
            'data' => [
                'device_id' => $device->device_id,
                'room_no' => $device->room_no,
                'is_demo' => (bool) $device->is_demo,
                'demo_expires_at' => $device->demo_expires_at ? $device->demo_expires_at->toIso8601String() : null,
                'hotel' => [
                    'hotel_name' => $hotel->hotel_name,
                    'city' => $hotel->city ?? $hotel->hotel_location ?? 'New Delhi',
                    'hotel_location' => $hotel->hotel_location,
                    'timezone' => config('app.timezone', 'Asia/Kolkata'),
                ]
            ]
        ]);
    }
}
