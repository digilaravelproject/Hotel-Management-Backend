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

                @php
                    $selectedPrimaryId = old('primary_airport_id', $hotel->primary_airport_id ?? ($cityAirports->first()->id ?? $allAirports->first()->id ?? ''));
                    $selectedSecondaryId = old('secondary_airport_id', $hotel->secondary_airport_id ?? '');

                    $primaryObj = $allAirports->firstWhere('id', $selectedPrimaryId) ?? $cityAirports->firstWhere('id', $selectedPrimaryId);
                    $secondaryObj = $selectedSecondaryId ? ($allAirports->firstWhere('id', $selectedSecondaryId) ?? $cityAirports->firstWhere('id', $selectedSecondaryId)) : null;
                @endphp

                <form action="{{ route('hotel.flights.update') }}" method="POST" class="space-y-5" id="airportForm">
                    @csrf
                    
                    <!-- Primary Airport Dropdown -->
                    <div class="space-y-2 relative" id="wrapper-primary">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Primary Airport * <span class="text-indigo-600 font-normal lowercase">(default on TV)</span>
                        </label>
                        
                        <input type="hidden" name="primary_airport_id" id="input_primary_airport_id" value="{{ $selectedPrimaryId }}" required>

                        <!-- Trigger Button -->
                        <div id="trigger_primary" 
                             onclick="toggleAirportDropdown('primary')"
                             tabindex="0"
                             class="w-full flex items-center justify-between p-3 bg-white border border-slate-200 hover:border-indigo-400 focus:border-indigo-600 rounded-2xl cursor-pointer shadow-xs transition-all select-none group">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-xs">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <div class="min-w-0 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span id="display_primary_name" class="font-bold text-sm text-slate-900 truncate">
                                            {{ $primaryObj ? $primaryObj->name : 'Select Primary Airport' }}
                                        </span>
                                        <span id="display_primary_iata" class="px-2 py-0.5 rounded-md bg-indigo-100/90 text-indigo-700 font-mono font-black text-xs shrink-0 border border-indigo-200/60">
                                            {{ $primaryObj ? $primaryObj->iata_code : '---' }}
                                        </span>
                                    </div>
                                    <p id="display_primary_city" class="text-xs text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i>
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
                            <!-- Search Header -->
                            <div class="p-3 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="text" 
                                           id="search_primary" 
                                           oninput="filterAirportOptions('primary')" 
                                           placeholder="Search airport, city, or code (e.g. BOM, Mumbai)..." 
                                           class="w-full pl-9 pr-8 py-2 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-800 placeholder-slate-400 transition">
                                    <button type="button" onclick="clearAirportSearch('primary')" id="clear_primary" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- List Options -->
                            <div id="list_primary" class="max-h-[300px] overflow-y-auto p-2 space-y-1">
                                @if($cityAirports->count() > 0)
                                    <div class="group-nearby px-3 py-2 text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-50/70 rounded-xl flex items-center justify-between mt-1 mb-1">
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-crosshairs text-indigo-600"></i> Nearby in {{ $hotel->city }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-200/60 text-indigo-800 font-bold">Recommended</span>
                                    </div>
                                    @foreach($cityAirports as $apt)
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-indigo-50/80 cursor-pointer flex items-center justify-between transition-all {{ $selectedPrimaryId == $apt->id ? 'bg-indigo-50 border border-indigo-200/80 font-semibold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('primary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedPrimaryId == $apt->id ? 'bg-indigo-600 text-white' : 'bg-indigo-100/80 text-indigo-600' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedPrimaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-indigo-600 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="group-other px-3 py-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5 mt-2 mb-1">
                                    <i class="fa-solid fa-earth-asia text-slate-400"></i> Other Major Airports
                                </div>

                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-slate-100 cursor-pointer flex items-center justify-between transition-all {{ $selectedPrimaryId == $apt->id ? 'bg-indigo-50 border border-indigo-200/80 font-semibold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('primary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedPrimaryId == $apt->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedPrimaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-indigo-600 font-bold text-sm"></i>
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

                    <!-- Secondary Airport Dropdown -->
                    <div class="space-y-2 relative" id="wrapper-secondary">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Secondary Airport <span class="text-slate-400 font-normal lowercase">(optional 2nd tab on TV)</span>
                        </label>
                        
                        <input type="hidden" name="secondary_airport_id" id="input_secondary_airport_id" value="{{ $selectedSecondaryId }}">

                        <!-- Trigger Button -->
                        <div id="trigger_secondary" 
                             onclick="toggleAirportDropdown('secondary')"
                             tabindex="0"
                             class="w-full flex items-center justify-between p-3 bg-white border border-slate-200 hover:border-indigo-400 focus:border-indigo-600 rounded-2xl cursor-pointer shadow-xs transition-all select-none group">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div id="icon_secondary_container" class="w-10 h-10 rounded-xl {{ $secondaryObj ? 'bg-sky-50 text-sky-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-xs">
                                    <i id="icon_secondary" class="fa-solid {{ $secondaryObj ? 'fa-plane-arrival' : 'fa-minus' }} text-sm"></i>
                                </div>
                                <div class="min-w-0 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span id="display_secondary_name" class="font-bold text-sm {{ $secondaryObj ? 'text-slate-900' : 'text-slate-500' }} truncate">
                                            {{ $secondaryObj ? $secondaryObj->name : '-- None (Only 1 Airport on TV) --' }}
                                        </span>
                                        <span id="display_secondary_iata" class="px-2 py-0.5 rounded-md bg-sky-100/90 text-sky-700 font-mono font-black text-xs shrink-0 border border-sky-200/60 {{ $secondaryObj ? '' : 'hidden' }}">
                                            {{ $secondaryObj ? $secondaryObj->iata_code : '' }}
                                        </span>
                                    </div>
                                    <p id="display_secondary_city" class="text-xs text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i>
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
                            <!-- Search Header -->
                            <div class="p-3 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="text" 
                                           id="search_secondary" 
                                           oninput="filterAirportOptions('secondary')" 
                                           placeholder="Search airport, city, or code (e.g. DEL, Delhi)..." 
                                           class="w-full pl-9 pr-8 py-2 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-800 placeholder-slate-400 transition">
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
                                            <div class="text-xs font-bold text-slate-700">-- None (Only 1 Airport on TV) --</div>
                                            <div class="text-[11px] text-slate-400 truncate">Do not show a second airport schedule tab on TV</div>
                                        </div>
                                    </div>
                                    <div class="w-5 text-center check-icon {{ empty($selectedSecondaryId) ? '' : 'hidden' }}">
                                        <i class="fa-solid fa-check text-indigo-600 font-bold text-sm"></i>
                                    </div>
                                </div>

                                @if($cityAirports->count() > 0)
                                    <div class="group-nearby px-3 py-2 text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-50/70 rounded-xl flex items-center justify-between mt-1 mb-1">
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-crosshairs text-indigo-600"></i> Nearby in {{ $hotel->city }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-200/60 text-indigo-800 font-bold">Recommended</span>
                                    </div>
                                    @foreach($cityAirports as $apt)
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-indigo-50/80 cursor-pointer flex items-center justify-between transition-all {{ $selectedSecondaryId == $apt->id ? 'bg-indigo-50 border border-indigo-200/80 font-semibold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('secondary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedSecondaryId == $apt->id ? 'bg-indigo-600 text-white' : 'bg-indigo-100/80 text-indigo-600' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedSecondaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-indigo-600 font-bold text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="group-other px-3 py-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5 mt-2 mb-1">
                                    <i class="fa-solid fa-earth-asia text-slate-400"></i> Other Major Airports
                                </div>

                                @foreach($allAirports as $apt)
                                    @if(!$cityAirports->contains('id', $apt->id))
                                        <div class="airport-item p-2.5 rounded-xl hover:bg-slate-100 cursor-pointer flex items-center justify-between transition-all {{ $selectedSecondaryId == $apt->id ? 'bg-indigo-50 border border-indigo-200/80 font-semibold' : '' }}"
                                             data-id="{{ $apt->id }}"
                                             data-name="{{ $apt->name }}"
                                             data-iata="{{ $apt->iata_code }}"
                                             data-city="{{ $apt->city }}"
                                             data-country="{{ $apt->country }}"
                                             onclick="selectAirport('secondary', '{{ $apt->id }}', '{{ addslashes($apt->name) }}', '{{ $apt->iata_code }}', '{{ addslashes($apt->city) }}', '{{ addslashes($apt->country) }}')">
                                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                                <div class="w-8 h-8 rounded-lg {{ $selectedSecondaryId == $apt->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs shrink-0">
                                                    <i class="fa-solid fa-plane"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $apt->name }}</div>
                                                    <div class="text-[11px] text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                                        <i class="fa-solid fa-location-dot text-[9px] text-slate-400"></i> {{ $apt->city }}@if($apt->country), {{ $apt->country }}@endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 shrink-0">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200/70">{{ $apt->iata_code }}</span>
                                                <div class="w-5 text-center check-icon {{ $selectedSecondaryId == $apt->id ? '' : 'hidden' }}">
                                                    <i class="fa-solid fa-check text-indigo-600 font-bold text-sm"></i>
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

                    <div class="pt-3">
                        <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-extrabold text-sm transition-all shadow-md shadow-indigo-600/30 flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-indigo-200"></i>
                            <span>Save Airport Settings</span>
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

@section('styles')
<style>
    /* Custom sleek scrollbar for dropdown lists */
    #list_primary::-webkit-scrollbar,
    #list_secondary::-webkit-scrollbar {
        width: 6px;
    }
    #list_primary::-webkit-scrollbar-track,
    #list_secondary::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }
    #list_primary::-webkit-scrollbar-thumb,
    #list_secondary::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    #list_primary::-webkit-scrollbar-thumb:hover,
    #list_secondary::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection

@section('scripts')
<script>
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
        if (panel && !panel.classList.contains('hidden')) {
            panel.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (wrapper) wrapper.classList.remove('z-50');
        }
    }

    function closeAllAirportDropdowns() {
        closeAirportDropdown('primary');
        closeAirportDropdown('secondary');
    }

    // Close on click outside
    document.addEventListener('click', function(e) {
        const wrapPrimary = document.getElementById('wrapper-primary');
        const wrapSecondary = document.getElementById('wrapper-secondary');
        if (wrapPrimary && !wrapPrimary.contains(e.target) && wrapSecondary && !wrapSecondary.contains(e.target)) {
            closeAllAirportDropdowns();
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllAirportDropdowns();
        }
    });

    function filterAirportOptions(type) {
        const searchInput = document.getElementById('search_' + type);
        const clearBtn = document.getElementById('clear_' + type);
        const filter = (searchInput.value || '').toLowerCase().trim();
        const list = document.getElementById('list_' + type);
        const emptyState = document.getElementById('empty_' + type);
        const items = list.querySelectorAll('.airport-item');

        if (filter.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        let visibleCount = 0;

        items.forEach(item => {
            const name = (item.dataset.name || '').toLowerCase();
            const iata = (item.dataset.iata || '').toLowerCase();
            const city = (item.dataset.city || '').toLowerCase();
            const country = (item.dataset.country || '').toLowerCase();

            const isMatch = filter === '' || name.includes(filter) || iata.includes(filter) || city.includes(filter) || country.includes(filter);

            if (isMatch) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });

        // Toggle category headers based on item visibility
        const nearbyHeader = list.querySelector('.group-nearby');
        if (nearbyHeader) {
            let hasNearby = false;
            let el = nearbyHeader.nextElementSibling;
            while (el && !el.classList.contains('group-other')) {
                if (el.classList.contains('airport-item') && !el.classList.contains('hidden')) {
                    hasNearby = true;
                    break;
                }
                el = el.nextElementSibling;
            }
            nearbyHeader.style.display = hasNearby ? '' : 'none';
        }

        const otherHeader = list.querySelector('.group-other');
        if (otherHeader) {
            let hasOther = false;
            let el = otherHeader.nextElementSibling;
            while (el) {
                if (el.classList.contains('airport-item') && !el.classList.contains('hidden')) {
                    hasOther = true;
                    break;
                }
                el = el.nextElementSibling;
            }
            otherHeader.style.display = hasOther ? '' : 'none';
        }

        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    function clearAirportSearch(type) {
        const searchInput = document.getElementById('search_' + type);
        if (searchInput) {
            searchInput.value = '';
            filterAirportOptions(type);
            searchInput.focus();
        }
    }

    function selectAirport(type, id, name, iata, city, country) {
        const hiddenInput = document.getElementById('input_' + type + '_airport_id');
        const displayName = document.getElementById('display_' + type + '_name');
        const displayIata = document.getElementById('display_' + type + '_iata');
        const displayCity = document.getElementById('display_' + type + '_city');
        const list = document.getElementById('list_' + type);

        // Validation helper: If secondary is chosen same as primary
        if (type === 'secondary' && id !== '') {
            const primaryId = document.getElementById('input_primary_airport_id').value;
            if (primaryId && primaryId == id) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Same Airport Selected',
                        text: 'This airport is already chosen as your Primary Airport. Please select a different airport or None.',
                        confirmButtonColor: '#4f46e5',
                        customClass: {
                            popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs'
                        }
                    });
                } else {
                    alert('This airport is already set as your Primary Airport. Please choose a different airport or None.');
                }
                return;
            }
        }

        // If primary changes to what secondary currently is, reset secondary to None
        if (type === 'primary') {
            const secondaryInput = document.getElementById('input_secondary_airport_id');
            if (secondaryInput && secondaryInput.value == id) {
                selectAirport('secondary', '', '-- None (Only 1 Airport on TV) --', '', 'Optional', '');
            }
        }

        // Update hidden input value
        hiddenInput.value = id;

        // Update trigger UI
        if (type === 'secondary' && (!id || id === '')) {
            displayName.innerText = '-- None (Only 1 Airport on TV) --';
            displayName.classList.remove('text-slate-900');
            displayName.classList.add('text-slate-500');

            displayIata.innerText = '';
            displayIata.classList.add('hidden');

            displayCity.innerHTML = '<i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i><span>Optional</span>';

            const iconContainer = document.getElementById('icon_secondary_container');
            const icon = document.getElementById('icon_secondary');
            if (iconContainer && icon) {
                iconContainer.className = 'w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-xs';
                icon.className = 'fa-solid fa-minus text-sm';
            }
        } else {
            displayName.innerText = name;
            displayName.classList.remove('text-slate-500');
            displayName.classList.add('text-slate-900');

            displayIata.innerText = iata;
            displayIata.classList.remove('hidden');

            const fullCity = city + (country ? ', ' + country : '');
            displayCity.innerHTML = `<i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i><span>${fullCity}</span>`;

            if (type === 'secondary') {
                const iconContainer = document.getElementById('icon_secondary_container');
                const icon = document.getElementById('icon_secondary');
                if (iconContainer && icon) {
                    iconContainer.className = 'w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-xs';
                    icon.className = 'fa-solid fa-plane-arrival text-sm';
                }
            }
        }

        // Update checkmarks and active option highlight
        const items = list.querySelectorAll('.airport-item');
        items.forEach(item => {
            const check = item.querySelector('.check-icon');
            if (item.dataset.id == id) {
                item.classList.add('bg-indigo-50', 'border', 'border-indigo-200/80', 'font-semibold');
                if (check) check.classList.remove('hidden');
            } else {
                item.classList.remove('bg-indigo-50', 'border', 'border-indigo-200/80', 'font-semibold');
                if (check) check.classList.add('hidden');
            }
        });

        closeAirportDropdown(type);
    }
</script>
@endsection
