@extends('layouts.landing')

@section('title', 'HotelTV Connect - Premium Hotel Smart TV OS & Guest Experience Platform')

@section('content')
<div class="relative bg-slate-50 text-slate-900 font-sans min-h-screen selection:bg-indigo-600 selection:text-white overflow-hidden">

    <!-- Organic Background Diffused Glows -->
    <div class="absolute -top-40 left-1/4 w-[600px] h-[600px] bg-indigo-200/40 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] bg-sky-200/40 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-slate-200/80 px-6 lg:px-16 py-4 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tv text-lg"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-slate-900 font-['Syne']">
                    Hotel<span class="text-indigo-600">TV</span>
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-9 text-xs font-bold text-slate-600">
                <a href="#features" class="hover:text-indigo-600 transition-colors">Features</a>
                <a href="#preview" class="hover:text-indigo-600 transition-colors">Interactive OS</a>
                <a href="#social-proof" class="hover:text-indigo-600 transition-colors">Hospitality Partners</a>
                <a href="#plans" class="hover:text-indigo-600 transition-colors">Pricing & Plans</a>
                <a href="#faq" class="hover:text-indigo-600 transition-colors">FAQ</a>
            </nav>

            <!-- Status Flash notifications -->
            @if(session('error'))
                <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                @if(Auth::guard('hotel_admin')->check())
                    <a href="{{ route('hotel.dashboard') }}" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('hotel.login') }}" class="hidden sm:inline-flex px-5 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs transition-all shadow-xs">
                        Hotel Login
                    </a>

                    <!-- Access Portal Dropdown (Desktop Hover + Mobile Touch Toggle) -->
                    <div class="relative">
                        <button type="button" id="accessPortalBtn" onclick="toggleAccessPortal(event)" class="px-4 py-2.5 sm:px-5 sm:py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-1.5 sm:space-x-2">
                            <span>Access Portal</span>
                            <i id="accessPortalChevron" class="fa-solid fa-chevron-down text-[10px] transition-transform"></i>
                        </button>

                        <div id="accessPortalMenu" class="hidden absolute right-0 top-[calc(100%+8px)] w-56 bg-white border border-slate-200/90 rounded-2xl shadow-2xl p-2 z-50 space-y-1">
                            <button type="button" onclick="openRegisterModal(); closeAccessPortal();" class="w-full flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 text-left transition-colors">
                                <i class="fa-solid fa-user-plus text-indigo-600"></i>
                                <span>Register Hotel</span>
                            </button>
                            <a href="{{ route('hotel.login') }}" class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-right-to-bracket text-emerald-600"></i>
                                <span>Hotel Admin Login</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('super-admin.login') }}" class="flex items-center space-x-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                                <span>Super Admin Panel</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Asymmetric Human-Designed Hero Section -->
    <section class="pt-16 pb-28 px-6 lg:px-16 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Column: Copy & Social Proof -->
            <div class="lg:col-span-6 space-y-8 text-left">
                <!-- Release Pill -->
                <div class="inline-flex items-center space-x-3 px-3.5 py-1.5 rounded-full bg-indigo-50/80 border border-indigo-200/80 text-indigo-700 text-xs font-bold shadow-xs">
                    <span class="px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[10px] font-extrabold uppercase tracking-wide">v2.4 Release</span>
                    <span class="text-slate-600 font-medium">Smart TV Concierge Operating System</span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12] text-slate-900 font-['Syne']">
                    Elevate Every Room with <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-rose-600 bg-clip-text text-transparent">Digital Luxury Concierge</span>
                </h1>

                <!-- Sub-description -->
                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed max-w-xl">
                    Replace generic TV screens with fully branded interactive guest hubs. Control streaming apps, room service menus, and guest messages centrally across thousands of room displays.
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button onclick="openRegisterModal()" class="px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xl shadow-indigo-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                        <span>Get Started Free</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    <a href="#preview" class="px-8 py-4 rounded-2xl bg-white border border-slate-200/80 text-slate-700 hover:bg-slate-100 font-bold text-xs shadow-xs transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-circle-play text-indigo-600"></i>
                        <span>Watch 2-Min Demo</span>
                    </a>
                </div>

                <!-- Human Social Proof & Ratings -->
                <div class="pt-4 border-t border-slate-200/80 flex items-center space-x-6">
                    <div class="flex -space-x-3 overflow-hidden">
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" alt="Hotel Manager">
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80" alt="Resort Operations Director">
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" alt="Hospitality Tech Officer">
                    </div>
                    <div>
                        <div class="flex items-center space-x-1 text-amber-400 text-xs">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-slate-900 font-extrabold text-xs ml-1.5">4.9 / 5</span>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Trusted by 500+ Luxury Hotels & Resorts</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Human-Designed Visual Container & Floating Cards -->
            <div class="lg:col-span-6 relative pt-8 pb-10 px-4 sm:px-8">
                <!-- Main Frame Container -->
                <div class="relative bg-slate-900 rounded-3xl p-3 sm:p-4 border-8 border-slate-800 shadow-2xl shadow-indigo-900/20 aspect-video overflow-hidden text-white">
                    <!-- Unsplash Luxury Suite Background Behind UI -->
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80" alt="Hotel Room Suite Background" class="absolute inset-0 w-full h-full object-cover opacity-40">

                    <!-- Overlay TV Screen Mockup -->
                    <div class="relative z-10 h-full bg-slate-950/70 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span class="font-extrabold text-xs sm:text-sm tracking-wide font-['Syne']">The Grand Oberoi & Spa</span>
                            </div>
                            <span class="text-[10px] font-bold bg-white/10 px-3 py-1 rounded-full border border-white/10 text-indigo-300">Welcome, Mr. Harrison</span>
                        </div>

                        <div class="text-center space-y-2 my-auto">
                            <div class="w-12 h-12 mx-auto rounded-full bg-indigo-600/40 border border-indigo-400/50 flex items-center justify-center cursor-pointer hover:scale-110 transition-transform">
                                <i class="fa-solid fa-play text-lg text-indigo-300 ml-0.5"></i>
                            </div>
                            <h4 class="text-sm sm:text-base font-extrabold tracking-tight">Press Play to Access In-Room Services</h4>
                            <p class="text-[10px] sm:text-xs text-slate-300">Dining • Live TV • Streaming • Housekeeping</p>
                        </div>

                        <div class="grid grid-cols-4 gap-2 border-t border-white/10 pt-3 text-[10px] text-center font-bold">
                            <div class="p-2 rounded-xl bg-indigo-600/30 border border-indigo-400/30 text-indigo-200"><i class="fa-solid fa-tv mr-1"></i>TV</div>
                            <div class="p-2 rounded-xl bg-white/10 text-slate-200"><i class="fa-solid fa-utensils mr-1"></i>Dining</div>
                            <div class="p-2 rounded-xl bg-white/10 text-slate-200"><i class="fa-solid fa-bell mr-1"></i>Services</div>
                            <div class="p-2 rounded-xl bg-white/10 text-slate-200"><i class="fa-solid fa-film mr-1"></i>OTT</div>
                        </div>
                    </div>
                </div>

                <!-- Floating Feature Card 1: Remote OTA Sync -->
                <div class="hidden sm:flex absolute -bottom-4 left-0 bg-white/95 backdrop-blur-xl border border-slate-200/80 p-4 rounded-2xl shadow-xl space-x-3 items-center max-w-xs z-20">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <h5 class="text-xs font-extrabold text-slate-900">Instant OTA Push Sync</h5>
                        <p class="text-[10px] text-slate-500 font-medium">Updates menu & logos in under 2 seconds.</p>
                    </div>
                </div>

                <!-- Floating Feature Card 2: 16-Digit License -->
                <div class="hidden sm:flex absolute -top-2 right-0 bg-white/95 backdrop-blur-xl border border-slate-200/80 p-3.5 rounded-2xl shadow-xl space-x-3 items-center z-20">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">One-Click License</span>
                        <span class="text-xs font-mono font-extrabold text-indigo-600">8934-2901-4412-9901</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Interactive Logo Ticker / Partner Logos -->
    <section id="social-proof" class="py-12 border-y border-slate-200/80 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 text-center space-y-6">
            <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Powering Next-Gen Displays Across Industry Leaders</p>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all">
                <div class="flex items-center space-x-2 font-extrabold text-slate-700 text-sm"><i class="fa-solid fa-hotel text-indigo-600 text-lg"></i><span>MARRIOTT</span></div>
                <div class="flex items-center space-x-2 font-extrabold text-slate-700 text-sm"><i class="fa-solid fa-crown text-indigo-600 text-lg"></i><span>TAJ HOTELS</span></div>
                <div class="flex items-center space-x-2 font-extrabold text-slate-700 text-sm"><i class="fa-solid fa-building-columns text-indigo-600 text-lg"></i><span>HYATT REGENCY</span></div>
                <div class="flex items-center space-x-2 font-extrabold text-slate-700 text-sm"><i class="fa-solid fa-spa text-indigo-600 text-lg"></i><span>RADISSON BLU</span></div>
            </div>
        </div>
    </section>

    <!-- Features Showcase -->
    <section id="features" class="py-24 px-6 lg:px-16 bg-slate-50">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-['Syne']">Designed for Hospitality Operations</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    A comprehensive hospitality suite that manages guest entertainment, branding, and service requests from one centralized cloud dashboard.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-indigo-300 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold">
                        <i class="fa-solid fa-paintbrush"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 font-['Syne']">Custom Hotel Branding</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Display your hotel logo, custom welcome greetings, promotional banners, and local weather updates on every TV startup.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-violet-300 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600 text-xl font-bold">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 font-['Syne']">Central Menu Visibility</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Toggle menu visibility across all TVs or individual room devices globally—enable or hide apps like Live TV, Screen Cast, or Flights.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-rose-300 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 text-xl font-bold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 font-['Syne']">16-Digit Instant Licensing</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Instant 16-digit license code generation upon subscription. Enter the code once on your Android Smart TV APK for instant pairing.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="plans" class="py-24 px-6 lg:px-16 bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto space-y-16 text-center">
            <div class="space-y-3 max-w-2xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-['Syne']">Transparent Pricing for Properties of Any Scale</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Choose a plan tailored to your room count. All plans include automated licensing keys and central cloud control.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:border-indigo-300 transition-all flex flex-col justify-between space-y-6 text-left relative {{ $plan->room_count === 50 ? 'border-2 border-indigo-600 shadow-indigo-600/10' : '' }}">
                        @if($plan->room_count === 50)
                            <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 font-extrabold text-[10px] uppercase">Most Popular</span>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 font-['Syne']">{{ $plan->name }}</h3>
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-extrabold text-[10px] uppercase">
                                    Up to {{ $plan->room_count }} Rooms
                                </span>
                            </div>

                            <div class="text-4xl font-extrabold text-slate-900 font-['Syne']">
                                ₹{{ number_format($plan->price, 0) }}<span class="text-xs text-slate-400 font-sans font-normal">/month</span>
                            </div>

                            <ul class="space-y-2.5 text-xs text-slate-600 font-semibold">
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Authorize up to {{ $plan->room_count }} TVs</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Instant 16-Digit License Code</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Custom Guest Welcome Text</span>
                                </li>
                                @if($plan->room_count >= 50)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                        <span>Priority Dashboard Analytics</span>
                                    </li>
                                @endif
                                @if($plan->room_count >= 100)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                        <span>Dedicated API & Support</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <button onclick="openRegisterModalWithPlan({{ $plan->id }}, {{ $plan->room_count }})" class="w-full py-3.5 px-4 rounded-2xl font-bold text-xs transition-all {{ $plan->room_count === 50 ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}">
                            Select {{ $plan->name }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 px-6 lg:px-16 bg-slate-50 border-t border-slate-200/80">
        <div class="max-w-4xl mx-auto space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-['Syne']">Frequently Asked Questions</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500">Everything you need to know about setting up HotelTV in your property.</p>
            </div>

            <div class="space-y-4">
                <div class="p-6 bg-white border border-slate-200/80 rounded-2xl shadow-xs space-y-2">
                    <h3 class="text-sm font-extrabold text-slate-900">How do room TVs pair with the HotelTV system?</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">After registering, you receive a 16-digit license key. Enter this key once inside the HotelTV Android TV app during first setup to automatically connect the room.</p>
                </div>
                <div class="p-6 bg-white border border-slate-200/80 rounded-2xl shadow-xs space-y-2">
                    <h3 class="text-sm font-extrabold text-slate-900">Can I update menu items or logos remotely?</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Yes! All changes made from your Hotel Admin web dashboard reflect instantly on connected room TVs without requiring physical TV access.</p>
                </div>
                <div class="p-6 bg-white border border-slate-200/80 rounded-2xl shadow-xs space-y-2">
                    <h3 class="text-sm font-extrabold text-slate-900">What happens if our hotel expands room count?</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">You can easily upgrade your plan anytime from the Hotel Admin portal to unlock support for additional room TVs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-6 px-6 lg:px-16">
        <div class="max-w-7xl mx-auto">
            <!-- Footer Top Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 pb-12 border-b border-slate-700/60">

                <!-- Brand Column -->
                <div class="space-y-5 lg:col-span-1">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-tv text-lg"></i>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-white">
                            Hotel<span class="text-indigo-400">TV</span>
                        </span>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
                        The all-in-one smart TV operating system built for modern hospitality. Control guest experiences at scale.
                    </p>
                    <!-- Social Icons -->
                    <div class="flex items-center space-x-3 pt-2">
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                            <i class="fa-brands fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                            <i class="fa-brands fa-linkedin-in text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                            <i class="fa-brands fa-youtube text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Features</a></li>
                        <li><a href="#preview" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Interactive OS</a></li>
                        <li><a href="#plans" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Pricing & Plans</a></li>
                        <li><a href="#faq" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">About Us</a></li>
                        <li><a href="#social-proof" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Hospitality Partners</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Access Portal -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Access Portal</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('hotel.login') }}" class="inline-flex items-center space-x-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors">
                                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                                <span>Hotel Admin Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('super-admin.login') }}" class="inline-flex items-center space-x-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                                <span>Super Admin Panel</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pt-3">
                        <button onclick="openRegisterModal()" class="w-full px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all">
                            Get Started Free <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between pt-6 gap-4">
                <p class="text-xs text-slate-500 font-medium">© {{ date('Y') }} HotelTV Management System. All rights reserved.</p>
                <div class="flex items-center space-x-6 text-xs text-slate-500">
                    <a href="#" class="hover:text-slate-300 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Terms</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Registration Modal Overlay -->
