<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Services\FlightDataService;
use App\Services\FirebaseFcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TvFlightController extends Controller
{
    protected FlightDataService $flightService;
    protected FirebaseFcmService $fcmService;

    public function __construct(FlightDataService $flightService, FirebaseFcmService $fcmService)
    {
        $this->flightService = $flightService;
        $this->fcmService = $fcmService;
    }

    /**
     * Get Real-time Flight Departures and Arrivals for the Hotel's Airport.
     */
    public function getFlights(Request $request)
    {
        $hotel = $request->input('current_hotel');
        $device = $request->input('current_device');

        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated or Hotel not associated with device.',
            ], 401);
        }

        // Determine which airport to query
        $requestedIata = $request->query('airport');
        $primaryAirport = $hotel->primaryAirport;
        $secondaryAirport = $hotel->secondaryAirport;

        // Auto-fallback to city airport if none configured
        if (!$primaryAirport && $hotel->city) {
            $primaryAirport = Airport::where('city', 'LIKE', '%' . trim($hotel->city) . '%')
                ->where('status', true)
                ->first();
            if ($primaryAirport) {
                $hotel->update(['primary_airport_id' => $primaryAirport->id]);
            }
        }

        // Global fallback to BOM if nothing found
        $targetIata = $requestedIata 
            ? strtoupper(trim($requestedIata))
            : ($primaryAirport ? $primaryAirport->iata_code : 'BOM');

        $schedule = $this->flightService->getAirportSchedule($targetIata, false);

        return response()->json([
            'status' => true,
            'message' => 'Flight schedule retrieved successfully.',
            'data' => [
                'current_airport' => $schedule['airport'] ?? ['iata_code' => $targetIata],
                'primary_airport' => $primaryAirport ? [
                    'name' => $primaryAirport->name,
                    'iata_code' => $primaryAirport->iata_code,
                    'city' => $primaryAirport->city,
                ] : null,
                'secondary_airport' => $secondaryAirport ? [
                    'name' => $secondaryAirport->name,
                    'iata_code' => $secondaryAirport->iata_code,
                    'city' => $secondaryAirport->city,
                ] : null,
                'last_updated' => $schedule['last_updated'] ?? now()->format('d M Y, h:i A'),
                'is_live' => $schedule['is_live'] ?? false,
                'departures' => $schedule['departures'] ?? [],
                'arrivals' => $schedule['arrivals'] ?? [],
            ],
        ]);
    }

    /**
     * Force-refresh flight data with 3-minute rate limit cooldown.
     */
    public function refreshFlights(Request $request)
    {
        $hotel = $request->input('current_hotel');
        $device = $request->input('current_device');

        if (!$hotel) {
            return response()->json(['status' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $rateKey = "flight_refresh_{$hotel->id}";
        if (RateLimiter::tooManyAttempts($rateKey, 1)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'status' => false,
                'message' => "Please wait {$seconds} seconds before refreshing flights again.",
                'cooldown_seconds' => $seconds,
            ], 429);
        }

        // 3-minute cooldown (180 seconds)
        RateLimiter::hit($rateKey, 180);

        $requestedIata = $request->input('airport') ?? $request->query('airport');
        $targetIata = $requestedIata ? strtoupper(trim($requestedIata)) : ($hotel->primaryAirport ? $hotel->primaryAirport->iata_code : 'BOM');

        $schedule = $this->flightService->getAirportSchedule($targetIata, true);

        // Optional: Broadcast silent FCM refresh notification to hotel TVs
        try {
            $this->fcmService->sendToTopic("hotel_{$hotel->id}", [
                'action' => 'REFRESH_FLIGHTS',
                'airport' => $targetIata,
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'status' => true,
            'message' => 'Flight schedule refreshed successfully.',
            'data' => [
                'current_airport' => $schedule['airport'] ?? ['iata_code' => $targetIata],
                'last_updated' => $schedule['last_updated'] ?? now()->format('d M Y, h:i A'),
                'is_live' => $schedule['is_live'] ?? false,
                'departures' => $schedule['departures'] ?? [],
                'arrivals' => $schedule['arrivals'] ?? [],
            ],
        ]);
    }
}
