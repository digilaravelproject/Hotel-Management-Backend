@extends('layouts.app')

@section('title', 'Sign In - ' . app_name())

@section('styles')
<style>
    .login-cosmic-canvas {
        background: radial-gradient(circle at 50% 15%, #1f124d 0%, #10092c 50%, #060312 100%);
    }
    .slide-fade {
        transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
    }
    .carousel-dot.active {
        width: 2rem;
        background-color: #FFFFFF;
    }
    .carousel-dot:not(.active) {
        width: 0.5rem;
        background-color: rgba(255, 255, 255, 0.4);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 login-cosmic-canvas relative overflow-hidden selection:bg-purple-600 selection:text-white">

    <!-- Ambient Glow Highlights in Background -->
    <div class="absolute top-1/4 left-1/3 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-600/15 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-[500px] h-[500px] bg-indigo-600/12 rounded-full blur-[130px] pointer-events-none"></div>

    <!-- Main Floating Split-Screen Card (Image 1 Style) -->
    <div class="w-full max-w-5xl bg-[#150E2D]/90 backdrop-blur-2xl border border-white/10 rounded-[30px] sm:rounded-[36px] shadow-2xl shadow-black/70 p-3 sm:p-4 lg:p-5 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">

        <!-- ================================================================= -->
        <!-- LEFT PANEL: Scenic Visual Slider with Overlay & Taglines          -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 xl:col-span-7 relative rounded-[22px] sm:rounded-[28px] overflow-hidden min-h-[380px] sm:min-h-[460px] lg:min-h-[620px] flex flex-col justify-between p-6 sm:p-8 bg-slate-950 shadow-inner select-none">

            <!-- Background Slide Images -->
            <div class="absolute inset-0 z-0">
                <!-- Slide 1 -->
                <img id="loginSlide1" 
                     src="{{ asset('images/landing/tv-wallpaper-resort.jpg') }}" 
                     alt="Luxury Hotel Resort" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-100 scale-100">
                <!-- Slide 2 -->
                <img id="loginSlide2" 
                     src="{{ asset('images/landing/hero-suite-sunset.jpg') }}" 
                     alt="Smart TV Suite" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-0 scale-105">
                <!-- Slide 3 -->
                <img id="loginSlide3" 
                     src="{{ asset('images/landing/about-suite-clean.jpg') }}" 
                     alt="In-Room Hospitality" 
                     class="slide-fade absolute inset-0 w-full h-full object-cover opacity-0 scale-105">

                <!-- Gradient Vignettes for Atmospheric Contrast -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#090417] via-[#090417]/50 to-black/35 z-10"></div>
                <div class="absolute inset-0 bg-[#150A42]/30 mix-blend-multiply z-10"></div>
            </div>

            <!-- Top Header Inside Visual: Brand Logo & Back to Website Pill -->
            <div class="relative z-20 flex items-center justify-between w-full">
                <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105" title="{{ app_name() }}">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" 
                             alt="{{ app_name() }}" 
                             class="h-9 sm:h-11 w-auto object-contain drop-shadow-lg">
                    </picture>
                </a>

                <a href="{{ url('/') }}" 
                   class="px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-semibold border border-white/20 backdrop-blur-md transition-all flex items-center space-x-1.5 shadow-sm hover:border-white/30">
                    <span>Back to website</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Bottom Content Inside Visual: Captions & Slide Dots -->
            <div class="relative z-20 space-y-4 pt-12">
                <div class="space-y-2">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-200 text-[11px] font-bold tracking-wider uppercase backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                        <span>Smart Hospitality Cloud</span>
                    </div>

                    <h2 id="slideHeadline" class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight drop-shadow-md">
                        Next-Gen Hospitality,<br>Seamless In-Room TV.
                    </h2>

                    <p id="slideSubtitle" class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed max-w-md drop-shadow-sm">
                        Unified smart TV platform empowering luxury hotels, distributors, and connected TV operators worldwide.
                    </p>
                </div>

                <!-- Slide Dots Indicators -->
                <div class="flex items-center space-x-2 pt-2">
                    <button type="button" onclick="goToSlide(0)" class="carousel-dot active h-1.5 rounded-full transition-all duration-300 cursor-pointer" aria-label="Slide 1"></button>
                    <button type="button" onclick="goToSlide(1)" class="carousel-dot h-1.5 rounded-full transition-all duration-300 cursor-pointer" aria-label="Slide 2"></button>
                    <button type="button" onclick="goToSlide(2)" class="carousel-dot h-1.5 rounded-full transition-all duration-300 cursor-pointer" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- RIGHT PANEL: Sleek Professional Authentication Form               -->
        <!-- ================================================================= -->
        <div class="lg:col-span-6 xl:col-span-5 flex flex-col justify-center px-3 sm:px-6 lg:px-4 xl:px-8 py-4 sm:py-6 text-left">

            <!-- Title & Subtitle -->
            <div class="space-y-1.5 mb-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-sans">
                    Welcome back
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 font-medium">
                    Sign in with your email to access your dashboard.
                </p>
            </div>

            <!-- Flash & Error Alerts -->
            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-4 p-3.5 rounded-2xl bg-purple-500/15 border border-purple-500/30 text-purple-200 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-info text-purple-400 text-sm shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-4 p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs space-y-1">
                    <div class="flex items-center space-x-2 font-bold text-rose-200">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm"></i>
                        <span>Authentication failed:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1 text-rose-300 text-xs font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Common Multi-Role Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="loginEmail" class="block text-xs font-bold text-slate-300">
                        Email Address <span class="text-rose-400 font-black">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input type="email" 
                               id="loginEmail" 
                               name="email" 
                               required 
                               value="{{ old('email') }}" 
                               placeholder="name@company.com" 
                               autocomplete="email" 
                               autofocus
                               class="w-full pl-10 pr-4 py-3 bg-[#1F1739]/90 border border-white/10 rounded-xl text-sm font-semibold text-white placeholder:text-slate-500 focus:bg-[#281F48] focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-400 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="loginPassword" class="block text-xs font-bold text-slate-300">
                            Password <span class="text-rose-400 font-black">*</span>
                        </label>
                        @if(Route::has('hotel.forgot-password'))
                            <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-semibold text-purple-400 hover:text-purple-300 hover:underline transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-lock text-xs"></i>
                        </div>
                        <input type="password" 
                               id="loginPassword" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               autocomplete="current-password"
                               class="w-full pl-10 pr-11 py-3 bg-[#1F1739]/90 border border-white/10 rounded-xl text-sm font-semibold text-white placeholder:text-slate-500 focus:bg-[#281F48] focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-400 transition-all">
                        <button type="button" 
                                onclick="togglePasswordVisibility('loginPassword', 'loginEyeIcon')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer" 
                                aria-label="Toggle password visibility">
                            <i id="loginEyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center space-x-2 text-xs font-semibold text-slate-400 hover:text-slate-200 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="remember" 
                               id="rememberDevice" 
                               {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 rounded-md border-white/20 bg-[#1F1739] text-purple-600 focus:ring-purple-500/30 cursor-pointer">
                        <span>Remember this device</span>
                    </label>
                </div>

                <!-- Primary Submit CTA Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-5 rounded-xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-500 hover:via-violet-500 hover:to-indigo-500 text-white font-extrabold text-sm shadow-xl shadow-purple-600/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer mt-2">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Bottom Multi-Role Detection Note -->
            <div class="mt-6 pt-5 border-t border-white/10 flex items-center justify-center space-x-2 text-xs text-slate-400 font-medium">
                <i class="fa-solid fa-shield-halved text-emerald-400 text-xs"></i>
                <span>Smart Multi-Tenant Enterprise Authentication</span>
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
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // 2. Smooth Left Panel Image Carousel
    const slideData = [
        {
            headline: "Next-Gen Hospitality,<br>Seamless In-Room TV.",
            subtitle: "Unified smart TV platform empowering luxury hotels, distributors, and connected TV operators worldwide."
        },
        {
            headline: "Connected TVs,<br>Zero Guest Friction.",
            subtitle: "Manage connected TV pairing, live app installations, and branded guest menus in real-time."
        },
        {
            headline: "5-Star Experience,<br>Automated Privacy.",
            subtitle: "Instant credentials wiped on guest checkout with zero local credential storage on property screens."
        }
    ];

    let currentSlide = 0;
    const slides = [
        document.getElementById('loginSlide1'),
        document.getElementById('loginSlide2'),
        document.getElementById('loginSlide3')
    ];
    const dots = document.querySelectorAll('.carousel-dot');
    const headlineEl = document.getElementById('slideHeadline');
    const subtitleEl = document.getElementById('slideSubtitle');

    function goToSlide(index) {
        currentSlide = index;
        slides.forEach((slide, i) => {
            if (i === currentSlide) {
                slide.classList.remove('opacity-0', 'scale-105');
                slide.classList.add('opacity-100', 'scale-100');
            } else {
                slide.classList.remove('opacity-100', 'scale-100');
                slide.classList.add('opacity-0', 'scale-105');
            }
        });

        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        if (headlineEl && slideData[currentSlide]) {
            headlineEl.innerHTML = slideData[currentSlide].headline;
        }
        if (subtitleEl && slideData[currentSlide]) {
            subtitleEl.textContent = slideData[currentSlide].subtitle;
        }
    }

    // Auto rotate every 6 seconds
    setInterval(() => {
        const nextSlide = (currentSlide + 1) % slides.length;
        goToSlide(nextSlide);
    }, 6000);
</script>
@endsection
