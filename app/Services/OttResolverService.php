<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;
use App\Models\Plan;
use Illuminate\Support\Str;

class OttResolverService
{
    /**
     * Resolve active OTT platforms for a specific TV device adhering to the priority hierarchy:
     * 1. Room/Device Overrides
     * 2. Hotel Global Default
     * 3. Super Admin Plan Limit (Hard upper boundary filter)
     *
     * @param ConnectedDevice $device
     * @param HotelAdmin $hotel
     * @return array<int, array<string, string>>
     */
    public function getActiveOttForDevice(ConnectedDevice $device, HotelAdmin $hotel): array
    {
        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        // Hierarchy evaluation:
        if (!is_null($device->ott_overrides) && is_array($device->ott_overrides)) {
            // Priority 1: Room Level Override
            $selectedPackages = $device->ott_overrides;
        } elseif (!is_null($hotel->global_ott_settings) && is_array($hotel->global_ott_settings)) {
            // Priority 2: Hotel Global Default
            $selectedPackages = $hotel->global_ott_settings;
        } else {
            // Default: All Super Admin plan packages
            $selectedPackages = $planPackageNames;
        }

        // Priority 3: Super Admin Plan Limit (Strict intersection filtering)
        $effectivePackages = array_values(array_intersect($selectedPackages, $planPackageNames));

        // Format into requested object array format
        $allPlatforms = Plan::getAvailableOttPlatforms();
        $activeOttList = [];

        foreach ($allPlatforms as $ott) {
            if (in_array($ott['package'], $effectivePackages)) {
                // Compute clean ID slug
                $id = Str::slug($ott['name']);
                if ($ott['package'] === 'com.android.vending') {
                    $id = 'playstore';
                } elseif ($ott['package'] === 'com.netflix.mediaclient') {
                    $id = 'netflix';
                } elseif ($ott['package'] === 'in.startv.hotstar') {
                    $id = 'hotstar';
                } elseif ($ott['package'] === 'com.amazon.avod.thirdpartyclient') {
                    $id = 'prime';
                }

                $activeOttList[] = [
                    'id' => $id,
                    'name' => $ott['name'],
                    'package_name' => $ott['package'],
                ];
            }
        }

        return $activeOttList;
    }
}
