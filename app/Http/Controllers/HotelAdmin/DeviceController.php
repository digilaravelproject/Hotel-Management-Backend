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
        $activeGuests = Guest::query()->where('hotel_id', $hotel->id)
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

    /**
     * Pair TV Device by 8-Digit Pairing Code from Hotel Admin Web UI.
     */
    public function pairDeviceByCode(Request $request, \App\Services\TvLoginService $tvLoginService)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'pair_code' => 'required|string',
            'room_no' => 'required|string|max:50',
        ]);

        $cleanCode = strtoupper(trim($request->pair_code));
        $session = \App\Models\TvPairSession::where('pair_code', $cleanCode)
            ->where('status', 'pending')
            ->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired 8-digit pair code. Please refresh TV code.'], 404);
        }

        if ($session->isExpired()) {
            $session->update(['status' => 'expired']);
            return response()->json(['success' => false, 'message' => 'This 8-digit pair code has expired. Please refresh TV code.'], 410);
        }

        try {
            // Authenticate TV using existing service logic (validates limits & idempotency)
            $result = $tvLoginService->authenticateTv([
                'license_key' => $hotel->license_key,
                'room_no' => $request->room_no,
                'deviceId' => $session->device_id,
                'macAddress' => $session->mac_address,
                'ipAddress' => $session->ip_address,
                'model' => $session->model,
                'brand' => $session->brand,
                'osVersion' => $session->os_version,
            ]);

            // Mark session as paired so TV App polling receives full login response
            $session->update([
                'status' => 'paired',
                'hotel_admin_id' => $hotel->id,
                'assigned_room_no' => $request->room_no,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'TV Room ' . $request->room_no . ' paired and connected successfully!'
            ]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to pair device: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show individual Room / Device OTT configuration view.
     */
    public function showRoomOtt(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        
        $allPlatforms = \App\Models\Plan::getAvailableOttPlatforms();
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        // Available OTTs strictly bound by Super Admin plan
        $availablePlatforms = array_values(array_filter($allPlatforms, function ($ott) use ($planPackageNames) {
            return in_array($ott['package'], $planPackageNames);
        }));

        $globalSettings = $hotel->global_ott_settings ?? $planPackageNames;
        $hasOverride = !is_null($device->ott_overrides);
        $currentDeviceSettings = $hasOverride ? $device->ott_overrides : $globalSettings;

        return view('hotel_admin.devices.ott', compact('hotel', 'device', 'plan', 'availablePlatforms', 'currentDeviceSettings', 'hasOverride', 'globalSettings'));
    }

    /**
     * Update individual Room / Device OTT configuration.
     */
    public function updateRoomOtt(Request $request, int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $hotel->loadMissing('plan');
        $plan = $hotel->plan;
        $planPackageNames = $plan && is_array($plan->ott_platforms) ? $plan->ott_platforms : [];

        $request->validate([
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $selected = $request->input('ott_platforms', []);
        $validSelected = array_values(array_intersect($selected, $planPackageNames));

        $device->update([
            'ott_overrides' => $validSelected,
        ]);

        return redirect()->back()->with('success', 'Room ' . $device->room_no . ' OTT configuration saved.');
    }

    /**
     * Reset Room / Device OTT configuration to Hotel Global Default.
     */
    public function resetRoomOtt(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $device->update([
            'ott_overrides' => null,
        ]);

        return redirect()->back()->with('success', 'Room ' . $device->room_no . ' OTT configuration reset to Hotel Global Default.');
    }

    /**
     * Show individual Room / Device Menu configuration view.
     */
    public function showRoomMenus(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $defaultMenus = \App\Services\MenuResolverService::getDefaultMenus();
        
        $globalSettings = $hotel->global_menu_settings ?? [];
        $hasOverride = !is_null($device->menu_overrides);

        $currentSettings = $hasOverride ? $device->menu_overrides : $globalSettings;

        return view('hotel_admin.devices.menus', compact('hotel', 'device', 'defaultMenus', 'currentSettings', 'hasOverride', 'globalSettings'));
    }

    /**
     * Update individual Room / Device Menu configuration.
     */
    public function updateRoomMenus(Request $request, int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $defaultMenus = \App\Services\MenuResolverService::getDefaultMenus();
        $inputSettings = $request->input('menus', []);

        $formattedSettings = [];
        foreach ($defaultMenus as $menu) {
            $formattedSettings[$menu['id']] = isset($inputSettings[$menu['id']]) ? 'show' : 'hide';
        }

        $device->update([
            'menu_overrides' => $formattedSettings,
        ]);

        return redirect()->back()->with('success', 'Room ' . $device->room_no . ' Menu configuration saved.');
    }

    /**
     * Reset Room / Device Menu configuration to Hotel Global Default.
     */
    public function resetRoomMenus(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $device = $hotel->connectedDevices()->findOrFail($id);
        $device->update([
            'menu_overrides' => null,
        ]);

        return redirect()->back()->with('success', 'Room ' . $device->room_no . ' Menu configuration reset to Hotel Global Default.');
    }
}
