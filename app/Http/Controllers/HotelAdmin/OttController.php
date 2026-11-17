<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class OttController extends Controller
{
    /**
     * Display the current active package plan and OTT features.
     */
    public function myPackage()
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $hasActivePlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());
        if (!$hasActivePlan) {
            return redirect()->route('hotel.subscription');
        }

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $allPlatforms = Plan::getAvailableOttPlatforms();
        
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        $assignedPlatforms = array_filter($allPlatforms, function ($ott) use ($planPackageNames) {
            return in_array($ott['package'], $planPackageNames);
        });

        $availablePlans = Plan::where('status', true)->where('is_hidden', false)->orderBy('room_count', 'asc')->get();

        return view('hotel_admin.ott.package', compact('hotel', 'plan', 'assignedPlatforms', 'availablePlans'));
    }

    /**
     * Display and manage Global OTT Settings for the Hotel.
     */
    public function globalSettings()
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $hasActivePlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());
        if (!$hasActivePlan) {
            return redirect()->route('hotel.subscription');
        }

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $allPlatforms = Plan::getAvailableOttPlatforms();

        // Strict Plan OTT Boundary: Only show platforms explicitly enabled in the Super Admin plan
        if (!$plan || !$plan->hasOtt() || empty($plan->ott_platforms)) {
            $availablePlatforms = [];
            $planPackageNames = [];
        } else {
            $planPackageNames = array_values((array) $plan->ott_platforms);
            $availablePlatforms = array_values(array_filter($allPlatforms, function ($ott) use ($planPackageNames) {
                return in_array($ott['package'], $planPackageNames);
            }));
        }

        // Current global settings: only keep those that belong to the active plan
        $savedSettings = $hotel->global_ott_settings;
        if (is_array($savedSettings)) {
            $currentGlobalSettings = array_values(array_intersect($savedSettings, $planPackageNames));
        } else {
            $currentGlobalSettings = $planPackageNames;
        }

        return view('hotel_admin.ott.global_settings', compact('hotel', 'plan', 'availablePlatforms', 'currentGlobalSettings'));
    }

    /**
     * Update Global OTT Settings for the Hotel.
     */
    public function updateGlobalSettings(Request $request)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $hasActivePlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());
        if (!$hasActivePlan) {
            return redirect()->route('hotel.subscription');
        }

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;

        $request->validate([
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $selected = $request->input('ott_platforms', []);

        // Filter out any OTT not allowed by Super Admin plan
        if (!$plan || !$plan->hasOtt() || empty($plan->ott_platforms)) {
            $validSelected = [];
        } else {
            $planPackageNames = array_values((array) $plan->ott_platforms);
            $validSelected = array_values(array_intersect($selected, $planPackageNames));
        }

        $hotel->update([
            'global_ott_settings' => $validSelected,
        ]);

        // Invalidate TV version cache for real-time sync across all hotel TVs
        \App\Services\TvVersionCacheService::clearHotelCache((int) $hotel->id, 'OTT', null, true);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Global OTT Apps updated & synced to TVs in real-time!',
                'data' => $validSelected,
                'count' => count($validSelected)
            ]);
        }

        return redirect()->back()->with('success', 'Global OTT Platform settings updated successfully.');
    }
}
