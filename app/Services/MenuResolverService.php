<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;

class MenuResolverService
{
    /**
     * Standard list of default menu items.
     *
     * @return array<int, array<string, string>>
     */
    public static function getDefaultMenus(): array
    {
        return [
            ['id' => 'flights', 'name' => 'Flights'],
            ['id' => 'input', 'name' => 'Input'],
            ['id' => 'languages', 'name' => 'Languages'],
            ['id' => 'live_tv', 'name' => 'Live TV'],
            ['id' => 'our_city', 'name' => 'Our City'],
            ['id' => 'screen_cast', 'name' => 'Screen Cast'],
            ['id' => 'settings', 'name' => 'Settings'],
            ['id' => 'travel', 'name' => 'Travel'],
            ['id' => 'weather', 'name' => 'Weather'],
        ];
    }

    /**
     * Resolve menu visibility payload for a specific TV device adhering to priority:
     * 1. Room/Device Overrides
     * 2. Hotel Global Default
     * 3. Fallback: All menus "show"
     *
     * Format: Array of objects with "id", "name", "status" ("show" / "hide")
     *
     * @param ConnectedDevice $device
     * @param HotelAdmin $hotel
     * @return array<int, array<string, string>>
     */
    public function getResolvedMenusForDevice(ConnectedDevice $device, HotelAdmin $hotel): array
    {
        // 1. Room Level Override check
        if (!is_null($device->menu_overrides) && is_array($device->menu_overrides)) {
            $effectiveSettings = $device->menu_overrides;
        } 
        // 2. Hotel Global Default check
        elseif (!is_null($hotel->global_menu_settings) && is_array($hotel->global_menu_settings)) {
            $effectiveSettings = $hotel->global_menu_settings;
        } 
        // 3. Fallback default: all menus show
        else {
            $effectiveSettings = [];
            foreach (self::getDefaultMenus() as $menu) {
                $effectiveSettings[$menu['id']] = 'show';
            }
        }

        $resolvedList = [];
        foreach (self::getDefaultMenus() as $menu) {
            $status = isset($effectiveSettings[$menu['id']]) && $effectiveSettings[$menu['id']] === 'hide' ? 'hide' : 'show';
            $resolvedList[] = [
                'id' => $menu['id'],
                'name' => $menu['name'],
                'status' => $status,
            ];
        }

        return $resolvedList;
    }
}
