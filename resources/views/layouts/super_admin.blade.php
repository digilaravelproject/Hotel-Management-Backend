<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F6FC] font-sans antialiased selection:bg-[#281483] selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Control Center') - {{ app_setting('app_name', 'PAX TV Launcher') }}</title>

    <!-- Dynamic Application Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="apple-touch-icon" href="{{ app_favicon_url() }}">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets (Tailwind CSS v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body, .font-mono, .font-space-mono, code, kbd, samp, pre { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
    @yield('styles')
</head>
<body class="h-full bg-[#F4F6FC] text-slate-800">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#0A051B]/70 backdrop-blur-sm z-40 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation (Deep Midnight Cosmic Violet - Ultra Professional & Dark) -->
        <aside id="sidebar" class="w-64 bg-gradient-to-b from-[#150A42] via-[#0F0631] to-[#090320] text-white flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-white/[0.08] shadow-2xl">
            
            <!-- Sidebar Header & Logo -->
            <div class="h-24 flex items-center justify-start px-4 sm:px-5 border-b border-white/[0.08] relative">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center justify-start group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-16 sm:h-20 w-auto max-w-[200px] object-contain drop-shadow-md transition-transform duration-200 group-hover:scale-105">
                    </picture>
                </a>
                <button type="button" onclick="toggleSidebar()" aria-label="Close Navigation Menu" class="absolute right-4 top-1/2 -translate-y-1/2 md:hidden w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center border border-white/10 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Director Status Pill (Jobie Style) -->
            <div class="p-3 mx-4 my-3 rounded-2xl bg-white/[0.06] border border-white/10 backdrop-blur-md flex items-center space-x-3 shadow-inner">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-900 flex items-center justify-center font-extrabold text-sm shadow-md shrink-0">
                    <i class="fa-solid fa-crown text-xs text-white"></i>
                </div>
                <div class="overflow-hidden min-w-0">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h4 class="text-xs font-bold text-white truncate">Platform Director</h4>
                    </div>
                    <p class="text-[10px] text-purple-200/80 font-mono truncate font-medium">Root Access</p>
                </div>
            </div>

            <!-- Menu Navigation Links (Clean Grouped Accordion Hierarchy) -->
            <nav class="flex-1 px-3 space-y-1 overflow-y-auto sidebar-scroll pb-4">
                <div class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                    Navigation
                </div>

                <!-- 1. Direct Dashboard Link -->
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.dashboard') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-house text-sm w-5 text-center {{ Request::routeIs('super-admin.dashboard') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Hospitality Hub (Hotels, Subscriptions, Flights) -->
                @php
                    $isHospitalityActive = Request::routeIs('super-admin.hotels.*') || Request::routeIs('super-admin.plans.*') || Request::routeIs('super-admin.flights.*');
                @endphp
                <div>
                    <button type="button" onclick="toggleSubmenu('submenu-hospitality')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $isHospitalityActive ? 'bg-white/10 text-white shadow-xs' : 'text-purple-200/75 hover:text-white hover:bg-white/[0.06]' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-hotel text-sm w-5 text-center {{ $isHospitalityActive ? 'text-amber-300' : 'text-purple-300' }}"></i>
                            <span>Hospitality Hub</span>
                        </div>
                        <i id="chevron-submenu-hospitality" class="fa-solid fa-chevron-down text-[10px] text-purple-300/70 transition-transform duration-200 {{ $isHospitalityActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-hospitality" class="mt-1 space-y-1 pl-6 pr-1 {{ $isHospitalityActive ? '' : 'hidden' }}">
                        <a href="{{ route('super-admin.hotels.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.hotels.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.hotels.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Hotels Directory</span>
                        </a>
                        <a href="{{ route('super-admin.plans.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.plans.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.plans.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Subscription Tiers</span>
                        </a>
                        <a href="{{ route('super-admin.flights.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.flights.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.flights.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Flights & Airports</span>
                        </a>
                    </div>
                </div>

                <!-- 3. Connected TVs & Tech Operations (Connected TVs, OTA Builds, OTT Master, FCM) -->
                @php
                    $isFleetActive = Request::routeIs('super-admin.devices.*') || Request::routeIs('super-admin.templates.*') || Request::routeIs('super-admin.ott-master.*') || Request::routeIs('super-admin.firebase-settings.*');
                @endphp
                <div>
                    <button type="button" onclick="toggleSubmenu('submenu-fleet')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $isFleetActive ? 'bg-white/10 text-white shadow-xs' : 'text-purple-200/75 hover:text-white hover:bg-white/[0.06]' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-tv text-sm w-5 text-center {{ $isFleetActive ? 'text-amber-300' : 'text-purple-300' }}"></i>
                            <span>Connected TVs & Systems</span>
                        </div>
                        <i id="chevron-submenu-fleet" class="fa-solid fa-chevron-down text-[10px] text-purple-300/70 transition-transform duration-200 {{ $isFleetActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-fleet" class="mt-1 space-y-1 pl-6 pr-1 {{ $isFleetActive ? '' : 'hidden' }}">
                        <a href="{{ route('super-admin.devices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.devices.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.devices.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Connected TVs</span>
                        </a>
                        <a href="{{ route('super-admin.templates.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.templates.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.templates.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">OTA App Builds</span>
                        </a>
                        <a href="{{ route('super-admin.ott-master.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.ott-master.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.ott-master.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">OTT & TV Apps</span>
                        </a>
                        <a href="{{ route('super-admin.firebase-settings.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.firebase-settings.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.firebase-settings.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">FCM Realtime</span>
                        </a>
                    </div>
                </div>

                <!-- 4. Partners & Finance (Users, Invoices, Taxes, Withdrawals, Commissions) -->
                @php
                    $isFinanceActive = Request::routeIs('super-admin.users.*') || Request::routeIs('super-admin.invoices.*') || Request::routeIs('super-admin.taxes.*') || Request::routeIs('super-admin.withdrawals.*') || Request::routeIs('super-admin.commissions.*');
                    $pendingWithdrawalsCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                @endphp
                <div>
                    <button type="button" onclick="toggleSubmenu('submenu-finance')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $isFinanceActive ? 'bg-white/10 text-white shadow-xs' : 'text-purple-200/75 hover:text-white hover:bg-white/[0.06]' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-coins text-sm w-5 text-center {{ $isFinanceActive ? 'text-amber-300' : 'text-purple-300' }}"></i>
                            <span>Billing & Finance</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($pendingWithdrawalsCount > 0)
                                <span class="px-1.5 py-0.2 rounded-full bg-amber-400 text-slate-900 font-extrabold text-[9px] font-mono">
                                    {{ $pendingWithdrawalsCount }}
                                </span>
                            @endif
                            <i id="chevron-submenu-finance" class="fa-solid fa-chevron-down text-[10px] text-purple-300/70 transition-transform duration-200 {{ $isFinanceActive ? 'rotate-180' : '' }}"></i>
                        </div>
                    </button>
                    <div id="submenu-finance" class="mt-1 space-y-1 pl-6 pr-1 {{ $isFinanceActive ? '' : 'hidden' }}">
                        <a href="{{ route('super-admin.invoices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.invoices.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.invoices.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Tax Invoices</span>
                        </a>
                        <a href="{{ route('super-admin.taxes.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.taxes.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.taxes.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Tax Management</span>
                        </a>
                        <a href="{{ route('super-admin.users.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.users.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.users.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Distributors & Users</span>
                        </a>
                        <a href="{{ route('super-admin.withdrawals.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.withdrawals.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <div class="flex items-center space-x-2.5 truncate">
                                <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.withdrawals.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                                <span class="truncate">Withdrawals</span>
                            </div>
                            @if($pendingWithdrawalsCount > 0)
                                <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-900 font-extrabold text-[9px] font-mono shrink-0">
                                    {{ $pendingWithdrawalsCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('super-admin.commissions.settings') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.commissions.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.commissions.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Commissions</span>
                        </a>
                    </div>
                </div>

                <!-- 5. Platform Settings (Branding, Profile & Security) -->
                @php
                    $isSettingsActive = Request::routeIs('super-admin.settings.*') || Request::routeIs('super-admin.profile');
                @endphp
                <div>
                    <button type="button" onclick="toggleSubmenu('submenu-settings')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $isSettingsActive ? 'bg-white/10 text-white shadow-xs' : 'text-purple-200/75 hover:text-white hover:bg-white/[0.06]' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-sliders text-sm w-5 text-center {{ $isSettingsActive ? 'text-amber-300' : 'text-purple-300' }}"></i>
                            <span>Platform Settings</span>
                        </div>
                        <i id="chevron-submenu-settings" class="fa-solid fa-chevron-down text-[10px] text-purple-300/70 transition-transform duration-200 {{ $isSettingsActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-settings" class="mt-1 space-y-1 pl-6 pr-1 {{ $isSettingsActive ? '' : 'hidden' }}">
                        <a href="{{ route('super-admin.settings.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.settings.*') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.settings.*') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">System Branding</span>
                        </a>
                        <a href="{{ route('super-admin.profile') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs transition-all {{ Request::routeIs('super-admin.profile') ? 'bg-white text-[#150A42] font-bold shadow-md shadow-black/20' : 'text-purple-200/70 hover:text-white hover:bg-white/5 font-semibold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::routeIs('super-admin.profile') ? 'bg-[#150A42]' : 'bg-purple-400/50' }}"></span>
                            <span class="truncate">Profile & Security</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Bottom Brand Info (Jobie style) -->
            <div class="px-5 py-3 border-t border-white/[0.08] text-center">
                <p class="text-[10px] text-purple-200/50 font-medium">PAX TV Platform Admin</p>
                <p class="text-[9px] text-purple-300/30 font-mono mt-0.5">© {{ date('Y') }} All Rights Reserved</p>
            </div>

            <!-- Sign Out Button (Sticky at bottom on mobile) -->
            <div class="p-3 border-t border-white/[0.08] sticky bottom-0 bg-[#090320] z-20">
                <form action="{{ route('super-admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl bg-red-500/15 border border-red-500/30 text-rose-200 hover:text-white hover:bg-red-600 text-xs font-bold transition-all cursor-pointer shadow-xs">
                        <i class="fa-solid fa-right-from-bracket text-rose-400"></i>
                        <span>Admin Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            
            <!-- Modern Jobie Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200/80 sticky top-0 z-40 px-3 sm:px-8 flex items-center justify-between shadow-xs">
                
                <!-- Left: Mobile Menu Toggle & Clean Search Bar -->
                <div class="flex items-center space-x-3 sm:space-x-4 flex-1 max-w-xl">
                    <!-- Modern 44x44 Touch-Point Mobile Menu Button (Jan Mraz UX Cheatsheet: Modern Fries Style) -->
                    <button type="button" onclick="toggleSidebar()" aria-label="Toggle Navigation Menu" class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-[#F8FAFC] hover:bg-purple-50/70 border border-slate-200/90 shadow-xs hover:border-purple-200/80 flex flex-col items-center justify-center gap-[4.5px] focus:outline-hidden active:scale-95 transition-all md:hidden group cursor-pointer shrink-0">
                        <span class="w-5 h-[2.5px] rounded-full bg-slate-700 group-hover:bg-[#150A42] transition-colors"></span>
                        <span class="w-3.5 h-[2.5px] rounded-full bg-slate-700 group-hover:w-5 group-hover:bg-[#150A42] transition-all self-start ml-[11px]"></span>
                        <span class="w-5 h-[2.5px] rounded-full bg-slate-700 group-hover:bg-[#150A42] transition-colors"></span>
                    </button>

                    <!-- Mobile Brand Logo (Left-aligned on mobile header) -->
                    <a href="{{ route('super-admin.dashboard') }}" class="md:hidden flex items-center shrink-0">
                        <picture>
                            <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                            <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto max-w-[140px] object-contain">
                        </picture>
                    </a>

                    <!-- Search Input (Jobie style) -->
                    <div class="relative w-full max-w-md hidden sm:block">
                        <input type="text" placeholder="Search hotels, connected TVs, distributors..." class="w-full pl-4 pr-11 py-2.5 rounded-full bg-[#F3F5FA] border border-transparent focus:border-purple-300 focus:bg-white focus:outline-hidden text-xs text-slate-700 placeholder:text-slate-400 font-medium transition-all shadow-inner">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Right Action Icons, Profile Info & Fast Logout Button -->
                <div class="flex items-center space-x-2 sm:space-x-3.5 shrink-0">
                    
                    <!-- Notification Bell with Badge (Jobie 62) -->
                    <a href="{{ route('super-admin.hotels.index', ['status' => 'pending']) }}" title="Pending Approvals & Notifications" class="relative w-10 h-10 rounded-full bg-[#F3F5FA] hover:bg-purple-50 text-slate-600 hover:text-[#150A42] flex items-center justify-center transition-all">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute -top-1 -right-1 px-1.5 py-0.2 rounded-full bg-[#150A42] text-white text-[9px] font-bold font-mono shadow-xs">
                            62
                        </span>
                    </a>

                    <!-- Super Admin Profile (Avatar + Name + Subtitle matching Jobie) -->
                    <a href="{{ route('super-admin.profile') }}" class="flex items-center space-x-2.5 pl-1.5 sm:pl-2 py-1 pr-1 rounded-2xl hover:bg-slate-50 transition-all" title="View Admin Profile">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#150A42] to-purple-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-purple-100 shadow-sm shrink-0">
                            <i class="fa-solid fa-user-tie text-sm"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-xs font-extrabold text-slate-900 leading-tight">
                                {{ Auth::guard('super_admin')->user()->name ?? 'Super Admin' }}
                            </div>
                            <div class="text-[10px] text-slate-500 font-medium">
                                Director
                            </div>
                        </div>
                    </a>

                    <!-- Fast Logout Button (Visible on Mobile & Desktop) -->
                    <form action="{{ route('super-admin.logout') }}" method="POST" class="inline-flex m-0">
                        @csrf
                        <button type="submit" 
                                title="Sign Out of Super Admin" 
                                class="h-10 px-2.5 sm:px-3.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200/80 flex items-center space-x-1.5 text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs"
                                aria-label="Sign Out">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto pb-24 md:pb-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-2.5 shadow-xs">
                        <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center space-x-2.5 shadow-xs">
                        <i class="fa-solid fa-circle-exclamation text-base text-red-600 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Bottom App-Style Floating Navigation Bar (5 Core Menus matching sample) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 px-3 pb-3 pt-1 pointer-events-none">
        <div class="pointer-events-auto bg-white/95 backdrop-blur-md rounded-[26px] border border-slate-200/90 shadow-[0_-6px_25px_rgba(21,10,66,0.12)] px-2 py-1.5 flex items-center justify-around relative">
            
            <!-- 1. Dashboard -->
            @php $isDash = Request::routeIs('super-admin.dashboard'); @endphp
            <a href="{{ route('super-admin.dashboard') }}" class="relative flex flex-col items-center group transition-all {{ $isDash ? '-mt-5' : 'py-1 px-2' }}">
                @if($isDash)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-house text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">Home</span>
                @else
                    <i class="fa-solid fa-house text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">Home</span>
                @endif
            </a>

            <!-- 2. Hotels Directory -->
            @php $isHotels = Request::routeIs('super-admin.hotels.*'); @endphp
            <a href="{{ route('super-admin.hotels.index') }}" class="relative flex flex-col items-center group transition-all {{ $isHotels ? '-mt-5' : 'py-1 px-2' }}">
                @if($isHotels)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-hotel text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">Hotels</span>
                @else
                    <i class="fa-solid fa-hotel text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">Hotels</span>
                @endif
            </a>

            <!-- 3. Subscriptions -->
            @php $isPlans = Request::routeIs('super-admin.plans.*'); @endphp
            <a href="{{ route('super-admin.plans.index') }}" class="relative flex flex-col items-center group transition-all {{ $isPlans ? '-mt-5' : 'py-1 px-2' }}">
                @if($isPlans)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-layer-group text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">Plans</span>
                @else
                    <i class="fa-solid fa-layer-group text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">Plans</span>
                @endif
            </a>

            <!-- 4. Distributors & Users -->
            @php $isPartners = Request::routeIs('super-admin.users.*'); @endphp
            <a href="{{ route('super-admin.users.index') }}" class="relative flex flex-col items-center group transition-all {{ $isPartners ? '-mt-5' : 'py-1 px-2' }}">
                @if($isPartners)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-users-gear text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">Users</span>
                @else
                    <i class="fa-solid fa-users-gear text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">Users</span>
                @endif
            </a>

            <!-- 5. Connected TVs -->
            @php $isFleet = Request::routeIs('super-admin.devices.*'); @endphp
            <a href="{{ route('super-admin.devices.index') }}" class="relative flex flex-col items-center group transition-all {{ $isFleet ? '-mt-5' : 'py-1 px-2' }}">
                @if($isFleet)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-tv text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">TVs</span>
                @else
                    <i class="fa-solid fa-tv text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">TVs</span>
                @endif
            </a>

        </div>
    </nav>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const chevron = document.getElementById('chevron-' + id);
            if (!submenu) return;
            const isHidden = submenu.classList.contains('hidden');
            if (isHidden) {
                submenu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        // Global Table Action Menu (3-dots) Handler
        window.toggleActionMenu = function(event, id, btnEl) {
            if (event) {
                if (typeof event.preventDefault === 'function') event.preventDefault();
                if (typeof event.stopPropagation === 'function') event.stopPropagation();
            }
            const dropdown = document.getElementById(`action-dropdown-${id}`);
            if (!dropdown) return;

            const isVisible = dropdown.style.display === 'block' && !dropdown.classList.contains('hidden');

            // Close all open action menus
            document.querySelectorAll('.action-menu-dropdown').forEach(d => {
                d.classList.add('hidden');
                d.style.display = 'none';
            });
            document.querySelectorAll('.action-dropdown-container').forEach(c => c.style.zIndex = '');
            document.querySelectorAll('tr').forEach(tr => tr.style.zIndex = '');
            document.querySelectorAll('td').forEach(td => td.style.zIndex = '');

            if (!isVisible) {
                dropdown.classList.remove('hidden');
                dropdown.style.display = 'block';
                dropdown.style.visibility = 'visible';
                dropdown.style.opacity = '1';
                dropdown.style.zIndex = '99999';

                const btn = btnEl || document.getElementById(`action-btn-${id}`) || (event ? event.target?.closest('button') : null);
                if (btn) {
                    try {
                        const rect = btn.getBoundingClientRect();
                        if (window.innerHeight - rect.bottom < 260) {
                            dropdown.classList.remove('top-full', 'mt-1.5');
                            dropdown.classList.add('bottom-full', 'mb-1.5');
                        } else {
                            dropdown.classList.remove('bottom-full', 'mb-1.5');
                            dropdown.classList.add('top-full', 'mt-1.5');
                        }
                    } catch (e) {}

                    const container = btn.closest('.action-dropdown-container');
                    if (container) {
                        container.style.position = 'relative';
                        container.style.zIndex = '99999';
                    }
                    const td = btn.closest('td');
                    if (td) {
                        td.style.position = 'relative';
                        td.style.zIndex = '99999';
                    }
                    const tr = btn.closest('tr');
                    if (tr) {
                        tr.style.position = 'relative';
                        tr.style.zIndex = '99999';
                    }
                }
            }
        };

        // Global loader overlay on form submits
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('globalAppLoader')) {
                const loaderDiv = document.createElement('div');
                loaderDiv.id = 'globalAppLoader';
                loaderDiv.className = 'fixed inset-0 bg-[#0A051B]/60 backdrop-blur-sm z-[9999] hidden flex flex-col items-center justify-center transition-all duration-300';
                loaderDiv.innerHTML = `
                    <div class="bg-[#120B2F] text-slate-300 p-6 rounded-3xl shadow-2xl border border-white/10 flex flex-col items-center space-y-4 max-w-xs w-full mx-4 text-center">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-4 border-white/10"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-purple-500 border-t-transparent animate-spin"></div>
                            <i class="fa-solid fa-bolt text-purple-400 text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white">Processing Admin Request...</h4>
                            <p class="text-[11px] text-slate-400 font-medium">Syncing database & connected devices</p>
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

        // Global SweetAlert2 confirmation handler for forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            // Skip for AJAX/background forms or forms inside modal dialogues
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
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md bg-red-600',
                        cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs bg-slate-200 text-slate-800'
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
