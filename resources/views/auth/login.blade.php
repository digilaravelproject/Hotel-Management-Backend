@extends('layouts.app')

@section('title', 'Sign In - ' . app_name())

@section('styles')
<style>
    /* Dark Vignette Ambient Background matching Reference Image */
    .payoneer-ambient-canvas {
        background: radial-gradient(circle at 50% 30%, #201a18 0%, #130f0e 60%, #090706 100%);
    }

    /* Concentric Design Rings behind Device Mockup */
    .concentric-ring-1 {
        width: 380px;
        height: 380px;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
    .concentric-ring-2 {
        width: 520px;
        height: 520px;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 payoneer-ambient-canvas relative overflow-hidden selection:bg-[#FF5722] selection:text-white">

    <!-- Subtle Ambient Lighting Glow -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-orange-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[450px] h-[450px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- MAIN FLOATING CARD (Exact Layout matching Reference Image 2) -->
    <div class="w-full max-w-5xl xl:max-w-[1080px] bg-[#1D1918] rounded-[32px] sm:rounded-[38px] shadow-[0_30px_90px_rgba(0,0,0,0.7)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 border border-white/5">

        <!-- ================================================================= -->
        <!-- LEFT PANEL: Dark Editorial Screen (50% Width on Desktop)          -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 bg-[#1D1918] p-7 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between relative overflow-hidden select-none min-h-[480px] lg:min-h-[640px]">

            <!-- Background Concentric Architectural Rings -->
            <div class="concentric-ring-2 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none z-0"></div>
            <div class="concentric-ring-1 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none z-0"></div>

            <!-- Top Tagline -->
            <div class="relative z-10">
                <p class="text-xs sm:text-[13px] text-[#A69E98] font-normal leading-relaxed max-w-sm">
                    Global hospitality made simple — online in-room TV solutions for luxury hotels & networks.
                </p>
            </div>

            <!-- Centered Large Editorial Typography -->
            <div class="relative z-10 my-auto py-8 sm:py-12">
                <h2 class="text-4xl sm:text-5xl xl:text-6xl font-normal text-white tracking-tight leading-[1.08] font-sans">
                    Manage<br>
                    <span class="font-medium text-[#F4F1EE]">your screens</span>
                </h2>
            </div>

            <!-- Bottom Graphic: Handheld Device Display with In-Room Launcher Mockup -->
            <div class="relative z-10 pt-4 flex items-end justify-between">
                <!-- Device Mockup Card (Styled like Payoneer Hand Phone in Ref Image 2) -->
                <div class="w-56 sm:w-64 bg-[#14100F] border border-white/10 rounded-[24px] p-3 shadow-2xl backdrop-blur-sm -mb-4 transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="bg-[#1C1615] rounded-[18px] p-3 border border-white/5 space-y-2.5">
                        <div class="flex items-center justify-between text-[10px] text-white/50">
                            <span>Room #304 TV</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="text-lg font-bold text-white tracking-tight">Active Stream</div>
                        <!-- Mini Bar Graph (like Payoneer reference chart) -->
                        <div class="flex items-end space-x-1.5 h-10 pt-2">
                            <div class="w-3 bg-white/20 rounded-xs h-4"></div>
                            <div class="w-3 bg-white/30 rounded-xs h-6"></div>
                            <div class="w-3 bg-[#FF5722] rounded-xs h-9"></div>
                            <div class="w-3 bg-[#FF5722] rounded-xs h-7"></div>
                            <div class="w-3 bg-white/25 rounded-xs h-5"></div>
                            <div class="w-3 bg-white/15 rounded-xs h-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Circular Hospitality Icon (as in Ref Image 2) -->
                <div class="w-8 h-8 rounded-full border border-orange-500/50 text-[#FF5722] flex items-center justify-center text-xs shrink-0 mb-1">
                    <i class="fa-solid fa-hotel text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- RIGHT PANEL: Crisp White Payoneer-Style Card (50% Width)         -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 bg-white lg:rounded-l-[38px] p-7 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between relative shadow-xl">

            <!-- Top Header Row: Brand Logo & Sign Up -->
            <div class="flex items-center justify-between pb-6 sm:pb-8">
                <!-- Brand Logo (Pura Left-Aligned) -->
                <a href="{{ url('/') }}" title="{{ app_name() }}" class="inline-flex items-center transition-transform hover:scale-105">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" 
                             alt="{{ app_name() }}" 
                             class="h-10 sm:h-12 w-auto max-w-[170px] sm:max-w-[190px] object-contain">
                    </picture>
                </a>

                <!-- Top Right Sign Up Link (matching Reference Image 2) -->
                <a href="{{ url('/') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 flex items-center space-x-1.5 transition-colors group">
                    <i class="fa-regular fa-user text-xs text-slate-400 group-hover:text-slate-700"></i>
                    <span>Sign Up</span>
                </a>
            </div>

            <!-- Main Form Body: Centered & Pill Inputs (Pixel-to-Pixel match) -->
            <div class="my-auto py-4 sm:py-6 max-w-sm w-full mx-auto">

                <!-- Headline -->
                <h1 class="text-3xl sm:text-4xl font-normal text-slate-900 tracking-tight font-sans mb-8">
                    Sign In
                </h1>

                <!-- Flash & Error Alerts -->
                @if(session('success'))
                    <div class="mb-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2 font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-4 p-3 rounded-2xl bg-purple-50 border border-purple-200 text-purple-900 text-xs flex items-center space-x-2 font-medium">
                        <i class="fa-solid fa-circle-info text-purple-600 text-sm shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2 font-medium">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                        <div class="flex items-center space-x-2 font-bold text-rose-900">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                            <span>Sign in failed:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 pl-1 text-rose-700 text-xs font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Common Authentication Form with Pill Inputs -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- 1. Email or Username (Rounded Pill Input) -->
                    <div>
                        <input type="text" 
                               id="loginEmail" 
                               name="email" 
                               required 
                               value="{{ old('email') }}" 
                               placeholder="Email or Username" 
                               autocomplete="email" 
                               autofocus
                               class="w-full px-5 py-3.5 rounded-full border border-slate-300 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-400 bg-white transition-all">
                    </div>

                    <!-- 2. Password (Rounded Pill Input with Toggle Eye) -->
                    <div class="relative">
                        <input type="password" 
                               id="loginPassword" 
                               name="password" 
                               required 
                               placeholder="Password" 
                               autocomplete="current-password"
                               class="w-full pl-5 pr-12 py-3.5 rounded-full border border-slate-300 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-400 bg-white transition-all">
                        <button type="button" 
                                onclick="togglePasswordVisibility('loginPassword', 'loginEyeIcon')" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1 cursor-pointer transition-colors" 
                                aria-label="Toggle password visibility">
                            <i id="loginEyeIcon" class="fa-regular fa-eye-slash text-sm"></i>
                        </button>
                    </div>

                    <!-- 3. Forgot Password Link (matching Reference Image 2 orange coral text) -->
                    <div class="pt-0.5">
                        @if(Route::has('hotel.forgot-password'))
                            <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-semibold text-[#FF5722] hover:text-[#E64A19] hover:underline transition-colors inline-block">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- 4. Gradient Pill CTA Button (matching Reference Image 2) -->
                    <button type="submit" 
                            class="w-full mt-3 py-3.5 px-6 rounded-full bg-gradient-to-r from-[#FF5722] via-[#FF6E40] to-[#FF5252] hover:from-[#F4511E] hover:to-[#E64A19] text-white font-extrabold text-sm shadow-md shadow-orange-500/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>

            </div>

            <!-- Bottom Footer Row: Copyright & Links (matching Reference Image 2) -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-slate-100 text-[11px] text-slate-400 gap-2">
                <div>
                    © 2005-2026 Pax TV Launcher Inc.
                </div>
                <div class="flex items-center space-x-4 text-slate-500 font-medium">
                    <a href="{{ url('/') }}" class="hover:text-slate-900 transition-colors">Contact Us</a>
                    <span class="flex items-center space-x-1 cursor-pointer hover:text-slate-900 transition-colors">
                        <span>English</span>
                        <i class="fa-solid fa-chevron-down text-[8px] opacity-70"></i>
                    </span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Password Visibility Toggle
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection
