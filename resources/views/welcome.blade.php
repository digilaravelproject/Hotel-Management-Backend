@extends('layouts.landing')

@section('title', 'HotelTV - Next Gen Smart TV Solution for Luxury Hospitality')

@section('content')
<div class="relative overflow-hidden bg-slate-50 text-slate-800">
    <!-- Background Light Subtle Radial Gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.08),rgba(255,255,255,0))] pointer-events-none"></div>

    <!-- Header Glassmorphism Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        <i class="fa-solid fa-tv text-xl text-white"></i>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Hotel<span class="text-indigo-600">TV</span>
                    </span>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#solutions" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition-colors">Solutions</a>
                    <a href="#experience" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition-colors">Guest Experience</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('hotel.login') }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 px-4 py-2.5 rounded-xl transition-colors">
                        Hotel Login
                    </a>
                    <a href="{{ route('hotel.login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                        Get Started <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-16 pb-24 sm:pt-20 sm:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Badge -->
                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span>Next Generation Hospitality TV System</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                    Transform Room TVs into <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-rose-600 bg-clip-text text-transparent">Luxury Concierge Hubs</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed">
                    Streamline guest engagement, OTT app integration, in-room dining services, and custom branding across thousands of Smart TVs seamlessly.
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('hotel.login') }}" class="w-full sm:w-auto px-8 py-4 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-xl shadow-indigo-600/30 transition-all hover:-translate-y-1">
                        Launch Hotel Portal <i class="fa-solid fa-rocket ml-2"></i>
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 text-xs font-bold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200/80 rounded-2xl transition-all shadow-xs">
                        Explore Features
                    </a>
                </div>
            </div>

            <!-- Hero Image Preview -->
            <div class="mt-16 relative max-w-5xl mx-auto rounded-3xl overflow-hidden shadow-2xl shadow-slate-200 border border-slate-200/80">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80" alt="Luxury Hotel Suite TV Interface" class="w-full h-[450px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                
                <!-- Floating Info Box -->
                <div class="absolute bottom-6 left-6 right-6 flex flex-col md:flex-row items-center justify-between bg-white/95 backdrop-blur-xl border border-slate-200 p-6 rounded-3xl gap-4 shadow-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-sliders text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-extrabold text-sm">Real-time Remote Control & OTA</h4>
                            <p class="text-slate-500 text-xs font-medium">Push updates, template customizations, and app configs instantly.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6 text-slate-700 text-xs font-bold">
                        <span><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> 4K Native Support</span>
                        <span><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> Custom Branding</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Stats Section -->
    <section class="border-y border-slate-200/80 bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="space-y-1">
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900">500+</h3>
                    <p class="text-slate-500 text-xs font-bold">Hotels Onboarded</p>
                </div>
                <div class="space-y-1">
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-indigo-600">25,000+</h3>
                    <p class="text-slate-500 text-xs font-bold">Smart TV Devices Active</p>
                </div>
                <div class="space-y-1">
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-violet-600">99.9%</h3>
                    <p class="text-slate-500 text-xs font-bold">Uptime Guarantee</p>
                </div>
                <div class="space-y-1">
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-rose-600">24/7</h3>
                    <p class="text-slate-500 text-xs font-bold">Dedicated Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Showcase -->
    <section id="features" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Everything Your Hotel Needs on TV</h2>
                <p class="text-slate-500 text-xs font-medium">Deliver a seamless digital experience that boosts guest satisfaction and revenue.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-indigo-500/40 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="fa-solid fa-tv text-xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">Custom Interactive Templates</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Customize welcome screens, hotel logos, dynamic image sliders, and local weather directly from the Hotel Admin portal.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-violet-500/40 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                        <i class="fa-solid fa-utensils text-xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">In-Room Dining & Services</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Allow guests to browse food & beverage menus, request housekeeping, or access emergency contacts right from their screen.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-rose-500/40 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                        <i class="fa-solid fa-film text-xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">OTT Apps & Screen Cast</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Pre-configure Netflix, YouTube, Prime Video, or enable seamless mobile screen casting for guest entertainment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Clean Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-600/20">
                        <i class="fa-solid fa-tv text-base"></i>
                    </div>
                    <div>
                        <span class="text-slate-900 font-extrabold text-lg tracking-tight">Hotel<span class="text-indigo-600">TV</span></span>
                        <p class="text-[11px] text-slate-400 font-medium">Smart TV System for Luxury Hospitality</p>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-xs font-semibold text-slate-500">© {{ date('Y') }} HotelTV Management System. All rights reserved.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('hotel.login') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200/80 hover:border-indigo-300 text-slate-700 hover:text-indigo-600 text-xs font-bold transition-all shadow-xs">
                        <i class="fa-solid fa-hotel mr-1.5 text-indigo-600"></i> Hotel Admin
                    </a>
                    <a href="{{ route('super-admin.login') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200/80 hover:border-rose-300 text-slate-700 hover:text-rose-600 text-xs font-bold transition-all shadow-xs">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-rose-600"></i> Super Admin
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
