<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TvPairSession;
use App\Models\ConnectedDevice;
use App\Services\TvLoginService;
use App\Http\Resources\TvLoginResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TvPairController extends Controller
{
    /**
     * Generate 8-Digit Pairing Code & Session for TV App.
     */
    public function generatePairCode(Request $request)
    {
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

        // Auto Cleanup: Delete all previous sessions (pending or expired) for this device_id
        TvPairSession::where('device_id', $deviceId)->delete();

        // Also cleanup any global expired sessions
        TvPairSession::where('expires_at', '<', now())->delete();

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

        return response()->json([
            'status' => true,
            'message' => 'Pairing code generated successfully',
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
        $request->validate([
            'pair_code' => 'required|string',
            'deviceId' => 'nullable|string',
            'device_id' => 'nullable|string',
        ]);

        $deviceId = trim($request->input('deviceId') ?: $request->input('device_id', ''));
        $cleanCode = strtoupper(trim($request->pair_code));
        $rawCode = str_replace(['-', ' '], '', $cleanCode);
        $formattedCode = strlen($rawCode) === 8 ? substr($rawCode, 0, 4) . '-' . substr($rawCode, 4, 4) : $cleanCode;

        $query = TvPairSession::where(function($q) use ($cleanCode, $rawCode, $formattedCode) {
            $q->where('pair_code', $cleanCode)
              ->orWhere('pair_code', $rawCode)
              ->orWhere('pair_code', $formattedCode);
        });

        if (!empty($deviceId)) {
            $query->where('device_id', $deviceId);
        }

        $session = $query->first();

        if (!$session) {
            return response()->json([
                'status' => false,
                'state' => 'invalid',
                'message' => 'Invalid pairing session'
            ], 404);
        }

        if ($session->status === 'pending') {
            if ($session->isExpired()) {
                $session->update(['status' => 'expired']);
                return response()->json([
                    'status' => false,
                    'state' => 'expired',
                    'message' => 'Pairing code expired'
                ], 410);
            }

            return response()->json([
                'status' => true,
                'state' => 'pending',
                'message' => 'Waiting for hotel admin pairing...'
            ]);
        }

        if ($session->status === 'paired') {
            $device = ConnectedDevice::where('device_id', $session->device_id)->first();
            $hotel = $session->hotelAdmin;

            if (!$device || !$hotel) {
                return response()->json([
                    'status' => false,
                    'state' => 'failed',
                    'message' => 'Paired device or hotel not found'
                ], 500);
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
