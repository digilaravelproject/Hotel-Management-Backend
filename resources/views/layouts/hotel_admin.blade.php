<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-indigo-500 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Panel') - HotelTV</title>

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
        <aside id="sidebar" class="w-64 bg-slate-900 text-slate-300 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-slate-800 shadow-xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                        <i class="fa-solid fa-tv text-lg"></i>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tight">Hotel<span class="text-indigo-400">TV</span></span>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Hotel Branding Card -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-slate-800/60 border border-slate-700/60 flex items-center space-x-3">
                @if(Auth::guard('hotel_admin')->user()->hotel_logo)
                    <img src="{{ asset(Auth::guard('hotel_admin')->user()->hotel_logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover border border-slate-700">
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold text-base">
                        {{ substr(Auth::guard('hotel_admin')->user()->hotel_name, 0, 1) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-white truncate">{{ Auth::guard('hotel_admin')->user()->hotel_name }}</h4>
                    <p class="text-[11px] text-indigo-400 truncate font-medium"><i class="fa-solid fa-location-dot mr-1"></i>{{ Auth::guard('hotel_admin')->user()->city ?? 'Active Hotel' }}</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('hotel.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-chart-line text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('hotel.ott.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.ott.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-brands fa-google-play text-base w-5 text-center"></i>
                    <span>OTT Apps Control</span>
                </a>
                <a href="{{ route('hotel.menus.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.menus.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-list-check text-base w-5 text-center"></i>
                    <span>TV Menus Visibility</span>
                </a>
                <a href="{{ route('hotel.amenities.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.amenities.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-spa text-base w-5 text-center"></i>
                    <span>Hotel Amenities</span>
                </a>
                <a href="{{ route('hotel.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.devices.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('hotel.guests.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.guests.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-users text-base w-5 text-center"></i>
                    <span>In-House Guests</span>
                </a>
                <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.hotel-info') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                    <span>Hotel Profile & Media</span>
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-slate-800/80">
                <form action="{{ route('hotel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl border border-slate-700/80 text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-bold transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Sign Out</span>
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
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('page_title', 'Hotel Admin')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="hidden sm:inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
                        <i class="fa-regular fa-clock mr-1.5 text-indigo-500"></i> {{ date('d M, Y') }}
                    </span>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profileDropdownContainer">
                        <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 p-1.5 rounded-full hover:bg-slate-100 transition-colors focus:outline-none">
                            @if(Auth::guard('hotel_admin')->user()->hotel_logo)
                                <img src="{{ asset(Auth::guard('hotel_admin')->user()->hotel_logo) }}" alt="Logo" class="w-9 h-9 rounded-full object-cover border border-slate-300">
                            @else
                                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                    {{ substr(Auth::guard('hotel_admin')->user()->owner_name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-xs font-bold text-slate-700 hidden lg:inline">{{ Auth::guard('hotel_admin')->user()->owner_name }}</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs hidden lg:inline"></i>
                        </button>

                        <!-- Menu Dropdown -->
                        <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200/80 rounded-2xl shadow-xl py-2 z-50">
                            <a href="{{ route('hotel.profile') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fa-regular fa-user text-indigo-500 w-4"></i>
                                <span>Update Account Profile</span>
                            </a>
                            <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-hotel text-violet-500 w-4"></i>
                                <span>Update Hotel Info</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('hotel.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
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
    </script>
    @yield('scripts')
</body>
</html>
