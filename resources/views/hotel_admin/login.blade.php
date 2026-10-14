@extends('layouts.app')

@section('title', 'Hotel Admin Sign In - ' . app_name())

@section('styles')
<!-- Luxury Editorial Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-serif-lux {
        font-family: 'Playfair Display', Georgia, serif;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#F8FAFC] text-[#1E293B] relative overflow-hidden">
    <!-- Top Header Bar -->
    <header class="w-full px-6 lg:px-12 py-5 bg-white border-b border-[#E2E8F0] shadow-xs relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#F9F5EB] text-[#6E1841] border border-[#C5A059]/40">
                    <i class="fa-solid fa-hotel text-[10px] text-[#C5A059] mr-1.5"></i> Hotel Portal
                </span>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="px-3.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#64748B] hover:text-[#1E293B] text-xs font-semibold transition-all inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-left text-[11px] text-[#C5A059]"></i>
                    <span>Back to Website</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Console Container (Split Showcase + Login Card) -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-16 w-full flex items-center justify-center relative z-10">
        <!-- Ambient Decorative Top Gradient -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-48 bg-gradient-to-b from-[#6E1841]/5 via-transparent to-transparent pointer-events-none"></div>

        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-center">
            
            <!-- Left Editorial & Highlights Column (Hidden on Small Screens) -->
            <div class="hidden lg:flex lg:col-span-6 flex-col space-y-7 text-left pr-4">
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] text-[11px] font-bold uppercase tracking-[0.15em]">
                        <i class="fa-solid fa-sparkles text-[10px] text-[#C5A059]"></i>
                        <span>Hospitality Smart TV Suite</span>
                    </div>

                    <h1 class="font-serif-lux text-4xl xl:text-5xl font-semibold tracking-tight text-[#1E293B] leading-[1.2]">
                        Elevate In-Room Guest Entertainment
                    </h1>

                    <p class="text-sm text-[#64748B] leading-relaxed max-w-lg font-normal">
                        Synchronize room smart TVs, broadcast property announcements, customize interactive concierge services, and monitor device health in real time.
                    </p>
                </div>

                <!-- 3 Feature Highlights -->
                <div class="space-y-3.5 pt-1">
                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-tv text-sm text-[#C5A059]"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-[#1E293B] tracking-wide">Fleet Device Pairing</h4>
                            <p class="text-xs text-[#64748B] leading-relaxed">Instant smart pairing and live diagnostics for all property TVs using dedicated device keys.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bell-concierge text-sm text-[#C5A059]"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-[#1E293B] tracking-wide">Digital Guest Concierge</h4>
                            <p class="text-xs text-[#64748B] leading-relaxed">Publish room service menus, spa bookings, hotel facilities, and interactive guides on the fly.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shield-halved text-sm text-[#C5A059]"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-[#1E293B] tracking-wide">Guest Privacy Protection</h4>
                            <p class="text-xs text-[#64748B] leading-relaxed">Automated zero-retention guest session wipe ensures full privacy and hospitality compliance.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 text-xs text-[#64748B] pt-2">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                    <span>Trusted by leading boutique hotels & luxury resorts globally.</span>
                </div>
            </div>

            <!-- Right Column: Login Card -->
            <div class="lg:col-span-6 w-full max-w-md mx-auto">
                <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 space-y-7 relative">
                    
                    <!-- Card Header -->
                    <div class="text-center space-y-2.5">
                        <div class="w-14 h-14 rounded-2xl bg-[#6E1841] text-[#C5A059] flex items-center justify-center mx-auto shadow-md shadow-[#6E1841]/20 border border-[#8A1C56]">
                            <i class="fa-solid fa-hotel text-2xl"></i>
                        </div>
                        <h2 class="font-serif-lux text-2xl sm:text-3xl font-semibold text-[#1E293B] tracking-tight">
                            Hotel Admin Portal
                        </h2>
                        <p class="text-xs text-[#64748B] font-normal">
                            Sign in to configure and monitor your hotel's Smart TV fleet
                        </p>
                    </div>

                    <!-- Flash & Validation Alerts -->
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-[#DCFCE7] border border-[#16A34A]/25 text-[#16A34A] text-xs flex items-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="p-4 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/35 text-[#8A1C56] text-xs flex items-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-info text-sm text-[#C5A059] shrink-0"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 rounded-xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs space-y-1.5">
                            <div class="flex items-center space-x-2 font-bold">
                                <i class="fa-solid fa-circle-exclamation text-[#DC2626]"></i>
                                <span>Please resolve the following:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1 font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Hotel Sign-in Form -->
                    <form action="{{ route('hotel.login') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Email Input -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-xs font-bold text-[#1E293B]">Property Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@grandresort.com" autocomplete="email" autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#C5A059] focus:ring-2 focus:ring-[#C5A059]/20 transition-all">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-1.5 text-left">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-[#1E293B]">Password</label>
                                @if (Route::has('hotel.forgot-password'))
                                    <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-bold text-[#C5A059] hover:text-[#A88440] transition-colors">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input type="password" id="hotelPassword" name="password" required placeholder="••••••••" autocomplete="current-password"
                                    class="w-full pl-10 pr-10 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#C5A059] focus:ring-2 focus:ring-[#C5A059]/20 transition-all">
                                <button type="button" onclick="togglePasswordVisibility('hotelPassword', 'hotelEyeIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#64748B] hover:text-[#1E293B] transition-colors cursor-pointer" aria-label="Toggle password visibility">
                                    <i id="hotelEyeIcon" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me Option -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center space-x-2 text-[#64748B] hover:text-[#1E293B] cursor-pointer select-none">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-[#CBD5E1] text-[#6E1841] focus:ring-[#C5A059]/40 cursor-pointer">
                                <span>Remember this workstation</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 px-4 bg-[#C5A059] hover:bg-[#A88440] text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#C5A059]/25 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                            <span>Sign In to Hotel Admin</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <!-- Demo Access Mention -->
                    <div class="pt-4 border-t border-[#E2E8F0] text-center space-y-1">
                        <p class="text-xs font-bold text-[#1E293B]">Looking for Demo Access?</p>
                        <p class="text-[11px] text-[#64748B]">Contact your distributor or system administrator for demo login credentials.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="w-full px-6 lg:px-12 py-4 bg-white border-t border-[#E2E8F0] text-xs text-[#64748B] relative z-20">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-[#6E1841] hover:underline">Digi Emperor</a></p>
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('privacy-policy') }}" class="hover:text-[#1E293B] transition-colors">Privacy Policy</a>
                <span class="text-[#CBD5E1]">|</span>
                <a href="{{ route('contact-us') }}" class="hover:text-[#1E293B] transition-colors">Support</a>
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
