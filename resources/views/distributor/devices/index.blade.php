@extends('layouts.distributor')

@section('title', 'Connected TVs - Distributor Portal')
@section('page_title', 'Connected Smart TVs')

@section('content')
<div class="space-y-6">
    <!-- Top Filter & Search Bar -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-5 sm:p-6 shadow-sm">
        <form method="GET" action="{{ route('distributor.devices.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <!-- Filter & Search Controls -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Hotel Filter Dropdown -->
                <div class="w-full sm:w-64 shrink-0">
                    <label for="hotelFilter" class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Filter by Hotel</label>
                    <div class="relative">
                        @php
                            $hotelOptions = ['' => 'All My Hotels (' . $hotels->count() . ')'] + $hotels->pluck('hotel_name', 'id')->toArray();
                        @endphp
                        <x-dropdown name="hotel_id" :options="$hotelOptions" :selected="request('hotel_id')" onchange="this.form.submit()" />
                    </div>
                </div>

                <!-- Search Input -->
                <div class="flex-1">
                    <label for="searchInput" class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Search Devices</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search room no, device ID, MAC, IP, brand..." class="w-full pl-9 pr-8 py-2.5 rounded-2xl border border-slate-200 bg-slate-50/70 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-400">
                        @if(request('search'))
                            <a href="{{ route('distributor.devices.index', array_filter(['hotel_id' => request('hotel_id')])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="self-end sm:self-auto sm:pt-5">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-1.5 active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Apply</span>
                    </button>
                </div>
            </div>

            <!-- Stats / Counter Indicator -->
            <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                <div class="px-5 py-3 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center space-x-3 shadow-2xs">
                    <div class="w-10 h-10 rounded-xl bg-[#150A42] text-white flex items-center justify-center text-sm font-black shadow-xs">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-purple-700 tracking-wider">Connected Fleet</div>
                        <div class="text-base font-black text-slate-900 leading-tight">
                            {{ $devices->total() }} <span class="text-xs font-bold text-slate-400">screens</span>
                            @if(isset($selectedHotel))
                                <span class="text-xs font-black text-purple-700">/ {{ $selectedHotel->allowed_device_limit }} cap</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop & Tablet Table View -->
    <div class="hidden md:block bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800">
                <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-4 w-12 text-center">#</th>
                        <th class="px-5 py-4">Hotel Property</th>
                        <th class="px-5 py-4">Room</th>
                        <th class="px-5 py-4">Hardware & OS</th>
                        <th class="px-5 py-4">Device & Network</th>
                        <th class="px-5 py-4">Connected At</th>
                        <th class="px-5 py-4 text-center">Details</th>
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
                                'distributor_name' => $device->hotelAdmin->distributor->name ?? (auth()->user()->name ?? 'Distributor'),
                                'device_id' => $device->device_id,
                                'mac_address' => $device->mac_address ?? 'N/A',
                                'ip_address' => $device->ip_address ?? 'N/A',
                                'brand' => $device->brand ?? '',
                                'model' => $device->model ?? '',
                                'os_version' => $device->os_version ?? '',
                                'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                                'disconnect_url' => route('distributor.devices.destroy', $device->id),
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 text-center font-bold text-slate-400">
                                {{ $devices->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900">{{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold truncate max-w-[180px]">{{ $device->hotelAdmin->owner_name ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-xl bg-purple-50 border border-purple-200/70 text-[#150A42] font-black text-xs shadow-2xs">
                                    <i class="fa-solid fa-door-closed mr-1.5 text-purple-600 text-[10px]"></i>
                                    Room {{ $device->room_no }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($device->brand || $device->model)
                                    <div class="font-bold text-slate-800 capitalize flex items-center space-x-1.5">
                                        <i class="fa-solid fa-tv text-purple-600 text-[11px]"></i>
                                        <span>{{ $device->brand }} {{ $device->model }}</span>
                                    </div>
                                    @if($device->os_version)
                                        <span class="text-[10px] text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded-md mt-0.5 inline-block">Android {{ $device->os_version }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Smart TV Device</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-mono text-[11px] font-bold text-slate-800 truncate max-w-[160px]" title="{{ $device->device_id }}">
                                    {{ Str::limit($device->device_id, 16) }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 flex items-center gap-2 mt-0.5">
                                    @if($device->ip_address)<span>IP: {{ $device->ip_address }}</span>@endif
                                    @if($device->mac_address)<span>MAC: {{ $device->mac_address }}</span>@endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-500 font-medium whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $device->created_at ? $device->created_at->format('d M, Y') : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $device->created_at ? $device->created_at->format('h:i A') : '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white border border-purple-200/80 font-black text-xs transition-all shadow-2xs active:scale-95 cursor-pointer" title="View Details">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        <span>View</span>
                                    </button>
                                    <button type="button" onclick="confirmDisconnect('{{ route('distributor.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }} ({{ $device->hotelAdmin->hotel_name ?? 'Hotel' }})')" class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl border border-rose-200 bg-rose-50/60 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-xs transition-all shadow-2xs active:scale-95 cursor-pointer" title="Disconnect TV">
                                        <i class="fa-solid fa-power-off text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] text-xl mb-3 shadow-xs">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-900">No Connected TV Screens Found</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">No TV devices found under your hotels for the selected criteria.</p>
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
                $devicePayload = [
                    'id' => $device->id,
                    'room_no' => $device->room_no,
                    'hotel_name' => $device->hotelAdmin->hotel_name ?? 'N/A',
                    'owner_name' => $device->hotelAdmin->owner_name ?? 'N/A',
                    'license_key' => $device->hotelAdmin->license_key ?? 'N/A',
                    'plan_name' => $device->hotelAdmin->plan->name ?? 'Standard Plan',
                    'expiry_date' => $device->hotelAdmin->expiry_date ? \Carbon\Carbon::parse($device->hotelAdmin->expiry_date)->format('d M, Y') : ($device->hotelAdmin->plan_expires_at ? \Carbon\Carbon::parse($device->hotelAdmin->plan_expires_at)->format('d M, Y') : 'Active'),
                    'distributor_name' => $device->hotelAdmin->distributor->name ?? (auth()->user()->name ?? 'Distributor'),
                    'device_id' => $device->device_id,
                    'mac_address' => $device->mac_address ?? 'N/A',
                    'ip_address' => $device->ip_address ?? 'N/A',
                    'brand' => $device->brand ?? '',
                    'model' => $device->model ?? '',
                    'os_version' => $device->os_version ?? '',
                    'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                    'disconnect_url' => route('distributor.devices.destroy', $device->id),
                ];
            @endphp
            <div class="bg-white border border-slate-100 rounded-[24px] p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="min-w-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-purple-50 border border-purple-200/70 text-[#150A42] font-black text-[11px] mb-1">
                            <i class="fa-solid fa-door-closed mr-1 text-purple-600 text-[10px]"></i>
                            Room {{ $device->room_no }}
                        </span>
                        <h4 class="text-xs font-black text-slate-900 truncate">{{ $device->hotelAdmin->hotel_name ?? 'Unknown Hotel' }}</h4>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Online
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Hardware</div>
                        <div class="font-bold text-slate-800 truncate mt-0.5">
                            {{ $device->brand || $device->model ? trim($device->brand . ' ' . $device->model) : 'Smart TV' }}
                        </div>
                        @if($device->os_version)
                            <div class="text-[9px] text-slate-500 font-semibold">Android {{ $device->os_version }}</div>
                        @endif
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Connected</div>
                        <div class="font-bold text-slate-800 truncate mt-0.5">
                            {{ $device->created_at ? $device->created_at->format('d M, Y') : 'N/A' }}
                        </div>
                        <div class="text-[9px] text-slate-400">{{ $device->created_at ? $device->created_at->format('h:i A') : '' }}</div>
                    </div>
                </div>

                <div class="text-[10px] font-mono text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 truncate">
                    <span class="font-bold text-slate-400">ID:</span> {{ $device->device_id }}
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="w-full py-2 px-3 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white border border-purple-200/80 font-black text-xs flex items-center justify-center space-x-1 transition-all">
                        <i class="fa-solid fa-eye text-xs"></i>
                        <span>Details & Key</span>
                    </button>
                    <button type="button" onclick="confirmDisconnect('{{ route('distributor.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }} ({{ $device->hotelAdmin->hotel_name ?? 'Hotel' }})')" class="w-full py-2 px-3 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs flex items-center justify-center space-x-1 transition-colors">
                        <i class="fa-solid fa-power-off text-xs"></i>
                        <span>Disconnect</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-100 rounded-[24px] p-8 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] text-xl mb-2">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <h4 class="text-xs font-black text-slate-800">No TV Screens Found</h4>
                <p class="text-[11px] text-slate-400 mt-1">No connected TV screens found for your hotels.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($devices->hasPages())
        <div class="bg-white border border-slate-100 rounded-[28px] p-4 shadow-sm">
            {{ $devices->links() }}
        </div>
    @endif
</div>

<!-- Reusable Device & License Details Modal -->
<div id="deviceDetailsModal" class="fixed inset-0 z-50 bg-[#0B0424]/75 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-4 overflow-hidden" onclick="closeDeviceModalOnBackdrop(event)">
    <div class="bg-white border border-slate-100 rounded-[28px] max-w-lg w-full shadow-2xl animate-in fade-in zoom-in duration-150 flex flex-col max-h-[84vh] sm:max-h-[86vh] min-h-0 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center space-x-3.5 min-w-0 pr-2">
                <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shrink-0 shadow-2xs">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-black text-slate-900 leading-tight truncate">Device & License Details</h3>
                    <p id="modalSubtitle" class="text-[11px] sm:text-xs text-slate-400 font-bold truncate mt-0.5">Room 101 • Hotel Details</p>
                </div>
            </div>
            <!-- Top Actions: Disconnect button (desktop) & Close button -->
            <div class="flex items-center space-x-1.5 shrink-0">
                <button type="button" onclick="disconnectFromModal()" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all shadow-2xs" title="Disconnect this TV">
                    <i class="fa-solid fa-power-off text-xs"></i>
                    <span>Disconnect</span>
                </button>
                <button type="button" onclick="closeDeviceDetailsModal()" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Modal Content (touch scroll enabled) -->
        <div class="px-5 py-5 sm:px-6 sm:py-6 overflow-y-auto overscroll-contain flex-1 min-h-0 space-y-4" style="-webkit-overflow-scrolling: touch;">
            <!-- License Key Highlight Box -->
            <div class="p-4 sm:p-5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-purple-900 flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-[10px] text-purple-600"></i>
                        <span>Hotel License Key</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-200/60 text-purple-900 text-[10px] font-black">Authorized</span>
                </div>
                <div class="flex items-center justify-between gap-2 bg-white rounded-xl p-3 border border-purple-100 shadow-2xs">
                    <span id="modalLicenseKey" class="font-mono font-black text-xs sm:text-base text-slate-900 tracking-wide select-all truncate">
                        ---
                    </span>
                    <button type="button" onclick="copyLicenseKey()" id="copyKeyBtn" class="shrink-0 px-3.5 py-1.5 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-[11px] shadow-xs transition-all flex items-center space-x-1 cursor-pointer">
                        <i class="fa-solid fa-copy text-[11px]" id="copyKeyIcon"></i>
                        <span id="copyKeyText">Copy</span>
                    </button>
                </div>

                <!-- Assigned Hotel Identification -->
                <div class="bg-white rounded-xl p-3.5 border border-purple-100 text-xs space-y-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-[11px] text-slate-400 font-bold flex items-center">
                            <i class="fa-solid fa-hotel text-purple-600 mr-1.5 text-[11px]"></i> Assigned Hotel:
                        </span>
                        <span id="modalKeyHotelName" class="font-black text-slate-900 truncate max-w-[200px] sm:max-w-[240px]">---</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>
                            <span class="text-slate-400 font-medium">Owner / Contact:</span>
                            <span id="modalKeyOwner" class="font-bold text-slate-800 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium">Plan:</span>
                            <span id="modalKeyPlan" class="font-bold text-purple-700 ml-1 truncate">---</span>
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

                <p class="text-[10px] text-purple-700 font-medium leading-relaxed">Use this license key when pairing or authorizing TV screens in this hotel.</p>
            </div>

            <!-- Device Technical Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hotel Name</div>
                    <div id="modalHotelName" class="font-black text-slate-900 truncate">---</div>
                    <div id="modalOwnerName" class="text-[10px] text-slate-400 font-medium truncate">---</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Room Number</div>
                    <div id="modalRoomNo" class="font-black text-purple-700">---</div>
                    <div class="text-[10px] text-emerald-600 font-bold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Status: Connected</span>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Device Unique ID</div>
                    <div class="flex items-center justify-between gap-2">
                        <div id="modalDeviceId" class="font-mono font-bold text-slate-800 text-[11px] truncate select-all">---</div>
                        <button type="button" onclick="copyToClipboard(document.getElementById('modalDeviceId').innerText, this)" class="text-slate-400 hover:text-slate-700 text-xs shrink-0 p-1 cursor-pointer" title="Copy Device ID">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hardware / Model</div>
                    <div id="modalHardware" class="font-bold text-slate-800 truncate">---</div>
                    <div id="modalOsVersion" class="text-[10px] text-slate-400 font-bold">---</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Network Info</div>
                    <div id="modalIpAddress" class="font-mono text-slate-700 font-bold text-[11px]">IP: ---</div>
                    <div id="modalMacAddress" class="font-mono text-[10px] text-slate-400">MAC: ---</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Connection Timestamp</div>
                    <div id="modalConnectedAt" class="font-bold text-slate-700 text-[11px]">---</div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-4 sm:px-6 sm:py-4 border-t border-slate-100 flex items-center justify-between space-x-2.5 shrink-0 bg-slate-50/80">
            <button type="button" onclick="disconnectFromModal()" class="px-4 py-2.5 rounded-2xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs active:scale-95 cursor-pointer">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span>Disconnect TV</span>
            </button>
            <button type="button" onclick="closeDeviceDetailsModal()" class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors shadow-2xs active:scale-95 cursor-pointer">
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
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Disconnect',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-[28px]',
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
            if (confirm('Disconnect ' + deviceLabel + '? The TV will need to be re-paired to reconnect.')) {
                const form = document.getElementById('disconnectDeviceForm');
                form.action = actionUrl;
                form.submit();
            }
        }
    }
</script>
@endsection
