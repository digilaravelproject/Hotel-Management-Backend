
@extends('layouts.app')

@section('title', 'Sign In - ' . app_name())

@section('styles')
<style>
    /* 1. Signature Cosmic Canvas Background */
    .login-cosmic-canvas {
        background: radial-gradient(circle at 50% 15%, #1d1148 0%, #0f0828 50%, #060312 100%);
    }

    /* 2. Full-Panel Sliding Images Crossfade & Zoom Transitions */
    .slide-fade {
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .carousel-dot.active {
        width: 1.5rem;
        background-color: #A855F7;
    }
    .carousel-dot:not(.active) {
        width: 0.45rem;
        background-color: rgba(255, 255, 255, 0.25);
    }

    /* 3. Ambient Floating Glowing Orbs */
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
    .animate-orb-1 { animation: orbFloat1 18s ease-in-out infinite; }
    .animate-orb-2 { animation: orbFloat2 22s ease-in-out infinite; }
    .animate-orb-3 { animation: orbFloat3 26s ease-in-out infinite; }

    /* 4. Active Stream Equalizer Bars Live Pulse Animations */
    @keyframes eq1 { 0%, 100% { height: 10px; } 50% { height: 26px; } }
    @keyframes eq2 { 0%, 100% { height: 22px; } 50% { height: 12px; } }
    @keyframes eq3 { 0%, 100% { height: 32px; } 50% { height: 18px; } }
    @keyframes eq4 { 0%, 100% { height: 16px; } 50% { height: 32px; } }
    @keyframes eq5 { 0%, 100% { height: 24px; } 50% { height: 14px; } }
    @keyframes eq6 { 0%, 100% { height: 12px; } 50% { height: 22px; } }

    .animate-eq-1 { animation: eq1 1.2s ease-in-out infinite; }
    .animate-eq-2 { animation: eq2 0.9s ease-in-out infinite 0.1s; }
    .animate-eq-3 { animation: eq3 1.4s ease-in-out infinite 0.2s; }
    .animate-eq-4 { animation: eq4 1.1s ease-in-out infinite 0.15s; }
    .animate-eq-5 { animation: eq5 1.3s ease-in-out infinite 0.05s; }
    .animate-eq-6 { animation: eq6 1.0s ease-in-out infinite 0.25s; }
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

    <!-- ========================================================================= -->
    <!-- MAIN SPLIT CARD (Exact Payoneer-Style Layout with Signature Cosmic Theme) -->
    <!-- ========================================================================= -->
    <div class="w-full max-w-5xl xl:max-w-[1120px] bg-[#0d0628] rounded-[36px] sm:rounded-[44px] shadow-[0_30px_90px_rgba(0,0,0,0.85)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 border border-white/10">

        <!-- ================================================================= -->
        <!-- LEFT PANEL: "Manage your screens" + Concentric Rings + Stream Eq  -->
        <!-- ================================================================= -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 relative overflow-hidden min-h-[620px] xl:min-h-[680px] flex-col justify-between p-10 lg:p-12 xl:p-14 select-none bg-[#0e0725]">

            <!-- Provision for Full-Panel Background Sliding Images -->
            <div class="absolute inset-0 z-0">
                <!-- Slide 1 (In-Room TV Suite with Remote) -->
                <img id="loginSlide1" 
                     src="{{ asset('images/landing/about-suite-remote.jpg') }}" 
                     alt="In-Room Hospitality Screen" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-25 scale-100">

                <!-- Slide 2 (Luxury Hotel Resort Wallpaper) -->
                <img id="loginSlide2" 
                     src="{{ asset('images/landing/tv-wallpaper-resort.jpg') }}" 
                     alt="Luxury Hotel Resort" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-0 scale-105">

                <!-- Slide 3 (Smart Suite at Sunset) -->
                <img id="loginSlide3" 
                     src="{{ asset('images/landing/hero-suite-sunset.jpg') }}" 
                     alt="Smart TV Suite" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-0 scale-105">

                <!-- Deep Cosmic Purple Gradients Over Sliding Photos -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0e0725] via-[#0e0725]/85 to-[#0e0725]/70 z-10"></div>
                <div class="absolute inset-0 bg-[#150A42]/50 mix-blend-multiply z-10"></div>
            </div>

            <!-- Concentric Circular Orbits / Wave Rings (From Reference Mockup) -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[620px] h-[620px] pointer-events-none z-10">
                <div class="absolute inset-0 m-auto w-[240px] h-[240px] rounded-full border border-purple-400/[0.08]"></div>
                <div class="absolute inset-0 m-auto w-[420px] h-[420px] rounded-full border border-purple-400/[0.07]"></div>
                <div class="absolute inset-0 m-auto w-[600px] h-[600px] rounded-full border border-purple-400/[0.05]"></div>
            </div>

            <!-- Top Tagline -->
            <div class="relative z-20">
                <p class="text-slate-300/80 text-xs sm:text-sm font-normal max-w-sm leading-relaxed tracking-wide">
                    Global hospitality made simple — online in-room TV solutions for luxury hotels & networks.
                </p>
            </div>

            <!-- Center Headline: "Manage your screens" -->
            <div class="relative z-20 my-auto py-10">
                <h1 class="text-4xl sm:text-5xl xl:text-6xl font-black text-white tracking-tight leading-[1.08] font-sans">
                    Manage<br>your screens
                </h1>
            </div>

            <!-- Bottom Row: Equalizer Card + Hotel Icon Badge + Slider Dots -->
            <div class="relative z-20 flex items-end justify-between gap-4">
                <!-- Floating Glass Equalizer Card ("Room #304 TV Active Stream") -->
                <div class="bg-black/60 backdrop-blur-xl border border-purple-500/25 rounded-2xl p-4 sm:p-5 w-56 sm:w-64 shadow-2xl shadow-purple-950/60">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-semibold text-slate-400">Room #304 TV</span>
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </div>
                    <div class="text-sm font-bold text-white mb-3 tracking-wide">
                        Active Stream
                    </div>
                    <!-- Live Animated Equalizer Bars in Cosmic Violet Gradient -->
                    <div class="flex items-end space-x-1.5 h-8">
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-700 to-purple-400 animate-eq-1"></div>
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-700 to-purple-400 animate-eq-2"></div>
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-600 to-fuchsia-400 animate-eq-3"></div>
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-600 to-fuchsia-400 animate-eq-4"></div>
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-700 to-purple-400 animate-eq-5"></div>
                        <div class="w-2.5 rounded-full bg-gradient-to-t from-purple-700 to-purple-400 animate-eq-6"></div>
                    </div>
                </div>

                <!-- Right Side: Circular Hotel Badge + Carousel Navigation Dots -->
                <div class="flex flex-col items-center space-y-4">
                    <!-- Circular Hotel Icon Badge (From Mockup) -->
                    <div class="w-10 h-10 rounded-full border border-purple-500/30 bg-purple-950/70 flex items-center justify-center text-purple-300 shadow-lg shadow-purple-900/40">
                        <i class="fa-solid fa-hotel text-xs"></i>
                    </div>

                    <!-- Slide Indicator Dots -->
                    <div class="flex items-center space-x-1.5" title="Switch Hotel Visuals">
                        <button type="button" onclick="setLoginSlide(0)" class="carousel-dot active h-1.5 rounded-full transition-all cursor-pointer" id="loginDot0" aria-label="Slide 1"></button>
                        <button type="button" onclick="setLoginSlide(1)" class="carousel-dot h-1.5 rounded-full transition-all cursor-pointer" id="loginDot1" aria-label="Slide 2"></button>
                        <button type="button" onclick="setLoginSlide(2)" class="carousel-dot h-1.5 rounded-full transition-all cursor-pointer" id="loginDot2" aria-label="Slide 3"></button>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- RIGHT PANEL: Overlapping Curved White Card (Exact Reference Look) -->
        <!-- ================================================================= -->
        <div class="col-span-1 lg:col-span-6 xl:col-span-6 bg-white lg:rounded-l-[44px] xl:rounded-l-[52px] shadow-2xl p-8 sm:p-12 lg:p-14 xl:p-16 flex flex-col justify-between relative z-20">

            <!-- Top Row: Pax TV Launcher Logo (Left) + Sign Up (Right) -->
            <div class="flex items-center justify-between mb-8 sm:mb-12">
                <a href="{{ url('/') }}" class="inline-block group focus:outline-none">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Pax TV Launcher" 
                         class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-[#150A42] transition-colors">
                    <i class="fa-regular fa-user text-xs"></i>
                    <span>Sign Up</span>
                </a>
            </div>

            <!-- Middle: Sign In Form Content -->
            <div class="max-w-md w-full mx-auto my-auto py-2">
                <!-- Title -->
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-8">
                    Sign In
                </h2>

                <!-- Validation Alerts -->
                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500 shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                        <div class="font-bold flex items-center space-x-1.5">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                            <span>Authentication failed</span>
                        </div>
                        <ul class="list-disc list-inside text-[11px] text-rose-600 space-y-0.5 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email / Username Input (Signature Smooth Pill) -->
                    <div>
                        <input id="email" 
                               type="text" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="Email or Username"
                               class="w-full px-6 py-4 text-sm font-medium rounded-full border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-white text-slate-800 placeholder-slate-400 transition-all shadow-xs">
                    </div>

                    <!-- Password Input with Toggle (Signature Smooth Pill) -->
                    <div class="relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Password"
                               class="w-full px-6 py-4 pr-14 text-sm font-medium rounded-full border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-white text-slate-800 placeholder-slate-400 transition-all shadow-xs">
                        
                        <button type="button" 
                                onclick="toggleLoginPassword()" 
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition-colors"
                                aria-label="Toggle password visibility">
                            <i id="passwordToggleIcon" class="fa-regular fa-eye-slash text-sm"></i>
                        </button>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="pt-1 text-left">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#FF5722] hover:text-[#E64A19] transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Sign In Pill Button (Signature Arrow + Gradient) -->
                    <button type="submit" 
                            class="w-full mt-6 py-4 px-8 rounded-full bg-gradient-to-r from-[#FF5722] to-[#FF3D00] hover:from-[#f4511e] hover:to-[#e63900] text-white font-extrabold text-sm tracking-wide shadow-lg shadow-[#FF5722]/30 hover:shadow-xl hover:shadow-[#FF5722]/40 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>
            </div>

            <!-- Footer: Copyright + Contact Us / Language -->
            <div class="pt-8 sm:pt-12 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-400 font-medium">
                <div>
                    &copy; 2005-2026 Pax TV Launcher Inc.
                </div>
                <div class="flex items-center space-x-4">
                    <a href="mailto:support@paxtvnetwork.com" class="hover:text-slate-600 transition-colors">Contact Us</a>
                    <span class="hover:text-slate-600 cursor-pointer flex items-center space-x-1 select-none">
                        <span>English</span>
                        <i class="fa-solid fa-chevron-down text-[9px]"></i>
                    </span>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Interactive Cosmic Particle Canvas & Slider Logic -->
<script>
    // 1. Password Visibility Toggle
    function toggleLoginPassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
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

    // 2. Full-Panel Background Sliding Images Logic
    let currentSlide = 0;
    const totalSlides = 3;
    const slides = [
        document.getElementById('loginSlide1'),
        document.getElementById('loginSlide2'),
        document.getElementById('loginSlide3')
    ];
    const dots = [
        document.getElementById('loginDot0'),
        document.getElementById('loginDot1'),
        document.getElementById('loginDot2')
    ];

    function setLoginSlide(index) {
        currentSlide = index;
        slides.forEach((slide, i) => {
            if (slide) {
                if (i === currentSlide) {
                    slide.classList.remove('opacity-0', 'scale-105');
                    slide.classList.add('opacity-25', 'scale-100');
                } else {
                    slide.classList.remove('opacity-25', 'scale-100');
                    slide.classList.add('opacity-0', 'scale-105');
                }
            }
        });

        dots.forEach((dot, i) => {
            if (dot) {
                if (i === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            }
        });
    }

    // Auto-advance sliding images every 6 seconds
    setInterval(() => {
        const next = (currentSlide + 1) % totalSlides;
        setLoginSlide(next);
    }, 6000);

    // 3. Interactive Particle Stars Canvas
    (function initCosmicStars() {
        const canvas = document.getElementById('cosmicParticles');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });

        const numStars = Math.min(Math.floor((width * height) / 12000), 75);
        const stars = [];

        for (let i = 0; i < numStars; i++) {
            stars.push({
                x: Math.random() * width,
                y: Math.random() * height,
                radius: Math.random() * 1.4 + 0.3,
                baseAlpha: Math.random() * 0.5 + 0.2,
                alphaSpeed: Math.random() * 0.02 + 0.005,
                alphaPhase: Math.random() * Math.PI * 2,
                vx: (Math.random() - 0.5) * 0.15,
                vy: (Math.random() - 0.5) * 0.15,
                hue: Math.random() > 0.4 ? 265 : 220
            });
        }

        let time = 0;
        function animateStars() {
            ctx.clearRect(0, 0, width, height);
            time += 0.03;

            for (let i = 0; i < stars.length; i++) {
                const s = stars[i];
                s.x += s.vx;
                s.y += s.vy;

                if (s.x < 0) s.x = width;
                if (s.x > width) s.x = 0;
                if (s.y < 0) s.y = height;
                if (s.y > height) s.y = 0;

                const currentAlpha = s.baseAlpha + Math.sin(time + s.alphaPhase) * 0.25;
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.radius, 0, Math.PI * 2);
                ctx.fillStyle = `hsla(${s.hue}, 85%, 85%, ${Math.max(0.08, currentAlpha)})`;
                ctx.fill();
            }

            requestAnimationFrame(animateStars);
        }
        animateStars();
    })();
</script>
@endsection
