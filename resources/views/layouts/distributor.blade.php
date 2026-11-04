<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F5F7] font-sans antialiased selection:bg-[#C5A880] selection:text-[#111827]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Distributor Portal - ' . app_name())</title>

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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @yield('styles')
</head>
<body class="h-full bg-[#F4F5F7] text-[#111827]">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#111827]/70 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation (colors.md: Nordic Slate Blue #111827 & Muted Champagne #C5A880) -->
        <aside id="sidebar" class="w-64 bg-[#111827] text-[#94A3B8] flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-[#334155] shadow-2xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-[#334155]">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 overflow-hidden bg-[#FFFFFF] px-3 py-1.5 rounded-xl shadow-xs">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-10 sm:h-11 max-w-[170px] object-contain">
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-[#94A3B8] hover:text-[#FFFFFF]">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Distributor Badge (colors.md: Soft Mist #F1F5F9 & Hairline Gray border) -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-[#F1F5F9] border border-[#E5E7EB] flex items-center space-x-3 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-[#27374D] text-[#FFFFFF] flex items-center justify-center font-extrabold text-base shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-[#111827] truncate">{{ auth()->user()->name ?? 'Partner' }}</h4>
                    <p class="text-[11px] text-[#6B7280] font-bold truncate">Authorized Distributor</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.dashboard') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center {{ Request::routeIs('distributor.dashboard') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.index') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center {{ Request::routeIs('distributor.hotels.index') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>My Hotels</span>
                </a>
                <a href="{{ route('distributor.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.devices.*') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center {{ Request::routeIs('distributor.devices.*') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('distributor.hotels.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.create') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-plus-circle text-base w-5 text-center {{ Request::routeIs('distributor.hotels.create') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>Onboard Hotel</span>
                </a>
                <a href="{{ route('distributor.sales.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.index') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-receipt text-base w-5 text-center {{ Request::routeIs('distributor.sales.index') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>Sales Ledger</span>
                </a>
                <a href="{{ route('distributor.sales.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.create') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <i class="fa-solid fa-box-open text-base w-5 text-center {{ Request::routeIs('distributor.sales.create') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                    <span>Sell Package</span>
                </a>
                <a href="{{ route('distributor.wallet.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.wallet.*') ? 'bg-[#334155] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155]/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-wallet text-base w-5 text-center {{ Request::routeIs('distributor.wallet.*') ? 'text-[#C5A880]' : 'text-[#94A3B8]' }}"></i>
                        <span>My Wallet</span>
                    </div>
                    @php
                        $userWallet = auth()->user() ? auth()->user()->wallet : null;
                    @endphp
                    @if($userWallet && $userWallet->balance > 0)
                        <span class="px-2 py-0.5 rounded-full bg-[#F0FDF4] text-[#15803D] font-extrabold text-[10px] border border-[#DCFCE7]">
                            ₹{{ number_format($userWallet->balance, 0) }}
                        </span>
                    @endif
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-[#334155]">
                <form action="{{ route('distributor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2.5 px-4 py-2.5 rounded-xl text-xs font-bold text-[#94A3B8] hover:text-[#FFFFFF] hover:bg-[#334155] hover:border-[#C5A880] transition-colors border border-[#94A3B8]/20">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col md:pl-64 min-w-0">
            <!-- Topbar (colors.md: Pure White #FFFFFF with bottom border #E5E7EB) -->
            <header class="h-16 sm:h-20 bg-[#FFFFFF] border-b border-[#E5E7EB] sticky top-0 z-30 flex items-center justify-between px-6 sm:px-8">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-[#111827] hover:text-[#000000] p-2 rounded-lg bg-[#F4F5F7] border border-[#E5E7EB]">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-[#111827] tracking-tight">@yield('page_title', 'Distributor Control Center')</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Distributor Role Badge (colors.md: Soft Mist #F1F5F9 with Hairline border #E5E7EB) -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#F1F5F9] border border-[#E5E7EB] text-[#111827] text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-shield-check mr-1.5 text-[#27374D]"></i> Distributor Verified
                    </span>
                </div>
            </header>

            <!-- Alerts Banner (colors.md Status Colors) -->
            <div class="px-6 sm:px-8 pt-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-[#F0FDF4] border border-[#DCFCE7] text-[#15803D] text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-[#15803D] hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#FEE2E2] text-[#B91C1C] text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-[#B91C1C] hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>

            <!-- Page Body (colors.md: Soft Warm Alabaster #F4F5F7) -->
            <main class="flex-1 p-6 sm:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
