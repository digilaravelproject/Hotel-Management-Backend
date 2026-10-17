<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AirportGeocodeService
{
    /**
     * Free Nominatim (OpenStreetMap) geocoding - No API key needed.
     */
    public static function fetchCoordinates(string $name, string $city): ?array
    {
        $query = urlencode("{$name}, {$city}, India");
        try {
            $res = Http::withHeaders([
                'User-Agent' => 'HotelManagementApp/1.0 (contact@hotel.local)'
            ])->timeout(10)->get("https://nominatim.openstreetmap.org/search", [
                'format' => 'json',
                'q' => $query,
                'limit' => 1,
            ]);

            if ($res->successful()) {
                $data = $res->json();
                if (!empty($data[0]['lat']) && !empty($data[0]['lon'])) {
                    return [
                        'latitude' => (float) $data[0]['lat'],
                        'longitude' => (float) $data[0]['lon'],
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("[AirportGeocode] Free geocode failed: " . $e->getMessage());
        }
        return null;
    }
}