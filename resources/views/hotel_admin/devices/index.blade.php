@extends('layouts.hotel_admin')

@section('title', 'Connected TVs - Hotel Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top License & TV Capacity Card (PaxTV Luxury Midnight Theme) -->
    <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[28px] p-5 sm:p-7 shadow-sm relative overflow-hidden flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="absolute -right-16 -top-16 w-60 h-60 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-10 w-48 h-48 bg-purple-50/40 rounded-full blur-2xl pointer-events-none"></div>

        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-[11px] sm:text-xs border border-purple-200/60 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Hardware Network</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-600 font-bold truncate max-w-[160px] sm:max-w-none">{{ $hotel->hotel_name }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Connected Smart TV Fleet
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium leading-relaxed">
                Authorized, cloud-synchronized Smart TV devices running PaxTV Launcher across your property rooms.
            </p>
        </div>
        
        <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3 relative z-10 shrink-0">
            <!-- Pair TV Screen Primary Action Button -->
            <button type="button" 
                    onclick="openPairModal()" 
                    class="px-5 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 shrink-0 cursor-pointer active:scale-95 group">
                <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-tv text-xs text-amber-300"></i>
                </div>
                <span>Pair TV Screen</span>
                <i class="fa-solid fa-plus text-[10px] text-purple-300 ml-0.5"></i>
            </button>

            <!-- TV Hardware Capacity Meter Pill -->
            <div class="space-y-2 bg-slate-50/90 p-3.5 sm:p-4 rounded-2xl border border-slate-200/70 min-w-[230px] shadow-2xs">
                @php
                    $connectedCount = $devices->total();
                    $allowedLimit = (int) ($hotel->allowed_device_limit ?: ($hotel->room_count ?: 0));
                    $percent = $allowedLimit > 0 ? min(100, round(($connectedCount / $allowedLimit) * 100)) : 0;
                    $available = max(0, $allowedLimit - $connectedCount);
                @endphp
                <div class="text-xs font-semibold text-slate-500 flex items-center justify-between space-x-2">
                    <span class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-wider">Capacity Meter</span>
                    <div>
                        <span class="text-sm font-black text-slate-900">{{ $connectedCount }}</span> / <strong class="text-slate-900">{{ $allowedLimit }} TVs</strong>
                    </div>
                </div>
                <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-600 to-[#150A42] rounded-full transition-all duration-500" style="width: {{ $percent }}%;"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 font-bold">
                    <span>{{ $percent }}% Synchronized</span>
                    <span class="text-emerald-600">{{ $available }} slots free</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-slate-100 rounded-2xl p-3.5 sm:p-4 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('hotel.devices.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by room no (e.g. 101), device ID, MAC, IP, brand..." 
                       class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-400">
                @if(request('search'))
                    <a href="{{ route('hotel.devices.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 text-xs" title="Clear search">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-bold text-xs transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-xs active:scale-95">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filter</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('hotel.devices.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="hidden md:flex items-center space-x-2 text-xs font-bold text-slate-500 shrink-0">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Total Units: <strong class="text-slate-900 font-black">{{ $devices->total() }}</strong></span>
        </div>
    </div>

    <!-- Pair TV Modal (8-Digit Code & Live QR Scanner) -->
    <div id="pairModal" class="fixed inset-0 z-50 bg-[#0B0424]/80 backdrop-blur-sm flex items-center justify-center hidden p-3 sm:p-4" onclick="closePairModalOnBackdrop(event)">
        <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[26px] p-4 sm:p-5 max-w-sm sm:max-w-md w-full shadow-2xl space-y-3 animate-in fade-in zoom-in-95 duration-150 relative" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-sm shadow-2xs shrink-0">
                        <i class="fa-solid fa-tv text-purple-700"></i>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight leading-tight">Pair TV Screen</h3>
                        <p class="text-[10px] sm:text-[11px] text-slate-400 font-medium">Link via 8-digit code or QR scan on TV screen</p>
                    </div>
                </div>
                <button onclick="closePairModal()" class="text-slate-400 hover:text-slate-700 w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer" title="Close">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Sub Tab: Manual Code vs Camera Scanner -->
            <div class="flex items-center p-1 bg-slate-100 border border-slate-200/80 rounded-xl text-[11px]">
                <button type="button" id="tabManualBtn" onclick="switchPairSubTab('manual')" class="flex-1 py-1.5 font-bold rounded-lg bg-white text-[#150A42] shadow-xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-keyboard text-[10px]"></i>
                    <span>Type 8-Digit Code</span>
                </button>
                <button type="button" id="tabScanBtn" onclick="switchPairSubTab('scan')" class="flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    <span>Scan TV QR</span>
                </button>
            </div>

            <!-- Camera Scanner Box (Compact without scrollbars) -->
            <div id="qrScannerBox" class="hidden space-y-1 text-center">
                <div id="qrReader" class="w-full max-w-[210px] h-[160px] mx-auto overflow-hidden rounded-xl border-2 border-purple-300 bg-slate-900 flex items-center justify-center shadow-inner relative">
                    <div id="qrLoading" class="hidden absolute inset-0 bg-slate-900 flex flex-col items-center justify-center text-white z-10">
                        <i class="fa-solid fa-circle-notch fa-spin text-xl text-purple-400 mb-1.5"></i>
                        <span class="text-[10px] text-slate-300 font-bold">Starting Camera...</span>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Point camera at TV QR code</p>
            </div>

            <!-- Pair Form -->
            <form id="pairForm" onsubmit="submitPairForm(event)" class="space-y-3">
                @csrf
                <div id="pairCodeInputWrapper" class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-900 flex items-center justify-between">
                        <span>8-Digit Pairing Code <span class="text-rose-600">*</span></span>
                        <span class="text-[9px] text-purple-700 font-bold uppercase tracking-wider">From TV Launcher</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="pairCodeInput" 
                               required 
                               placeholder="e.g. 8F2A-9K3P" 
                               maxlength="10" 
                               autocomplete="off"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono font-black text-xl tracking-widest text-[#150A42] uppercase focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-300 shadow-inner">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-900 flex items-center justify-between">
                        <span>Assign Room Number <span class="text-rose-600">*</span></span>
                        <span class="text-[9px] text-slate-400">e.g. 101, 204</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-door-closed absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               id="roomNoInput" 
                               required 
                               placeholder="Enter room number" 
                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                    </div>
                </div>

                <div id="pairFormAlert" class="hidden p-2.5 rounded-xl text-xs font-semibold"></div>

                <div class="pt-1 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closePairModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" id="pairSubmitBtn" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-bold text-xs shadow-md shadow-[#150A42]/15 flex items-center space-x-1.5 cursor-pointer active:scale-95 transition-all">
                        <i class="fa-solid fa-link text-[11px] text-amber-300"></i>
                        <span>Connect & Pair TV</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop & Tablet Devices Table -->
    <div class="hidden md:block bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-4 w-12 text-center">#</th>
                        <th class="px-5 py-4">Room & In-House Guest</th>
                        <th class="px-5 py-4">Hardware Specs</th>
                        <th class="px-5 py-4">Device & Network ID</th>
                        <th class="px-5 py-4">Connected At</th>
                        <th class="px-5 py-4 text-center">Room Config</th>
                        <th class="px-5 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $index => $device)
                        @php
                            $devicePayload = [
                                'id' => $device->id,
                                'room_no' => $device->room_no,
                                'hotel_name' => $hotel->hotel_name ?? 'Hotel',
                                'owner_name' => $hotel->owner_name ?? 'N/A',
                                'license_key' => $hotel->license_key ?? 'N/A',
                                'plan_name' => $hotel->plan->name ?? 'Standard Plan',
                                'expiry_date' => $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('d M, Y') : ($hotel->plan_expires_at ? \Carbon\Carbon::parse($hotel->plan_expires_at)->format('d M, Y') : 'Active'),
                                'distributor_name' => $hotel->distributor->name ?? 'Direct',
                                'device_id' => $device->device_id,
                                'mac_address' => $device->mac_address ?? 'N/A',
                                'ip_address' => $device->ip_address ?? 'N/A',
                                'brand' => $device->brand ?? '',
                                'model' => $device->model ?? '',
                                'os_version' => $device->os_version ?? '',
                                'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                                'disconnect_url' => route('hotel.devices.destroy', $device->id),
                            ];
                        @endphp
                        <tr class="hover:bg-purple-50/30 transition-colors">
                            <td class="px-5 py-4 text-center font-bold text-slate-400">
                                {{ $devices->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-purple-50 text-[#150A42] font-black text-xs border border-purple-200/60 shadow-2xs">
                                        <i class="fa-solid fa-door-closed mr-1.5 text-purple-600 text-[10px]"></i>
                                        Room {{ $device->room_no }}
                                    </span>
                                </div>
                                @if($activeGuest = $activeGuests->get($device->room_no))
                                    <div class="mt-1">
                                        <a href="{{ route('hotel.guests.index') }}?room={{ urlencode($device->room_no) }}" class="inline-flex items-center text-[11px] font-bold text-emerald-700 hover:underline">
                                            <i class="fa-solid fa-user text-emerald-600 text-[9px] mr-1"></i>
                                            <span>{{ $activeGuest->name }}</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-1 text-[11px] text-slate-400 italic flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span> Vacant
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($device->brand || $device->model)
                                    <div class="font-extrabold text-slate-900 capitalize flex items-center space-x-1.5">
                                        <i class="fa-solid fa-tv text-purple-600 text-[11px]"></i>
                                        <span>{{ $device->brand }} {{ $device->model }}</span>
                                    </div>
                                    @if($device->os_version)
                                        <span class="text-[10px] text-slate-500 font-semibold bg-slate-100 px-2 py-0.5 rounded-md mt-0.5 inline-block">Android {{ $device->os_version }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Smart TV Device</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-mono text-[11px] font-bold text-slate-800 truncate max-w-[170px]" title="{{ $device->device_id }}">
                                    {{ Str::limit($device->device_id, 18) }}
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
                                    <a href="{{ route('hotel.devices.ott', $device->id) }}" class="px-3 py-1.5 rounded-xl border border-purple-200/80 bg-purple-50/50 text-[#150A42] hover:bg-purple-100 font-extrabold text-[11px] transition-all shadow-2xs" title="Manage OTT Apps">
                                        <i class="fa-solid fa-sliders mr-1 text-purple-600"></i> OTT
                                    </a>
                                    <a href="{{ route('hotel.devices.menus', $device->id) }}" class="px-3 py-1.5 rounded-xl border border-blue-200/80 bg-blue-50/50 text-blue-900 hover:bg-blue-100 font-extrabold text-[11px] transition-all shadow-2xs" title="Manage TV Menus">
                                        <i class="fa-solid fa-list-check mr-1 text-blue-600"></i> Menus
                                    </a>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-white border border-slate-200 text-slate-800 font-bold text-xs transition-all shadow-2xs cursor-pointer" title="View Full Specs">
                                        <i class="fa-solid fa-eye text-purple-600"></i>
                                        <span>View</span>
                                    </button>
                                    <button type="button" onclick="confirmDisconnect('{{ route('hotel.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-colors shadow-2xs cursor-pointer" title="Disconnect TV">
                                        <i class="fa-solid fa-power-off text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 mx-auto rounded-3xl bg-purple-50 text-purple-700 flex items-center justify-center text-2xl mb-3 shadow-inner">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-900">No Connected TVs Found</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Pair your TV screen with an 8-digit pairing code to link devices to hotel rooms.</p>
                                <button type="button" onclick="openPairModal()" class="mt-4 px-5 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-bold text-xs transition-all shadow-md active:scale-95 cursor-pointer">
                                    <i class="fa-solid fa-plus mr-1"></i> Pair First TV
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View (Optimized for fast mobile management) -->
    <div class="md:hidden space-y-3.5">
        @forelse($devices as $index => $device)
            @php
                $devicePayload = [
                    'id' => $device->id,
                    'room_no' => $device->room_no,
                    'hotel_name' => $hotel->hotel_name ?? 'Hotel',
                    'owner_name' => $hotel->owner_name ?? 'N/A',
                    'license_key' => $hotel->license_key ?? 'N/A',
                    'plan_name' => $hotel->plan->name ?? 'Standard Plan',
                    'expiry_date' => $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('d M, Y') : ($hotel->plan_expires_at ? \Carbon\Carbon::parse($hotel->plan_expires_at)->format('d M, Y') : 'Active'),
                    'distributor_name' => $hotel->distributor->name ?? 'Direct',
                    'device_id' => $device->device_id,
                    'mac_address' => $device->mac_address ?? 'N/A',
                    'ip_address' => $device->ip_address ?? 'N/A',
                    'brand' => $device->brand ?? '',
                    'model' => $device->model ?? '',
                    'os_version' => $device->os_version ?? '',
                    'connected_at' => $device->created_at ? $device->created_at->format('d M, Y - h:i A') : 'N/A',
                    'disconnect_url' => route('hotel.devices.destroy', $device->id),
                ];
            @endphp
            <div class="bg-white border border-slate-100 rounded-[22px] p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="min-w-0">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-purple-50 border border-purple-200/60 text-[#150A42] font-black text-xs mb-1 shadow-2xs">
                            <i class="fa-solid fa-door-closed mr-1.5 text-purple-600 text-[10px]"></i>
                            Room {{ $device->room_no }}
                        </span>
                        @if($activeGuest = $activeGuests->get($device->room_no))
                            <div class="text-xs font-extrabold text-slate-900 truncate flex items-center">
                                <i class="fa-solid fa-user text-emerald-600 text-[10px] mr-1.5"></i>
                                <span>{{ $activeGuest->name }}</span>
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400 italic">Vacant Room</div>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Online
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/60">
                        <div class="text-[9px] uppercase font-black text-slate-400 tracking-wider">Hardware</div>
                        <div class="font-extrabold text-slate-900 truncate mt-0.5">
                            {{ $device->brand || $device->model ? trim($device->brand . ' ' . $device->model) : 'Smart TV' }}
                        </div>
                        @if($device->os_version)
                            <div class="text-[9px] text-slate-400 font-semibold">Android {{ $device->os_version }}</div>
                        @endif
                    </div>

                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/60">
                        <div class="text-[9px] uppercase font-black text-slate-400 tracking-wider">Connected</div>
                        <div class="font-extrabold text-slate-900 truncate mt-0.5">
                            {{ $device->created_at ? $device->created_at->format('d M, Y') : 'N/A' }}
                        </div>
                        <div class="text-[9px] text-slate-400">{{ $device->created_at ? $device->created_at->format('h:i A') : '' }}</div>
                    </div>
                </div>

                <div class="text-[10px] font-mono text-slate-600 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/60 flex items-center justify-between">
                    <span class="truncate"><strong class="text-slate-400 font-sans">ID:</strong> {{ $device->device_id }}</span>
                    <button type="button" onclick="copyToClipboard('{{ $device->device_id }}', this)" class="text-slate-400 hover:text-slate-700 ml-2 p-1" title="Copy Device ID">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>

                <!-- Action Controls: Config OTT/Menus, Details & Disconnect -->
                <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                    <a href="{{ route('hotel.devices.ott', $device->id) }}" class="flex-1 py-2 px-2 rounded-xl border border-purple-200/80 bg-purple-50/50 text-[#150A42] hover:bg-purple-100 font-extrabold text-xs flex items-center justify-center space-x-1 transition-colors shadow-2xs">
                        <i class="fa-solid fa-sliders text-[11px] text-purple-600"></i>
                        <span>OTT</span>
                    </a>
                    <a href="{{ route('hotel.devices.menus', $device->id) }}" class="flex-1 py-2 px-2 rounded-xl border border-blue-200/80 bg-blue-50/50 text-blue-900 hover:bg-blue-100 font-extrabold text-xs flex items-center justify-center space-x-1 transition-colors shadow-2xs">
                        <i class="fa-solid fa-list-check text-[11px] text-blue-600"></i>
                        <span>Menus</span>
                    </a>
                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="py-2 px-3 rounded-xl bg-slate-50 hover:bg-white border border-slate-200 text-slate-900 font-bold text-xs flex items-center justify-center transition-all cursor-pointer shadow-2xs" title="View Full Specs">
                        <i class="fa-solid fa-eye text-purple-600"></i>
                    </button>
                    <button type="button" onclick="confirmDisconnect('{{ route('hotel.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }}')" class="py-2 px-3 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs flex items-center justify-center transition-colors cursor-pointer shadow-2xs" title="Disconnect TV">
                        <i class="fa-solid fa-power-off text-rose-600"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-100 rounded-[22px] p-8 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl mb-2">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <h4 class="text-xs font-black text-slate-900">No TV Screens Found</h4>
                <p class="text-[11px] text-slate-400 mt-1">Pair your TV screen with an 8-digit code to connect.</p>
                <button type="button" onclick="openPairModal()" class="mt-3 px-4 py-2 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-bold text-xs transition-all shadow-md active:scale-95 cursor-pointer">
                    Pair TV Screen
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $devices->links() }}
    </div>
</div>

<!-- Reusable Device & License Details Modal -->
<div id="deviceDetailsModal" class="fixed inset-0 z-50 bg-[#0B0424]/80 backdrop-blur-sm flex items-center justify-center hidden p-3 sm:p-4 overflow-hidden" onclick="closeDeviceModalOnBackdrop(event)">
    <div class="bg-white border border-slate-100 rounded-[24px] sm:rounded-[30px] max-w-lg w-full shadow-2xl animate-in fade-in zoom-in-95 duration-150 flex flex-col max-h-[86vh] min-h-0 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <div class="flex items-center space-x-3 min-w-0 pr-2">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold text-base sm:text-lg shrink-0 shadow-2xs">
                    <i class="fa-solid fa-tv text-purple-700"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-black text-slate-900 leading-tight truncate">Device & License Details</h3>
                    <p id="modalSubtitle" class="text-[11px] sm:text-xs text-slate-500 font-semibold truncate mt-0.5">Room 101 • Device Specs</p>
                </div>
            </div>
            <!-- Top Actions: Disconnect button (desktop) & Close button -->
            <div class="flex items-center space-x-1.5 shrink-0">
                <button type="button" onclick="disconnectFromModal()" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 text-xs font-bold transition-all shadow-2xs cursor-pointer" title="Disconnect this TV">
                    <i class="fa-solid fa-power-off text-xs"></i>
                    <span>Disconnect</span>
                </button>
                <button type="button" onclick="closeDeviceDetailsModal()" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Modal Content (touch scroll enabled) -->
        <div class="px-5 py-4 sm:px-6 sm:py-5 overflow-y-auto overscroll-contain flex-1 min-h-0 space-y-4" style="-webkit-overflow-scrolling: touch;">
            <!-- License Key Highlight Box -->
            <div class="p-4 sm:p-5 rounded-2xl bg-purple-50/70 border border-purple-200/60 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-purple-900 flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-[10px] text-purple-600"></i>
                        <span>Hotel License Key</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">Authorized</span>
                </div>
                <div class="flex items-center justify-between gap-2 bg-white rounded-xl p-2.5 border border-purple-200/80 shadow-2xs">
                    <span id="modalLicenseKey" class="font-mono font-black text-xs sm:text-base text-[#150A42] tracking-wide select-all truncate">
                        ---
                    </span>
                    <button type="button" onclick="copyLicenseKey()" id="copyKeyBtn" class="shrink-0 px-3 py-1.5 rounded-lg bg-[#150A42] hover:bg-[#1f105e] text-white font-bold text-[11px] shadow-xs transition-all flex items-center space-x-1 cursor-pointer">
                        <i class="fa-solid fa-copy text-[11px]" id="copyKeyIcon"></i>
                        <span id="copyKeyText">Copy</span>
                    </button>
                </div>

                <!-- Hotel & License Identification -->
                <div class="bg-white rounded-xl p-3 border border-slate-200/80 text-xs space-y-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-[11px] text-slate-500 font-semibold flex items-center">
                            <i class="fa-solid fa-hotel text-purple-600 mr-1.5 text-[11px]"></i> Assigned Hotel:
                        </span>
                        <span id="modalKeyHotelName" class="font-bold text-slate-900 truncate max-w-[200px] sm:max-w-[240px]">---</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>
                            <span class="text-slate-500 font-medium">Owner / Contact:</span>
                            <span id="modalKeyOwner" class="font-bold text-slate-900 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium">Plan:</span>
                            <span id="modalKeyPlan" class="font-bold text-[#150A42] ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium">Expiry:</span>
                            <span id="modalKeyExpiry" class="font-bold text-slate-900 ml-1 truncate">---</span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-medium">Distributor:</span>
                            <span id="modalKeyDistributor" class="font-bold text-slate-900 ml-1 truncate">---</span>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-slate-500 font-medium leading-relaxed">Hotel license key used to authenticate connected Smart TVs in your property.</p>
            </div>

            <!-- Device Technical Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-black text-slate-400">Hotel Name</div>
                    <div id="modalHotelName" class="font-extrabold text-slate-900 truncate">---</div>
                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>License Active</span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-black text-slate-400">Room Number</div>
                    <div id="modalRoomNo" class="font-extrabold text-[#150A42]">---</div>
                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Status: Connected</span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-black text-slate-400">Device Unique ID</div>
                    <div class="flex items-center justify-between gap-2">
                        <div id="modalDeviceId" class="font-mono font-bold text-slate-800 text-[11px] truncate select-all">---</div>
                        <button type="button" onclick="copyToClipboard(document.getElementById('modalDeviceId').innerText, this)" class="text-slate-400 hover:text-slate-700 text-xs shrink-0 p-1" title="Copy Device ID">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-black text-slate-400">Hardware / Model</div>
                    <div id="modalHardware" class="font-bold text-slate-800 truncate">---</div>
                    <div id="modalOsVersion" class="text-[10px] text-slate-500 font-semibold">---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-black text-slate-400">Network Info</div>
                    <div id="modalIpAddress" class="font-mono text-slate-700 font-semibold text-[11px]">IP: ---</div>
                    <div id="modalMacAddress" class="font-mono text-[10px] text-slate-400">MAC: ---</div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5 sm:col-span-2">
                    <div class="text-[10px] uppercase font-black text-slate-400">Connection Timestamp</div>
                    <div id="modalConnectedAt" class="font-semibold text-slate-700 text-[11px]">---</div>
                </div>
            </div>

            <!-- Disconnect Device Guidance -->
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-slate-600 text-[11px] flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-indigo-500 text-xs mt-0.5 shrink-0"></i>
                <p>Disconnecting will unpair this TV immediately from the room. To reconnect, re-pair using the 8-digit code or hotel license key on screen.</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-slate-100 flex items-center justify-between space-x-2.5 shrink-0 bg-slate-50/80">
            <button type="button" onclick="disconnectFromModal()" class="px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span>Disconnect TV</span>
            </button>
            <button type="button" onclick="closeDeviceDetailsModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors shadow-2xs">
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<style>
    #qrReader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 0.75rem;
    }
    #qrReader img {
        display: none !important;
    }
    #qrReader__scan_region {
        min-height: auto !important;
    }
</style>
<script>
    var html5QrCode = null;

    // Auto-open pair modal if ?pair=1 in URL
    document.addEventListener('DOMContentLoaded', function() {
        if (new URLSearchParams(window.location.search).get('pair') === '1') {
            setTimeout(function() {
                if (typeof window.openPairModal === 'function') {
                    window.openPairModal();
                }
            }, 250);
        }
    });

    // Open pair modal
    window.openPairModal = function() {
        document.body.style.overflow = 'hidden';
        document.body.classList.add('overflow-hidden');
        document.getElementById('pairModal').classList.remove('hidden');
        switchPairSubTab('manual');
        setTimeout(function() {
            var el = document.getElementById('pairCodeInput');
            if (el) el.focus();
        }, 100);
    };
    window.openAddDeviceModal = window.openPairModal;

    function closePairModal() {
        stopScanner();
        document.getElementById('pairModal').classList.add('hidden');
        var alert = document.getElementById('pairFormAlert');
        if (alert) alert.classList.add('hidden');
        var form = document.getElementById('pairForm');
        if (form) form.reset();
        document.body.style.overflow = '';
        document.body.classList.remove('overflow-hidden');
    }

    function closePairModalOnBackdrop(event) {
        if (event.target.id === 'pairModal') {
            closePairModal();
        }
    }

    function switchPairSubTab(tab) {
        const manualBtn = document.getElementById('tabManualBtn');
        const scanBtn = document.getElementById('tabScanBtn');
        const qrBox = document.getElementById('qrScannerBox');
        const codeInputWrapper = document.getElementById('pairCodeInputWrapper');

        if (tab === 'scan') {
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-[#150A42] shadow-xs transition-all flex items-center justify-center space-x-1.5';
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.remove('hidden');
            codeInputWrapper.classList.add('hidden');
            startScanner();
        } else {
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-[#150A42] shadow-xs transition-all flex items-center justify-center space-x-1.5';
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.add('hidden');
            codeInputWrapper.classList.remove('hidden');
            stopScanner();
            document.getElementById('pairCodeInput')?.focus();
        }
    }

    function startScanner() {
        const loader = document.getElementById('qrLoading');
        if (loader) loader.classList.remove('hidden');

        if (html5QrCode && html5QrCode.isScanning) {
            if (loader) loader.classList.add('hidden');
            return;
        }

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qrReader");
        }

        html5QrCode.start(
            { facingMode: { ideal: "environment" } },
            { fps: 15, qrbox: { width: 140, height: 140 } },
            (decodedText) => {
                let text = decodedText.trim();
                let code = '';
                if (text.includes('code=')) {
                    code = text.split('code=')[1].split('&')[0].split('#')[0];
                } else if (text.includes('/')) {
                    const parts = text.split('/');
                    code = parts[parts.length - 1];
                } else {
                    code = text;
                }
                code = code.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                if (code.length > 4) {
                    code = code.substring(0, 4) + '-' + code.substring(4, 8);
                }
                document.getElementById('pairCodeInput').value = code;
                switchPairSubTab('manual');
                document.getElementById('roomNoInput').focus();
                
                const alertBox = document.getElementById('pairFormAlert');
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-800';
                alertBox.innerText = 'QR Scanned Code: ' + code + '. Now assign room number to connect.';
                alertBox.classList.remove('hidden');
            },
            (errorMessage) => {}
        ).then(() => {
            if (loader) loader.classList.add('hidden');
        }).catch((err) => {
            if (loader) loader.classList.add('hidden');
            const alertBox = document.getElementById('pairFormAlert');
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800';
            alertBox.innerText = 'Camera access denied or unavailable. Please enter code manually.';
            alertBox.classList.remove('hidden');
        });
    }

    function stopScanner() {
        const loader = document.getElementById('qrLoading');
        if (loader) loader.classList.add('hidden');
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => console.error(err));
        }
    }

    document.getElementById('pairCodeInput')?.addEventListener('input', function (e) {
        let val = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        if (val.length > 4) {
            val = val.substring(0, 4) + '-' + val.substring(4, 8);
        }
        e.target.value = val;
    });

    // Pair Screen Form Submission
    async function submitPairForm(event) {
        event.preventDefault();
        const code = document.getElementById('pairCodeInput').value.trim();
        const roomNo = document.getElementById('roomNoInput').value.trim();
        const alertBox = document.getElementById('pairFormAlert');
        const submitBtn = document.getElementById('pairSubmitBtn');

        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Connecting...';

        try {
            const response = await fetch("{{ route('hotel.devices.pair') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pair_code: code,
                    room_no: roomNo
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> ' + data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800 space-y-2';
                let msgHtml = `<div>${data.message || 'Pairing failed. Please check the code.'}</div>`;
                alertBox.innerHTML = msgHtml;
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-link text-xs text-amber-300 mr-1.5"></i><span>Connect & Pair TV</span>';
            }
        } catch (err) {
            alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-link text-xs text-amber-300 mr-1.5"></i><span>Connect & Pair TV</span>';
        }
    }

    let currentModalDevice = null;

    // Modal Details functions
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

    function disconnectFromModal() {
        if (!currentModalDevice || !currentModalDevice.disconnect_url) return;
        const deviceLabel = 'Room ' + currentModalDevice.room_no;
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
            if (confirm('Disconnect ' + deviceLabel + '? The TV will need to be re-paired to reconnect.')) {
                const form = document.getElementById('disconnectDeviceForm');
                form.action = actionUrl;
                form.submit();
            }
        }
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
            closePairModal();
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
</script>
@endsection
