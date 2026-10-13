<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Show global commission settings.
     */
    public function settings()
    {
        $setting = CommissionSetting::getSetting();
        return view('super_admin.commissions.settings', compact('setting'));
    }

    /**
     * Update global commission settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_percentage' => 'required|numeric|between:0,100',
        ], [
            'default_percentage.required' => 'Default commission percentage is required.',
            'default_percentage.numeric' => 'Commission percentage must be a valid number.',
            'default_percentage.between' => 'Commission percentage must be between 0% and 100%.',
        ]);

        $setting = CommissionSetting::getSetting();
        $setting->update([
            'default_percentage' => $validated['default_percentage'],
        ]);

        CommissionSetting::flushCache();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Global commission setting updated successfully.',
                'data' => $setting,
            ]);
        }

        return redirect()->back()->with('success', "Default distributor commission set to {$setting->default_percentage}%.");
    }
}
