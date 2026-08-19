<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomInfo;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class RoomInfoController extends Controller
{
    /**
     * Display a listing of room info items for logged-in hotel admin.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $roomInfos = RoomInfo::where('hotel_admin_id', $hotel->id)
                             ->orderBy('sr_no', 'asc')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('hotel_admin.room_infos.index', compact('roomInfos'));
    }

    /**
     * Store a newly created room info item with image compression.
     */
    public function store(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description cannot exceed 500 characters.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/room_infos',
                800,
                'room_info',
                1920
            );
        }

        RoomInfo::create([
            'hotel_admin_id' => $hotel->id,
            'sr_no' => $request->sr_no,
            'title' => $request->title,
            'icon' => $request->icon ?? 'fa-solid fa-bed',
            'image' => $imagePath,
            'description' => $request->description,
            'status' => true,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room Info added & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.room-infos.index')
                         ->with('success', 'Room Info added successfully!');
    }

    /**
     * Update the specified room info item.
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $roomInfo = RoomInfo::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description cannot exceed 500 characters.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);

        $imagePath = $roomInfo->image;
        if ($request->hasFile('image')) {
            if ($roomInfo->image) {
                ImageHelper::deleteFile($roomInfo->image);
            }
            $imagePath = ImageHelper::compressAndConvertToWebp(
                $request->file('image'),
                'uploads/room_infos',
                800,
                'room_info',
                1920
            );
        }

        $roomInfo->update([
            'sr_no' => $request->sr_no,
            'title' => $request->title,
            'icon' => $request->icon ?? 'fa-solid fa-bed',
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room Info updated & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.room-infos.index')
                         ->with('success', 'Room Info updated successfully!');
    }

    /**
     * Remove the specified room info item.
     */
    public function destroy($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $roomInfo = RoomInfo::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        if ($roomInfo->image) {
            ImageHelper::deleteFile($roomInfo->image);
        }

        $roomInfo->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room Info deleted & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.room-infos.index')
                         ->with('success', 'Room Info deleted successfully!');
    }

    /**
     * Toggle status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $roomInfo = RoomInfo::where('hotel_admin_id', $hotel->id)->findOrFail($id);

        $roomInfo->status = !$roomInfo->status;
        $roomInfo->save();

        return response()->json([
            'success' => true,
            'status' => $roomInfo->status,
            'message' => 'Room Info status updated to ' . ($roomInfo->status ? 'Active' : 'Inactive')
        ]);
    }
}
