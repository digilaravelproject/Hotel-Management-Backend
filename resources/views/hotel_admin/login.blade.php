@extends('layouts.app')

@section('title', 'Hotel Admin Sign In - ' . app_name())

@section('styles')
<!-- Typography & Custom Theme Styling -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<style>
    .font-display, .font-mono {
        font-family: 'Space Mono', monospace;
    }
    .purple-mesh-bg {
        background: radial-gradient(circle at 15% 15%, rgba(124, 58, 237, 0.22) 0%, transparent 40%),
                    radial-gradient(circle at 85% 85%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                    radial-gradient(circle at 50% 50%, rgba(147, 51, 234, 0.08) 0%, transparent 60%),
                    #0D091B;
    }
    .glass-showcase {
        background: linear-gradient(145deg, rgba(30, 20, 56, 0.85) 0%, rgba(18, 12, 36, 0.95) 100%);
        box-shadow: 0 25px 50px -12px rgba(10, 6, 24, 0.7), inset 0 1px 1px rgba(255, 255, 255, 0.12);
    }
    .glass-floating-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .glow-orb {
        filter: blur(64px);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col justify-between purple-mesh-bg text-slate-100 relative selection:bg-purple-600 selection:text-white">

    <!-- Ambient Glowing Orbs in Background -->
    <div class="absolute -top-28 -left-28 w-96 h-96 bg-purple-600/20 rounded-full glow-orb pointer-events-none"></div>
    <div class="absolute -bottom-28 -right-28 w-96 h-96 bg-indigo-600/18 rounded-full glow-orb pointer-events-none"></div>

    <!-- Geometric Accent Rings (Subtle reference to sample UI) -->
    <div class="absolute top-16 right-16 w-60 h-60 border border-purple-500/10 rounded-full pointer-events-none hidden xl:block"></div>
    <div class="absolute bottom-16 left-12 w-48 h-48 border border-indigo-500/10 rounded-full pointer-events-none hidden xl:block"></div>

    <!-- Professional Top Navigation Header -->
    <header class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/70 backdrop-blur-xl border-b border-white/[0.08] relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                    </picture>
                </a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-purple-500/15 text-purple-200 border border-purple-400/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                    Hotel Portal
                </span>
            </div>

            <a href="{{ route('landing') }}" class="px-3.5 py-1.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-semibold transition-all inline-flex items-center space-x-2 backdrop-blur-md">
                <i class="fa-solid fa-arrow-left text-xs text-purple-400"></i>
                <span>Back to Website</span>
            </a>
        </div>
    </header>

    <!-- Main Section (Balanced, Professional & Fits Viewport Without Scroll on Desktop) -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-4 lg:py-6 w-full flex items-center justify-center relative z-10">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

            <!-- Left Showcase Panel (Prominent, Elegant Typography, Cliqk Style) -->
            <div class="hidden lg:flex lg:col-span-6 glass-showcase rounded-3xl p-8 xl:p-10 flex-col justify-between border border-white/10 relative overflow-hidden space-y-6">
                <!-- Inner flare -->
                <div class="absolute top-0 right-0 w-72 h-72 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Headline -->
                <div class="space-y-3 relative z-10">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-purple-500/15 border border-purple-400/25 text-purple-300 text-xs font-semibold tracking-wide">
                        <i class="fa-solid fa-sparkles text-purple-400"></i>
                        <span>Smart TV Hospitality Suite</span>
                    </div>

                    <h1 class="font-display text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                        Elevate In-Room <br>
                        <span class="bg-gradient-to-r from-purple-300 via-violet-200 to-indigo-300 bg-clip-text text-transparent">Guest Experience.</span>
                    </h1>

                    <p class="text-sm text-slate-300/80 leading-relaxed max-w-md font-normal">
                        Synchronize room smart TVs, publish digital concierge dining and facilities, broadcast announcements, and monitor fleet diagnostics in real time.
                    </p>
                </div>

                <!-- Floating Metric Highlights (Clean 3 Cards) -->
                <div class="relative z-10 space-y-3">
                    <div class="glass-floating-card rounded-2xl p-3.5 flex items-center justify-between border border-white/10">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-400/30 flex items-center justify-center text-purple-300 shrink-0">
                                <i class="fa-solid fa-tv text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white tracking-wide">Fleet Device Pairing</h4>
                                <p class="text-xs text-slate-400">Instant 6-digit room PIN pairing and live telemetry</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">Active</span>
                    </div>

                    <div class="glass-floating-card rounded-2xl p-3.5 flex items-center justify-between border border-white/10">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-violet-500/20 border border-violet-400/30 flex items-center justify-center text-violet-300 shrink-0">
                                <i class="fa-solid fa-bell-concierge text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white tracking-wide">Digital Guest Concierge</h4>
                                <p class="text-xs text-slate-400">Dining menus, spa bookings, hotel notices & local guide</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-200 border border-purple-400/30">Live</span>
                    </div>

                    <div class="glass-floating-card rounded-2xl p-3.5 flex items-center justify-between border border-white/10">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300 shrink-0">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white tracking-wide">Guest Privacy Shield</h4>
                                <p class="text-xs text-slate-400">Automated zero-retention session wipe on checkout</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/15 text-blue-300 border border-blue-400/30">Certified</span>
                    </div>
                </div>

                <!-- Bottom Trust Status -->
                <div class="pt-3.5 border-t border-white/10 flex items-center justify-between text-xs text-slate-400 relative z-10">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Multi-Property System Online</span>
                    </div>
                    <div class="flex items-center space-x-1.5 text-slate-400">
                        <i class="fa-solid fa-lock text-xs text-purple-400"></i>
                        <span>256-Bit Encrypted</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Professional, Clean & Proportionate Login Card -->
            <div class="lg:col-span-6 w-full max-w-[430px] mx-auto flex flex-col justify-center">
                <div class="bg-white rounded-3xl p-7 sm:p-8 shadow-2xl shadow-purple-950/40 border border-purple-100/60 relative">

                    <!-- Portal Selector Pills (Professional Sizing) -->
                    <div class="mb-5 bg-slate-100 p-1.5 rounded-2xl flex items-center text-xs font-semibold">
                        <a href="{{ route('hotel.login') }}" class="flex-1 py-2 text-center rounded-xl bg-purple-600 text-white shadow-sm font-bold transition-all flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-hotel text-xs"></i>
                            <span>Hotel</span>
                        </a>
                        <a href="{{ route('distributor.login') }}" class="flex-1 py-2 text-center rounded-xl text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                            <span>Distributor</span>
                        </a>
                        <a href="{{ route('super-admin.login') }}" class="flex-1 py-2 text-center rounded-xl text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-crown text-xs"></i>
                            <span>Master</span>
                        </a>
                    </div>

                    <!-- Header -->
                    <div class="text-left space-y-1 mb-5">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Welcome back
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal">
                            Sign in to manage your hotel's Smart TV network
                        </p>
                    </div>

                    <!-- Flash & Validation Alerts -->
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="mb-4 p-3 rounded-xl bg-purple-50 border border-purple-200 text-purple-900 text-xs flex items-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-info text-purple-600 text-sm shrink-0"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                            <div class="flex items-center space-x-1.5 font-bold text-red-800">
                                <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                                <span>Please resolve the following:</span>
                            </div>
                            <ul class="list-disc pl-4 space-y-0.5 font-medium text-red-700 text-xs">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Hotel Sign-in Form -->
                    <form action="{{ route('hotel.login') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Property Email Address -->
                        <div class="space-y-1.5 text-left">
                            <label for="hotelEmail" class="block text-xs font-bold text-slate-700">Property Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input type="email" id="hotelEmail" name="email" required value="{{ old('email') }}" placeholder="admin@grandresort.com" autocomplete="email" autofocus
                                    class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-1.5 text-left">
                            <div class="flex items-center justify-between">
                                <label for="hotelPassword" class="block text-xs font-bold text-slate-700">Password</label>
                                @if (Route::has('hotel.forgot-password'))
                                    <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-700 hover:underline transition-colors">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input type="password" id="hotelPassword" name="password" required placeholder="••••••••" autocomplete="current-password"
                                    class="w-full pl-10 pr-10 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all">
                                <button type="button" onclick="togglePasswordVisibility('hotelPassword', 'hotelEyeIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" aria-label="Toggle password visibility">
                                    <i id="hotelEyeIcon" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me Option -->
                        <div class="flex items-center justify-between text-xs pt-0.5">
                            <label class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 cursor-pointer select-none">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500/40 cursor-pointer">
                                <span>Remember this workstation</span>
                            </label>
                        </div>

                        <!-- Primary Sign In Button -->
                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-500 hover:via-violet-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                            <span>Sign In to Hotel Admin</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <!-- Support Note -->
                    <div class="mt-4 pt-3.5 border-t border-slate-100 text-center space-y-0.5">
                        <p class="text-xs font-semibold text-slate-700">Need demo access or assistance?</p>
                        <p class="text-[11px] text-slate-500">Contact your regional distributor or administrator.</p>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Bottom Professional Footer Bar -->
    <footer class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/80 backdrop-blur-md border-t border-white/[0.08] text-xs text-slate-400 relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2.5 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-purple-300 hover:underline">Digi Emperor</a></p>
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span class="text-white/20">|</span>
                <a href="{{ route('contact-us') }}" class="hover:text-white transition-colors">Support</a>
            </div>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs';
        }
    }
</script>
@endsection
