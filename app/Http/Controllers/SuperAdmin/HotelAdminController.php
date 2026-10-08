<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\User;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HotelAdminController extends Controller
{
    /**
     * Display a listing of the hotel admins.
     */
    public function index()
    {
        $hotels = HotelAdmin::with(['plan', 'distributor', 'connectedDevices'])->orderBy('created_at', 'desc')->get();
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
            'city' => 'nullable|string|max:100',
            'room_count' => 'nullable|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
            'distributor_id' => 'nullable|exists:users,id',
            'payment_status' => 'required|in:pending,paid',
            'approval_status' => 'required|in:pending,approved,disapproved',
            'description' => 'nullable|string|max:1000',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ]);

        $roomCount = $request->input('room_count');
        if (!$roomCount) {
            if ($request->plan_id) {
                $selectedPlan = Plan::find($request->plan_id);
                $roomCount = $selectedPlan ? $selectedPlan->room_count : 25;
            } else {
                $roomCount = 25;
            }
        }

        $licenseKey = null;
        $purchaseDate = null;
        $expiryDate = null;

        if ($request->plan_id && $request->payment_status === 'paid') {
            $licenseKey = sprintf(
                "%s-%s-%s-%s",
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
            $purchaseDate = now();
            $expiryDate = now()->addDays(30);
        }

        $hotelData = [
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'city' => $request->city,
            'room_count' => $roomCount,
            'plan_id' => $request->plan_id,
            'distributor_id' => $request->distributor_id,
            'payment_status' => $request->payment_status,
            'approval_status' => $request->approval_status,
            'description' => $request->description,
            'license_key' => $licenseKey,
            'status' => true,
            'purchase_date' => $purchaseDate,
            'expiry_date' => $expiryDate,
        ];

        if ($request->hasFile('hotel_logo')) {
            $hotelData['hotel_logo'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_logo'),
                'uploads/hotel_logos',
                500,
                'logo',
                1200
            );
        }

        if ($request->hasFile('hotel_image')) {
            $hotelData['hotel_image'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_image'),
                'uploads/hotel_images',
                1000,
                'cover',
                2560
            );
        }

        HotelAdmin::create($hotelData);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Vendor created successfully!');
    }

    /**
     * Display the specified hotel admin.
     */
    public function show(int $id)
    {
        $hotel = HotelAdmin::with('plan')->findOrFail($id);
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
     * Update the specified hotel admin in storage.
     */
    public function update(Request $request, int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email,' . $id,
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'room_count' => 'nullable|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
            'distributor_id' => 'nullable|exists:users,id',
            'payment_status' => 'required|in:pending,paid',
            'approval_status' => 'required|in:pending,approved,disapproved',
            'description' => 'nullable|string|max:1000',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:purchase_date',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'slider_images' => 'nullable|array|max:10',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $roomCount = $request->input('room_count');
        if (!$roomCount) {
            if ($request->plan_id) {
                $selectedPlan = Plan::find($request->plan_id);
                $roomCount = $selectedPlan ? $selectedPlan->room_count : ($hotel->room_count ?: 25);
            } else {
                $roomCount = $hotel->room_count ?: 25;
            }
        }

        $data = [
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'city' => $request->city,
            'room_count' => $roomCount,
            'plan_id' => $request->plan_id,
            'distributor_id' => $request->distributor_id,
            'payment_status' => $request->payment_status,
            'approval_status' => $request->approval_status,
            'description' => $request->description,
        ];

        if ($request->filled('purchase_date')) {
            $data['purchase_date'] = $request->purchase_date;
        }

        if ($request->filled('expiry_date')) {
            $data['expiry_date'] = $request->expiry_date;
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        // Handle logo replacement with WebP conversion
        if ($request->hasFile('hotel_logo')) {
            if ($hotel->hotel_logo) {
                ImageHelper::deleteFile($hotel->hotel_logo);
            }
            $data['hotel_logo'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_logo'),
                'uploads/hotel_logos',
                500,
                'logo',
                1200
            );
        }

        // Handle cover image replacement with WebP conversion
        if ($request->hasFile('hotel_image')) {
            if ($hotel->hotel_image) {
                ImageHelper::deleteFile($hotel->hotel_image);
            }
            $data['hotel_image'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_image'),
                'uploads/hotel_images',
                1000,
                'cover',
                2560
            );
        }

        // Handle slider uploads with WebP conversion
        if ($request->hasFile('slider_images')) {
            $existingSliders = $hotel->slider_images ?? [];
            if (count($existingSliders) + count($request->file('slider_images')) <= 10) {
                foreach ($request->file('slider_images') as $file) {
                    $savedSlider = ImageHelper::compressAndConvertToWebp(
                        $file,
                        'uploads/hotel_sliders',
                        800,
                        'slider',
                        2560
                    );
                    $existingSliders[] = $savedSlider;
                }
                $data['slider_images'] = $existingSliders;
            }
        }

        if (!$hotel->license_key && $request->plan_id && $request->payment_status === 'paid') {
            $data['license_key'] = sprintf(
                "%s-%s-%s-%s",
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        }

        if ($request->plan_id && $request->payment_status === 'paid' && !$request->filled('purchase_date')) {
            if (!$hotel->purchase_date || $hotel->plan_id !== (int) $request->plan_id || $hotel->payment_status !== 'paid') {
                $data['purchase_date'] = now();
                if (!$request->filled('expiry_date')) {
                    $data['expiry_date'] = now()->addDays(30);
                }
            }
        }

        $hotel->update($data);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin updated successfully!');
    }

    /**
     * Toggle the status of a hotel admin (active/inactive).
     */
    public function toggleStatus(int $id)
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
    public function toggleApproval(Request $request, int $id)
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
    public function destroy(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        if ($hotel->hotel_logo) {
            ImageHelper::deleteFile($hotel->hotel_logo);
        }
        if ($hotel->hotel_image) {
            ImageHelper::deleteFile($hotel->hotel_image);
        }
        if ($hotel->slider_images && is_array($hotel->slider_images)) {
            foreach ($hotel->slider_images as $slider) {
                ImageHelper::deleteFile($slider);
            }
        }
        if ($hotel->hotel_gallery_images && is_array($hotel->hotel_gallery_images)) {
            foreach ($hotel->hotel_gallery_images as $gallery) {
                ImageHelper::deleteFile($gallery);
            }
        }

        $hotel->delete();

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin deleted successfully!');
    }
}
