<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;

class DeviceController extends Controller
{
    /**
     * Display a listing of connected devices.
     */
    public function index(Request $request)
    {
        $hotelId = $request->query('hotel_id');
        $query = ConnectedDevice::with('hotelAdmin.plan');

        if ($hotelId) {
            $query->where('hotel_admin_id', $hotelId);
        }

        $devices = $query->latest()->paginate(15);
        $hotels = HotelAdmin::query()->where('status', true)->get();
        $selectedHotel = $hotelId ? HotelAdmin::query()->find($hotelId) : null;

        return view('super_admin.devices.index', compact('devices', 'hotels', 'selectedHotel'));
    }

    /**
     * Delete/Disconnect a device.
     */
    public function destroy(int $id)
    {
        $device = ConnectedDevice::findOrFail($id);
        $device->delete();

        return redirect()->back()->with('success', 'Device disconnected successfully.');
    }
}
