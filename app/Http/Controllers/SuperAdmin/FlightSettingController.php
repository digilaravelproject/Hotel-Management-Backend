<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\FlightSetting;
use App\Services\FlightDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FlightSettingController extends Controller
{
    protected FlightDataService $flightService;

    public function __construct(FlightDataService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Show Super Admin Flight & Airport Management Page.
     */
    public function index()
    {
        $setting = FlightSetting::latest()->first() ?? new FlightSetting([
            'provider' => 'airlabs',
            'api_key' => '',
            'cache_ttl_minutes' => 30,
            'is_active' => true,
        ]);

        $airports = Airport::orderBy('city', 'asc')->paginate(15);
        $totalAirports = Airport::count();

        return view('super_admin.flights.index', compact('setting', 'airports', 'totalAirports'));
    }

    /**
     * Update Flight API Provider & Credentials.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|in:airlabs,aviationstack,aerodatabox',
            'api_key' => 'nullable|string',
            'cache_ttl_minutes' => 'required|integer|min:5|max:1440',
            'is_active' => 'nullable|boolean',
        ]);

        $setting = FlightSetting::first();
        if (!$setting) {
            $setting = new FlightSetting();
        }

        $setting->provider = $request->input('provider', 'airlabs');
        $setting->api_key = $request->input('api_key');
        $setting->cache_ttl_minutes = (int) $request->input('cache_ttl_minutes', 30);
        $setting->is_active = $request->boolean('is_active');
        $setting->save();

        return redirect()->back()->with('success', 'Flight API settings updated successfully.');
    }

    /**
     * Add new Airport to catalog.
     */
    public function storeAirport(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'iata_code' => 'required|string|max:10|unique:airports,iata_code',
            'icao_code' => 'nullable|string|max:10',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'timezone' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
        ]);

        Airport::create([
            'name' => $request->input('name'),
            'iata_code' => strtoupper($request->input('iata_code')),
            'icao_code' => $request->input('icao_code') ? strtoupper($request->input('icao_code')) : null,
            'city' => $request->input('city'),
            'country' => $request->input('country', 'India'),
            'timezone' => $request->input('timezone', 'Asia/Kolkata'),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->back()->with('success', 'Airport added successfully.');
    }

    /**
     * Toggle Airport active status.
     */
    public function toggleAirportStatus($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->status = !$airport->status;
        $airport->save();

        return redirect()->back()->with('success', "Airport {$airport->iata_code} status updated.");
    }

    /**
     * Force refresh cache for an airport (Super Admin action).
     */
    public function forceRefresh($iata)
    {
        $data = $this->flightService->getAirportSchedule(strtoupper($iata), true);
        return redirect()->back()->with('success', "Live flight cache refreshed successfully for {$iata}!");
    }
}
