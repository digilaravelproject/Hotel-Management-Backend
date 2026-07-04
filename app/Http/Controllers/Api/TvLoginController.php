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
                'status' => false,
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
                'status' => false,
                'message' => 'Invalid or inactive license key'
            ], 403);
        }

        // Check if device with this ID & MAC already exists for this hotel (Idempotency)
        $device = $hotel->connectedDevices()
            ->where('device_id', $request->deviceId)
            ->where('mac_address', $request->macAddress)
            ->first();

        $token = Str::random(80);

        if (!$device) {
            // New Registration - Check Allowed Limit
            $allowedLimit = $hotel->allowed_device_limit;
            $currentCount = $hotel->connectedDevices()->count();

            if ($currentCount >= $allowedLimit) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device limit reached for this license'
                ], 403);
            }

            // Create new device record with API Token
            $device = $hotel->connectedDevices()->create([
                'room_no' => $request->room_no,
                'device_id' => $request->deviceId,
                'mac_address' => $request->macAddress,
                'ip_address' => $request->ipAddress,
                'model' => $request->model,
                'brand' => $request->brand,
                'os_version' => $request->osVersion,
                'api_token' => $token,
            ]);
        } else {
            // Existing Device - Update dynamic details and generate new Token
            $device->update([
                'room_no' => $request->room_no,
                'ip_address' => $request->ipAddress,
                'model' => $request->model,
                'brand' => $request->brand,
                'os_version' => $request->osVersion,
                'api_token' => $token,
            ]);
        }

        // Format slider images asset URLs
        $sliders = [];
        if ($hotel->slider_images && is_array($hotel->slider_images)) {
            foreach ($hotel->slider_images as $path) {
                $sliders[] = asset($path);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'TV logged in successfully.',
            'auth' => [
                'token' => $device->api_token,
            ],
            'device' => [
                'room_no' => $device->room_no,
                'device_id' => $device->device_id,
                'mac_address' => $device->mac_address,
                'ip_address' => $device->ip_address,
                'model' => $device->model,
                'brand' => $device->brand,
                'os_version' => $device->os_version,
            ],
            'hotel' => [
                'hotel_name' => $hotel->hotel_name,
                'hotel_location' => $hotel->hotel_location,
                'description' => $hotel->description,
                'owner_name' => $hotel->owner_name,
                'email' => $hotel->email,
                'phone' => $hotel->phone,
                'media' => [
                    'logo_image' => $hotel->hotel_logo ? asset($hotel->hotel_logo) : null,
                    'cover_image' => $hotel->hotel_image ? asset($hotel->hotel_image) : null,
                    'slider_images' => $sliders,
                ],
            ]
        ], 200);
    }
}
