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

        // Attach the resolved device and hotel to the request
        $request->merge([
            'current_device' => $device,
            'current_hotel' => $device->hotelAdmin
        ]);

        return $next($request);
    }
}
