@extends('layouts.super_admin')

@section('title', 'Connected Devices - Super Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6">

    <!-- Top Segmented TV Type Tabs & Statistics -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Filter Tabs: All, Live, Demo -->
        <div class="inline-flex p-1.5 bg-slate-100 rounded-2xl border border-slate-200/80 overflow-x-auto">
            @php
                $queryParams = array_filter(['hotel_id' => request('hotel_id'), 'search' => request('search')]);
            @endphp
            <a href="{{ route('super-admin.devices.index', array_merge($queryParams, ['type' => 'all'])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center space-x-2 {{ $type === 'all' ? 'bg-[#0F2537] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-tv text-xs"></i>
                <span>All Screens</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type === 'all' ? 'bg-[#D4AF37] text-[#0F2537]' : 'bg-slate-200 text-slate-700' }}">{{ $totalCount }}</span>
            </a>

            <a href="{{ route('super-admin.devices.index', array_merge($queryParams, ['type' => 'live'])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center space-x-2 {{ $type === 'live' ? 'bg-[#0F2537] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-hotel text-xs"></i>
                <span>Live Hotel TVs</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type === 'live' ? 'bg-[#D4AF37] text-[#0F2537]' : 'bg-slate-200 text-slate-700' }}">{{ $liveCount }}</span>
            </a>

            <a href="{{ route('super-admin.devices.index', array_merge($queryParams, ['type' => 'demo'])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center space-x-2 {{ in_array($type, ['demo', 'active_demo', 'expired_demo']) ? 'bg-[#D4AF37] text-[#0F2537] shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-flask text-xs"></i>
                <span>Demo TVs</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ in_array($type, ['demo', 'active_demo', 'expired_demo']) ? 'bg-[#0F2537] text-white' : 'bg-amber-100 text-amber-800' }}">{{ $demoCount }}</span>
            </a>
        </div>

        <!-- Quick Sub-status Pills for Demo Filter -->
        <div class="flex items-center gap-2 text-xs">
            @if(in_array($type, ['demo', 'active_demo', 'expired_demo']))
                <a href="{{ route('super-admin.devices.index', array_merge($queryParams, ['type' => 'active_demo'])) }}"
                   class="px-3 py-1.5 rounded-xl border text-[11px] font-bold transition-all flex items-center space-x-1.5 {{ $type === 'active_demo' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-white border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Active Trials ({{ $demoActiveCount }})</span>
                </a>
                <a href="{{ route('super-admin.devices.index', array_merge($queryParams, ['type' => 'expired_demo'])) }}"
                   class="px-3 py-1.5 rounded-xl border text-[11px] font-bold transition-all flex items-center space-x-1.5 {{ $type === 'expired_demo' ? 'bg-slate-700 text-white border-slate-700 shadow-xs' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                    <span>Expired / Disconnected ({{ $demoExpiredCount }})</span>
                </a>
            @else
                <div class="px-4 py-2 rounded-2xl bg-[#0F2537]/5 border border-[#0F2537]/10 flex items-center space-x-3 text-xs">
                    <span class="text-slate-500 font-semibold">Demo Pool:</span>
                    <span class="inline-flex items-center text-emerald-700 font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> {{ $demoActiveCount }} Active</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500 font-bold">{{ $demoExpiredCount }} Expired</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Filter & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-xs">
        <form method="GET" action="{{ route('super-admin.devices.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Filter & Search Controls -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Hotel Filter Dropdown -->
                <div class="w-full sm:w-64 shrink-0">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter by Hotel</label>
                    <div class="relative">
                        @php
                            $hotelOptions = ['' => 'All Hotels (' . $hotels->count() . ')'] + $hotels->pluck('hotel_name', 'id')->toArray();
                        @endphp
                        <x-dropdown name="hotel_id" label="All Hotels" :options="$hotelOptions" :selected="request('hotel_id')" theme="sa" />
                    </div>
                </div>

                <!-- Search Input -->
                <div class="flex-1">
                    <label for="searchInput" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Devices</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search room no, device ID, MAC, IP, brand, demo status..." class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/30 focus:border-[#D4AF37] transition-all placeholder:text-slate-400">
                        @if(request('search'))
                            <a href="{{ route('super-admin.devices.index', array_filter(['hotel_id' => request('hotel_id'), 'type' => $type])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="self-end sm:self-auto sm:pt-5">
                    <button type="submit" class="btn-sa-primary w-full sm:w-auto px-5 py-2.5 flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Apply</span>
                    </button>
                </div>
            </div>

            <!-- Stats / Counter Indicator -->
            <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                <div class="px-4 py-2.5 rounded-2xl bg-[#F9F5EB] border border-[#D4AF37]/30 text-[#0F2537] flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-[#0F2537] text-[#D4AF37] flex items-center justify-center text-sm font-bold shadow-xs">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Filtered TV Count</div>
                        <div class="text-sm font-extrabold text-[#0F2537]">
                            {{ $devices->total() }} <span class="text-xs font-semibold text-slate-500">screens</span>
                            @if(isset($selectedHotel))
                                <span class="text-xs font-bold text-[#D4AF37]">/ {{ $selectedHotel->allowed_device_limit }} cap</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/90 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-3.5 py-3.5 w-10 text-center">#</th>
                        <th class="px-3.5 py-3.5">Property / Client</th>
                        <th class="px-3.5 py-3.5">Type & Room</th>
                        <th class="px-3.5 py-3.5">Hardware & OS</th>
                        <th class="px-3.5 py-3.5">Device & Network</th>
                        <th class="px-3.5 py-3.5">Trial & Expiry</th>
                        <th class="px-3.5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $index => $device)
                        @php
                            $isDemo = (bool)$device->is_demo;
                            $isExpiredDemo = $isDemo && ($device->demo_status === 'expired' || ($device->demo_expires_at && now()->greaterThan($device->demo_expires_at)));
                            $daysLeft = ($isDemo && $device->demo_expires_at) ? max(0, (int)ceil(now()->diffInHours($device->demo_expires_at, false) / 24)) : null;

                            $devicePayload = [
                                'id' => $device->id,
                                'room_no' => $device->room_no,
                                'is_demo' => $isDemo,
                                'is_expired' => $isExpiredDemo,
                                'demo_status' => $device->demo_status ?? 'active',
                                'demo_expires_at' => $device->demo_expires_at ? $device->demo_expires_at->format('d M, Y - h:i A') : 'N/A',
                                'days_left' => $daysLeft,
                                'hotel_name' => $device->hotelAdmin->hotel_name ?? 'N/A',
                                'owner_name' => $device->hotelAdmin->owner_name ?? 'N/A',
                                'license_key' => $device->hotelAdmin->license_key ?? 'N/A',
                                'plan_name' => $device->hotelAdmin->plan->name ?? ($isDemo ? '7-Day Demo Trial' : 'Standard Plan'),
                                'expiry_date' => $isDemo 
                                    ? ($device->demo_expires_at ? $device->demo_expires_at->format('d M, Y - h:i A') : '7 Days Trial')
                                    : ($device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M, Y') : 'Active License'),
                                'distributor_name' => $device->hotelAdmin->distributor->name ?? 'Direct (Admin)',
                                'device_id' => $device->device_id,
                                'mac_address' => $device->mac_address ?? 'N/A',
                                'ip_address' => $device->ip_address ?? 'N/A',
                                'brand' => $device->brand ?? '',
                                'model' => $device->model ?? '',
                                'os_version' => $device->os_version ?? '',
                                'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                                'disconnect_url' => route('super-admin.devices.destroy', $device->id),
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors {{ $isExpiredDemo ? 'bg-slate-50/50 opacity-85' : '' }}">
                            <td class="px-3.5 py-3.5 text-center font-bold text-slate-400">
                                {{ $devices->firstItem() + $index }}
                            </td>
                            <td class="px-3.5 py-3.5">
                                <div class="font-extrabold text-slate-900 leading-snug">
                                    {{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium truncate max-w-[170px]">
                                    {{ $device->hotelAdmin->owner_name ?? '' }}
                                </div>
                            </td>
                            <td class="px-3.5 py-3.5 space-y-1">
                                <!-- TV Type Badge -->
                                <div>
                                    @if($isDemo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 font-extrabold text-[10px] tracking-wide">
                                            <i class="fa-solid fa-flask mr-1 text-amber-600"></i> Demo TV
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]">
                                            <i class="fa-solid fa-hotel mr-1 text-slate-500"></i> Hotel TV
                                        </span>
                                    @endif
                                </div>
                                <!-- Room No Badge -->
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-200/80 text-indigo-700 font-extrabold text-[11px]">
                                        <i class="fa-solid fa-door-closed mr-1 text-indigo-500 text-[10px]"></i>
                                        Room {{ $device->room_no }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-3.5 py-3.5">
                                @if($device->brand || $device->model)
                                    <div class="font-bold text-slate-800 capitalize flex items-center space-x-1.5 leading-snug">
                                        <i class="fa-solid fa-tv text-slate-400 text-[10px]"></i>
                                        <span>{{ $device->brand }} {{ $device->model }}</span>
                                    </div>
                                    @if($device->os_version)
                                        <span class="text-[10px] text-slate-500 font-semibold bg-slate-100 px-1.5 py-0.5 rounded inline-block mt-0.5">Android {{ $device->os_version }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Smart TV Device</span>
                                @endif
                            </td>
                            <td class="px-3.5 py-3.5">
                                <div class="font-mono text-[11px] font-bold text-slate-700 truncate max-w-[150px]" title="{{ $device->device_id }}">
                                    {{ Str::limit($device->device_id, 16) }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 flex items-center gap-2 mt-0.5">
                                    @if($device->ip_address)<span>IP: {{ $device->ip_address }}</span>@endif
                                    @if($device->mac_address)<span>MAC: {{ $device->mac_address }}</span>@endif
                                </div>
                            </td>
                            <!-- Expiry Status Column with Connected Timestamp -->
                            <td class="px-3.5 py-3.5 whitespace-nowrap">
                                @if($isDemo)
                                    @if($isExpiredDemo)
                                        <div class="space-y-0.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-extrabold">
                                                <i class="fa-solid fa-circle-xmark mr-1 text-rose-500"></i> Expired / Inactive
                                            </span>
                                            <div class="text-[10px] text-slate-400 font-medium">
                                                {{ $device->demo_expires_at ? $device->demo_expires_at->format('d M Y, h:i A') : 'Expired' }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="space-y-0.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-extrabold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>
                                                Expires in {{ $daysLeft }}d
                                            </span>
                                            <div class="text-[10px] text-slate-500 font-medium">
                                                {{ $device->demo_expires_at ? $device->demo_expires_at->format('d M Y, h:i A') : '7 Days Trial' }}
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold">
                                            <i class="fa-solid fa-shield-check mr-1 text-emerald-600"></i> Licensed
                                        </span>
                                        <div class="text-[10px] text-slate-500 font-medium">
                                            {{ $device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M Y') : 'Active License' }}
                                        </div>
                                    </div>
                                @endif
                                <div class="text-[9px] text-slate-400 mt-1">
                                    <i class="fa-regular fa-clock text-[8px] mr-0.5"></i> Connected: {{ $device->created_at ? $device->created_at->format('d M Y') : 'N/A' }}
                                </div>
                            </td>
                            <!-- Unified Actions Column: View + Disconnect side by side -->
                            <td class="px-3.5 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end space-x-1.5">
                                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold text-xs transition-all shadow-2xs cursor-pointer">
                                        <i class="fa-solid fa-eye text-[#0F2537] text-[11px]"></i>
                                        <span>View</span>
                                    </button>

                                    @if($isDemo && $isExpiredDemo)
                                        <!-- Disconnected / Expired Demo TV: Gray Faded Disabled State -->
                                        <button type="button" disabled class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed font-bold text-xs" title="Demo TV is disconnected / expired">
                                            <i class="fa-solid fa-power-off text-[10px]"></i>
                                            <span>Disconnected</span>
                                        </button>
                                    @else
                                        <!-- Active Device (Demo or Live Hotel TV) -->
                                        <button type="button" onclick="confirmDisconnect('{{ route('super-admin.devices.destroy', $device->id) }}', '{{ $isDemo ? 'Demo TV' : 'Hotel TV' }} - Room {{ $device->room_no }} ({{ addslashes($device->hotelAdmin->hotel_name ?? 'Hotel') }})', {{ $isDemo ? 'true' : 'false' }})" class="btn-danger inline-flex items-center space-x-1 px-2.5 py-1.5 font-bold text-xs cursor-pointer">
                                            <i class="fa-solid fa-power-off text-[10px]"></i>
                                            <span>Disconnect</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 mx-auto rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No TV Screens Found</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">No connected TV screens match current filters or search criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3.5">
        @forelse($devices as $index => $device)
            @php
                $isDemo = (bool)$device->is_demo;
                $isExpiredDemo = $isDemo && ($device->demo_status === 'expired' || ($device->demo_expires_at && now()->greaterThan($device->demo_expires_at)));
                $daysLeft = ($isDemo && $device->demo_expires_at) ? max(0, (int)ceil(now()->diffInHours($device->demo_expires_at, false) / 24)) : null;

                $devicePayload = [
                    'id' => $device->id,
                    'room_no' => $device->room_no,
                    'is_demo' => $isDemo,
                    'is_expired' => $isExpiredDemo,
                    'demo_status' => $device->demo_status ?? 'active',
                    'demo_expires_at' => $device->demo_expires_at ? $device->demo_expires_at->format('d M, Y - h:i A') : 'N/A',
                    'days_left' => $daysLeft,
                    'hotel_name' => $device->hotelAdmin->hotel_name ?? 'N/A',
                    'owner_name' => $device->hotelAdmin->owner_name ?? 'N/A',
                    'license_key' => $device->hotelAdmin->license_key ?? 'N/A',
                    'plan_name' => $device->hotelAdmin->plan->name ?? ($isDemo ? '7-Day Demo Trial' : 'Standard Plan'),
                    'expiry_date' => $isDemo 
                        ? ($device->demo_expires_at ? $device->demo_expires_at->format('d M, Y - h:i A') : '7 Days Trial')
                        : ($device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M, Y') : 'Active License'),
                    'distributor_name' => $device->hotelAdmin->distributor->name ?? 'Direct (Admin)',
                    'device_id' => $device->device_id,
                    'mac_address' => $device->mac_address ?? 'N/A',
                    'ip_address' => $device->ip_address ?? 'N/A',
                    'brand' => $device->brand ?? '',
                    'model' => $device->model ?? '',
                    'os_version' => $device->os_version ?? '',
                    'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                    'disconnect_url' => route('super-admin.devices.destroy', $device->id),
                ];
            @endphp
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3 {{ $isExpiredDemo ? 'opacity-80 bg-slate-50/60' : '' }}">
                <!-- Top Row: Hotel + Type Badge -->
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="min-w-0">
                        <div class="flex items-center space-x-2 mb-1">
                            @if($isDemo)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 font-extrabold text-[10px]">
                                    <i class="fa-solid fa-flask mr-1 text-amber-600"></i> Demo TV
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]">
                                    <i class="fa-solid fa-hotel mr-1 text-slate-500"></i> Hotel TV
                                </span>
                            @endif

                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-200/80 text-indigo-700 font-extrabold text-[11px]">
                                Room {{ $device->room_no }}
                            </span>
                        </div>
                        <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}</h4>
                    </div>

                    @if($isDemo)
                        @if($isExpiredDemo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200 shrink-0">
                                Expired
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active Trial
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Connected
                        </span>
                    @endif
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="bg-slate-50/80 rounded-xl p-2 border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Hardware</div>
                        <div class="font-bold text-slate-800 truncate mt-0.5">
                            {{ $device->brand || $device->model ? trim($device->brand . ' ' . $device->model) : 'Smart TV' }}
                        </div>
                        @if($device->os_version)
                            <div class="text-[9px] text-slate-500 font-semibold">Android {{ $device->os_version }}</div>
                        @endif
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-2 border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Trial / Expiry</div>
                        @if($isDemo)
                            <div class="font-bold {{ $isExpiredDemo ? 'text-rose-600' : 'text-emerald-700' }} truncate mt-0.5">
                                {{ $isExpiredDemo ? 'Expired' : ($daysLeft . ' days left') }}
                            </div>
                            <div class="text-[9px] text-slate-400 truncate">{{ $device->demo_expires_at ? $device->demo_expires_at->format('d M Y') : 'N/A' }}</div>
                        @else
                            <div class="font-bold text-slate-800 truncate mt-0.5">
                                {{ $device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M Y') : 'Active' }}
                            </div>
                            <div class="text-[9px] text-slate-400">Licensed Plan</div>
                        @endif
                    </div>
                </div>

                <div class="text-[10px] font-mono text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100 truncate">
                    <span class="font-bold text-slate-400">ID:</span> {{ $device->device_id }}
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="btn-secondary w-full py-2 px-3 text-xs flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-eye text-[#0F2537]"></i>
                        <span>Details</span>
                    </button>
                    
                    @if($isDemo && $isExpiredDemo)
                        <button type="button" disabled class="w-full py-2 px-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed font-bold text-xs flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-power-off text-xs"></i>
                            <span>Disconnected</span>
                        </button>
                    @else
                        <button type="button" onclick="confirmDisconnect('{{ route('super-admin.devices.destroy', $device->id) }}', '{{ $isDemo ? 'Demo TV' : 'Hotel TV' }} - Room {{ $device->room_no }}', {{ $isDemo ? 'true' : 'false' }})" class="btn-danger w-full py-2 px-3 text-xs flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-power-off text-xs"></i>
                            <span>Disconnect</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-2">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <h4 class="text-xs font-extrabold text-slate-800">No TV Screens Found</h4>
                <p class="text-[11px] text-slate-400 mt-1">No connected TV screens match current filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $devices->links() }}
    </div>
</div>

<!-- Reusable Device & License Details Modal -->
<div id="deviceDetailsModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-4 overflow-hidden" onclick="closeDeviceModalOnBackdrop(event)">
    <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full shadow-2xl animate-in fade-in zoom-in duration-150 flex flex-col max-h-[84vh] sm:max-h-[86vh] min-h-0 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-4 py-3.5 sm:px-6 sm:py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center space-x-3 min-w-0 pr-2">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-[#0F2537] text-[#D4AF37] flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-sm sm:text-base font-extrabold text-slate-900 leading-tight truncate">
                            Device & Screen Details
                        </h3>
                        <span id="modalTypeBadge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-extrabold"></span>
                    </div>
                    <p id="modalSubtitle" class="text-[11px] sm:text-xs text-slate-500 font-semibold truncate mt-0.5">Room 101 • Hotel Details</p>
                </div>
            </div>
            <!-- Top Actions -->
            <div class="flex items-center space-x-1.5 shrink-0">
                <button type="button" onclick="closeDeviceDetailsModal()" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors" title="Close">
                    <i class="fa-solid fa-xmark text-base sm:text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Modal Content -->
        <div class="px-4 py-4 sm:px-6 sm:py-5 overflow-y-auto overscroll-contain flex-1 min-h-0 space-y-3.5 sm:space-y-4" style="-webkit-overflow-scrolling: touch;">
            
            <!-- Expiry & Demo Info Banner -->
            <div id="modalExpiryBanner" class="p-3.5 sm:p-4 rounded-2xl border space-y-1.5 bg-slate-50 border-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span id="modalExpiryLabel">Trial / License Validity</span>
                    </span>
                    <span id="modalExpiryBadge" class="px-2 py-0.5 rounded-md text-[10px] font-bold">---</span>
                </div>
                <div id="modalExpiryTimestamp" class="text-xs font-bold text-slate-900">---</div>
                <p id="modalExpiryNote" class="text-[11px] text-slate-500 leading-tight">---</p>
            </div>

            <!-- License Key Highlight Box -->
            <div class="p-3.5 sm:p-5 rounded-2xl bg-gradient-to-br from-slate-50 via-slate-100/60 to-slate-50 border border-slate-200 space-y-2.5 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#0F2537] flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-[10px] text-[#D4AF37]"></i>
                        <span>License Key / Master Credential</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md bg-[#D4AF37]/20 text-[#0F2537] text-[10px] font-bold">Authorized</span>
                </div>
                
                <div class="flex items-center justify-between gap-2 bg-white rounded-xl p-2.5 border border-slate-200 shadow-2xs">
                    <span id="modalLicenseKey" class="font-mono font-black text-xs sm:text-base text-slate-900 tracking-wide select-all truncate">
                        ---
                    </span>
                    <button type="button" onclick="copyLicenseKey()" id="copyKeyBtn" class="shrink-0 btn-sa-primary px-3 py-1.5 text-[11px] flex items-center space-x-1">
                        <i class="fa-solid fa-copy text-[11px]" id="copyKeyIcon"></i>
                        <span id="copyKeyText">Copy</span>
                    </button>
                </div>

                <!-- Assigned Property Details -->
                <div class="bg-white rounded-xl p-3 border border-slate-200 text-xs space-y-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-[11px] text-slate-500 font-semibold flex items-center">
                            <i class="fa-solid fa-hotel text-slate-400 mr-1.5 text-[11px]"></i> Assigned Hotel:
                        </span>
                        <span id="modalKeyHotelName" class="font-bold text-slate-900 truncate max-w-[200px] sm:max-w-[240px]">---</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>
                            <span class="text-slate-400 font-medium">Owner:</span>
                            <span id="modalKeyOwner" class="font-bold text-slate-800 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Plan:</span>
                            <span id="modalKeyPlan" class="font-bold text-[#0F2537] ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Expires:</span>
                            <span id="modalKeyExpiry" class="font-bold text-slate-700 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Distributor:</span>
                            <span id="modalKeyDistributor" class="font-bold text-slate-700 ml-1 truncate">---</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Technical Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hotel Name</div>
                    <div id="modalHotelName" class="font-extrabold text-slate-900 truncate">---</div>
                    <div id="modalOwnerName" class="text-[10px] text-slate-500 font-medium truncate">---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Room Number</div>
                    <div id="modalRoomNo" class="font-extrabold text-[#0F2537]">---</div>
                    <div id="modalOnlineStatus" class="text-[10px] font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Status: Online</span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Device Unique ID</div>
                    <div class="flex items-center justify-between gap-2">
                        <div id="modalDeviceId" class="font-mono font-bold text-slate-800 text-[11px] truncate select-all">---</div>
                        <button type="button" onclick="copyToClipboard(document.getElementById('modalDeviceId').innerText, this)" class="text-slate-400 hover:text-slate-700 text-xs shrink-0 p-1" title="Copy Device ID">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hardware / Model</div>
                    <div id="modalHardware" class="font-bold text-slate-800 truncate">---</div>
                    <div id="modalOsVersion" class="text-[10px] text-slate-500 font-semibold">---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Network Info</div>
                    <div id="modalIpAddress" class="font-mono text-slate-700 font-semibold text-[11px]">IP: ---</div>
                    <div id="modalMacAddress" class="font-mono text-[10px] text-slate-400">MAC: ---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Connection Timestamp</div>
                    <div id="modalConnectedAt" class="font-semibold text-slate-700 text-[11px]">---</div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 border-t border-slate-100 flex items-center justify-between space-x-2.5 shrink-0 bg-slate-50/80">
            <button type="button" id="modalDisconnectBtn" onclick="disconnectFromModal()" class="btn-danger px-4 py-2.5 text-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span id="modalDisconnectBtnText">Disconnect TV</span>
            </button>
            <button type="button" onclick="closeDeviceDetailsModal()" class="btn-secondary px-5 py-2.5 text-xs font-bold">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Hidden Disconnect Form for Execution -->
<form id="disconnectDeviceForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    let currentModalDevice = null;

    function showDeviceDetails(device) {
        currentModalDevice = device;
        document.getElementById('modalSubtitle').innerText = 'Room ' + device.room_no + ' • ' + device.hotel_name;
        document.getElementById('modalLicenseKey').innerText = device.license_key || 'N/A';
        
        // Ownership details for key
        document.getElementById('modalKeyHotelName').innerText = device.hotel_name || 'N/A';
        document.getElementById('modalKeyOwner').innerText = device.owner_name || 'N/A';
        document.getElementById('modalKeyPlan').innerText = device.plan_name || 'Standard Plan';
        document.getElementById('modalKeyExpiry').innerText = device.expiry_date || 'Active';
        document.getElementById('modalKeyDistributor').innerText = device.distributor_name || 'Direct';

        document.getElementById('modalHotelName').innerText = device.hotel_name || 'N/A';
        document.getElementById('modalOwnerName').innerText = device.owner_name ? 'Owner: ' + device.owner_name : '';
        document.getElementById('modalRoomNo').innerText = 'Room ' + device.room_no;
        document.getElementById('modalDeviceId').innerText = device.device_id || 'N/A';
        
        const hw = (device.brand || '') + ' ' + (device.model || '');
        document.getElementById('modalHardware').innerText = hw.trim() ? hw.trim() : 'Generic Smart TV';
        document.getElementById('modalOsVersion').innerText = device.os_version ? 'Android ' + device.os_version : 'Android OS';
        
        document.getElementById('modalIpAddress').innerText = 'IP: ' + (device.ip_address || 'N/A');
        document.getElementById('modalMacAddress').innerText = 'MAC: ' + (device.mac_address || 'N/A');
        document.getElementById('modalConnectedAt').innerText = device.connected_at || 'N/A';

        // Type Badge & Expiry Banner configuration
        const typeBadge = document.getElementById('modalTypeBadge');
        const expiryBanner = document.getElementById('modalExpiryBanner');
        const expiryLabel = document.getElementById('modalExpiryLabel');
        const expiryBadge = document.getElementById('modalExpiryBadge');
        const expiryTimestamp = document.getElementById('modalExpiryTimestamp');
        const expiryNote = document.getElementById('modalExpiryNote');
        const modalDisconnectBtn = document.getElementById('modalDisconnectBtn');
        const modalDisconnectBtnText = document.getElementById('modalDisconnectBtnText');

        typeBadge.classList.remove('hidden');
        if (device.is_demo) {
            typeBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 border border-amber-200 text-amber-800';
            typeBadge.innerHTML = '<i class="fa-solid fa-flask mr-1 text-amber-600"></i> Demo TV';

            expiryLabel.innerText = 'Demo Trial Validity';
            expiryTimestamp.innerText = device.demo_expires_at ? device.demo_expires_at : '7 Days Trial';

            if (device.is_expired) {
                expiryBanner.className = 'p-3.5 sm:p-4 rounded-2xl border space-y-1.5 bg-rose-50/70 border-rose-200';
                expiryBadge.className = 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-200 text-rose-800';
                expiryBadge.innerText = 'Expired / Disconnected';
                expiryNote.innerText = 'This Demo TV trial has ended or was disconnected. Record is retained in demo archive.';

                // Disabled gray disconnect button
                modalDisconnectBtn.disabled = true;
                modalDisconnectBtn.className = 'px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed font-bold text-xs flex items-center space-x-1.5';
                modalDisconnectBtnText.innerText = 'Already Disconnected';
            } else {
                expiryBanner.className = 'p-3.5 sm:p-4 rounded-2xl border space-y-1.5 bg-emerald-50/70 border-emerald-200';
                expiryBadge.className = 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-200 text-emerald-800';
                expiryBadge.innerText = device.days_left + ' Days Remaining';
                expiryNote.innerText = 'Active 7-day demo trial period. TV has full preview capabilities.';

                modalDisconnectBtn.disabled = false;
                modalDisconnectBtn.className = 'btn-danger px-4 py-2.5 text-xs flex items-center space-x-1.5';
                modalDisconnectBtnText.innerText = 'Disconnect & Expire Demo';
            }
        } else {
            typeBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 border border-slate-200 text-slate-700';
            typeBadge.innerHTML = '<i class="fa-solid fa-hotel mr-1 text-slate-500"></i> Hotel TV';

            expiryBanner.className = 'p-3.5 sm:p-4 rounded-2xl border space-y-1.5 bg-slate-50 border-slate-200';
            expiryLabel.innerText = 'Subscription License';
            expiryBadge.className = 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-200 text-slate-800';
            expiryBadge.innerText = 'Licensed Hotel Screen';
            expiryTimestamp.innerText = 'Valid until: ' + (device.expiry_date || 'Active');
            expiryNote.innerText = 'Authorized room TV screen bound to active property subscription.';

            modalDisconnectBtn.disabled = false;
            modalDisconnectBtn.className = 'btn-danger px-4 py-2.5 text-xs flex items-center space-x-1.5';
            modalDisconnectBtnText.innerText = 'Disconnect TV';
        }

        // Reset copy button state
        document.getElementById('copyKeyIcon').className = 'fa-solid fa-copy text-[11px]';
        document.getElementById('copyKeyText').innerText = 'Copy';

        // Lock background scroll and open modal
        document.body.style.overflow = 'hidden';
        document.body.classList.add('overflow-hidden');
        document.getElementById('deviceDetailsModal').classList.remove('hidden');
    }

    function closeDeviceDetailsModal() {
        document.getElementById('deviceDetailsModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.body.classList.remove('overflow-hidden');
    }

    function closeDeviceModalOnBackdrop(event) {
        if (event.target.id === 'deviceDetailsModal') {
            closeDeviceDetailsModal();
        }
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeviceDetailsModal();
        }
    });

    function copyLicenseKey() {
        const key = document.getElementById('modalLicenseKey').innerText;
        if (!key || key === 'N/A' || key === '---') return;

        navigator.clipboard.writeText(key).then(() => {
            const icon = document.getElementById('copyKeyIcon');
            const text = document.getElementById('copyKeyText');
            icon.className = 'fa-solid fa-check text-[11px]';
            text.innerText = 'Copied!';

            setTimeout(() => {
                icon.className = 'fa-solid fa-copy text-[11px]';
                text.innerText = 'Copy';
            }, 2000);
        });
    }

    function copyToClipboard(text, btnElement) {
        if (!text || text === 'N/A' || text === '---') return;
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fa-solid fa-check text-emerald-500"></i>';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
            }, 1800);
        });
    }

    function disconnectFromModal() {
        if (!currentModalDevice || !currentModalDevice.disconnect_url) return;
        const deviceLabel = (currentModalDevice.is_demo ? 'Demo TV' : 'Hotel TV') + ' Room ' + currentModalDevice.room_no + ' (' + currentModalDevice.hotel_name + ')';
        confirmDisconnect(currentModalDevice.disconnect_url, deviceLabel, currentModalDevice.is_demo);
    }

    function confirmDisconnect(actionUrl, deviceLabel, isDemo) {
        const title = isDemo ? 'Disconnect Demo TV?' : 'Disconnect Device?';
        const text = isDemo 
            ? 'Are you sure you want to disconnect ' + deviceLabel + '? This will expire the demo trial immediately without deleting the record from your TV archive, and its disconnect option will be disabled.'
            : 'Are you sure you want to disconnect ' + deviceLabel + '? The room pairing will be cleared and the TV can be re-paired with a fresh code.';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#0F2537',
                confirmButtonText: isDemo ? 'Yes, Expire & Disconnect' : 'Yes, Disconnect',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5',
                    cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('disconnectDeviceForm');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                const form = document.getElementById('disconnectDeviceForm');
                form.action = actionUrl;
                form.submit();
            }
        }
    }
</script>
@endsection
