<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Helpers\ImageHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HotelService
{
    /**
     * Create a new hotel admin client.
     */
    public function createHotel(
        array $data,
        ?UploadedFile $logo = null,
        ?UploadedFile $coverImage = null
    ): HotelAdmin {
        $planId = !empty($data['plan_id']) ? (int) $data['plan_id'] : null;
        $plan = $planId ? Plan::find($planId) : null;

        // DRY Room / TV Limit resolution
        $roomCount = $this->resolveRoomCount(
            !empty($data['room_count']) ? (int) $data['room_count'] : null,
            $plan,
            25
        );

        // License Key & Billing Dates
        $paymentStatus = $data['payment_status'] ?? 'pending';
        $licenseKey = null;
        $purchaseDate = null;
        $expiryDate = null;

        if ($planId && $paymentStatus === 'paid') {
            $prefix = !empty($data['distributor_id']) ? 'DIST' : null;
            $licenseKey = $this->generateUniqueLicenseKey($prefix);
            $purchaseDate = !empty($data['purchase_date']) ? $data['purchase_date'] : now();
            $expiryDate = !empty($data['expiry_date']) ? $data['expiry_date'] : now()->addDays(30);
        }

        $hotelData = [
            'owner_name' => $data['owner_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'hotel_name' => $data['hotel_name'],
            'hotel_location' => $data['hotel_location'],
            'city' => $data['city'] ?? $data['hotel_location'] ?? null,
            'room_count' => $roomCount,
            'plan_id' => $planId,
            'distributor_id' => !empty($data['distributor_id']) ? (int) $data['distributor_id'] : null,
            'payment_status' => $paymentStatus,
            'approval_status' => $data['approval_status'] ?? 'pending',
            'description' => $data['description'] ?? null,
            'license_key' => $licenseKey,
            'status' => $data['status'] ?? true,
            'purchase_date' => $purchaseDate,
            'expiry_date' => $expiryDate,
        ];

        // Process Hotel Logo
        if ($logo) {
            $hotelData['hotel_logo'] = ImageHelper::compressAndConvertToWebp(
                $logo,
                'uploads/hotel_logos',
                500,
                'logo',
                1200
            );
        }

        // Process Hotel Cover Image
        if ($coverImage) {
            $hotelData['hotel_image'] = ImageHelper::compressAndConvertToWebp(
                $coverImage,
                'uploads/hotel_images',
                1000,
                'cover',
                2560
            );
        }

        $hotel = HotelAdmin::create($hotelData);

        // Record distributor sale if requested or in distributor flow
        if (!empty($data['record_distributor_sale']) && !empty($hotel->distributor_id) && $plan) {
            DistributorSale::create([
                'distributor_id' => $hotel->distributor_id,
                'hotel_id' => $hotel->id,
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'payment_status' => 'completed',
                'payment_method' => $data['payment_method'] ?? 'distributor_direct',
                'license_key_issued' => $licenseKey,
                'notes' => $data['sale_notes'] ?? "Onboarding package: {$plan->name}",
            ]);
        }

        return $hotel;
    }

    /**
     * Dedicated helper to onboard a hotel client from the Distributor panel.
     */
    public function createHotelForDistributor(int $distributorId, array $data): HotelAdmin
    {
        $planId = !empty($data['plan_id']) ? (int) $data['plan_id'] : null;

        $payload = array_merge($data, [
            'distributor_id' => $distributorId,
            'approval_status' => 'approved',
            'status' => true,
            'payment_status' => $planId ? 'paid' : 'pending',
            'record_distributor_sale' => (bool) $planId,
            'payment_method' => 'distributor_direct',
            'sale_notes' => 'Distributor portal onboarding',
        ]);

        return $this->createHotel($payload);
    }

    /**
     * Update an existing hotel admin client.
     */
    public function updateHotel(
        HotelAdmin $hotel,
        array $data,
        ?UploadedFile $logo = null,
        ?UploadedFile $coverImage = null,
        array $sliderImages = []
    ): HotelAdmin {
        $planId = !empty($data['plan_id']) ? (int) $data['plan_id'] : null;
        $plan = $planId ? Plan::find($planId) : null;

        // DRY Room / TV Limit resolution
        $roomCount = $this->resolveRoomCount(
            !empty($data['room_count']) ? (int) $data['room_count'] : null,
            $plan,
            $hotel->room_count ?: 25
        );

        $updateData = [
            'owner_name' => $data['owner_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'hotel_name' => $data['hotel_name'],
            'hotel_location' => $data['hotel_location'],
            'city' => $data['city'] ?? $hotel->city,
            'room_count' => $roomCount,
            'plan_id' => $planId,
            'distributor_id' => !empty($data['distributor_id']) ? (int) $data['distributor_id'] : null,
            'payment_status' => $data['payment_status'] ?? $hotel->payment_status,
            'approval_status' => $data['approval_status'] ?? $hotel->approval_status,
            'description' => $data['description'] ?? $hotel->description,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (array_key_exists('purchase_date', $data) && $data['purchase_date']) {
            $updateData['purchase_date'] = $data['purchase_date'];
        }

        if (array_key_exists('expiry_date', $data) && $data['expiry_date']) {
            $updateData['expiry_date'] = $data['expiry_date'];
        }

        // Logo replacement
        if ($logo) {
            if ($hotel->hotel_logo) {
                ImageHelper::deleteFile($hotel->hotel_logo);
            }
            $updateData['hotel_logo'] = ImageHelper::compressAndConvertToWebp(
                $logo,
                'uploads/hotel_logos',
                500,
                'logo',
                1200
            );
        }

        // Cover image replacement
        if ($coverImage) {
            if ($hotel->hotel_image) {
                ImageHelper::deleteFile($hotel->hotel_image);
            }
            $updateData['hotel_image'] = ImageHelper::compressAndConvertToWebp(
                $coverImage,
                'uploads/hotel_images',
                1000,
                'cover',
                2560
            );
        }

        // Slider images append
        if (!empty($sliderImages)) {
            $existingSliders = $hotel->slider_images ?? [];
            if (count($existingSliders) + count($sliderImages) <= 10) {
                foreach ($sliderImages as $file) {
                    if ($file instanceof UploadedFile) {
                        $savedSlider = ImageHelper::compressAndConvertToWebp(
                            $file,
                            'uploads/hotel_sliders',
                            800,
                            'slider',
                            2560
                        );
                        $existingSliders[] = $savedSlider;
                    }
                }
                $updateData['slider_images'] = $existingSliders;
            }
        }

        // License key generation if newly paid
        if (!$hotel->license_key && $planId && ($updateData['payment_status'] ?? '') === 'paid') {
            $prefix = !empty($updateData['distributor_id']) ? 'DIST' : null;
            $updateData['license_key'] = $this->generateUniqueLicenseKey($prefix);
        }

        // Date calculation if newly paid without explicit dates
        if ($planId && ($updateData['payment_status'] ?? '') === 'paid' && empty($data['purchase_date'])) {
            if (!$hotel->purchase_date || $hotel->plan_id !== $planId || $hotel->payment_status !== 'paid') {
                $updateData['purchase_date'] = now();
                if (empty($data['expiry_date'])) {
                    $updateData['expiry_date'] = now()->addDays(30);
                }
            }
        }

        $hotel->update($updateData);

        return $hotel->fresh(['plan', 'distributor']);
    }

    /**
     * Assign / extend package for a hotel and record in distributor sales ledger.
     */
    public function assignPlanAndRecordSale(
        HotelAdmin $hotel,
        Plan $plan,
        int $durationMonths,
        array $saleData,
        int $distributorId
    ): array {
        $now = now();
        $durationDays = $durationMonths * 30;

        // If existing expiry date is in the future, extend from that point; otherwise start from now
        $baseDate = ($hotel->expiry_date && $hotel->expiry_date > $now) ? $hotel->expiry_date : $now;
        $newExpiry = $baseDate->copy()->addDays($durationDays);

        $hotel->update([
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'purchase_date' => $now,
            'expiry_date' => $newExpiry,
            'room_count' => $this->resolveRoomCount($hotel->room_count, $plan, $hotel->room_count ?: 25),
        ]);

        $sale = DistributorSale::create([
            'distributor_id' => $distributorId,
            'hotel_id' => $hotel->id,
            'plan_id' => $plan->id,
            'amount' => $saleData['amount'] ?? $plan->price,
            'payment_status' => 'completed',
            'payment_method' => $saleData['payment_method'] ?? 'distributor_direct',
            'license_key_issued' => $hotel->license_key,
            'notes' => $saleData['notes'] ?? "Package sale: {$plan->name} ({$durationMonths} months)",
        ]);

        return [
            'hotel' => $hotel->fresh(['plan']),
            'sale' => $sale,
            'new_expiry' => $newExpiry,
        ];
    }

    /**
     * Toggle the active status of a hotel admin.
     */
    public function toggleStatus(HotelAdmin $hotel): bool
    {
        $hotel->status = !$hotel->status;
        $hotel->save();

        return $hotel->status;
    }

    /**
     * Update the approval status of a hotel admin.
     */
    public function updateApprovalStatus(HotelAdmin $hotel, string $status): string
    {
        $hotel->approval_status = $status;
        $hotel->save();

        return $hotel->approval_status;
    }

    /**
     * Delete hotel and purge all associated media files (DRY media cleanup).
     */
    public function deleteHotel(HotelAdmin $hotel): bool
    {
        $this->purgeHotelMedia($hotel);

        return (bool) $hotel->delete();
    }

    /**
     * Purge all uploaded media files associated with a hotel.
     */
    public function purgeHotelMedia(HotelAdmin $hotel): void
    {
        $files = array_filter(array_merge(
            [$hotel->hotel_logo, $hotel->hotel_image],
            is_array($hotel->slider_images) ? $hotel->slider_images : [],
            is_array($hotel->hotel_gallery_images) ? $hotel->hotel_gallery_images : []
        ));

        foreach ($files as $filePath) {
            ImageHelper::deleteFile($filePath);
        }
    }

    /**
     * Resolve final room count based on plan capacity and requested capacity (DRY helper).
     */
    public function resolveRoomCount(?int $requestedRoomCount, ?Plan $plan, int $currentRoomCount = 25): int
    {
        if (!$requestedRoomCount) {
            return $plan ? (int) $plan->room_count : $currentRoomCount;
        }

        return $plan ? max($requestedRoomCount, (int) $plan->room_count) : $requestedRoomCount;
    }

    /**
     * Generate a collision-free unique license key (DRY chunk formatting).
     */
    public function generateUniqueLicenseKey(?string $prefix = null): string
    {
        do {
            $chunks = array_map(fn() => strtoupper(Str::random(4)), range(1, $prefix ? 3 : 4));
            if ($prefix) {
                array_unshift($chunks, strtoupper($prefix));
            }
            $key = implode('-', $chunks);
        } while (HotelAdmin::where('license_key', $key)->exists());

        return $key;
    }
}
