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
        $query = ConnectedDevice::with(['hotelAdmin.plan', 'hotelAdmin.distributor']);

        if ($hotelId) {
            $query->where('hotel_admin_id', $hotelId);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('room_no', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('hotelAdmin', function ($hq) use ($search) {
                      $hq->where('hotel_name', 'like', "%{$search}%")
                        ->orWhere('license_key', 'like', "%{$search}%");
                  });
            });
        }

        $devices = $query->latest()->paginate(15)->withQueryString();
        $hotels = HotelAdmin::query()->where('status', true)->orderBy('hotel_name')->get();
        $selectedHotel = $hotelId ? HotelAdmin::query()->find($hotelId) : null;

        return view('super_admin.devices.index', compact('devices', 'hotels', 'selectedHotel'));
    }

    /**
     * Delete/Disconnect a device.
     */
    public function destroy(int $id)
    {
        $device = ConnectedDevice::with('hotelAdmin')->findOrFail($id);
        $roomNo = $device->room_no;
        $hotelName = $device->hotelAdmin->hotel_name ?? 'Hotel';
        $device->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Device for Room {$roomNo} ({$hotelName}) disconnected successfully."
            ]);
        }

        return redirect()->back()->with('success', "Device for Room {$roomNo} ({$hotelName}) disconnected successfully.");
    }
}
