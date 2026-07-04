<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConnectedDevice;

class DeviceController extends Controller
{
    /**
     * Display a listing of connected devices for the authenticated hotel owner.
     */
    public function index()
    {
        $hotel = auth()->guard('hotel_admin')->user();
        
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        // Paginate hotel devices
        $devices = $hotel->connectedDevices()->latest()->paginate(15);

        return view('hotel_admin.devices.index', compact('devices', 'hotel'));
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
