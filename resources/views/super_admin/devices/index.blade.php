@extends('layouts.super_admin')

@section('title', 'Connected Devices - Super Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6">
    <!-- Top Filter & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-xs">
        <form method="GET" action="{{ route('super-admin.devices.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <!-- Filter & Search Controls -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Hotel Filter Dropdown -->
                <div class="w-full sm:w-64 shrink-0">
                    <label for="hotelFilter" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter by Hotel</label>
                    <div class="relative">
                        <select name="hotel_id" id="hotelFilter" onchange="this.form.submit()" class="w-full pl-3.5 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                            <option value="">All Hotels ({{ $hotels->count() }})</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->hotel_name }} ({{ $hotel->room_count }} R)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="flex-1">
                    <label for="searchInput" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Devices</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search room no, device ID, MAC, IP, brand..." class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder:text-slate-400">
                        @if(request('search'))
                            <a href="{{ route('super-admin.devices.index', array_filter(['hotel_id' => request('hotel_id')])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="self-end sm:self-auto sm:pt-5">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Apply</span>
                    </button>
                </div>
            </div>

            <!-- Stats / Counter Indicator -->
            <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                <div class="px-4 py-2.5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-rose-500 tracking-wider">Total Active TVs</div>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ $devices->total() }} <span class="text-xs font-semibold text-slate-500">screens</span>
                            @if(isset($selectedHotel))
                                <span class="text-xs font-bold text-rose-600">/ {{ $selectedHotel->allowed_device_limit }} cap</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop & Tablet Table View (hidden on small mobile screens) -->
    <div class="hidden md:block bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/90 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-4 w-12 text-center">#</th>
                        <th class="px-5 py-4">Hotel Client</th>
                        <th class="px-5 py-4">Room</th>
                        <th class="px-5 py-4">Hardware & OS</th>
                        <th class="px-5 py-4">Device & Network</th>
                        <th class="px-5 py-4">Connected At</th>
                        <th class="px-5 py-4 text-center">Details</th>
                        <th class="px-5 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $index => $device)
                        @php
                            $devicePayload = [
                                'id' => $device->id,
                                'room_no' => $device->room_no,
                                'hotel_name' => $device->hotelAdmin->hotel_name ?? 'N/A',
                                'owner_name' => $device->hotelAdmin->owner_name ?? 'N/A',
                                'license_key' => $device->hotelAdmin->license_key ?? 'N/A',
                                'plan_name' => $device->hotelAdmin->plan->name ?? 'Standard Plan',
                                'expiry_date' => $device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M, Y') : ($device->hotelAdmin->plan_expires_at ? \Carbon\Carbon::parse($device->hotelAdmin->plan_expires_at)->format('d M, Y') : 'Active'),
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
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 text-center font-bold text-slate-400">
                                {{ $devices->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-900">{{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}</div>
                                <div class="text-[11px] text-slate-400 font-medium truncate max-w-[180px]">{{ $device->hotelAdmin->owner_name ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-indigo-50 border border-indigo-200/80 text-indigo-700 font-extrabold text-xs">
                                    <i class="fa-solid fa-door-closed mr-1.5 text-indigo-500 text-[10px]"></i>
                                    Room {{ $device->room_no }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($device->brand || $device->model)
                                    <div class="font-bold text-slate-800 capitalize flex items-center space-x-1.5">
                                        <i class="fa-solid fa-tv text-slate-400 text-[11px]"></i>
                                        <span>{{ $device->brand }} {{ $device->model }}</span>
                                    </div>
                                    @if($device->os_version)
                                        <span class="text-[10px] text-slate-500 font-semibold bg-slate-100 px-1.5 py-0.5 rounded">Android {{ $device->os_version }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Smart TV Device</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-mono text-[11px] font-bold text-slate-700 truncate max-w-[160px]" title="{{ $device->device_id }}">
                                    {{ Str::limit($device->device_id, 16) }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 flex items-center gap-2 mt-0.5">
                                    @if($device->ip_address)<span>IP: {{ $device->ip_address }}</span>@endif
                                    @if($device->mac_address)<span>MAC: {{ $device->mac_address }}</span>@endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-500 font-medium whitespace-nowrap">
                                <div>{{ $device->created_at ? $device->created_at->format('d M, Y') : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $device->created_at ? $device->created_at->format('h:i A') : '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 hover:text-indigo-600 font-bold text-xs transition-all shadow-2xs">
                                    <i class="fa-solid fa-eye text-indigo-500"></i>
                                    <span>View</span>
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button type="button" onclick="confirmDisconnect('{{ route('super-admin.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }} ({{ addslashes($device->hotelAdmin->hotel_name ?? 'Hotel') }})')" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs transition-colors">
                                    <i class="fa-solid fa-power-off text-[11px]"></i>
                                    <span>Disconnect</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 mx-auto rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No Connected TV Screens Found</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">No TV devices match your current filter or search criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View (shown only on small devices) -->
    <div class="md:hidden space-y-3.5">
        @forelse($devices as $index => $device)
            @php
                $devicePayload = [
                    'id' => $device->id,
                    'room_no' => $device->room_no,
                    'hotel_name' => $device->hotelAdmin->hotel_name ?? 'N/A',
                    'owner_name' => $device->hotelAdmin->owner_name ?? 'N/A',
                    'license_key' => $device->hotelAdmin->license_key ?? 'N/A',
                    'plan_name' => $device->hotelAdmin->plan->name ?? 'Standard Plan',
                    'expiry_date' => $device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M, Y') : ($device->hotelAdmin->plan_expires_at ? \Carbon\Carbon::parse($device->hotelAdmin->plan_expires_at)->format('d M, Y') : 'Active'),
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
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3">
                <!-- Top Row: Hotel + Room Badge -->
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="min-w-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-200/80 text-indigo-700 font-extrabold text-[11px] mb-1">
                            <i class="fa-solid fa-door-closed mr-1 text-indigo-500 text-[10px]"></i>
                            Room {{ $device->room_no }}
                        </span>
                        <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}</h4>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Online
                    </span>
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
                        <div class="text-[10px] uppercase font-bold text-slate-400">Connected</div>
                        <div class="font-bold text-slate-800 truncate mt-0.5">
                            {{ $device->created_at ? $device->created_at->format('d M, Y') : 'N/A' }}
                        </div>
                        <div class="text-[9px] text-slate-400">{{ $device->created_at ? $device->created_at->format('h:i A') : '' }}</div>
                    </div>
                </div>

                <div class="text-[10px] font-mono text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100 truncate">
                    <span class="font-bold text-slate-400">ID:</span> {{ $device->device_id }}
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 hover:text-indigo-600 font-bold text-xs flex items-center justify-center space-x-1.5 transition-all">
                        <i class="fa-solid fa-eye text-indigo-500"></i>
                        <span>Details & Key</span>
                    </button>
                    
                    <button type="button" onclick="confirmDisconnect('{{ route('super-admin.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }}')" class="w-full py-2 px-3 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs flex items-center justify-center space-x-1.5 transition-colors">
                        <i class="fa-solid fa-power-off text-xs"></i>
                        <span>Disconnect</span>
                    </button>
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
<div id="deviceDetailsModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center hidden p-4 sm:p-6 overflow-y-auto" onclick="closeDeviceModalOnBackdrop(event)">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 max-w-lg w-full shadow-2xl animate-in fade-in zoom-in duration-150 my-auto flex flex-col max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight flex items-center space-x-2">
                        <span>Device & License Details</span>
                    </h3>
                    <p id="modalSubtitle" class="text-xs text-slate-500 font-semibold truncate mt-0.5">Room 101 • Hotel Details</p>
                </div>
            </div>
            <!-- Top Actions: Disconnect button & Close button -->
            <div class="flex items-center space-x-2 shrink-0">
                <button type="button" onclick="disconnectFromModal()" class="px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs" title="Disconnect this TV">
                    <i class="fa-solid fa-power-off text-xs"></i>
                    <span>Disconnect</span>
                </button>
                <button type="button" onclick="closeDeviceDetailsModal()" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Modal Content -->
        <div class="overflow-y-auto space-y-4 pr-1 py-1 -mr-1">
            <!-- License Key Highlight Box -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-indigo-50/80 via-violet-50/60 to-purple-50/40 border border-indigo-200/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-[10px]"></i>
                        <span>Hotel License Key</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md bg-indigo-600/10 text-indigo-700 text-[10px] font-bold">Authorized</span>
                </div>
                
                <div class="flex items-center justify-between gap-2 bg-white/90 rounded-xl p-2.5 border border-indigo-200/60 shadow-2xs">
                    <span id="modalLicenseKey" class="font-mono font-black text-sm sm:text-base text-indigo-900 tracking-wide select-all truncate">
                        ---
                    </span>
                    <button type="button" onclick="copyLicenseKey()" id="copyKeyBtn" class="shrink-0 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[11px] shadow-xs transition-all flex items-center space-x-1">
                        <i class="fa-solid fa-copy text-[11px]" id="copyKeyIcon"></i>
                        <span id="copyKeyText">Copy</span>
                    </button>
                </div>

                <!-- Whose license key is this? Clear Hotel & License Identification -->
                <div class="bg-white/90 rounded-xl p-3 border border-indigo-100 text-xs space-y-2">
                    <div class="flex items-center justify-between border-b border-indigo-50/80 pb-1.5">
                        <span class="text-[11px] text-slate-500 font-semibold flex items-center">
                            <i class="fa-solid fa-hotel text-indigo-500 mr-1.5 text-[11px]"></i> Assigned Hotel:
                        </span>
                        <span id="modalKeyHotelName" class="font-bold text-slate-900 truncate max-w-[220px]">---</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>
                            <span class="text-slate-400 font-medium">Owner:</span>
                            <span id="modalKeyOwner" class="font-bold text-slate-800 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Plan:</span>
                            <span id="modalKeyPlan" class="font-bold text-indigo-600 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Expiry:</span>
                            <span id="modalKeyExpiry" class="font-bold text-slate-700 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Distributor:</span>
                            <span id="modalKeyDistributor" class="font-bold text-slate-700 ml-1 truncate">---</span>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-indigo-600/80 font-medium">Use this license key when pairing or re-authorizing TV screens in this hotel.</p>
            </div>

            <!-- Device Technical Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hotel Name</div>
                    <div id="modalHotelName" class="font-extrabold text-slate-900 truncate">---</div>
                    <div id="modalOwnerName" class="text-[10px] text-slate-500 font-medium truncate">---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Room Number</div>
                    <div id="modalRoomNo" class="font-extrabold text-indigo-600">---</div>
                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Status: Connected</span>
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
        <div class="pt-3 flex items-center justify-between space-x-2.5 border-t border-slate-100 shrink-0">
            <button type="button" onclick="disconnectFromModal()" class="px-4 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span>Disconnect TV</span>
            </button>
            <button type="button" onclick="closeDeviceDetailsModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Hidden Disconnect Form for Confirmation Execution -->
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
        const deviceLabel = 'Room ' + currentModalDevice.room_no + ' (' + currentModalDevice.hotel_name + ')';
        confirmDisconnect(currentModalDevice.disconnect_url, deviceLabel);
    }

    function confirmDisconnect(actionUrl, deviceLabel) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Disconnect Device?',
                text: 'Are you sure you want to disconnect ' + deviceLabel + '? The TV will need to be re-paired to reconnect.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Disconnect',
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
            if (confirm('Disconnect ' + deviceLabel + '?')) {
                const form = document.getElementById('disconnectDeviceForm');
                form.action = actionUrl;
                form.submit();
            }
        }
    }
</script>
@endsection
