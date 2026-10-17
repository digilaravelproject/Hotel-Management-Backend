<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Services\FlightDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlightController extends Controller
{
    protected FlightDataService $flightService;

    public function __construct(FlightDataService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Display Airport assignment page for Hotel Admin.
     * Filtered by 100km radius of the hotel's saved city.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $city = trim($hotel->city ?? '');

        $cityAirports = collect();
        if (!empty($city)) {
            // Find reference center coordinates for the hotel's saved city from airports table
            $cityCenter = Airport::where('city', 'LIKE', "%{$city}%")
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->selectRaw('AVG(latitude) as lat, AVG(longitude) as lng')
                ->first();

            if ($cityCenter && $cityCenter->lat && $cityCenter->lng) {
                $lat = $cityCenter->lat;
                $lng = $cityCenter->lng;
                $radius = 100; // 100 KM radius limit

                // Optimized MySQL Haversine geospatial query utilizing table indexes for lightning-fast performance
                $cityAirports = Airport::where('status', true)
                    ->select('airports.*')
                    ->selectRaw(
                        "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                        [$lat, $lng, $lat]
                    )
                    ->having("distance", "<=", $radius)
                    ->orderBy("distance", "asc")
                    ->get();
            } else {
                // Fallback to strict string city match if coordinates are not available
                $cityAirports = Airport::where('city', 'LIKE', "%{$city}%")
                    ->where('status', true)
                    ->orderBy('name', 'asc')
                    ->get();
            }
        }

        // All active airports list for fallback search if needed
        $allAirports = Airport::where('status', true)->orderBy('city', 'asc')->get();

        // Auto-select if only 1 airport in range and not yet set
        if ($cityAirports->count() === 1 && empty($hotel->primary_airport_id)) {
            $hotel->primary_airport_id = $cityAirports->first()->id;
            $hotel->save();
        }

        // Live preview of primary airport flights
        $primaryAirport = $hotel->primaryAirport ?? ($cityAirports->first() ?? $allAirports->first());
        $livePreview = null;
        if ($primaryAirport) {
            $livePreview = $this->flightService->getAirportSchedule($primaryAirport->iata_code, false);
        }

        return view('hotel_admin.flights.index', compact('hotel', 'cityAirports', 'allAirports', 'primaryAirport', 'livePreview'));
    }

    /**
     * Save Primary & Secondary Airport selections.
     */
    public function update(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'primary_airport_id' => 'required|exists:airports,id',
            'secondary_airport_id' => 'nullable|different:primary_airport_id|exists:airports,id',
        ]);

        $hotel->primary_airport_id = $request->input('primary_airport_id');
        $hotel->secondary_airport_id = $request->input('secondary_airport_id');
        $hotel->save();

        return redirect()->back()->with('success', 'Airport configuration updated successfully. Hotel TVs will now display these flights.');
    }
}
