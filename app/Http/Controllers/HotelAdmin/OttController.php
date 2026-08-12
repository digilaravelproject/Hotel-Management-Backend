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

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $allPlatforms = Plan::getAvailableOttPlatforms();
        
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        $assignedPlatforms = array_filter($allPlatforms, function ($ott) use ($planPackageNames) {
            return in_array($ott['package'], $planPackageNames);
        });

        return view('hotel_admin.ott.package', compact('hotel', 'plan', 'assignedPlatforms'));
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

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $allPlatforms = Plan::getAvailableOttPlatforms();

        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        // Filter platforms allowed by Super Admin plan
        $availablePlatforms = array_values(array_filter($allPlatforms, function ($ott) use ($planPackageNames) {
            return in_array($ott['package'], $planPackageNames);
        }));

        // If hotel global settings null, default to all plan platforms enabled
        $currentGlobalSettings = $hotel->global_ott_settings ?? $planPackageNames;

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

        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        $request->validate([
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $selected = $request->input('ott_platforms', []);

        // Filter out any OTT not allowed by Super Admin plan
        $validSelected = array_values(array_intersect($selected, $planPackageNames));

        $hotel->update([
            'global_ott_settings' => $validSelected,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Global OTT Apps updated & synced to TVs in real-time!',
                'data' => $validSelected
            ]);
        }

        return redirect()->back()->with('success', 'Global OTT Platform settings updated successfully.');
    }
}