<div id="registerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-xl p-6 sm:p-8 space-y-6 shadow-2xl my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-extrabold text-slate-900 font-['Syne']">Hotel Registration</h3>
            <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form id="registerForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div id="registerError" class="hidden p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold"></div>

            <!-- Owner Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">Personal Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Owner Name</label>
                        <input type="text" name="owner_name" required placeholder="e.g. John Doe" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" required placeholder="e.g. 9876543210" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Email Address</label>
                        <input type="email" name="email" required placeholder="username@example.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Password</label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Hotel Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">Hotel Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Name</label>
                        <input type="text" name="hotel_name" required placeholder="e.g. Grand Resort" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Location / City</label>
                        <input type="text" name="hotel_location" required placeholder="e.g. Mumbai, India" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Total Room Count</label>
                    <input type="number" name="room_count" id="roomCountInput" min="1" required placeholder="e.g. 50" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    
                    <div id="suggestedPlanBox" class="hidden p-4 rounded-2xl bg-indigo-50 border border-indigo-200 space-y-1 mt-2">
                        <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider block">Suggested Subscription Plan</span>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                            <span id="suggestedPlanName">-</span>
                            <span id="suggestedPlanPrice" class="text-indigo-600 font-extrabold">-</span>
                        </div>
                        <input type="hidden" name="plan_id" id="suggestedPlanId">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRegisterModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30">Pay & Complete Registration</button>
            </div>
        </form>
    </div>
