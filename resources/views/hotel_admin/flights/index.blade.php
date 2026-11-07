@extends('layouts.hotel_admin')

@section('title', 'Hotel Flights & Airport Radar - Hotel Admin')
@section('page_title', 'In-Room Flight Schedules')

@section('styles')
<style>
    /* TV Simulation Frame for FIDS Screen */
    .fids-tv-frame {
        aspect-ratio: 16 / 10;
        background: #09061a;
        box-shadow: 0 20px 40px -15px rgba(21, 10, 66, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
    }

    /* Custom sleek scrollbar for dropdown lists and flight boards */
    #list_primary::-webkit-scrollbar,
    #list_secondary::-webkit-scrollbar,
    .fids-scroll::-webkit-scrollbar {
        width: 5px;
    }
    #list_primary::-webkit-scrollbar-track,
    #list_secondary::-webkit-scrollbar-track,
    .fids-scroll::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.6);
        border-radius: 9999px;
    }
    #list_primary::-webkit-scrollbar-thumb,
    #list_secondary::-webkit-scrollbar-thumb,
    .fids-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    #list_primary::-webkit-scrollbar-thumb:hover,
    #list_secondary::-webkit-scrollbar-thumb:hover,
    .fids-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection

@section('content')
@php
    $selectedPrimaryId = old('primary_airport_id', $hotel->primary_airport_id ?? ($cityAirports->first()->id ?? $allAirports->first()->id ?? ''));
    $selectedSecondaryId = old('secondary_airport_id', $hotel->secondary_airport_id ?? '');

    $primaryObj = $allAirports->firstWhere('id', $selectedPrimaryId) ?? $cityAirports->firstWhere('id', $selectedPrimaryId);
    $secondaryObj = $selectedSecondaryId ? ($allAirports->firstWhere('id', $selectedSecondaryId) ?? $cityAirports->firstWhere('id', $selectedSecondaryId)) : null;

    $connectedTvCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
    $departuresCount = !empty($livePreview['departures']) ? count($livePreview['departures']) : 0;
    $arrivalsCount = !empty($livePreview['arrivals']) ? count($livePreview['arrivals']) : 0;
