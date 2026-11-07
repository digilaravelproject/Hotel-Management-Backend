<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8F9FD] font-sans antialiased selection:bg-[#150A42] selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Panel') - {{ app_name() }}</title>

    <!-- Dynamic Application Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="apple-touch-icon" href="{{ app_favicon_url() }}">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets (Tailwind CSS v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Globally hide native spin buttons on number inputs across all browsers */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
            display: none !important;
        }
        input[type="number"] {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full bg-[#F8F9FD] text-slate-900 antialiased">
    @php
        $hotelUser = Auth::guard('hotel_admin')->user();
        $hotelDevicesCount = $hotelUser ? $hotelUser->connectedDevices()->count() : 0;
        $hotelDeviceLimit = $hotelUser ? $hotelUser->allowed_device_limit : 0;
        $hotelPlan = $hotelUser ? $hotelUser->plan : null;
        $subStatus = $hotelUser ? $hotelUser->subscription_status : 'unpaid';
        $isPaidAndActive = $hotelUser 
            && $hotelUser->plan_id 
            && $hotelUser->payment_status === 'paid' 
            && ($hotelUser->expiry_date === null || $hotelUser->expiry_date->isFuture());
    @endphp

    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#0B0424]/75 backdrop-blur-xs z-50 md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Navigation (Signature Deep Midnight Cosmic Violet #150A42) -->
        <aside id="sidebar" class="w-64 bg-[#150A42] text-purple-200/80 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 border-r border-white/10 shadow-2xl">
            <!-- Sidebar Header & Logo -->
            <div class="h-24 flex items-center justify-between px-4 sm:px-5 border-b border-white/10 bg-[#0B0424]/40 shrink-0 relative">
                <a href="{{ $isPaidAndActive ? route('hotel.dashboard') : route('hotel.subscription') }}" class="flex items-center justify-start group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-16 sm:h-20 w-auto max-w-[200px] object-contain drop-shadow-md transition-transform duration-200 group-hover:scale-105">
                    </picture>
                </a>
                <button type="button" onclick="toggleSidebar()" aria-label="Close Navigation Menu" class="md:hidden text-purple-200/80 hover:text-white p-2 rounded-xl hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Hotel Branding Card -->
            <div class="p-3.5 mx-3.5 my-3 rounded-2xl bg-white/[0.07] border border-white/10 flex items-center space-x-3 shadow-xs shrink-0">
                @if($hotelUser && $hotelUser->hotel_logo)
                    <img src="{{ asset($hotelUser->hotel_logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover border border-white/20 shadow-xs shrink-0">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md shrink-0">
                        {{ strtoupper(substr($hotelUser->hotel_name ?? 'H', 0, 1)) }}
                    </div>
                @endif
                <div class="overflow-hidden min-w-0 flex-1">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full {{ $subStatus === 'active' ? 'bg-emerald-400 animate-pulse' : ($subStatus === 'expiring_soon' ? 'bg-amber-400 animate-pulse' : 'bg-rose-400') }} shrink-0"></span>
                        <h4 class="text-xs font-bold text-white truncate">{{ $hotelUser->hotel_name ?? 'Hotel Admin' }}</h4>
                    </div>
                    <div class="flex items-center space-x-2 mt-0.5">
                        <span class="text-[10px] text-purple-300/80 font-medium truncate">
                            <i class="fa-solid fa-location-dot text-[9px] mr-1 text-purple-300"></i>{{ $hotelUser->city ?? 'Active Hotel' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grouped Menu Navigation Links -->
            <nav class="flex-1 px-3 space-y-1 overflow-y-auto sidebar-scroll pb-4">
                
                @if($isPaidAndActive)
                    <!-- Group 1: Workspace -->
                    <div class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                        Workspace
                    </div>

                    <a href="{{ route('hotel.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.dashboard') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-house text-sm w-5 text-center {{ Request::routeIs('hotel.dashboard') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('hotel.devices.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.devices.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-tv text-sm w-5 text-center {{ Request::routeIs('hotel.devices.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                            <span>Connected TVs</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ Request::routeIs('hotel.devices.*') ? 'bg-[#150A42] text-white' : 'bg-white/15 text-purple-200' }}">
                            {{ $hotelDevicesCount }}/{{ $hotelDeviceLimit }}
                        </span>
                    </a>

                    <a href="{{ route('hotel.guests.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.guests.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-users text-sm w-5 text-center {{ Request::routeIs('hotel.guests.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>In-House Guests</span>
                    </a>

                    <!-- Group 2: TV Experience & Display -->
                    <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                        TV Experience
                    </div>

                    <a href="{{ route('hotel.package') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.package') || Request::routeIs('hotel.ott-settings') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-brands fa-google-play text-sm w-5 text-center {{ Request::routeIs('hotel.package') || Request::routeIs('hotel.ott-settings') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>OTT Apps Control</span>
                    </a>

                    <a href="{{ route('hotel.menus.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.menus.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-list-check text-sm w-5 text-center {{ Request::routeIs('hotel.menus.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>TV Menus Visibility</span>
                    </a>

                    <a href="{{ route('hotel.themes.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.themes.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-palette text-sm w-5 text-center {{ Request::routeIs('hotel.themes.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>TV Themes & Styling</span>
                    </a>

                    <a href="{{ route('hotel.flights.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.flights.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-plane-departure text-sm w-5 text-center {{ Request::routeIs('hotel.flights.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Airport & Flights</span>
                    </a>

                    <!-- Group 3: Hotel CMS & Facilities -->
                    <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                        Hotel CMS
                    </div>

                    <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.hotel-info') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-sliders text-sm w-5 text-center {{ Request::routeIs('hotel.hotel-info') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Profile & Media</span>
                    </a>

                    <a href="{{ route('hotel.amenities.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.amenities.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-spa text-sm w-5 text-center {{ Request::routeIs('hotel.amenities.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Hotel Amenities</span>
                    </a>

                    <a href="{{ route('hotel.room-infos.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.room-infos.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-bed text-sm w-5 text-center {{ Request::routeIs('hotel.room-infos.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Room Information</span>
                    </a>

                    <a href="{{ route('hotel.facilities.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.facilities.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-hotel text-sm w-5 text-center {{ Request::routeIs('hotel.facilities.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Hotel Facilities</span>
                    </a>

                    <a href="{{ route('hotel.our-city.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.our-city.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-city text-sm w-5 text-center {{ Request::routeIs('hotel.our-city.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Our City Guide</span>
                    </a>

                    <!-- Group 4: Commercials & Billing -->
                    <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                        Billing & Plan
                    </div>

                    <a href="{{ route('hotel.subscription') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.subscription*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-credit-card text-sm w-5 text-center {{ Request::routeIs('hotel.subscription*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Plan & Renewal</span>
                    </a>

                    <a href="{{ route('hotel.invoices.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('hotel.invoices.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                        <i class="fa-solid fa-file-invoice-dollar text-sm w-5 text-center {{ Request::routeIs('hotel.invoices.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>Tax Invoices</span>
                    </a>
                @else
                    <!-- UNPAID / EXPIRED LOCKED STATE: All Menus Blanked Out Until Plan Purchased -->
                    <div class="p-4 mx-1 my-3 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-200 space-y-2">
                        <div class="flex items-center space-x-2 text-rose-300 font-black text-xs uppercase tracking-wider">
                            <i class="fa-solid fa-lock text-sm"></i>
                            <span>{{ $subStatus === 'expired' ? 'Subscription Expired' : 'Features Locked' }}</span>
                        </div>
                        <p class="text-[11px] text-purple-200/80 font-medium leading-relaxed">
                            {{ $subStatus === 'expired' 
                                ? 'Your TV subscription has expired. In-room smart TVs and hotel features are locked until renewal.' 
                                : 'Panel features and TV sync are locked. Select a plan below and complete payment to unlock full access.' }}
                        </p>
                    </div>

                    <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/90 font-mono flex items-center justify-between">
                        <span>Activation Required</span>
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-ping"></span>
                    </div>

                    <a href="{{ route('hotel.subscription') }}" class="flex items-center space-x-3 px-3.5 py-3 rounded-2xl text-xs font-black transition-all bg-gradient-to-r from-purple-600 to-[#150A42] text-white border border-purple-400/40 shadow-lg shadow-purple-950/40 hover:brightness-110 active:scale-95">
                        <i class="fa-solid fa-credit-card text-sm w-5 text-center text-purple-200"></i>
                        <span>Choose Plan & Pay Now</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Brand Info & Sign Out -->
            <div class="p-3 border-t border-white/[0.08] shrink-0">
                <form action="{{ route('hotel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2 px-3 rounded-xl border border-white/15 text-purple-200/80 hover:text-white hover:bg-white/10 text-xs font-bold transition-all cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Topbar Header -->
            <header class="h-16 sm:h-20 bg-white border-b border-slate-200/80 sticky top-0 z-40 px-4 sm:px-6 lg:px-8 flex items-center justify-between shadow-2xs">
                <!-- Left: Hamburger & Page Title -->
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <button type="button" 
                            onclick="toggleSidebar()" 
                            aria-label="Toggle Navigation Drawer"
                            class="md:hidden w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200 transition-colors active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div class="truncate">
                        <h1 class="text-base sm:text-xl font-black text-slate-900 tracking-tight truncate">
                            @yield('page_title', 'Hotel Admin')
                        </h1>
                    </div>
                </div>

                <!-- Right: TV Limit Pill, Plan Pill & Profile Menu -->
                <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    @if($isPaidAndActive)
                        <!-- Quick TV Hardware Slots Pill -->
                        <a href="{{ route('hotel.devices.index') }}" 
                           title="Connected TV Screens"
                           class="hidden sm:flex items-center space-x-2 px-3 py-1.5 rounded-2xl bg-purple-50/80 hover:bg-purple-100 border border-purple-200/70 text-[#150A42] transition-all active:scale-95 shadow-2xs">
                            <i class="fa-solid fa-tv text-purple-600 text-xs"></i>
                            <span class="text-xs font-black font-mono">{{ $hotelDevicesCount }} / {{ $hotelDeviceLimit }} TVs</span>
                        </a>

                        <!-- Plan Status Pill -->
                        @if($hotelPlan)
                            <a href="{{ route('hotel.subscription') }}" 
                               title="Subscription Status"
                               class="hidden md:flex items-center space-x-1.5 px-3 py-1.5 rounded-2xl {{ $subStatus === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200/70' : ($subStatus === 'expiring_soon' ? 'bg-amber-50 text-amber-800 border-amber-200/70' : 'bg-rose-50 text-rose-800 border-rose-200/70') }} border text-xs font-bold transition-all shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full {{ $subStatus === 'active' ? 'bg-emerald-500 animate-pulse' : ($subStatus === 'expiring_soon' ? 'bg-amber-500 animate-pulse' : 'bg-rose-500') }}"></span>
                                <span class="truncate max-w-[120px]">{{ $hotelPlan->name }}</span>
                            </a>
                        @endif
                    @else
                        <!-- Unpaid / Expired Status Pill -->
                        <a href="{{ route('hotel.subscription') }}" 
                           title="Click to Choose Plan & Pay"
                           class="flex items-center space-x-1.5 px-3 py-1.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 text-xs font-black transition-all shadow-2xs active:scale-95 animate-pulse">
                            <i class="fa-solid fa-lock text-[10px] text-rose-600"></i>
                            <span>{{ $subStatus === 'expired' ? 'Plan Expired — Renew' : 'Payment Required' }}</span>
                        </a>
                    @endif

                    <!-- User Profile Avatar & Dropdown -->
                    <div class="relative" id="profileDropdownContainer">
                        <button type="button" 
                                onclick="toggleProfileDropdown()" 
                                class="flex items-center space-x-2.5 pl-1.5 sm:pl-2 py-1 pr-1 sm:pr-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100/90 border border-slate-200/80 shadow-2xs transition-all cursor-pointer">
                            @if($hotelUser && $hotelUser->hotel_logo)
                                <img src="{{ asset($hotelUser->hotel_logo) }}" alt="Logo" class="w-8 h-8 rounded-full object-cover ring-2 ring-purple-100">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#150A42] to-purple-600 text-white flex items-center justify-center font-black text-xs ring-2 ring-purple-100 shadow-xs">
                                    {{ strtoupper(substr($hotelUser->owner_name ?? 'H', 0, 1)) }}
                                </div>
                            @endif
                            <div class="hidden lg:block text-left pr-1">
                                <div class="text-xs font-extrabold text-slate-900 leading-tight">
                                    {{ $hotelUser->owner_name ?? 'Hotel Admin' }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium truncate max-w-[110px]">
                                    {{ $hotelUser->hotel_name ?? 'Hotel' }}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] hidden sm:inline"></i>
                        </button>

                        <!-- Menu Dropdown -->
                        <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200/80 rounded-2xl shadow-xl py-2 z-50 animate-in fade-in zoom-in-95 duration-150">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs">
                                <p class="font-black text-slate-900 truncate">{{ $hotelUser->owner_name ?? 'Hotel Admin' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium truncate">{{ $hotelUser->email ?? '' }}</p>
                            </div>
                            @if($isPaidAndActive)
                                <a href="{{ route('hotel.profile') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-purple-50 hover:text-purple-900 transition-colors">
                                    <i class="fa-regular fa-user text-purple-600 w-4"></i>
                                    <span>Account Profile</span>
                                </a>
                                <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-purple-50 hover:text-purple-900 transition-colors">
                                    <i class="fa-solid fa-hotel text-purple-600 w-4"></i>
                                    <span>Hotel Info & Media</span>
                                </a>
                            @endif
                            <a href="{{ route('hotel.subscription') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-purple-50 hover:text-purple-900 transition-colors">
                                <i class="fa-solid fa-credit-card text-purple-600 w-4"></i>
                                <span>Plan & Billing</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('hotel.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors text-left cursor-pointer">
                                    <i class="fa-solid fa-right-from-bracket w-4 text-rose-500"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Fast Sign Out Icon -->
                    <form action="{{ route('hotel.logout') }}" method="POST" class="inline-flex m-0">
                        @csrf
                        <button type="submit" 
                                title="Sign Out of Hotel Portal" 
                                class="h-10 px-2.5 sm:px-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center space-x-1.5 text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-2xs"
                                aria-label="Sign Out">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Container with Centralized Alerts -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto pb-24 md:pb-8">
                @if(!$isPaidAndActive && !Request::routeIs('hotel.subscription*'))
                    <div class="mb-6 p-5 rounded-[24px] bg-gradient-to-r from-rose-50 via-amber-50 to-purple-50 border-2 border-rose-200/90 text-rose-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm animate-in fade-in duration-200">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-black text-lg shrink-0 shadow-md">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-rose-950">
                                    {{ $subStatus === 'expired' ? 'Subscription Expired — Renewal Required' : 'Payment Required — Hotel Panel Features Locked' }}
                                </h4>
                                <p class="text-xs text-rose-900/85 font-medium mt-0.5">
                                    {{ $subStatus === 'expired' 
                                        ? 'Your hotel subscription period has ended. In-room TV displays and features are suspended until renewed.' 
                                        : 'Payment required. Please select a plan and complete payment to unlock hotel panel features and start pairing TV screens.' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('hotel.subscription') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center space-x-2 shrink-0 active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-credit-card text-purple-200 text-xs"></i>
                            <span>Choose Plan & Pay</span>
                        </a>
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error') && (!Request::routeIs('hotel.subscription*') || !str_contains(session('error'), 'Payment required. Please select a plan')))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-exclamation text-base text-rose-600 shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        function toggleProfileDropdown() {
            const menu = document.getElementById('profileDropdownMenu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('profileDropdownContainer');
            const menu = document.getElementById('profileDropdownMenu');
            if (container && !container.contains(e.target) && menu) {
                menu.classList.add('hidden');
            }
        });

        // Global loader overlay on form submits (Signature Midnight Navy & Purple)
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('globalAppLoader')) {
                const loaderDiv = document.createElement('div');
                loaderDiv.id = 'globalAppLoader';
                loaderDiv.className = 'fixed inset-0 bg-[#0B0424]/75 backdrop-blur-xs z-[9999] hidden flex flex-col items-center justify-center transition-all duration-300';
                loaderDiv.innerHTML = `
                    <div class="bg-[#150A42] text-white p-6 sm:p-7 rounded-[28px] shadow-2xl border border-white/10 flex flex-col items-center space-y-4 max-w-xs w-full mx-4 text-center transform scale-100 transition-transform">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-4 border-purple-500/20"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-purple-400 border-t-transparent animate-spin"></div>
                            <i class="fa-solid fa-bolt text-amber-300 text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-black text-white">Processing Request...</h4>
                            <p class="text-[11px] text-purple-200/70 font-medium">Syncing database & connected devices</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(loaderDiv);
            }
        });

        function showGlobalLoader(form = null) {
            if (form) {
                const parentModal = form.closest('.fixed, [id*="Modal"], [id*="modal"], .modal-overlay');
                if (parentModal && parentModal.id !== 'globalAppLoader') {
                    parentModal.classList.add('hidden');
                    parentModal.classList.remove('active');
                    parentModal.style.display = 'none';
                }
            }
            document.querySelectorAll('[id*="Modal"], [id*="modal"], .modal-overlay').forEach(m => {
                if (m.id !== 'globalAppLoader') {
                    m.classList.add('hidden');
                    m.classList.remove('active');
                    m.style.display = 'none';
                }
            });

            if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                Swal.close();
            }

            const loader = document.getElementById('globalAppLoader');
            if (loader) loader.classList.remove('hidden');
        }

        function hideGlobalLoader() {
            const loader = document.getElementById('globalAppLoader');
            if (loader) loader.classList.add('hidden');
        }

        window.showGlobalLoader = showGlobalLoader;
        window.hideGlobalLoader = hideGlobalLoader;

        // Form submission confirmation handling
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            if (
                form.dataset.ajaxForm === "true" || 
                form.dataset.noLoader === "true" ||
                form.hasAttribute('data-ajax-form') ||
                form.hasAttribute('data-no-loader') ||
                form.closest('#setup2FAModal, #disable2FAModal, [data-no-loader="true"]')
            ) {
                return;
            }
            if (form.dataset.swalBypass) {
                showGlobalLoader(form);
                return;
            }

            const onsubmitAttr = form.getAttribute('onsubmit');
            if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                let message = 'Are you sure you want to perform this action?';
                const match = onsubmitAttr.match(/confirm\(['"]([^'"]+)['"]\)/);
                if (match && match[1]) {
                    message = match[1];
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#150A42',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl font-sans',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md bg-[#150A42] text-white',
                        cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showGlobalLoader(form);
                        form.dataset.swalBypass = "true";
                        form.submit();
                    }
                });
            } else {
                showGlobalLoader(form);
            }
        }, true);
    </script>
    @yield('scripts')
</body>
</html>
