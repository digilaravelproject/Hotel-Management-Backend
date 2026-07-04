<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TvLoginService
{
    /**
     * Authenticate TV, manage limits/idempotency, and return the device and hotel models.
     *
     * @param array $data
     * @return array
     * @throws HttpException
     */
    public function authenticateTv(array $data): array
    {
        // 1. Find the active and approved hotel admin by license key
        $hotel = HotelAdmin::query()
            ->where('license_key', $data['license_key'])
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->first();

        if (!$hotel) {
            throw new HttpException(403, 'Invalid or inactive license key');
        }

        // 2. Check if device with this ID & MAC already exists for this hotel (Idempotency)
        $device = $hotel->connectedDevices()
            ->where('device_id', $data['deviceId'])
            ->where('mac_address', $data['macAddress'])
            ->first();

        $token = Str::random(80);

        if (!$device) {
            // New Registration - Check Allowed Limit
            $allowedLimit = $hotel->allowed_device_limit;
            $currentCount = $hotel->connectedDevices()->count();

            if ($currentCount >= $allowedLimit) {
                throw new HttpException(403, 'Device limit reached for this license');
            }

            // Create new device record with API Token
            $device = $hotel->connectedDevices()->create([
                'room_no' => $data['room_no'],
                'device_id' => $data['deviceId'],
                'mac_address' => $data['macAddress'],
                'ip_address' => $data['ipAddress'] ?? null,
                'model' => $data['model'] ?? null,
                'brand' => $data['brand'] ?? null,
                'os_version' => $data['osVersion'] ?? null,
                'api_token' => $token,
            ]);
        } else {
            // Existing Device - Update dynamic details and generate new Token
            $device->update([
                'room_no' => $data['room_no'],
                'ip_address' => $data['ipAddress'] ?? null,
                'model' => $data['model'] ?? null,
                'brand' => $data['brand'] ?? null,
                'os_version' => $data['osVersion'] ?? null,
                'api_token' => $token,
            ]);
        }

        return [
            'device' => $device,
            'hotel' => $hotel,
        ];
    }
}
