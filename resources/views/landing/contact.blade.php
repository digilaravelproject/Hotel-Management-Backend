@extends('layouts.landing')

@section('title', 'Contact Us - PAX TV Luxury Hospitality OS')

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
                <a href="{{ route('contact-us') }}" class="text-white border-b-2 border-[#C5A880] pb-1 hover:text-[#C5A880] transition-colors">Contact</a>
            </nav>

            <!-- Header Action Button -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-4 py-2 rounded-full border border-white/20 bg-white/5 hover:bg-white/10 text-white font-medium text-xs tracking-wider transition-all">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Home</span>
                </a>
                <a href="{{ route('hotel.login') }}" class="text-xs font-medium text-stone-200 hover:text-[#C5A880] flex items-center space-x-1.5 px-3.5 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#C5A880]/50 transition-all">
                    <i class="fa-solid fa-hotel text-[11px] text-[#C5A880]"></i>
                    <span>Hotel Login</span>
                </a>
                <a href="{{ route('landing') }}" class="px-6 py-2.5 btn-gold-pill text-xs tracking-wider flex items-center space-x-1.5">
                    <span>Request Demo</span>
                    <span class="text-xs font-bold font-mono">›</span>
                </a>
            </div>

        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- HERO HEADER BANNER (LUXURY SUITE ATMOSPHERE) -->
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
                GET IN TOUCH
            </div>
            <h1 class="font-serif-lux text-4xl sm:text-5xl lg:text-6xl font-medium tracking-tight text-white leading-tight">
                Contact Us
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-normal">
                Have questions about TV hardware compatibility, onboarding your property, or enterprise setup? Reach out to our hospitality specialists.
            </p>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- MAIN CONTENT SECTION (WARM CREAM PALETTE MATCHING ARTBOARD) -->
    <!-- ========================================================================= -->
    <main class="py-20 lg:py-24 px-6 lg:px-16 bg-[#FAF8F5] text-slate-900">
        <div class="max-w-7xl mx-auto space-y-16">
            
            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center space-x-3 shadow-xs">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div>
                        <span class="font-bold block">Message Sent Successfully!</span>
                        <span class="text-emerald-700 text-xs font-normal">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-1 shadow-xs">
                    <div class="flex items-center space-x-2 font-bold text-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside pl-4 font-normal text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                <!-- Left Column: Contact Channels & Cards -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="space-y-2">
                        <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">
                            HOSPITALITY DESK
                        </div>
                        <h2 class="font-serif-lux text-2xl sm:text-3xl lg:text-4xl font-medium tracking-tight text-slate-900">
                            Get in Touch with Us
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                            Whether you are outfitting 10 luxury suites or managing 500+ screens across multi-property hotels, our technical deployment team is ready to assist.
                        </p>
                    </div>

                    <!-- Channel Cards (Matching Artboard Tan & Gold Accents) -->
                    <div class="space-y-3.5">
                        
                        <!-- Email Card -->
                        <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] flex items-start space-x-4 shadow-2xs hover:border-[#C5A880] transition-all">
                            <div class="w-11 h-11 rounded-xl icon-box-gold flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope text-base"></i>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block font-serif-lux">Email Inquiries</span>
                                <p class="text-[11px] text-slate-500">General support & enterprise licensing</p>
                                <div class="pt-1.5 space-y-0.5">
                                    <a href="mailto:info@paxtvnetwork.com" class="text-xs font-bold text-[#A07F54] hover:underline block">
                                        info@paxtvnetwork.com
                                    </a>
                                    <a href="mailto:support@paxtvnetwork.com" class="text-xs font-medium text-slate-600 hover:underline block">
                                        support@paxtvnetwork.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Card -->
                        <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] flex items-start space-x-4 shadow-2xs hover:border-[#C5A880] transition-all">
                            <div class="w-11 h-11 rounded-xl icon-box-gold flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone text-base"></i>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block font-serif-lux">Direct Helpline</span>
                                <p class="text-[11px] text-slate-500">Mon – Sat from 9:00 AM to 8:00 PM IST</p>
                                <div class="pt-1.5 space-y-0.5">
                                    <a href="tel:+919876543210" class="text-xs font-bold text-[#A07F54] hover:underline block">
                                        +91 98765 43210
                                    </a>
                                    <span class="text-[11px] text-slate-500 block">Landline: +91 (080) 4567-8900</span>
                                </div>
                            </div>
                        </div>

                        <!-- Office Location Card -->
                        <div class="p-5 rounded-2xl bg-white border border-[#E5DAC8] flex items-start space-x-4 shadow-2xs hover:border-[#C5A880] transition-all">
                            <div class="w-11 h-11 rounded-xl icon-box-gold flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-base"></i>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block font-serif-lux">Corporate Office</span>
                                <p class="text-xs text-slate-600 leading-relaxed pt-1">
                                    PAX TV Hospitality Networks<br>
                                    Bandra Kurla Complex, Mumbai, Maharashtra 400051, India
                                </p>
                            </div>
                        </div>

                        <!-- Priority 24/7 Support for Live Hotels -->
                        <div class="p-5 rounded-2xl bg-[#FAF6EE] border border-[#E0D3BE] space-y-2">
                            <div class="flex items-center space-x-2 text-[#A07F54] font-bold text-xs">
                                <i class="fa-solid fa-bell-concierge text-sm"></i>
                                <span>24x7 Priority Support for Active Hotels</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                Operating an active property and need immediate TV pairing or sync assistance? Log in directly via your <a href="{{ route('hotel.login') }}" class="font-bold underline text-[#A07F54]">Hotel Admin Portal</a>.
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Luxury Interactive Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white border border-[#E5DAC8] rounded-3xl p-6 sm:p-10 shadow-xl space-y-6">
                        
                        <div class="border-b border-stone-200 pb-4 space-y-1">
                            <h3 class="font-serif-lux text-xl sm:text-2xl font-bold text-slate-900">Send Us a Message</h3>
                            <p class="text-xs text-slate-500 font-normal">Our technical and onboarding team will respond within 24 hours.</p>
                        </div>

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                            @csrf

                            <!-- Name & Email Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="name" class="text-xs font-bold text-slate-700">Full Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Rahul Sharma" class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="email" class="text-xs font-bold text-slate-700">Work Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="rahul@luxuryhotel.com" class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all">
                                </div>
                            </div>

                            <!-- Phone & Hotel Name Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="phone" class="text-xs font-bold text-slate-700">Phone Number <span class="text-[10px] text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="hotel_name" class="text-xs font-bold text-slate-700">Hotel / Property Name <span class="text-[10px] text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name') }}" placeholder="e.g. The Grand Palace Resort" class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all">
                                </div>
                            </div>

                            <!-- Inquiry Category -->
                            <div class="space-y-1.5">
                                <label for="subject" class="text-xs font-bold text-slate-700">How can we assist you? <span class="text-rose-500">*</span></label>
                                <select name="subject" id="subject" required class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all text-slate-700">
                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select an inquiry topic...</option>
                                    <option value="New Hotel Onboarding & Plan Pricing" {{ old('subject') == 'New Hotel Onboarding & Plan Pricing' ? 'selected' : '' }}>New Hotel Onboarding & Plan Pricing</option>
                                    <option value="TV Device Pairing & Hardware Setup" {{ old('subject') == 'TV Device Pairing & Hardware Setup' ? 'selected' : '' }}>TV Device Pairing & Hardware Setup</option>
                                    <option value="Billing & Subscription Renewal" {{ old('subject') == 'Billing & Subscription Renewal' ? 'selected' : '' }}>Billing & Subscription Renewal</option>
                                    <option value="Custom Theme, OTT, or PMS Integration" {{ old('subject') == 'Custom Theme, OTT, or PMS Integration' ? 'selected' : '' }}>Custom Theme, OTT, or PMS Integration</option>
                                    <option value="General Question / Partnership" {{ old('subject') == 'General Question / Partnership' ? 'selected' : '' }}>General Question / Partnership</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="space-y-1.5">
                                <label for="message" class="text-xs font-bold text-slate-700">Your Message <span class="text-rose-500">*</span></label>
                                <textarea name="message" id="message" rows="5" required placeholder="Tell us about your property, estimated room count, or specific requirements..." class="w-full px-4 py-3 bg-[#FAF8F5] border border-[#E5DAC8] rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#C5A880] transition-all resize-y">{{ old('message') }}</textarea>
                            </div>

                            <!-- Submit Action -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <p class="text-[11px] text-slate-500">
                                    <i class="fa-solid fa-lock mr-1 text-[#A07F54]"></i>
                                    Protected under our <a href="{{ route('privacy-policy') }}" class="underline hover:text-[#A07F54]">Privacy Policy</a>.
                                </p>
                                <button type="submit" class="w-full sm:w-auto px-8 py-3 btn-gold-pill text-xs tracking-wider flex items-center justify-center space-x-1.5 cursor-pointer">
                                    <span>Send Message</span>
                                    <span class="text-xs font-bold font-mono">›</span>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>

            <!-- Frequently Asked Questions Accordion/Cards -->
            <section class="pt-12 border-t border-stone-200">
                <div class="max-w-2xl mx-auto text-center space-y-2 mb-10">
                    <div class="text-[#A07F54] font-bold text-xs uppercase tracking-[0.25em]">COMMON QUESTIONS</div>
                    <h2 class="font-serif-lux text-2xl sm:text-3xl font-medium tracking-tight text-slate-900">Frequently Asked Questions</h2>
                    <p class="text-xs text-slate-500 font-normal">Quick answers about PAX TV deployment and hardware compatibility.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl mx-auto">
                    <div class="p-6 rounded-2xl bg-white border border-[#E5DAC8] shadow-2xs space-y-2">
                        <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-tv text-[#A07F54]"></i>
                            <span>Do I need to buy special commercial TVs?</span>
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            No. PAX TV runs smoothly on standard Android TVs (Android 8.0+), Google TVs, or economical Android streaming sticks plugged into any existing HDMI television.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white border border-[#E5DAC8] shadow-2xs space-y-2">
                        <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-bolt text-[#A07F54]"></i>
                            <span>How quickly can we roll out to 50+ rooms?</span>
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Pairing each TV takes less than 60 seconds. Simply open the PAX TV app on the screen, input your 16-digit hotel license key, and the TV configures itself automatically.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white border border-[#E5DAC8] shadow-2xs space-y-2">
                        <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-palette text-[#A07F54]"></i>
                            <span>Can I customize the TV branding with our hotel logo?</span>
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Yes. From your Hotel Admin Dashboard, you can upload your custom hotel logo, cover photo, dining menus, local airport timings, and emergency numbers in real-time.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white border border-[#E5DAC8] shadow-2xs space-y-2">
                        <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-credit-card text-[#A07F54]"></i>
                            <span>How does billing and plan upgrading work?</span>
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Subscription plans scale cleanly with your room count. You can start on a smaller plan and upgrade directly through our automated Razorpay payment integration anytime.
                        </p>
                    </div>
                </div>
            </section>

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
                        <li><a href="{{ route('contact-us') }}" class="text-[#C5A880] font-bold hover:underline">Contact</a></li>
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
                    <a href="{{ route('privacy-policy') }}" class="hover:text-[#C5A880] transition-colors">Privacy Policy</a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="hover:text-[#C5A880] transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

</div>
@endsection
