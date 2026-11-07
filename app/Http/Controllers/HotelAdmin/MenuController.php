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

            $hotel->loadMissing(['plan', 'connectedDevices', 'primaryAirport', 'secondaryAirport']);

            $currentTree = $this->menuResolver->resolveTree($hotel->global_menu_settings ?? []);
            $defaultTree = MenuResolverService::getDefaultMenus();
            $itemCatalog = MenuResolverService::getItemCatalog();

            $selectedThemeId = (int) ($hotel->selected_theme_id ?? 1);
            $activeTemplate = \App\Models\TvTemplate::where('theme_id', $selectedThemeId)
                ->where('is_active', true)
                ->orderBy('id', 'desc')
                ->first()
                ?? \App\Models\TvTemplate::where('theme_id', $selectedThemeId)->orderBy('id', 'desc')->first()
                ?? \App\Models\TvTemplate::where('is_active', true)->orderBy('id', 'desc')->first();

            $themePreviewUrl = ($activeTemplate && !empty($activeTemplate->extracted_path)) 
                ? route('hotel.menus.theme-preview') 
                : null;

            $themePreviewImage = ($activeTemplate && !empty($activeTemplate->preview_image)) ? asset($activeTemplate->preview_image) : null;
            $themeName = $activeTemplate ? ($activeTemplate->theme_name ?? 'Theme #' . $activeTemplate->theme_id) : ('Theme #' . $selectedThemeId);
            $themeVersion = $activeTemplate ? $activeTemplate->version : '1.0';

            return view('hotel_admin.menus.index', compact(
                'hotel', 
                'currentTree', 
                'defaultTree', 
                'itemCatalog',
                'selectedThemeId',
                'activeTemplate',
                'themePreviewUrl',
                'themePreviewImage',
                'themeName',
                'themeVersion'
            ));
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

    /**
     * Render the Live Extracted Smart TV Theme Preview with real Hotel data.json injection.
     */
    public function themePreview(Request $request)
    {
        $hotel = auth()->guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $hotel->loadMissing(['plan', 'connectedDevices', 'primaryAirport', 'secondaryAirport']);
        $selectedThemeId = (int) ($hotel->selected_theme_id ?? 1);

        $activeTemplate = \App\Models\TvTemplate::where('theme_id', $selectedThemeId)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->first()
            ?? \App\Models\TvTemplate::where('theme_id', $selectedThemeId)->orderBy('id', 'desc')->first()
            ?? \App\Models\TvTemplate::where('is_active', true)->orderBy('id', 'desc')->first();

        if (!$activeTemplate || empty($activeTemplate->extracted_path)) {
            return response(
                '<div style="font-family:sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;background:#0d0926;color:#fff;text-align:center;padding:20px;">' .
                '<h2 style="margin:0 0 10px;font-size:20px;color:#f59e0b;">Theme #' . $selectedThemeId . ' Not Uploaded</h2>' .
                '<p style="color:#94a3b8;font-size:13px;max-width:400px;line-height:1.5;">Please contact Super Admin to upload template files for Theme #' . $selectedThemeId . ' to activate live preview.</p>' .
                '</div>',
                200
            )->header('Content-Type', 'text/html; charset=UTF-8');
        }

        $indexPath = public_path($activeTemplate->extracted_path . '/index.html');
        if (!file_exists($indexPath)) {
            $indexPath = public_path($activeTemplate->extracted_path . '/index.htm');
        }

        if (!file_exists($indexPath)) {
            return response(
                '<div style="font-family:sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;background:#0d0926;color:#fff;text-align:center;padding:20px;">' .
                '<h2 style="margin:0 0 10px;font-size:20px;color:#ef4444;">index.html Not Found</h2>' .
                '<p style="color:#94a3b8;font-size:13px;max-width:400px;line-height:1.5;">The extracted theme directory does not contain index.html.</p>' .
                '</div>',
                200
            )->header('Content-Type', 'text/html; charset=UTF-8');
        }

        $html = file_get_contents($indexPath);

        // Build real hotel data
        $device = $hotel->connectedDevices()->first() ?? new \App\Models\ConnectedDevice([
            'room_no' => 'Suite 101',
            'device_id' => 'PREVIEW-TV-01',
            'mac_address' => '00:11:22:33:44:55',
            'ip_address' => '192.168.1.100',
            'model' => 'PaxTV Smart Display',
            'brand' => 'PaxTV Hospitality',
            'os_version' => 'Android TV 11',
            'is_demo' => false,
            'api_token' => 'PREVIEW_TOKEN',
        ]);

        $payload = (new \App\Http\Resources\TvLoginResource([
            'hotel' => $hotel,
            'device' => $device,
            'message' => 'Hotel TV Data for ' . $hotel->hotel_name,
        ]))->toArray($request);

        $jsonPayload = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);
        $baseHref = asset($activeTemplate->extracted_path) . '/';

        $injectionScript = <<<HTML
