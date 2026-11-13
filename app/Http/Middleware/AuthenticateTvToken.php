<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ConnectedDevice;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateTvToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Bearer token missing.'
            ], 401);
        }

        $device = ConnectedDevice::query()->where('api_token', '=', $token)->first();

        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Invalid token.'
            ], 401);
        }

        // Real-time demo expiry check for isolated per-device trial locking
        if ($device->is_demo && $device->demo_expires_at && now()->greaterThan($device->demo_expires_at)) {
            $device->update(['demo_status' => 'expired']);
            return response()->json([
                'status' => false,
                'error_code' => 'DEMO_EXPIRED',
                'message' => 'Demo trial period has expired. Please purchase a license to continue.',
            ], 403);
        }

        $hotel = $device->hotelAdmin;
        if (!$hotel || (!$device->is_demo && (!$hotel->status || $hotel->approval_status !== 'approved'))) {
            return response()->json([
                'status' => false,
                'error_code' => 'HOTEL_INACTIVE',
                'message' => 'Hotel account is suspended or awaiting approval.',
            ], 403);
        }

        if ($hotel && !$device->is_demo) {
            // Real-time subscription validity check
            if ($hotel->payment_status !== 'paid' || ($hotel->expiry_date && $hotel->expiry_date->isPast())) {
                return response()->json([
                    'status' => false,
                    'error_code' => 'SUBSCRIPTION_EXPIRED',
                    'message' => 'Hotel subscription has expired or is inactive. Please contact administration.',
                ], 403);
            }

            // Strict TV Quota Enforcement:
            // If the hotel purchased 40 TVs, only up to 40 devices can operate; excess devices are locked
            $allowedLimit = (int) ($hotel->allowed_device_limit ?? $hotel->room_count ?? 0);
            if ($allowedLimit > 0) {
                $allowedDeviceIds = \Illuminate\Support\Facades\Cache::remember(
                    "hotel_{$hotel->id}_allowed_tv_ids",
                    60,
                    function () use ($hotel, $allowedLimit) {
                        return $hotel->connectedDevices()
                            ->orderBy('id', 'asc')
                            ->take($allowedLimit)
                            ->pluck('id')
                            ->toArray();
                    }
                );

                if (!in_array($device->id, $allowedDeviceIds)) {
                    return response()->json([
                        'status' => false,
                        'error_code' => 'DEVICE_QUOTA_EXCEEDED',
                        'message' => "Device quota exceeded ({$allowedLimit} TVs authorized for your hotel). This TV screen is currently deactivated.",
                    ], 403);
                }
            }
        }

        // Attach the resolved device and hotel to the request
        $request->merge([
            'current_device' => $device,
            'current_hotel' => $hotel
        ]);

        return $next($request);
    }
}
