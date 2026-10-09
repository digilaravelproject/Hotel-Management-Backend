<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MenuResolverService;
use App\Events\TvConfigUpdatedEvent;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    protected MenuResolverService $menuResolver;

    public function __construct(MenuResolverService $menuResolver)
    {
        $this->menuResolver = $menuResolver;
    }

    /**
     * Display Global Menu Hierarchy & Visibility settings for Hotel Admin.
     */
    public function index()
    {
        try {
            $hotel = auth()->guard('hotel_admin')->user();
            if (!$hotel) {
                return redirect()->route('hotel.login');
            }

            $currentTree = $this->menuResolver->resolveTree($hotel->global_menu_settings ?? []);
            $defaultTree = MenuResolverService::getDefaultMenus();
            $itemCatalog = MenuResolverService::getItemCatalog();

            return view('hotel_admin.menus.index', compact('hotel', 'currentTree', 'defaultTree', 'itemCatalog'));
        } catch (\Throwable $e) {
            Log::error('HotelAdmin\MenuController@index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Unable to load menu configuration: ' . $e->getMessage());
        }
    }

    /**
     * Update Global Menu Hierarchy & Visibility settings.
     */
    public function update(Request $request)
    {
        try {
            $hotel = auth()->guard('hotel_admin')->user();
            if (!$hotel) {
                return redirect()->route('hotel.login');
            }

            $rawMenus = $request->input('menus_hierarchy');
            if (is_string($rawMenus)) {
                $decoded = json_decode($rawMenus, true);
                $rawMenus = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            }

            if (!is_array($rawMenus) || empty($rawMenus)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or empty menu hierarchy provided.'
                ], 422);
            }

            $sanitizedTree = $this->menuResolver->sanitizeTree($rawMenus);

            $hotel->update([
                'global_menu_settings' => $sanitizedTree,
            ]);

            // Clear cache and dispatch real-time TV update event
            try {
                \App\Services\TvVersionCacheService::clearHotelCache($hotel->id, 'MENU');
            } catch (\Throwable $eventEx) {
                Log::warning('TvConfigUpdatedEvent dispatch failed for Menu: ' . $eventEx->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Global TV Menu order & visibility updated & synced in real-time!',
                    'data' => $sanitizedTree,
                ]);
            }

            return redirect()->back()->with('success', 'Global TV Menu settings updated successfully.');
        } catch (\Throwable $e) {
            Log::error('HotelAdmin\MenuController@update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update menu settings: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to update menu settings: ' . $e->getMessage());
        }
    }

    /**
     * Reset Global TV Menus to the default system hierarchy.
     */
    public function reset(Request $request)
    {
        try {
            $hotel = auth()->guard('hotel_admin')->user();
            if (!$hotel) {
                return redirect()->route('hotel.login');
            }

            $hotel->update([
                'global_menu_settings' => null,
            ]);

            try {
                \App\Services\TvVersionCacheService::clearHotelCache($hotel->id, 'MENU');
            } catch (\Throwable $eventEx) {
                Log::warning('TvConfigUpdatedEvent dispatch failed on Menu reset: ' . $eventEx->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'TV Menu hierarchy reset to default configuration!',
                    'data' => MenuResolverService::getDefaultMenus(),
                ]);
            }

            return redirect()->back()->with('success', 'TV Menus reset to default successfully.');
        } catch (\Throwable $e) {
            Log::error('HotelAdmin\MenuController@reset Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to reset menus: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to reset menus: ' . $e->getMessage());
        }
    }
}
