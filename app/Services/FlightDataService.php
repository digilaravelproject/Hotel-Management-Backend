<?php

namespace App\Services;

use App\Models\Airport;
use App\Models\FlightSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FlightDataService
{
    /**
     * Get real-time or cached flight schedule for a specific airport IATA code.
     */
    public function getAirportSchedule(string $iataCode, bool $forceRefresh = false): array
    {
        $iata = strtoupper(trim($iataCode));
        $cacheKey = "airport_flights_{$iata}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $setting = FlightSetting::getActiveSetting();
        $ttlSeconds = ($setting ? (int) $setting->cache_ttl_minutes : 30) * 60;

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($iata, $setting) {
            $airport = Airport::where('iata_code', $iata)->first();

            // Attempt live fetch if API key is configured
            if ($setting && !empty($setting->api_key)) {
                try {
                    $liveData = $this->fetchFromApi($iata, $setting);
                    if ($liveData && (!empty($liveData['departures']) || !empty($liveData['arrivals']))) {
                        $liveData['airport'] = [
                            'name' => $airport ? $airport->name : "{$iata} Airport",
                            'iata_code' => $iata,
                            'city' => $airport ? $airport->city : '',
                            'country' => $airport ? $airport->country : '',
                        ];
                        $liveData['is_live'] = true;
                        $liveData['last_updated'] = Carbon::now()->format('d M Y, h:i A');
                        return $liveData;
                    }
                } catch (\Throwable $e) {
                    Log::warning("[FlightDataService] Live API fetch failed for {$iata}: " . $e->getMessage());
                }
            }

            // High-fidelity dynamic fallback mock generator
            return $this->generateDynamicSchedule($iata, $airport);
        });
    }

    /**
     * Fetch from live 3rd-party flight API (AirLabs).
     */
    protected function fetchFromApi(string $iata, FlightSetting $setting): ?array
    {
        $apiKey = $setting->api_key;
        $provider = strtolower($setting->provider);

        if ($provider === 'airlabs') {
            $depRes = Http::timeout(8)->get('https://airlabs.co/api/v9/schedules', [
                'dep_iata' => $iata,
                'api_key' => $apiKey,
            ]);

            $arrRes = Http::timeout(8)->get('https://airlabs.co/api/v9/schedules', [
                'arr_iata' => $iata,
                'api_key' => $apiKey,
            ]);

            $now = Carbon::now('Asia/Kolkata');

            // DEPARTURES: Filter out stale past flights, show live/upcoming and delayed flights
            $departures = [];
            if ($depRes->successful() && isset($depRes->json()['response'])) {
                $rawDeps = $depRes->json()['response'];
                $candidateDeps = [];

                foreach ($rawDeps as $f) {
                    $status = $this->normalizeStatus($f['status'] ?? 'On Time');
                    $depTimeRaw = $f['dep_actual'] ?? $f['dep_estimated'] ?? $f['dep_time'] ?? null;
                    if (!$depTimeRaw) continue;

                    $depCarbon = Carbon::parse($depTimeRaw, 'Asia/Kolkata');

                    // Skip flights that have already completed their journey and landed
                    if ($status === 'Landed') {
                        continue;
                    }

                    // If departed more than 15 mins ago, remove from board
                    if ($status === 'Departed' && $depCarbon->lt($now->copy()->subMinutes(15))) {
                        continue;
                    }

                    // If scheduled time was more than 30 mins ago, ONLY keep if Delayed or Boarding (still at gate)
                    if ($depCarbon->lt($now->copy()->subMinutes(30))) {
                        if (!in_array($status, ['Delayed', 'Boarding'])) {
                            continue;
                        }
                    }

                    $candidateDeps[] = [
                        'flight_no' => $f['flight_iata'] ?? $f['flight_number'] ?? 'N/A',
                        'airline' => $f['airline_name'] ?? $f['airline_iata'] ?? 'Airline',
                        'destination' => $f['arr_city'] ?? $f['arr_iata'] ?? 'Destination',
                        'dest_iata' => $f['arr_iata'] ?? '',
                        'scheduled_time' => isset($f['dep_time']) ? Carbon::parse($f['dep_time'])->format('H:i') : '--:--',
                        'estimated_time' => (isset($f['dep_actual']) || isset($f['dep_estimated']) || isset($f['dep_time'])) ? Carbon::parse($f['dep_actual'] ?? $f['dep_estimated'] ?? $f['dep_time'])->format('H:i') : '--:--',
                        'terminal' => $f['dep_terminal'] ?? 'T2',
                        'gate' => $f['dep_gate'] ?? null,
                        'status' => $status,
                        '_sort' => $depCarbon->timestamp,
                    ];
                }

                // If candidate list is empty, fallback to raw list so board is never completely blank
                if (empty($candidateDeps)) {
                    foreach (array_slice($rawDeps, 0, 21) as $f) {
                        $candidateDeps[] = [
                            'flight_no' => $f['flight_iata'] ?? $f['flight_number'] ?? 'N/A',
                            'airline' => $f['airline_name'] ?? $f['airline_iata'] ?? 'Airline',
                            'destination' => $f['arr_city'] ?? $f['arr_iata'] ?? 'Destination',
                            'dest_iata' => $f['arr_iata'] ?? '',
                            'scheduled_time' => isset($f['dep_time']) ? Carbon::parse($f['dep_time'])->format('H:i') : '--:--',
                            'estimated_time' => (isset($f['dep_actual']) || isset($f['dep_estimated']) || isset($f['dep_time'])) ? Carbon::parse($f['dep_actual'] ?? $f['dep_estimated'] ?? $f['dep_time'])->format('H:i') : '--:--',
                            'terminal' => $f['dep_terminal'] ?? 'T2',
                            'gate' => $f['dep_gate'] ?? null,
                            'status' => $this->normalizeStatus($f['status'] ?? 'On Time'),
                            '_sort' => isset($f['dep_time']) ? Carbon::parse($f['dep_time'])->timestamp : 0,
                        ];
                    }
                }

                // Sort chronologically (earliest upcoming first)
                usort($candidateDeps, fn($a, $b) => $a['_sort'] <=> $b['_sort']);

                $departures = array_map(function($item) {
                    unset($item['_sort']);
                    return $item;
                }, array_slice($candidateDeps, 0, 21));
            }

            // ARRIVALS: Filter out flights landed long ago, show upcoming and active arrivals
            $arrivals = [];
            if ($arrRes->successful() && isset($arrRes->json()['response'])) {
                $rawArrs = $arrRes->json()['response'];
                $candidateArrs = [];

                foreach ($rawArrs as $f) {
                    $status = $this->normalizeStatus($f['status'] ?? 'Landed');
                    $arrTimeRaw = $f['arr_actual'] ?? $f['arr_estimated'] ?? $f['arr_time'] ?? null;
                    if (!$arrTimeRaw) continue;

                    $arrCarbon = Carbon::parse($arrTimeRaw, 'Asia/Kolkata');

                    // If landed more than 30 mins ago, baggage is already collected -> skip
                    if ($status === 'Landed' && $arrCarbon->lt($now->copy()->subMinutes(30))) {
                        continue;
                    }

                    // If scheduled time was more than 30 mins ago and not delayed, skip
                    if ($arrCarbon->lt($now->copy()->subMinutes(30))) {
                        if (!in_array($status, ['Delayed', 'On Time'])) {
                            continue;
                        }
                    }

                    $candidateArrs[] = [
                        'flight_no' => $f['flight_iata'] ?? $f['flight_number'] ?? 'N/A',
                        'airline' => $f['airline_name'] ?? $f['airline_iata'] ?? 'Airline',
                        'origin' => $f['dep_city'] ?? $f['dep_iata'] ?? 'Origin',
                        'origin_iata' => $f['dep_iata'] ?? '',
                        'scheduled_time' => isset($f['arr_time']) ? Carbon::parse($f['arr_time'])->format('H:i') : '--:--',
                        'estimated_time' => (isset($f['arr_actual']) || isset($f['arr_estimated']) || isset($f['arr_time'])) ? Carbon::parse($f['arr_actual'] ?? $f['arr_estimated'] ?? $f['arr_time'])->format('H:i') : '--:--',
                        'terminal' => $f['arr_terminal'] ?? 'T2',
                        'belt' => $f['arr_baggage'] ?? null,
                        'status' => $status,
                        '_sort' => $arrCarbon->timestamp,
                    ];
                }

                if (empty($candidateArrs)) {
                    foreach (array_slice($rawArrs, 0, 21) as $f) {
                        $candidateArrs[] = [
                            'flight_no' => $f['flight_iata'] ?? $f['flight_number'] ?? 'N/A',
                            'airline' => $f['airline_name'] ?? $f['airline_iata'] ?? 'Airline',
                            'origin' => $f['dep_city'] ?? $f['dep_iata'] ?? 'Origin',
                            'origin_iata' => $f['dep_iata'] ?? '',
                            'scheduled_time' => isset($f['arr_time']) ? Carbon::parse($f['arr_time'])->format('H:i') : '--:--',
                            'estimated_time' => (isset($f['arr_actual']) || isset($f['arr_estimated']) || isset($f['arr_time'])) ? Carbon::parse($f['arr_actual'] ?? $f['arr_estimated'] ?? $f['arr_time'])->format('H:i') : '--:--',
                            'terminal' => $f['arr_terminal'] ?? 'T2',
                            'belt' => $f['arr_baggage'] ?? null,
                            'status' => $this->normalizeStatus($f['status'] ?? 'Landed'),
                            '_sort' => isset($f['arr_time']) ? Carbon::parse($f['arr_time'])->timestamp : 0,
                        ];
                    }
                }

                usort($candidateArrs, fn($a, $b) => $a['_sort'] <=> $b['_sort']);

                $arrivals = array_map(function($item) {
                    unset($item['_sort']);
                    return $item;
                }, array_slice($candidateArrs, 0, 21));
            }

            return [
                'departures' => $departures,
                'arrivals' => $arrivals,
            ];
        }

        return null;
    }

    /**
     * Map varying status strings to unified tags.
     */
    protected function normalizeStatus(string $raw): string
    {
        $s = strtolower($raw);
        if (str_contains($s, 'delay')) return 'Delayed';
        if (str_contains($s, 'cancel')) return 'Cancelled';
        if (str_contains($s, 'board')) return 'Boarding';
        if (str_contains($s, 'land') || str_contains($s, 'arrived')) return 'Landed';
        if (str_contains($s, 'depart') || str_contains($s, 'active') || str_contains($s, 'air')) return 'Departed';
        return 'On Time';
    }

    /**
     * Dynamic high-fidelity schedule generator (Used as default offline/fallback).
     */
    protected function generateDynamicSchedule(string $iata, ?Airport $airport): array
    {
        $now = Carbon::now();

        $airlineCatalog = [
            ['name' => 'IndiGo', 'code' => '6E', 'routes' => ['Delhi (DEL)', 'Bangalore (BLR)', 'Goa (GOI)', 'Dubai (DXB)', 'Jaipur (JAI)']],
            ['name' => 'Air India', 'code' => 'AI', 'routes' => ['London (LHR)', 'New York (JFK)', 'Delhi (DEL)', 'Chennai (MAA)', 'Kochi (COK)']],
            ['name' => 'Vistara', 'code' => 'UK', 'routes' => ['Singapore (SIN)', 'Hyderabad (HYD)', 'Bangalore (BLR)', 'Kolkata (CCU)']],
            ['name' => 'Akasa Air', 'code' => 'QP', 'routes' => ['Ahmedabad (AMD)', 'Pune (PNQ)', 'Lucknow (LKO)', 'Goa (GOX)']],
            ['name' => 'SpiceJet', 'code' => 'SG', 'routes' => ['Dubai (DXB)', 'Patna (PAT)', 'Bagdogra (IXB)', 'Varanasi (VNS)']],
            ['name' => 'Emirates', 'code' => 'EK', 'routes' => ['Dubai (DXB)', 'London (LHR)']],
            ['name' => 'Singapore Airlines', 'code' => 'SQ', 'routes' => ['Singapore (SIN)']],
            ['name' => 'Qatar Airways', 'code' => 'QR', 'routes' => ['Doha (DOH)']],
        ];

        $statuses = ['On Time', 'On Time', 'Boarding', 'On Time', 'Delayed', 'On Time'];
        $departures = [];
        $arrivals = [];

        for ($i = 0; $i < 10; $i++) {
            $airline = $airlineCatalog[$i % count($airlineCatalog)];
            $route = $airline['routes'][$i % count($airline['routes'])];
            $flightNum = $airline['code'] . ' ' . rand(100, 999);
            $depTime = $now->copy()->addMinutes(15 + ($i * 20));
            $estDepTime = ($i === 4) ? $depTime->copy()->addMinutes(25) : $depTime;

            $departures[] = [
                'flight_no' => $flightNum,
                'airline' => $airline['name'],
                'destination' => $route,
                'dest_iata' => substr(explode('(', $route)[1] ?? 'DEL', 0, 3),
                'scheduled_time' => $depTime->format('H:i'),
                'estimated_time' => $estDepTime->format('H:i'),
                'terminal' => ($i % 3 === 0) ? 'T1' : 'T2',
                'gate' => (string) rand(12, 54),
                'status' => $statuses[$i % count($statuses)],
            ];

            $arrAirline = $airlineCatalog[($i + 2) % count($airlineCatalog)];
            $arrRoute = $arrAirline['routes'][($i + 1) % count($arrAirline['routes'])];
            $arrFlightNum = $arrAirline['code'] . ' ' . rand(100, 999);
            $arrTime = $now->copy()->addMinutes(10 + ($i * 18));
            $arrStatus = ($i === 0) ? 'Landed' : (($i === 3) ? 'Delayed' : 'On Time');

            $arrivals[] = [
                'flight_no' => $arrFlightNum,
                'airline' => $arrAirline['name'],
                'origin' => $arrRoute,
                'origin_iata' => substr(explode('(', $arrRoute)[1] ?? 'BOM', 0, 3),
                'scheduled_time' => $arrTime->format('H:i'),
                'estimated_time' => $arrTime->format('H:i'),
                'terminal' => ($i % 3 === 0) ? 'T1' : 'T2',
                'belt' => 'B' . rand(1, 8),
                'status' => $arrStatus,
            ];
        }

        return [
            'airport' => [
                'name' => $airport ? $airport->name : "Chhatrapati Shivaji Maharaj International Airport",
                'iata_code' => $iata,
                'city' => $airport ? $airport->city : 'Mumbai',
                'country' => $airport ? $airport->country : 'India',
            ],
            'is_live' => false,
            'last_updated' => $now->format('d M Y, h:i A'),
            'departures' => $departures,
            'arrivals' => $arrivals,
        ];
    }
}
