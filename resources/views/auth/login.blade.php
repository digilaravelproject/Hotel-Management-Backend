@extends('layouts.app')

@section('title', 'Sign In - ' . app_name())

@section('styles')
<style>
    /* 1. Signature Cosmic Canvas Background */
    .login-cosmic-canvas {
        background: radial-gradient(circle at 50% 15%, #1d1148 0%, #0f0828 50%, #060312 100%);
    }

    /* 2. Ambient Floating Glowing Orbs Animations */
    @keyframes orbFloat1 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(50px, -35px) scale(1.12); }
        66% { transform: translate(-35px, 45px) scale(0.95); }
    }
    @keyframes orbFloat2 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(-45px, 50px) scale(1.08); }
        66% { transform: translate(35px, -30px) scale(0.92); }
    }
    @keyframes orbFloat3 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        50% { transform: translate(30px, -50px) scale(1.15); }
    }
    .animate-orb-1 {
        animation: orbFloat1 18s ease-in-out infinite;
    }
    .animate-orb-2 {
        animation: orbFloat2 22s ease-in-out infinite;
    }
    .animate-orb-3 {
        animation: orbFloat3 26s ease-in-out infinite;
    }

    /* 3. Concentric Rings behind Screen Mockup */
    .concentric-ring-1 {
        width: 380px;
        height: 380px;
        border: 1px solid rgba(168, 85, 247, 0.12);
    }
    .concentric-ring-2 {
        width: 540px;
        height: 540px;
        border: 1px solid rgba(168, 85, 247, 0.07);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 login-cosmic-canvas relative overflow-hidden selection:bg-purple-600 selection:text-white">

    <!-- Subtle Tech Dot-Grid Overlay for Depth -->
    <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.06)_1px,transparent_1px)] [background-size:28px_28px] pointer-events-none z-0"></div>

    <!-- Live Animated Glowing Ambient Cosmic Orbs -->
    <div class="animate-orb-1 absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[550px] sm:w-[650px] h-[550px] sm:h-[650px] bg-purple-600/20 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="animate-orb-2 absolute bottom-12 right-1/4 w-[450px] sm:w-[550px] h-[450px] sm:h-[550px] bg-indigo-600/18 rounded-full blur-[130px] pointer-events-none z-0"></div>
    <div class="animate-orb-3 absolute top-2/3 left-1/2 -translate-x-1/2 w-[380px] h-[380px] bg-violet-500/12 rounded-full blur-[110px] pointer-events-none z-0"></div>

    <!-- Interactive Floating Particle Stars Canvas -->
    <canvas id="cosmicParticles" class="absolute inset-0 pointer-events-none z-0"></canvas>

    <!-- MAIN FLOATING CARD (Exact Layout from Reference Image in Brand Colors) -->
    <div class="w-full max-w-5xl xl:max-w-[1080px] bg-[#0E0625] rounded-[32px] sm:rounded-[40px] shadow-[0_30px_90px_rgba(0,0,0,0.8)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 border border-white/10">

        <!-- ================================================================= -->
        <!-- LEFT PANEL: Dark Editorial Screen (50% Width on Desktop)          -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 bg-gradient-to-b from-[#150A3E] via-[#0E0625] to-[#070314] p-7 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between relative overflow-hidden select-none min-h-[480px] lg:min-h-[660px]">

            <!-- Background Concentric Architectural Rings -->
            <div class="concentric-ring-2 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none z-0"></div>
            <div class="concentric-ring-1 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none z-0"></div>

            <!-- Top Tagline (matching Reference Image) -->
            <div class="relative z-10">
                <p class="text-xs sm:text-[13px] text-purple-200/70 font-normal leading-relaxed max-w-sm">
                    Global hospitality made simple — online in-room TV solutions for luxury hotels & networks.
                </p>
            </div>

            <!-- Centered Large Editorial Typography (matching Reference Image) -->
            <div class="relative z-10 my-auto py-8 sm:py-12">
                <h2 class="text-4xl sm:text-5xl xl:text-6xl font-normal text-white tracking-tight leading-[1.08] font-sans">
                    Manage<br>
                    <span class="font-medium text-purple-100">your screens</span>
                </h2>
            </div>

            <!-- Bottom Graphic: Room 304 TV Active Stream Card + Circular Icon -->
            <div class="relative z-10 pt-4 flex items-end justify-between">
                <!-- Device Mockup Card (Exact Match from User's Reference) -->
                <div class="w-56 sm:w-64 bg-[#160B33]/90 border border-purple-500/20 rounded-[22px] p-3 shadow-2xl backdrop-blur-md -mb-4 transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="bg-[#0F0624] rounded-[16px] p-3.5 border border-white/5 space-y-2.5">
                        <div class="flex items-center justify-between text-[11px] text-purple-200/60 font-medium">
                            <span>Room #304 TV</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>
                        <div class="text-base sm:text-lg font-bold text-white tracking-tight">Active Stream</div>
                        <!-- Equalizer Bar Graph matching User's Reference Image in Brand Colors -->
                        <div class="flex items-end space-x-1.5 h-9 pt-1">
                            <div class="w-3 bg-purple-500/20 rounded-xs h-3"></div>
                            <div class="w-3 bg-purple-500/35 rounded-xs h-5"></div>
                            <div class="w-3 bg-gradient-to-t from-purple-600 to-indigo-400 rounded-xs h-8"></div>
                            <div class="w-3 bg-gradient-to-t from-purple-600 to-indigo-400 rounded-xs h-6"></div>
                            <div class="w-3 bg-purple-500/30 rounded-xs h-4"></div>
                            <div class="w-3 bg-purple-500/20 rounded-xs h-2.5"></div>
                        </div>
                    </div>
                </div>

                <!-- Circular Hospitality Brand Badge (matching User's Reference Image) -->
                <div class="w-9 h-9 rounded-full border border-purple-500/40 bg-purple-600/10 text-purple-300 flex items-center justify-center text-xs shrink-0 mb-1 shadow-xs">
                    <i class="fa-solid fa-hotel text-xs"></i>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- RIGHT PANEL: Crisp White Seamless Card with Curved Overlap       -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 bg-white lg:rounded-l-[44px] p-7 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between relative shadow-2xl">

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

                <!-- Top Right Sign Up Link (matching Reference Image) -->
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

                <!-- Common Authentication Form with Pill Inputs (matching Reference Image) -->
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
                               class="w-full px-5 py-3.5 rounded-full border border-slate-300 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/10 bg-white transition-all shadow-xs">
                    </div>

                    <!-- 2. Password (Rounded Pill Input with Toggle Eye) -->
                    <div class="relative">
                        <input type="password" 
                               id="loginPassword" 
                               name="password" 
                               required 
                               placeholder="Password" 
                               autocomplete="current-password"
                               class="w-full pl-5 pr-12 py-3.5 rounded-full border border-slate-300 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/10 bg-white transition-all shadow-xs">
                        <button type="button" 
                                onclick="togglePasswordVisibility('loginPassword', 'loginEyeIcon')" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1 cursor-pointer transition-colors" 
                                aria-label="Toggle password visibility">
                            <i id="loginEyeIcon" class="fa-regular fa-eye-slash text-sm"></i>
                        </button>
                    </div>

                    <!-- 3. Forgot Password Link (Brand Purple Color) -->
                    <div class="pt-0.5">
                        @if(Route::has('hotel.forgot-password'))
                            <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-bold text-purple-700 hover:text-[#150A42] hover:underline transition-colors inline-block">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- 4. Gradient Pill CTA Button (matching Reference Layout in Brand Colors) -->
                    <button type="submit" 
                            class="w-full mt-3 py-3.5 sm:py-4 px-6 rounded-full bg-gradient-to-r from-[#150A42] via-[#24106B] to-purple-700 hover:from-[#1b0d54] hover:via-[#2b147e] hover:to-purple-600 text-white font-extrabold text-sm shadow-xl shadow-[#150A42]/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>

            </div>

            <!-- Bottom Footer Row: Copyright & Links (matching Reference Image) -->
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
    // 1. Password Visibility Toggle
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

    // 2. Ultra-Lightweight Cosmic Particles Canvas Animation
    (function initCosmicCanvas() {
        const canvas = document.getElementById('cosmicParticles');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });

        const particleCount = window.innerWidth < 768 ? 25 : 55;
        const particles = [];

        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                radius: Math.random() * 1.6 + 0.4,
                speedY: Math.random() * 0.35 + 0.1,
                speedX: (Math.random() - 0.5) * 0.2,
                alpha: Math.random() * 0.7 + 0.2,
                pulseSpeed: Math.random() * 0.02 + 0.005,
                pulseAngle: Math.random() * Math.PI * 2
            });
        }

        let animationFrameId;
        function renderParticles() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.y -= p.speedY;
                p.x += p.speedX;
                p.pulseAngle += p.pulseSpeed;

                const currentAlpha = p.alpha + Math.sin(p.pulseAngle) * 0.25;

                if (p.y < -10) {
                    p.y = height + 10;
                    p.x = Math.random() * width;
                }
                if (p.x < -10) p.x = width + 10;
                if (p.x > width + 10) p.x = -10;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(192, 175, 255, ${Math.max(0.1, Math.min(0.9, currentAlpha))})`;
                ctx.shadowBlur = 8;
                ctx.shadowColor = 'rgba(168, 85, 247, 0.4)';
                ctx.fill();
            }

            animationFrameId = requestAnimationFrame(renderParticles);
        }

        renderParticles();

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                cancelAnimationFrame(animationFrameId);
            } else {
                renderParticles();
            }
        });
    })();
</script>
@endsection
