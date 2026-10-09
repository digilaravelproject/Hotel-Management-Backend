<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $hotel->loadMissing(['plan', 'distributor']);
        $query = $hotel->connectedDevices()->with(['hotelAdmin.plan', 'hotelAdmin.distributor'])->latest();

        // Filter by room number if provided
        if ($request->filled('room_no')) {
            $query->where('room_no', $request->input('room_no'));
        }

        // General search
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('room_no', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $devices = $query->paginate(20)->withQueryString();

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
     * Store / Register a new TV device directly for the hotel.
     */
    public function store(Request $request)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'room_no' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:100',
            'device_id' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:45',
        ]);

        $roomNo = trim($request->room_no);

        // Check Allowed Device Limit
        $allowedLimit = $hotel->allowed_device_limit;
        $currentCount = $hotel->connectedDevices()->count();

        // Check if device already exists for this room in this hotel
        $existingDevice = $hotel->connectedDevices()->where('room_no', $roomNo)->first();
        if (!$existingDevice && $currentCount >= $allowedLimit) {
            return response()->json([
                'success' => false,
                'message' => "Device limit reached ({$allowedLimit} TVs allowed for your subscription plan). Please upgrade plan."
            ], 403);
        }

        // Generate clean unique device ID if not provided
        $cleanRoomSlug = preg_replace('/[^A-Za-z0-9]/', '', $roomNo) ?: '101';
        $deviceId = !empty($request->device_id) 
            ? trim($request->device_id) 
            : 'TV-' . strtoupper(Str::random(4)) . '-RM' . $cleanRoomSlug;

        while (ConnectedDevice::where('device_id', $deviceId)->when($existingDevice, fn($q) => $q->where('id', '!=', $existingDevice->id))->exists()) {
            $deviceId = 'TV-' . strtoupper(Str::random(6)) . '-RM' . $cleanRoomSlug;
        }

        // Generate MAC address if not provided
        $macAddress = !empty($request->mac_address)
            ? strtoupper(trim($request->mac_address))
            : strtoupper(implode(':', str_split(bin2hex(random_bytes(6)), 2)));

        if ($existingDevice) {
            $existingDevice->update([
                'brand' => $request->brand ?: ($existingDevice->brand ?: 'Smart TV'),
                'model' => $request->model ?: ($existingDevice->model ?: 'Android TV'),
                'mac_address' => !empty($request->mac_address) ? $macAddress : $existingDevice->mac_address,
                'ip_address' => $request->ip_address ?: ($existingDevice->ip_address ?: request()->ip()),
            ]);
            $device = $existingDevice;
            $msg = "Room {$roomNo} TV device updated successfully!";
        } else {
            $device = $hotel->connectedDevices()->create([
                'room_no' => $roomNo,
                'device_id' => $deviceId,
                'mac_address' => $macAddress,
                'brand' => $request->brand ?: 'Smart TV',
                'model' => $request->model ?: 'Android TV',
                'ip_address' => $request->ip_address ?: request()->ip(),
                'api_token' => Str::random(80),
            ]);
            $msg = "Room {$roomNo} TV device connected and added successfully!";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'device' => $device,
            ], 201);
        }

        return redirect()->route('hotel.devices.index')->with('success', $msg);
    }

    /**
     * Delete/Disconnect a device belonging to the authenticated hotel.
     */
    public function destroy(int $id)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            abort(403, 'Unauthorized.');
        }

        // Strict authorization: Ensure device belongs only to this hotel
        $device = $hotel->connectedDevices()->findOrFail($id);
        $roomNo = $device->room_no;
        $device->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Device for Room {$roomNo} disconnected successfully."
            ]);
        }

        return redirect()->back()->with('success', "Device for Room {$roomNo} disconnected successfully.");
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
        $rawCode = str_replace(['-', ' '], '', $cleanCode);
        $formattedCode = strlen($rawCode) === 8 ? substr($rawCode, 0, 4) . '-' . substr($rawCode, 4, 4) : $cleanCode;

        $session = \App\Models\TvPairSession::where(function($q) use ($cleanCode, $rawCode, $formattedCode) {
            $q->where('pair_code', $cleanCode)
              ->orWhere('pair_code', $rawCode)
              ->orWhere('pair_code', $formattedCode);
        })
        ->whereIn('status', ['pending', 'paired'])
        ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Pairing code "' . $cleanCode . '" was not found. Please verify the code displayed on your TV screen and make sure the TV is online.',
            ], 404);
        }

        if ($session->isExpired()) {
            $session->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => 'This pairing code has expired. Please refresh the code on your TV screen.',
            ], 410);
        }

        try {
            // Authenticate TV using existing service logic (validates limits & idempotency)
            $result = $tvLoginService->authenticateTv([
                'license_key' => $hotel->license_key,
                'room_no' => trim($request->room_no),
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
                'assigned_room_no' => trim($request->room_no),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'TV Room ' . trim($request->room_no) . ' paired and connected successfully!'
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room ' . $device->room_no . ' OTT configuration synced in real-time!'
            ]);
        }

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

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room ' . $device->room_no . ' OTT reset & synced in real-time!'
            ]);
        }

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
        $catalog = \App\Services\MenuResolverService::getItemCatalog();
        
        $globalSettings = $hotel->global_menu_settings ?? [];
        $hasOverride = !is_null($device->menu_overrides);

        $currentSettings = $hasOverride ? $device->menu_overrides : $globalSettings;

        return view('hotel_admin.devices.menus', compact('hotel', 'device', 'catalog', 'currentSettings', 'hasOverride', 'globalSettings'));
    }

    /**
     * Update individual Room / Device Menu configuration.
     */
    public function updateRoomMenus(Request $request, int $id)
    {
        try {
            $hotel = auth()->guard('hotel_admin')->user();
            if (!$hotel) {
                return redirect()->route('hotel.login');
            }

            $device = $hotel->connectedDevices()->findOrFail($id);
            $catalog = \App\Services\MenuResolverService::getItemCatalog();
            $inputSettings = $request->input('menus', []);

            $formattedSettings = [];
            foreach ($catalog as $itemId => $meta) {
                $formattedSettings[$itemId] = isset($inputSettings[$itemId]) ? 'show' : 'hide';
            }

            $device->update([
                'menu_overrides' => $formattedSettings,
            ]);

            try {
                event(new \App\Events\TvConfigUpdatedEvent($hotel->id, 'MENU', $device->room_no, ['action' => 'update']));
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning('TvConfigUpdatedEvent failed: ' . $ex->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Room ' . $device->room_no . ' Menu configuration synced in real-time!'
                ]);
            }

            return redirect()->back()->with('success', 'Room ' . $device->room_no . ' Menu configuration saved.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DeviceController@updateRoomMenus Error: ' . $e->getMessage());
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to save: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
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

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room ' . $device->room_no . ' Menu reset & synced in real-time!'
            ]);
        }

        return redirect()->back()->with('success', 'Room ' . $device->room_no . ' Menu configuration reset to Hotel Global Default.');
    }
}
