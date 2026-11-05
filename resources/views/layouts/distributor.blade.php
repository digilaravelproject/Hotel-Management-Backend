<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8F9FD] font-sans antialiased selection:bg-[#150A42] selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Distributor Portal - ' . app_name())</title>

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
    </style>
    @yield('styles')
</head>
<body class="h-full bg-[#F8F9FD] text-slate-900 antialiased">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#0B0424]/75 backdrop-blur-xs z-50 md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Navigation (Signature Deep Midnight Navy #150A42 matching Super Admin) -->
        <aside id="sidebar" class="w-64 bg-[#150A42] text-purple-200/80 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 border-r border-white/10 shadow-2xl">
            <!-- Sidebar Header & Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-[#0B0424]/40 shrink-0">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 overflow-hidden bg-white/95 px-3 py-1.5 rounded-2xl shadow-xs transition-transform hover:scale-[1.02]">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto max-w-[150px] object-contain">
                </a>
                <button type="button" onclick="toggleSidebar()" aria-label="Close Navigation Menu" class="md:hidden text-purple-200/80 hover:text-white p-2 rounded-xl hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Distributor Profile Card -->
            <div class="p-3.5 mx-3.5 my-4 rounded-2xl bg-white/[0.07] border border-white/10 flex items-center space-x-3 shadow-xs shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-500 to-amber-400 text-slate-950 flex items-center justify-center font-black text-xs shadow-md shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
                </div>
                <div class="overflow-hidden min-w-0 flex-1">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                        <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Distributor' }}</h4>
                    </div>
                    <div class="flex items-center space-x-2 mt-0.5">
                        <span class="text-[10px] text-purple-300/80 font-semibold truncate">Partner</span>
                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded-md bg-amber-400/20 text-amber-300 border border-amber-400/30 font-bold">
                            {{ auth()->user()->effective_commission_percentage ?? 10 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation Links -->
            <nav class="flex-1 px-3 space-y-1 overflow-y-auto sidebar-scroll pb-4">
                <!-- Group 1: Workspace -->
                <div class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                    Workspace
                </div>

                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.dashboard') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-house text-sm w-5 text-center {{ Request::routeIs('distributor.dashboard') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.hotels.index') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-hotel text-sm w-5 text-center {{ Request::routeIs('distributor.hotels.index') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>My Hotels</span>
                </a>

                <a href="{{ route('distributor.devices.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.devices.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-tv text-sm w-5 text-center {{ Request::routeIs('distributor.devices.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Connected TVs</span>
                </a>

                <!-- Group 2: Growth & Commercials -->
                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                    Operations
                </div>

                <a href="{{ route('distributor.hotels.create') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.hotels.create') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-circle-plus text-sm w-5 text-center {{ Request::routeIs('distributor.hotels.create') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Onboard Hotel</span>
                </a>

                <a href="{{ route('distributor.sales.create') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.sales.create') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-box-open text-sm w-5 text-center {{ Request::routeIs('distributor.sales.create') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Sell Package</span>
                </a>

                <a href="{{ route('distributor.sales.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.sales.index') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-receipt text-sm w-5 text-center {{ Request::routeIs('distributor.sales.index') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                    <span>Sales Ledger</span>
                </a>

                <!-- Group 3: Financial Revenue -->
                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/50 font-mono">
                    Finance
                </div>

                @php
                    $userWallet = auth()->user() ? auth()->user()->wallet : null;
                    $walletBalance = $userWallet ? $userWallet->available_balance : 0.00;
                @endphp
                <a href="{{ route('distributor.wallet.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('distributor.wallet.*') ? 'bg-white text-[#150A42] shadow-lg shadow-black/40' : 'text-purple-200/75 hover:text-white hover:bg-white/10' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-wallet text-sm w-5 text-center {{ Request::routeIs('distributor.wallet.*') ? 'text-[#150A42]' : 'text-purple-300' }}"></i>
                        <span>My Wallet</span>
                    </div>
                    @if($walletBalance > 0)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-400 text-slate-900 font-extrabold text-[10px] font-mono shadow-2xs">
                            ₹{{ number_format($walletBalance, 0) }}
                        </span>
                    @endif
                </a>
            </nav>

            <!-- Bottom Brand Info -->
            <div class="px-5 py-3 border-t border-white/[0.08] text-center shrink-0">
                <p class="text-[10px] text-purple-200/50 font-medium">{{ app_name() }} Distributor</p>
                <p class="text-[9px] text-purple-300/30 font-mono mt-0.5">© {{ date('Y') }} All Rights Reserved</p>
            </div>

            <!-- Sign Out Button -->
            <div class="p-3 border-t border-white/[0.08] bg-[#090320] shrink-0">
                <form action="{{ route('distributor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl bg-red-500/15 border border-red-500/30 text-rose-200 hover:text-white hover:bg-red-600 text-xs font-bold transition-all cursor-pointer shadow-xs">
                        <i class="fa-solid fa-right-from-bracket text-rose-400"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Modern Topbar Header (Matching Jobie / Super Admin) -->
            <header class="h-20 bg-white border-b border-slate-200/80 sticky top-0 z-40 px-3 sm:px-8 flex items-center justify-between shadow-xs">
                <!-- Left: Mobile Menu Toggle & Brand Logo -->
                <div class="flex items-center space-x-3 sm:space-x-4 flex-1 max-w-xl">
                    <!-- Modern 44x44 Touch Point Mobile Menu Button (Jan Mraz Modern Fries Style) -->
                    <button type="button" onclick="toggleSidebar()" aria-label="Toggle Navigation Menu" class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-[#F8FAFC] hover:bg-purple-50/70 border border-slate-200/90 shadow-xs hover:border-purple-200/80 flex flex-col items-center justify-center gap-[4.5px] focus:outline-hidden active:scale-95 transition-all md:hidden group cursor-pointer shrink-0">
                        <span class="w-5 h-[2.5px] rounded-full bg-slate-700 group-hover:bg-[#150A42] transition-colors"></span>
                        <span class="w-3.5 h-[2.5px] rounded-full bg-slate-700 group-hover:w-5 group-hover:bg-[#150A42] transition-all self-start ml-[11px]"></span>
                        <span class="w-5 h-[2.5px] rounded-full bg-slate-700 group-hover:bg-[#150A42] transition-colors"></span>
                    </button>

                    <!-- Mobile Brand Logo -->
                    <a href="{{ route('distributor.dashboard') }}" class="md:hidden flex items-center shrink-0">
                        <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto max-w-[140px] object-contain">
                    </a>

                    <!-- Page Title / Quick Breadcrumb for Desktop -->
                    <div class="hidden sm:block">
                        <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-tight">
                            @yield('page_title', 'Distributor Workspace')
                        </h1>
                        <p class="text-[11px] text-slate-400 font-semibold">Authorized Partner Network</p>
                    </div>
                </div>

                <!-- Right: Quick Wallet Pill, Profile Info & Logout -->
                <div class="flex items-center space-x-2 sm:space-x-3.5 shrink-0">
                    <!-- Quick Wallet Balance Pill -->
                    <a href="{{ route('distributor.wallet.index') }}" title="Available Wallet Balance" 
                       class="flex items-center space-x-2 px-3 sm:px-3.5 py-1.5 rounded-2xl bg-purple-50/80 hover:bg-purple-100/90 border border-purple-200/80 text-[#150A42] transition-all active:scale-95 shadow-2xs">
                        <i class="fa-solid fa-wallet text-purple-600 text-xs"></i>
                        <span class="text-xs font-black font-mono">₹{{ number_format($walletBalance, 2) }}</span>
                    </a>

                    <!-- User Profile Avatar & Name -->
                    <div class="flex items-center space-x-2.5 pl-1.5 sm:pl-2 py-1 pr-1 rounded-2xl bg-slate-50/80 border border-slate-200/60 shadow-2xs">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#150A42] to-purple-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-purple-100 shadow-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
                        </div>
                        <div class="hidden lg:block text-left pr-2">
                            <div class="text-xs font-extrabold text-slate-900 leading-tight">
                                {{ auth()->user()->name ?? 'Distributor' }}
                            </div>
                            <div class="text-[10px] text-slate-500 font-medium">
                                {{ auth()->user()->email ?? '' }}
                            </div>
                        </div>
                    </div>

                    <!-- Fast Logout Button -->
                    <form action="{{ route('distributor.logout') }}" method="POST" class="inline-flex m-0">
                        @csrf
                        <button type="submit" 
                                title="Sign Out of Distributor Portal" 
                                class="h-10 px-2.5 sm:px-3.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200/80 flex items-center space-x-1.5 text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs"
                                aria-label="Sign Out">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Container with Centralized Alerts -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto pb-24 md:pb-8">
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

                @if(session('error'))
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

                @if(session('info'))
                    <div class="mb-6 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-info text-base text-sky-600 shrink-0"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700 p-1 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-base text-amber-600 shrink-0"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700 p-1 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Bottom App-Style Floating Navigation Bar (5 Core Quick Actions) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 px-3 pb-3 pt-1 pointer-events-none">
        <div class="pointer-events-auto bg-white/95 backdrop-blur-md rounded-[26px] border border-slate-200/90 shadow-[0_-6px_25px_rgba(21,10,66,0.12)] px-2 py-1.5 flex items-center justify-around relative">
            
            <!-- 1. Dashboard -->
            @php $isDash = Request::routeIs('distributor.dashboard'); @endphp
            <a href="{{ route('distributor.dashboard') }}" class="relative flex flex-col items-center group transition-all {{ $isDash ? '-mt-5' : 'py-1 px-2' }}">
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

            <!-- 2. Hotels -->
            @php $isHotels = Request::routeIs('distributor.hotels.index'); @endphp
            <a href="{{ route('distributor.hotels.index') }}" class="relative flex flex-col items-center group transition-all {{ $isHotels ? '-mt-5' : 'py-1 px-2' }}">
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

            <!-- 3. Sell Package (Elevated Hero Action) -->
            @php $isSell = Request::routeIs('distributor.sales.create'); @endphp
            <a href="{{ route('distributor.sales.create') }}" class="relative flex flex-col items-center group transition-all -mt-5">
                <div class="w-13 h-13 rounded-full bg-gradient-to-tr from-[#150A42] to-purple-600 text-white flex items-center justify-center shadow-xl shadow-[#150A42]/40 border-4 border-white transition-transform group-hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-box-open text-lg text-amber-300"></i>
                </div>
                <span class="text-[10px] font-black text-[#150A42] mt-0.5 tracking-tight font-sans">Sell</span>
            </a>

            <!-- 4. TVs -->
            @php $isDevices = Request::routeIs('distributor.devices.*'); @endphp
            <a href="{{ route('distributor.devices.index') }}" class="relative flex flex-col items-center group transition-all {{ $isDevices ? '-mt-5' : 'py-1 px-2' }}">
                @if($isDevices)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-tv text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">TVs</span>
                @else
                    <i class="fa-solid fa-tv text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">TVs</span>
                @endif
            </a>

            <!-- 5. Wallet -->
            @php $isWallet = Request::routeIs('distributor.wallet.*'); @endphp
            <a href="{{ route('distributor.wallet.index') }}" class="relative flex flex-col items-center group transition-all {{ $isWallet ? '-mt-5' : 'py-1 px-2' }}">
                @if($isWallet)
                    <div class="w-12 h-12 rounded-full bg-[#150A42] text-white flex items-center justify-center shadow-lg shadow-[#150A42]/35 border-4 border-white transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-wallet text-base"></i>
                    </div>
                    <span class="text-[10px] font-extrabold text-[#150A42] mt-0.5 tracking-tight font-sans">Wallet</span>
                @else
                    <i class="fa-solid fa-wallet text-base text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    <span class="text-[10px] font-semibold text-slate-500 font-sans mt-0.5">Wallet</span>
                @endif
            </a>

        </div>
    </nav>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
