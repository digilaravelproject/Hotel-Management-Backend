@extends('layouts.landing')

@section('title', 'HotelTV - Next Gen Smart TV Solution for Luxury Hospitality')

@section('content')
<div class="relative overflow-hidden bg-slate-900 text-slate-100">
    <!-- Background Glow Gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.25),rgba(255,255,255,0))] pointer-events-none"></div>

    <!-- Header Glassmorphism Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-slate-900/80 border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-tv text-xl text-white"></i>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">
                        Hotel<span class="text-indigo-400">TV</span>
                    </span>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Features</a>
                    <a href="#solutions" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Solutions</a>
                    <a href="#experience" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Guest Experience</a>
                    <a href="#contact" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Contact</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('hotel.login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-4 py-2.5 rounded-lg transition-colors">
                        Hotel Login
                    </a>
                    <a href="{{ route('hotel.login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                        Get Started <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Badge -->
                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span>Next Generation Hospitality TV System</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Transform Room TVs into <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent">Luxury Concierge Hubs</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg sm:text-xl text-slate-400 font-normal leading-relaxed">
                    Streamline guest engagement, OTT app integration, in-room dining services, and custom branding across thousands of Smart TVs seamlessly.
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('hotel.login') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-2xl shadow-xl shadow-indigo-500/25 transition-all hover:-translate-y-1">
                        Launch Hotel Portal <i class="fa-solid fa-rocket ml-2"></i>
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 text-base font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 rounded-2xl transition-all">
                        Explore Features
                    </a>
                </div>
            </div>

            <!-- Hero Image Graphic -->
            <div class="mt-16 relative max-w-5xl mx-auto rounded-3xl overflow-hidden shadow-2xl shadow-indigo-950/50 border border-slate-800">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80" alt="Luxury Hotel Suite TV Interface" class="w-full h-[480px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                
                <!-- Overlay Card Preview -->
                <div class="absolute bottom-8 left-8 right-8 flex flex-col md:flex-row items-center justify-between bg-slate-900/90 backdrop-blur-xl border border-slate-800 p-6 rounded-2xl gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                            <i class="fa-solid fa-sliders text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-base">Real-time Remote Control & OTA</h4>
                            <p class="text-slate-400 text-xs">Push updates, template customizations, and app configs instantly.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6 text-slate-300 text-sm font-semibold">
                        <span><i class="fa-solid fa-check text-emerald-400 mr-1.5"></i> 4K Native Support</span>
                        <span><i class="fa-solid fa-check text-emerald-400 mr-1.5"></i> Custom Branding</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Stats Section -->
    <section class="border-y border-slate-800 bg-slate-950/60 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="space-y-2">
                    <h3 class="text-4xl font-extrabold text-white">500+</h3>
                    <p class="text-slate-400 text-sm font-medium">Hotels Onboarded</p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-extrabold text-indigo-400">25,000+</h3>
                    <p class="text-slate-400 text-sm font-medium">Smart TV Devices Active</p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-extrabold text-purple-400">99.9%</h3>
                    <p class="text-slate-400 text-sm font-medium">Uptime Guarantee</p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-extrabold text-pink-400">24/7</h3>
                    <p class="text-slate-400 text-sm font-medium">Dedicated Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Showcase -->
    <section id="features" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Everything Your Hotel Needs on TV</h2>
                <p class="text-slate-400 text-base">Deliver a seamless digital experience that boosts guest satisfaction and revenue.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-slate-800/50 border border-slate-700/60 rounded-3xl p-8 hover:border-indigo-500/50 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i class="fa-solid fa-tv text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Custom Interactive Templates</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Customize welcome screens, hotel logos, dynamic image sliders, and local weather directly from the Hotel Admin portal.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="bg-slate-800/50 border border-slate-700/60 rounded-3xl p-8 hover:border-purple-500/50 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                        <i class="fa-solid fa-utensils text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">In-Room Dining & Services</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Allow guests to browse food & beverage menus, request housekeeping, or access emergency contacts right from their screen.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="bg-slate-800/50 border border-slate-700/60 rounded-3xl p-8 hover:border-pink-500/50 transition-all hover:-translate-y-1 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400">
                        <i class="fa-solid fa-film text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">OTT Apps & Screen Cast</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Pre-configure Netflix, YouTube, Prime Video, or enable seamless mobile screen casting for guest entertainment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-950 py-12 text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                    <i class="fa-solid fa-tv text-xs"></i>
                </div>
                <span class="text-white font-bold tracking-tight text-lg">HotelTV</span>
            </div>
            <p>© {{ date('Y') }} HotelTV Management System. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="{{ route('hotel.login') }}" class="hover:text-white transition-colors">Hotel Admin</a>
                <a href="{{ route('super.login') }}" class="hover:text-white transition-colors">Super Admin</a>
            </div>
        </div>
    </footer>
</div>
@endsection
