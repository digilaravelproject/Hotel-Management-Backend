<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8FAFC] font-sans antialiased selection:bg-[#E5A93C] selection:text-[#004B87]">
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
<body class="h-full bg-[#F8FAFC] text-[#1E293B]">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#004B87]/70 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation (colors.md: Sapphire #004B87 & Warm Gold #E5A93C) -->
        <aside id="sidebar" class="w-64 bg-[#004B87] text-[#E0F2FE] flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-[#0056B3] shadow-2xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-[#0056B3]">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 overflow-hidden bg-[#FFFFFF] px-3 py-1.5 rounded-xl shadow-xs">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-8 max-w-[140px] object-contain">
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-[#E0F2FE] hover:text-[#FFFFFF]">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Distributor Badge (colors.md: Soft Gold Tint #FEF9EE & Warm Amber Gold #E5A93C) -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/40 flex items-center space-x-3 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-[#E5A93C] text-[#004B87] flex items-center justify-center font-extrabold text-base shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-[#004B87] truncate">{{ auth()->user()->name ?? 'Partner' }}</h4>
                    <p class="text-[11px] text-[#C88E26] font-bold truncate">Authorized Distributor</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.dashboard') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center {{ Request::routeIs('distributor.dashboard') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.index') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center {{ Request::routeIs('distributor.hotels.index') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>My Hotels</span>
                </a>
                <a href="{{ route('distributor.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.devices.*') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center {{ Request::routeIs('distributor.devices.*') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('distributor.hotels.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.create') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-plus-circle text-base w-5 text-center {{ Request::routeIs('distributor.hotels.create') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>Onboard Hotel</span>
                </a>
                <a href="{{ route('distributor.sales.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.index') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-receipt text-base w-5 text-center {{ Request::routeIs('distributor.sales.index') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>Sales Ledger</span>
                </a>
                <a href="{{ route('distributor.sales.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.create') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <i class="fa-solid fa-box-open text-base w-5 text-center {{ Request::routeIs('distributor.sales.create') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                    <span>Sell Package</span>
                </a>
                <a href="{{ route('distributor.wallet.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.wallet.*') ? 'bg-[#0056B3] text-[#E5A93C] font-bold border-l-4 border-[#E5A93C] shadow-md shadow-[#0056B3]/40' : 'text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#0056B3]/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-wallet text-base w-5 text-center {{ Request::routeIs('distributor.wallet.*') ? 'text-[#E5A93C]' : 'text-[#E0F2FE]' }}"></i>
                        <span>My Wallet</span>
                    </div>
                    @php
                        $userWallet = auth()->user() ? auth()->user()->wallet : null;
                    @endphp
                    @if($userWallet && $userWallet->balance > 0)
                        <span class="px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#16A34A] font-extrabold text-[10px] border border-[#16A34A]/30">
                            ₹{{ number_format($userWallet->balance, 0) }}
                        </span>
                    @endif
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-[#0056B3]">
                <form action="{{ route('distributor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2.5 px-4 py-2.5 rounded-xl text-xs font-bold text-[#E0F2FE] hover:text-[#FFFFFF] hover:bg-[#E5A93C]/20 hover:border-[#E5A93C] transition-colors border border-[#E0F2FE]/20">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col md:pl-64 min-w-0">
            <!-- Topbar (colors.md: Pure White #FFFFFF with bottom border #E2E8F0) -->
            <header class="h-16 sm:h-20 bg-[#FFFFFF] border-b border-[#E2E8F0] sticky top-0 z-30 flex items-center justify-between px-6 sm:px-8">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-[#1E293B] hover:text-[#004B87] p-2 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0]">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-[#1E293B] tracking-tight">@yield('page_title', 'Distributor Control Center')</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Distributor Role Badge (colors.md: Soft Gold Tint #FEF9EE with Gold #E5A93C border/text) -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#FEF9EE] border border-[#E5A93C]/50 text-[#E5A93C] text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-shield-check mr-1.5 text-[#E5A93C]"></i> Distributor Verified
                    </span>
                </div>
            </header>

            <!-- Alerts Banner (colors.md Status Colors) -->
            <div class="px-6 sm:px-8 pt-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-[#DCFCE7] border border-[#16A34A]/30 text-[#16A34A] text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-[#16A34A] hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-[#FEE2E2] border border-[#DC2626]/30 text-[#DC2626] text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-[#DC2626] hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>

            <!-- Page Body (colors.md: Light UI Gray #F8FAFC) -->
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
