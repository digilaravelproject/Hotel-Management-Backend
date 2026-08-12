<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Display a listing of guests for the logged-in hotel admin.
     */
    public function index(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $query = Guest::query()->where('hotel_id', $hotel->id);

        if ($request->filled('room')) {
            $query->where('room_number', $request->input('room'));
        }

        $guests = $query->orderBy('created_at', 'desc')->get();

        return view('hotel_admin.guests.index', compact('guests'));
    }

    /**
     * Store a newly created guest.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'room_number' => 'required|string|max:50',
            'check_in_datetime' => 'required|date',
            'check_out_datetime' => 'nullable|date|after:check_in_datetime',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();

        Guest::create([
            'hotel_id' => $hotel->id,
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'room_number' => $request->room_number,
            'check_in_datetime' => $request->check_in_datetime,
            'check_out_datetime' => $request->check_out_datetime,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Guest checked in & synced to TV in real-time!'
            ]);
        }

        return redirect()->route('hotel.guests.index')
            ->with('success', 'Guest checked in successfully!');
    }

    /**
     * Update the specified guest's details.
     */
    public function update(Request $request, int $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $guest = Guest::query()->where('hotel_id', $hotel->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'room_number' => 'required|string|max:50',
            'check_in_datetime' => 'required|date',
            'check_out_datetime' => 'nullable|date|after:check_in_datetime',
        ]);

        $guest->update([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'room_number' => $request->room_number,
            'check_in_datetime' => $request->check_in_datetime,
            'check_out_datetime' => $request->check_out_datetime,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Guest details updated & synced to TV in real-time!'
            ]);
        }

        return redirect()->route('hotel.guests.index')
            ->with('success', 'Guest details updated successfully!');
    }

    /**
     * Checkout the guest (set check_out_datetime to now).
     */
    public function checkout(int $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $guest = Guest::query()->where('hotel_id', $hotel->id)->findOrFail($id);

        $guest->update([
            'check_out_datetime' => now(),
        ]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Guest checked out & synced to TV in real-time!'
            ]);
        }

        return redirect()->route('hotel.guests.index')
            ->with('success', 'Guest checked out successfully!');
    }

    /**
     * Remove the specified guest from storage.
     */
    public function destroy(int $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        Guest::query()->where('hotel_id', $hotel->id)->where('id', $id)->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Guest deleted & synced in real-time!'
            ]);
        }

        return redirect()->route('hotel.guests.index')
            ->with('success', 'Guest deleted successfully!');
    }
}
