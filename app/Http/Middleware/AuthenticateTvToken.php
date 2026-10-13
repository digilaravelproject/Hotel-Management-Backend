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

        // Attach the resolved device and hotel to the request
        $request->merge([
            'current_device' => $device,
            'current_hotel' => $device->hotelAdmin
        ]);

        return $next($request);
    }
}
