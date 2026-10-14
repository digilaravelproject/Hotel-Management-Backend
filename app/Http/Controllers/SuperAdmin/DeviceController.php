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
        $type = $request->query('type', 'all');

        $query = ConnectedDevice::with(['hotelAdmin.plan', 'hotelAdmin.distributor']);

        // Hotel Filter
        if ($hotelId) {
            $query->where('hotel_admin_id', $hotelId);
        }

        // Demo vs Live Filter
        if ($type === 'demo') {
            $query->where('is_demo', true);
        } elseif ($type === 'live') {
            $query->where('is_demo', false);
        } elseif ($type === 'active_demo') {
            $query->where('is_demo', true)
                  ->where('demo_status', '!=', 'expired')
                  ->where(function ($q) {
                      $q->whereNull('demo_expires_at')->orWhere('demo_expires_at', '>', now());
                  });
        } elseif ($type === 'expired_demo') {
            $query->where('is_demo', true)
                  ->where(function ($q) {
                      $q->where('demo_status', 'expired')
                        ->orWhere('demo_expires_at', '<=', now());
                  });
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
                  ->orWhere('demo_status', 'like', "%{$search}%")
                  ->orWhereHas('hotelAdmin', function ($hq) use ($search) {
                      $hq->where('hotel_name', 'like', "%{$search}%")
                        ->orWhere('license_key', 'like', "%{$search}%");
                  });
            });
        }

        $devices = $query->latest()->paginate(20)->withQueryString();
        $hotels = HotelAdmin::query()->where('status', true)->orderBy('hotel_name')->get();
        $selectedHotel = $hotelId ? HotelAdmin::query()->find($hotelId) : null;

        // Statistics for Demo & Live TV badges
        $baseCountQuery = ConnectedDevice::query();
        if ($hotelId) {
            $baseCountQuery->where('hotel_admin_id', $hotelId);
        }

        $totalCount = (clone $baseCountQuery)->count();
        $liveCount = (clone $baseCountQuery)->where('is_demo', false)->count();
        $demoCount = (clone $baseCountQuery)->where('is_demo', true)->count();
        $demoActiveCount = (clone $baseCountQuery)
            ->where('is_demo', true)
            ->where('demo_status', '!=', 'expired')
            ->where(function ($q) {
                $q->whereNull('demo_expires_at')->orWhere('demo_expires_at', '>', now());
            })->count();
        $demoExpiredCount = (clone $baseCountQuery)
            ->where('is_demo', true)
            ->where(function ($q) {
                $q->where('demo_status', 'expired')
                  ->orWhere('demo_expires_at', '<=', now());
            })->count();

        return view('super_admin.devices.index', compact(
            'devices',
            'hotels',
            'selectedHotel',
            'type',
            'totalCount',
            'liveCount',
            'demoCount',
            'demoActiveCount',
            'demoExpiredCount'
        ));
    }

    /**
     * Delete/Disconnect a device.
     * Note: Demo TVs are never permanently removed on disconnect;
     * they are marked as expired so history & trial records remain visible.
     */
    public function destroy(int $id)
    {
        $device = ConnectedDevice::with('hotelAdmin')->findOrFail($id);
        $roomNo = $device->room_no;
        $hotelName = $device->hotelAdmin->hotel_name ?? 'Hotel';

        if ($device->is_demo) {
            // For Demo TVs: Do NOT delete from DB. Mark as expired/disconnected and invalidate token.
            $device->update([
                'demo_status' => 'expired',
                'demo_expires_at' => ($device->demo_expires_at && $device->demo_expires_at->isPast()) ? $device->demo_expires_at : now(),
                'api_token' => null,
            ]);

            $message = "Demo TV for Room {$roomNo} ({$hotelName}) disconnected and marked as Expired. It will remain in Demo archive.";
        } else {
            // Regular hotel TV: normal delete to release room pairing
            $device->delete();
            $message = "Device for Room {$roomNo} ({$hotelName}) disconnected successfully.";
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_demo' => (bool)$device->is_demo,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
