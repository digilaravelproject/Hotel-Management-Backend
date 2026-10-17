<?php

namespace App\Observers;

use App\Models\Airport;
use App\Services\AirportGeocodeService;
use Illuminate\Support\Facades\Log;

class AirportObserver
{
    /**
     * Triggered when Airport is saved (created or updated).
     * Auto-fetches Latitude & Longitude from Nominatim if not already set.
     */
    public function created(Airport $airport): void
    {
        if (empty($airport->latitude) || empty($airport->longitude)) {
            $this->autoFillCoordinates($airport);
        }
    }

    public function updating(Airport $airport): void
    {
        // Only auto-fetch if explicitly left blank but was previously set
    }

    protected function autoFillCoordinates(Airport $airport): void
    {
        $coords = AirportGeocodeService::fetchCoordinates(
            $airport->name,
            $airport->city
        );

        if ($coords) {
            $airport->latitude = $coords['latitude'];
            $airport->longitude = $coords['longitude'];
            Log::info("[AirportObserver] Auto-set coords for {$airport->iata_code}: {$airport->latitude}, {$airport->longitude}");
        } else {
            Log::warning("[AirportObserver] Could not geocode: {$airport->name} / {$airport->city}");
        }
    }
}