<base href="{$baseHref}">
<script>
    (function() {
        var hotelData = {$jsonPayload};
        window.HOTEL_DATA = hotelData;
        window.TV_DATA = hotelData;
        window.DATA = hotelData;
        window.MENU_DATA = (hotelData && hotelData.data && hotelData.data.menus) ? hotelData.data.menus : [];
        try {
            localStorage.setItem('tv_data', JSON.stringify(hotelData));
            localStorage.setItem('hotel_data', JSON.stringify(hotelData));
            localStorage.setItem('hotel_name', hotelData.data ? hotelData.data.hotel.hotel_name : '');
        } catch(e) {}

        // Intercept XMLHttpRequest for data.json
        var origXHR = window.XMLHttpRequest;
        function customXHR() {
            var xhr = new origXHR();
            var origOpen = xhr.open;
            xhr.open = function(method, url) {
                if (typeof url === 'string' && (url.indexOf('data.json') !== -1 || url.indexOf('tv_data.json') !== -1)) {
                    this._isDataJson = true;
                }
                return origOpen.apply(this, arguments);
            };
            var origSend = xhr.send;
            xhr.send = function() {
                if (this._isDataJson) {
                    var self = this;
                    setTimeout(function() {
                        Object.defineProperty(self, 'status', { value: 200, writable: false });
                        Object.defineProperty(self, 'statusText', { value: 'OK', writable: false });
                        Object.defineProperty(self, 'responseText', { value: JSON.stringify(hotelData), writable: false });
                        Object.defineProperty(self, 'response', { value: JSON.stringify(hotelData), writable: false });
                        Object.defineProperty(self, 'readyState', { value: 4, writable: false });
                        if (typeof self.onreadystatechange === 'function') self.onreadystatechange();
                        if (typeof self.onload === 'function') self.onload();
                    }, 10);
                    return;
                }
                return origSend.apply(this, arguments);
            };
            return xhr;
        }
        window.XMLHttpRequest = customXHR;

        // Intercept fetch for data.json
        if (window.fetch) {
            var origFetch = window.fetch;
            window.fetch = function(input, init) {
                var url = (typeof input === 'string') ? input : (input && input.url ? input.url : '');
                if (url && (url.indexOf('data.json') !== -1 || url.indexOf('tv_data.json') !== -1)) {
                    return Promise.resolve(new Response(JSON.stringify(hotelData), {
                        status: 200,
                        headers: { 'Content-Type': 'application/json' }
                    }));
                }
                return origFetch.apply(this, arguments);
            };
        }

        // Listen for real-time menu updates from parent builder
        window.addEventListener('message', function(ev) {
            if (ev.data && ev.data.type === 'UPDATE_MENU') {
                var updatedMenus = ev.data.menus;
                window.MENU_DATA = updatedMenus;
                if (window.HOTEL_DATA && window.HOTEL_DATA.data) {
                    window.HOTEL_DATA.data.menus = updatedMenus;
                }
                try {
                    localStorage.setItem('tv_data', JSON.stringify(window.HOTEL_DATA));
                } catch(e) {}
                
                if (typeof window.onMenuConfigUpdate === 'function') {
                    window.onMenuConfigUpdate(updatedMenus);
                } else if (typeof window.renderNavigation === 'function') {
                    window.renderNavigation(updatedMenus);
                } else if (typeof window.initMenus === 'function') {
                    window.initMenus(updatedMenus);
                }
            }
        });
    })();
</script>
HTML;

        if (stripos($html, '<head>') !== false) {
            $html = preg_replace('/<head>/i', "<head>\n" . $injectionScript, $html, 1);
        } else {
            $html = $injectionScript . "\n" . $html;
        }

        return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
