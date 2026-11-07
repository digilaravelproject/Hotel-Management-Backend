@extends('layouts.hotel_admin')

@section('title', 'Room ' . $device->room_no . ' Menu Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' Menu Configuration')

@section('content')
@php
    $menuMeta = [
        'hotel_menu' => [
            'category' => 'services',
            'category_name' => 'Hotel Services',
            'desc' => 'Primary directory of hotel services, dining catalog & guest amenities',
            'icon' => 'fa-solid fa-list-check',
            'color' => 'from-purple-600 to-indigo-700',
            'bg' => 'bg-purple-50 text-purple-700 border-purple-200/70',
            'internet' => false
        ],
        'hotel_info' => [
            'category' => 'services',
            'category_name' => 'Hotel Services',
            'desc' => 'Property overview, contacts, operational hours & policies',
            'icon' => 'fa-solid fa-hotel',
            'color' => 'from-indigo-600 to-blue-700',
            'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200/70',
            'internet' => false
        ],
        'room_info' => [
            'category' => 'services',
            'category_name' => 'Hotel Services',
            'desc' => 'Room features, Wi-Fi credentials, intercom & room guide',
            'icon' => 'fa-solid fa-door-open',
            'color' => 'from-emerald-600 to-teal-700',
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
            'internet' => false
        ],
        'amenities' => [
            'category' => 'services',
            'category_name' => 'Hotel Services',
            'desc' => 'Swimming pool, spa, fitness center, lounge & facilities',
            'icon' => 'fa-solid fa-bell-concierge',
            'color' => 'from-amber-500 to-amber-700',
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200/70',
            'internet' => false
        ],
        'interactive_services' => [
            'category' => 'services',
            'category_name' => 'Hotel Services',
            'desc' => 'In-room dining orders, housekeeping requests & digital concierge',
            'icon' => 'fa-solid fa-handshake-angle',
            'color' => 'from-cyan-600 to-blue-700',
            'bg' => 'bg-cyan-50 text-cyan-700 border-cyan-200/70',
            'internet' => false
        ],
        'flights' => [
            'category' => 'data',
            'category_name' => 'Live Cloud Data',
            'desc' => 'Live airport departure & arrival radar schedules for travelers',
            'icon' => 'fa-solid fa-plane-departure',
            'color' => 'from-sky-500 to-blue-600',
            'bg' => 'bg-sky-50 text-sky-700 border-sky-200/70',
            'internet' => true,
            'tooltip' => 'Active Internet connection is required on the Smart TV screen to fetch live airport flight status in real time.'
        ],
        'weather' => [
            'category' => 'data',
            'category_name' => 'Live Cloud Data',
            'desc' => 'Real-time city temperature, hourly forecast and weekly outlook',
            'icon' => 'fa-solid fa-cloud-sun',
            'color' => 'from-amber-500 to-orange-600',
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200/70',
            'internet' => true,
            'tooltip' => 'Active Internet connection is required on the Smart TV screen to fetch live city meteorological forecasts.'
        ],
        'ourcity' => [
            'category' => 'data',
            'category_name' => 'Live Cloud Data',
            'desc' => 'Handpicked city attractions, local shopping & neighborhood guide',
            'icon' => 'fa-solid fa-city',
            'color' => 'from-teal-600 to-emerald-700',
            'bg' => 'bg-teal-50 text-teal-700 border-teal-200/70',
            'internet' => false
        ],
        'livetv' => [
            'category' => 'entertainment',
            'category_name' => 'Media & TV',
            'desc' => 'Linear cable, satellite channels & broadcast television',
            'icon' => 'fa-solid fa-tv',
            'color' => 'from-rose-600 to-red-700',
            'bg' => 'bg-rose-50 text-rose-700 border-rose-200/70',
            'internet' => false
        ],
        'apps' => [
            'category' => 'entertainment',
            'category_name' => 'Media & TV',
            'desc' => 'Preloaded OTT platforms, entertainment apps & video players',
            'icon' => 'fa-solid fa-shapes',
            'color' => 'from-violet-600 to-purple-800',
            'bg' => 'bg-violet-50 text-violet-700 border-violet-200/70',
            'internet' => false
        ],
        'screen_cast' => [
            'category' => 'entertainment',
            'category_name' => 'Media & TV',
            'desc' => 'Wireless display mirroring from guest mobile phones & laptops',
            'icon' => 'fa-solid fa-mobile-screen-button',
            'color' => 'from-fuchsia-600 to-pink-700',
            'bg' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/70',
            'internet' => false
        ],
        'language' => [
            'category' => 'system',
            'category_name' => 'System & Controls',
            'desc' => 'Multi-lingual TV interface localization for international guests',
            'icon' => 'fa-solid fa-language',
            'color' => 'from-blue-600 to-indigo-700',
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200/70',
            'internet' => false
        ],
        'input' => [
            'category' => 'system',
            'category_name' => 'System & Controls',
            'desc' => 'Quick switch between HDMI 1, HDMI 2, AV or USB input sources',
            'icon' => 'fa-solid fa-right-to-bracket',
            'color' => 'from-slate-600 to-slate-800',
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200/70',
            'internet' => false
        ],
        'settings' => [
            'category' => 'system',
            'category_name' => 'System & Controls',
            'desc' => 'Display, sound, Wi-Fi connectivity & TV system controls',
            'icon' => 'fa-solid fa-gear',
            'color' => 'from-slate-700 to-zinc-800',
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200/70',
            'internet' => false
        ],
    ];

    $totalCount = count($catalog);
    $activeCount = 0;
    foreach($catalog as $mId => $mMeta) {
        if (!isset($currentSettings[$mId]) || $currentSettings[$mId] !== 'hide') {
            $activeCount++;
        }
    }
@endphp

<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.devices.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Connected TVs</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Room {{ $device->room_no }} Menus</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Room {{ $device->room_no }} Navigation Menus</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Control which TV menu items appear on the Smart TV launcher for Room {{ $device->room_no }}.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if($hasOverride)
                <form action="{{ route('hotel.devices.menus.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default Menu settings?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-2xl border border-amber-200 text-amber-800 bg-amber-50 hover:bg-amber-100 font-black text-xs transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer active:scale-95">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset to Default</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('hotel.devices.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Connected TVs</span>
            </a>
        </div>
    </div>

    <!-- Hero Room Specs & Status Banner (PaxTV Luxury Theme) -->
    <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[28px] p-5 sm:p-7 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-start sm:items-center space-x-3.5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-2xs shrink-0">
                <i class="fa-solid fa-bars-staggered text-purple-700"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl bg-purple-50 text-[#150A42] font-black text-xs border border-purple-200/60 shadow-2xs">
                        <i class="fa-solid fa-door-closed mr-1.5 text-purple-600 text-[10px]"></i>
                        Room {{ $device->room_no }}
                    </span>
                    @if($device->brand || $device->model)
                        <span class="text-xs font-bold text-slate-600">{{ $device->brand }} {{ $device->model }}</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 font-mono">
                    ID: {{ $device->device_id }} @if($device->mac_address)• MAC: {{ $device->mac_address }}@endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 relative z-10 shrink-0">
            @if($hasOverride)
                <span class="inline-flex items-center px-3 py-1.5 rounded-2xl bg-amber-50 text-amber-800 text-xs font-black border border-amber-200 shadow-2xs">
                    <i class="fa-solid fa-sliders mr-1.5 text-xs text-amber-600"></i> Custom Room Override Active
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-2xl bg-purple-50 text-[#150A42] text-xs font-black border border-purple-200/80 shadow-2xs">
                    <i class="fa-solid fa-globe mr-1.5 text-xs text-purple-600"></i> Inheriting Hotel Global Default
                </span>
            @endif

            <div class="px-3.5 py-1.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                <span id="selectedCounterPill" class="font-black text-purple-700">{{ $activeCount }}</span> / {{ $totalCount }} Menus Active
            </div>
        </div>
    </div>

    <!-- Important Notice Banner: Flights & Weather Internet Requirement -->
    <div class="bg-gradient-to-r from-amber-500/10 via-amber-400/5 to-transparent border border-amber-300/80 rounded-2xl sm:rounded-3xl p-4 sm:p-5 flex items-start sm:items-center justify-between gap-4 shadow-2xs">
        <div class="flex items-start sm:items-center space-x-3.5">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/25">
                <i class="fa-solid fa-wifi text-sm"></i>
            </div>
            <div class="space-y-0.5">
                <div class="flex items-center space-x-2">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight">Internet Connection Notice</h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-100 text-amber-800 border border-amber-200">Live Cloud Modules</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 font-medium">
                    <strong>Flights</strong> aur <strong>Weather</strong> menus ko TV launcher par enable karne ke liye TV screen ka active Wi-Fi ya LAN Internet se connect hona zaroori hai, taaki real-time updates show ho sakein.
                </p>
            </div>
        </div>
        <span class="hidden md:inline-flex items-center px-3 py-1.5 rounded-xl bg-white border border-amber-200 text-amber-900 text-[11px] font-black uppercase tracking-wider shrink-0 shadow-2xs">
            <i class="fa-solid fa-circle-info text-amber-600 mr-1.5"></i> Tooltip Enabled
        </span>
    </div>

    <!-- Main Menu Configuration Form Container -->
    <div class="bg-white border border-slate-100 rounded-[24px] sm:rounded-[28px] p-5 sm:p-8 shadow-sm space-y-6">

        <!-- Controls Bar: Search, Category Filters & Batch Toggles -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            
            <!-- Live Search -->
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       id="menuSearchInput" 
                       placeholder="Search menu items (e.g. Flights, Dining, TV)..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
            </div>

            <!-- Category Filter Pills & Batch Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                
                <!-- Category Filters -->
                <div class="flex items-center p-1 bg-slate-100 rounded-2xl overflow-x-auto max-w-full no-scrollbar">
                    <button type="button" onclick="filterMenuCategory('all', this)" class="category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-black transition-all bg-white text-[#150A42] shadow-2xs whitespace-nowrap">
                        All ({{ $totalCount }})
                    </button>
                    <button type="button" onclick="filterMenuCategory('services', this)" class="category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap">
                        Services
                    </button>
                    <button type="button" onclick="filterMenuCategory('data', this)" class="category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap flex items-center space-x-1">
                        <span>Live Data</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    </button>
                    <button type="button" onclick="filterMenuCategory('entertainment', this)" class="category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap">
                        Media & TV
                    </button>
                    <button type="button" onclick="filterMenuCategory('system', this)" class="category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap">
                        System
                    </button>
                </div>

                <!-- Select All / Deselect All Button -->
                <button type="button" 
                        onclick="toggleSelectAllMenus()"
                        class="px-3.5 py-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 cursor-pointer active:scale-95">
                    <i class="fa-solid fa-check-double text-purple-600 text-xs"></i>
                    <span id="toggleSelectAllText">{{ $activeCount === $totalCount ? 'Deselect All' : 'Select All' }}</span>
                </button>
            </div>
        </div>

        <!-- Form Submission -->
        <form id="deviceMenuForm" action="{{ url('/hotel/devices/' . $device->id . '/menus') }}" method="POST" class="space-y-6" data-swal-bypass="true" data-ajax-form="true">
            @csrf

            <!-- Grid of Interactive Menu Cards (Thin Borders, Uniform Height, Luxury Theme) -->
            <div id="menusGrid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-4">
                @foreach($catalog as $menuId => $menu)
                    @php
                        $isShown = !isset($currentSettings[$menuId]) || $currentSettings[$menuId] !== 'hide';
                        $meta = $menuMeta[$menuId] ?? [
                            'category' => 'services',
                            'category_name' => 'Services',
                            'desc' => 'Smart TV launcher menu item for Room ' . $device->room_no,
                            'icon' => 'fa-solid fa-cube',
                            'color' => 'from-purple-600 to-indigo-700',
                            'bg' => 'bg-purple-50 text-purple-700 border-purple-200/70',
                            'internet' => false
                        ];
                    @endphp

                    <div class="menu-card group relative p-3 sm:p-5 rounded-2xl border transition-all duration-200 cursor-pointer select-none flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 {{ $isShown ? 'border-purple-300/90 bg-gradient-to-b from-white to-purple-50/20 shadow-xs ring-1 ring-purple-400/20 is-active' : 'border-slate-200/80 bg-white hover:border-slate-300' }}"
                         data-id="{{ $menuId }}"
                         data-name="{{ strtolower($menu['name']) }}"
                         data-category="{{ $meta['category'] }}"
                         onclick="handleCardClick(this, event)">

                        <!-- Top: Icon, Title & Toggle Switch -->
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2 sm:mb-2.5">
                                <!-- Icon + Title -->
                                <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0">
                                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ $meta['bg'] }} border flex items-center justify-center text-xs sm:text-base shadow-2xs shrink-0 transition-transform group-hover:scale-105">
                                        <i class="{{ $meta['icon'] }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-xs sm:text-sm font-black text-slate-800 tracking-tight truncate leading-tight group-hover:text-[#150A42] transition-colors">{{ $menu['name'] }}</h3>
                                        <span class="text-[9px] sm:text-[10px] font-mono text-slate-400 block truncate mt-0.5">id: {{ $menuId }}</span>
                                    </div>
                                </div>

                                <!-- Custom iOS Toggle Switch -->
                                <label class="relative inline-flex items-center cursor-pointer shrink-0" onclick="event.stopPropagation();">
                                    <input type="checkbox" 
                                           name="menus[{{ $menuId }}]" 
                                           value="1" 
                                           {{ $isShown ? 'checked' : '' }} 
                                           class="sr-only peer menu-checkbox"
                                           onchange="handleCheckboxChange(this)">
                                    <div class="w-9 sm:w-11 h-5 sm:h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 sm:after:h-5 after:w-4 sm:after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                            </div>

                            <!-- Uniform Description (fixed min-height ensures all cards are identical in height) -->
                            <p class="text-[10px] sm:text-xs text-slate-500 font-medium leading-relaxed my-2 line-clamp-2 min-h-[30px] sm:min-h-[36px]">
                                {{ $meta['desc'] }}
                            </p>
                        </div>

                        <!-- Bottom Footer Row: Category + Internet Tooltip + Status Badge -->
                        <div class="pt-2 sm:pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] sm:text-[11px] gap-1.5">
                            <div class="flex items-center space-x-1.5 min-w-0">
                                <span class="category-pill text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 truncate">
                                    {{ $meta['category_name'] }}
                                </span>
                                @if(!empty($meta['internet']))
                                    <div class="relative inline-flex items-center space-x-1" onclick="event.stopPropagation();">
                                        <span class="inline-flex items-center space-x-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                            <i class="fa-solid fa-wifi text-[8px] text-amber-600"></i>
                                            <span class="hidden sm:inline">Net Req</span>
                                        </span>
                                        <button type="button" 
                                                title="Internet Requirement Details"
                                                onclick="toggleTooltip('tip-{{ $menuId }}')"
                                                class="w-4.5 h-4.5 sm:w-5 sm:h-5 rounded-md bg-amber-100 hover:bg-amber-200 text-amber-800 flex items-center justify-center text-[9px] font-bold transition-all active:scale-95 cursor-pointer">
                                            <i class="fa-solid fa-info"></i>
                                        </button>
                                        <!-- Popover Tooltip -->
                                        <div id="tip-{{ $menuId }}" class="menu-tooltip-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto sm:right-0 top-1/3 sm:top-auto sm:bottom-full sm:mb-2 sm:w-64 p-3.5 bg-slate-900/95 backdrop-blur-md text-white rounded-2xl shadow-2xl z-50 text-xs leading-relaxed border border-slate-700 animate-in fade-in duration-150">
                                            <div class="flex items-center justify-between pb-1.5 mb-1.5 border-b border-slate-700/80">
                                                <div class="flex items-center space-x-1.5 text-amber-400 font-extrabold text-[11px]">
                                                    <i class="fa-solid fa-wifi"></i>
                                                    <span>Active Internet Required</span>
                                                </div>
                                                <button type="button" onclick="closeAllTooltips()" class="text-slate-400 hover:text-white text-xs p-0.5">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <p class="text-[11px] text-slate-300 font-normal">
                                                {{ $meta['tooltip'] ?? 'Is menu ko TV par live chalane ke liye active Internet zaroori hai.' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Status Indicator Pill -->
                            <span class="status-indicator font-black flex items-center space-x-1 sm:space-x-1.5 shrink-0 px-2 py-0.5 rounded-lg text-[10px] sm:text-[11px] {{ $isShown ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-50 text-slate-400 border border-slate-200/80' }}">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $isShown ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                <span>{{ $isShown ? 'Visible' : 'Hidden' }}<span class="hidden sm:inline"> on TV</span></span>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty Search Results State -->
            <div id="emptySearchState" class="hidden text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">No matching menu items found</h4>
                <p class="text-xs text-slate-500 mt-1">Try searching with a different keyword or reset filters.</p>
            </div>

            <!-- Sticky Save Bottom Bar -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
                    <i class="fa-solid fa-cloud-arrow-up text-purple-600"></i>
                    <span>Changes take effect immediately on TV screen launcher</span>
                </div>

                <div class="flex items-center space-x-2.5">
                    <a href="{{ route('hotel.devices.index') }}" class="px-5 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="saveRoomMenuBtn" 
                            class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                        <span>Save Room {{ $device->room_no }} Menu Settings</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Card Click Toggle
    function handleCardClick(cardElement, event) {
        // If clicked on input or tooltip button, ignore to prevent double trigger
        if (event.target.closest('input') || event.target.closest('button')) {
            return;
        }
        const checkbox = cardElement.querySelector('.menu-checkbox');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateCardStyle(cardElement, checkbox.checked);
            updateCounters();
        }
    }

    // Checkbox Change Trigger
    function handleCheckboxChange(checkbox) {
        const cardElement = checkbox.closest('.menu-card');
        if (cardElement) {
            updateCardStyle(cardElement, checkbox.checked);
            updateCounters();
        }
    }

    // Dynamic Card Visual Styling (Thin, refined borders)
    function updateCardStyle(cardElement, isChecked) {
        const statusIndicator = cardElement.querySelector('.status-indicator');
        
        if (isChecked) {
            cardElement.classList.add('border-purple-300/90', 'bg-gradient-to-b', 'from-white', 'to-purple-50/20', 'ring-1', 'ring-purple-400/20', 'shadow-xs', 'is-active');
            cardElement.classList.remove('border-slate-200/90', 'border-slate-200/80', 'bg-white');
            if (statusIndicator) {
                statusIndicator.className = 'status-indicator font-black flex items-center space-x-1 sm:space-x-1.5 shrink-0 px-2 py-0.5 rounded-lg text-[10px] sm:text-[11px] bg-emerald-50 text-emerald-700 border border-emerald-200/60';
                statusIndicator.innerHTML = '<span class="w-1.5 h-1.5 rounded-full shrink-0 bg-emerald-500 animate-pulse"></span><span>Visible<span class="hidden sm:inline"> on TV</span></span>';
            }
        } else {
            cardElement.classList.remove('border-purple-300/90', 'bg-gradient-to-b', 'from-white', 'to-purple-50/20', 'ring-1', 'ring-purple-400/20', 'shadow-xs', 'is-active');
            cardElement.classList.add('border-slate-200/80', 'bg-white');
            if (statusIndicator) {
                statusIndicator.className = 'status-indicator font-black flex items-center space-x-1 sm:space-x-1.5 shrink-0 px-2 py-0.5 rounded-lg text-[10px] sm:text-[11px] bg-slate-50 text-slate-400 border border-slate-200/80';
                statusIndicator.innerHTML = '<span class="w-1.5 h-1.5 rounded-full shrink-0 bg-slate-300"></span><span>Hidden<span class="hidden sm:inline"> from TV</span></span>';
            }
        }
    }

    // Update Counter Pills
    function updateCounters() {
        const all = document.querySelectorAll('.menu-checkbox');
        const checked = document.querySelectorAll('.menu-checkbox:checked').length;
        const total = all.length;

        const counterPill = document.getElementById('selectedCounterPill');
        if (counterPill) counterPill.innerText = checked;

        const toggleBtnText = document.getElementById('toggleSelectAllText');
        if (toggleBtnText) {
            toggleBtnText.innerText = (total > 0 && checked === total) ? 'Deselect All' : 'Select All';
        }
    }

    // Select All / Deselect All
    function toggleSelectAllMenus() {
        const all = document.querySelectorAll('.menu-checkbox');
        const checked = document.querySelectorAll('.menu-checkbox:checked').length;
        const shouldCheck = (checked < all.length);

        all.forEach(cb => {
            cb.checked = shouldCheck;
            const card = cb.closest('.menu-card');
            if (card) updateCardStyle(card, shouldCheck);
        });

        updateCounters();
    }

    // Live Search Filter
    document.getElementById('menuSearchInput')?.addEventListener('input', function(e) {
        applyFilters();
    });

    // Category Tabs Filter
    let activeCategory = 'all';
    function filterMenuCategory(category, button) {
        activeCategory = category;
        document.querySelectorAll('.category-filter-btn').forEach(btn => {
            btn.className = 'category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all';
        });
        button.className = 'category-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-black transition-all bg-white text-[#150A42] shadow-2xs';
        applyFilters();
    }

    function applyFilters() {
        const query = (document.getElementById('menuSearchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const id = card.getAttribute('data-id') || '';
            const cat = card.getAttribute('data-category') || '';

            const matchesQuery = !query || name.includes(query) || id.includes(query);
            const matchesCat = (activeCategory === 'all') || (cat === activeCategory);

            if (matchesQuery && matchesCat) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('emptySearchState');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    // Tooltip handler
    function toggleTooltip(id) {
        const target = document.getElementById(id);
        if (!target) return;
        const isHidden = target.classList.contains('hidden');
        closeAllTooltips();
        if (isHidden) {
            target.classList.remove('hidden');
        }
    }

    function closeAllTooltips() {
        document.querySelectorAll('.menu-tooltip-popover').forEach(el => el.classList.add('hidden'));
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.menu-tooltip-popover') && !e.target.closest('button[onclick*="toggleTooltip"]')) {
            closeAllTooltips();
        }
    });

    // AJAX Form Submission
    document.addEventListener('DOMContentLoaded', function () {
        updateCounters();

        const Toast = typeof Swal !== 'undefined' ? Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        }) : null;

        const form = document.getElementById('deviceMenuForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('saveRoomMenuBtn');
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-xs"></i><span>Synchronizing with TV...</span>';
                btn.classList.add('opacity-90');
                if (typeof showGlobalLoader === 'function') showGlobalLoader();

                const formData = new FormData(this);

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && (data.success || data.status === 'success')) {
                        if (Toast) {
                            Toast.fire({
                                icon: 'success',
                                title: data.message || 'Room {{ $device->room_no }} menu preferences synchronized with TV screen.'
                            });
                        } else {
                            alert(data.message || 'Room {{ $device->room_no }} menu preferences synchronized with TV screen.');
                        }
                    } else {
                        if (Toast) {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Failed to update menu preferences.'
                            });
                        } else {
                            alert(data.message || 'Failed to update preferences.');
                        }
                    }
                } catch (err) {
                    console.error(err);
                    if (Toast) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Settings saved successfully.'
                        });
                    } else {
                        // Form fallback
                        form.submit();
                    }
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('opacity-90');
                    if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
                }
            });
        }
    });
</script>
@endsection