@endphp

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span>TV Experience</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">Airport & Flight Schedules</span>
            </div>
            <div class="flex items-center space-x-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Airport Flight Radar & Schedule Setup</h1>
                <span class="text-xs font-black text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/80 font-mono">
                    <i class="fa-solid fa-location-dot mr-1 text-purple-600"></i>{{ $hotel->city ?? 'Property City' }} (100km Geolocation Active)
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configure live airport flight departures & arrivals for guests on in-room Smart TVs.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.menus.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-list-check text-xs text-purple-600"></i>
                <span>TV Menus Visibility</span>
            </a>
            <button type="button" 
                    onclick="document.getElementById('saveAirportBtn').click()" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                <span>Save Airport Setup</span>
            </button>
        </div>
    </div>

    <!-- Executive KPI Metrics Summary Cards (Thin Elegant Borders) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        
        <!-- 1. Primary Airport -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-plane-departure text-purple-700"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-lg sm:text-xl font-black text-slate-900 tracking-tight font-mono">{{ $primaryObj ? $primaryObj->iata_code : 'N/A' }}</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">{{ $primaryObj ? $primaryObj->name : 'Primary Airport' }}</div>
            </div>
        </div>

        <!-- 2. Secondary Airport -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-plane-arrival text-sky-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-lg sm:text-xl font-black text-slate-900 tracking-tight font-mono">
                    {{ $secondaryObj ? $secondaryObj->iata_code : 'Single Mode' }}
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">
                    {{ $secondaryObj ? $secondaryObj->name : 'No 2nd Airport Tab' }}
                </div>
            </div>
        </div>

        <!-- 3. Nearby Coverage Radius -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-location-crosshairs text-emerald-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">100 KM</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Proximity Radar in {{ $hotel->city ?? 'City' }}</div>
            </div>
        </div>

        <!-- 4. Live Flight Radar Feed -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-tower-broadcast text-amber-500"></i>
            </div>
            <div class="min-w-0">
                <div class="text-sm sm:text-base font-black text-slate-900 tracking-tight">Live FIDS</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Auto-Synced on Room TVs</div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Configuration Form (Left) & Live TV Flight Radar (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

        <!-- CONFIGURATION FORM COLUMN (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs space-y-5">
                <div>
                    <h3 class="text-base font-black text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-sliders text-[#150A42]"></i>
                        <span>Airport Selection</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Airports located within 100km of your property are prioritized.</p>
                </div>

                <form action="{{ route('hotel.flights.update') }}" method="POST" class="space-y-5" id="airportForm">
                    @csrf
                    
                    <!-- 1. Primary Airport Dropdown Selector -->
                    <div class="space-y-1.5 relative" id="wrapper-primary">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                            Primary Airport * <span class="text-purple-600 font-normal lowercase">(default TV tab)</span>
                        </label>
                        
                        <input type="hidden" name="primary_airport_id" id="input_primary_airport_id" value="{{ $selectedPrimaryId }}" required>

                        <!-- Trigger Button Card -->
                        <div id="trigger_primary" 
                             onclick="toggleAirportDropdown('primary')"
                             tabindex="0"
                             class="w-full flex items-center justify-between p-3.5 bg-white border border-slate-200/90 hover:border-purple-300 focus:border-[#150A42] rounded-2xl cursor-pointer shadow-2xs transition-all select-none group">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 group-hover:bg-[#150A42] group-hover:text-white transition-colors shadow-2xs border border-purple-100">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <div class="min-w-0 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span id="display_primary_name" class="font-black text-xs sm:text-sm text-slate-900 truncate">
                                            {{ $primaryObj ? $primaryObj->name : 'Select Primary Airport' }}
                                        </span>
                                        <span id="display_primary_iata" class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 font-mono font-black text-xs shrink-0 border border-purple-200/60">
                                            {{ $primaryObj ? $primaryObj->iata_code : '---' }}
                                        </span>
                                    </div>
                                    <p id="display_primary_city" class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5 font-medium">
                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i>
                                        <span>{{ $primaryObj ? ($primaryObj->city . ($primaryObj->country ? ', ' . $primaryObj->country : '')) : 'Required' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center pl-2 text-slate-400 group-hover:text-slate-600 shrink-0">
                                <i id="chevron_primary" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                            </div>
                        </div>

                        <!-- Dropdown Panel -->
                        <div id="panel_primary" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-2xl z-50 overflow-hidden flex flex-col transition-all">
                            <!-- Search Bar -->
                            <div class="p-3 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="text" 
                                           id="search_primary" 
                                           oninput="filterAirportOptions('primary')" 
                                           placeholder="Search airport, city, or IATA code..." 
                                           class="w-full pl-9 pr-8 py-2 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-purple-500/15 text-slate-800 placeholder-slate-400 transition">
                                    <button type="button" onclick="clearAirportSearch('primary')" id="clear_primary" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- List Options -->
                            <div id="list_primary" class="max-h-[300px] overflow-y-auto p-2 space-y-1">
                                @if($cityAirports->count() > 0)
                                    <div class="group-nearby px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-purple-700 bg-purple-50/70 rounded-xl flex items-center justify-between mt-1 mb-1 font-mono">
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-crosshairs text-purple-600"></i> Nearby {{ $hotel->city }}</span>
                                        <span class="text-[9px] px-2 py-0.5 rounded-md bg-purple-200/60 text-purple-900 font-bold">Recommended</span>
                                    </div>
                                    @foreach($cityAirports as $apt)
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-purple-50/80 cursor-pointer flex items-center justify-between transition-all {{ $selectedPrimaryId == $apt->id ? 'bg-purple-50 border border-purple-200/80 font-bold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('primary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedPrimaryId == $apt->id ? 'bg-[#150A42] text-white' : 'bg-purple-100/80 text-purple-700' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-black text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedPrimaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-purple-700 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="group-other px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mt-2 mb-1 font-mono">
                                    <i class="fa-solid fa-earth-asia text-slate-400"></i> Other Major Airports
                                </div>

                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-slate-100 cursor-pointer flex items-center justify-between transition-all {{ $selectedPrimaryId == $apt->id ? 'bg-purple-50 border border-purple-200/80 font-bold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('primary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedPrimaryId == $apt->id ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-black text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedPrimaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-purple-700 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Empty Results -->
                            <div id="empty_primary" class="hidden py-8 px-4 text-center">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-sm">
                                    <i class="fa-solid fa-plane-slash"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700">No airports found</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Try searching with a different name or IATA code.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Secondary Airport Dropdown Selector -->
                    <div class="space-y-1.5 relative" id="wrapper-secondary">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                            Secondary Airport <span class="text-slate-400 font-normal lowercase">(optional 2nd tab on TV)</span>
                        </label>
                        
                        <input type="hidden" name="secondary_airport_id" id="input_secondary_airport_id" value="{{ $selectedSecondaryId }}">

                        <!-- Trigger Button Card -->
                        <div id="trigger_secondary" 
                             onclick="toggleAirportDropdown('secondary')"
                             tabindex="0"
                             class="w-full flex items-center justify-between p-3.5 bg-white border border-slate-200/90 hover:border-purple-300 focus:border-[#150A42] rounded-2xl cursor-pointer shadow-2xs transition-all select-none group">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div id="icon_secondary_container" class="w-10 h-10 rounded-xl {{ $secondaryObj ? 'bg-sky-50 text-sky-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0 group-hover:bg-[#150A42] group-hover:text-white transition-colors shadow-2xs border border-slate-100">
                                    <i id="icon_secondary" class="fa-solid {{ $secondaryObj ? 'fa-plane-arrival' : 'fa-minus' }} text-sm"></i>
                                </div>
                                <div class="min-w-0 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span id="display_secondary_name" class="font-black text-xs sm:text-sm {{ $secondaryObj ? 'text-slate-900' : 'text-slate-500' }} truncate">
                                            {{ $secondaryObj ? $secondaryObj->name : '-- None (Only 1 Airport on TV) --' }}
                                        </span>
                                        <span id="display_secondary_iata" class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-800 font-mono font-black text-xs shrink-0 border border-sky-200/60 {{ $secondaryObj ? '' : 'hidden' }}">
                                            {{ $secondaryObj ? $secondaryObj->iata_code : '' }}
                                        </span>
                                    </div>
                                    <p id="display_secondary_city" class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5 font-medium">
                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i>
                                        <span>{{ $secondaryObj ? ($secondaryObj->city . ($secondaryObj->country ? ', ' . $secondaryObj->country : '')) : 'Optional' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center pl-2 text-slate-400 group-hover:text-slate-600 shrink-0">
                                <i id="chevron_secondary" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                            </div>
                        </div>

                        <!-- Dropdown Panel -->
                        <div id="panel_secondary" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-2xl z-50 overflow-hidden flex flex-col transition-all">
                            <!-- Search Bar -->
                            <div class="p-3 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="text" 
                                           id="search_secondary" 
                                           oninput="filterAirportOptions('secondary')" 
                                           placeholder="Search airport, city, or IATA code..." 
                                           class="w-full pl-9 pr-8 py-2 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-purple-500/15 text-slate-800 placeholder-slate-400 transition">
                                    <button type="button" onclick="clearAirportSearch('secondary')" id="clear_secondary" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- List Options -->
                            <div id="list_secondary" class="max-h-[300px] overflow-y-auto p-2 space-y-1">
                                <!-- Option None -->
                                <div class="airport-item p-2.5 rounded-xl hover:bg-slate-100 cursor-pointer flex items-center justify-between transition-all {{ empty($selectedSecondaryId) ? 'bg-slate-100 border border-slate-200 font-semibold' : '' }}"
                                     data-id=""
                                     data-name="-- None (Only 1 Airport on TV) --"
                                     data-iata=""
                                     data-city="Optional"
                                     data-country=""
                                     onclick="selectAirport('secondary', '', '-- None (Only 1 Airport on TV) --', '', 'Optional', '')">
                                    <div class="flex items-center space-x-3 min-w-0 pr-2">
                                        <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-ban"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-black text-slate-700">-- None (Only 1 Airport on TV) --</div>
                                            <div class="text-[11px] text-slate-400 truncate">Do not show a second airport schedule tab on TV</div>
                                        </div>
                                    </div>
                                    <div class="w-5 text-center check-icon {{ empty($selectedSecondaryId) ? '' : 'hidden' }}">
                                        <i class="fa-solid fa-check text-purple-700 font-bold text-sm"></i>
                                    </div>
                                </div>

                                @if($cityAirports->count() > 0)
                                    <div class="group-nearby px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-purple-700 bg-purple-50/70 rounded-xl flex items-center justify-between mt-1 mb-1 font-mono">
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-crosshairs text-purple-600"></i> Nearby {{ $hotel->city }}</span>
                                        <span class="text-[9px] px-2 py-0.5 rounded-md bg-purple-200/60 text-purple-900 font-bold">Recommended</span>
                                    </div>
                                    @foreach($cityAirports as $apt)
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-purple-50/80 cursor-pointer flex items-center justify-between transition-all {{ $selectedSecondaryId == $apt->id ? 'bg-purple-50 border border-purple-200/80 font-bold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('secondary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedSecondaryId == $apt->id ? 'bg-[#150A42] text-white' : 'bg-purple-100/80 text-purple-700' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-black text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedSecondaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-purple-700 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="group-other px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mt-2 mb-1 font-mono">
                                    <i class="fa-solid fa-earth-asia text-slate-400"></i> Other Major Airports
                                </div>

                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-slate-100 cursor-pointer flex items-center justify-between transition-all {{ $selectedSecondaryId == $apt->id ? 'bg-purple-50 border border-purple-200/80 font-bold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('secondary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedSecondaryId == $apt->id ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-black text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedSecondaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-purple-700 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Empty Results -->
                            <div id="empty_secondary" class="hidden py-8 px-4 text-center">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-sm">
                                    <i class="fa-solid fa-plane-slash"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700">No airports found</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Try searching with a different name or IATA code.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                id="saveAirportBtn" 
                                class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] active:scale-[0.99] text-white font-black text-xs transition-all shadow-md shadow-[#150A42]/15 flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-amber-300"></i>
                            <span>Save Airport Settings</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- LIVE FIDS TELEVISION RADAR SCREEN (lg:col-span-7) -->
        <div class="lg:col-span-7 space-y-4">
            
            <!-- Television Outer Casing -->
            <div class="relative rounded-[28px] p-2 bg-gradient-to-b from-slate-900 to-slate-950 shadow-2xl border-2 border-slate-700/80">
                
                <!-- Sensor Notch -->
                <div class="absolute top-1 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-slate-800 border border-slate-700"></div>

                <!-- Inner Screen Display -->
                <div class="fids-tv-frame rounded-2xl overflow-hidden p-4 sm:p-5 text-white flex flex-col justify-between select-none">
                    
                    <!-- FIDS Header -->
                    <div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 rounded-xl bg-amber-400/20 text-amber-400 font-mono font-black text-xs sm:text-sm border border-amber-400/30 shadow-2xs">
                                    {{ $livePreview['airport']['iata_code'] ?? ($primaryObj ? $primaryObj->iata_code : 'BOM') }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="font-black text-xs sm:text-sm text-white truncate">
                                        {{ $livePreview['airport']['name'] ?? ($primaryObj ? $primaryObj->name : 'Airport Flight Board') }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 font-mono">In-Room Smart TV Simulation &bull; Updated: {{ $livePreview['last_updated'] ?? 'Live' }}</p>
                                </div>
                            </div>
                            
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                Live FIDS
                            </span>
                        </div>

                        <!-- Departures vs Arrivals Tabs Switcher -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="inline-flex rounded-xl bg-white/10 p-0.5 space-x-1 text-[11px] font-black">
                                <button type="button" 
                                        onclick="switchFidsTab('departures')" 
                                        id="fidsTabBtnDepartures" 
                                        class="px-3 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all flex items-center space-x-1.5">
                                    <i class="fa-solid fa-plane-departure text-purple-600 text-[10px]"></i>
                                    <span>Departures</span>
                                    <span class="text-[9px] px-1.5 py-0.2 rounded-full bg-purple-100 text-purple-800 font-mono">{{ $departuresCount }}</span>
                                </button>
                                <button type="button" 
                                        onclick="switchFidsTab('arrivals')" 
                                        id="fidsTabBtnArrivals" 
                                        class="px-3 py-1 rounded-lg text-slate-300 hover:text-white transition-all flex items-center space-x-1.5">
                                    <i class="fa-solid fa-plane-arrival text-sky-400 text-[10px]"></i>
                                    <span>Arrivals</span>
                                    <span class="text-[9px] px-1.5 py-0.2 rounded-full bg-white/15 text-slate-300 font-mono">{{ $arrivalsCount }}</span>
                                </button>
                            </div>

                            <span class="text-[10px] font-mono text-amber-300/80 hidden sm:inline">
                                <i class="fa-solid fa-tower-broadcast mr-1"></i> Live Schedule Radar
                            </span>
                        </div>
                    </div>

                    <!-- FIDS Board Content Area -->
                    <div class="fids-scroll overflow-y-auto max-h-[300px] space-y-1.5 pr-1">
                        
                        <!-- 1. DEPARTURES TABLE -->
                        <div id="fidsDeparturesList" class="space-y-1.5">
                            @if(!empty($livePreview['departures']))
                                @foreach($livePreview['departures'] as $flight)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 text-xs transition-colors">
                                        <div class="flex items-center space-x-2.5 w-1/3 min-w-0">
                                            <span class="font-mono font-black text-amber-300 text-xs sm:text-sm shrink-0">{{ $flight['flight_no'] }}</span>
                                            <span class="text-slate-400 text-[11px] truncate">{{ $flight['airline'] }}</span>
                                        </div>
                                        <div class="w-1/3 text-slate-200 font-black truncate px-1 text-[11px] sm:text-xs">
                                            &rarr; {{ $flight['destination'] }}
                                        </div>
                                        <div class="w-1/6 font-mono font-bold text-white text-right text-[11px]">
                                            {{ $flight['scheduled_time'] }}
                                        </div>
                                        <div class="w-1/6 text-right">
                                            @php
                                                $status = $flight['status'] ?? 'Scheduled';
                                                $statusClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                                if (in_array($status, ['Delayed', 'Cancelled'])) {
                                                    $statusClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                                                } elseif (in_array($status, ['Boarding', 'Gate Closed'])) {
                                                    $statusClass = 'bg-amber-500/20 text-amber-400 border border-amber-500/30';
                                                }
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider inline-block {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-12 text-center text-slate-500 text-xs">
                                    <i class="fa-solid fa-plane-circle-exclamation text-2xl mb-1 text-slate-600 block"></i>
                                    <span>No active departures returned for this airport schedule.</span>
                                </div>
                            @endif
                        </div>

                        <!-- 2. ARRIVALS TABLE (Hidden by default) -->
                        <div id="fidsArrivalsList" class="hidden space-y-1.5">
                            @if(!empty($livePreview['arrivals']))
                                @foreach($livePreview['arrivals'] as $flight)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 text-xs transition-colors">
                                        <div class="flex items-center space-x-2.5 w-1/3 min-w-0">
                                            <span class="font-mono font-black text-sky-400 text-xs sm:text-sm shrink-0">{{ $flight['flight_no'] }}</span>
                                            <span class="text-slate-400 text-[11px] truncate">{{ $flight['airline'] }}</span>
                                        </div>
                                        <div class="w-1/3 text-slate-200 font-black truncate px-1 text-[11px] sm:text-xs">
                                            &larr; {{ $flight['origin'] }}
                                        </div>
                                        <div class="w-1/6 font-mono font-bold text-white text-right text-[11px]">
                                            {{ $flight['scheduled_time'] }}
                                        </div>
                                        <div class="w-1/6 text-right">
                                            @php
                                                $status = $flight['status'] ?? 'Scheduled';
                                                $statusClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                                if (in_array($status, ['Delayed', 'Cancelled'])) {
                                                    $statusClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                                                } elseif (in_array($status, ['Landed', 'Arrived'])) {
                                                    $statusClass = 'bg-sky-500/20 text-sky-400 border border-sky-500/30';
                                                }
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider inline-block {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-12 text-center text-slate-500 text-xs">
                                    <i class="fa-solid fa-plane-circle-exclamation text-2xl mb-1 text-slate-600 block"></i>
                                    <span>No active arrivals returned for this airport schedule.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- FIDS TV Stand Footer -->
                    <div class="flex items-center justify-between pt-3 mt-2 border-t border-white/10 text-[9px] font-mono text-slate-400">
                        <span>BROADCAST: 1080p FHD</span>
                        <span class="font-black tracking-widest text-white/50">PAX TV FLIGHT RADAR</span>
                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-circle text-[6px] mr-1"></i>AUTO-REFRESH</span>
                    </div>
                </div>
            </div>

            <!-- TV Advice Card -->
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-1">
                <div class="flex items-center space-x-2 text-xs font-black text-slate-800">
                    <i class="fa-solid fa-circle-info text-purple-600"></i>
                    <span>Guest In-Room Experience</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                    Guests can view this live board by opening <strong>Interactive Services &rarr; Flights</strong> with their TV remote. Flight times, gates, and statuses are refreshed automatically in the background.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function switchFidsTab(mode) {
        const depList = document.getElementById('fidsDeparturesList');
        const arrList = document.getElementById('fidsArrivalsList');
        const depBtn = document.getElementById('fidsTabBtnDepartures');
        const arrBtn = document.getElementById('fidsTabBtnArrivals');

        if (mode === 'departures') {
            depList.classList.remove('hidden');
            arrList.classList.add('hidden');
            depBtn.className = 'px-3 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all flex items-center space-x-1.5';
            arrBtn.className = 'px-3 py-1 rounded-lg text-slate-300 hover:text-white transition-all flex items-center space-x-1.5';
        } else {
            depList.classList.add('hidden');
            arrList.classList.remove('hidden');
            depBtn.className = 'px-3 py-1 rounded-lg text-slate-300 hover:text-white transition-all flex items-center space-x-1.5';
            arrBtn.className = 'px-3 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all flex items-center space-x-1.5';
        }
    }

    function toggleAirportDropdown(type) {
        const otherType = type === 'primary' ? 'secondary' : 'primary';
        closeAirportDropdown(otherType);

        const panel = document.getElementById('panel_' + type);
        const chevron = document.getElementById('chevron_' + type);
        const wrapper = document.getElementById('wrapper-' + type);
        const isHidden = panel.classList.contains('hidden');

        if (isHidden) {
            panel.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            wrapper.classList.add('z-50');
            const searchInput = document.getElementById('search_' + type);
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 60);
            }
        } else {
            closeAirportDropdown(type);
        }
    }

    function closeAirportDropdown(type) {
        const panel = document.getElementById('panel_' + type);
        const chevron = document.getElementById('chevron_' + type);
        const wrapper = document.getElementById('wrapper-' + type);
        if (panel) panel.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
        if (wrapper) wrapper.classList.remove('z-50');
    }

    function selectAirport(type, id, name, iata, city, country) {
        document.getElementById('input_' + type + '_airport_id').value = id;
        document.getElementById('display_' + type + '_name').textContent = name;
        
        const iataEl = document.getElementById('display_' + type + '_iata');
        if (iataEl) {
            if (iata) {
                iataEl.textContent = iata;
                iataEl.classList.remove('hidden');
            } else {
                iataEl.classList.add('hidden');
            }
        }

        const cityEl = document.getElementById('display_' + type + '_city');
        if (cityEl) {
            cityEl.innerHTML = `<i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> <span>${city}${country ? ', ' + country : ''}</span>`;
        }

        if (type === 'secondary') {
            const iconContainer = document.getElementById('icon_secondary_container');
            const icon = document.getElementById('icon_secondary');
            if (id) {
                iconContainer.className = 'w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 group-hover:bg-[#150A42] group-hover:text-white transition-colors shadow-2xs border border-slate-100';
                icon.className = 'fa-solid fa-plane-arrival text-sm';
            } else {
                iconContainer.className = 'w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-[#150A42] group-hover:text-white transition-colors shadow-2xs border border-slate-100';
                icon.className = 'fa-solid fa-minus text-sm';
            }
        }

        // Update active check indicators
        const items = document.querySelectorAll('#list_' + type + ' .airport-item');
        items.forEach(item => {
            const itemId = item.getAttribute('data-id') || '';
            const check = item.querySelector('.check-icon');
            if (itemId == id) {
                item.classList.add('bg-purple-50', 'border', 'border-purple-200/80', 'font-bold');
                if (check) check.classList.remove('hidden');
            } else {
                item.classList.remove('bg-purple-50', 'border', 'border-purple-200/80', 'font-bold');
                if (check) check.classList.add('hidden');
            }
        });

        closeAirportDropdown(type);
    }

    function filterAirportOptions(type) {
        const query = (document.getElementById('search_' + type).value || '').toLowerCase().trim();
        const clearBtn = document.getElementById('clear_' + type);
        if (clearBtn) {
            clearBtn.classList.toggle('hidden', query.length === 0);
        }

        const items = document.querySelectorAll('#list_' + type + ' .airport-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const iata = (item.getAttribute('data-iata') || '').toLowerCase();
            const city = (item.getAttribute('data-city') || '').toLowerCase();
            const country = (item.getAttribute('data-country') || '').toLowerCase();

            if (!query || name.includes(query) || iata.includes(query) || city.includes(query) || country.includes(query)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const emptyMsg = document.getElementById('empty_' + type);
        if (emptyMsg) {
            emptyMsg.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function clearAirportSearch(type) {
        const input = document.getElementById('search_' + type);
        if (input) {
            input.value = '';
            filterAirportOptions(type);
            input.focus();
        }
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#wrapper-primary')) closeAirportDropdown('primary');
        if (!e.target.closest('#wrapper-secondary')) closeAirportDropdown('secondary');
    });
</script>
@endsection