</div>

<!-- Simulated Sandbox Payment Modal Overlay -->
<div id="paymentLoader" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-4 text-center text-white">
    <div class="w-12 h-12 border-4 border-white/20 border-t-indigo-400 rounded-full animate-spin mb-4"></div>
    <h3 id="loaderTitle" class="text-xl font-extrabold font-['Syne']">Processing Order Request</h3>
    <p id="loaderMessage" class="text-xs text-slate-400 font-medium mt-1">Talking to payment gateway. Please do not close this window...</p>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const registerModal = document.getElementById('registerModal');
    const roomCountInput = document.getElementById('roomCountInput');
    const suggestedPlanBox = document.getElementById('suggestedPlanBox');
    const suggestedPlanName = document.getElementById('suggestedPlanName');
    const suggestedPlanPrice = document.getElementById('suggestedPlanPrice');
    const suggestedPlanId = document.getElementById('suggestedPlanId');
    const paymentLoader = document.getElementById('paymentLoader');

    function openRegisterModal() {
        registerModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openRegisterModalWithPlan(planId, rooms) {
        openRegisterModal();
        roomCountInput.value = rooms;
        fetchSuggestedPlan(rooms);
    }

    function closeRegisterModal() {
        registerModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('registerForm').reset();
        suggestedPlanBox.classList.add('hidden');
    }

    let debounceTimer;
    roomCountInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const rooms = parseInt(this.value);
        if (rooms > 0) {
            debounceTimer = setTimeout(() => fetchSuggestedPlan(rooms), 300);
        } else {
            suggestedPlanBox.classList.add('hidden');
        }
    });

    function fetchSuggestedPlan(rooms) {
        fetch("{{ route('register.suggest-plan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ room_count: rooms })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.plan) {
                suggestedPlanName.textContent = data.plan.name + ` (Max ${data.plan.room_count} rooms)`;
                suggestedPlanPrice.textContent = '₹' + parseFloat(data.plan.price).toLocaleString('en-IN') + '/mo';
                suggestedPlanId.value = data.plan.id;
                suggestedPlanBox.classList.remove('hidden');
            }
        })
        .catch(err => console.error('Plan Suggestion Error:', err));
    }

    function toggleAccessPortal(event) {
        event.stopPropagation();
        const menu = document.getElementById('accessPortalMenu');
        const chevron = document.getElementById('accessPortalChevron');
        const isHidden = menu.classList.contains('hidden');

        if (isHidden) {
            menu.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            closeAccessPortal();
        }
    }

    function closeAccessPortal() {
        const menu = document.getElementById('accessPortalMenu');
        const chevron = document.getElementById('accessPortalChevron');
        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    // Close Access Portal dropdown if clicked outside on touch screens
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('accessPortalBtn');
        const menu = document.getElementById('accessPortalMenu');
        if (menu && !menu.classList.contains('hidden')) {
            if (btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                closeAccessPortal();
            }
        }
    });
</script>
@endsection
