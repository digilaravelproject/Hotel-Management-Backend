<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\User;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelAdminController extends Controller
{
    /**
     * Inject reusable HotelService layer.
     */
    public function __construct(
        protected HotelService $hotelService
    ) {}

    /**
     * Display a listing of the hotel admins.
     */
    public function index()
    {
        $hotels = HotelAdmin::with(['plan', 'distributor', 'connectedDevices'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super_admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel admin.
     */
    public function create()
    {
        $plans = Plan::query()->where('status', '=', true)->get();
        $distributors = User::role('distributor')->where('status', true)->get();

        return view('super_admin.hotels.create', compact('plans', 'distributors'));
    }

    /**
     * Store a newly created hotel admin in storage using FormRequest and Service.
     */
    public function store(StoreHotelRequest $request)
    {
        $this->hotelService->createHotel(
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image')
        );

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Vendor created successfully!');
    }

    /**
     * Display the specified hotel admin.
     */
    public function show(int $id)
    {
        $hotel = HotelAdmin::with(['plan', 'distributor', 'connectedDevices'])->findOrFail($id);

        return view('super_admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel admin.
     */
    public function edit(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $plans = Plan::query()->where('status', '=', true)->get();
        $distributors = User::role('distributor')->where('status', true)->get();

        return view('super_admin.hotels.edit', compact('hotel', 'plans', 'distributors'));
    }

    /**
     * Update the specified hotel admin in storage using FormRequest and Service.
     */
    public function update(UpdateHotelRequest $request, int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        $this->hotelService->updateHotel(
            $hotel,
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image'),
            $request->file('slider_images', [])
        );

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin updated successfully!');
    }

    /**
     * Toggle the status of a hotel admin (active/inactive).
     */
    public function toggleStatus(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $newStatus = $this->hotelService->toggleStatus($hotel);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status updated to ' . ($newStatus ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Update the approval status of a hotel admin.
     */
    public function toggleApproval(Request $request, int $id)
    {
        $request->validate([
            'approval_status' => 'required|in:pending,approved,disapproved'
        ]);

        $hotel = HotelAdmin::findOrFail($id);
        $approvalStatus = $this->hotelService->updateApprovalStatus($hotel, $request->approval_status);

        return response()->json([
            'success' => true,
            'approval_status' => $approvalStatus,
            'message' => 'Approval status updated to ' . ucfirst($approvalStatus)
        ]);
    }

    /**
     * Remove the specified hotel admin from storage.
     */
    public function destroy(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $this->hotelService->deleteHotel($hotel);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin deleted successfully!');
    }
}
