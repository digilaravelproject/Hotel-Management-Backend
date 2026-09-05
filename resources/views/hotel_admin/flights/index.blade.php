@extends('layouts.hotel_admin')

@section('page_title', 'Hotel Flights & Airport Configuration')

@section('content')
<div class="space-y-8">
    <!-- Top Info Card -->
    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
            <i class="fa-solid fa-plane-departure"></i>
        </div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 mb-3">
                <i class="fa-solid fa-location-dot mr-1.5"></i> Detected Hotel City: {{ $hotel->city ?? 'Not Set' }}
            </span>
            <h2 class="text-3xl font-black tracking-tight">Airport Flight Schedule Setup</h2>
            <p class="text-sm text-slate-300 mt-2">
                Configure the nearest airport(s) for your hotel. When guests open <strong>Interactive Services &rarr; Flights</strong> on their in-room TV, real-time Arrivals and Departures for these airports will be displayed instantly.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Configuration Form Column -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-extrabold text-slate-900 mb-1 flex items-center space-x-2">
                    <i class="fa-solid fa-sliders text-indigo-600"></i>
                    <span>Airport Selection</span>
                </h3>
                <p class="text-xs text-slate-500 mb-6">Airports in and around your city are prioritized below.</p>

                <form action="{{ route('hotel.flights.update') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Primary Airport * <span class="text-indigo-600 font-normal">(Default on TV)</span>
                        </label>
                        <select name="primary_airport_id" required class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @if($cityAirports->count() > 0)
                                <optgroup label="Nearby Airports in {{ $hotel->city }}">
                                    @foreach($cityAirports as $apt)
                                        <option value="{{ $apt->id }}" {{ ($hotel->primary_airport_id == $apt->id) ? 'selected' : '' }}>
                                            {{ $apt->name }} ({{ $apt->iata_code }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <optgroup label="Other Major Airports">
                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <option value="{{ $apt->id }}" {{ ($hotel->primary_airport_id == $apt->id) ? 'selected' : '' }}>
                                            {{ $apt->name }} ({{ $apt->iata_code }}) - {{ $apt->city }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Secondary Airport <span class="text-slate-400 font-normal">(Optional 2nd tab on TV)</span>
                        </label>
                        <select name="secondary_airport_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- None (Only 1 Airport on TV) --</option>
                            @if($cityAirports->count() > 0)
                                <optgroup label="Nearby Airports in {{ $hotel->city }}">
                                    @foreach($cityAirports as $apt)
                                        <option value="{{ $apt->id }}" {{ ($hotel->secondary_airport_id == $apt->id) ? 'selected' : '' }}>
                                            {{ $apt->name }} ({{ $apt->iata_code }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            <optgroup label="Other Major Airports">
                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <option value="{{ $apt->id }}" {{ ($hotel->secondary_airport_id == $apt->id) ? 'selected' : '' }}>
                                            {{ $apt->name }} ({{ $apt->iata_code }}) - {{ $apt->city }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-md shadow-indigo-600/30">
                            Save Airport Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live FIDS Preview Column -->
        <div class="lg:col-span-7">
            <div class="bg-slate-950 rounded-3xl p-6 border border-slate-800 shadow-2xl text-white">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded-xl bg-amber-400/20 text-amber-400 font-mono font-black text-sm border border-amber-400/30">
                            {{ $livePreview['airport']['iata_code'] ?? 'BOM' }}
                        </span>
                        <div>
                            <h4 class="font-extrabold text-sm text-white">{{ $livePreview['airport']['name'] ?? 'Airport Live Board' }}</h4>
                            <p class="text-xs text-slate-400">Live TV Preview &bull; Updated: {{ $livePreview['last_updated'] ?? 'Now' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                        Live FIDS
                    </span>
                </div>

                <!-- Departures preview snippet -->
                <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1">
                    <div class="text-xs font-extrabold text-amber-400 uppercase tracking-wider mb-2 flex items-center space-x-2">
                        <i class="fa-solid fa-plane-departure"></i>
                        <span>Upcoming Departures Sample</span>
                    </div>

                    @if(!empty($livePreview['departures']))
                        @foreach(array_slice($livePreview['departures'], 0, 5) as $flight)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/80 border border-slate-800/80 text-xs font-medium">
                                <div class="flex items-center space-x-3 w-1/3">
                                    <span class="font-mono font-black text-amber-400 text-sm">{{ $flight['flight_no'] }}</span>
                                    <span class="text-slate-400 truncate">{{ $flight['airline'] }}</span>
                                </div>
                                <div class="w-1/3 text-slate-300 font-bold truncate">
                                    &rarr; {{ $flight['destination'] }}
                                </div>
                                <div class="w-1/6 font-mono font-bold text-white text-right">
                                    {{ $flight['scheduled_time'] }}
                                </div>
                                <div class="w-1/6 text-right">
                                    <span class="px-2 py-0.5 rounded text-2xs font-extrabold {{ $flight['status'] === 'On Time' ? 'bg-emerald-500/20 text-emerald-400' : ($flight['status'] === 'Boarding' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400') }}">
                                        {{ $flight['status'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-slate-500 py-4 text-center">No flight data available for this airport.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
