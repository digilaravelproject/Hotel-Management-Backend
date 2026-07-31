<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-indigo-500 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Control Center') - HotelTV</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets (Tailwind CSS v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-rose-500/20">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tight">Super<span class="text-rose-500">Admin</span></span>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Admin Badge -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                    SA
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-white truncate">Platform Director</h4>
                    <p class="text-[11px] text-rose-400 font-medium truncate">System Administrator</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.dashboard') ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                    <span>Control Dashboard</span>
                </a>
                <a href="{{ route('super-admin.hotels.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.hotels.*') ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                    <span>Hotels Directory</span>
                </a>
                <a href="{{ route('super-admin.plans.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.plans.*') ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-layer-group text-base w-5 text-center"></i>
                    <span>Subscription Plans</span>
                </a>
                <a href="{{ route('super-admin.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.devices.*') ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('super-admin.templates.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.templates.*') ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i class="fa-solid fa-code-branch text-base w-5 text-center"></i>
                    <span>TV App OTA Releases</span>
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-slate-800/80">
                <form action="{{ route('super-admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:bg-rose-600/20 hover:border-rose-500/40 text-xs font-bold transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Admin Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Topbar Header -->
            <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200/80 sticky top-0 z-40 px-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg bg-slate-100">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('page_title', 'Super Admin Center')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="hidden sm:inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-rose-500"></i> Global Administrator
                    </span>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-base"></i>
                        <span>{{ session('error') }}</span>
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
    </script>
    @yield('scripts')
</body>
</html>
