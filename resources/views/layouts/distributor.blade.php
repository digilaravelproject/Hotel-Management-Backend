<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Distributor Portal - ' . app_name())</title>

    <!-- Dynamic Application Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="apple-touch-icon" href="{{ app_favicon_url() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @yield('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-64 bg-slate-950 text-slate-300 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-slate-800 shadow-2xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-8 max-w-[150px] object-contain">
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Distributor Badge -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-base shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Partner' }}</h4>
                    <p class="text-[11px] text-amber-400 font-medium truncate">Authorized Distributor</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('distributor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.dashboard') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.index') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                    <span>My Hotels</span>
                </a>
                <a href="{{ route('distributor.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.devices.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('distributor.hotels.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.hotels.create') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-plus-circle text-base w-5 text-center"></i>
                    <span>Onboard Hotel</span>
                </a>
                <a href="{{ route('distributor.sales.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.index') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-receipt text-base w-5 text-center"></i>
                    <span>Sales Ledger</span>
                </a>
                <a href="{{ route('distributor.sales.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('distributor.sales.create') ? 'bg-amber-500 text-slate-950 font-bold shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-box-open text-base w-5 text-center"></i>
                    <span>Sell Package</span>
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-slate-800/80">
                <form action="{{ route('distributor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2.5 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors border border-rose-500/20">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col md:pl-64 min-w-0">
            <!-- Topbar -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-6 sm:px-8">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg bg-slate-100">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">@yield('page_title', 'Distributor Control Center')</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold">
                        <i class="fa-solid fa-shield-check mr-1.5 text-amber-500"></i> Distributor Verified
                    </span>
                </div>
            </header>

            <!-- Alerts Banner -->
            <div class="px-6 sm:px-8 pt-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
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
