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
                    {{ $deviceCount ?? 0 }} <span class="text-lg font-bold text-slate-400">/ {{ $allowedLimit ?? 0 }} TVs</span>
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-slate-100 space-y-2">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-semibold">{{ $usagePercent ?? 0 }}% Synchronized</span>
                    <a href="{{ route('hotel.devices.index') }}" class="font-black text-purple-700 hover:text-purple-950 underline underline-offset-2">Manage ↗</a>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-600 to-[#150A42] rounded-full transition-all duration-500" style="width: {{ $usagePercent ?? 0 }}%;"></div>
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
                @elseif($hotel->expiry_date && $daysRemaining <= 4 && $daysRemaining > 0)
                    <span class="inline-flex items-center text-amber-600 font-bold text-[11px]">
                        <i class="fa-solid fa-clock text-[10px] mr-1"></i> Expiring Soon ({{ $daysRemaining }}d left)
                    </span>
                @else
                    <span class="inline-flex items-center text-emerald-600 font-bold text-[11px]">
                        <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Active License @if($daysRemaining > 0)({{ $daysRemaining }}d left)@endif
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
                <span class="text-slate-500 font-semibold">{{ $totalCheckInsThisMonth ?? 0 }} this month</span>
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

    <!-- Interactive Visual Analytics & Operational Graphs (Modern Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Chart 1: 7-Day Guest Check-ins & Activity Flow (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Guest Check-In Activity</h3>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-1">Daily guest arrivals and in-room TV experience activations (Last 7 Days).</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-xl bg-purple-50 text-purple-800 font-extrabold text-xs border border-purple-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mr-1.5"></span>
                        Weekly Total: {{ array_sum($checkInCounts ?? []) }}
                    </span>
                    <a href="{{ route('hotel.guests.create') }}" class="px-3.5 py-1.5 rounded-xl bg-[#150A42] hover:bg-[#200f60] text-white font-extrabold text-xs transition-all active:scale-95 shadow-xs">
                        <i class="fa-solid fa-user-plus text-[10px] mr-1"></i> New Guest
                    </a>
                </div>
            </div>

            <!-- Canvas wrapper -->
            <div class="relative w-full h-64 sm:h-72 mt-5">
                <canvas id="guestCheckInChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: TV Hardware Capacity & Sync Ratio (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="border-b border-slate-100 pb-5">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">TV Slot Allocation</h3>
                </div>
                <p class="text-xs text-slate-400 font-medium mt-1">Paired in-room Smart TVs vs unassigned hardware capacity.</p>
            </div>

            <!-- Doughnut Chart wrapper with Centered KPI -->
            <div class="relative w-full h-52 sm:h-56 my-3 flex items-center justify-center">
                <canvas id="deviceCapacityChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $usagePercent ?? 0 }}%</span>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Allocated</span>
                </div>
            </div>

            <!-- Custom Legend Breakdown -->
            <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/60">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#150A42]"></span>
                        <span class="text-[11px] font-bold text-slate-600">Paired TVs</span>
                    </div>
                    <p class="text-lg font-black text-[#150A42] mt-1">{{ $deviceCount ?? 0 }} <span class="text-[11px] font-bold text-slate-400">units</span></p>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                        <span class="text-[11px] font-bold text-slate-600">Available</span>
                    </div>
                    <p class="text-lg font-black text-slate-800 mt-1">{{ $availableSlots ?? 0 }} <span class="text-[11px] font-bold text-slate-400">slots</span></p>
                </div>
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
                        @elseif($daysRemaining <= 4 && $daysRemaining > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200">Expires Soon ({{ $daysRemaining }}d left)</span>
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
                    @elseif($daysRemaining <= 4 && $daysRemaining > 0)
                        <p class="text-sm font-black text-amber-600 flex items-center space-x-2">
                            <i class="fa-solid fa-clock"></i>
                            <span>{{ $daysRemaining }} Days Remaining (Expiring Soon)</span>
                        </p>
                    @else
                        <p class="text-sm font-black text-emerald-600 flex items-center space-x-2">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>{{ $daysRemaining }} Days Remaining</span>
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
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">OTT Apps Control</h4>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-purple-100/70 text-purple-800">{{ $ottAppsCount ?? 0 }} Live</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1.5 leading-relaxed">Toggle streaming apps (Netflix, Prime, YouTube) for TV screens.</p>
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
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">TV Menus Visibility</h4>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-blue-100/70 text-blue-800">Dynamic</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1.5 leading-relaxed">Customize navigation tiles and services displayed on TV.</p>
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
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">Hotel Amenities</h4>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-emerald-100/70 text-emerald-800">{{ $amenitiesCount ?? 0 }} Items</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1.5 leading-relaxed">Customize in-room amenities, icons, images and sort order.</p>
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
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-black text-slate-900 group-hover:text-purple-900 transition-colors">Room Information</h4>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-100/70 text-amber-800">{{ $roomsConfigured ?? 0 }} Rooms</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1.5 leading-relaxed">Customize room guides, intercoms, WiFi cards and photos.</p>
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

<!-- Chart.js Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Global Chart Defaults
        Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#64748B';

        // 1. Guest Check-in Activity Flow Chart (Area / Line)
        const checkInCtx = document.getElementById('guestCheckInChart');
        if (checkInCtx) {
            const labels = @json($checkInLabels ?? []);
            const counts = @json($checkInCounts ?? []);

            const gradient = checkInCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(109, 40, 217, 0.22)');
            gradient.addColorStop(1, 'rgba(109, 40, 217, 0.00)');

            new Chart(checkInCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Guest Check-Ins',
                        data: counts,
                        borderColor: '#6D28D9',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.38,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#6D28D9',
                        pointBorderWidth: 2.5,
                        pointRadius: 4.5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#150A42',
                        pointHoverBorderColor: '#FFFFFF',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleColor: '#FFFFFF',
                            bodyColor: '#E2E8F0',
                            padding: 12,
                            cornerRadius: 14,
                            displayColors: false,
                            titleFont: { size: 12, weight: '700' },
                            bodyFont: { size: 12, weight: '600' },
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Guest Check-In' + (context.parsed.y === 1 ? '' : 's');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' }, color: '#94A3B8' }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 5,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 11, weight: '600' },
                                color: '#94A3B8'
                            },
                            grid: {
                                color: 'rgba(241, 245, 249, 0.9)',
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }

        // 2. TV Hardware Capacity Doughnut Chart
        const capacityCtx = document.getElementById('deviceCapacityChart');
        if (capacityCtx) {
            const paired = {{ (int) ($deviceCount ?? 0) }};
            const available = {{ (int) ($availableSlots ?? 0) }};
            const total = {{ (int) ($allowedLimit ?? 0) }};

            // Fallback for zero slots configured
            const chartData = (total === 0 && paired === 0) ? [0, 1] : [paired, available];
            const bgColors = (total === 0 && paired === 0) ? ['#150A42', '#E2E8F0'] : ['#150A42', '#EDE9FE'];

            new Chart(capacityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paired TVs', 'Available TV Slots'],
                    datasets: [{
                        data: chartData,
                        backgroundColor: bgColors,
                        borderWidth: 3,
                        borderColor: '#FFFFFF',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleColor: '#FFFFFF',
                            bodyColor: '#E2E8F0',
                            padding: 10,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' units';
                                }
                            }
                        }
                    }
                }
            });
        }
    });

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
