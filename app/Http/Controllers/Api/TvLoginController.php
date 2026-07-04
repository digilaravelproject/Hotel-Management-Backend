<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TvLoginController extends Controller
{
    /**
     * Authenticate TV and register a new connected device if limits are respected.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'room_no' => 'required|string|max:50',
            'deviceId' => 'required|string|max:100',
            'macAddress' => 'required|string|max:100',
            'ipAddress' => 'nullable|string|max:45',
            'model' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'osVersion' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the active and approved hotel admin by license key
        $hotel = HotelAdmin::query()
            ->where('license_key', $request->license_key)
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->first();

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive license key'
            ], 403);
        }

        // Check if device is already registered for this hotel
        $device = $hotel->connectedDevices()->where('device_id', $request->deviceId)->first();

        if (!$device) {
            // Determine allowed limit (via room_count / allowed_device_limit accessor)
            $allowedLimit = $hotel->allowed_device_limit;
            
            // Count active registered devices
            $currentCount = $hotel->connectedDevices()->count();

            if ($currentCount >= $allowedLimit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device limit reached for this license'
                ], 403);
            }

            // Create new device record
            $device = $hotel->connectedDevices()->create([
                'room_no' => $request->room_no,
                'device_id' => $request->deviceId,
                'mac_address' => $request->macAddress,
                'ip_address' => $request->ipAddress,
                'model' => $request->model,
                'brand' => $request->brand,
                'os_version' => $request->osVersion,
            ]);
        } else {
            // Update existing device record
            $device->update([
                'room_no' => $request->room_no,
                'mac_address' => $request->macAddress,
                'ip_address' => $request->ipAddress,
                'model' => $request->model,
                'brand' => $request->brand,
                'os_version' => $request->osVersion,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'TV logged in successfully.',
            'device_id' => $device->device_id,
            'mac_address' => $device->mac_address,
            'hotel' => $hotel->load('plan')
        ], 200);
    }
}
