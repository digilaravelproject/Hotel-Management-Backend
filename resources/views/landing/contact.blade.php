@extends('layouts.landing')

@section('title', 'Contact Us - HotelTV Connect')

@section('content')
<div class="relative bg-slate-50 text-slate-900 font-sans min-h-screen selection:bg-indigo-600 selection:text-white">

    <!-- Organic Background Diffused Glows -->
    <div class="absolute -top-40 left-1/4 w-[500px] h-[500px] bg-indigo-200/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-[450px] h-[450px] bg-sky-200/30 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-slate-200/80 px-6 lg:px-16 py-4 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tv text-lg"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-slate-900 font-['Syne']">
                    Hotel<span class="text-indigo-600">TV</span>
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-bold text-slate-600">
                <a href="{{ route('landing') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <a href="{{ route('landing') }}#features" class="hover:text-indigo-600 transition-colors">Features</a>
                <a href="{{ route('landing') }}#plans" class="hover:text-indigo-600 transition-colors">Pricing & Plans</a>
                <a href="{{ route('privacy-policy') }}" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-4 py-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-all shadow-xs">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back to Home</span>
                </a>
                @if(Auth::guard('hotel_admin')->check())
                    <a href="{{ route('hotel.dashboard') }}" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('hotel.login') }}" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all">
                        Hotel Login
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="relative pt-12 pb-10 px-6 lg:px-16 border-b border-slate-200/60 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/60 text-indigo-700 text-xs font-bold tracking-wide">
                <i class="fa-solid fa-headset text-indigo-600"></i>
                <span>We're Here For You</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight font-['Syne']">
                Contact Us
            </h1>
            <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Have questions about TV hardware compatibility, onboarding your property, or enterprise setup? Reach out to our team anytime.
            </p>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="max-w-7xl mx-auto px-6 lg:px-16 py-12 lg:py-16">
        
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center space-x-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <span class="font-bold block">Message Sent Successfully!</span>
                    <span class="text-emerald-700 text-xs font-normal">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-xs">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
            
            <!-- Left Column: Contact Channels & Cards -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="space-y-2">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 font-['Syne']">
                        Get in touch with us
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Whether you are setting up 5 rooms or managing 500+ screens across multi-city hotels, our support engineers and product specialists are ready to help.
                    </p>
                </div>

                <!-- Channels List -->
                <div class="space-y-4">
                    
                    <!-- Email Card -->
                    <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-start space-x-4 hover:border-indigo-200 transition-all">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 shadow-xs">
                            <i class="fa-solid fa-envelope text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-['Syne']">Email Us</h3>
                            <p class="text-xs text-slate-500">General support & product questions</p>
                            <div class="pt-1">
                                <a href="mailto:support@hoteltvconnect.com" class="text-xs font-bold text-indigo-600 hover:underline block">
                                    support@hoteltvconnect.com
                                </a>
                                <a href="mailto:sales@hoteltvconnect.com" class="text-xs font-medium text-slate-600 hover:underline block">
                                    sales@hoteltvconnect.com
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-start space-x-4 hover:border-indigo-200 transition-all">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 shadow-xs">
                            <i class="fa-solid fa-phone text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-['Syne']">Call Support</h3>
                            <p class="text-xs text-slate-500">Mon – Sat from 9:00 AM to 8:00 PM IST</p>
                            <div class="pt-1">
                                <a href="tel:+919876543210" class="text-xs font-bold text-emerald-700 hover:underline block">
                                    +91 98765 43210
                                </a>
                                <span class="text-[11px] text-slate-500 block font-medium">Landline: +91 (080) 4567-8900</span>
                            </div>
                        </div>
                    </div>

                    <!-- Office Location Card -->
                    <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-start space-x-4 hover:border-indigo-200 transition-all">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600 shrink-0 shadow-xs">
                            <i class="fa-solid fa-location-dot text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-['Syne']">Head Office</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                HotelTV Connect Technologies<br>
                                Embassy Tech Village, Outer Ring Road,<br>
                                Bengaluru, Karnataka 560103, India
                            </p>
                        </div>
                    </div>

                    <!-- 24x7 Emergency Help for Live Hotels -->
                    <div class="p-5 rounded-3xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                        <div class="flex items-center space-x-2 text-amber-800 font-bold text-xs">
                            <i class="fa-solid fa-bell-concierge text-sm"></i>
                            <span>Hospitality Priority Support</span>
                        </div>
                        <p class="text-xs text-amber-900/80 leading-relaxed">
                            Already an active partner hotel experiencing a critical TV outage or pairing issue during guest check-in? Log in to your <a href="{{ route('hotel.login') }}" class="font-bold underline text-amber-950">Hotel Admin Portal</a> for instant live ticket routing.
                        </p>
                    </div>

                </div>

            </div>

            <!-- Right Column: Interactive Contact Form -->
            <div class="lg:col-span-7">
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-xl shadow-slate-200/50 space-y-6">
                    
                    <div class="border-b border-slate-100 pb-4 space-y-1">
                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Send us a message</h3>
                        <p class="text-xs text-slate-500 font-medium">Fill out the details below and our team will get back to you within 24 hours.</p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Name and Email Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="name" class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                    <span>Full Name <span class="text-rose-500">*</span></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-regular fa-user text-xs"></i>
                                    </div>
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Rahul Sharma" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="email" class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                    <span>Work Email <span class="text-rose-500">*</span></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-regular fa-envelope text-xs"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="rahul@luxuryhotel.com" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Phone and Hotel Name Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="phone" class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                    <span>Phone Number</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Optional</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-phone text-xs"></i>
                                    </div>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="hotel_name" class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                    <span>Hotel / Property Name</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Optional</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-hotel text-xs"></i>
                                    </div>
                                    <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name') }}" placeholder="e.g. Royal Orchid Resort" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Inquiry Category -->
                        <div class="space-y-1.5">
                            <label for="subject" class="text-xs font-bold text-slate-700">
                                How can we help? <span class="text-rose-500">*</span>
                            </label>
                            <select name="subject" id="subject" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all text-slate-700">
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select an inquiry topic...</option>
                                <option value="New Hotel Onboarding & Plan Pricing" {{ old('subject') == 'New Hotel Onboarding & Plan Pricing' ? 'selected' : '' }}>New Hotel Onboarding & Plan Pricing</option>
                                <option value="TV Device Pairing & Hardware Help" {{ old('subject') == 'TV Device Pairing & Hardware Help' ? 'selected' : '' }}>TV Device Pairing & Hardware Help</option>
                                <option value="Billing & Subscription Renewal" {{ old('subject') == 'Billing & Subscription Renewal' ? 'selected' : '' }}>Billing & Subscription Renewal</option>
                                <option value="Custom Theme, OTT, or API Integration" {{ old('subject') == 'Custom Theme, OTT, or API Integration' ? 'selected' : '' }}>Custom Theme, OTT, or API Integration</option>
                                <option value="General Question / Partnership" {{ old('subject') == 'General Question / Partnership' ? 'selected' : '' }}>General Question / Partnership</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="space-y-1.5">
                            <label for="message" class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                <span>Your Message <span class="text-rose-500">*</span></span>
                                <span class="text-[10px] text-slate-400 font-normal">Min 10 characters</span>
                            </label>
                            <textarea name="message" id="message" rows="5" required placeholder="Tell us about your requirements, room count, or any specific questions..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-indigo-600 transition-all resize-y">{{ old('message') }}</textarea>
                        </div>

                        <!-- Privacy notice & Submit -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-[11px] text-slate-400 leading-tight text-center sm:text-left">
                                <i class="fa-solid fa-lock mr-1 text-slate-400"></i>
                                We respect your privacy. See our <a href="{{ route('privacy-policy') }}" class="underline hover:text-indigo-600">Privacy Policy</a>.
                            </p>
                            <button type="submit" class="w-full sm:w-auto px-7 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                                <span>Send Message</span>
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- Quick FAQ Section -->
        <section class="mt-20 pt-12 border-t border-slate-200/80">
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-10">
                <span class="text-xs font-extrabold text-indigo-600 uppercase tracking-widest font-['Syne']">Common Questions</span>
                <h2 class="text-2xl font-extrabold text-slate-900 font-['Syne']">Frequently Asked Questions</h2>
                <p class="text-xs text-slate-500">Quick answers to help you get started with HotelTV Connect.</p>
            </div>

            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                    <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                        <i class="fa-regular fa-circle-question text-indigo-600"></i>
                        <span>Do I need to buy special commercial TVs?</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        No. HotelTV Connect runs smoothly on standard Android TVs (Android 8.0+), Google TVs, or economical Android streaming sticks plugged into any existing HDMI television.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                    <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                        <i class="fa-regular fa-circle-question text-indigo-600"></i>
                        <span>How quickly can we roll out to 50+ rooms?</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pairing each TV takes less than 60 seconds. Simply download the HotelTV app on the screen, input your 16-digit hotel license key, and the TV configures itself automatically.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                    <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                        <i class="fa-regular fa-circle-question text-indigo-600"></i>
                        <span>Can I customize the TV branding with my hotel logo?</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Yes! From your Hotel Admin Dashboard, you can upload your custom hotel logo, cover photo, food menus, local airport timings, and emergency contact numbers in real-time.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-2">
                    <h4 class="font-bold text-xs text-slate-900 flex items-center space-x-2">
                        <i class="fa-regular fa-circle-question text-indigo-600"></i>
                        <span>How does billing and plan upgrading work?</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Subscription plans scale cleanly with your room count. You can start on a smaller plan and upgrade directly through our automated Razorpay payment integration anytime.
                    </p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white border-t border-slate-800 pt-16 pb-12 mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30">
                            <i class="fa-solid fa-tv text-lg"></i>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-white font-['Syne']">
                            Hotel<span class="text-indigo-400">TV</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Next-generation Android TV operating system & guest interaction software designed for modern hotels, boutique resorts, and luxury stays.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Navigation</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('landing') }}" class="hover:text-indigo-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('landing') }}#features" class="hover:text-indigo-400 transition-colors">Platform Features</a></li>
                        <li><a href="{{ route('landing') }}#plans" class="hover:text-indigo-400 transition-colors">Pricing & Plans</a></li>
                        <li><a href="{{ route('landing') }}#faq" class="hover:text-indigo-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Legal & Support Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Trust & Support</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-indigo-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('contact-us') }}" class="text-indigo-400 font-bold hover:underline">Contact Support</a></li>
                        <li><a href="{{ route('landing') }}#social-proof" class="hover:text-indigo-400 transition-colors">Hotel Partners</a></li>
                    </ul>
                </div>

                <!-- Access Portals -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Access Portals</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li>
                            <a href="{{ route('hotel.login') }}" class="inline-flex items-center space-x-2 hover:text-indigo-400 transition-colors">
                                <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                                <span>Hotel Admin Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('super-admin.login') }}" class="inline-flex items-center space-x-2 hover:text-indigo-400 transition-colors">
                                <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                <span>Super Admin Console</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="flex flex-col md:flex-row items-center justify-between pt-6 border-t border-slate-800 gap-4 text-xs text-slate-500 font-medium">
                <p>© {{ date('Y') }} HotelTV Connect Management System. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-slate-300 transition-colors">Privacy</a>
                    <a href="{{ route('contact-us') }}" class="hover:text-slate-300 transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

</div>
@endsection
