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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets (Tailwind CSS v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono, .font-space-mono { font-family: 'Space Mono', monospace; }
        
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

        <!-- Sidebar Navigation (Deep Royal Indigo / Purple matching Jobie UI) -->
        <aside id="sidebar" class="w-64 bg-gradient-to-b from-[#241372] via-[#1D0C63] to-[#14064B] text-white flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-white/10 shadow-2xl">
            
            <!-- Sidebar Header & Logo -->
            <div class="h-24 flex items-center justify-center px-5 border-b border-white/10 relative">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center justify-center group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-14 sm:h-16 w-auto max-w-[200px] object-contain drop-shadow-md transition-transform duration-200 group-hover:scale-105">
                    </picture>
                </a>
                <button onclick="toggleSidebar()" class="absolute right-4 top-1/2 -translate-y-1/2 md:hidden text-purple-200 hover:text-white p-1.5 rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Director Status Pill (Jobie Style) -->
            <div class="p-3 mx-4 my-3 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center space-x-3 shadow-inner">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-900 flex items-center justify-center font-extrabold text-sm shadow-md shrink-0">
                    <i class="fa-solid fa-crown text-xs text-white"></i>
                </div>
                <div class="overflow-hidden min-w-0">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h4 class="text-xs font-bold text-white truncate">Platform Director</h4>
                    </div>
                    <p class="text-[10px] text-purple-200 font-mono truncate font-medium">Root Access</p>
                </div>
            </div>

            <!-- Menu Navigation Links -->
            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto sidebar-scroll pb-4">
                <div class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/60 font-mono">
                    Overview
                </div>

                <!-- Dashboard (Active: White Card with Purple Text matching Jobie) -->
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.dashboard') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-house text-sm w-5 text-center {{ Request::routeIs('super-admin.dashboard') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Hotels Directory -->
                <a href="{{ route('super-admin.hotels.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.hotels.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-hotel text-sm w-5 text-center {{ Request::routeIs('super-admin.hotels.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Hotels Directory</span>
                </a>

                <!-- Users & Permissions -->
                <a href="{{ route('super-admin.users.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.users.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-users-gear text-sm w-5 text-center {{ Request::routeIs('super-admin.users.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Staff & Partners</span>
                </a>

                <!-- Subscription Plans -->
                <a href="{{ route('super-admin.plans.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.plans.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-layer-group text-sm w-5 text-center {{ Request::routeIs('super-admin.plans.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Subscription Tiers</span>
                </a>

                <!-- Connected TVs -->
                <a href="{{ route('super-admin.devices.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.devices.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-tv text-sm w-5 text-center {{ Request::routeIs('super-admin.devices.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Hardware Fleet</span>
                </a>

                <!-- TV App OTA Releases -->
                <a href="{{ route('super-admin.templates.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.templates.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-code-branch text-sm w-5 text-center {{ Request::routeIs('super-admin.templates.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>OTA App Builds</span>
                </a>

                <!-- Flights & Airports -->
                <a href="{{ route('super-admin.flights.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.flights.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-plane-departure text-sm w-5 text-center {{ Request::routeIs('super-admin.flights.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Flights & Airports</span>
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-purple-300/60 font-mono">
                    Financial & System
                </div>

                <!-- Withdrawals -->
                <a href="{{ route('super-admin.withdrawals.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.withdrawals.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-money-bill-transfer text-sm w-5 text-center {{ Request::routeIs('super-admin.withdrawals.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                        <span>Withdrawals</span>
                    </div>
                    @php
                        $pendingWithdrawalsCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingWithdrawalsCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-900 font-extrabold text-[10px] font-mono">
                            {{ $pendingWithdrawalsCount }}
                        </span>
                    @endif
                </a>

                <!-- Commission Setup -->
                <a href="{{ route('super-admin.commissions.settings') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.commissions.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-percent text-sm w-5 text-center {{ Request::routeIs('super-admin.commissions.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Commissions</span>
                </a>

                <!-- FCM Config & Realtime -->
                <a href="{{ route('super-admin.firebase-settings.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.firebase-settings.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-fire text-sm w-5 text-center {{ Request::routeIs('super-admin.firebase-settings.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>FCM Realtime</span>
                </a>

                <!-- System & Branding -->
                <a href="{{ route('super-admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.settings.*') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-sliders text-sm w-5 text-center {{ Request::routeIs('super-admin.settings.*') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>System Branding</span>
                </a>

                <!-- My Profile & Security -->
                <a href="{{ route('super-admin.profile') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('super-admin.profile') ? 'bg-white text-[#241372] shadow-lg shadow-purple-950/20' : 'text-purple-200/80 hover:text-white hover:bg-white/10' }}">
                    <i class="fa-solid fa-user-gear text-sm w-5 text-center {{ Request::routeIs('super-admin.profile') ? 'text-[#241372]' : 'text-purple-300' }}"></i>
                    <span>Profile & Security</span>
                </a>
            </nav>

            <!-- Bottom Brand Info (Jobie style) -->
            <div class="px-5 py-4 border-t border-white/10 text-center">
                <p class="text-[10px] text-purple-200/60 font-medium">PAX TV Platform Admin</p>
                <p class="text-[9px] text-purple-300/40 font-mono mt-0.5">© {{ date('Y') }} All Rights Reserved</p>
            </div>

            <!-- Sign Out Button -->
            <div class="p-3 border-t border-white/10">
                <form action="{{ route('super-admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2 px-4 rounded-xl border border-white/10 text-purple-200 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 text-xs font-bold transition-all cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket text-red-400"></i>
                        <span>Admin Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            
            <!-- Modern Jobie Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200/80 sticky top-0 z-40 px-4 sm:px-8 flex items-center justify-between shadow-xs">
                
                <!-- Left Title & Mobile Hamburger -->
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="text-slate-700 hover:text-[#241372] p-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 md:hidden transition-colors">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                            @yield('page_title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <!-- Center Search Input (Jobie "Search something here...") -->
                <div class="hidden lg:flex items-center flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search properties, hardware, distributors..." class="w-full pl-4 pr-11 py-2.5 rounded-full bg-[#F3F5FA] border border-transparent focus:border-purple-300 focus:bg-white focus:outline-hidden text-xs text-slate-700 placeholder:text-slate-400 font-medium transition-all shadow-inner">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Right Action Icons & Profile Info (Jobie Style: Chat 18, Bell 62, User Avatar) -->
                <div class="flex items-center space-x-3 sm:space-x-4 shrink-0">
                    
                    <!-- Chat / Communication Icon with Badge (Jobie 18) -->
                    <a href="{{ route('super-admin.hotels.index') }}" title="System Messages & Inquiries" class="relative w-10 h-10 rounded-full bg-[#F3F5FA] hover:bg-purple-50 text-slate-600 hover:text-[#241372] flex items-center justify-center transition-all">
                        <i class="fa-regular fa-comment-dots text-sm"></i>
                        <span class="absolute -top-1 -right-1 px-1.5 py-0.2 rounded-full bg-[#241372] text-white text-[9px] font-bold font-mono shadow-xs">
                            18
                        </span>
                    </a>

                    <!-- Notification Bell with Badge (Jobie 62) -->
                    <a href="{{ route('super-admin.hotels.index', ['status' => 'pending']) }}" title="Pending Approvals & Notifications" class="relative w-10 h-10 rounded-full bg-[#F3F5FA] hover:bg-purple-50 text-slate-600 hover:text-[#241372] flex items-center justify-center transition-all">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute -top-1 -right-1 px-1.5 py-0.2 rounded-full bg-[#241372] text-white text-[9px] font-bold font-mono shadow-xs">
                            62
                        </span>
                    </a>

                    <!-- Super Admin Profile (Avatar + Name + Subtitle matching Jobie) -->
                    <a href="{{ route('super-admin.profile') }}" class="flex items-center space-x-3 pl-2 py-1 pr-1 rounded-2xl hover:bg-slate-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#241372] to-purple-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-purple-100 shadow-sm shrink-0">
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
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto">
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

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

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
