<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConnectedDevice;
use App\Models\Guest;

class DeviceController extends Controller
{
    /**
     * Display a listing of connected devices for the authenticated hotel owner.
     */
    public function index(Request $request)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $query = $hotel->connectedDevices()->latest();

        // Filter by room number if provided
        if ($request->filled('room_no')) {
            $query->where('room_no', $request->input('room_no'));
        }

        $devices = $query->paginate(15);

        // Fetch active guests to cross-reference occupied rooms
        $now = now();
        $activeGuests = Guest::where('hotel_id', $hotel->id)
            ->where('check_in_datetime', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('check_out_datetime')
                  ->orWhere('check_out_datetime', '>=', $now);
            })
            ->get()
            ->keyBy('room_number');

        return view('hotel_admin.devices.index', compact('devices', 'hotel', 'activeGuests'));
    }

    /**
     * Delete/Disconnect a device.
     */
    public function destroy(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $device->delete();

        return redirect()->back()->with('success', 'Device disconnected successfully.');
    }
}
