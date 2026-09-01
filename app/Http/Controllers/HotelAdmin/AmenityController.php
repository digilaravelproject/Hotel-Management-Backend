<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    /**
     * Display a listing of amenities for the logged-in hotel admin, ordered by sr_no.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenities = Amenity::where('hotel_admin_id', $hotel->id)
                            ->orderBy('sr_no', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('hotel_admin.amenities.index', compact('amenities'));
    }

    /**
     * Store a newly created amenity with image compression & WebP conversion.
     */
    public function store(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120', // Max 5MB (5120KB)
        ], [
            'description.max' => 'Description payload cannot exceed 250 characters.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/amenities',
                800,
                'amenity',
                1920
            );
        }

        Amenity::create([
            'hotel_admin_id' => $hotel->id,
            'sr_no' => $request->sr_no,
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-solid fa-square-check',
            'image' => $imagePath,
            'description' => $request->description,
            'status' => true,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Amenity added & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.amenities.index')
                         ->with('success', 'Amenity added & compressed to WebP successfully!');
    }

    /**
     * Update the specified amenity.
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenity = Amenity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120', // Max 5MB (5120KB)
        ], [
            'description.max' => 'Description payload cannot exceed 250 characters.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = $amenity->image;
        if ($request->hasFile('image')) {
            // Remove old image file safely
            if ($amenity->image) {
                ImageHelper::deleteFile($amenity->image);
            }

            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/amenities',
                800,
                'amenity',
                1920
            );
        }

        $amenity->update([
            'sr_no' => $request->sr_no,
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-solid fa-square-check',
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Amenity updated & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.amenities.index')
                         ->with('success', 'Amenity updated & compressed to WebP successfully!');
    }

    /**
     * Remove the specified amenity from storage.
     */
    public function destroy($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenity = Amenity::where('hotel_admin_id', $hotel->id)->findOrFail($id);
        
        if ($amenity->image) {
            ImageHelper::deleteFile($amenity->image);
        }

        $amenity->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Amenity deleted & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.amenities.index')
                         ->with('success', 'Amenity deleted successfully!');
    }

    /**
     * Toggle the status of an amenity (active/inactive).
     */
    public function toggleStatus($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenity = Amenity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $amenity->status = !$amenity->status;
        $amenity->save();

        return response()->json([
            'success' => true,
            'status' => $amenity->status,
            'message' => 'Amenity status updated to ' . ($amenity->status ? 'Active' : 'Inactive')
        ]);
    }
}
