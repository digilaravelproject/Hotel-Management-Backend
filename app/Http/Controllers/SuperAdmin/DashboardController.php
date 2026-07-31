<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\Plan;

class DashboardController extends Controller
{
    /**
     * Show the Super Admin Dashboard.
     */
    public function index()
    {
        $totalHotels = HotelAdmin::count();
        $activeHotels = HotelAdmin::where('status', true)->where('approval_status', 'approved')->count();
        $pendingApprovals = HotelAdmin::where('approval_status', 'pending')->count();
        
        // Calculate estimated monthly revenue from active paid hotels
        $monthlyRevenue = HotelAdmin::where('payment_status', 'paid')
            ->join('plans', 'hotel_admins.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        $totalPlans = Plan::count();
        $recentHotels = HotelAdmin::with('plan')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('super_admin.dashboard', compact(
            'totalHotels',
            'activeHotels',
            'pendingApprovals',
            'monthlyRevenue',
            'totalPlans',
            'recentHotels'
        ));
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
    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $admin = auth()->guard('super_admin')->user();

        $request->validate([
            'email' => 'required|email|unique:super_admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('super-admin.profile')->with('success', 'Super Admin profile credentials updated successfully!');
    }
}
