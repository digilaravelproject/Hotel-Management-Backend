<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use App\Models\HotelAdmin;

class AmenityController extends Controller
{
    /**
     * Display a listing of amenities for a specific hotel.
     */
    public function index($hotelId)
    {
        $hotel = HotelAdmin::findOrFail($hotelId);
        $amenities = Amenity::where('hotel_admin_id', $hotelId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('super_admin.hotels.amenities', compact('hotel', 'amenities'));
    }

    /**
     * Update the specified amenity.
     */
    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);

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

        return redirect()->route('super-admin.hotels.amenities', $amenity->hotel_admin_id)
                         ->with('success', 'Amenity updated successfully!');
    }

    /**
     * Remove the specified amenity from storage.
     */
    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $hotelId = $amenity->hotel_admin_id;
        $amenity->delete();

        return redirect()->route('super-admin.hotels.amenities', $hotelId)
                         ->with('success', 'Amenity deleted successfully!');
    }

    /**
     * Toggle status (active/deactive).
     */
    public function toggleStatus($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->status = !$amenity->status;
        $amenity->save();

        return response()->json([
            'success' => true,
            'status' => $amenity->status,
            'message' => 'Amenity status updated successfully!'
        ]);
    }
}
