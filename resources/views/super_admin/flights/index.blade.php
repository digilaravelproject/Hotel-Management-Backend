@extends('layouts.super_admin')

@section('title', 'Flight Radar & Airport Catalog - Super Admin')
@section('page_title', 'Flight Radar & Airport Catalog')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Flight Radar & Airport Catalog</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Configure live commercial flight tracking APIs and manage global destination airports for hotel TVs.</p>
        </div>
        <button type="button" 
                onclick="openAddAirportModal()" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer">
            <i class="fa-solid fa-plane-circle-check text-xs"></i>
            <span>Add New Airport</span>
        </button>
    </div>

    <!-- Quick Stats Metric Cards (2x2 on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- 1. Total Airports in Catalog -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $totalAirports }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Total Airports</div>
            </div>
        </div>

        <!-- 2. Active Operational Airports -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight leading-tight">{{ $activeAirports ?? $totalAirports }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Active on Hotel TVs</div>
            </div>
        </div>

        <!-- 3. Connected API Provider -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-satellite-dish"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight truncate">
                    {{ strtoupper($setting->provider ?? 'AirLabs') }}
                </div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Commercial API Engine</div>
            </div>
        </div>

        <!-- 4. Cache TTL -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $setting->cache_ttl_minutes ?? 30 }} <span class="text-xs font-semibold text-slate-400 font-sans">Min</span>
                </div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Radar Cache Window</div>
            </div>
        </div>
    </div>

    <!-- Flight API Configuration Card -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-visible relative z-20">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-base shrink-0 shadow-2xs">
                    <i class="fa-solid fa-tower-broadcast"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Commercial Flight Radar API</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Set credentials for live flight schedules, delays, departures & arrival feeds.</p>
                </div>
            </div>
            <div class="self-start sm:self-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold {{ ($setting->is_active ?? true) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-2 h-2 rounded-full mr-2 {{ ($setting->is_active ?? true) ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    {{ ($setting->is_active ?? true) ? 'API Engine Active' : 'Fallback Dynamic Mock' }}
                </span>
            </div>
        </div>

        <form action="{{ route('super-admin.flights.settings.update') }}" method="POST" class="p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
                
                <!-- 1. Flight API Provider Custom Dropdown -->
                <div class="relative" id="providerDropdownWrapper">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Commercial API Engine</label>
                    <input type="hidden" name="provider" id="input_provider" value="{{ $setting->provider ?? 'airlabs' }}">

                    <!-- Trigger Button -->
                    <button type="button" 
                            id="providerDropdownTrigger" 
                            onclick="toggleProviderDropdown(event)" 
                            class="w-full flex items-center justify-between p-3 bg-[#F8F9FD] hover:bg-white border border-slate-200 focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 rounded-2xl cursor-pointer shadow-2xs transition-all select-none text-left group">
                        <div class="flex items-center space-x-3 min-w-0 pr-2">
                            <div id="providerTriggerIcon" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 transition-colors">
                                <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span id="providerTriggerTitle" class="font-extrabold text-xs sm:text-sm text-slate-900 truncate">
                                        AirLabs API
                                    </span>
                                    <span id="providerTriggerBadge" class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-extrabold text-[10px] shrink-0 border border-emerald-200/60">
                                        Recommended
                                    </span>
                                </div>
                                <p id="providerTriggerSubtitle" class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                                    Airport schedules & flight status
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center pl-2 text-slate-400 group-hover:text-slate-600 shrink-0">
                            <i id="providerChevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                        </div>
                    </button>

                    <!-- Floating Dropdown Menu -->
                    <div id="providerDropdownMenu" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-2xl z-50 overflow-hidden p-2 space-y-1.5 transition-all">
                        <!-- Option 1: AirLabs -->
                        <div onclick="selectProvider('airlabs')" 
                             id="opt_airlabs"
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-purple-50/40 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AirLabs API</span>
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-extrabold text-[10px] border border-emerald-200/60">Recommended</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Direct airport schedules & live arrivals/departures</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-[#150A42] font-bold text-sm check-icon hidden"></i>
                        </div>

                        <!-- Option 2: AeroDataBox -->
                        <div onclick="selectProvider('aerodatabox')" 
                             id="opt_aerodatabox"
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-purple-50/40 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AeroDataBox</span>
                                        <span class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-800 font-extrabold text-[10px] border border-sky-200/60">RapidAPI</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Commercial flight radar & global FIDS displays</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-[#150A42] font-bold text-sm check-icon hidden"></i>
                        </div>

                        <!-- Option 3: AviationStack -->
                        <div onclick="selectProvider('aviationstack')" 
                             id="opt_aviationstack"
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-purple-50/40 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-100">
                                    <i class="fa-solid fa-tower-broadcast text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AviationStack</span>
                                        <span class="px-2 py-0.5 rounded-md bg-violet-100 text-violet-800 font-extrabold text-[10px] border border-violet-200/60">REST API</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Global aviation routes & departure timetables</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-[#150A42] font-bold text-sm check-icon hidden"></i>
                        </div>
                    </div>
                </div>

                <!-- 2. API Access Key -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Provider Access Key / Token</label>
                    <div class="relative">
                        <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" 
                               name="api_key" 
                               value="" 
                               autocomplete="new-password"
                               placeholder="{{ !empty($setting->api_key) ? '•••••••••••• (Configured — leave blank to keep)' : 'Enter API key (Leave empty for dynamic mock)' }}" 
                               class="w-full pl-10 pr-4 py-3 text-xs sm:text-sm font-medium rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-800 font-mono transition-all">
                    </div>
                    @if(!empty($setting->api_key))
                        <label class="inline-flex items-center space-x-2 mt-1.5 text-[11px] font-semibold text-rose-600 cursor-pointer">
                            <input type="checkbox" name="clear_api_key" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>Clear stored API key (switch to dynamic mock)</span>
                        </label>
                    @endif
                </div>

                <!-- 3. Cache TTL -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Cache Interval (Minutes)</label>
                    <div class="relative">
                        <i class="fa-solid fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="number" 
                               name="cache_ttl_minutes" 
                               min="5" 
                               max="1440" 
                               value="{{ $setting->cache_ttl_minutes ?? 30 }}" 
                               class="w-full pl-10 pr-4 py-3 text-xs sm:text-sm font-black rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <label class="inline-flex items-center cursor-pointer space-x-3 select-none">
                    <input type="checkbox" name="is_active" value="1" {{ ($setting->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#150A42] focus:ring-[#150A42] cursor-pointer">
                    <span class="text-xs font-bold text-slate-700">Enable Live 3rd-Party Flight API Fetching</span>
                </label>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Radar Configuration</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Filter & Search Bar for Airports -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 shadow-xs">
        <form method="GET" action="{{ route('super-admin.flights.index') }}" class="flex flex-col lg:flex-row lg:items-end justify-between gap-3">
            <!-- Left: Search Box -->
            <div class="w-full lg:w-96 shrink-0">
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Search Airport Catalog</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search IATA, city, airport name, country..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] text-slate-800 font-medium transition-all shadow-inner">
                </div>
            </div>

            <!-- Right: Status Filter & Action (Pushed to Right) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-end gap-3 w-full lg:w-auto">
                <div class="w-full sm:w-44">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Status</label>
                    <x-dropdown name="status" 
                                label="All Statuses" 
                                :options="['1' => 'Active Only', '0' => 'Disabled Only']" 
                                :selected="request('status', '')" 
                                theme="sa" 
                                wrapClass="w-full" 
                                selectClass="py-2 px-3 text-xs font-semibold" />
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                    <button type="submit" class="w-full sm:w-auto py-2 px-5 text-xs rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold shadow-sm transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        <span>Apply Filters</span>
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('super-admin.flights.index') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Airports Master Catalog Table -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-visible">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-visible">
            <table class="w-full text-left text-xs text-slate-800">
                <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5">IATA / ICAO Code</th>
                        <th class="px-5 py-3.5">Airport Title & Specs</th>
                        <th class="px-5 py-3.5">City & Country</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($airports as $airport)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <!-- Code Badge -->
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#150A42] font-black font-mono border border-purple-100 flex items-center justify-center text-xs shadow-2xs shrink-0">
                                        {{ $airport->iata_code }}
                                    </div>
                                    @if($airport->icao_code)
                                        <span class="font-mono text-[11px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md font-semibold">
                                            {{ $airport->icao_code }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Airport Title -->
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                                    {{ $airport->name }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-medium">
                                    Commercial Passenger Hub
                                </div>
                            </td>

                            <!-- City & Location -->
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-1.5 text-slate-700 font-bold text-xs">
                                    <i class="fa-solid fa-location-dot text-purple-600 text-xs"></i>
                                    <span>{{ $airport->city }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 pl-4 mt-0.5">
                                    {{ $airport->country }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                @if($airport->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                        Disabled
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Refresh Cache -->
                                    <form method="POST" action="{{ route('super-admin.flights.refresh', $airport->iata_code) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                title="Force Refresh Live Cache"
                                                class="w-8 h-8 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 flex items-center justify-center transition-all shadow-2xs cursor-pointer">
                                            <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                        </button>
                                    </form>

                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('super-admin.flights.airports.toggle', $airport->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $airport->status ? 'Disable Airport' : 'Enable Airport' }}"
                                                class="w-8 h-8 rounded-xl {{ $airport->status ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-100' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100' }} flex items-center justify-center transition-all shadow-2xs cursor-pointer">
                                            <i class="fa-solid {{ $airport->status ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-plane-slash text-2xl text-slate-300 mb-2 block"></i>
                                No airports found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($airports as $airport)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-black text-[#150A42] bg-purple-50 px-2.5 py-1 rounded-xl border border-purple-100 text-xs shadow-2xs">
                                {{ $airport->iata_code }}
                            </span>
                            @if($airport->icao_code)
                                <span class="font-mono text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $airport->icao_code }}
                                </span>
                            @endif
                        </div>
                        @if($airport->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Disabled
                            </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">{{ $airport->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1 flex items-center space-x-1">
                            <i class="fa-solid fa-location-dot text-purple-600 mr-0.5"></i>
                            <span class="font-semibold text-slate-700">{{ $airport->city }}</span>, {{ $airport->country }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100/60">
                        <form method="POST" action="{{ route('super-admin.flights.refresh', $airport->iata_code) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold transition-colors cursor-pointer">
                                <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                                <span>Refresh Cache</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('super-admin.flights.airports.toggle', $airport->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl {{ $airport->status ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} text-xs font-bold transition-colors cursor-pointer">
                                <i class="fa-solid {{ $airport->status ? 'fa-ban' : 'fa-check' }} text-[11px]"></i>
                                <span>{{ $airport->status ? 'Disable' : 'Enable' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">No airports found in catalog.</div>
            @endforelse
        </div>

        @if($airports->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100">
                {{ $airports->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Add Airport Modal -->
<div id="addAirportModal" onclick="handleModalBackdropClick(event)" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center text-base shrink-0 shadow-2xs">
                    <i class="fa-solid fa-plane-circle-check"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Add Airport to Master Catalog</h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Add a new destination airport for hotel TV suites.</p>
                </div>
            </div>
            <button type="button" onclick="closeAddAirportModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
        
        <form action="{{ route('super-admin.flights.airports.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Airport Official Name <span class="text-rose-500 font-bold">*</span></label>
                <input type="text" 
                       name="name" 
                       required 
                       placeholder="e.g. Pune International Airport" 
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">IATA Code (3 Letters) <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" 
                           name="iata_code" 
                           maxlength="3" 
                           required 
                           placeholder="PNQ" 
                           class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ICAO Code <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" 
                           name="icao_code" 
                           maxlength="4" 
                           placeholder="VAPO" 
                           class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" 
                           name="city" 
                           required 
                           placeholder="Pune" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" 
                           name="country" 
                           required 
                           value="India" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddAirportModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 text-center transition-all">Cancel</button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white text-xs font-extrabold shadow-md transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add to Catalog</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const providerMeta = {
        airlabs: {
            title: 'AirLabs API',
            badge: 'Recommended',
            badgeClass: 'bg-emerald-100 text-emerald-800 border-emerald-200/60',
            subtitle: 'Airport schedules & live arrivals/departures',
            icon: 'fa-solid fa-cloud-arrow-down',
            iconBg: 'bg-emerald-50 text-emerald-600 border-emerald-100'
        },
        aerodatabox: {
            title: 'AeroDataBox',
            badge: 'RapidAPI',
            badgeClass: 'bg-sky-100 text-sky-800 border-sky-200/60',
            subtitle: 'Commercial flight radar & global FIDS displays',
            icon: 'fa-solid fa-plane-departure',
            iconBg: 'bg-sky-50 text-sky-600 border-sky-100'
        },
        aviationstack: {
            title: 'AviationStack',
            badge: 'REST API',
            badgeClass: 'bg-violet-100 text-violet-800 border-violet-200/60',
            subtitle: 'Global aviation routes & departure timetables',
            icon: 'fa-solid fa-tower-broadcast',
            iconBg: 'bg-violet-50 text-violet-600 border-violet-100'
        }
    };

    function toggleProviderDropdown(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const menu = document.getElementById('providerDropdownMenu');
        const chevron = document.getElementById('providerChevron');
        const trigger = document.getElementById('providerDropdownTrigger');
        const isOpen = !menu.classList.contains('hidden');

        if (isOpen) {
            menu.classList.add('hidden');
            chevron.style.transform = 'rotate(0deg)';
            trigger.classList.remove('border-[#150A42]', 'ring-2', 'ring-[#150A42]/15');
        } else {
            menu.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
            trigger.classList.add('border-[#150A42]', 'ring-2', 'ring-[#150A42]/15');
        }
    }

    function selectProvider(providerKey) {
        const meta = providerMeta[providerKey];
        if (!meta) return;

        document.getElementById('input_provider').value = providerKey;
        document.getElementById('providerTriggerTitle').innerText = meta.title;

        const badge = document.getElementById('providerTriggerBadge');
        badge.innerText = meta.badge;
        badge.className = `px-2 py-0.5 rounded-md ${meta.badgeClass} font-extrabold text-[10px] shrink-0 border`;

        document.getElementById('providerTriggerSubtitle').innerText = meta.subtitle;

        const iconContainer = document.getElementById('providerTriggerIcon');
        iconContainer.className = `w-10 h-10 rounded-xl ${meta.iconBg} flex items-center justify-center shrink-0 border transition-colors`;
        iconContainer.innerHTML = `<i class="${meta.icon} text-base"></i>`;

        // Update active checkmarks and highlights
        document.querySelectorAll('.provider-opt').forEach(opt => {
            opt.classList.remove('bg-purple-50/70', 'border-purple-100', 'shadow-2xs');
            opt.classList.add('border-transparent');
            const check = opt.querySelector('.check-icon');
            if (check) check.classList.add('hidden');
        });

        const activeOpt = document.getElementById('opt_' + providerKey);
        if (activeOpt) {
            activeOpt.classList.remove('border-transparent');
            activeOpt.classList.add('bg-purple-50/70', 'border-purple-100', 'shadow-2xs');
            const check = activeOpt.querySelector('.check-icon');
            if (check) check.classList.remove('hidden');
        }

        // Close menu
        const menu = document.getElementById('providerDropdownMenu');
        const chevron = document.getElementById('providerChevron');
        const trigger = document.getElementById('providerDropdownTrigger');
        menu.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
        trigger.classList.remove('border-[#150A42]', 'ring-2', 'ring-[#150A42]/15');
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('providerDropdownWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const menu = document.getElementById('providerDropdownMenu');
            const chevron = document.getElementById('providerChevron');
            const trigger = document.getElementById('providerDropdownTrigger');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                if (trigger) trigger.classList.remove('border-[#150A42]', 'ring-2', 'ring-[#150A42]/15');
            }
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('providerDropdownMenu');
            const chevron = document.getElementById('providerChevron');
            const trigger = document.getElementById('providerDropdownTrigger');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                if (trigger) trigger.classList.remove('border-[#150A42]', 'ring-2', 'ring-[#150A42]/15');
            }
            closeAddAirportModal();
        }
    });

    // Modal Management with background scroll lock
    function openAddAirportModal() {
        const modal = document.getElementById('addAirportModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeAddAirportModal() {
        const modal = document.getElementById('addAirportModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function handleModalBackdropClick(e) {
        if (e.target && e.target.id === 'addAirportModal') {
            closeAddAirportModal();
        }
    }

    // Set initial selection on load
    document.addEventListener('DOMContentLoaded', function () {
        const current = document.getElementById('input_provider').value || 'airlabs';
        selectProvider(current);
    });
</script>
@endsection
