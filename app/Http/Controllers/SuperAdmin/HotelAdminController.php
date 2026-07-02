<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HotelAdminController extends Controller
{
    /**
     * Display a listing of the hotel admins.
     */
    public function index()
    {
        $hotels = HotelAdmin::with('plan')->orderBy('created_at', 'desc')->get();
        return view('super_admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel admin.
     */
    public function create()
    {
        $plans = Plan::where('status', true)->get();
        return view('super_admin.hotels.create', compact('plans'));
    }

    /**
     * Store a newly created hotel admin in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'room_count' => 'required|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
            'payment_status' => 'required|in:pending,paid',
            'approval_status' => 'required|in:pending,approved,disapproved',
        ]);

        $licenseKey = null;

        if ($request->plan_id && $request->payment_status === 'paid') {
            $licenseKey = sprintf(
                "%s-%s-%s-%s",
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        }

        HotelAdmin::create([
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'room_count' => $request->room_count,
            'plan_id' => $request->plan_id,
            'payment_status' => $request->payment_status,
            'approval_status' => $request->approval_status,
            'license_key' => $licenseKey,
            'status' => true,
        ]);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin created successfully!');
    }

    /**
     * Display the specified hotel admin.
     */
    public function show($id)
    {
        $hotel = HotelAdmin::with('plan')->findOrFail($id);
        return view('super_admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel admin.
     */
    public function edit($id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $plans = Plan::where('status', true)->get();
        return view('super_admin.hotels.edit', compact('hotel', 'plans'));
    }

    /**
     * Update the specified hotel admin in storage.
     */
    public function update(Request $request, $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email,' . $id,
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'room_count' => 'required|integer|min:1',
            'plan_id' => 'required|exists:plans,id',
            'payment_status' => 'required|in:pending,paid',
            'approval_status' => 'required|in:pending,approved,disapproved',
        ]);

        $data = [
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'room_count' => $request->room_count,
            'plan_id' => $request->plan_id,
            'payment_status' => $request->payment_status,
            'approval_status' => $request->approval_status,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        if (!$hotel->license_key) {
            $data['license_key'] = sprintf(
                "%s-%s-%s-%s",
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        }

        $hotel->update($data);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin updated successfully!');
    }

    /**
     * Toggle the status of a hotel admin (active/inactive).
     */
    public function toggleStatus($id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $hotel->status = !$hotel->status;
        $hotel->save();

        return response()->json([
            'success' => true,
            'status' => $hotel->status,
            'message' => 'Status updated to ' . ($hotel->status ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Update the approval status of a hotel admin.
     */
    public function toggleApproval(Request $request, $id)
    {
        $request->validate([
            'approval_status' => 'required|in:pending,approved,disapproved'
        ]);

        $hotel = HotelAdmin::findOrFail($id);
        $hotel->approval_status = $request->approval_status;
        $hotel->save();

        return response()->json([
            'success' => true,
            'approval_status' => $hotel->approval_status,
            'message' => 'Approval status updated to ' . ucfirst($hotel->approval_status)
        ]);
    }

    /**
     * Remove the specified hotel admin from storage.
     */
    public function destroy($id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $hotel->delete();

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin deleted successfully!');
    }
}
