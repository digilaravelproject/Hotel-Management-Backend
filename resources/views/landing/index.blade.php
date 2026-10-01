@extends('layouts.landing')

@section('title', 'PAX TV - Luxury Hotel Smart TV OS & Guest Experience Platform')

@section('styles')
<!-- Luxury Typography: Cormorant Garamond, Playfair Display, Cinzel, Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Direct Font Import Guarantee */
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    /* Exact Color Tokens from Reference Screenshot */
    :root {
        --color-gold: #E5A853;
        --color-gold-hover: #F2B660;
        --color-gold-light: #F7D59E;
        --color-gold-dark: #B88035;
        --color-gold-badge: #F4EFE6;
        --color-gold-border: #E8DCCB;
        --color-bg-dark: #0A0D14;
        --color-bg-dark-card: #0F131C;
        --color-bg-cream: #FAF8F5;
        --color-text-gold: #E5A853;
    }

    .font-serif-lux {
        font-family: 'Playfair Display', 'Cormorant Garamond', 'Cinzel', Georgia, serif !important;
    }
    .font-sans-lux {
        font-family: 'Plus Jakarta Sans', 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
    }

    /* Exact Warm Gold Pill Button from Screenshot */
    .btn-gold-pill {
        background-color: #E5A853;
        color: #111111;
        font-weight: 600;
        border-radius: 9999px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(229, 168, 83, 0.25);
    }
    .btn-gold-pill:hover {
        background-color: #F2B660;
        transform: translateY(-1.5px);
        box-shadow: 0 8px 24px rgba(229, 168, 83, 0.4);
    }

    /* Glass Pill Button (Watch Video) from Screenshot */
    .btn-glass-pill {
        background-color: rgba(10, 15, 25, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.28);
        color: #FFFFFF;
        font-weight: 500;
        border-radius: 9999px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-glass-pill:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.7);
        transform: translateY(-1.5px);
    }

    /* 3D Hardware Perspective for Smart TV Setup (Enhanced Cinematic Angle) */
    .tv-perspective-stage {
        perspective: 900px;
        perspective-origin: 85% 45%;
    }

    .tv-tilted-rig {
        transform-style: preserve-3d;
        transform: rotateY(-27deg) rotateX(3.5deg) rotateZ(-1.5deg);
        transform-origin: 90% 50%;
        transition: transform 0.45s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .tv-tilted-rig:hover {
        transform: rotateY(-21deg) rotateX(2.5deg) rotateZ(-1deg);
    }

    /* Ultra-realistic TV Hardware Frame with 3D Depth & Rim Highlight */
    .tv-screen-chassis-3d {
        background: #080a0f;
        border: 2px solid #222938;
        border-right: 7px solid #333f57;
        border-bottom: 5px solid #18202d;
        border-radius: 1.25rem;
        box-shadow: 
            -35px 40px 80px -10px rgba(0, 0, 0, 0.98),
            -15px 18px 35px rgba(0, 0, 0, 0.9),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    /* Wooden TV Media Console Cabinet in matching 3D */
    .credenza-console-3d {
        background: linear-gradient(180deg, #2c1e15 0%, #150e09 100%);
        border-top: 2px solid #543b2a;
        border-right: 6px solid #664834;
        border-bottom: 2px solid #100b07;
        box-shadow: -30px 35px 70px rgba(0, 0, 0, 0.95);
    }

    /* Wood Paneling Behind TV */
    .wood-slat-backdrop {
        background: repeating-linear-gradient(90deg, #241812 0px, #241812 18px, #1a110c 18px, #1a110c 24px);
    }

    /* Icon Box styling */
    .icon-box-gold {
        background-color: #F5EFE6;
        border: 1px solid #E6D8C4;
        color: #A07F54;
    }
</style>
@endsection

@section('content')
<div class="relative bg-[#0A0D14] text-slate-100 font-sans-lux min-h-screen selection:bg-[#E5A853] selection:text-slate-950">

    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <div class="relative min-h-[95vh] lg:min-h-screen flex flex-col justify-between overflow-hidden">
        
        <!-- Luxury Hotel Suite Sunset Panoramic Room Background -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/hero-suite-sunset.jpg') }}" 
                 alt="Luxury Hotel Penthouse Suite Sunset" 
                 class="w-full h-full object-cover object-center filter brightness-[0.52] contrast-[1.05]">
            <!-- Subtle Vignette & Dark Overlay for Crisp Contrast -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0D14] via-transparent to-[#0A0D14]/70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0D14]/90 via-[#0A0D14]/40 to-black/30"></div>
        </div>

        <!-- ========================================================================= -->
        <!-- HEADER NAVBAR (EXACT MATCH: LOGO, CENTER LINKS, REQUEST DEMO BUTTON) -->
        <!-- ========================================================================= -->
        <header class="relative z-50 px-6 lg:px-16 pt-5 pb-4 bg-gradient-to-b from-black/80 via-black/40 to-transparent">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                
                <!-- Brand Logo: Golden Rosette Crest + TAJ in Serif Typography -->
                <a href="{{ route('landing') }}" class="flex flex-col items-center group cursor-pointer">
                    <!-- Intricate Golden Sacred Geometry Rosette Emblem -->
                    <div class="w-8 h-8 text-[#E5A853] flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                        <svg class="w-7 h-7 fill-current drop-shadow-md" viewBox="0 0 40 40">
                            <!-- Outer 8-point geometric rosette -->
                            <polygon points="20,1 25,12 36,9 31,20 39,28 28,31 25,39 20,31 15,39 12,31 1,28 9,20 4,9 15,12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <!-- Inner concentric diamond flower -->
                            <polygon points="20,7 28,20 20,33 12,20" fill="none" stroke="currentColor" stroke-width="1.4"/>
                            <!-- Core faceted star -->
                            <polygon points="20,13 24,20 20,27 16,20" fill="currentColor"/>
                            <circle cx="20" cy="20" r="2.2" fill="#0A0D14"/>
                        </svg>
                    </div>
                    <!-- Brand Name -->
                    <span class="font-serif-lux font-bold text-lg sm:text-xl tracking-[0.28em] text-[#E5A853] uppercase leading-none mt-1 group-hover:text-[#F2B660] transition-colors">
                        TAJ
                    </span>
                </a>

                <!-- Centered Navigation Links (Exact: Home, Features, Solutions, Gallery, About, Contact) -->
                <nav class="hidden md:flex items-center space-x-9 text-xs font-medium tracking-wide">
                    <!-- Home with Active Golden Underline -->
                    <div class="relative py-1">
                        <a href="#home" class="text-white hover:text-[#E5A853] transition-colors">Home</a>
                        <span class="absolute bottom-0 left-0 right-0 h-[2px] bg-[#E5A853] rounded-full"></span>
                    </div>
                    <a href="#features" class="text-stone-200 hover:text-[#E5A853] transition-colors">Features</a>
                    <a href="#solutions" class="text-stone-200 hover:text-[#E5A853] transition-colors">Solutions</a>
                    <a href="#gallery" class="text-stone-200 hover:text-[#E5A853] transition-colors">Gallery</a>
                    <a href="#about" class="text-stone-200 hover:text-[#E5A853] transition-colors">About</a>
                    <a href="{{ route('contact-us') }}" class="text-stone-200 hover:text-[#E5A853] transition-colors">Contact</a>
                </nav>

                <!-- Right Action Button: Request Demo > (Warm Gold Pill) -->
                <div class="flex items-center">
                    <button onclick="openRegisterModal()" class="px-6 py-2.5 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-sm font-bold font-mono">›</span>
                    </button>
                </div>

            </div>
        </header>

        <!-- ========================================================================= -->
        <!-- HERO CONTENT: LEFT TYPOGRAPHY & RIGHT SMART TV CONSOLE -->
        <!-- ========================================================================= -->
        <div id="home" class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 pt-6 pb-16 lg:py-12 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center w-full">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-5 space-y-6 text-left">
                
                <!-- Gold Overline Badge -->
                <div class="text-[#E5A853] text-[11px] sm:text-xs font-semibold tracking-[0.25em] uppercase flex items-center space-x-2">
                    <span>PREMIUM HOTEL TV SOLUTION</span>
                </div>

                <!-- Editorial Headline (Exact wording & high contrast serif font) -->
                <h1 class="font-serif-lux text-4xl sm:text-5xl lg:text-[4.15rem] font-normal tracking-tight text-white leading-[1.1]">
                    A Smarter<br>
                    Stay Experience
                </h1>

                <!-- Subtitle / Paragraph -->
                <p class="text-xs sm:text-sm text-stone-300 leading-relaxed max-w-md font-normal">
                    Transform every guest room into a personalized entertainment and hospitality hub with our advanced Hotel TV application.
                </p>

                <!-- Dual Action Buttons: Request Demo > & Watch Video -->
                <div class="flex items-center space-x-4 pt-2">
                    <!-- Primary Gold Pill -->
                    <button onclick="openRegisterModal()" class="px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-sm font-bold font-mono">›</span>
                    </button>

                    <!-- Secondary Glass Pill with Play Circle -->
                    <a href="#about" class="px-6 py-3 btn-glass-pill text-xs tracking-wider flex items-center space-x-2.5">
                        <i class="fa-regular fa-circle-play text-base text-white"></i>
                        <span>Watch Video</span>
                    </a>
                </div>

            </div>

            <!-- Right Hero: Smart TV Display on Luxury Console in 3D Perspective -->
            <div class="lg:col-span-7 relative tv-perspective-stage">
                
                <!-- Ambient Amber Underglow behind TV -->
                <div class="absolute -inset-4 bg-[#E5A853]/15 blur-3xl rounded-3xl -z-10"></div>

                <!-- 3D Tilted Hardware Rig (Exact match to reference angle) -->
                <div class="tv-tilted-rig">

                    <!-- TV Hardware Frame & Display Screen -->
                    <div class="relative tv-screen-chassis-3d p-2 sm:p-2.5">
                        
                        <!-- Inside TV Screen Display (16:9 Aspect Ratio) -->
                        <div class="relative rounded-xl overflow-hidden aspect-[16/9] bg-slate-950 border border-white/10 group select-none shadow-inner">
                            
                            <!-- User's Exact High-Res TV Screen Interface Image -->
                            <img src="{{ asset('images/landing/tvscreen.png') }}" 
                                 alt="Taj Hotel Smart TV OS Screen" 
                                 class="w-full h-full object-cover filter brightness-[1.02] contrast-[1.03] group-hover:scale-[1.015] transition-transform duration-700">
                            
                            <!-- Realistic Glass Screen Reflection Overlays -->
                            <div class="absolute inset-0 pointer-events-none bg-gradient-to-tr from-transparent via-white/[0.03] to-white/[0.12]"></div>
                            <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_20px_rgba(0,0,0,0.6)]"></div>
                        </div>

                        <!-- Sleek TV Base Stand resting on media console -->
                        <div class="w-24 h-2 bg-gradient-to-b from-[#3a404d] to-[#12151b] mx-auto mt-1 rounded-b-xs shadow-md"></div>
                    </div>

                    <!-- Luxury Dark Walnut TV Credenza Cabinet with Warm Underglow -->
                    <div class="relative mt-2 mx-auto w-full credenza-console-3d rounded-md p-2.5 sm:p-3 shadow-2xl">
                        <!-- Warm Amber LED Strip Light -->
                        <div class="h-[2px] w-full bg-gradient-to-r from-[#E5A853]/20 via-[#E5A853] to-[#E5A853]/20 shadow-[0_2px_14px_rgba(229,168,83,0.6)]"></div>
                        <!-- Credenza Drawers / Wood Grain Front -->
                        <div class="flex items-center justify-between pt-2 px-4 text-[10px] text-stone-500">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-1 bg-[#3a291e] rounded-full"></div>
                                <div class="w-12 h-1 bg-[#3a291e] rounded-full"></div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-1 bg-[#3a291e] rounded-full"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="h-4"></div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. FOUR FEATURES STRIP (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section id="features" class="relative z-20 bg-white text-stone-900 border-y border-stone-200 py-6 px-6 lg:px-12 shadow-xs">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-stone-200">
            
            <!-- Item 1: Customizable UI -->
            <div class="flex items-center space-x-3.5 py-3 sm:py-1 px-3 lg:px-6 group">
                <div class="w-12 h-12 rounded-full bg-[#FEF3E2] flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <!-- Crossed magic wand / tools with sparkles -->
                    <svg class="w-6 h-6 text-[#C08237]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m14 10 7.5-7.5"/>
                        <path d="m3 21 8.5-8.5"/>
                        <circle cx="16.5" cy="4.5" r="0.5" fill="currentColor"/>
                        <circle cx="4.5" cy="16.5" r="0.5" fill="currentColor"/>
                        <path d="m9 3 1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2z"/>
                        <path d="m17 13 1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-stone-900 leading-snug">Customizable UI</h3>
                    <p class="text-xs text-stone-500 font-normal leading-tight mt-0.5">Branded experience for your hotel</p>
                </div>
            </div>

            <!-- Item 2: Multi-Language -->
            <div class="flex items-center space-x-3.5 py-3 sm:py-1 px-3 lg:px-6 group">
                <div class="w-12 h-12 rounded-full bg-[#FEF3E2] flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <!-- Overlapping speech bubbles with quote lines -->
                    <svg class="w-6 h-6 text-[#C08237]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 9a2 2 0 0 1-2 2H6l-3 3V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v4z"/>
                        <path d="M18 9h1a2 2 0 0 1 2 2v9l-3-3h-6a2 2 0 0 1-2-2v-1"/>
                        <path d="M6 7h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-stone-900 leading-snug">Multi-Language</h3>
                    <p class="text-xs text-stone-500 font-normal leading-tight mt-0.5">Global guest support</p>
                </div>
            </div>

            <!-- Item 3: Hotel Information -->
            <div class="flex items-center space-x-3.5 py-3 sm:py-1 px-3 lg:px-6 group">
                <div class="w-12 h-12 rounded-full bg-[#FEF3E2] flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <!-- Classical hotel facade building with pediment & columns -->
                    <svg class="w-6 h-6 text-[#C08237]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                    <h3 class="font-bold text-sm text-stone-900 leading-snug">Hotel Information</h3>
                    <p class="text-xs text-stone-500 font-normal leading-tight mt-0.5">Showcase amenities and services</p>
                </div>
            </div>

            <!-- Item 4: Secure & Reliable -->
            <div class="flex items-center space-x-3.5 py-3 sm:py-1 px-3 lg:px-6 group">
                <div class="w-12 h-12 rounded-full bg-[#FEF3E2] flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <!-- Shield outline -->
                    <svg class="w-6 h-6 text-[#C08237]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-stone-900 leading-snug">Secure & Reliable</h3>
                    <p class="text-xs text-stone-500 font-normal leading-tight mt-0.5">Built for performance and guest privacy.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. ABOUT US SECTION (HAND HOLDING REMOTE POINTED AT TV) -->
    <!-- ========================================================================= -->
    <section id="about" class="py-20 lg:py-24 px-6 lg:px-16 bg-white text-slate-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left: Hotel Suite with Remote Control in Hand (Exact Visual Match) -->
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl border border-stone-200">
                    <img src="https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1200&q=85" 
                         alt="Hotel Guest Using Remote with PAX TV" 
                         class="w-full h-[380px] sm:h-[440px] object-cover">
                    
                    <!-- Bottom Remote Badge matching screenshot -->
                    <div class="absolute bottom-5 left-5 right-5 p-3.5 rounded-xl bg-slate-950/85 backdrop-blur-md border border-white/10 text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-[#C5A880] text-slate-950 flex items-center justify-center">
                                <i class="fa-solid fa-remote-control text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-200">Seamless In-Room Experience</span>
                        </div>
                        <span class="text-[#C5A880] text-xs font-bold font-serif-lux uppercase tracking-widest">PAX TV</span>
                    </div>
                </div>
            </div>

            <!-- Right: About Us Text Content -->
            <div class="lg:col-span-6 space-y-5">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    ABOUT US
                </div>

                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900 leading-[1.2]">
                    Enhancing Hospitality<br>
                    Through Smart Technology
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    We provide advanced Hotel TV solutions that combine entertainment, information and hospitality services into a single, easy-to-use application, designed to create memorable guest experiences.
                </p>

                <div class="pt-2">
                    <button onclick="openRegisterModal()" class="px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer">
                        <span>Learn More</span>
                        <span class="text-xs font-bold font-mono">›</span>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. MULTI-LANGUAGE SUPPORT (DARK SECTION WITH LANGUAGE MODAL SCREEN) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 px-6 lg:px-16 bg-[#0B0E14] text-white border-t border-white/10 relative overflow-hidden">
        
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left: Description and 3 Rows of Language Pills -->
            <div class="lg:col-span-6 space-y-6">
                <div class="text-[#C5A880] font-bold text-xs uppercase tracking-[0.25em]">
                    GLOBAL GUEST EXPERIENCE
                </div>

                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-white leading-tight">
                    Multi-Language Support
                </h2>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-lg">
                    Cater to international guests with multiple language options and easy navigation.
                </p>

                <!-- Exact 3 Rows of Language Pills -->
                <div class="space-y-2.5 pt-2 max-w-lg">
                    <!-- Row 1 -->
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-1.5 rounded-lg btn-gold-pill text-xs font-bold shadow-xs">English</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">हिंदी</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">मराठी</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">ગુજરાતી</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">தமிழ்</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">తెలుగు</span>
                    </div>

                    <!-- Row 2 -->
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">संस्कृत</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">বাংলা</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">Français</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">Deutsch</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">Español</span>
                    </div>

                    <!-- Row 3 -->
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">Português</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">中文</span>
                        <span class="px-4 py-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:border-[#C5A880] transition-colors cursor-pointer">عربي</span>
                    </div>
                </div>

            </div>

            <!-- Right: TV Displaying Exact Language Selection Dialog -->
            <div class="lg:col-span-6">
                <div class="tv-screen-chassis p-2.5 sm:p-3 rounded-2xl sm:rounded-3xl shadow-2xl">
                    <div class="rounded-xl overflow-hidden aspect-[16/10] bg-slate-950 border border-white/10 p-5 flex flex-col justify-center relative">
                        
                        <div class="absolute inset-0 bg-cover bg-center filter brightness-[0.25]" 
                             style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1000&q=80');"></div>

                        <!-- Language Modal Card on TV Screen -->
                        <div class="relative z-10 bg-slate-950/90 backdrop-blur-xl border border-white/15 rounded-xl p-4 sm:p-5 max-w-xs mx-auto w-full space-y-3 shadow-2xl">
                            
                            <div class="flex items-center space-x-2 border-b border-white/10 pb-2.5">
                                <i class="fa-solid fa-globe text-[#C5A880] text-xs"></i>
                                <div>
                                    <div class="text-[11px] font-bold text-white uppercase tracking-wider">SELECT LANGUAGE</div>
                                    <div class="text-[9px] text-slate-400">Choose your preferred language</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded-lg btn-gold-pill text-xs font-bold flex items-center justify-between shadow-xs">
                                    <span>English</span>
                                    <i class="fa-solid fa-check text-[9px]"></i>
                                </div>
                                <div class="p-2 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium">
                                    हिंदी
                                </div>
                                <div class="p-2 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium">
                                    मराठी
                                </div>
                                <div class="p-2 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium">
                                    ગુજરાતી
                                </div>
                                <div class="p-2 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium">
                                    বাংলা
                                </div>
                                <div class="p-2 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-medium">
                                    தமிழ்
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. HOTEL INFORMATION & SERVICES (WARM CREAM SECTION) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF8F5] text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left: Description and 6 Tan Outlined Cards (Row 1: 4 cards, Row 2: 2 cards) -->
            <div class="lg:col-span-6 space-y-6">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    EVERYTHING YOUR GUESTS NEED
                </div>

                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900 leading-tight">
                    Hotel Information & Services
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Showcase hotel amenities, dining options, facilities and local attractions directly on the TV.
                </p>

                <!-- Service Cards Grid (Matching Exact Screenshot Layout) -->
                <div class="space-y-3 pt-2">
                    
                    <!-- Row 1: 4 Items -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-hotel text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">Hotel Information</span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">Dining & Restaurants</span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-map-location-dot text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">Local Attractions</span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-bell-concierge text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">In-Room Services</span>
                        </div>
                    </div>

                    <!-- Row 2: 2 Items -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-plane-departure text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">Flight Information</span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-[#E5DAC8] flex flex-col items-center text-center space-y-2 shadow-xs">
                            <div class="w-9 h-9 rounded-lg icon-box-gold flex items-center justify-center">
                                <i class="fa-solid fa-compass text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 leading-tight">City Guide</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Smart TV Displaying Hotel Information Grid (Exact Match) -->
            <div class="lg:col-span-6">
                <div class="tv-screen-chassis p-2.5 sm:p-3 rounded-2xl sm:rounded-3xl shadow-2xl">
                    <div class="rounded-xl overflow-hidden aspect-[16/10] bg-slate-950 border border-white/10 p-5 flex flex-col justify-between text-white">
                        
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="font-serif-lux font-bold text-xs text-[#E8DCCB]">Hotel Information</span>
                            <span class="text-[9px] text-slate-400 font-mono">Room 1111</span>
                        </div>

                        <!-- TV Screen 6 Grid Apps -->
                        <div class="grid grid-cols-3 gap-2.5 my-auto">
                            <div class="p-2.5 rounded-lg bg-white/10 border border-[#C5A880] flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-hotel text-[#C5A880] text-xs"></i>
                                <span class="text-[9px] font-bold">About Hotel</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-utensils text-slate-300 text-xs"></i>
                                <span class="text-[9px] font-semibold">Dining</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-spa text-slate-300 text-xs"></i>
                                <span class="text-[9px] font-semibold">Facilities</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-map-pin text-slate-300 text-xs"></i>
                                <span class="text-[9px] font-semibold">Local Attractions</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-bell-concierge text-slate-300 text-xs"></i>
                                <span class="text-[9px] font-semibold">Services</span>
                            </div>
                            <div class="p-2.5 rounded-lg bg-white/5 border border-white/10 flex flex-col items-center text-center space-y-1">
                                <i class="fa-solid fa-plane text-slate-300 text-xs"></i>
                                <span class="text-[9px] font-semibold">Flights</span>
                            </div>
                        </div>

                        <div class="text-[9px] text-slate-400 flex items-center justify-between">
                            <span>PAX TV Guest Concierge</span>
                            <span class="text-[#C5A880]">Select with remote</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. LIVE TV & ENTERTAINMENT (EXACT 8 BROADCAST CHANNELS MATCH) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 px-6 lg:px-16 bg-white text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left: Description and 5 Horizontal Feature Icons -->
            <div class="lg:col-span-6 space-y-6">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    NON-STOP ENTERTAINMENT
                </div>

                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900 leading-tight">
                    Live TV & Entertainment
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Deliver seamless live TV, movies and guest entertainment with an intuitive interface.
                </p>

                <!-- Exact 5 Horizontal Feature Icons (Screenshot Match) -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-2">
                    
                    <div class="p-2.5 rounded-xl bg-[#FAF8F5] border border-stone-200 flex flex-col items-center text-center space-y-1.5 shadow-2xs">
                        <i class="fa-solid fa-tv text-[#A07F54] text-sm"></i>
                        <span class="text-[10px] font-bold text-slate-800 leading-tight">Live TV Channels</span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#FAF8F5] border border-stone-200 flex flex-col items-center text-center space-y-1.5 shadow-2xs">
                        <i class="fa-solid fa-film text-[#A07F54] text-sm"></i>
                        <span class="text-[10px] font-bold text-slate-800 leading-tight">On-Demand Movies</span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#FAF8F5] border border-stone-200 flex flex-col items-center text-center space-y-1.5 shadow-2xs">
                        <i class="fa-solid fa-globe text-[#A07F54] text-sm"></i>
                        <span class="text-[10px] font-bold text-slate-800 leading-tight">Web Applications</span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#FAF8F5] border border-stone-200 flex flex-col items-center text-center space-y-1.5 shadow-2xs">
                        <i class="fa-solid fa-mobile-screen text-[#A07F54] text-sm"></i>
                        <span class="text-[10px] font-bold text-slate-800 leading-tight">Screen Cast</span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-[#FAF8F5] border border-stone-200 flex flex-col items-center text-center space-y-1.5 shadow-2xs col-span-2 sm:col-span-1">
                        <i class="fa-regular fa-star text-[#A07F54] text-sm"></i>
                        <span class="text-[10px] font-bold text-slate-800 leading-tight">Personalised Recommendations</span>
                    </div>

                </div>
            </div>

            <!-- Right: Smart TV Displaying Exact 8 Channel Cards -->
            <div class="lg:col-span-6">
                <div class="tv-screen-chassis p-2.5 sm:p-3 rounded-2xl sm:rounded-3xl shadow-2xl">
                    <div class="rounded-xl overflow-hidden aspect-[16/10] bg-slate-950 border border-white/10 p-5 flex flex-col justify-between text-white">
                        
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="font-serif-lux font-bold text-xs text-[#E8DCCB]">Live TV</span>
                            <span class="text-[9px] text-slate-400">Broadcast Channels</span>
                        </div>

                        <!-- 8 Channel Logos (BBC, ESPN, Nat Geo, StarPlus, HBO, SONY, Zee TV, Discovery) -->
                        <div class="grid grid-cols-4 gap-2 my-auto">
                            
                            <!-- 1. BBC News -->
                            <div class="aspect-[16/10] rounded-lg bg-[#BB1919] border border-red-500/50 flex flex-col items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-extrabold text-[11px] text-white tracking-widest font-mono">BBC</span>
                                <span class="text-[7px] font-bold text-white tracking-widest uppercase">NEWS</span>
                            </div>

                            <!-- 2. ESPN -->
                            <div class="aspect-[16/10] rounded-lg bg-white border border-slate-200 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-extrabold text-[12px] italic text-[#CC0000] tracking-wider font-sans">ESPN</span>
                            </div>

                            <!-- 3. National Geographic -->
                            <div class="aspect-[16/10] rounded-lg bg-black border border-amber-500/60 flex items-center justify-center p-1 text-center shadow-xs">
                                <div class="border border-amber-400 px-1 py-0.5 text-[7px] font-bold text-white uppercase leading-none font-sans">
                                    NATIONAL<br>GEOGRAPHIC
                                </div>
                            </div>

                            <!-- 4. StarPlus -->
                            <div class="aspect-[16/10] rounded-lg bg-[#0C2340] border border-blue-400/40 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-bold text-[10px] text-white font-sans flex items-center space-x-0.5">
                                    <span class="text-rose-500">★</span><span>StarPlus</span>
                                </span>
                            </div>

                            <!-- 5. HBO -->
                            <div class="aspect-[16/10] rounded-lg bg-[#141414] border border-slate-700 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-black text-[12px] text-white tracking-widest font-serif">HBO</span>
                            </div>

                            <!-- 6. SONY -->
                            <div class="aspect-[16/10] rounded-lg bg-[#1B1B1B] border border-slate-700 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-bold text-[10px] text-white tracking-wider font-sans">SONY</span>
                            </div>

                            <!-- 7. Zee TV -->
                            <div class="aspect-[16/10] rounded-lg bg-[#110626] border border-purple-500/40 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-bold text-[10px] text-amber-400 font-sans">ZEE TV</span>
                            </div>

                            <!-- 8. Discovery -->
                            <div class="aspect-[16/10] rounded-lg bg-[#0B2545] border border-cyan-500/40 flex items-center justify-center p-1.5 text-center shadow-xs">
                                <span class="font-bold text-[9px] text-cyan-300 font-sans">Discovery</span>
                            </div>

                        </div>

                        <div class="text-[9px] text-slate-400 flex items-center justify-between">
                            <span>PAX TV Stream Engine</span>
                            <span class="text-[#C5A880]">250+ HD Channels</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. OUR SOLUTIONS (3 STACKED CARDS LEFT + LUXURY SUITE BEDROOM RIGHT) -->
    <!-- ========================================================================= -->
    <section id="solutions" class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF8F5] text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto space-y-10">
            
            <div class="max-w-2xl space-y-2">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    TAILORED FOR YOUR HOTEL
                </div>
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900">
                    Our Solutions
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-normal">
                    A complete in-room entertainment and guest engagement solution designed for modern hospitality needs.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: 3 Stacked Cards (Matching Screenshot) -->
                <div class="lg:col-span-6 space-y-3.5">
                    
                    <!-- Card 1 -->
                    <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] hover:border-[#C5A880] transition-all shadow-2xs space-y-2">
                        <div class="w-9 h-9 rounded-xl icon-box-gold flex items-center justify-center">
                            <i class="fa-solid fa-tv text-sm"></i>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900">Hotel TV Application</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Custom branded interface with all essential features</p>
                        <a href="#plans" class="inline-flex items-center text-xs font-bold text-[#A07F54] hover:text-[#7A5A1E] pt-1">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono ml-1">›</span>
                        </a>
                    </div>

                    <!-- Card 2 -->
                    <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] hover:border-[#C5A880] transition-all shadow-2xs space-y-2">
                        <div class="w-9 h-9 rounded-xl icon-box-gold flex items-center justify-center">
                            <i class="fa-solid fa-gear text-sm"></i>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900">System Integration</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Integrate with hotel PMS, services and third-party apps</p>
                        <a href="#plans" class="inline-flex items-center text-xs font-bold text-[#A07F54] hover:text-[#7A5A1E] pt-1">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono ml-1">›</span>
                        </a>
                    </div>

                    <!-- Card 3 -->
                    <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] hover:border-[#C5A880] transition-all shadow-2xs space-y-2">
                        <div class="w-9 h-9 rounded-xl icon-box-gold flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900">Ongoing Support</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Dedicated support and regular updates for seamless operation</p>
                        <a href="#plans" class="inline-flex items-center text-xs font-bold text-[#A07F54] hover:text-[#7A5A1E] pt-1">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono ml-1">›</span>
                        </a>
                    </div>

                </div>

                <!-- Right: Luxury Hotel Bedroom Suite Photo (Warm Ambient Headboard) -->
                <div class="lg:col-span-6 h-full">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl border border-stone-200 h-[440px]">
                        <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=85" 
                             alt="Luxury Bedroom with Backlit Headboard" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-5 left-5 text-white">
                            <span class="text-[#E8DCCB] text-xs font-serif-lux font-bold uppercase tracking-wider">Premium Room Suites</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. GALLERY (REAL IMPLEMENTATIONS - 1 LARGE LEFT + 4 SMALL RIGHT) -->
    <!-- ========================================================================= -->
    <section id="gallery" class="py-20 lg:py-24 px-6 lg:px-16 bg-white text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto space-y-10">
            
            <div class="max-w-2xl space-y-2">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    REAL IMPLEMENTATIONS
                </div>
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900">
                    Gallery
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-normal">
                    See how our Hotel TV solution enhances guest experiences in premium properties worldwide.
                </p>
            </div>

            <!-- Exact Mosaic Gallery Grid Matching Screenshot -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Large Image on Left -->
                <div class="lg:col-span-6 relative rounded-2xl overflow-hidden shadow-md border border-stone-200 min-h-[320px] group">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=85" 
                         alt="Ocean Suite Implementation" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <span class="absolute bottom-4 left-4 text-xs font-bold text-white font-serif-lux">Oceanfront Penthouse</span>
                </div>

                <!-- 4 Smaller Rectangular Images on Right (2x2 Grid) -->
                <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                    
                    <div class="relative rounded-xl overflow-hidden shadow-xs border border-stone-200 h-38 group">
                        <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80" 
                             alt="Luxury Room TV" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-slate-950/30"></div>
                        <span class="absolute bottom-2.5 left-2.5 text-[10px] font-bold text-white">Presidential Suite</span>
                    </div>

                    <div class="relative rounded-xl overflow-hidden shadow-xs border border-stone-200 h-38 group">
                        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=600&q=80" 
                             alt="Penthouse Living Room" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-slate-950/30"></div>
                        <span class="absolute bottom-2.5 left-2.5 text-[10px] font-bold text-white">Deluxe King</span>
                    </div>

                    <div class="relative rounded-xl overflow-hidden shadow-xs border border-stone-200 h-38 group">
                        <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80" 
                             alt="Deluxe Room TV" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-slate-950/30"></div>
                        <span class="absolute bottom-2.5 left-2.5 text-[10px] font-bold text-white">Boutique Suite</span>
                    </div>

                    <div class="relative rounded-xl overflow-hidden shadow-xs border border-stone-200 h-38 group">
                        <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80" 
                             alt="Executive Lounge" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-slate-950/30"></div>
                        <span class="absolute bottom-2.5 left-2.5 text-[10px] font-bold text-white">Executive Lounge</span>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. PRICING & PLANS SECTION (DYNAMIC DATABASE PLANS PRESERVED) -->
    <!-- ========================================================================= -->
    <section id="plans" class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF8F5] text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto space-y-14 text-center">
            
            <div class="space-y-2 max-w-2xl mx-auto">
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    FLEXIBLE SUBSCRIPTION
                </div>
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-slate-900">
                    Transparent Pricing for Properties of Any Scale
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-normal">
                    Choose a plan tailored to your room count. Instant licensing keys and central cloud control.
                </p>
            </div>

            <!-- Dynamic Plans Loop from Database -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                @foreach($plans as $plan)
                    <div class="bg-white border rounded-2xl p-7 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between space-y-6 relative {{ $plan->room_count === 50 ? 'border-2 border-[#C5A880] shadow-sm' : 'border-stone-200' }}">
                        
                        @if($plan->room_count === 50)
                            <span class="absolute top-5 right-5 px-3 py-1 rounded-full btn-gold-pill text-slate-950 font-bold text-[10px] uppercase tracking-wider">
                                Most Popular
                            </span>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-serif-lux text-xl font-bold text-slate-900">{{ $plan->name }}</h3>
                                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-[#FAF6EE] text-[#A07F54] font-bold text-[10px] uppercase tracking-wider">
                                    Up to {{ $plan->room_count }} Rooms
                                </span>
                            </div>

                            <div class="text-3xl font-extrabold text-slate-900 font-serif-lux">
                                ₹{{ number_format($plan->price, 0) }}<span class="text-xs text-slate-400 font-normal ml-1">/mo</span>
                            </div>

                            <ul class="space-y-2.5 text-xs text-slate-600 font-medium">
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-[#C5A880]"></i>
                                    <span>Authorize up to {{ $plan->room_count }} TVs</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-[#C5A880]"></i>
                                    <span>Instant 16-Digit License Key</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-[#C5A880]"></i>
                                    <span>Custom Hotel Logo & Branding</span>
                                </li>
                                @if($plan->room_count >= 50)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-[#C5A880]"></i>
                                        <span>Cloud Multi-Theme Support</span>
                                    </li>
                                @endif
                                @if($plan->room_count >= 100)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-[#C5A880]"></i>
                                        <span>Dedicated Account Manager</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <button onclick="openRegisterModalWithPlan({{ $plan->id }}, {{ $plan->room_count }})" 
                                class="w-full py-3 px-4 rounded-full font-bold text-xs tracking-wider uppercase transition-all {{ $plan->room_count === 50 ? 'btn-gold-pill shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-800' }}">
                            Select {{ $plan->name }}
                        </button>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 10. REQUEST A DEMO TODAY (DUSK RESORT ILLUMINATED BANNER) -->
    <!-- ========================================================================= -->
    <section class="relative py-24 lg:py-28 px-6 lg:px-16 text-white overflow-hidden border-t border-white/10">
        
        <!-- Dusk Resort Hotel Background with Pool & Palms (Exact Screenshot Match) -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85" 
                 alt="Luxury Hotel at Dusk" 
                 class="w-full h-full object-cover object-center filter brightness-[0.38] contrast-[1.1]">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/70 to-slate-950/85"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            
            <div class="space-y-3.5 max-w-2xl text-left">
                <div class="text-[#C5A880] font-bold text-[11px] uppercase tracking-[0.25em]">
                    READY TO TRANSFORM YOUR GUEST EXPERIENCE?
                </div>

                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-white leading-tight">
                    Request a Demo Today
                </h2>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal max-w-lg">
                    Discover how our Hotel TV solution can add value to your property and delight your guests.
                </p>

                <!-- Dual Action Buttons -->
                <div class="flex items-center space-x-4 pt-2">
                    <button onclick="openRegisterModal()" class="px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer">
                        <span>Request Demo</span>
                        <span class="text-xs font-bold font-mono">›</span>
                    </button>

                    <a href="{{ route('contact-us') }}" class="px-7 py-3 btn-glass-pill text-xs tracking-wider">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Minimalist Crest Seal on Right -->
            <div class="hidden lg:flex flex-col items-center justify-center p-8 rounded-full border border-white/10 bg-white/5 backdrop-blur-md w-44 h-44 text-center space-y-2">
                <svg class="w-8 h-8 fill-current text-[#C5A880]" viewBox="0 0 24 24">
                    <path d="M12 1L14.4 7.2L20.8 5.6L18 11.6L23 15.2L16.8 17.2L16 23.6L12 18.8L8 23.6L7.2 17.2L1 15.2L6 11.6L3.2 5.6L9.6 7.2L12 1Z"/>
                </svg>
                <span class="font-serif-lux font-bold text-sm tracking-widest text-[#E8DCCB]">PAX TV</span>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 11. LUXURY DARK FOOTER (100% REPLICATED FROM REFERENCE ARTBOARD) -->
    <!-- ========================================================================= -->
    <footer class="bg-[#0A0D14] text-white border-t border-white/10 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 space-y-12">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Column 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-7 h-7 fill-current text-[#C5A880]" viewBox="0 0 24 24">
                            <path d="M12 1L14.4 7.2L20.8 5.6L18 11.6L23 15.2L16.8 17.2L16 23.6L12 18.8L8 23.6L7.2 17.2L1 15.2L6 11.6L3.2 5.6L9.6 7.2L12 1Z"/>
                        </svg>
                        <span class="font-serif-lux font-bold text-xl tracking-[0.22em] text-white">
                            PAX<span class="text-[#C5A880]">TV</span>
                        </span>
                    </div>

                    <p class="text-xs text-slate-400 leading-relaxed font-normal">
                        Premium Hotel TV Solution.<br>
                        For a Smarter Stay Experience.
                    </p>

                    <!-- Social Icons -->
                    <div class="flex items-center space-x-2.5 pt-1">
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-[#C5A880] hover:text-slate-950 border border-white/10 flex items-center justify-center text-slate-400 transition-all">
                            <i class="fa-brands fa-linkedin-in text-xs"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-[#C5A880] hover:text-slate-950 border border-white/10 flex items-center justify-center text-slate-400 transition-all">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-[#C5A880] hover:text-slate-950 border border-white/10 flex items-center justify-center text-slate-400 transition-all">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links (Exact Match) -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="#home" class="hover:text-[#C5A880] transition-colors">Home</a></li>
                        <li><a href="#features" class="hover:text-[#C5A880] transition-colors">Features</a></li>
                        <li><a href="#solutions" class="hover:text-[#C5A880] transition-colors">Solutions</a></li>
                        <li><a href="#gallery" class="hover:text-[#C5A880] transition-colors">Gallery</a></li>
                        <li><a href="#about" class="hover:text-[#C5A880] transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-[#C5A880] transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Our Solutions (Exact Match) -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Our Solutions</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="#solutions" class="hover:text-[#C5A880] transition-colors">Hotel TV Application</a></li>
                        <li><a href="#solutions" class="hover:text-[#C5A880] transition-colors">System Integration</a></li>
                        <li><a href="#features" class="hover:text-[#C5A880] transition-colors">Multi-Language Support</a></li>
                        <li><a href="#features" class="hover:text-[#C5A880] transition-colors">Hotel Information</a></li>
                        <li><a href="#features" class="hover:text-[#C5A880] transition-colors">Live TV & Entertainment</a></li>
                        <li><a href="#features" class="hover:text-[#C5A880] transition-colors">Guest Services</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Us (Exact Match) -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Contact Us</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-location-dot text-[#C5A880] text-xs"></i>
                            <span>Mumbai, India</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-phone text-[#C5A880] text-xs"></i>
                            <a href="tel:+919876543210" class="hover:text-white transition-colors">+91 98765 43210</a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-envelope text-[#C5A880] text-xs"></i>
                            <a href="mailto:info@paxtvnetwork.com" class="hover:text-white transition-colors">info@paxtvnetwork.com</a>
                        </li>
                    </ul>

                    <div class="pt-2">
                        <button onclick="openRegisterModal()" class="w-full py-2.5 px-4 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-1 cursor-pointer">
                            <span>Request Demo</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links -->
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-[#C5A880] transition-colors">Privacy Policy</a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="hover:text-[#C5A880] transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

</div>

<!-- ========================================================================= -->
<!-- REGISTRATION / REQUEST DEMO MODAL OVERLAY (PRESERVED LOGIC) -->
<!-- ========================================================================= -->
<div id="registerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white text-slate-900 border border-stone-200 rounded-3xl w-full max-w-xl p-6 sm:p-8 space-y-6 shadow-2xl my-8">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#C5A880] text-slate-950 flex items-center justify-center">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-serif-lux">PAX TV Registration</h3>
            </div>
            <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold cursor-pointer">&times;</button>
        </div>

        <form id="registerForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div id="registerError" class="hidden p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold"></div>

            <!-- Owner Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-[#A07F54] uppercase tracking-wider border-b border-slate-100 pb-2">Personal Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Owner Name</label>
                        <input type="text" name="owner_name" required placeholder="e.g. John Doe" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" required placeholder="e.g. 9876543210" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Email Address</label>
                        <input type="email" name="email" required placeholder="username@example.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Password</label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                </div>
            </div>

            <!-- Hotel Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-[#A07F54] uppercase tracking-wider border-b border-slate-100 pb-2">Hotel Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Name</label>
                        <input type="text" name="hotel_name" required placeholder="e.g. Grand Resort" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Location / City</label>
                        <input type="text" name="hotel_location" required placeholder="e.g. Mumbai, India" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FAF6EE] file:text-[#A07F54]">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FAF6EE] file:text-[#A07F54]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Total Room Count</label>
                    <input type="number" name="room_count" id="roomCountInput" min="1" required placeholder="e.g. 50" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#C5A880]">
                    
                    <div id="suggestedPlanBox" class="hidden p-4 rounded-2xl bg-[#FAF6EE] border border-[#E8DFC9] space-y-1 mt-2">
                        <span class="text-[10px] font-bold text-[#A07F54] uppercase tracking-wider block">Suggested Subscription Plan</span>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                            <span id="suggestedPlanName">-</span>
                            <span id="suggestedPlanPrice" class="text-[#A07F54] font-extrabold">-</span>
                        </div>
                        <input type="hidden" name="plan_id" id="suggestedPlanId">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRegisterModal()" class="px-6 py-2.5 rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold cursor-pointer">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-full btn-gold-pill text-xs shadow-md cursor-pointer">Pay & Complete Registration</button>
            </div>
        </form>
    </div>
</div>

<!-- Simulated Sandbox Payment Modal Overlay -->
<div id="paymentLoader" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-4 text-center text-white">
    <div class="w-12 h-12 border-4 border-white/20 border-t-[#C5A880] rounded-full animate-spin mb-4"></div>
    <h3 id="loaderTitle" class="text-xl font-bold font-serif-lux">Processing Order Request</h3>
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
</script>
@endsection
