@extends('layouts.super_admin')

@section('page_title', 'Flight API & Airports Management')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">Total Airports</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalAirports }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">Active Provider</p>
                <h3 class="text-xl font-black text-slate-800 uppercase truncate">{{ $setting->provider ?? 'AirLabs' }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4 sm:col-span-2 lg:col-span-1">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-database"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">Cache TTL</p>
                <h3 class="text-xl font-black text-slate-800">{{ $setting->cache_ttl_minutes ?? 30 }} Mins</h3>
            </div>
        </div>
    </div>

    <!-- API Settings Configuration Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs relative z-20">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-key text-rose-600"></i>
                    <span>Flight API Configuration</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Configure your 3rd-party commercial flight tracking API credentials.</p>
            </div>
            <div class="self-start sm:self-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ ($setting->is_active ?? true) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-2 h-2 rounded-full mr-2 {{ ($setting->is_active ?? true) ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    {{ ($setting->is_active ?? true) ? 'API Enabled' : 'Disabled (Mock Fallback Active)' }}
                </span>
            </div>
        </div>

        <form action="{{ route('super-admin.flights.settings.update') }}" method="POST" class="p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Flight API Provider Custom Dropdown -->
                <div class="relative" id="providerDropdownWrapper">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Flight API Provider</label>
                    <input type="hidden" name="provider" id="input_provider" value="{{ $setting->provider ?? 'airlabs' }}">

                    <!-- Trigger Button -->
                    <button type="button" 
                            id="providerDropdownTrigger" 
                            onclick="toggleProviderDropdown(event)" 
                            class="w-full flex items-center justify-between p-2.5 sm:p-3 bg-white border border-slate-200/90 hover:border-slate-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 rounded-2xl cursor-pointer shadow-2xs transition-all select-none text-left group">
                        <div class="flex items-center space-x-3 min-w-0 pr-2">
                            <div id="providerTriggerIcon" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100/80 transition-colors">
                                <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span id="providerTriggerTitle" class="font-extrabold text-sm text-slate-900 truncate">
                                        AirLabs API
                                    </span>
                                    <span id="providerTriggerBadge" class="px-2 py-0.5 rounded-md bg-emerald-100/80 text-emerald-800 font-extrabold text-[10px] shrink-0 border border-emerald-200/60">
                                        Recommended
                                    </span>
                                </div>
                                <p id="providerTriggerSubtitle" class="text-xs text-slate-500 font-medium truncate mt-0.5">
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
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-slate-50 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AirLabs API</span>
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-100/80 text-emerald-800 font-extrabold text-[10px] border border-emerald-200/60">Recommended</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Direct airport schedules & live arrivals/departures</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-rose-600 font-bold text-sm check-icon hidden"></i>
                        </div>

                        <!-- Option 2: AeroDataBox -->
                        <div onclick="selectProvider('aerodatabox')" 
                             id="opt_aerodatabox"
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-slate-50 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AeroDataBox</span>
                                        <span class="px-2 py-0.5 rounded-md bg-sky-100/80 text-sky-800 font-extrabold text-[10px] border border-sky-200/60">RapidAPI</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Commercial flight radar & global FIDS displays</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-rose-600 font-bold text-sm check-icon hidden"></i>
                        </div>

                        <!-- Option 3: AviationStack -->
                        <div onclick="selectProvider('aviationstack')" 
                             id="opt_aviationstack"
                             class="provider-opt p-2.5 rounded-xl cursor-pointer flex items-center justify-between transition-all hover:bg-slate-50 border border-transparent">
                            <div class="flex items-center space-x-3 min-w-0 pr-2">
                                <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-100">
                                    <i class="fa-solid fa-tower-broadcast text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-extrabold text-xs sm:text-sm text-slate-900">AviationStack</span>
                                        <span class="px-2 py-0.5 rounded-md bg-violet-100/80 text-violet-800 font-extrabold text-[10px] border border-violet-200/60">REST API</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 truncate">Global aviation routes & departure timetables</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-check text-rose-600 font-bold text-sm check-icon hidden"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">API Access Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                        <input type="text" name="api_key" value="{{ $setting->api_key ?? '' }}" placeholder="Enter provider API key (Leave empty for Dynamic Mock)" class="w-full rounded-2xl border border-slate-200/90 pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 font-mono text-xs transition-all bg-white hover:border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cache Duration (Minutes)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-clock text-xs"></i>
                        </div>
                        <input type="number" name="cache_ttl_minutes" min="5" max="1440" value="{{ $setting->cache_ttl_minutes ?? 30 }}" class="w-full rounded-2xl border border-slate-200/90 pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 font-bold text-slate-800 transition-all bg-white hover:border-slate-300">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <label class="inline-flex items-center cursor-pointer space-x-3 select-none">
                    <input type="checkbox" name="is_active" value="1" {{ ($setting->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 h-4 w-4">
                    <span class="text-xs font-bold text-slate-700">Enable Live Flight API Fetching</span>
                </label>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm transition-all shadow-md shadow-rose-600/20 text-center">
                    Save API Configuration
                </button>
            </div>
        </form>
    </div>

    <!-- Airports Master Catalog Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-plane-arrival text-sky-600"></i>
                    <span>Airports Master Catalog</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Airports available for hotels across all regions.</p>
            </div>
            
            <div>
                <!-- Add Airport Modal Trigger -->
                <button onclick="openAddAirportModal()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add New Airport</span>
                </button>
            </div>
        </div>

        <!-- Mobile Card View (shown on screens < 768px) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($airports as $airport)
                <div class="p-4 sm:p-5 flex flex-col space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-black text-slate-900 bg-sky-50 text-sky-700 px-2.5 py-1 rounded-lg border border-sky-200 text-xs">{{ $airport->iata_code }}</span>
                            @if($airport->icao_code)
                                <span class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $airport->icao_code }}</span>
                            @endif
                        </div>
                        @if($airport->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Disabled
                            </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">{{ $airport->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>
                            <span class="font-semibold text-slate-700">{{ $airport->city }}</span>, {{ $airport->country }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100/60">
                        <a href="{{ route('super-admin.flights.refresh', $airport->iata_code) }}" title="Force refresh live cache" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold transition-colors">
                            <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                            <span>Refresh Cache</span>
                        </a>
                        <a href="{{ route('super-admin.flights.airports.toggle', $airport->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg {{ $airport->status ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} text-xs font-bold transition-colors">
                            <i class="fa-solid {{ $airport->status ? 'fa-ban' : 'fa-check' }} text-[11px]"></i>
                            <span>{{ $airport->status ? 'Disable' : 'Enable' }}</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">No airports found in catalog.</div>
            @endforelse
        </div>

        <!-- Desktop Table View (shown on md+ screens) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-extrabold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">IATA / ICAO</th>
                        <th class="px-6 py-4">Airport Name</th>
                        <th class="px-6 py-4">City / Country</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($airports as $airport)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="font-mono font-black text-slate-900 bg-sky-50 text-sky-700 px-2.5 py-1 rounded-lg border border-sky-200 text-xs">{{ $airport->iata_code }}</span>
                                    @if($airport->icao_code)
                                        <span class="font-mono text-xs text-slate-400">{{ $airport->icao_code }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $airport->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs">
                                <span class="font-bold text-slate-800">{{ $airport->city }}</span>, {{ $airport->country }}
                            </td>
                            <td class="px-6 py-4">
                                @if($airport->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('super-admin.flights.refresh', $airport->iata_code) }}" title="Force refresh live cache" class="inline-flex items-center justify-center p-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                </a>
                                <a href="{{ route('super-admin.flights.airports.toggle', $airport->id) }}" class="inline-flex items-center justify-center p-2 rounded-lg {{ $airport->status ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} transition-colors">
                                    <i class="fa-solid {{ $airport->status ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">No airports found in catalog.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($airports->hasPages())
            <div class="p-4 sm:p-6 border-t border-slate-100">
                {{ $airports->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Airport Modal -->
<div id="addAirportModal" onclick="handleModalBackdropClick(event)" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-8 shadow-2xl border border-slate-100 relative my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Add Airport to Master Catalog</h3>
                <p class="text-xs text-slate-500 mt-1">Enter new commercial airport details for hotel TV assignment.</p>
            </div>
            <button type="button" onclick="closeAddAirportModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
        
        <form action="{{ route('super-admin.flights.airports.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Airport Name *</label>
                <input type="text" name="name" required placeholder="e.g. Pune International Airport" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">IATA Code (3 letters) *</label>
                    <input type="text" name="iata_code" maxlength="3" required placeholder="PNQ" class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ICAO Code (Optional)</label>
                    <input type="text" name="icao_code" maxlength="4" placeholder="VAPO" class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City *</label>
                    <input type="text" name="city" required placeholder="Pune" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                    <input type="text" name="country" required value="India" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddAirportModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 text-center">Cancel</button>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/20 text-center">Add Airport</button>
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
            badgeClass: 'bg-emerald-100/80 text-emerald-800 border-emerald-200/60',
            subtitle: 'Airport schedules & live arrivals/departures',
            icon: 'fa-solid fa-cloud-arrow-down',
            iconBg: 'bg-emerald-50 text-emerald-600 border-emerald-100/80'
        },
        aerodatabox: {
            title: 'AeroDataBox',
            badge: 'RapidAPI',
            badgeClass: 'bg-sky-100/80 text-sky-800 border-sky-200/60',
            subtitle: 'Commercial flight radar & global FIDS displays',
            icon: 'fa-solid fa-plane-departure',
            iconBg: 'bg-sky-50 text-sky-600 border-sky-100/80'
        },
        aviationstack: {
            title: 'AviationStack',
            badge: 'REST API',
            badgeClass: 'bg-violet-100/80 text-violet-800 border-violet-200/60',
            subtitle: 'Global aviation routes & departure timetables',
            icon: 'fa-solid fa-tower-broadcast',
            iconBg: 'bg-violet-50 text-violet-600 border-violet-100/80'
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
            trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
        } else {
            menu.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
            trigger.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20');
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
            opt.classList.remove('bg-rose-50/80', 'border-rose-200', 'shadow-2xs');
            opt.classList.add('border-transparent');
            const check = opt.querySelector('.check-icon');
            if (check) check.classList.add('hidden');
        });

        const activeOpt = document.getElementById('opt_' + providerKey);
        if (activeOpt) {
            activeOpt.classList.remove('border-transparent');
            activeOpt.classList.add('bg-rose-50/80', 'border-rose-200', 'shadow-2xs');
            const check = activeOpt.querySelector('.check-icon');
            if (check) check.classList.remove('hidden');
        }

        // Close menu
        const menu = document.getElementById('providerDropdownMenu');
        const chevron = document.getElementById('providerChevron');
        const trigger = document.getElementById('providerDropdownTrigger');
        menu.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
        trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
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
                if (trigger) trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
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
                if (trigger) trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20');
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
