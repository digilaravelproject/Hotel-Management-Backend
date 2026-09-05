<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Facades\Auth;
use App\Services\TvVersionCacheService;
use App\Events\TvConfigUpdatedEvent;

class TvThemeController extends Controller
{
    /**
     * Display a listing of available themes for the hotel to choose from.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $selectedThemeId = (int) ($hotel->selected_theme_id ?? 1);

        // Fetch all active builds grouped by theme_id
        $activeThemes = TvTemplate::where('is_active', true)
            ->orderBy('theme_id', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('theme_id')
            ->values();

        // If no themes exist yet in the database, present default Theme 1 placeholder
        $isFallbackDefault = $activeThemes->isEmpty();

        return view('hotel_admin.theme.index', compact('hotel', 'selectedThemeId', 'activeThemes', 'isFallbackDefault'));
    }

    /**
     * Handle theme selection for this hotel.
     */
    public function select(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|integer|min:1',
        ]);

        $themeId = (int) $request->input('theme_id');
        $hotel = Auth::guard('hotel_admin')->user();

        // Check if theme exists and has an active build
        $themeExists = TvTemplate::where('theme_id', $themeId)
            ->where('is_active', true)
            ->exists();

        // Allow selection of Theme 1 always as safe default, or verify theme exists
        if ($themeId !== 1 && !$themeExists) {
            return back()->with('error', "Selected theme #{$themeId} is not currently available or active.");
        }

        // Update hotel's selected theme
        $hotel->selected_theme_id = $themeId;
        $hotel->save();

        // Invalidate TV check-version cache for this hotel
        TvVersionCacheService::clearHotelCache($hotel->id);

        // Dispatch domain event to send Firebase FCM update to all room TVs in this hotel
        event(new TvConfigUpdatedEvent($hotel->id, 'TEMPLATE', null, [
            'action' => 'theme_switched',
            'theme_id' => $themeId,
        ]));

        return back()->with('success', "TV Theme #{$themeId} activated successfully! Connected TVs in your hotel will update automatically.");
    }
}
