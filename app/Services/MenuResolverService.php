<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\ConnectedDevice;
use Illuminate\Support\Facades\Log;

class MenuResolverService
{
    /**
     * Master catalog of known menu items with their default labels, icons, and container flags.
     *
     * @return array<string, array{name: string, icon: string, is_container: bool}>
     */
    public static function getItemCatalog(): array
    {
        return [
            'hotel_menu'           => ['name' => 'Hotel Menu', 'icon' => 'assets/images/icons/hotelinfo.png', 'is_container' => true],
            'hotel_info'           => ['name' => 'Hotel Info', 'icon' => 'assets/images/icons/hotelinfo.png', 'is_container' => false],
            'room_info'            => ['name' => 'Room Info', 'icon' => 'assets/images/icons/amenities.png', 'is_container' => false],
            'amenities'            => ['name' => 'Amenities', 'icon' => 'assets/images/icons/roomservice.png', 'is_container' => false],
            'apps'                 => ['name' => 'Applications', 'icon' => 'assets/images/icons/apps.png', 'is_container' => false],
            'language'             => ['name' => 'Language', 'icon' => 'assets/images/icons/languages.png', 'is_container' => false],
            'livetv'               => ['name' => 'Live TV', 'icon' => 'assets/images/icons/livetv.png', 'is_container' => false],
            'interactive_services' => ['name' => 'Interactive Services', 'icon' => 'assets/images/icons/roomservice.png', 'is_container' => true],
            'flights'              => ['name' => 'Flights', 'icon' => 'assets/images/icons/flights.png', 'is_container' => false],
            'weather'              => ['name' => 'Weather', 'icon' => 'assets/images/icons/weather.png', 'is_container' => false],
            'input'                => ['name' => 'Input', 'icon' => 'assets/images/icons/input.png', 'is_container' => false],
            'settings'             => ['name' => 'Settings', 'icon' => 'assets/images/icons/settings.png', 'is_container' => false],
            'ourcity'              => ['name' => 'Our City', 'icon' => 'assets/images/icons/ourcity.png', 'is_container' => false],
            'screen_cast'          => ['name' => 'Screen Cast', 'icon' => 'assets/images/icons/cast.png', 'is_container' => false],
        ];
    }

    /**
     * Standard nested default menu tree matching window.MENU_DATA specification.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getDefaultMenus(): array
    {
        return [
            [
                'id' => 'hotel_menu',
                'name' => 'Hotel Menu',
                'icon' => 'assets/images/icons/hotelinfo.png',
                'status' => 'show',
                'sub_menus' => [
                    [
                        'id' => 'hotel_info',
                        'name' => 'Hotel Info',
                        'icon' => 'assets/images/icons/hotelinfo.png',
                        'status' => 'show',
                    ],
                    [
                        'id' => 'room_info',
                        'name' => 'Room Info',
                        'icon' => 'assets/images/icons/amenities.png',
                        'status' => 'show',
                    ],
                    [
                        'id' => 'amenities',
                        'name' => 'Amenities',
                        'icon' => 'assets/images/icons/roomservice.png',
                        'status' => 'show',
                    ],
                ],
            ],
            [
                'id' => 'apps',
                'name' => 'Applications',
                'icon' => 'assets/images/icons/apps.png',
                'status' => 'show',
            ],
            [
                'id' => 'language',
                'name' => 'Language',
                'icon' => 'assets/images/icons/languages.png',
                'status' => 'show',
            ],
            [
                'id' => 'livetv',
                'name' => 'Live TV',
                'icon' => 'assets/images/icons/livetv.png',
                'status' => 'show',
            ],
            [
                'id' => 'interactive_services',
                'name' => 'Interactive Services',
                'icon' => 'assets/images/icons/roomservice.png',
                'status' => 'show',
                'sub_menus' => [
                    [
                        'id' => 'flights',
                        'name' => 'Flights',
                        'icon' => 'assets/images/icons/flights.png',
                        'status' => 'show',
                    ],
                    [
                        'id' => 'weather',
                        'name' => 'Weather',
                        'icon' => 'assets/images/icons/weather.png',
                        'status' => 'show',
                    ],
                    [
                        'id' => 'input',
                        'name' => 'Input',
                        'icon' => 'assets/images/icons/input.png',
                        'status' => 'show',
                    ],
                    [
                        'id' => 'settings',
                        'name' => 'Settings',
                        'icon' => 'assets/images/icons/settings.png',
                        'status' => 'show',
                    ],
                ],
            ],
            [
                'id' => 'ourcity',
                'name' => 'Our City',
                'icon' => 'assets/images/icons/ourcity.png',
                'status' => 'show',
            ],
            [
                'id' => 'screen_cast',
                'name' => 'Screen Cast',
                'icon' => 'assets/images/icons/cast.png',
                'status' => 'show',
            ],
        ];
    }

    /**
     * Resolve menu hierarchy payload for a specific TV device adhering to priority:
     * 1. Room/Device Overrides
     * 2. Hotel Global Default
     * 3. Fallback: Default full nested menu tree with all 'show'
     *
     * @param ConnectedDevice $device
     * @param HotelAdmin $hotel
     * @return array<int, array<string, mixed>>
     */
    public function getResolvedMenusForDevice(ConnectedDevice $device, HotelAdmin $hotel): array
    {
        try {
            if (!is_null($device->menu_overrides) && is_array($device->menu_overrides)) {
                return $this->resolveTree($device->menu_overrides);
            }

            if (!is_null($hotel->global_menu_settings) && is_array($hotel->global_menu_settings)) {
                return $this->resolveTree($hotel->global_menu_settings);
            }

            return self::getDefaultMenus();
        } catch (\Throwable $e) {
            Log::error('MenuResolverService@getResolvedMenusForDevice Error: ' . $e->getMessage(), [
                'device_id' => $device->id ?? null,
                'hotel_id' => $hotel->id ?? null,
            ]);

            return self::getDefaultMenus();
        }
    }

