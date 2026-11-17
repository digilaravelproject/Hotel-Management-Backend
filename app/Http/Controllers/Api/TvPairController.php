<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TvPairSession;
use App\Models\ConnectedDevice;
use App\Services\TvLoginService;
use App\Http\Resources\TvLoginResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class TvPairController extends Controller
{
    /**
     * Generate 8-Digit Pairing Code & Session for TV App.
     */
    public function generatePairCode(Request $request)
    {
        $throttleKey = 'tv-pair-gen:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 30)) {
            return response()->json([
                'status' => false,
                'message' => 'Too many pairing requests. Please wait a moment.'
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $request->validate([
            'deviceId' => 'nullable|string',
            'device_id' => 'nullable|string',
            'macAddress' => 'nullable|string',
            'mac_address' => 'nullable|string',
            'ipAddress' => 'nullable|string',
            'ip_address' => 'nullable|string',
            'model' => 'nullable|string',
            'brand' => 'nullable|string',
            'osVersion' => 'nullable|string',
            'os_version' => 'nullable|string',
        ]);

        $deviceId = trim($request->input('deviceId') ?: $request->input('device_id', ''));
        $macAddress = trim($request->input('macAddress') ?: $request->input('mac_address', ''));

        if (empty($deviceId) || empty($macAddress)) {
            return response()->json([
                'status' => false,
                'message' => 'deviceId and macAddress are required to generate pairing code.',
            ], 422);
        }

        // Generate unique formatted 8-character code e.g. "8F2A-9K3P"
        do {
            $rawCode = strtoupper(Str::random(8));
            $pairCode = substr($rawCode, 0, 4) . '-' . substr($rawCode, 4, 4);
        } while (TvPairSession::where('pair_code', $pairCode)->where('status', 'pending')->exists());

        // Auto Cleanup: Delete only pending or expired sessions for this device_id (never delete active paired sessions)
        TvPairSession::where('device_id', $deviceId)->where('status', 'pending')->delete();

        // Also cleanup any global expired pending sessions
        TvPairSession::where('expires_at', '<', now())->where('status', 'pending')->delete();

        $expiresAt = now()->addMinutes(10);

        $session = TvPairSession::create([
            'pair_code' => $pairCode,
            'device_id' => $deviceId,
            'mac_address' => $macAddress,
            'ip_address' => $request->input('ipAddress') ?: $request->input('ip_address'),
            'model' => $request->input('model'),
            'brand' => $request->input('brand'),
            'os_version' => $request->input('osVersion') ?: $request->input('os_version'),
            'status' => 'pending',
            'expires_at' => $expiresAt,
        ]);

        $now = now();
        return response()->json([
            'status' => true,
            'message' => 'Pairing code generated successfully',
            'server_time' => $now->toIso8601String(),
            'timestamp' => (int) (microtime(true) * 1000),
            'timezone' => config('app.timezone', 'Asia/Kolkata'),
            'utc_offset_seconds' => $now->offset,
            'data' => [
                'pair_code' => $session->pair_code,
                'expires_at' => $expiresAt->toIso8601String(),
                'expires_in_seconds' => now()->diffInSeconds($expiresAt),
            ]
        ]);
    }

    /**
     * Check pairing status (Polled continuously by TV App every 3-5 seconds).
     */
    public function checkStatus(Request $request, TvLoginService $tvLoginService)
    {
        $throttleKey = 'tv-pair-check:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 300)) {
            return response()->json([
                'status' => false,
                'message' => 'Too many status check requests. Please slow down.'
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $request->validate([
            'pair_code' => 'nullable|string',
            'pairCode' => 'nullable|string',
            'deviceId' => 'nullable|string',
            'device_id' => 'nullable|string',
            'macAddress' => 'nullable|string',
            'mac_address' => 'nullable|string',
        ]);

        $rawInputCode = $request->input('pair_code') ?: $request->input('pairCode');
        if (empty($rawInputCode)) {
            return response()->json([
                'status' => false,
                'message' => 'The pair_code field is required.',
            ], 422);
        }

        $deviceId = trim($request->input('deviceId') ?: $request->input('device_id', ''));
        $macAddress = trim($request->input('macAddress') ?: $request->input('mac_address', ''));

        $cleanCode = strtoupper(trim($rawInputCode));
        $rawCode = str_replace(['-', ' '], '', $cleanCode);
        $formattedCode = strlen($rawCode) === 8 ? substr($rawCode, 0, 4) . '-' . substr($rawCode, 4, 4) : $cleanCode;

        $session = TvPairSession::where(function($q) use ($cleanCode, $rawCode, $formattedCode) {
            $q->where('pair_code', $cleanCode)
              ->orWhere('pair_code', $rawCode)
              ->orWhere('pair_code', $formattedCode);
        })->first();

        $now = now();
        $timeMeta = [
            'server_time' => $now->toIso8601String(),
            'timestamp' => (int) (microtime(true) * 1000),
            'timezone' => config('app.timezone', 'Asia/Kolkata'),
            'utc_offset_seconds' => $now->offset,
        ];

        if (!$session) {
            return response()->json(array_merge([
                'status' => false,
                'state' => 'invalid',
                'message' => 'Invalid pairing session'
            ], $timeMeta), 404);
        }

        // SEC-04: Strict device authorization check: if deviceId or macAddress are provided, verify they match
        if ($deviceId !== '' && !empty($session->device_id) && $session->device_id !== $deviceId) {
            return response()->json(array_merge([
                'status' => false,
                'state' => 'unauthorized',
                'message' => 'Device authorization failed. Device ID mismatch.'
            ], $timeMeta), 403);
        }

        if ($macAddress !== '' && !empty($session->mac_address) && strtoupper($session->mac_address) !== strtoupper($macAddress)) {
            return response()->json(array_merge([
                'status' => false,
                'state' => 'unauthorized',
                'message' => 'Device authorization failed. MAC address mismatch.'
            ], $timeMeta), 403);
        }

        if ($session->status === 'pending') {
            if ($session->isExpired()) {
                $session->update(['status' => 'expired']);
                return response()->json(array_merge([
                    'status' => false,
                    'state' => 'expired',
                    'message' => 'Pairing code expired'
                ], $timeMeta), 410);
            }

            return response()->json(array_merge([
                'status' => true,
                'state' => 'pending',
                'message' => 'Waiting for hotel admin pairing...'
            ], $timeMeta));
        }

        if ($session->status === 'paired') {
            // Restrict paired token pickup window to 15 minutes after pairing while keeping immediate retries working
            if ($session->updated_at && $session->updated_at->lt(now()->subMinutes(15))) {
                return response()->json(array_merge([
                    'status' => false,
                    'state' => 'expired',
                    'message' => 'Pairing token retrieval window expired. Please generate a new pairing code.'
                ], $timeMeta), 410);
            }

            $device = ConnectedDevice::where('device_id', $session->device_id)
                ->where('hotel_admin_id', $session->hotel_admin_id)
                ->first();
            $hotel = $session->hotelAdmin;

            if (!$device || !$hotel || !$hotel->status || $hotel->approval_status !== 'approved') {
                return response()->json([
                    'status' => false,
                    'state' => 'failed',
                    'message' => 'Paired device or active approved hotel not found.'
                ], 403);
            }

            $hotel->loadMissing('plan');

            return new TvLoginResource([
                'device' => $device,
                'hotel' => $hotel,
                'message' => 'TV Paired and logged in successfully!'
            ]);
        }

        return response()->json([
            'status' => false,
            'state' => $session->status,
            'message' => 'Pairing code ' . $session->status
        ], 400);
    }
}
