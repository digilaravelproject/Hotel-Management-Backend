@extends('layouts.landing')

@section('title', 'Privacy Policy - PAX TV Luxury Hospitality OS')

@section('styles')
<!-- Google Fonts: Luxury Editorial Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --color-gold: #C5A880;
        --color-gold-hover: #D8BA93;
        --color-gold-dark: #A07F54;
        --color-gold-badge: #F4EFE6;
        --color-gold-border: #E8DCCB;
        --color-bg-dark: #0A0D14;
        --color-bg-cream: #FAF8F5;
    }

    .font-serif-lux {
        font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
    }
    .font-sans-lux {
        font-family: 'Plus Jakarta Sans', 'DM Sans', sans-serif;
    }

    .btn-gold-pill {
        background-color: #C5A880;
        color: #11141B;
        font-weight: 700;
        border-radius: 9999px;
        transition: all 0.2s ease-in-out;
    }
    .btn-gold-pill:hover {
        background-color: #D6B993;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(197, 168, 128, 0.35);
    }

    .icon-box-gold {
        background-color: #F5EFE6;
        border: 1px solid #E6D8C4;
        color: #A07F54;
    }
</style>
@endsection

@section('content')
<div class="relative bg-[#0A0D14] text-slate-100 font-sans-lux min-h-screen selection:bg-[#C5A880] selection:text-slate-950">

    <!-- ========================================================================= -->
    <!-- HEADER NAVBAR (100% IDENTICAL TO LANDING PAGE) -->
    <!-- ========================================================================= -->
    <header class="relative z-50 px-6 lg:px-16 py-6 border-b border-white/10 backdrop-blur-md bg-[#0A0D14]/80 sticky top-0">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            
            <!-- Brand Logo: 8-Point Faceted Golden Crest + PAX TV -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-8 h-8 flex items-center justify-center text-[#C5A880] transition-transform duration-300 group-hover:scale-105">
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 1L14.4 7.2L20.8 5.6L18 11.6L23 15.2L16.8 17.2L16 23.6L12 18.8L8 23.6L7.2 17.2L1 15.2L6 11.6L3.2 5.6L9.6 7.2L12 1Z"/>
                    </svg>
                </div>
                <span class="font-serif-lux font-bold text-2xl tracking-[0.22em] text-white uppercase group-hover:text-[#E8DCCB] transition-colors">
                    PAX<span class="text-[#C5A880] ml-1">TV</span>
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-9 text-xs font-medium tracking-wider text-slate-300">
                <a href="{{ route('landing') }}" class="hover:text-[#C5A880] transition-colors">Home</a>
                <a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Features</a>
                <a href="{{ route('landing') }}#solutions" class="hover:text-[#C5A880] transition-colors">Solutions</a>
                <a href="{{ route('landing') }}#about" class="hover:text-[#C5A880] transition-colors">About</a>
                <a href="{{ route('contact-us') }}" class="hover:text-[#C5A880] transition-colors">Contact</a>
            </nav>

            <!-- Header Actions -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-4 py-2 rounded-full border border-white/20 bg-white/5 hover:bg-white/10 text-white font-medium text-xs tracking-wider transition-all">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Home</span>
                </a>
                <a href="{{ route('hotel.login') }}" class="text-xs font-medium text-stone-200 hover:text-[#C5A880] flex items-center space-x-1.5 px-3.5 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#C5A880]/50 transition-all">
                    <i class="fa-solid fa-hotel text-[11px] text-[#C5A880]"></i>
                    <span>Hotel Login</span>
                </a>
                <a href="{{ route('contact-us') }}" class="px-6 py-2.5 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5">
                    <span>Contact Us</span>
                    <span class="text-xs font-bold font-mono">›</span>
                </a>
            </div>

        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- HERO HEADER BANNER (LUXURY ATMOSPHERE) -->
    <!-- ========================================================================= -->
    <section class="relative py-20 px-6 lg:px-16 border-b border-white/10 overflow-hidden">
        <!-- Background Imagery with Ambient Vignette -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85" 
                 alt="Luxury Resort" 
                 class="w-full h-full object-cover filter brightness-[0.3] contrast-[1.1]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0D14] via-[#0A0D14]/70 to-[#0A0D14]/90"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center space-y-4">
            <div class="text-[#C5A880] text-xs font-bold tracking-[0.25em] uppercase">
                SECURITY & TRUST
            </div>
            <h1 class="font-serif-lux text-4xl sm:text-5xl lg:text-6xl font-medium tracking-tight text-white leading-tight">
                Privacy Policy
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-normal">
                Simple, transparent, and built with respect for hotel partners and guest confidentiality.
            </p>
            <div class="flex items-center justify-center space-x-4 text-xs text-[#DFCAAB] font-medium pt-1">
                <span>Effective Date: September 2026</span>
                <span>•</span>
                <span>Version 2.5</span>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- MAIN CONTENT SECTION (WARM CREAM PALETTE MATCHING ARTBOARD) -->
    <!-- ========================================================================= -->
    <main class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF8F5] text-slate-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
            
            <!-- Sticky Sidebar on Left -->
            <aside class="hidden lg:block lg:col-span-4">
                <div class="sticky top-28 space-y-5 p-6 bg-white border border-[#E5DAC8] rounded-3xl shadow-sm">
                    <h3 class="font-serif-lux font-bold text-sm text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-list-ul text-[#A07F54] text-xs"></i>
                        <span>Table of Contents</span>
                    </h3>
                    <nav class="space-y-1.5 text-xs font-medium text-slate-600">
                        <a href="#overview" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">1. Overview & Scope</a>
                        <a href="#information-we-collect" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">2. Information We Collect</a>
                        <a href="#guest-privacy" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">3. In-Room Smart TV Privacy</a>
                        <a href="#how-we-use-data" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">4. How We Use Information</a>
                        <a href="#data-storage-security" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">5. Security & Encryption</a>
                        <a href="#third-parties" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">6. Third-Party Integrations</a>
                        <a href="#data-retention" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">7. Data Retention & Deletion</a>
                        <a href="#partner-rights" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">8. Hotel Partner Rights</a>
                        <a href="#contact" class="block px-3 py-2 rounded-xl hover:bg-[#FAF6EE] hover:text-[#A07F54] transition-colors">9. Contact & Inquiries</a>
                    </nav>

                    <div class="pt-4 border-t border-stone-200">
                        <div class="p-4 rounded-2xl bg-[#FAF6EE] border border-[#E5DAC8] text-xs space-y-2">
                            <span class="font-bold text-slate-900 block font-serif-lux">Have a Question?</span>
                            <p class="text-slate-600 leading-relaxed font-normal">Our data privacy & technical desk is available to assist.</p>
                            <a href="{{ route('contact-us') }}" class="inline-flex items-center text-[#A07F54] font-bold hover:underline">
                                <span>Contact Us</span>
                                <span class="font-mono text-xs ml-1">›</span>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Policy Content Articles on Right -->
            <div class="lg:col-span-8 space-y-10">

                <!-- Highlights Pledge Box (Dark & Gold Luxury Card) -->
                <div class="p-7 sm:p-9 rounded-3xl bg-[#0A0D14] text-white border border-white/10 shadow-xl space-y-3 relative overflow-hidden">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-[#C5A880] text-[10px] font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span>Hospitality Privacy Pledge</span>
                    </div>
                    <h2 class="font-serif-lux text-2xl sm:text-3xl font-bold text-white">Privacy by Design for Hospitality</h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                        PAX TV is purpose-built for five-star hotels and luxury stays. We never sell personal data, we never secretly track guest viewing habits, and in-room smart TV sessions are expunged automatically upon guest checkout.
                    </p>
                </div>

                <!-- Section 1 -->
                <section id="overview" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">1</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">Overview & Scope</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        This Privacy Policy governs the manner in which <strong>PAX TV Hospitality Networks</strong> ("PAX TV", "we", "our", or "the Platform") collects, maintains, and discloses operational telemetry collected from hotel subscribers ("Hotels") and our in-room Smart TV applications deployed on guest room screens.
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        By subscribing to a plan, provisioning TV licenses, or deploying the PAX TV client software onto hotel television hardware, you acknowledge and agree to the protocols outlined herein.
                    </p>
                </section>

                <!-- Section 2 -->
                <section id="information-we-collect" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">2</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">Information We Collect</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        To maintain high reliability and seamless remote control across guest room screens, we collect only necessary administrative and hardware data:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] space-y-2">
                            <div class="text-[#A07F54] font-bold text-xs flex items-center space-x-2">
                                <i class="fa-solid fa-user-tie"></i>
                                <span>Hotel Partner Information</span>
                            </div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside font-normal">
                                <li>Hotel owner / GM representative name</li>
                                <li>Corporate email & telephone number</li>
                                <li>Property brand name, city, and address</li>
                                <li>Total room count & subscription tier</li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] space-y-2">
                            <div class="text-[#A07F54] font-bold text-xs flex items-center space-x-2">
                                <i class="fa-solid fa-tv"></i>
                                <span>TV & Hardware Telemetry</span>
                            </div>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside font-normal">
                                <li>Assigned room numbers & 16-digit license</li>
                                <li>Device MAC address & internal local IP</li>
                                <li>App version, OS build & connectivity heartbeats</li>
                                <li>Online status for PMS synchronization</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Section 3 -->
                <section id="guest-privacy" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">3</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">In-Room Smart TV & Guest Privacy</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        Guest privacy is non-negotiable. Our smart TV platform enforces strict operational boundaries:
                    </p>
                    
                    <div class="space-y-3 pt-1">
                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-[#FAF6EE] border border-[#E5DAC8] text-xs">
                            <i class="fa-solid fa-circle-check text-[#A07F54] mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-slate-900 block font-serif-lux">Automatic Session Wipe on Checkout</span>
                                <span class="text-slate-600 leading-relaxed">When a guest checks out, all temporary cache, app history, and third-party logins on the in-room TV are expunged automatically.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-white border border-[#E5DAC8] text-xs">
                            <i class="fa-solid fa-eye-slash text-[#A07F54] mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-slate-900 block font-serif-lux">No Hidden Audio or Video Monitoring</span>
                                <span class="text-slate-600 leading-relaxed">PAX TV does not record microphone audio or webcam video. It operates exclusively as a presentation and hospitality guest interface.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-white border border-[#E5DAC8] text-xs">
                            <i class="fa-solid fa-shield text-[#A07F54] mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-slate-900 block font-serif-lux">No Storage of Personal Streaming Logins</span>
                                <span class="text-slate-600 leading-relaxed">If guests open third-party OTT applications (e.g. Netflix, YouTube), credentials are encrypted directly through Google Play services and never stored on our servers.</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 4 -->
                <section id="how-we-use-data" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">4</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">How We Use Information</h2>
                    </div>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-2 list-disc list-inside font-normal">
                        <li><strong>Platform Provisioning:</strong> Validating television screens against authorized 16-digit license keys.</li>
                        <li><strong>Content Synchronization:</strong> Pushing real-time hotel dining menus, custom welcome themes, and city attractions.</li>
                        <li><strong>Emergency Notifications:</strong> Broadcasting instant priority alerts (fire alarms, flight updates, weather alerts) to guest screens.</li>
                        <li><strong>Billing & Invoicing:</strong> Managing subscription cycles and GST compliant commercial invoicing.</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section id="data-storage-security" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">5</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">Security & Encryption</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        We employ enterprise-grade cloud security to safeguard all hotel telemetry and configuration assets:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] text-center space-y-1">
                            <i class="fa-solid fa-lock text-[#A07F54] text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">TLS 256-Bit</h4>
                            <p class="text-[11px] text-slate-500">All data in transit is encrypted using HTTPS / TLS protocol.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] text-center space-y-1">
                            <i class="fa-solid fa-key text-[#A07F54] text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">Bcrypt Hashing</h4>
                            <p class="text-[11px] text-slate-500">Admin passwords and tokens are irreversibly hashed.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] text-center space-y-1">
                            <i class="fa-solid fa-server text-[#A07F54] text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">Cloud Backups</h4>
                            <p class="text-[11px] text-slate-500">Automated daily snapshots with isolated tenant architecture.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 6 -->
                <section id="contact" class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl icon-box-gold flex items-center justify-center font-bold text-xs">6</span>
                        <h2 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">Contact & Inquiries</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        If you have questions about this Privacy Policy or wish to request data deletion for your property, contact our privacy officer:
                    </p>
                    <div class="p-5 rounded-2xl bg-[#FAF8F5] border border-[#E5DAC8] space-y-2 text-xs">
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-envelope text-[#A07F54]"></i>
                            <span>Email: <a href="mailto:info@paxtvnetwork.com" class="text-[#A07F54] hover:underline">info@paxtvnetwork.com</a></span>
                        </div>
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-phone text-[#A07F54]"></i>
                            <span>Helpline: +91 98765 43210</span>
                        </div>
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-location-dot text-[#A07F54]"></i>
                            <span>Office: Bandra Kurla Complex, Mumbai, Maharashtra 400051, India</span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('contact-us') }}" class="inline-flex items-center space-x-1.5 px-6 py-2.5 btn-gold-pill text-xs">
                            <span>Open Contact Form</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </a>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- FOOTER (100% IDENTICAL TO LANDING PAGE) -->
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

                <!-- Column 2: Quick Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('landing') }}" class="hover:text-[#C5A880] transition-colors">Home</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Features</a></li>
                        <li><a href="{{ route('landing') }}#solutions" class="hover:text-[#C5A880] transition-colors">Solutions</a></li>
                        <li><a href="{{ route('landing') }}#about" class="hover:text-[#C5A880] transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-[#C5A880] transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Our Solutions -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Our Solutions</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('landing') }}#solutions" class="hover:text-[#C5A880] transition-colors">Hotel TV Application</a></li>
                        <li><a href="{{ route('landing') }}#solutions" class="hover:text-[#C5A880] transition-colors">System Integration</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Multi-Language Support</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Hotel Information</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Live TV & Entertainment</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-[#C5A880] transition-colors">Guest Services</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Us -->
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
                        <a href="{{ route('landing') }}" class="w-full py-2.5 px-4 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-1">
                            <span>Request Demo</span>
                            <span class="text-xs font-bold font-mono">›</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links -->
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
                <p class="text-stone-400">
                    Developed by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="text-[#C5A880] hover:text-[#E5A853] font-medium transition-colors underline decoration-[#C5A880]/40 underline-offset-2 hover:decoration-[#E5A853]">Digi Emperor</a>
                </p>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('privacy-policy') }}" class="text-[#C5A880] font-bold hover:underline">Privacy Policy</a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="hover:text-[#C5A880] transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

</div>
@endsection
