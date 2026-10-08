@extends('layouts.hotel_admin')

@section('title', 'Connected TVs - Hotel Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6">
    <!-- Info & License Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">TV Connection License Limit</h3>
            <p class="text-xs text-slate-500 font-medium">Authorized and synchronized Smart TV devices across hotel rooms.</p>
        </div>
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <button type="button" onclick="openAddDeviceModal('quick')" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center space-x-2 shrink-0">
                <i class="fa-solid fa-plus text-sm"></i>
                <span>Add TV Device</span>
            </button>

            <button type="button" onclick="openAddDeviceModal('pair')" class="px-4 py-3 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/90 text-slate-700 font-bold text-xs shadow-2xs transition-all flex items-center justify-center space-x-2 shrink-0">
                <i class="fa-solid fa-qrcode text-indigo-600"></i>
                <span>Pair TV Screen</span>
            </button>

            <div class="space-y-2 bg-slate-50/80 p-3 rounded-2xl border border-slate-100 min-w-[200px]">
                @php
                    $connectedCount = $devices->total();
                    $allowedLimit = $hotel->allowed_device_limit;
                    $percent = $allowedLimit > 0 ? min(100, ($connectedCount / $allowedLimit) * 100) : 0;
                @endphp
                <div class="text-xs font-semibold text-slate-600 flex items-center justify-between space-x-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Usage:</span>
                    <div>
                        <span class="text-sm font-extrabold text-indigo-600">{{ $connectedCount }}</span> / <strong class="text-slate-900">{{ $allowedLimit }} TVs</strong>
                    </div>
                </div>
                <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-600 rounded-full transition-all duration-500" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('hotel.devices.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by room number, device ID, MAC, IP, brand..." class="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                @if(request('search'))
                    <a href="{{ route('hotel.devices.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Search</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Add Device / Pair TV Modal -->
    <div id="pairModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-4 overflow-hidden" onclick="closePairModalOnBackdrop(event)">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-md w-full shadow-2xl space-y-5 animate-in fade-in zoom-in duration-150 max-h-[88vh] overflow-y-auto overscroll-contain" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900" id="deviceModalTitle">Add TV Device</h3>
                        <p class="text-xs text-slate-500 font-medium" id="deviceModalSubtitle">Connect and provision room television</p>
                    </div>
                </div>
                <button onclick="closePairModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Primary Mode Tabs: Quick Add vs 8-Digit Pairing -->
            <div class="flex items-center p-1 bg-slate-100 rounded-2xl">
                <button type="button" id="tabQuickBtn" onclick="switchModalMode('quick')" class="flex-1 py-2 text-xs font-bold rounded-xl bg-white text-indigo-600 shadow-sm transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-bolt text-amber-500"></i>
                    <span>Quick Add TV</span>
                </button>
                <button type="button" id="tabPairBtn" onclick="switchModalMode('pair')" class="flex-1 py-2 text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Pair 8-Digit Code</span>
                </button>
            </div>

            <!-- 1. Quick Add TV Form (Fast, direct provision without waiting for TV app) -->
            <form id="quickAddForm" onsubmit="submitQuickAddForm(event)" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Room Number <span class="text-rose-500">*</span></label>
                    <input type="text" id="quickRoomNo" required placeholder="e.g. 101, 204, Suite-A" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">TV Hardware / Brand</label>
                    <select id="quickBrand" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="Smart TV">Smart TV (Android OS)</option>
                        <option value="Samsung">Samsung TV (Tizen)</option>
                        <option value="LG">LG Smart TV (webOS)</option>
                        <option value="Sony">Sony Bravia TV</option>
                        <option value="Xiaomi">Xiaomi / Mi TV</option>
                        <option value="OnePlus">OnePlus TV</option>
                        <option value="TCL">TCL Android TV</option>
                        <option value="Other">Other Screen</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Model / Serial (Optional)</label>
                    <input type="text" id="quickModel" placeholder="e.g. 43-inch UHD 4K" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div id="quickFormAlert" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closePairModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                    <button type="submit" id="quickSubmitBtn" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 flex items-center space-x-2">
                        <i class="fa-solid fa-bolt text-amber-300"></i>
                        <span>Add TV Device</span>
                    </button>
                </div>
            </form>

            <!-- 2. Pair TV Form (With 8-Digit code) -->
            <div id="pairModeWrapper" class="hidden space-y-4">
                <!-- Sub Tab: Manual Code vs Camera Scanner -->
                <div class="flex items-center p-1 bg-slate-100/80 rounded-xl text-[11px]">
                    <button type="button" id="tabManualBtn" onclick="switchPairSubTab('manual')" class="flex-1 py-1.5 font-bold rounded-lg bg-white text-indigo-600 shadow-2xs transition-all flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-keyboard text-[10px]"></i>
                        <span>Type Code</span>
                    </button>
                    <button type="button" id="tabScanBtn" onclick="switchPairSubTab('scan')" class="flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                        <span>Scan TV QR</span>
                    </button>
                </div>

                <!-- Camera Scanner Box -->
                <div id="qrScannerBox" class="hidden space-y-2 text-center">
                    <div id="qrReader" class="w-full overflow-hidden rounded-2xl border-2 border-indigo-500/30 bg-slate-950 aspect-square flex items-center justify-center"></div>
                    <p class="text-[11px] text-slate-500 font-medium">Point your camera at the QR code displayed on TV screen</p>
                </div>

                <form id="pairForm" onsubmit="submitPairForm(event)" class="space-y-4">
                    @csrf
                    <div id="pairCodeInputWrapper" class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">8-Digit Pairing Code (Shown on TV Screen) <span class="text-rose-500">*</span></label>
                        <input type="text" id="pairCodeInput" required placeholder="e.g. 8F2A-9K3P" maxlength="10" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono font-extrabold text-lg tracking-widest text-indigo-600 uppercase focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Assign Room Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="roomNoInput" required placeholder="e.g. 104" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-indigo-500">
                    </div>

                    <div id="pairFormAlert" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                    <div class="pt-2 flex items-center justify-end space-x-3">
                        <button type="button" onclick="closePairModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                        <button type="submit" id="pairSubmitBtn" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 flex items-center space-x-2">
                            <span>Connect & Pair TV</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop & Tablet Devices Table (Note: Disconnect action removed) -->
    <div class="hidden md:block bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/90 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-4 w-12 text-center">#</th>
                        <th class="px-5 py-4">Room & Guest</th>
                        <th class="px-5 py-4">Hardware & OS</th>
                        <th class="px-5 py-4">Device & Network</th>
                        <th class="px-5 py-4">Connected At</th>
                        <th class="px-5 py-4 text-center">Room Config</th>
                        <th class="px-5 py-4 text-center">Details</th>
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
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 text-center font-bold text-slate-400">
                                {{ $devices->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4">
                                @if($activeGuest = $activeGuests->get($device->room_no))
                                    <div>
                                        <a href="{{ route('hotel.guests.index') }}?room={{ urlencode($device->room_no) }}" class="font-extrabold text-indigo-600 hover:underline">
                                             Room {{ $device->room_no }}
                                        </a>
                                    </div>
                                    <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 mt-0.5">
                                        <i class="fa-solid fa-user mr-1 text-[9px]"></i> {{ $activeGuest->name }}
                                    </span>
                                @else
                                    <div class="font-extrabold text-slate-800">Room {{ $device->room_no }}</div>
                                    <span class="text-[11px] text-slate-400 italic">Vacant</span>
                                @endif
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
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('hotel.devices.ott', $device->id) }}" class="px-2.5 py-1.5 rounded-xl border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold text-[11px] transition-colors" title="Manage OTT Apps">
                                        <i class="fa-solid fa-sliders mr-1"></i> OTT
                                    </a>
                                    <a href="{{ route('hotel.devices.menus', $device->id) }}" class="px-2.5 py-1.5 rounded-xl border border-violet-200 text-violet-600 hover:bg-violet-50 font-bold text-[11px] transition-colors" title="Manage TV Menus">
                                        <i class="fa-solid fa-list-check mr-1"></i> Menus
                                    </a>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 hover:text-indigo-600 font-bold text-xs transition-all shadow-2xs" title="View Details">
                                        <i class="fa-solid fa-eye text-indigo-500"></i>
                                        <span>View</span>
                                    </button>
                                    <button type="button" onclick="confirmDisconnect('{{ route('hotel.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }}')" class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs transition-colors shadow-2xs" title="Disconnect TV">
                                        <i class="fa-solid fa-power-off text-rose-500"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 mx-auto rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No Connected TVs Found</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Pair your TV screen using the 8-digit code to link room devices.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View (No remove actions) -->
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
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="min-w-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-200/80 text-indigo-700 font-extrabold text-[11px] mb-1">
                            <i class="fa-solid fa-door-closed mr-1 text-indigo-500 text-[10px]"></i>
                            Room {{ $device->room_no }}
                        </span>
                        @if($activeGuest = $activeGuests->get($device->room_no))
                            <div class="text-xs font-bold text-slate-900 truncate">
                                <i class="fa-solid fa-user text-emerald-500 text-[10px] mr-1"></i> {{ $activeGuest->name }}
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400 italic">Vacant Room</div>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Online
                    </span>
                </div>

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

                <!-- Action Controls: Config OTT/Menus, Details & Disconnect -->
                <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                    <a href="{{ route('hotel.devices.ott', $device->id) }}" class="flex-1 py-2 px-2 rounded-xl border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold text-xs flex items-center justify-center space-x-1 transition-colors">
                        <i class="fa-solid fa-sliders text-[11px]"></i>
                        <span>OTT</span>
                    </a>
                    <a href="{{ route('hotel.devices.menus', $device->id) }}" class="flex-1 py-2 px-2 rounded-xl border border-violet-200 text-violet-600 hover:bg-violet-50 font-bold text-xs flex items-center justify-center space-x-1 transition-colors">
                        <i class="fa-solid fa-list-check text-[11px]"></i>
                        <span>Menus</span>
                    </a>
                    <button type="button" onclick="showDeviceDetails({{ json_encode($devicePayload) }})" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 hover:text-indigo-600 font-bold text-xs flex items-center justify-center transition-all" title="View Details">
                        <i class="fa-solid fa-eye text-indigo-500"></i>
                    </button>
                    <button type="button" onclick="confirmDisconnect('{{ route('hotel.devices.destroy', $device->id) }}', 'Room {{ $device->room_no }}')" class="py-2 px-3 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs flex items-center justify-center transition-colors" title="Disconnect TV">
                        <i class="fa-solid fa-power-off text-rose-500"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-2">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <h4 class="text-xs font-extrabold text-slate-800">No TV Screens Found</h4>
                <p class="text-[11px] text-slate-400 mt-1">Pair your TV screen with an 8-digit code to connect.</p>
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
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 leading-tight truncate">Device & License Details</h3>
                    <p id="modalSubtitle" class="text-[11px] sm:text-xs text-slate-500 font-semibold truncate mt-0.5">Room 101 • Device Specs</p>
                </div>
            </div>
            <!-- Top Actions: Disconnect button (desktop) & Close button -->
            <div class="flex items-center space-x-1.5 shrink-0">
                <button type="button" onclick="disconnectFromModal()" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition-all shadow-2xs" title="Disconnect this TV">
                    <i class="fa-solid fa-power-off text-xs"></i>
                    <span>Disconnect</span>
                </button>
                <button type="button" onclick="closeDeviceDetailsModal()" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors" title="Close">
                    <i class="fa-solid fa-xmark text-base sm:text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Modal Content (touch scroll enabled) -->
        <div class="px-4 py-4 sm:px-6 sm:py-5 overflow-y-auto overscroll-contain flex-1 min-h-0 space-y-3.5 sm:space-y-4" style="-webkit-overflow-scrolling: touch;">
            <!-- License Key Highlight Box -->
            <div class="p-3.5 sm:p-5 rounded-2xl bg-gradient-to-br from-indigo-50/80 via-violet-50/60 to-purple-50/40 border border-indigo-200/80 space-y-2.5 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-[10px]"></i>
                        <span>Hotel License Key</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md bg-indigo-600/10 text-indigo-700 text-[10px] font-bold">Authorized</span>
                </div>
                <div class="flex items-center justify-between gap-2 bg-white/90 rounded-xl p-2.5 border border-indigo-200/60 shadow-2xs">
                    <span id="modalLicenseKey" class="font-mono font-black text-xs sm:text-base text-indigo-900 tracking-wide select-all truncate">
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
                        <span id="modalKeyHotelName" class="font-bold text-slate-900 truncate max-w-[200px] sm:max-w-[240px]">---</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>
                            <span class="text-slate-400 font-medium">Owner / Contact:</span>
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

                <p class="text-[10px] text-indigo-600/80 font-medium leading-relaxed">Hotel license key used to authenticate connected Smart TVs in your property.</p>
            </div>

            <!-- Device Technical Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-0.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Hotel Name</div>
                    <div id="modalHotelName" class="font-extrabold text-slate-900 truncate">---</div>
                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>License Active</span>
                    </div>
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

            <!-- Disconnect Device Guidance -->
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-slate-600 text-[11px] flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-indigo-500 text-xs mt-0.5 shrink-0"></i>
                <p>Disconnecting will unpair this TV immediately from the room. To reconnect, re-pair using the 8-digit code or hotel license key on screen.</p>
            </div>
        </div>

        <!-- Modal Footer (Always visible and pinned) -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 border-t border-slate-100 flex items-center justify-between space-x-2.5 shrink-0 bg-slate-50/80">
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

<script>
    let html5QrCode = null;
    let qrScriptLoaded = false;

    function openAddDeviceModal(mode = 'quick') {
        document.body.style.overflow = 'hidden';
        document.body.classList.add('overflow-hidden');
        document.getElementById('pairModal').classList.remove('hidden');
        switchModalMode(mode);
    }

    // Keep backwards compatibility for any openPairModal calls
    function openPairModal() {
        openAddDeviceModal('quick');
    }

    function closePairModal() {
        stopScanner();
        document.getElementById('pairModal').classList.add('hidden');
        document.getElementById('quickFormAlert').classList.add('hidden');
        document.getElementById('pairFormAlert').classList.add('hidden');
        document.getElementById('quickAddForm').reset();
        document.getElementById('pairForm').reset();
        document.body.style.overflow = '';
        document.body.classList.remove('overflow-hidden');
    }

    function closePairModalOnBackdrop(event) {
        if (event.target.id === 'pairModal') {
            closePairModal();
        }
    }

    function switchModalMode(mode) {
        const quickBtn = document.getElementById('tabQuickBtn');
        const pairBtn = document.getElementById('tabPairBtn');
        const quickForm = document.getElementById('quickAddForm');
        const pairWrapper = document.getElementById('pairModeWrapper');
        const title = document.getElementById('deviceModalTitle');
        const subtitle = document.getElementById('deviceModalSubtitle');

        if (mode === 'pair') {
            pairBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl bg-white text-indigo-600 shadow-sm transition-all flex items-center justify-center space-x-1.5';
            quickBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            quickForm.classList.add('hidden');
            pairWrapper.classList.remove('hidden');
            title.innerText = 'Pair TV Screen';
            subtitle.innerText = 'Connect TV via 8-digit screen code';
            switchPairSubTab('manual');
        } else {
            quickBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl bg-white text-indigo-600 shadow-sm transition-all flex items-center justify-center space-x-1.5';
            pairBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            pairWrapper.classList.add('hidden');
            quickForm.classList.remove('hidden');
            title.innerText = 'Add TV Device';
            subtitle.innerText = 'Quick provision room television';
            stopScanner();
            setTimeout(() => {
                document.getElementById('quickRoomNo')?.focus();
            }, 50);
        }
    }

    function switchPairSubTab(tab) {
        const manualBtn = document.getElementById('tabManualBtn');
        const scanBtn = document.getElementById('tabScanBtn');
        const qrBox = document.getElementById('qrScannerBox');
        const codeInputWrapper = document.getElementById('pairCodeInputWrapper');

        if (tab === 'scan') {
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-indigo-600 shadow-2xs transition-all flex items-center justify-center space-x-1.5';
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.remove('hidden');
            codeInputWrapper.classList.add('hidden');
            startScanner();
        } else {
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-indigo-600 shadow-2xs transition-all flex items-center justify-center space-x-1.5';
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.add('hidden');
            codeInputWrapper.classList.remove('hidden');
            stopScanner();
            document.getElementById('pairCodeInput')?.focus();
        }
    }

    function ensureQrScriptLoaded(callback) {
        if (typeof Html5Qrcode !== 'undefined') {
            callback();
            return;
        }
        if (qrScriptLoaded) {
            let interval = setInterval(() => {
                if (typeof Html5Qrcode !== 'undefined') {
                    clearInterval(interval);
                    callback();
                }
            }, 50);
            return;
        }
        qrScriptLoaded = true;
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.onload = callback;
        script.onerror = () => {
            const alertBox = document.getElementById('pairFormAlert');
            if (alertBox) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800';
                alertBox.innerText = 'Camera scanner script unavailable. Please enter code manually.';
                alertBox.classList.remove('hidden');
            }
        };
        document.head.appendChild(script);
    }

    function startScanner() {
        ensureQrScriptLoaded(() => {
            if (html5QrCode && html5QrCode.isScanning) return;

            html5QrCode = new Html5Qrcode("qrReader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 220, height: 220 } },
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
            ).catch((err) => {
                const alertBox = document.getElementById('pairFormAlert');
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800';
                alertBox.innerText = 'Camera access denied or unavailable. Please enter code manually.';
                alertBox.classList.remove('hidden');
            });
        });
    }

    function stopScanner() {
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

    // ⚡ Fast Quick Add Form Submission
    async function submitQuickAddForm(event) {
        event.preventDefault();
        const roomNo = document.getElementById('quickRoomNo').value.trim();
        const brand = document.getElementById('quickBrand').value;
        const model = document.getElementById('quickModel').value.trim();
        const alertBox = document.getElementById('quickFormAlert');
        const submitBtn = document.getElementById('quickSubmitBtn');

        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Adding TV Device...';

        try {
            const response = await fetch("{{ route('hotel.devices.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    room_no: roomNo,
                    brand: brand,
                    model: model
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> ' + data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
                alertBox.innerText = data.message || 'Failed to add TV device. Please check room number.';
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-bolt text-amber-300 mr-2"></i> Add TV Device';
            }
        } catch (err) {
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-bolt text-amber-300 mr-2"></i> Add TV Device';
        }
    }

    // 📱 Pair Screen Form Submission
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
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> ' + data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800 space-y-2';
                let msgHtml = `<div>${data.message || 'Pairing failed. Please check the code.'}</div>`;
                if (roomNo) {
                    msgHtml += `
                        <div class="pt-1 border-t border-rose-200/80">
                            <button type="button" onclick="quickRegisterFromPair('${roomNo}')" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center space-x-1.5">
                                <i class="fa-solid fa-bolt text-amber-300"></i>
                                <span>Add Room ${roomNo} Directly Now</span>
                            </button>
                        </div>
                    `;
                }
                alertBox.innerHTML = msgHtml;
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Connect & Pair TV</span>';
            }
        } catch (err) {
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Connect & Pair TV</span>';
        }
    }

    // Direct fallback from pair tab to instant quick registration
    function quickRegisterFromPair(roomNo) {
        switchModalMode('quick');
        document.getElementById('quickRoomNo').value = roomNo;
        const fakeEvent = new Event('submit', { cancelable: true });
        submitQuickAddForm(fakeEvent);
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
