<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use Illuminate\Support\Facades\Auth;

class AmenityController extends Controller
{
    /**
     * Display a listing of amenities for the logged-in hotel admin.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenities = Amenity::where('hotel_admin_id', $hotel->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('hotel_admin.amenities.index', compact('amenities'));
    }

    /**
     * Store a newly created amenity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();

        Amenity::create([
            'hotel_admin_id' => $hotel->id,
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-solid fa-square-check', // fallback icon
            'description' => $request->description,
            'status' => true,
        ]);

        return redirect()->route('hotel.amenities.index')
                         ->with('success', 'Amenity added successfully!');
    }

    /**
     * Update the specified amenity.
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenity = Amenity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $amenity->update([
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-solid fa-square-check',
            'description' => $request->description,
        ]);

        return redirect()->route('hotel.amenities.index')
                         ->with('success', 'Amenity updated successfully!');
    }

    /**
     * Remove the specified amenity from storage.
     */
    public function destroy($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $amenity = Amenity::where('hotel_admin_id', $hotel->id)->findOrFail($id);
        $amenity->delete();

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
            'message' => 'Amenity is now ' . ($amenity->status ? 'Active' : 'Inactive')
        ]);
    }
}
