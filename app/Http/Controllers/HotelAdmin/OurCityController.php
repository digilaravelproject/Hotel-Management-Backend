<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OurCity;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class OurCityController extends Controller
{
    /**
     * Display a listing of city attractions/places for logged-in hotel admin.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $cityPlaces = OurCity::where('hotel_admin_id', $hotel->id)
                             ->orderBy('sr_no', 'asc')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('hotel_admin.our_city.index', compact('cityPlaces'));
    }

    /**
     * Store a newly created city place/attraction with 16:9 WebP image compression.
     */
    public function store(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'attractions' => 'nullable|array|max:4',
            'attractions.*' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description payload cannot exceed 500 characters.',
            'attractions.max' => 'You can specify a maximum of 4 highlights or tags.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/our_city',
                800,
                'city_place',
                1920
            );
        }

        // Clean and slice attractions (max 4)
        $attractions = [];
        if ($request->has('attractions') && is_array($request->attractions)) {
            $attractions = array_values(array_filter($request->attractions, function ($val) {
                return !empty(trim($val));
            }));
            $attractions = array_slice($attractions, 0, 4);
        }

        OurCity::create([
            'hotel_admin_id' => $hotel->id,
            'sr_no' => $request->sr_no,
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description,
            'attractions' => $attractions,
            'status' => true,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'City attraction added & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.our-city.index')
                         ->with('success', 'City attraction added successfully!');
    }

    /**
     * Update the specified city attraction item.
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'attractions' => 'nullable|array|max:4',
            'attractions.*' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description payload cannot exceed 500 characters.',
            'attractions.max' => 'You can specify a maximum of 4 highlights or tags.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = $cityPlace->image;
        if ($request->hasFile('image')) {
            if ($cityPlace->image) {
                ImageHelper::deleteFile($cityPlace->image);
            }
            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/our_city',
                800,
                'city_place',
                1920
            );
        }

        // Clean and slice attractions (max 4)
        $attractions = [];
        if ($request->has('attractions') && is_array($request->attractions)) {
            $attractions = array_values(array_filter($request->attractions, function ($val) {
                return !empty(trim($val));
            }));
            $attractions = array_slice($attractions, 0, 4);
        }

        $cityPlace->update([
            'sr_no' => $request->sr_no,
            'title' => $request->title,
            'image' => $imagePath,
            'description' => $request->description,
            'attractions' => $attractions,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'City attraction updated & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.our-city.index')
                         ->with('success', 'City attraction updated successfully!');
    }

    /**
     * Remove the specified city attraction item.
     */
    public function destroy($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        if ($cityPlace->image) {
            ImageHelper::deleteFile($cityPlace->image);
        }

        $cityPlace->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'City attraction deleted & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.our-city.index')
                         ->with('success', 'City attraction deleted successfully!');
    }

    /**
     * Toggle status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $cityPlace->status = !$cityPlace->status;
        $cityPlace->save();

        return response()->json([
            'success' => true,
            'status' => $cityPlace->status,
            'message' => 'City attraction status updated to ' . ($cityPlace->status ? 'Active' : 'Inactive')
        ]);
    }
}
