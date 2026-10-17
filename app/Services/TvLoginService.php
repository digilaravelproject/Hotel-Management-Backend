<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TvLoginService
{
    public function resolveDistributorFromCode(?string $code): ?int
    {
        if (empty($code)) {
            return null;
        }

        // Direct user ID (e.g. 5)
        if (is_numeric($code)) {
            $user = \App\Models\User::find((int) $code);
            return $user?->id;
        }

        // Prefix match e.g. DST-5, DIST-5
        if (preg_match('/^(?:DIST|DST)[-_]?(\d+)$/i', $code, $matches)) {
            $user = \App\Models\User::find((int) $matches[1]);
            if ($user) {
                return $user->id;
            }
        }

        // Safe column check for custom referral code
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'distributor_referral_code')) {
            $user = \App\Models\User::where('distributor_referral_code', $code)->first();
            return $user?->id;
        }

        return null;
    }

    /**
     * Authenticate TV, manage limits/idempotency, and return the device and hotel models.
     *
     * @param array $data
     * @return array
     * @throws HttpException
     */
    public function authenticateTv(array $data): array
    {
        // Demo login path: bind to Demo Showcase Hotel if device not yet registered
        if (!empty($data['demo_login']) || !empty($data['is_demo'])) {
            $demoHotel = HotelAdmin::where('license_key', 'DEMO-MASTER-SHOWCASE')->first();
            if (!$demoHotel) {
                throw new HttpException(403, 'Demo showcase hotel not configured');
            }

            // Hardware-lock anti-abuse: check if device already has a demo record by deviceId or macAddress
            $existingDemoDevice = ConnectedDevice::query()
                ->where('is_demo', true)
                ->where(function ($q) use ($data) {
                    $q->where('device_id', $data['deviceId']);
                    if (!empty($data['macAddress'])) {
                        $q->orWhere('mac_address', $data['macAddress']);
                    }
                })
                ->first();

            if ($existingDemoDevice) {
                if ($existingDemoDevice->demo_status === 'expired' || ($existingDemoDevice->demo_expires_at && now()->greaterThan($existingDemoDevice->demo_expires_at))) {
                    $existingDemoDevice->update(['demo_status' => 'expired']);
                    throw new HttpException(403, 'Demo trial has already expired for this TV device. Please purchase a license.');
                }
                // Active existing demo device: refresh token
                $token = Str::random(80);
                $existingDemoDevice->update([
                    'api_token' => $token,
                    'mac_address' => $data['macAddress'] ?? $existingDemoDevice->mac_address,
                    'ip_address' => $data['ipAddress'] ?? $existingDemoDevice->ip_address,
                ]);
                $daysLeft = $existingDemoDevice->demo_expires_at ? max(0, (int) ceil(now()->diffInHours($existingDemoDevice->demo_expires_at) / 24)) : 0;
                return [
                    'device' => $existingDemoDevice->fresh(),
                    'hotel' => $demoHotel,
                    'is_demo' => true,
                    'days_left' => $daysLeft,
                ];
            }

            // New demo device registration under Demo Showcase Hotel
            $token = Str::random(80);
            $device = ConnectedDevice::create([
                'hotel_admin_id' => $demoHotel->id,
                'room_no' => $data['room_no'] ?? 'DEMO-001',
                'device_id' => $data['deviceId'],
                'mac_address' => $data['macAddress'],
                'ip_address' => $data['ipAddress'] ?? null,
                'model' => $data['model'] ?? null,
                'brand' => $data['brand'] ?? null,
                'os_version' => $data['osVersion'] ?? null,
                'api_token' => $token,
                'is_demo' => true,
                'demo_expires_at' => now()->addDays(7),
                'demo_status' => 'active',
                'distributor_id' => !empty($data['distributor_code']) ? $this->resolveDistributorFromCode($data['distributor_code']) : null,
            ]);

            return [
                'device' => $device,
                'hotel' => $demoHotel,
                'is_demo' => true,
                'days_left' => 7,
            ];
        }

        // Standard authentication path below...
        // 1. Find the active and approved hotel admin by license key
        $hotel = HotelAdmin::query()
            ->with('plan')
            ->where('license_key', $data['license_key'])
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->first();

        if (!$hotel) {
            throw new HttpException(403, 'Invalid or inactive license key');
        }

        // Validate plan subscription and payment status
        if ($hotel->payment_status !== 'paid' || !$hotel->plan_id) {
            throw new HttpException(403, 'Subscription payment is pending. Please complete your subscription payment to activate TV devices.');
        }

        if ($hotel->expiry_date && $hotel->expiry_date->isPast()) {
            throw new HttpException(403, 'Subscription plan has expired. Please renew your subscription to continue using TV services.');
        }

        // 2. Check if device with this ID already exists globally (Idempotency / interface shifts)
        $device = ConnectedDevice::query()->where('device_id', '=', $data['deviceId'])->first();

        // If device was previously in demo mode, convert it to paid
        if ($device && $device->is_demo) {
            $device->update([
                'is_demo' => false,
                'demo_expires_at' => null,
                'demo_status' => 'converted',
            ]);
        }

        // 3. Check if there's already an existing device assigned to this room in this hotel
        $existingRoomDevice = $hotel->connectedDevices()->where('room_no', $data['room_no'])->first();

        $token = Str::random(80);
        $fcmToken = $data['fcmToken'] ?? $data['fcm_token'] ?? null;

        if (!$device && $existingRoomDevice) {
            // Room already has a device record (e.g. placeholder or previous TV).
            // Re-bind the existing room record to this TV's hardware identifiers.
            $existingRoomDevice->update([
                'device_id'   => $data['deviceId'],
                'mac_address' => $data['macAddress'],
                'ip_address'  => $data['ipAddress'] ?? $existingRoomDevice->ip_address,
                'model'       => $data['model'] ?? $existingRoomDevice->model,
                'brand'       => $data['brand'] ?? $existingRoomDevice->brand,
                'os_version'  => $data['osVersion'] ?? $existingRoomDevice->os_version,
                'api_token'   => $token,
                'fcm_token'   => $fcmToken ?? $existingRoomDevice->fcm_token,
            ]);
            $device = $existingRoomDevice;
        } elseif (!$device) {
            // New Registration - Check Allowed Limit
            $allowedLimit = $hotel->allowed_device_limit;
            $currentCount = $hotel->connectedDevices()->count();

            if ($currentCount >= $allowedLimit) {
                throw new HttpException(403, "Device limit reached ({$allowedLimit} TVs allowed for your subscription plan).");
            }

            // Create new device record with API Token
            $device = $hotel->connectedDevices()->create([
                'room_no'     => $data['room_no'],
                'device_id'   => $data['deviceId'],
                'mac_address' => $data['macAddress'],
                'ip_address'  => $data['ipAddress'] ?? null,
                'model'       => $data['model'] ?? null,
                'brand'       => $data['brand'] ?? null,
                'os_version'  => $data['osVersion'] ?? null,
                'api_token'   => $token,
                'fcm_token'   => $fcmToken,
            ]);
        } else {
            // Existing Device - If hotel admin ID changes, check the limit for the new hotel admin
            if ($device->hotel_admin_id !== $hotel->id) {
                $allowedLimit = $hotel->allowed_device_limit;
                $currentCount = $hotel->connectedDevices()->count();

                if ($currentCount >= $allowedLimit) {
                    throw new HttpException(403, "Device limit reached ({$allowedLimit} TVs allowed for your subscription plan).");
                }
            }

            $fcmToken = $fcmToken ?? $device->fcm_token;

            // Update dynamic details, hotel assignment, and MAC address
            $device->update([
                'hotel_admin_id' => $hotel->id,
                'room_no'        => $data['room_no'],
                'mac_address'    => $data['macAddress'],
                'ip_address'     => $data['ipAddress'] ?? null,
                'model'          => $data['model'] ?? null,
                'brand'          => $data['brand'] ?? null,
                'os_version'     => $data['osVersion'] ?? null,
                'api_token'      => $token,
                'fcm_token'      => $fcmToken,
            ]);
        }

        return [
            'device' => $device,
            'hotel' => $hotel,
        ];
    }
}
