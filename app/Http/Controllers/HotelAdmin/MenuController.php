<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MenuResolverService;

class MenuController extends Controller
{
    /**
     * Display Global Menu Visibility settings for the Hotel Admin.
     */
    public function index()
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $defaultMenus = MenuResolverService::getDefaultMenus();
        $currentSettings = $hotel->global_menu_settings ?? [];

        return view('hotel_admin.menus.index', compact('hotel', 'defaultMenus', 'currentSettings'));
    }

    /**
     * Update Global Menu Visibility settings.
     */
    public function update(Request $request)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $defaultMenus = MenuResolverService::getDefaultMenus();
        $inputSettings = $request->input('menus', []);

        $formattedSettings = [];
        foreach ($defaultMenus as $menu) {
            // Checkbox present means "show", absent means "hide"
            $formattedSettings[$menu['id']] = isset($inputSettings[$menu['id']]) ? 'show' : 'hide';
        }

        $hotel->update([
            'global_menu_settings' => $formattedSettings,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Global Menu Visibility updated & synced to TVs in real-time!',
                'data' => $formattedSettings
            ]);
        }

        return redirect()->back()->with('success', 'Global Menu Visibility settings updated successfully.');
    }
}
