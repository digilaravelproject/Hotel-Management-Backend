@extends('layouts.super_admin')

@section('page_title', 'Flight API & Airports Management')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Airports</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalAirports }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Provider</p>
                <h3 class="text-xl font-black text-slate-800 uppercase">{{ $setting->provider ?? 'AirLabs' }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cache TTL</p>
                <h3 class="text-xl font-black text-slate-800">{{ $setting->cache_ttl_minutes ?? 30 }} Mins</h3>
            </div>
        </div>
    </div>

    <!-- API Settings Configuration Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-key text-rose-600"></i>
                    <span>Flight API Configuration</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Configure your 3rd-party commercial flight tracking API credentials.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ ($setting->is_active ?? true) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                <span class="w-2 h-2 rounded-full mr-2 {{ ($setting->is_active ?? true) ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ ($setting->is_active ?? true) ? 'API Enabled' : 'Disabled (Mock Fallback Active)' }}
            </span>
        </div>

        <form action="{{ route('super-admin.flights.settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Flight API Provider</label>
                    <select name="provider" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                        <option value="airlabs" {{ ($setting->provider ?? '') === 'airlabs' ? 'selected' : '' }}>AirLabs API (Recommended - Airport Schedules)</option>
                        <option value="aerodatabox" {{ ($setting->provider ?? '') === 'aerodatabox' ? 'selected' : '' }}>AeroDataBox (RapidAPI)</option>
                        <option value="aviationstack" {{ ($setting->provider ?? '') === 'aviationstack' ? 'selected' : '' }}>AviationStack</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">API Access Key</label>
                    <input type="text" name="api_key" value="{{ $setting->api_key ?? '' }}" placeholder="Enter provider API key (Leave empty for Dynamic Mock)" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cache Duration (Minutes)</label>
                    <input type="number" name="cache_ttl_minutes" min="5" max="1440" value="{{ $setting->cache_ttl_minutes ?? 30 }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <label class="inline-flex items-center cursor-pointer space-x-3">
                    <input type="checkbox" name="is_active" value="1" {{ ($setting->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 h-4 w-4">
                    <span class="text-xs font-bold text-slate-700">Enable Live Flight API Fetching</span>
                </label>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm transition-all shadow-md shadow-rose-600/20">
                    Save API Configuration
                </button>
            </div>
        </form>
    </div>

    <!-- Airports Master Catalog Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-plane-arrival text-sky-600"></i>
                    <span>Airports Master Catalog</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Airports available for hotels across all regions.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Add Airport Modal Trigger -->
                <button onclick="document.getElementById('addAirportModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add New Airport</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
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
            <div class="p-6 border-t border-slate-100">
                {{ $airports->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Airport Modal -->
<div id="addAirportModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-slate-100 relative">
        <h3 class="text-lg font-extrabold text-slate-900 mb-2">Add Airport to Master Catalog</h3>
        <p class="text-xs text-slate-500 mb-6">Enter new commercial airport details for hotel TV assignment.</p>
        
        <form action="{{ route('super-admin.flights.airports.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Airport Name *</label>
                <input type="text" name="name" required placeholder="e.g. Pune International Airport" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">IATA Code (3 letters) *</label>
                    <input type="text" name="iata_code" maxlength="3" required placeholder="PNQ" class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ICAO Code (Optional)</label>
                    <input type="text" name="icao_code" maxlength="4" placeholder="VAPO" class="w-full uppercase font-mono rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City *</label>
                    <input type="text" name="city" required placeholder="Pune" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                    <input type="text" name="country" required value="India" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addAirportModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/20">Add Airport</button>
            </div>
        </form>
    </div>
</div>
@endsection
