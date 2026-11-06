@extends('layouts.hotel_admin')

@section('title', 'Hotel Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">

    <!-- Top Welcome Banner (PaxTV Luxury Midnight Theme) -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-12 w-48 h-48 bg-purple-50/40 rounded-full blur-2xl pointer-events-none"></div>

        <div class="space-y-2.5 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Hospitality Network</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-600 font-bold">{{ $hotel->hotel_name }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Welcome back, {{ Auth::guard('hotel_admin')->user()->owner_name }}! 👋
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl font-medium leading-relaxed">
                Centralized control hub for in-room Smart TV experiences, active license subscriptions, guest check-ins, and interactive TV menus.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 shrink-0">
            <a href="{{ route('hotel.devices.index') }}" 
               class="px-5 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-tv text-xs text-amber-300"></i>
                <span>Connected TVs</span>
            </a>
            <a href="{{ route('hotel.subscription') }}" 
               class="px-4 py-3.5 rounded-2xl bg-purple-50 hover:bg-purple-100/90 text-[#150A42] border border-purple-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-credit-card text-xs text-purple-600"></i>
                <span>Plan & Billing</span>
            </a>
            <a href="{{ route('hotel.hotel-info') }}" 
               class="px-4 py-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/90 text-slate-700 border border-slate-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-gear text-xs text-slate-500"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Grid: Unified, High-Impact & Cohesive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Connected TVs -->
        @php
            $connectedCount = $deviceCount ?? 0;
            $allowedLimit = $hotel->allowed_device_limit ?? 0;
            $usagePercent = $allowedLimit > 0 ? min(100, round(($connectedCount / $allowedLimit) * 100)) : 0;
        @endphp
        <div class="bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-28 h-28 bg-purple-50/80 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">TV Hardware Slots</span>
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100/80 text-[#150A42] flex items-center justify-center text-lg shadow-2xs group-hover:scale-105 transition-transform shrink-0">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-2 tracking-tight">
                    {{ $connectedCount }} <span class="text-lg font-bold text-slate-400">/ {{ $allowedLimit }} TVs</span>
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-slate-100 space-y-2">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-semibold">{{ $usagePercent }}% Synchronized</span>
                    <a href="{{ route('hotel.devices.index') }}" class="font-black text-purple-700 hover:text-purple-950 underline underline-offset-2">Manage ↗</a>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-600 to-[#150A42] rounded-full transition-all duration-500" style="width: {{ $usagePercent }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Card 2: Active Plan Tier -->
        <div class="bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-28 h-28 bg-amber-50/80 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Active License Plan</span>
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-100/80 text-amber-600 flex items-center justify-center text-lg shadow-2xs group-hover:scale-105 transition-transform shrink-0">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2 tracking-tight truncate">
                    {{ $hotel->plan ? $hotel->plan->name : 'No Active Plan' }}
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                @if($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <span class="inline-flex items-center text-rose-600 font-bold text-[11px]">
                        <i class="fa-solid fa-circle-exclamation text-[10px] mr-1"></i> Expired
                    </span>
                @elseif($hotel->expiry_date && $hotel->expiry_date->diffInDays(now()) <= 7)
                    <span class="inline-flex items-center text-amber-600 font-bold text-[11px]">
                        <i class="fa-solid fa-clock text-[10px] mr-1"></i> Expiring Soon
                    </span>
                @else
                    <span class="inline-flex items-center text-emerald-600 font-bold text-[11px]">
                        <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Active License
                    </span>
                @endif
                <a href="{{ route('hotel.subscription') }}" class="text-[11px] font-black text-purple-700 hover:text-purple-950 underline underline-offset-2">Upgrade ↗</a>
            </div>
        </div>

        <!-- Card 3: In-House Guests -->
        <div class="bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-28 h-28 bg-emerald-50/80 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">In-House Guests</span>
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100/80 text-emerald-600 flex items-center justify-center text-lg shadow-2xs group-hover:scale-105 transition-transform shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600 mt-2 tracking-tight">
                    {{ $guestCount ?? 0 }}
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">Active Checked-In</span>
                <a href="{{ route('hotel.guests.index') }}" class="font-black text-purple-700 hover:text-purple-950 underline underline-offset-2">Guests ↗</a>
            </div>
        </div>

        <!-- Card 4: TV System Authentication License Key -->
        <div class="bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-28 h-28 bg-blue-50/80 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Hardware License Key</span>
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-100/80 text-blue-600 flex items-center justify-center text-lg shadow-2xs group-hover:scale-105 transition-transform shrink-0">
                        <i class="fa-solid fa-key"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 mt-2 font-mono tracking-tight truncate">
                    {{ $hotel->license_key ?? 'N/A' }}
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">TV Pairing Token</span>
                @if($hotel->license_key)
                    <button type="button" 
                            onclick="copyLicenseKey('{{ $hotel->license_key }}')" 
                            class="inline-flex items-center space-x-1 text-purple-700 hover:text-purple-950 font-black cursor-pointer">
                        <i class="fa-regular fa-copy text-[10px]"></i>
                        <span>Copy</span>
                    </button>
                @endif
            </div>
        </div>

    </div>

    <!-- Subscription Timeline & Renewal Status Strip -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-100 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="space-y-1">
                <div class="flex items-center space-x-3">
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Subscription Validity & Timeline</h3>
                    @if($hotel->expiry_date)
                        @if($hotel->expiry_date->isPast())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-200">Expired</span>
                        @elseif($hotel->expiry_date->diffInDays(now()) <= 7)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200">Expires Soon</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                        @endif
                    @endif
                </div>
                <p class="text-xs text-slate-400 font-medium">Detailed countdown and renewal window for your hotel hardware licensing.</p>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('hotel.subscription') }}" 
                   class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs transition-all active:scale-95 shadow-md shadow-[#150A42]/15">
                    <i class="fa-solid fa-arrows-rotate mr-1.5 text-amber-300"></i>
                    <span>Renew / Add TV Slots</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 border border-slate-200/60 space-y-1.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Purchase Activation Date</span>
                <p class="text-sm font-black text-slate-900 flex items-center space-x-2">
                    <i class="fa-regular fa-calendar-check text-purple-600"></i>
                    <span>{{ $hotel->purchase_date ? $hotel->purchase_date->format('M d, Y') : 'N/A' }}</span>
                </p>
            </div>

            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 border border-slate-200/60 space-y-1.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">License Expiry Date</span>
                <p class="text-sm font-black text-slate-900 flex items-center space-x-2">
                    <i class="fa-regular fa-clock text-indigo-600"></i>
                    <span>{{ $hotel->expiry_date ? $hotel->expiry_date->format('M d, Y') : 'N/A' }}</span>
                </p>
            </div>

            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 border border-slate-200/60 space-y-1.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Remaining Window</span>
                @if($hotel->expiry_date)
                    @if($hotel->expiry_date->isPast())
                        <p class="text-sm font-black text-rose-600 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Plan Expired (Services Halted)</span>
                        </p>
                    @else
                        <p class="text-sm font-black text-emerald-600 flex items-center space-x-2">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>{{ ceil(now()->diffInHours($hotel->expiry_date) / 24) }} Days Remaining</span>
                        </p>
                    @endif
                @else
                    <p class="text-sm font-black text-slate-400">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Navigation & Operations Command Center -->
    <div>
        <div class="mb-4">
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Hotel Experience & CMS Controls</h3>
            <p class="text-xs text-slate-400 font-medium">Quick links to update in-room TV experience, amenities, and guest services.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- OTT Apps -->
            <a href="{{ route('hotel.package') }}" class="group bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl group-hover:scale-105 transition-transform shadow-2xs">
                        <i class="fa-brands fa-google-play"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">OTT Apps Control</h4>
                        <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Toggle streaming apps (Netflix, Prime, YouTube) for TV screens.</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-purple-700 group-hover:text-purple-950">
                    <span>Manage Apps</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- TV Menus Visibility -->
            <a href="{{ route('hotel.menus.index') }}" class="group bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-105 transition-transform shadow-2xs">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">TV Menus Visibility</h4>
                        <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Customize navigation tiles and services displayed on TV.</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600 group-hover:text-blue-900">
                    <span>Manage Menus</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Hotel Amenities -->
            <a href="{{ route('hotel.amenities.index') }}" class="group bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-105 transition-transform shadow-2xs">
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">Hotel Amenities</h4>
                        <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Customize in-room amenities, icons, images and sort order.</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600 group-hover:text-emerald-900">
                    <span>Manage Amenities</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Room Information -->
            <a href="{{ route('hotel.room-infos.index') }}" class="group bg-white rounded-[26px] p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-105 transition-transform shadow-2xs">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">Room Information</h4>
                        <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Customize room guides, intercoms, WiFi cards and photos.</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-600 group-hover:text-amber-900">
                    <span>Manage Rooms</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        </div>
    </div>

</div>

<!-- Floating Copy Notification Pill -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 bg-slate-900/95 text-white px-4 py-2.5 rounded-2xl shadow-xl text-xs font-bold flex items-center space-x-2 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="copyToastText">Copied to clipboard!</span>
</div>

<script>
    function copyLicenseKey(key) {
        if (!navigator.clipboard) {
            const temp = document.createElement('textarea');
            temp.value = key;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToast('License Key copied: ' + key);
            return;
        }

        navigator.clipboard.writeText(key).then(() => {
            showToast('License Key copied: ' + key);
        }).catch(() => {
            showToast('License Key copied: ' + key);
        });
    }

    function showToast(text) {
        const toast = document.getElementById('copyToast');
        const toastText = document.getElementById('copyToastText');
        toastText.textContent = text;
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }, 2200);
    }
</script>
@endsection
