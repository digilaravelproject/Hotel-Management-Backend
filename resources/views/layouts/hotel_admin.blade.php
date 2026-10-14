<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8FAFC] font-sans antialiased selection:bg-[#C5A059] selection:text-[#6E1841]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Panel') - {{ app_name() }}</title>

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
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-[#6E1841]/70 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation (Imperial Burgundy #6E1841 & Champagne Gold #C5A059 Theme) -->
        <aside id="sidebar" class="w-64 bg-[#6E1841] text-[#FCE7F3] flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-[#8A1C56] shadow-xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-[#8A1C56]/80">
                <a href="{{ route('hotel.dashboard') }}" class="flex items-center space-x-3 overflow-hidden bg-[#FFFFFF] px-3 py-1.5 rounded-xl shadow-xs">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-8 max-w-[140px] object-contain">
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-[#FCE7F3] hover:text-[#FFFFFF]">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Hotel Branding Card (Warm Silk #F9F5EB / Champagne Gold #C5A059 Theme) -->
            <div class="p-3.5 mx-3 my-4 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/40 flex items-center space-x-3 shadow-xs">
                @if(Auth::guard('hotel_admin')->user()->hotel_logo)
                    <img src="{{ asset(Auth::guard('hotel_admin')->user()->hotel_logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover border border-[#C5A059]/40 shadow-xs">
                @else
                    <div class="w-10 h-10 rounded-xl bg-[#C5A059] text-[#6E1841] flex items-center justify-center font-extrabold text-base shadow-xs">
                        {{ substr(Auth::guard('hotel_admin')->user()->hotel_name, 0, 1) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-[#6E1841] truncate">{{ Auth::guard('hotel_admin')->user()->hotel_name }}</h4>
                    <p class="text-[11px] text-[#A88440] truncate font-bold"><i class="fa-solid fa-location-dot mr-1"></i>{{ Auth::guard('hotel_admin')->user()->city ?? 'Active Hotel' }}</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('hotel.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.dashboard') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-chart-line text-base w-5 text-center {{ Request::routeIs('hotel.dashboard') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('hotel.package') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.package') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-brands fa-google-play text-base w-5 text-center {{ Request::routeIs('hotel.package') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>OTT Apps Control</span>
                </a>
                <a href="{{ route('hotel.menus.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.menus.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-list-check text-base w-5 text-center {{ Request::routeIs('hotel.menus.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>TV Menus Visibility</span>
                </a>
                <a href="{{ route('hotel.amenities.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.amenities.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-spa text-base w-6 text-center {{ Request::routeIs('hotel.amenities.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Hotel Amenities</span>
                </a>
                <a href="{{ route('hotel.room-infos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.room-infos.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-bed text-base w-6 text-center {{ Request::routeIs('hotel.room-infos.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Room Information</span>
                </a>
                <a href="{{ route('hotel.facilities.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.facilities.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-hotel text-base w-6 text-center {{ Request::routeIs('hotel.facilities.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Hotel Facilities</span>
                </a>
                <a href="{{ route('hotel.our-city.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.our-city.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-city text-base w-6 text-center {{ Request::routeIs('hotel.our-city.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Our City Guide</span>
                </a>
                <a href="{{ route('hotel.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.devices.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center {{ Request::routeIs('hotel.devices.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('hotel.flights.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.flights.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-plane-departure text-base w-5 text-center {{ Request::routeIs('hotel.flights.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Airport & Flights</span>
                </a>
                <a href="{{ route('hotel.themes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.themes.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-palette text-base w-5 text-center {{ Request::routeIs('hotel.themes.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>TV Themes & Styling</span>
                </a>
                <a href="{{ route('hotel.guests.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.guests.*') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-users text-base w-5 text-center {{ Request::routeIs('hotel.guests.*') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>In-House Guests</span>
                </a>
                <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('hotel.hotel-info') ? 'bg-[#8A1C56] text-[#C5A059] font-bold border-l-4 border-[#C5A059] shadow-md shadow-[#8A1C56]/40' : 'text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#8A1C56]/60' }}">
                    <i class="fa-solid fa-sliders text-base w-5 text-center {{ Request::routeIs('hotel.hotel-info') ? 'text-[#C5A059]' : 'text-[#FCE7F3]' }}"></i>
                    <span>Hotel Profile & Media</span>
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-[#8A1C56]">
                <form action="{{ route('hotel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl border border-[#FCE7F3]/20 text-[#FCE7F3] hover:text-[#FFFFFF] hover:bg-[#C5A059]/20 hover:border-[#C5A059] text-xs font-bold transition-all shadow-xs">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Topbar Header (colors.md: Pure White #FFFFFF with bottom border #E2E8F0) -->
            <header class="h-16 sm:h-20 bg-[#FFFFFF] border-b border-[#E2E8F0] sticky top-0 z-40 px-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-[#1E293B] hover:text-[#6E1841] p-2 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0]">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-extrabold text-[#1E293B] tracking-tight">@yield('page_title', 'Hotel Admin')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Hotel Admin Role Badge (colors.md: Soft Gold Tint #F9F5EB, border/text Champagne Gold #C5A059) -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/50 text-[#C5A059] text-xs font-bold shadow-xs">
                        <i class="fa-solid fa-hotel mr-1.5 text-[#C5A059]"></i> Hotel Admin
                    </span>

                    <span class="hidden sm:inline-flex items-center text-xs font-semibold text-[#64748B] bg-[#F8FAFC] border border-[#E2E8F0] px-3 py-1.5 rounded-xl">
                        <i class="fa-regular fa-clock mr-1.5 text-[#C5A059]"></i> {{ date('d M, Y') }}
                    </span>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profileDropdownContainer">
                        <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 p-1.5 rounded-full hover:bg-[#F8FAFC] transition-colors focus:outline-none">
                            @if(Auth::guard('hotel_admin')->user()->hotel_logo)
                                <img src="{{ asset(Auth::guard('hotel_admin')->user()->hotel_logo) }}" alt="Logo" class="w-9 h-9 rounded-full object-cover border border-[#C5A059]/40">
                            @else
                                <div class="w-9 h-9 rounded-full bg-[#C5A059] text-[#6E1841] flex items-center justify-center font-extrabold text-sm shadow-xs">
                                    {{ substr(Auth::guard('hotel_admin')->user()->owner_name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-xs font-bold text-[#1E293B] hidden lg:inline">{{ Auth::guard('hotel_admin')->user()->owner_name }}</span>
                            <i class="fa-solid fa-chevron-down text-[#64748B] text-xs hidden lg:inline"></i>
                        </button>

                        <!-- Menu Dropdown -->
                        <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-[#FFFFFF] border border-[#E2E8F0] rounded-2xl shadow-xl py-2 z-50">
                            <a href="{{ route('hotel.profile') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-[#1E293B] hover:bg-[#F9F5EB] hover:text-[#A88440] transition-colors">
                                <i class="fa-regular fa-user text-[#C5A059] w-4"></i>
                                <span>Update Account Profile</span>
                            </a>
                            <a href="{{ route('hotel.hotel-info') }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-[#1E293B] hover:bg-[#F9F5EB] hover:text-[#A88440] transition-colors">
                                <i class="fa-solid fa-hotel text-[#C5A059] w-4"></i>
                                <span>Update Hotel Info</span>
                            </a>
                            <div class="border-t border-[#E2E8F0] my-1"></div>
                            <form action="{{ route('hotel.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-[#DC2626] hover:bg-[#FEE2E2] transition-colors text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alerts (colors.md Status Colors) -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#DCFCE7] border border-[#16A34A]/30 text-[#16A34A] text-xs font-bold flex items-center space-x-2 shadow-xs">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#FEE2E2] border border-[#DC2626]/30 text-[#DC2626] text-xs font-bold flex items-center space-x-2 shadow-xs">
                        <i class="fa-solid fa-circle-exclamation text-base"></i>
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

        // Global loader overlay on form submits and page unloads (colors.md: Burgundy & Champagne Gold)
        document.addEventListener('DOMContentLoaded', function() {
            // Create loader overlay HTML dynamically if not present
            if (!document.getElementById('globalAppLoader')) {
                const loaderDiv = document.createElement('div');
                loaderDiv.id = 'globalAppLoader';
                loaderDiv.className = 'fixed inset-0 bg-[#6E1841]/60 backdrop-blur-xs z-[9999] hidden flex flex-col items-center justify-center transition-all duration-300';
                loaderDiv.innerHTML = `
                    <div class="bg-[#FFFFFF] text-[#1E293B] p-6 rounded-3xl shadow-2xl border border-[#E2E8F0] flex flex-col items-center space-y-4 max-w-xs w-full mx-4 text-center transform scale-100 transition-transform">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-4 border-[#F9F5EB]"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-[#C5A059] border-t-transparent animate-spin"></div>
                            <i class="fa-solid fa-cloud-arrow-up text-[#C5A059] text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-extrabold text-[#1E293B]">Processing Request...</h4>
                            <p class="text-[11px] text-[#64748B] font-medium">Updating server & syncing TV devices</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(loaderDiv);
            }
        });

        function showGlobalLoader(form = null) {
            // Hide any parent modal or open modals immediately so there is no double popup collision
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

            // Close SweetAlert if visible
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

        // Global SweetAlert2 confirmation handler for forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.ajaxForm === "true" || form.dataset.noLoader === "true") {
                return; // Do not trigger full screen loader for background AJAX forms
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
                    confirmButtonColor: '#C5A059',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-3xl border border-[#E2E8F0] shadow-2xl font-sans',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md',
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
