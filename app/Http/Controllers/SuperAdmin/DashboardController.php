<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Inject reusable service layer.
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Show the Super Admin Control Dashboard.
     */
    public function index(Request $request)
    {
        $dashboardData = $this->dashboardService->getSuperAdminDashboardData($request->all());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dashboardData,
            ]);
        }

        return view('super_admin.dashboard', $dashboardData);
    }

    /**
     * Show Super Admin Profile Edit Form.
     */
    public function profileForm()
    {
        $superAdmin = auth()->guard('super_admin')->user();
        return view('super_admin.profile', compact('superAdmin'));
    }

    /**
     * Update the authenticated Super Admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->guard('super_admin')->user();

        $request->validate([
            'email' => 'required|email|unique:super_admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('super-admin.profile')
                         ->with('success', 'Super Admin profile credentials updated successfully!');
    }
}
