@extends('layouts.landing')

@section('title', 'PAX TV - Luxury Hotel Smart TV OS & Guest Experience Platform')

@section('styles')
<!-- Luxury Google Typography: Playfair Display, Cinzel, Cormorant Garamond, Plus Jakarta Sans, DM Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ========================================================================= */
    /* 1. LUXURY COLOR TOKENS & TYPOGRAPHY SYSTEM */
    /* ========================================================================= */
    :root {
        --color-gold: #DFBA58;
        --color-gold-hover: #EBC66B;
        --color-gold-light: #F5E8C7;
        --color-gold-dark: #9E7B35;
        --color-gold-subtle: rgba(223, 186, 88, 0.12);
        --color-gold-glow: rgba(223, 186, 88, 0.22);
        --color-gold-border: rgba(223, 186, 88, 0.24);

        --color-bg-noir: #0B0E14;
        --color-bg-card-dark: #121722;
        --color-bg-alabaster: #F7F5F0;
        --color-bg-cream-card: #FFFFFF;

        --text-primary-dark: #F8F9FA;
        --text-muted-dark: #A6B0BF;
        --text-primary-light: #1A1D23;
        --text-muted-light: #5A6270;
    }

    .font-serif-lux {
        font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif !important;
        letter-spacing: -0.01em;
    }
    .font-sans-lux {
        font-family: 'Plus Jakarta Sans', 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
    }

    /* ========================================================================= */
    /* 2. STANDARDIZED LUXURY BUTTON SYSTEM (TOUCH TARGET ≥ 44px) */
    /* ========================================================================= */
    .btn-gold-pill {
        background: linear-gradient(135deg, #E6C466 0%, #D4AE46 100%);
        color: #0B0E14;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.04em;
        border-radius: 9999px;
        min-height: 44px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 18px var(--color-gold-glow);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .btn-gold-pill::before {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
        transition: left 0.6s ease;
    }
    .btn-gold-pill:hover::before {
        left: 100%;
    }
    .btn-gold-pill:hover {
        background: linear-gradient(135deg, #F0CE74 0%, #DFBA58 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 26px rgba(223, 186, 88, 0.45);
    }
    .btn-gold-pill:active {
        transform: translateY(0);
    }

    .btn-glass-pill {
        background-color: rgba(18, 23, 34, 0.65);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(223, 186, 88, 0.28);
        color: #F8F9FA;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 500;
        font-size: 0.8125rem;
        letter-spacing: 0.04em;
        border-radius: 9999px;
        min-height: 44px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-glass-pill:hover {
        background-color: rgba(223, 186, 88, 0.15);
        border-color: rgba(223, 186, 88, 0.7);
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
    .btn-glass-pill:active {
        transform: translateY(0);
    }

    .btn-card-pill {
        background: rgba(223, 186, 88, 0.12);
        color: #8C6A24;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.04em;
        border-radius: 9999px;
        padding: 0.5rem 1.15rem;
        min-height: 38px;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(223, 186, 88, 0.32);
    }
    .btn-card-pill:hover {
        background: #DFBA58;
        color: #0B0E14;
        border-color: #DFBA58;
        transform: translateY(-1.5px);
        box-shadow: 0 4px 14px rgba(223, 186, 88, 0.35);
    }

    /* ========================================================================= */
    /* 3. 3D HARDWARE TV STAGE (RESPONSIVE SAFE: FLAT ON MOBILE, 3D ON DESKTOP) */
    /* ========================================================================= */
    .tv-perspective-stage {
        perspective: 1100px;
        perspective-origin: 85% 45%;
    }

    .tv-tilted-rig {
        transform-style: preserve-3d;
        transform-origin: 90% 50%;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Desktop only: full 3D tilt with float animation */
    @media (min-width: 1024px) {
        .tv-tilted-rig {
            transform: rotateY(-22deg) rotateX(2.5deg) rotateZ(-0.8deg);
            animation: floatTV 6s ease-in-out infinite alternate;
        }
        .tv-tilted-rig:hover {
            transform: rotateY(-15deg) rotateX(1.5deg) translateY(-8px);
        }
    }

    /* Mobile & Tablet: zero overflow rotation */
    @media (max-width: 1023px) {
        .tv-perspective-stage {
            perspective: none;
        }
        .tv-tilted-rig {
            transform: none !important;
            animation: none !important;
        }
    }

    @keyframes floatTV {
        0% { transform: rotateY(-22deg) rotateX(2.5deg) translateY(0px); }
        100% { transform: rotateY(-20deg) rotateX(1.8deg) translateY(-8px); }
    }

    .tv-screen-chassis-3d {
        background: #080A0F;
        border: 2px solid #222938;
        border-right: 4px solid #333F57;
        border-bottom: 4px solid #18202D;
        border-radius: 1.25rem;
        box-shadow: 
            0 20px 40px -10px rgba(0, 0, 0, 0.85),
            0 0 0 1px rgba(223, 186, 88, 0.15);
    }
    @media (min-width: 1024px) {
        .tv-screen-chassis-3d {
            border-right: 7px solid #333F57;
            border-bottom: 5px solid #18202D;
            box-shadow: 
                -28px 32px 65px -8px rgba(0, 0, 0, 0.96),
                -10px 14px 28px rgba(0, 0, 0, 0.82),
                0 0 0 1px rgba(223, 186, 88, 0.15);
        }
    }

    .credenza-console-3d {
        background: linear-gradient(180deg, #241810 0%, #110B07 100%);
        border-top: 2px solid #4C3322;
        border-right: 4px solid #5C3E29;
        border-bottom: 2px solid #0E0805;
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.85);
    }
    @media (min-width: 1024px) {
        .credenza-console-3d {
            border-right: 6px solid #5C3E29;
            box-shadow: -22px 28px 55px rgba(0, 0, 0, 0.92);
        }
    }

    /* Ambient Pulsing Glow */
    @keyframes ambientPulse {
        0%, 100% { opacity: 0.15; transform: scale(1); }
        50% { opacity: 0.28; transform: scale(1.04); }
    }
    .tv-ambient-glow {
        animation: ambientPulse 5s ease-in-out infinite;
    }

    /* ========================================================================= */
    /* 4. PERFORMANCE-SAFE RESPONSIVE SCROLL ANIMATIONS */
    /* ========================================================================= */
    .lux-reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .lux-reveal-left {
        opacity: 0;
        transform: translateX(-24px);
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .lux-reveal-right {
        opacity: 0;
        transform: translateX(24px);
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .lux-reveal-scale {
        opacity: 0;
        transform: scale(0.96);
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    /* On mobile viewports: pure vertical reveal to prevent horizontal jank or scroll clipping */
    @media (max-width: 767px) {
        .lux-reveal-left,
        .lux-reveal-right {
            transform: translateY(20px) !important;
        }
    }

    .lux-active {
        opacity: 1 !important;
        transform: translateY(0) translateX(0) scale(1) !important;
    }

    .del-1 { transition-delay: 60ms; }
    .del-2 { transition-delay: 120ms; }
    .del-3 { transition-delay: 180ms; }
    .del-4 { transition-delay: 240ms; }
    .del-5 { transition-delay: 300ms; }

    /* Sticky Navbar Scrolled Transition */
    .header-nav {
        transition: background-color 0.35s ease, backdrop-filter 0.35s ease, border-color 0.35s ease, padding 0.35s ease, box-shadow 0.35s ease;
    }
    .header-nav.is-scrolled {
        background-color: rgba(11, 14, 20, 0.95) !important;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-bottom: 1px solid rgba(223, 186, 88, 0.2);
        padding-top: 0.65rem !important;
        padding-bottom: 0.65rem !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
</style>
@endsection

@section('content')
<div class="relative bg-[#0B0E14] text-[#F8F9FA] font-sans-lux min-h-screen selection:bg-[#DFBA58] selection:text-[#0B0E14] overflow-x-hidden w-full">

    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION & RESPONSIVE HEADER WITH MOBILE MENU -->
    <!-- ========================================================================= -->
    <div class="relative min-h-[90vh] lg:min-h-screen flex flex-col justify-between overflow-hidden">
        
        <!-- Luxury Hotel Suite Sunset Panoramic Room Background -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/hero-suite-sunset.jpg') }}" 
                 alt="Luxury Hotel Penthouse Suite Sunset" 
                 class="w-full h-full object-cover object-center filter brightness-[0.52] contrast-[1.05]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0B0E14] via-[#0B0E14]/40 to-[#0B0E14]/80"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0B0E14]/95 via-[#0B0E14]/60 to-black/30"></div>
        </div>

        <!-- Sticky Dynamic Header Navbar -->
        <header id="mainHeader" class="header-nav fixed top-0 left-0 right-0 z-50 pt-4 sm:pt-5 pb-3 sm:pb-4 bg-gradient-to-b from-black/85 via-black/40 to-transparent">
            <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 flex items-center justify-between">
                
                <!-- Brand Logo -->
                <a href="{{ route('landing') }}" class="flex items-center space-x-2.5 group cursor-pointer shrink-0">
                    <img src="{{ asset('images/logo/logo.png') }}" 
                         alt="PAX TV" 
                         class="h-8 sm:h-10 lg:h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>

                <!-- Centered Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center space-x-6 lg:space-x-9 text-xs sm:text-[13px] font-medium tracking-wide">
                    <div class="relative py-1">
                        <a href="#home" class="text-white hover:text-[#DFBA58] transition-colors">Home</a>
                        <span class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-[#DFBA58] to-[#F5E8C7] rounded-full"></span>
                    </div>
                    <a href="#features" class="text-stone-300 hover:text-[#DFBA58] transition-colors">Features</a>
                    <a href="#solutions" class="text-stone-300 hover:text-[#DFBA58] transition-colors">Solutions</a>
                    <a href="#about" class="text-stone-300 hover:text-[#DFBA58] transition-colors">About</a>
                    <a href="{{ route('contact-us') }}" class="text-stone-300 hover:text-[#DFBA58] transition-colors">Contact</a>
                </nav>

                <!-- Right Action Buttons + Hamburger Menu Toggle -->
                <div class="flex items-center space-x-2.5 sm:space-x-3">
                    <a href="{{ route('hotel.login') }}" class="text-[11px] sm:text-xs font-medium text-stone-200 hover:text-[#DFBA58] flex items-center space-x-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#DFBA58]/40 transition-all">
                        <i class="fa-solid fa-hotel text-[10px] sm:text-[11px] text-[#DFBA58]"></i>
                        <span class="hidden xs:inline">Hotel </span><span>Login</span>
                    </a>
                    <button onclick="openRegisterModal()" class="px-4 sm:px-5 lg:px-6 py-2 btn-gold-pill text-xs tracking-wider flex items-center space-x-1 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-xs sm:text-sm font-bold leading-none font-mono">›</span>
                    </button>

                    <!-- Mobile Hamburger Button -->
                    <button id="mobileMenuBtn" aria-label="Toggle Navigation Menu" class="md:hidden w-9 h-9 rounded-full bg-white/5 border border-white/15 text-stone-200 flex items-center justify-center hover:text-[#DFBA58] hover:border-[#DFBA58]/40 transition-colors">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                </div>

            </div>

            <!-- Mobile Dropdown Drawer (Smooth Reveal) -->
            <div id="mobileMenu" class="hidden md:hidden px-4 pt-3 pb-5 mt-2 bg-[#0B0E14]/95 backdrop-blur-xl border-b border-[#DFBA58]/20 space-y-2.5 shadow-2xl">
                <a href="#home" onclick="closeMobileMenu()" class="block px-3 py-2 text-sm font-medium text-white hover:text-[#DFBA58] rounded-lg hover:bg-white/5">Home</a>
                <a href="#features" onclick="closeMobileMenu()" class="block px-3 py-2 text-sm font-medium text-stone-300 hover:text-[#DFBA58] rounded-lg hover:bg-white/5">Features</a>
                <a href="#solutions" onclick="closeMobileMenu()" class="block px-3 py-2 text-sm font-medium text-stone-300 hover:text-[#DFBA58] rounded-lg hover:bg-white/5">Solutions</a>
                <a href="#about" onclick="closeMobileMenu()" class="block px-3 py-2 text-sm font-medium text-stone-300 hover:text-[#DFBA58] rounded-lg hover:bg-white/5">About Us</a>
                <a href="{{ route('contact-us') }}" onclick="closeMobileMenu()" class="block px-3 py-2 text-sm font-medium text-stone-300 hover:text-[#DFBA58] rounded-lg hover:bg-white/5">Contact</a>
            </div>
        </header>

        <!-- Navbar Spacing Buffer -->
        <div class="h-16 sm:h-20 lg:h-24"></div>

        <!-- HERO CONTENT: LEFT TYPOGRAPHY & RIGHT SMART TV CONSOLE -->
        <div id="home" class="relative z-10 max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 pt-4 pb-10 lg:py-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center w-full my-auto">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-5 space-y-4 sm:space-y-5 text-left lux-reveal-left lux-active">
                
                <!-- Gold Overline Badge -->
                <div class="inline-flex items-center space-x-2 px-3 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-[#DFBA58]/10 border border-[#DFBA58]/30 text-[#DFBA58] text-[10px] sm:text-xs font-semibold tracking-[0.2em] sm:tracking-[0.22em] uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#DFBA58] animate-ping"></span>
                    <span>PREMIUM HOTEL TV SOLUTION</span>
                </div>

                <!-- Responsive Hero Headline -->
                <h1 class="font-serif-lux text-3xl sm:text-4xl lg:text-[3.25rem] font-medium tracking-tight text-white leading-[1.15]">
                    A Smarter<br>
                    <span class="bg-gradient-to-r from-white via-[#F5E8C7] to-[#DFBA58] bg-clip-text text-transparent">Stay Experience</span>
                </h1>

                <!-- Subtitle / Paragraph -->
                <p class="text-sm sm:text-base text-stone-300 leading-[1.6] sm:leading-[1.65] max-w-lg font-normal">
                    Transform every guest room into a personalized entertainment and hospitality hub with our advanced Hotel TV application.
                </p>

                <!-- Dual Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-1 sm:pt-2">
                    <button onclick="openRegisterModal()" class="w-full sm:w-auto px-6 sm:px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-sm font-bold leading-none font-mono">›</span>
                    </button>

                    <a href="#about" class="w-full sm:w-auto px-6 py-3 btn-glass-pill text-xs tracking-wider flex items-center justify-center space-x-2">
                        <i class="fa-regular fa-circle-play text-sm text-[#DFBA58]"></i>
                        <span>Watch Video</span>
                    </a>
                </div>

            </div>

            <!-- Right Hero: Smart TV Display on Luxury Console (Mobile-safe 100% width container) -->
            <div class="lg:col-span-7 relative tv-perspective-stage w-full max-w-md sm:max-w-xl lg:max-w-none mx-auto lux-reveal-right lux-active">
                
                <!-- Ambient Pulsing Champagne Gold Glow behind TV -->
                <div class="absolute -inset-4 sm:-inset-6 bg-[#DFBA58]/18 blur-2xl sm:blur-3xl rounded-full tv-ambient-glow -z-10"></div>

                <!-- 3D Tilted Hardware Rig (Flat on mobile to avoid overflow) -->
                <div class="tv-tilted-rig">

                    <!-- TV Hardware Frame & Display Screen -->
                    <div class="relative tv-screen-chassis-3d p-1.5 sm:p-2.5">
                        
                        <!-- Inside TV Screen Display (16:9 Aspect Ratio) -->
                        <div class="relative rounded-xl overflow-hidden aspect-[16/9] bg-slate-950 border border-white/10 group select-none shadow-inner">
                            <img src="{{ asset('images/landing/tvscreen.png') }}" 
                                 alt="Taj Hotel Smart TV OS Screen" 
                                 class="w-full h-full object-cover filter brightness-[1.02] contrast-[1.03] group-hover:scale-[1.02] transition-transform duration-700">
                            
                            <!-- Screen Reflection Overlays -->
                            <div class="absolute inset-0 pointer-events-none bg-gradient-to-tr from-transparent via-white/[0.04] to-white/[0.14]"></div>
                            <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_24px_rgba(0,0,0,0.65)]"></div>
                        </div>

                        <!-- TV Stand Base -->
                        <div class="w-20 sm:w-24 h-1.5 sm:h-2 bg-gradient-to-b from-[#3A404D] to-[#12151B] mx-auto mt-1 rounded-b-xs shadow-md"></div>
                    </div>

                    <!-- Luxury Dark Walnut TV Credenza Cabinet with Warm Underglow -->
                    <div class="relative mt-2 mx-auto w-full credenza-console-3d rounded-md p-2 sm:p-2.5 shadow-2xl">
                        <!-- Warm Amber LED Strip Light -->
                        <div class="h-[2px] w-full bg-gradient-to-r from-[#DFBA58]/20 via-[#DFBA58] to-[#DFBA58]/20 shadow-[0_2px_14px_rgba(223,186,88,0.7)]"></div>
                        <div class="flex items-center justify-between pt-1.5 sm:pt-2 px-3 sm:px-4 text-[9px] sm:text-[10px] text-stone-500">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-8 sm:w-12 h-1 bg-[#3A291E] rounded-full"></div>
                                <div class="w-8 sm:w-12 h-1 bg-[#3A291E] rounded-full"></div>
                            </div>
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-8 sm:w-12 h-1 bg-[#3A291E] rounded-full"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="h-2"></div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. FOUR FEATURES STRIP (RESPONSIVE GRID: 1 COL MOBILE, 2 COL TABLET, 4 COL DESKTOP) -->
    <!-- ========================================================================= -->
    <section id="features" class="relative z-20 bg-[#121722] text-[#F8F9FA] border-y border-[#DFBA58]/20 py-4 sm:py-6">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 divide-y sm:divide-y-0 lg:divide-x divide-white/10">
                
                <!-- Item 1: Customizable UI -->
                <div class="flex items-center space-x-3.5 pt-3 sm:pt-0 sm:py-1 px-1 lg:px-4 group lux-reveal del-1">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#DFBA58]/12 border border-[#DFBA58]/30 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105 shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#DFBA58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m14 10 7.5-7.5"/>
                            <path d="m3 21 8.5-8.5"/>
                            <circle cx="16.5" cy="4.5" r="0.5" fill="currentColor"/>
                            <circle cx="4.5" cy="16.5" r="0.5" fill="currentColor"/>
                            <path d="m9 3 1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2z"/>
                            <path d="m17 13 1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm sm:text-base text-white leading-snug">Customizable UI</h3>
                        <p class="text-xs text-stone-400 font-normal leading-tight mt-0.5">Branded experience for your hotel</p>
                    </div>
                </div>

                <!-- Item 2: Multi-Language -->
                <div class="flex items-center space-x-3.5 pt-3 sm:pt-0 sm:py-1 px-1 lg:px-4 group lux-reveal del-2">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#DFBA58]/12 border border-[#DFBA58]/30 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105 shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#DFBA58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 9a2 2 0 0 1-2 2H6l-3 3V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v4z"/>
                            <path d="M18 9h1a2 2 0 0 1 2 2v9l-3-3h-6a2 2 0 0 1-2-2v-1"/>
                            <path d="M6 7h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm sm:text-base text-white leading-snug">Multi-Language</h3>
                        <p class="text-xs text-stone-400 font-normal leading-tight mt-0.5">Global guest support</p>
                    </div>
                </div>

                <!-- Item 3: Hotel Information -->
                <div class="flex items-center space-x-3.5 pt-3 sm:pt-0 sm:py-1 px-1 lg:px-4 group lux-reveal del-3">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#DFBA58]/12 border border-[#DFBA58]/30 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105 shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#DFBA58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18"/>
                            <path d="M5 21V9l7-5 7 5v12"/>
                            <path d="M9 10v2"/>
                            <path d="M15 10v2"/>
                            <path d="M9 14v2"/>
                            <path d="M15 14v2"/>
                            <path d="M11 21v-3a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm sm:text-base text-white leading-snug">Hotel Information</h3>
                        <p class="text-xs text-stone-400 font-normal leading-tight mt-0.5">Showcase amenities & services</p>
                    </div>
                </div>

                <!-- Item 4: Secure & Reliable -->
                <div class="flex items-center space-x-3.5 pt-3 sm:pt-0 sm:py-1 px-1 lg:px-4 group lux-reveal del-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#DFBA58]/12 border border-[#DFBA58]/30 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105 shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#DFBA58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm sm:text-base text-white leading-snug">Secure & Reliable</h3>
                        <p class="text-xs text-stone-400 font-normal leading-tight mt-0.5">Built for speed & guest privacy</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. ABOUT US SECTION (RESPONSIVE STACK ON MOBILE / TABLET) -->
    <!-- ========================================================================= -->
    <section id="about" class="relative overflow-hidden bg-[#F7F5F0] text-[#1A1D23] border-b border-[#DFBA58]/20 py-10 sm:py-14 lg:py-16 flex items-center">
        
        <!-- Panoramic Suite Background (High contrast overlay on mobile) -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/landing/about-suite-clean.jpg') }}" 
                 alt="PAX TV Hotel Solution Experience" 
                 class="w-full h-full object-cover object-left md:object-center filter brightness-[1.02]">
            <div class="absolute inset-0 bg-[#F7F5F0]/92 md:bg-[#F7F5F0]/40 lg:bg-transparent"></div>
        </div>

        <div class="relative z-10 max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Space for TV & Suite Visual -->
                <div class="hidden lg:block lg:col-span-5"></div>

                <!-- Right Typography & CTA -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-5 text-left pl-0 lg:pl-6 lux-reveal-left">
                    
                    <div class="inline-flex items-center space-x-2 text-[#9E7B35] font-semibold text-xs tracking-[0.22em] uppercase">
                        <span class="w-4 h-[2px] bg-[#9E7B35]"></span>
                        <span>ABOUT US</span>
                    </div>

                    <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-[#1A1D23] leading-[1.22]">
                        Enhancing Hospitality<br>
                        Through Smart Technology
                    </h2>

                    <p class="text-sm sm:text-base text-[#5A6270] leading-[1.65] font-normal max-w-xl">
                        We provide advanced Hotel TV solutions that combine entertainment, information, and hospitality services into a single, intuitive interface crafted to create memorable guest experiences.
                    </p>

                    <div class="pt-2">
                        <button onclick="openRegisterModal()" class="w-full sm:w-auto px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-2 cursor-pointer shadow-md hover:shadow-lg transition-all">
                            <span>Learn More</span>
                            <span class="text-sm font-bold leading-none font-mono">›</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 4. OUR SOLUTIONS SECTION (RESPONSIVE 1/2/3 COLUMNS) -->
    <!-- ========================================================================= -->
    <section id="solutions" class="relative overflow-hidden bg-[#F7F5F0] text-[#1A1D23] border-b border-[#DFBA58]/20 py-10 sm:py-14 lg:py-16">
        
        <!-- Right Side Suite Image (Desktop only) -->
        <div class="absolute top-0 right-0 bottom-0 w-full lg:w-[46%] pointer-events-none hidden lg:block overflow-hidden z-0">
            <img src="{{ asset('images/landing/our-solutions-suite.jpg') }}" 
                 alt="Luxury Hotel Suite Experience" 
                 class="w-full h-full object-cover object-right">
            <div class="absolute inset-y-0 left-0 w-36 bg-gradient-to-r from-[#F7F5F0] via-[#F7F5F0]/85 to-transparent"></div>
            <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#F7F5F0] to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#F7F5F0] to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 w-full">
            
            <!-- Section Header -->
            <div class="max-w-xl mb-7 sm:mb-9 lux-reveal">
                <div class="inline-flex items-center space-x-2 text-[#9E7B35] font-semibold text-xs tracking-[0.22em] uppercase mb-2">
                    <span class="w-4 h-[2px] bg-[#9E7B35]"></span>
                    <span>TAILORED FOR YOUR HOTEL</span>
                </div>

                <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-[#1A1D23] leading-tight mb-2.5">
                    Our Solutions
                </h2>

                <p class="text-sm sm:text-base text-[#5A6270] leading-[1.65] font-normal">
                    A complete in-room entertainment and guest engagement solution designed for modern hospitality needs.
                </p>
            </div>

            <!-- Solutions Cards Grid (Responsive: 1-col on mobile, 2-col on tablet, 3-col on desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6 max-w-full lg:max-w-[65%] xl:max-w-[62%]">
                
                <!-- Card 1: Hotel TV Application -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/90 shadow-[0_4px_22px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_32px_rgba(223,186,88,0.18)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between lux-reveal del-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-4 sm:mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" stroke-linejoin="round"/>
                                <path d="M8 21h8M12 17v4" stroke-linecap="round"/>
                                <path d="M7 8h4M7 11h2" stroke-linecap="round"/>
                                <circle cx="15.5" cy="9.5" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-base sm:text-lg font-semibold text-[#1A1D23] tracking-tight leading-snug mb-1.5 font-sans-lux">
                            Hotel TV Application
                        </h3>

                        <p class="text-xs sm:text-[13px] text-[#5A6270] leading-[1.6] font-normal mb-4 sm:mb-5">
                            Custom branded interface with all essential entertainment & hospitality features.
                        </p>
                    </div>

                    <div>
                        <button onclick="openRegisterModal()" class="btn-card-pill cursor-pointer space-x-1.5">
                            <span>Learn More</span>
                            <span class="text-xs font-bold leading-none font-mono">›</span>
                        </button>
                    </div>
                </div>

                <!-- Card 2: System Integration -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/90 shadow-[0_4px_22px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_32px_rgba(223,186,88,0.18)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between lux-reveal del-2">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-4 sm:mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-base sm:text-lg font-semibold text-[#1A1D23] tracking-tight leading-snug mb-1.5 font-sans-lux">
                            System Integration
                        </h3>

                        <p class="text-xs sm:text-[13px] text-[#5A6270] leading-[1.6] font-normal mb-4 sm:mb-5">
                            Integrate with hotel PMS, hospitality services, and third-party apps seamlessly.
                        </p>
                    </div>

                    <div>
                        <button onclick="openRegisterModal()" class="btn-card-pill cursor-pointer space-x-1.5">
                            <span>Learn More</span>
                            <span class="text-xs font-bold leading-none font-mono">›</span>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Ongoing Support -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/90 shadow-[0_4px_22px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_32px_rgba(223,186,88,0.18)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between lux-reveal del-3 md:col-span-2 lg:col-span-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-4 sm:mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.663 3.04097 17.0827 4.74751 18.8732" stroke-linecap="round"/>
                                <polyline points="2 15 5 19 9 16" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-base sm:text-lg font-semibold text-[#1A1D23] tracking-tight leading-snug mb-1.5 font-sans-lux">
                            Ongoing Support
                        </h3>

                        <p class="text-xs sm:text-[13px] text-[#5A6270] leading-[1.6] font-normal mb-4 sm:mb-5">
                            Dedicated 24/7 technical support and regular updates for 99.9% uptime.
                        </p>
                    </div>

                    <div>
                        <button onclick="openRegisterModal()" class="btn-card-pill cursor-pointer space-x-1.5">
                            <span>Learn More</span>
                            <span class="text-xs font-bold leading-none font-mono">›</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 5. MULTI-LANGUAGE SUPPORT (RESPONSIVE TOUCH-FRIENDLY PILLS) -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden bg-[#0B0E14] text-white border-t border-[#DFBA58]/20 py-10 sm:py-14 lg:py-16 flex items-center">
        
        <!-- Panoramic Suite Background with TV -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/multi-language-bg.jpg') }}" 
                 alt="Hotel TV Multi-Language Interface" 
                 class="w-full h-full object-cover object-right md:object-center filter brightness-[1.0] contrast-[1.03]">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0B0E14] via-[#0B0E14]/85 to-transparent hidden md:block w-full lg:w-[65%]"></div>
            <div class="absolute inset-0 bg-[#0B0E14]/88 md:hidden"></div>
        </div>

        <div class="relative z-10 max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: Description and 3 Distinct Rows of Interactive Language Pills -->
                <div class="lg:col-span-7 xl:col-span-6 space-y-4 sm:space-y-5 text-left lux-reveal-left">
                    
                    <div class="inline-flex items-center space-x-2 text-[#DFBA58] font-semibold text-xs tracking-[0.22em] uppercase">
                        <span class="w-4 h-[2px] bg-[#DFBA58]"></span>
                        <span>GLOBAL GUEST EXPERIENCE</span>
                    </div>

                    <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-white leading-tight">
                        Multi-Language Support
                    </h2>

                    <p class="text-sm sm:text-base text-slate-300 leading-[1.65] font-normal max-w-md">
                        Cater to international guests with multiple language options and intuitive on-screen navigation.
                    </p>

                    <!-- Interactive Language Pills (Flex-wrap with comfortable touch targets) -->
                    <div class="space-y-2.5 pt-2 max-w-lg" id="landingLanguageContainer">
                        
                        <!-- Row 1 -->
                        <div class="flex flex-wrap gap-2 sm:gap-2.5">
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-[#DFBA58] text-[#0B0E14] font-bold text-xs shadow-md border-transparent ring-2 ring-[#DFBA58]/40 scale-105 cursor-pointer transition-all duration-200 active:scale-95">
                                English
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                हिंदी
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                मराठी
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                ગુજરાતી
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                தமிழ்
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                తెలుగు
                            </button>
                        </div>

                        <!-- Row 2 -->
                        <div class="flex flex-wrap gap-2 sm:gap-2.5">
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                ಕನ್ನಡ
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                বাংলা
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                മലയാളം
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Français
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Deutsch
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Español
                            </button>
                        </div>

                        <!-- Row 3 -->
                        <div class="flex flex-wrap gap-2 sm:gap-2.5">
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Português
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                中文
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-3.5 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                عربي
                            </button>
                        </div>

                    </div>

                </div>

                <div class="hidden lg:block lg:col-span-5 xl:col-span-6"></div>

            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 6. HOTEL INFORMATION & SERVICES (RESPONSIVE BADGE GRID) -->
    <!-- ========================================================================= -->
    <section class="py-10 sm:py-14 lg:py-16 bg-[#F7F5F0] text-[#1A1D23] border-t border-[#DFBA58]/20">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
            
            <!-- Left: Description and 6 Peach Icon Badges (2-col mobile, 4-col tablet/desktop) -->
            <div class="lg:col-span-6 space-y-4 sm:space-y-5 text-left lux-reveal-left">
                <div class="inline-flex items-center space-x-2 text-[#9E7B35] font-semibold text-xs tracking-[0.22em] uppercase">
                    <span class="w-4 h-[2px] bg-[#9E7B35]"></span>
                    <span>EVERYTHING YOUR GUESTS NEED</span>
                </div>

                <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-[#1A1D23] leading-tight">
                    Hotel Information & Services
                </h2>

                <p class="text-sm sm:text-base text-[#5A6270] leading-[1.65] font-normal max-w-lg">
                    Showcase hotel amenities, dining options, facilities and local attractions directly on the in-room TV.
                </p>

                <!-- Icon Badges (Clean 2-col on small phones, 4-col on tablet/desktop) -->
                <div class="space-y-3 sm:space-y-4 pt-2">
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-lg">
                        
                        <!-- 1. Hotel Information -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M3 21h18M6 18V7l6-4 6 4v11M9 9h2M13 9h2M9 13h2M13 13h2M9 17h2M13 17h2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                Hotel<br class="hidden sm:inline"> Information
                            </span>
                        </div>

                        <!-- 2. Dining & Restaurants -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M18 2v8a2 2 0 0 1-2 2h-1M15 12v10M8 2v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V2M5 9v13M5 2v5M7 2v5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                Dining &<br class="hidden sm:inline"> Restaurants
                            </span>
                        </div>

                        <!-- 3. Local Attractions -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 2l3 7h6l-5 4 2 7-6-4-6 4 2-7-5-4h6z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                Local<br class="hidden sm:inline"> Attractions
                            </span>
                        </div>

                        <!-- 4. In-Room Services -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 3v3M4 14a8 8 0 0 1 16 0H4zM2 18h20" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                In-Room<br class="hidden sm:inline"> Services
                            </span>
                        </div>

                        <!-- 5. Flight Information -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                Flight<br class="hidden sm:inline"> Info
                            </span>
                        </div>

                        <!-- 6. City Guide -->
                        <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/50 sm:bg-transparent border border-stone-200/50 sm:border-transparent">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#9E7B35] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M9 18l6-6-6-6M1 6v14l6-3 6 3 6-3 4 2V4l-4-2-6 3-6-3-6 3z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-[#1A1D23] text-center leading-tight">
                                City<br class="hidden sm:inline"> Guide
                            </span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Right: Luxury Hotel Suite Card with TV Displaying Hotel Information Interface -->
            <div class="lg:col-span-6 lux-reveal-right">
                <div class="relative rounded-2xl lg:rounded-3xl overflow-hidden shadow-2xl border border-stone-200/90 group bg-stone-900">
                    <img src="{{ asset('images/landing/hotel-info-tv-suite.jpg') }}" 
                         alt="Hotel Information & Services Smart TV" 
                         class="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. LIVE TV & ENTERTAINMENT (RESPONSIVE BADGES: 2-COL MOBILE, 3-COL TABLET, 5-COL DESKTOP) -->
    <!-- ========================================================================= -->
    <section class="py-10 sm:py-14 lg:py-16 bg-[#121722] text-[#F8F9FA] border-t border-[#DFBA58]/20">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
            
            <div class="lg:col-span-6 space-y-4 sm:space-y-5 text-left lux-reveal-left">
                <div class="inline-flex items-center space-x-2 text-[#DFBA58] font-semibold text-xs tracking-[0.22em] uppercase">
                    <span class="w-4 h-[2px] bg-[#DFBA58]"></span>
                    <span>NON-STOP ENTERTAINMENT</span>
                </div>

                <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-white leading-tight">
                    Live TV & Entertainment
                </h2>

                <p class="text-sm sm:text-base text-stone-300 leading-[1.65] font-normal max-w-lg">
                    Deliver seamless live TV, movies and guest entertainment with an intuitive, lag-free smart TV interface.
                </p>

                <!-- 5 Badges (Responsive: 2-col on small mobile, 3-col on tablet, 5-col on desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-2 max-w-xl">
                    
                    <!-- 1. Live TV Channels -->
                    <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/5 lg:bg-transparent border border-white/5 lg:border-transparent">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#DFBA58] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="15" rx="2" stroke-linejoin="round"/>
                                <polyline points="17 2 12 7 7 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-stone-200 text-center leading-tight">
                            Live TV<br class="hidden sm:inline"> Channels
                        </span>
                    </div>

                    <!-- 2. On-Demand Movies -->
                    <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/5 lg:bg-transparent border border-white/5 lg:border-transparent">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#DFBA58] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="2.18" stroke-linejoin="round"/>
                                <line x1="7" y1="2" x2="7" y2="22"/>
                                <line x1="17" y1="2" x2="17" y2="22"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <line x1="2" y1="7" x2="7" y2="7"/>
                                <line x1="2" y1="17" x2="7" y2="17"/>
                                <line x1="17" y1="17" x2="22" y2="17"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-stone-200 text-center leading-tight">
                            On-Demand<br class="hidden sm:inline"> Movies
                        </span>
                    </div>

                    <!-- 3. Web Applications -->
                    <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/5 lg:bg-transparent border border-white/5 lg:border-transparent">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#DFBA58] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-stone-200 text-center leading-tight">
                            Web<br class="hidden sm:inline"> Apps
                        </span>
                    </div>

                    <!-- 4. Screen Cast -->
                    <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/5 lg:bg-transparent border border-white/5 lg:border-transparent">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#DFBA58] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M2 16.1A5 5 0 0 1 5.9 20M2 12.05A9 9 0 0 1 9.95 20M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6M2 20h.01" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-stone-200 text-center leading-tight">
                            Screen<br class="hidden sm:inline"> Cast
                        </span>
                    </div>

                    <!-- 5. Recommendations -->
                    <div class="flex flex-col items-center text-center group cursor-pointer p-2 rounded-xl bg-white/5 lg:bg-transparent border border-white/5 lg:border-transparent col-span-2 sm:col-span-1">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#DFBA58]/15 border border-[#DFBA58]/35 flex items-center justify-center text-[#DFBA58] mb-2 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-stone-200 text-center leading-tight">
                            Smart<br class="hidden sm:inline"> Suggestions
                        </span>
                    </div>

                </div>
            </div>

            <!-- Right: TV Preview -->
            <div class="lg:col-span-6 lux-reveal-right">
                <div class="relative rounded-2xl lg:rounded-3xl overflow-hidden shadow-2xl border border-white/10 group bg-stone-900">
                    <img src="{{ asset('images/landing/live-tv-channels-suite.jpg') }}" 
                         alt="Live TV & Entertainment Broadcast Channels Smart TV" 
                         class="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. REQUEST A DEMO TODAY (RESPONSIVE BANNER) -->
    <!-- ========================================================================= -->
    <section class="relative py-12 sm:py-16 lg:py-20 text-white overflow-hidden border-t border-[#DFBA58]/20">
        
        <!-- Dusk Resort Hotel Background -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/demo.svg') }}" 
                 onerror="this.onerror=null;this.src='{{ asset('images/landing/demo.jpg') }}';" 
                 alt="Request a Demo - Luxury Beachfront Resort at Dusk" 
                 class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-r from-black/95 via-black/70 to-black/45"></div>
        </div>

        <div class="relative z-10 max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 sm:gap-8 lux-reveal">
            
            <div class="space-y-3.5 max-w-2xl text-left">
                <!-- Golden Overline Tag -->
                <div class="inline-flex items-center space-x-2 text-[#DFBA58] font-semibold text-xs tracking-[0.22em] uppercase">
                    <span class="w-4 h-[2px] bg-[#DFBA58]"></span>
                    <span>READY TO TRANSFORM YOUR GUEST EXPERIENCE?</span>
                </div>

                <!-- Editorial Headline -->
                <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-[2.25rem] font-medium tracking-tight text-white leading-tight">
                    Request a Demo Today
                </h2>

                <!-- Subtitle -->
                <p class="text-sm sm:text-base text-slate-200 leading-[1.65] font-normal max-w-lg">
                    Discover how our Hotel TV solution can add value to your property and delight your guests with seamless technology.
                </p>

                <!-- Dual Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button onclick="openRegisterModal()" class="w-full sm:w-auto px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-sm font-bold leading-none font-mono">›</span>
                    </button>

                    <a href="{{ route('contact-us') }}" class="w-full sm:w-auto px-7 py-3 btn-glass-pill text-xs font-semibold tracking-wider flex items-center justify-center">
                        Contact Us
                    </a>
                </div>
            </div>

            <div class="hidden lg:block lg:w-1/3"></div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. LUXURY DARK FOOTER (RESPONSIVE GRID) -->
    <!-- ========================================================================= -->
    <footer class="bg-[#080A0F] text-white border-t border-[#DFBA58]/20 pt-10 sm:pt-12 pb-8">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Column 1: Brand Info (4 cols) -->
                <div class="sm:col-span-2 lg:col-span-4 space-y-3.5 text-left">
                    <a href="{{ route('landing') }}" class="inline-block group">
                        <img src="{{ asset('images/logo/logo.png') }}" 
                             alt="PAX TV" 
                             class="h-9 sm:h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>

                    <p class="text-xs sm:text-[13px] text-stone-400 leading-relaxed font-normal pt-1">
                        Premium Hotel TV Solution<br>
                        for a Smarter Stay Experience.
                    </p>

                    <!-- Social Icons -->
                    <div class="flex items-center space-x-5 pt-1 text-stone-300">
                        <a href="#" class="hover:text-[#DFBA58] transition-colors" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in text-base"></i>
                        </a>
                        <a href="#" class="hover:text-[#DFBA58] transition-colors" aria-label="YouTube">
                            <i class="fa-brands fa-youtube text-base"></i>
                        </a>
                        <a href="#" class="hover:text-[#DFBA58] transition-colors" aria-label="Instagram">
                            <i class="fa-brands fa-instagram text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links (2.5 cols) -->
                <div class="lg:col-span-2 space-y-3 text-left">
                    <h4 class="text-sm font-semibold text-white tracking-wide">Quick Links</h4>
                    <ul class="space-y-2 text-xs sm:text-[13px] text-stone-400 font-normal">
                        <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#solutions" class="hover:text-white transition-colors">Solutions</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('hotel.login') }}" class="text-[#DFBA58] hover:text-[#EBC66B] font-medium transition-colors">Hotel Login</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Our Solutions (3 cols) -->
                <div class="lg:col-span-3 space-y-3 text-left">
                    <h4 class="text-sm font-semibold text-white tracking-wide">Our Solutions</h4>
                    <ul class="space-y-2 text-xs sm:text-[13px] text-stone-400 font-normal">
                        <li><a href="#solutions" class="hover:text-white transition-colors">Hotel TV Application</a></li>
                        <li><a href="#solutions" class="hover:text-white transition-colors">System Integration</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Multi-Language Support</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Hotel Information</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Live TV & Entertainment</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Us (3.5 cols) -->
                <div class="sm:col-span-2 lg:col-span-3 space-y-3 text-left">
                    <h4 class="text-sm font-semibold text-white tracking-wide">Contact Us</h4>
                    <ul class="space-y-2 text-xs sm:text-[13px] text-stone-300 font-normal">
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-location-dot text-sm text-[#DFBA58] w-4 shrink-0"></i>
                            <span>Mumbai, India</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-phone text-sm text-[#DFBA58] w-4 shrink-0"></i>
                            <a href="tel:+919876543210" class="hover:text-white transition-colors">+91 98765 43210</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-regular fa-envelope text-sm text-[#DFBA58] w-4 shrink-0"></i>
                            <a href="mailto:info@paxtv.com" class="hover:text-white transition-colors">info@paxtv.com</a>
                        </li>
                    </ul>

                    <div class="pt-2">
                        <button onclick="openRegisterModal()" 
                                class="w-full sm:w-auto px-7 py-2.5 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-1.5 cursor-pointer">
                            <span>Request Demo</span>
                            <span class="text-xs font-bold leading-none font-mono">›</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links -->
            <div class="mt-8 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-3">
                <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
                <p class="text-stone-400">
                    Developed by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="text-[#DFBA58] hover:text-[#EBC66B] font-medium transition-colors underline decoration-[#DFBA58]/40 underline-offset-2 hover:decoration-[#EBC66B]">Digi Emperor</a>
                </p>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-stone-300 transition-colors">Privacy Policy</a>
                    <span class="text-stone-700">|</span>
                    <a href="#" class="hover:text-stone-300 transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

</div>

<!-- ========================================================================= -->
<!-- REGISTRATION / REQUEST DEMO MODAL OVERLAY (RESPONSIVE) -->
<!-- ========================================================================= -->
<div id="registerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white text-slate-900 border border-stone-200 rounded-2xl sm:rounded-3xl w-full max-w-xl p-5 sm:p-7 space-y-5 shadow-2xl my-6 lux-reveal-scale lux-active">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#DFBA58] text-[#0B0E14] flex items-center justify-center shadow-xs shrink-0">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 font-serif-lux">PAX TV Registration</h3>
            </div>
            <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold cursor-pointer transition-colors leading-none">&times;</button>
        </div>

        <form id="registerForm" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
            @csrf
            <div id="registerError" class="hidden p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold"></div>

            <!-- Owner Section -->
            <div class="space-y-2.5">
                <h4 class="text-xs font-bold text-[#9E7B35] uppercase tracking-wider border-b border-slate-100 pb-1.5">Personal Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Owner Name</label>
                        <input type="text" name="owner_name" required placeholder="e.g. John Doe" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" required placeholder="e.g. 9876543210" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Email Address</label>
                        <input type="email" name="email" required placeholder="username@example.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                </div>
            </div>

            <!-- Hotel Section -->
            <div class="space-y-2.5">
                <h4 class="text-xs font-bold text-[#9E7B35] uppercase tracking-wider border-b border-slate-100 pb-1.5">Hotel Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Hotel Name</label>
                        <input type="text" name="hotel_name" required placeholder="e.g. Grand Resort" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Location / City</label>
                        <input type="text" name="hotel_location" required placeholder="e.g. Mumbai, India" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#DFBA58]/15 file:text-[#9E7B35]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#DFBA58]/15 file:text-[#9E7B35]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Total Room Count</label>
                    <input type="number" name="room_count" id="roomCountInput" min="1" required placeholder="e.g. 50" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DFBA58] focus:ring-1 focus:ring-[#DFBA58]">
                    
                    <div id="suggestedPlanBox" class="hidden p-3.5 rounded-2xl bg-[#DFBA58]/10 border border-[#DFBA58]/30 space-y-1 mt-2">
                        <span class="text-[10px] font-bold text-[#9E7B35] uppercase tracking-wider block">Suggested Subscription Plan</span>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                            <span id="suggestedPlanName">-</span>
                            <span id="suggestedPlanPrice" class="text-[#9E7B35] font-extrabold">-</span>
                        </div>
                        <input type="hidden" name="plan_id" id="suggestedPlanId">
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeRegisterModal()" class="px-4 sm:px-5 py-2.5 rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold cursor-pointer transition-colors">Cancel</button>
                <button type="submit" class="px-5 sm:px-7 py-2.5 btn-gold-pill text-xs shadow-md cursor-pointer">Pay & Complete</button>
            </div>
        </form>
    </div>
</div>

<!-- Simulated Payment Gateway Loading Overlay -->
<div id="paymentLoader" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-4 text-center text-white">
    <div class="w-12 h-12 border-4 border-white/20 border-t-[#DFBA58] rounded-full animate-spin mb-4"></div>
    <h3 id="loaderTitle" class="text-xl font-bold font-serif-lux">Processing Order Request</h3>
    <p id="loaderMessage" class="text-xs text-slate-400 font-medium mt-1">Talking to payment gateway. Please do not close this window...</p>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // =========================================================================
    // DYNAMIC STICKY NAVBAR BACKGROUND ON SCROLL
    // =========================================================================
    const mainHeader = document.getElementById('mainHeader');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 25) {
            mainHeader.classList.add('is-scrolled');
        } else {
            mainHeader.classList.remove('is-scrolled');
        }
    }, { passive: true });

    // =========================================================================
    // MOBILE NAVIGATION DRAWER TOGGLE
    // =========================================================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    function closeMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.add('hidden');
        }
    }

    // =========================================================================
    // GUARANTEED SCROLL REVEAL OBSERVER (RELIABLE WITH INSTANT FALLBACK)
    // =========================================================================
    function initScrollAnimations() {
        const revealElements = document.querySelectorAll('.lux-reveal, .lux-reveal-left, .lux-reveal-right, .lux-reveal-scale');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('lux-active');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.05,
                rootMargin: '0px 0px -15px 0px'
            });

            revealElements.forEach(el => observer.observe(el));
        } else {
            revealElements.forEach(el => el.classList.add('lux-active'));
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollAnimations);
    } else {
        initScrollAnimations();
    }

    // =========================================================================
    // REGISTRATION MODAL LOGIC
    // =========================================================================
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
        .catch(err => {
            console.error('Plan fetch error:', err);
        });
    }

    // =========================================================================
    // INTERACTIVE LANGUAGE PILL SELECTION
    // =========================================================================
    function selectLandingLanguage(btn) {
        if (!btn) return;
        const allPills = document.querySelectorAll('#landingLanguageContainer .lang-pill');
        allPills.forEach(p => {
            p.classList.remove('bg-[#DFBA58]', 'text-[#0B0E14]', 'font-bold', 'shadow-md', 'border-transparent', 'ring-2', 'ring-[#DFBA58]/40', 'scale-105');
            p.classList.add('bg-white/10', 'border', 'border-white/20', 'text-white', 'font-medium', 'scale-100');
        });

        btn.classList.remove('bg-white/10', 'border', 'border-white/20', 'text-white', 'font-medium', 'scale-100');
        btn.classList.add('bg-[#DFBA58]', 'text-[#0B0E14]', 'font-bold', 'shadow-md', 'border-transparent', 'ring-2', 'ring-[#DFBA58]/40', 'scale-105');
    }
</script>
@endsection
