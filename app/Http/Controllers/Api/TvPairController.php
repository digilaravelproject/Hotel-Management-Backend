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
            'deviceId' => 'required|string',
            'macAddress' => 'required|string',
            'ipAddress' => 'nullable|string',
            'model' => 'nullable|string',
            'brand' => 'nullable|string',
            'osVersion' => 'nullable|string',
        ]);

        // Generate unique formatted 8-character code e.g. "8F2A-9K3P"
        do {
            $rawCode = strtoupper(Str::random(8));
            $pairCode = substr($rawCode, 0, 4) . '-' . substr($rawCode, 4, 4);
        } while (TvPairSession::where('pair_code', $pairCode)->where('status', 'pending')->exists());

        // Auto Cleanup: Delete all previous sessions (pending or expired) for this device_id
        TvPairSession::where('device_id', $request->deviceId)->delete();

        // Also cleanup any global expired sessions older than 5 minutes
        TvPairSession::where('expires_at', '<', now())->delete();

        $expiresAt = now()->addMinutes(3);

        $session = TvPairSession::create([
            'pair_code' => $pairCode,
            'device_id' => $request->deviceId,
            'mac_address' => $request->macAddress,
            'ip_address' => $request->ipAddress,
            'model' => $request->model,
            'brand' => $request->brand,
            'os_version' => $request->osVersion,
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
            'deviceId' => 'required|string',
        ]);

        $session = TvPairSession::where('pair_code', strtoupper(trim($request->pair_code)))
            ->where('device_id', $request->deviceId)
            ->first();

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

            $response = new TvLoginResource([
                'device' => $device,
                'hotel' => $hotel,
                'message' => 'TV Paired and logged in successfully!'
            ]);

            // Once login response is delivered to TV app, cleanup the temporary pairing session record
            $session->delete();

            return $response;
        }

        return response()->json([
            'status' => false,
            'state' => $session->status,
            'message' => 'Pairing code ' . $session->status
        ], 400);
    }
}
