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
                
                <!-- Brand Logo: Exact logo.png -->
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group cursor-pointer">
                    <img src="{{ asset('images/logo/logo.png') }}" 
                         alt="PAX TV" 
                         class="h-10 sm:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
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
                    <a href="#about" class="text-stone-200 hover:text-[#E5A853] transition-colors">About</a>
                    <a href="{{ route('contact-us') }}" class="text-stone-200 hover:text-[#E5A853] transition-colors">Contact</a>
                </nav>

                <!-- Right Action Buttons: Hotel Login + Request Demo > -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('hotel.login') }}" class="text-xs font-medium text-stone-200 hover:text-[#E5A853] flex items-center space-x-1.5 px-3.5 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#E5A853]/40 transition-all">
                        <i class="fa-solid fa-hotel text-[11px] text-[#E5A853]"></i>
                        <span>Hotel Login</span>
                    </a>
                    <button onclick="openRegisterModal()" class="px-5 sm:px-6 py-2.5 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer">
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
    <!-- 3. ABOUT US SECTION (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section id="about" class="relative overflow-hidden bg-[#FBFBFA] text-stone-900 border-b border-stone-200 min-h-[500px] lg:min-h-[580px] xl:min-h-[620px] flex items-center">
        
        <!-- Panoramic Suite Background: PAX TV on Wood Slats with Remote (Left), Clean Wall (Center), Lounge Chair (Right) -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/landing/about-suite-clean.jpg') }}" 
                 alt="PAX TV Hotel Solution Experience" 
                 class="w-full h-full object-cover object-left md:object-center">
        </div>

        <!-- Section Content Grid Positioned on the Clean Wall Space -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 w-full py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Space for TV, Backlight Wall & Hand holding Remote -->
                <div class="hidden lg:block lg:col-span-5 xl:col-span-5"></div>

                <!-- Center / Right: Exact Text & CTA with pristine contrast on clean wall -->
                <div class="lg:col-span-7 xl:col-span-6 space-y-6 text-left pl-0 lg:pl-6">
                    
                    <!-- Overline Tag -->
                    <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                        ABOUT US
                    </div>

                    <!-- Editorial Serif Headline (Playfair Display / Cinzel) -->
                    <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] xl:text-[3rem] font-medium tracking-tight text-stone-900 leading-[1.18]">
                        Enhancing Hospitality<br>
                        Through Smart Technology
                    </h2>

                    <!-- Paragraph Description -->
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-normal max-w-xl">
                        We provide advanced Hotel TV solutions that combine entertainment, information and hospitality services into a single, easy-to-use application, designed to create memorable guest experiences.
                    </p>

                    <!-- Primary Pill Button: Learn More > -->
                    <div class="pt-2">
                        <button onclick="openRegisterModal()" class="px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center space-x-2 cursor-pointer shadow-md hover:shadow-lg transition-shadow">
                            <span>Learn More</span>
                            <span class="text-sm font-bold font-mono">›</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 4. OUR SOLUTIONS SECTION (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section id="solutions" class="relative overflow-hidden bg-[#FAF9F6] text-stone-900 border-b border-stone-200 py-16 lg:py-24">
        
        <!-- Right Side Luxury Bedroom Suite Image with Soft Gradient Fade -->
        <div class="absolute top-0 right-0 bottom-0 w-full lg:w-[48%] xl:w-[46%] pointer-events-none hidden lg:block overflow-hidden z-0">
            <img src="{{ asset('images/landing/our-solutions-suite.jpg') }}" 
                 alt="Luxury Hotel Suite Experience" 
                 class="w-full h-full object-cover object-right">
            <!-- Smooth left-side gradient blend into section cream background -->
            <div class="absolute inset-y-0 left-0 w-36 xl:w-48 bg-gradient-to-r from-[#FAF9F6] via-[#FAF9F6]/85 to-transparent"></div>
            <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#FAF9F6] to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#FAF9F6] to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 w-full">
            
            <!-- Section Header (Left-aligned) -->
            <div class="max-w-xl mb-10 lg:mb-12">
                <!-- Overline Badge -->
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em] mb-2.5">
                    TAILORED FOR YOUR HOTEL
                </div>

                <!-- Editorial Headline -->
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.75rem] font-medium tracking-tight text-stone-900 leading-tight mb-3">
                    Our Solutions
                </h2>

                <!-- Subtitle / Intro Description -->
                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                    A complete in-room entertainment and guest engagement solution designed for modern hospitality needs.
                </p>
            </div>

            <!-- Solutions Cards Grid (3 Cards in a row, aligned with screenshot) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-5 xl:gap-6 max-w-full lg:max-w-[62%] xl:max-w-[60%]">
                
                <!-- Card 1: Hotel TV Application -->
                <div class="bg-white rounded-2xl p-6 border border-stone-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Icon Badge: Light warm peach with amber icon -->
                        <div class="w-12 h-12 rounded-xl bg-[#FDE8C7]/90 flex items-center justify-center text-[#B87A2B] mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" stroke-linejoin="round"/>
                                <path d="M8 21h8M12 17v4" stroke-linecap="round"/>
                                <path d="M7 8h4M7 11h2" stroke-linecap="round"/>
                                <circle cx="15.5" cy="9.5" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-base font-bold text-stone-900 tracking-tight leading-snug mb-1.5">
                            Hotel TV Application
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-stone-500 leading-relaxed font-normal mb-5">
                            Custom branded interface with all essential features
                        </p>
                    </div>

                    <!-- Button: Learn More › -->
                    <div>
                        <button onclick="openRegisterModal()" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-full bg-[#FDE8C7] hover:bg-[#FCD8A2] text-stone-900 font-semibold text-xs transition-colors cursor-pointer shadow-xs">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </button>
                    </div>
                </div>

                <!-- Card 2: System Integration -->
                <div class="bg-white rounded-2xl p-6 border border-stone-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Icon Badge: Gear / Integration Network Hub -->
                        <div class="w-12 h-12 rounded-xl bg-[#FDE8C7]/90 flex items-center justify-center text-[#B87A2B] mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-base font-bold text-stone-900 tracking-tight leading-snug mb-1.5">
                            System Integration
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-stone-500 leading-relaxed font-normal mb-5">
                            Integrate with hotel PMS, services and third-party apps
                        </p>
                    </div>

                    <!-- Button: Learn More › -->
                    <div>
                        <button onclick="openRegisterModal()" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-full bg-[#FDE8C7] hover:bg-[#FCD8A2] text-stone-900 font-semibold text-xs transition-colors cursor-pointer shadow-xs">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Ongoing Support -->
                <div class="bg-white rounded-2xl p-6 border border-stone-100/90 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Icon Badge: Ongoing Support / Circular Clock Refresh -->
                        <div class="w-12 h-12 rounded-xl bg-[#FDE8C7]/90 flex items-center justify-center text-[#B87A2B] mb-5 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.663 3.04097 17.0827 4.74751 18.8732" stroke-linecap="round"/>
                                <polyline points="2 15 5 19 9 16" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-base font-bold text-stone-900 tracking-tight leading-snug mb-1.5">
                            Ongoing Support
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-stone-500 leading-relaxed font-normal mb-5">
                            Dedicated support and regular updates for seamless operation
                        </p>
                    </div>

                    <!-- Button: Learn More › -->
                    <div>
                        <button onclick="openRegisterModal()" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-full bg-[#FDE8C7] hover:bg-[#FCD8A2] text-stone-900 font-semibold text-xs transition-colors cursor-pointer shadow-xs">
                            <span>Learn More</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 5. MULTI-LANGUAGE SUPPORT (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section id="features" class="relative overflow-hidden bg-[#0A0D14] text-white border-t border-white/10 min-h-[540px] lg:min-h-[620px] flex items-center">
        
        <!-- Panoramic Suite Background with TV Displaying Language Dialog, Lamp & Plant (Right) -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/multi-language-bg.jpg') }}" 
                 alt="Hotel TV Multi-Language Interface" 
                 class="w-full h-full object-cover object-right md:object-center filter brightness-[1.0] contrast-[1.03]">
            <!-- Soft Charcoal Gradient on Left for Flawless Text & Pill Readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#080B10] via-[#080B10]/80 to-transparent hidden md:block w-full lg:w-[65%]"></div>
            <div class="absolute inset-0 bg-[#080B10]/75 md:hidden"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 w-full py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: Description and 3 Distinct Rows of Translucent Language Pills -->
                <div class="lg:col-span-7 xl:col-span-6 space-y-6 text-left">
                    
                    <!-- Overline Badge -->
                    <div class="text-[#E5A853] font-bold text-xs uppercase tracking-[0.25em]">
                        GLOBAL GUEST EXPERIENCE
                    </div>

                    <!-- Editorial Serif Headline -->
                    <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.85rem] font-medium tracking-tight text-white leading-tight">
                        Multi-Language Support
                    </h2>

                    <!-- Paragraph Subtitle -->
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal max-w-md">
                        Cater to international guests with multiple language options and easy navigation.
                    </p>

                    <!-- 3 Rows of Translucent Glass Language Pills (Clickable & Interactive) -->
                    <div class="space-y-3 pt-2 max-w-lg" id="landingLanguageContainer">
                        
                        <!-- Row 1 -->
                        <div class="flex flex-wrap gap-2.5">
                            <!-- Active English Pill: Warm Golden Yellow -->
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-5 py-2 rounded-2xl bg-[#F5C368] text-stone-950 font-bold text-xs shadow-md border-transparent ring-2 ring-[#F5C368]/40 scale-105 cursor-pointer transition-all duration-200 active:scale-95">
                                English
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                हिंदी
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                मराठी
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                ગુજરાતી
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                தமிழ்
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                తెలుగు
                            </button>
                        </div>

                        <!-- Row 2 -->
                        <div class="flex flex-wrap gap-2.5">
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                ಕನ್ನಡ
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                বাংলা
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                മലയാളം
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Français
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Deutsch
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Español
                            </button>
                        </div>

                        <!-- Row 3 -->
                        <div class="flex flex-wrap gap-2.5">
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                Português
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                中文
                            </button>
                            <button type="button" onclick="selectLandingLanguage(this)" 
                                    class="lang-pill px-4 sm:px-5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-medium backdrop-blur-md cursor-pointer transition-all duration-200 active:scale-95">
                                عربي
                            </button>
                        </div>

                    </div>

                </div>

                <!-- Right Space: Let the TV with on-screen SELECT LANGUAGE dialog, bedside lamp and plant shine in full clarity -->
                <div class="hidden lg:block lg:col-span-5 xl:col-span-6"></div>

            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- 5. HOTEL INFORMATION & SERVICES (WARM CREAM SECTION) -->
    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <!-- 6. HOTEL INFORMATION & SERVICES (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF7F2] text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center">
            
            <!-- Left: Description and 6 Circular Peach Icon Badges (Row 1: 4, Row 2: 2) -->
            <div class="lg:col-span-6 space-y-6 text-left">
                <!-- Overline Tag -->
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    EVERYTHING YOUR GUESTS NEED
                </div>

                <!-- Editorial Headline -->
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.85rem] font-medium tracking-tight text-slate-900 leading-tight">
                    Hotel Information & Services
                </h2>

                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal max-w-lg">
                    Showcase hotel amenities, dining options, facilities and local attractions directly on the TV.
                </p>

                <!-- Icon Badges (Exact 4 + 2 Layout Matching Screenshot) -->
                <div class="space-y-6 pt-3">
                    
                    <!-- Row 1: 4 Items -->
                    <div class="grid grid-cols-4 gap-3 sm:gap-4 max-w-md">
                        
                        <!-- 1. Hotel Information -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M3 21h18M6 18V7l6-4 6 4v11M9 9h2M13 9h2M9 13h2M13 13h2M9 17h2M13 17h2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                Hotel<br>Information
                            </span>
                        </div>

                        <!-- 2. Dining & Restaurants -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M18 2v8a2 2 0 0 1-2 2h-1M15 12v10M8 2v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V2M5 9v13M5 2v5M7 2v5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                Dining &<br>Restaurants
                            </span>
                        </div>

                        <!-- 3. Local Attractions -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 2l3 7h6l-5 4 2 7-6-4-6 4 2-7-5-4h6z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                Local<br>Attractions
                            </span>
                        </div>

                        <!-- 4. In-Room Services -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 3v3M4 14a8 8 0 0 1 16 0H4zM2 18h20" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                In-Room<br>Services
                            </span>
                        </div>

                    </div>

                    <!-- Row 2: 2 Items (Left-aligned under first two) -->
                    <div class="grid grid-cols-4 gap-3 sm:gap-4 max-w-md">
                        
                        <!-- 5. Flight Information -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                Flight<br>Information
                            </span>
                        </div>

                        <!-- 6. City Guide -->
                        <div class="flex flex-col items-center text-center group cursor-pointer">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M9 18l6-6-6-6M1 6v14l6-3 6 3 6-3 4 2V4l-4-2-6 3-6-3-6 3z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 text-center leading-tight">
                                City Guide
                            </span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Right: Exact Luxury Hotel Suite Card with TV Displaying Hotel Information Interface -->
            <div class="lg:col-span-6">
                <div class="relative rounded-2xl lg:rounded-3xl overflow-hidden shadow-2xl border border-stone-200/90 group bg-stone-900">
                    <img src="{{ asset('images/landing/hotel-info-tv-suite.jpg') }}" 
                         alt="Hotel Information & Services Smart TV" 
                         class="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. LIVE TV & ENTERTAINMENT (EXACT 8 BROADCAST CHANNELS MATCH) -->
    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <!-- 7. LIVE TV & ENTERTAINMENT (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 px-6 lg:px-16 bg-white text-slate-900 border-t border-stone-200">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center">
            
            <!-- Left: Description and 5 Circular Peach Icon Badges in 1 Row -->
            <div class="lg:col-span-6 space-y-6 text-left">
                <!-- Overline Badge -->
                <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                    NON-STOP ENTERTAINMENT
                </div>

                <!-- Editorial Headline -->
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.85rem] font-medium tracking-tight text-slate-900 leading-tight">
                    Live TV & Entertainment
                </h2>

                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal max-w-lg">
                    Deliver seamless live TV, movies and guest entertainment with an intuitive interface.
                </p>

                <!-- 5 Circular Peach Badges in One Horizontal Row (Screenshot Match) -->
                <div class="grid grid-cols-5 gap-2 sm:gap-3 pt-3 max-w-xl">
                    
                    <!-- 1. Live TV Channels -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="15" rx="2" stroke-linejoin="round"/>
                                <polyline points="17 2 12 7 7 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-800 text-center leading-tight">
                            Live TV<br>Channels
                        </span>
                    </div>

                    <!-- 2. On-Demand Movies -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="2.18" stroke-linejoin="round"/>
                                <line x1="7" y1="2" x2="7" y2="22"/>
                                <line x1="17" y1="2" x2="17" y2="22"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <line x1="2" y1="7" x2="7" y2="7"/>
                                <line x1="2" y1="17" x2="7" y2="17"/>
                                <line x1="17" y1="17" x2="22" y2="17"/>
                                <line x1="17" y1="7" x2="22" y2="7"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-800 text-center leading-tight">
                            On-Demand<br>Movies
                        </span>
                    </div>

                    <!-- 3. Web Applications -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-800 text-center leading-tight">
                            Web<br>Applications
                        </span>
                    </div>

                    <!-- 4. Screen Cast -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M2 16.1A5 5 0 0 1 5.9 20M2 12.05A9 9 0 0 1 9.95 20M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6M2 20h.01" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-800 text-center leading-tight">
                            Screen<br>Cast
                        </span>
                    </div>

                    <!-- 5. Personalised Recommendations -->
                    <div class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-full bg-[#FDE8C7] flex items-center justify-center text-[#B87A2B] mb-2.5 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-800 text-center leading-tight">
                            Personalised<br>Recommendations
                        </span>
                    </div>

                </div>
            </div>

            <!-- Right: Exact Luxury Hotel Suite Card with TV Displaying 8 Live Broadcast Channels -->
            <div class="lg:col-span-6">
                <div class="relative rounded-2xl lg:rounded-3xl overflow-hidden shadow-2xl border border-stone-200/90 group bg-stone-900">
                    <img src="{{ asset('images/landing/live-tv-channels-suite.jpg') }}" 
                         alt="Live TV & Entertainment Broadcast Channels Smart TV" 
                         class="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. REQUEST A DEMO TODAY (DUSK RESORT ILLUMINATED BANNER - DEMO.SVG) -->
    <!-- ========================================================================= -->
    <section class="relative py-20 lg:py-24 xl:py-28 px-6 lg:px-16 text-white overflow-hidden border-t border-white/10">
        
        <!-- Dusk Resort Hotel Background (Exact Match to Reference using demo.svg) -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/demo.svg') }}" 
                 onerror="this.onerror=null;this.src='{{ asset('images/landing/demo.jpg') }}';" 
                 alt="Request a Demo - Luxury Beachfront Resort at Dusk" 
                 class="w-full h-full object-cover object-center">
            <!-- Smooth Gradient Overlay on left to guarantee crystal-clear text readability while keeping illuminated resort vivid on right -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/45 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            
            <div class="space-y-4 max-w-2xl text-left">
                <!-- Golden Overline Tag -->
                <div class="text-[#E5A853] font-bold text-xs uppercase tracking-[0.25em]">
                    READY TO TRANSFORM YOUR GUEST EXPERIENCE?
                </div>

                <!-- Editorial Headline -->
                <h2 class="font-serif-lux text-3xl sm:text-4xl lg:text-[2.85rem] font-medium tracking-tight text-white leading-tight">
                    Request a Demo Today
                </h2>

                <!-- Subtitle -->
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal max-w-lg">
                    Discover how our Hotel TV solution can add value to your property and delight your guests.
                </p>

                <!-- Dual Action Buttons (Exact Match to Reference Screenshot) -->
                <div class="flex items-center space-x-4 pt-2">
                    <button onclick="openRegisterModal()" class="px-7 py-3 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5 cursor-pointer shadow-md hover:shadow-lg transition-shadow">
                        <span>Request Demo</span>
                        <span class="text-xs font-bold font-mono">›</span>
                    </button>

                    <a href="{{ route('contact-us') }}" class="px-7 py-3 rounded-full bg-black/60 hover:bg-black/85 text-white border border-white/35 text-xs font-semibold tracking-wider transition-colors backdrop-blur-xs">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Open Space on Right to showcase the illuminated resort architecture -->
            <div class="hidden lg:block lg:w-1/3"></div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 11. LUXURY DARK FOOTER (EXACT MATCH TO REFERENCE SCREENSHOT) -->
    <!-- ========================================================================= -->
    <footer class="bg-[#080B10] text-white border-t border-white/10 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 items-start">
                
                <!-- Column 1: Brand Info (4 cols) -->
                <div class="lg:col-span-4 space-y-4 text-left">
                    <!-- Brand Logo: Exact logo.png -->
                    <a href="{{ route('landing') }}" class="inline-block group">
                        <img src="{{ asset('images/logo/logo.png') }}" 
                             alt="PAX TV" 
                             class="h-11 sm:h-13 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>

                    <!-- Tagline -->
                    <p class="text-xs sm:text-[13px] text-stone-400 leading-relaxed font-normal pt-1">
                        Premium Hotel TV Solution<br>
                        for a Smarter Stay Experience.
                    </p>

                    <!-- Flat Social Icons (No Box - Exact Screenshot Match) -->
                    <div class="flex items-center space-x-5 pt-2 text-stone-300">
                        <a href="#" class="hover:text-[#E5A853] transition-colors">
                            <i class="fa-brands fa-linkedin-in text-base"></i>
                        </a>
                        <a href="#" class="hover:text-[#E5A853] transition-colors">
                            <i class="fa-brands fa-youtube text-base"></i>
                        </a>
                        <a href="#" class="hover:text-[#E5A853] transition-colors">
                            <i class="fa-brands fa-instagram text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links (2.5 cols) -->
                <div class="lg:col-span-2 space-y-3.5 text-left">
                    <h4 class="text-sm font-bold text-white tracking-wide">Quick Links</h4>
                    <ul class="space-y-2 text-xs sm:text-[13px] text-stone-400 font-normal">
                        <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#solutions" class="hover:text-white transition-colors">Solutions</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('hotel.login') }}" class="text-[#E5A853] hover:text-[#F2B660] font-medium transition-colors">Hotel Login</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Our Solutions (3 cols) -->
                <div class="lg:col-span-3 space-y-3.5 text-left">
                    <h4 class="text-sm font-bold text-white tracking-wide">Our Solutions</h4>
                    <ul class="space-y-2 text-xs sm:text-[13px] text-stone-400 font-normal">
                        <li><a href="#solutions" class="hover:text-white transition-colors">Hotel TV Application</a></li>
                        <li><a href="#solutions" class="hover:text-white transition-colors">System Integration</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Multi-Language Support</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Hotel Information</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Live TV & Entertainment</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Guest Services</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Us (3.5 cols) -->
                <div class="lg:col-span-3 space-y-3.5 text-left">
                    <h4 class="text-sm font-bold text-white tracking-wide">Contact Us</h4>
                    <ul class="space-y-2.5 text-xs sm:text-[13px] text-stone-300 font-normal">
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-location-dot text-sm text-stone-400 w-4"></i>
                            <span>Mumbai, India</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-phone text-sm text-stone-400 w-4"></i>
                            <a href="tel:+919876543210" class="hover:text-white transition-colors">+91 98765 43210</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-regular fa-envelope text-sm text-stone-400 w-4"></i>
                            <a href="mailto:info@paxtv.com" class="hover:text-white transition-colors">info@paxtv.com</a>
                        </li>
                    </ul>

                    <!-- Wide Golden Pill: Request Demo › (Exact Match) -->
                    <div class="pt-3">
                        <button onclick="openRegisterModal()" 
                                class="w-full sm:w-auto px-8 py-3 rounded-full bg-[#F5C368] hover:bg-[#F2BA55] text-stone-950 font-bold text-xs tracking-wider flex items-center justify-center space-x-1.5 shadow-md cursor-pointer transition-colors">
                            <span>Request Demo</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links -->
            <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
                <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
                <p class="text-stone-400">
                    Developed by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="text-[#E5A853] hover:text-[#F2B660] font-medium transition-colors underline decoration-[#E5A853]/40 underline-offset-2 hover:decoration-[#F2B660]">Digi Emperor</a>
                </p>
                <div class="flex items-center space-x-5">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-stone-300 transition-colors">Privacy Policy</a>
                    <span class="text-stone-700">|</span>
                    <a href="#" class="hover:text-stone-300 transition-colors">Terms of Service</a>
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
    }

    function selectLandingLanguage(btn) {
        if (!btn) return;
        const allPills = document.querySelectorAll('#landingLanguageContainer .lang-pill');
        allPills.forEach(p => {
            p.classList.remove('bg-[#F5C368]', 'text-stone-950', 'font-bold', 'shadow-md', 'border-transparent', 'ring-2', 'ring-[#F5C368]/40', 'scale-105');
            p.classList.add('bg-white/10', 'border', 'border-white/20', 'text-white', 'font-medium', 'scale-100');
        });

        btn.classList.remove('bg-white/10', 'border', 'border-white/20', 'text-white', 'font-medium', 'scale-100');
        btn.classList.add('bg-[#F5C368]', 'text-stone-950', 'font-bold', 'shadow-md', 'border-transparent', 'ring-2', 'ring-[#F5C368]/40', 'scale-105');
    }
</script>
@endsection
