<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F5F7] font-sans antialiased selection:bg-[#C5A880] selection:text-[#111827]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Control Center') - {{ app_setting('app_name', 'DigiHotel') }}</title>

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

        <!-- Sidebar Navigation (colors.md: Obsidian Slate #111827 & Warm Bronze #A68A64 / Muted Champagne #C5A880) -->
        <aside id="sidebar" class="w-64 bg-[#111827] text-[#9CA3AF] flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0 border-r border-[#1F2937] shadow-2xl">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-[#1F2937]">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 overflow-hidden bg-[#FFFFFF] px-3 py-1.5 rounded-xl shadow-xs">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-8 max-w-[140px] object-contain">
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-[#9CA3AF] hover:text-[#FFFFFF]">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Admin Badge (colors.md: Soft Tint Warm Sand #F5F2EB) -->
            <div class="p-4 mx-3 my-4 rounded-2xl bg-[#F5F2EB] border border-[#E5E7EB] flex items-center space-x-3 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-[#A68A64] text-[#FFFFFF] flex items-center justify-center font-extrabold text-base shadow-xs">
                    SA
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-bold text-[#111827] truncate">Platform Director</h4>
                    <p class="text-[11px] text-[#8C7250] font-bold truncate">System Administrator</p>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.dashboard') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center {{ Request::routeIs('super-admin.dashboard') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Control Dashboard</span>
                </a>
                <a href="{{ route('super-admin.hotels.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.hotels.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-hotel text-base w-5 text-center {{ Request::routeIs('super-admin.hotels.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Hotels Directory</span>
                </a>
                <a href="{{ route('super-admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.users.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-users-gear text-base w-5 text-center {{ Request::routeIs('super-admin.users.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Users & Permissions</span>
                </a>
                <a href="{{ route('super-admin.plans.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.plans.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-layer-group text-base w-5 text-center {{ Request::routeIs('super-admin.plans.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Subscription Plans</span>
                </a>
                <a href="{{ route('super-admin.devices.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.devices.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-tv text-base w-5 text-center {{ Request::routeIs('super-admin.devices.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Connected TVs</span>
                </a>
                <a href="{{ route('super-admin.templates.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.templates.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-code-branch text-base w-5 text-center {{ Request::routeIs('super-admin.templates.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>TV App OTA Releases</span>
                </a>
                <a href="{{ route('super-admin.flights.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.flights.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-plane-departure text-base w-5 text-center {{ Request::routeIs('super-admin.flights.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Flights & Airports</span>
                </a>
                <a href="{{ route('super-admin.firebase-settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.firebase-settings.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-fire text-base w-5 text-center {{ Request::routeIs('super-admin.firebase-settings.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>FCM Config & Realtime</span>
                </a>
                <a href="{{ route('super-admin.withdrawals.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.withdrawals.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-money-bill-transfer text-base w-5 text-center {{ Request::routeIs('super-admin.withdrawals.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                        <span>Withdrawals</span>
                    </div>
                    @php
                        $pendingWithdrawalsCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingWithdrawalsCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-[#FFFBEB] text-[#B45309] border border-[#FEF3C7] font-extrabold text-[10px] animate-pulse">
                            {{ $pendingWithdrawalsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('super-admin.commissions.settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.commissions.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-percent text-base w-5 text-center {{ Request::routeIs('super-admin.commissions.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>Commission Setup</span>
                </a>
                <a href="{{ route('super-admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.settings.*') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-sliders text-base w-5 text-center {{ Request::routeIs('super-admin.settings.*') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>System & Branding</span>
                </a>
                <a href="{{ route('super-admin.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ Request::routeIs('super-admin.profile') ? 'bg-[#1F2937] text-[#FFFFFF] font-bold border-l-4 border-[#C5A880] shadow-sm' : 'text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937]/60' }}">
                    <i class="fa-solid fa-user-gear text-base w-5 text-center {{ Request::routeIs('super-admin.profile') ? 'text-[#C5A880]' : 'text-[#9CA3AF]' }}"></i>
                    <span>My Profile & Security</span>
                </a>
            </nav>

            <!-- Sign Out Button -->
            <div class="p-4 border-t border-[#1F2937]">
                <form action="{{ route('super-admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl border border-[#9CA3AF]/20 text-[#9CA3AF] hover:text-[#FFFFFF] hover:bg-[#1F2937] hover:border-[#C5A880] text-xs font-bold transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Admin Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Topbar Header (colors.md: Pure White #FFFFFF with bottom border #E5E7EB) -->
            <header class="h-16 sm:h-20 bg-[#FFFFFF] border-b border-[#E5E7EB] sticky top-0 z-40 px-4 sm:px-6 flex items-center justify-between">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <button onclick="toggleSidebar()" class="md:hidden text-[#111827] hover:text-[#000000] p-2 rounded-lg bg-[#F4F5F7] border border-[#E5E7EB] shrink-0">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <h1 class="text-base sm:text-xl font-extrabold text-[#111827] tracking-tight truncate">@yield('page_title', 'Super Admin Center')</h1>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    <!-- Live Website Link -->
                    <a href="{{ route('landing') }}" target="_blank" title="View Live Website" class="hidden sm:inline-flex items-center space-x-1.5 text-xs font-semibold text-[#111827] bg-[#FFFFFF] border border-[#E5E7EB] hover:bg-[#F9FAFB] px-3 py-2 rounded-xl transition-all">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-[#6B7280]"></i>
                        <span>Live Website</span>
                    </a>

                    <!-- Branding & System Settings Quick Action -->
                    <a href="{{ route('super-admin.settings.index') }}" title="Application Branding & Settings" class="inline-flex items-center space-x-1.5 text-xs font-bold {{ Request::routeIs('super-admin.settings.*') ? 'bg-[#A68A64] text-[#FFFFFF]' : 'text-[#111827] bg-[#FFFFFF] border border-[#E5E7EB] hover:bg-[#F5F2EB] hover:text-[#8C7250]' }} px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl transition-all">
                        <i class="fa-solid fa-sliders {{ Request::routeIs('super-admin.settings.*') ? 'text-[#FFFFFF]' : 'text-[#A68A64]' }}"></i>
                        <span class="hidden sm:inline">Branding</span>
                    </a>

                    <!-- Super Admin Profile Badge (colors.md: Soft Tint Warm Sand #F5F2EB, hairline border #E5E7EB) -->
                    <a href="{{ route('super-admin.profile') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#111827] bg-[#F5F2EB] border border-[#E5E7EB] hover:border-[#C5A880] px-3 py-1.5 sm:py-2 rounded-xl transition-all shadow-xs">
                        <i class="fa-solid fa-user-shield text-[#A68A64]"></i>
                        <span class="hidden md:inline">{{ Auth::guard('super_admin')->user()->email ?? 'Super Admin' }}</span>
                        <span class="px-1.5 py-0.5 rounded-md bg-[#A68A64] text-[#FFFFFF] text-[10px] font-extrabold uppercase ml-1">Super Admin</span>
                    </a>
                </div>
            </header>

            <!-- Main Content (colors.md: Soft Warm Alabaster #F4F5F7) -->
            <main class="flex-1 p-4 sm:p-6 lg:p-6 xl:p-8 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#F0FDF4] border border-[#DCFCE7] text-[#15803D] text-xs font-bold flex items-center space-x-2 shadow-xs">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#FEF2F2] border border-[#FEE2E2] text-[#B91C1C] text-xs font-bold flex items-center space-x-2 shadow-xs">
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

        // Global loader overlay on form submits (Adhering to colors.md Obsidian Slate & Warm Bronze)
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('globalAppLoader')) {
                const loaderDiv = document.createElement('div');
                loaderDiv.id = 'globalAppLoader';
                loaderDiv.className = 'fixed inset-0 bg-[#111827]/60 backdrop-blur-xs z-[9999] hidden flex flex-col items-center justify-center transition-all duration-300';
                loaderDiv.innerHTML = `
                    <div class="bg-[#111827] text-[#9CA3AF] p-6 rounded-3xl shadow-2xl border border-[#1F2937] flex flex-col items-center space-y-4 max-w-xs w-full mx-4 text-center">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-4 border-[#1F2937]"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-[#C5A880] border-t-transparent animate-spin"></div>
                            <i class="fa-solid fa-bolt text-[#C5A880] text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-extrabold text-[#FFFFFF]">Processing Admin Request...</h4>
                            <p class="text-[11px] text-[#9CA3AF] font-medium">Syncing database & connected devices</p>
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
                    confirmButtonColor: '#B91C1C',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-3xl border border-[#E5E7EB] shadow-2xl font-sans',
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