    /**
     * Resolve tree whether stored as a structured nested array or legacy flat key-value status map.
     *
     * @param array $settings
     * @return array<int, array<string, mixed>>
     */
    public function resolveTree(array $settings): array
    {
        // Check if settings is a flat key-value map (legacy format e.g. ['flights' => 'hide'])
        $isFlatMap = false;
        foreach ($settings as $k => $v) {
            if (is_string($k) && is_string($v)) {
                $isFlatMap = true;
                break;
            }
        }

        if ($isFlatMap) {
            return $this->applyFlatStatusToDefaultTree($settings);
        }

        return $this->sanitizeTree($settings);
    }

    /**
     * Sanitize and normalize a nested menu tree submitted by UI or stored in DB.
     *
     * @param array $tree
     * @return array<int, array<string, mixed>>
     */
    public function sanitizeTree(array $tree): array
    {
        $catalog = self::getItemCatalog();
        $normalized = [];

        foreach ($tree as $item) {
            if (!is_array($item) || empty($item['id'])) {
                continue;
            }

            $id = (string) $item['id'];
            $meta = $catalog[$id] ?? [
                'name' => ucwords(str_replace('_', ' ', $id)),
                'icon' => 'assets/images/icons/hotelinfo.png',
                'is_container' => !empty($item['sub_menus']),
            ];

            $node = [
                'id' => $id,
                'name' => !empty($item['name']) ? (string) $item['name'] : $meta['name'],
                'icon' => !empty($item['icon']) ? (string) $item['icon'] : $meta['icon'],
                'status' => (isset($item['status']) && $item['status'] === 'hide') ? 'hide' : 'show',
            ];

            if (isset($item['sub_menus']) && is_array($item['sub_menus'])) {
                $subMenus = [];
                foreach ($item['sub_menus'] as $subItem) {
                    if (!is_array($subItem) || empty($subItem['id'])) {
                        continue;
                    }
                    $subId = (string) $subItem['id'];
                    $subMeta = $catalog[$subId] ?? [
                        'name' => ucwords(str_replace('_', ' ', $subId)),
                        'icon' => 'assets/images/icons/hotelinfo.png',
                        'is_container' => false,
                    ];

                    $subMenus[] = [
                        'id' => $subId,
                        'name' => !empty($subItem['name']) ? (string) $subItem['name'] : $subMeta['name'],
                        'icon' => !empty($subItem['icon']) ? (string) $subItem['icon'] : $subMeta['icon'],
                        'status' => (isset($subItem['status']) && $subItem['status'] === 'hide') ? 'hide' : 'show',
                    ];
                }
                $node['sub_menus'] = $subMenus;
            }

            $normalized[] = $node;
        }

        return !empty($normalized) ? $normalized : self::getDefaultMenus();
    }

    /**
     * Map legacy flat key-value settings onto the default nested tree.
     */
    private function applyFlatStatusToDefaultTree(array $flatMap): array
    {
        $defaultTree = self::getDefaultMenus();
        foreach ($defaultTree as &$parent) {
            $parentId = $parent['id'];
            if (isset($flatMap[$parentId])) {
                $parent['status'] = $flatMap[$parentId] === 'hide' ? 'hide' : 'show';
            }

            if (!empty($parent['sub_menus']) && is_array($parent['sub_menus'])) {
                foreach ($parent['sub_menus'] as &$child) {
                    $childId = $child['id'];
                    if (isset($flatMap[$childId])) {
                        $child['status'] = $flatMap[$childId] === 'hide' ? 'hide' : 'show';
                    }
                }
            }
        }

        return $defaultTree;
    }
}
