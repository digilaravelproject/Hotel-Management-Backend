<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
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
     * Show the Menu Hierarchy & Visibility builder for a specific hotel.
     */
    public function edit(int $hotelId)
    {
        try {
            $hotel = HotelAdmin::findOrFail($hotelId);
            $currentTree = $this->menuResolver->resolveTree($hotel->global_menu_settings ?? []);
            $defaultTree = MenuResolverService::getDefaultMenus();
            $itemCatalog = MenuResolverService::getItemCatalog();

            return view('super_admin.hotels.menus', compact('hotel', 'currentTree', 'defaultTree', 'itemCatalog'));
        } catch (\Throwable $e) {
            Log::error('SuperAdmin\MenuController@edit Error: ' . $e->getMessage(), [
                'hotel_id' => $hotelId,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('super-admin.hotels.index')->with('error', 'Unable to load hotel menus: ' . $e->getMessage());
        }
    }

    /**
     * Update Menu Hierarchy & Visibility for a specific hotel.
     */
    public function update(Request $request, int $hotelId)
    {
        try {
            $hotel = HotelAdmin::findOrFail($hotelId);

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

            try {
                event(new TvConfigUpdatedEvent($hotel->id, 'MENU', null, ['action' => 'update']));
            } catch (\Throwable $eventEx) {
                Log::warning('TvConfigUpdatedEvent dispatch failed: ' . $eventEx->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hotel TV Menu hierarchy updated & synced in real-time!',
                    'data' => $sanitizedTree,
                ]);
            }

            return redirect()->back()->with('success', 'Hotel TV Menu hierarchy updated successfully.');
        } catch (\Throwable $e) {
            Log::error('SuperAdmin\MenuController@update Error: ' . $e->getMessage(), [
                'hotel_id' => $hotelId,
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update hotel menus: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to update hotel menus: ' . $e->getMessage());
        }
    }

    /**
     * Reset hotel menus back to system default.
     */
    public function reset(Request $request, int $hotelId)
    {
        try {
            $hotel = HotelAdmin::findOrFail($hotelId);
            $hotel->update([
                'global_menu_settings' => null,
            ]);

            try {
                event(new TvConfigUpdatedEvent($hotel->id, 'MENU', null, ['action' => 'reset']));
            } catch (\Throwable $eventEx) {
                Log::warning('TvConfigUpdatedEvent dispatch failed: ' . $eventEx->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hotel menus reset to system default!',
                    'data' => MenuResolverService::getDefaultMenus(),
                ]);
            }

            return redirect()->back()->with('success', 'Hotel menus reset to system default successfully.');
        } catch (\Throwable $e) {
            Log::error('SuperAdmin\MenuController@reset Error: ' . $e->getMessage(), [
                'hotel_id' => $hotelId,
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
