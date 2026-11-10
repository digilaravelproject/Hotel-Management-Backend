@extends('web.layouts.app')

@section('title', 'Pax TV Launcher - Smart TV Experiences for Hotels')

@section('content')

<!-- 1. HERO SECTION (Matching Reference Image Exactly) -->
<section id="home" class="relative min-h-[90vh] lg:min-h-[96vh] flex items-center overflow-hidden bg-[#050B18]">
    <!-- Background Image with Subtle Shading -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('uploads/2026/11/PAXTV_Hotel_Banner_1500x700_HD.png') }}" 
             alt="PaxTV Hotel Suite Smart Display" 
             class="w-full h-full object-cover object-center filter brightness-[0.92] contrast-[1.04]" />
        <!-- Soft Gradient Shading for Text Legibility while preserving the Smart TV sharpness on the right -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#050B18] via-transparent to-black/30"></div>
    </div>

    <!-- Hero Content Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 sm:pt-40 lg:pt-44 pb-20 sm:pb-28 w-full">
        <div class="max-w-xl lg:max-w-2xl space-y-5">
            
            <!-- Category Eyebrow in Teal -->
            <div class="text-[#14B8A6] font-bold text-xs sm:text-sm tracking-[0.18em] uppercase font-mono">
                PREMIUM HOTEL TV SOLUTION
            </div>

            <!-- Main Exact Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-extrabold text-white font-heading leading-[1.14] tracking-tight">
                Transform Every Guest<br>
                Stay with Smarter TV<br>
                Experiences
            </h1>

            <!-- Exact Descriptive Subtitle -->
            <p class="text-sm sm:text-base text-slate-200 font-normal leading-relaxed max-w-lg">
                An all-in-one Android TV & Google TV hospitality solution designed to elevate guest entertainment and simplify hotel TV management.
            </p>

            <!-- Dual Exact Action Buttons -->
            <div class="pt-3 flex flex-wrap items-center gap-4">
                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm sm:text-base font-bold text-slate-900 bg-white hover:bg-slate-100 transition-all duration-200 shadow-xl hover:scale-[1.02] cursor-pointer">
                    <span>Request Demo</span>
                </button>

                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-play text-xs mr-2 text-white"></i>
                    <span>Watch Video</span>
                </button>
            </div>

        </div>
    </div>
</section>


<!-- 2. STATS RIBBON SECTION -->
<section class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="glass-panel rounded-2xl p-6 sm:p-8 shadow-2xl shadow-black/50 border border-white/10 bg-[#0B1E38]/90">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8 divide-y md:divide-y-0 md:divide-x divide-white/10">
            
            <!-- Metric 1 -->
            <div class="pt-4 md:pt-0 md:px-4 text-center">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-cyan-400 font-heading">
                    500+
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-300 mt-1">
                    Hotels Worldwide
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="pt-4 md:pt-0 md:px-4 text-center">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-cyan-400 font-heading">
                    50,000+
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-300 mt-1">
                    Active Smart TVs
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="pt-4 md:pt-0 md:px-4 text-center">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-cyan-400 font-heading">
                    1.2M+
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-300 mt-1">
                    Guests Entertained
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="pt-4 md:pt-0 md:px-4 text-center">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-cyan-400 font-heading">
                    99.8%
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-300 mt-1">
                    Cloud System Uptime
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 3. ABOUT US: PAXTV LAUNCHER SECTION -->
<section id="about" class="py-20 lg:py-28 bg-[#050B18]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Image Showcase -->
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="{{ asset('uploads/2026/11/About-us.png') }}" 
                         alt="PaxTV Hotel Experience Management" 
                         class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050B18]/60 via-transparent to-transparent"></div>
                </div>
                <!-- Ambient Backdrop Glow -->
                <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-[#1A9C9E]/20 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- Right Text Content -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                    <i class="fa-solid fa-hotel text-[10px]"></i>
                    <span>About Us</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading leading-tight tracking-tight">
                    PAXTV Launcher
                </h2>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                    PaxTV Launcher transforms standard guest room displays into intelligent interactive hubs. Our cloud-managed platform gives hotel operators full control over guest entertainment, hotel branding, personalized messaging, and contactless digital guest services.
                </p>

                <p class="text-sm sm:text-base text-slate-400 leading-relaxed">
                    With zero proprietary hardware lock-in, native support for Android TV and Google TV, and seamless integration with existing hotel PMS, PaxTV delivers a five-star hospitality experience right out of the box.
                </p>

                <!-- Value Props Checklist -->
                <div class="pt-2 space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 text-[#22B5B7] flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm text-slate-300"><strong>Zero Expensive Hardware:</strong> Deploy directly onto your existing smart TVs without bulky external boxes.</span>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 text-[#22B5B7] flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm text-slate-300"><strong>Over-the-Air Control:</strong> Update hotel branding, promos, and in-room menus across all TVs in seconds from anywhere.</span>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 text-[#22B5B7] flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm text-slate-300"><strong>Automated Privacy:</strong> Automatic credential wipe and cache clearing on every guest checkout.</span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="button" 
                            data-open-popup="popup-demo"
                            class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-lg shadow-[#1A9C9E]/30 cursor-pointer">
                        <span>Discover More</span>
                        <i class="fas fa-chevron-right ml-2 text-xs"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 4. OUR SOLUTIONS SECTION -->
<section id="solutions" class="py-20 lg:py-28 bg-[#08152B] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="max-w-3xl space-y-4 mb-14">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                <i class="fa-solid fa-shapes text-[10px]"></i>
                <span>Customized Hospitality Suite</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight">
                Our Solutions
            </h2>
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                Custom-tailored solutions designed to meet the distinct entertainment and service needs of luxury hotels, boutique stays, and hospitality chains.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left 3 Solution Cards -->
            <div class="lg:col-span-5 space-y-4">
                
                <!-- Card 1: Hotel TV Launcher -->
                <div class="glass-card rounded-2xl p-5 sm:p-6 bg-[#0E2556]/60 border border-white/10 hover:border-[#1A9C9E]/60 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center text-lg mb-3">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white font-heading">
                        Hotel TV Launcher
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1.5 leading-relaxed">
                        Custom-branded launcher interfaces with personalized guest welcome, express checkout, room directory, and fast app switching.
                    </p>
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <button type="button" 
                                data-open-popup="popup-hotel-tv"
                                class="inline-flex items-center text-xs font-bold text-[#22B5B7] hover:text-white transition-colors cursor-pointer">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-1.5 text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Live Streaming & OTT Hub -->
                <div class="glass-card rounded-2xl p-5 sm:p-6 bg-[#0E2556]/60 border border-white/10 hover:border-[#1A9C9E]/60 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center text-lg mb-3">
                        <i class="fa-solid fa-play"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white font-heading">
                        Live Streaming & OTT Hub
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1.5 leading-relaxed">
                        Secure guest casting, pre-loaded streaming apps with auto-credential wipe upon checkout for 100% privacy and security.
                    </p>
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <button type="button" 
                                data-open-popup="popup-demo"
                                class="inline-flex items-center text-xs font-bold text-[#22B5B7] hover:text-white transition-colors cursor-pointer">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-1.5 text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Ongoing Support & Maintenance -->
                <div class="glass-card rounded-2xl p-5 sm:p-6 bg-[#0E2556]/60 border border-white/10 hover:border-[#1A9C9E]/60 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center text-lg mb-3">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white font-heading">
                        Ongoing Support & Maintenance
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1.5 leading-relaxed">
                        24/7 dedicated hospitality engineering support, remote cloud management, hardware health monitoring, and instant over-the-air updates.
                    </p>
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <button type="button" 
                                data-open-popup="popup-support"
                                class="inline-flex items-center text-xs font-bold text-[#22B5B7] hover:text-white transition-colors cursor-pointer">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-1.5 text-[10px]"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Showcase TV Mockup -->
            <div class="lg:col-span-7">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="{{ asset('uploads/2026/11/Luxury-Teal-Smart-Hotel-Suite-1-1.png') }}" 
                         alt="PaxTV Luxury Suite Display" 
                         class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050B18]/50 via-transparent to-transparent"></div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- 5. FEATURE COMPARISON MATRIX ("GO BEYOND ENTERTAINMENT") -->
<section class="py-20 lg:py-28 bg-[#050B18]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                <i class="fa-solid fa-scale-balanced text-[10px]"></i>
                <span>The Future of Hotel TV</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight leading-tight">
                Go Beyond Entertainment.<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-cyan-400">Deliver a Connected Guest Experience.</span>
            </h2>
            <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">
                PaxTV replaces legacy cable and fragmented set-top boxes with a unified, high-performance cloud TV platform built specifically for the modern hotel.
            </p>
        </div>

        <!-- 3-Column Comparison Table -->
        <div class="overflow-x-auto">
            <div class="min-w-[700px] glass-panel rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-[#0B1E38]/80">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/5">
                            <th class="py-5 px-6 text-sm font-bold text-slate-300 uppercase tracking-wider w-2/5">
                                Features & Capabilities
                            </th>
                            <th class="py-5 px-6 text-sm font-black text-teal-300 uppercase tracking-wider w-3/10 bg-[#1A9C9E]/15 border-x border-[#1A9C9E]/30">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-crown text-amber-400"></i>
                                    <span>PaxTV Smart Cloud</span>
                                </div>
                            </th>
                            <th class="py-5 px-6 text-sm font-bold text-slate-400 uppercase tracking-wider w-3/10">
                                Legacy Cable & Traditional DTH
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        <!-- Row 1 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Centralized Cloud Management & Instant OTA Updates
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> Real-time (1-Click Sync)
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Manual USB / Technician Visit
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Custom Hotel Branding & Personalized Welcome Greeting
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> PMS Dynamic Sync
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Generic / Not Available
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Hardware Requirements
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> Runs on Android & Google TV
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Expensive Proprietary Boxes
                            </td>
                        </tr>

                        <!-- Row 4 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Built-in OTT & Casting Privacy (Auto-Wipe on Checkout)
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> 100% Secure Auto-Wipe
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Not Supported
                            </td>
                        </tr>

                        <!-- Row 5 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Interactive Hotel Services (Dining, Spa, Concierge)
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> Digital Interactive Menus
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Paper Directories Only
                            </td>
                        </tr>

                        <!-- Row 6 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Live Flight Radar & Real-Time Weather Widgets
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> Live API Integrated
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Not Supported
                            </td>
                        </tr>

                        <!-- Row 7 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Multilingual Interface (10+ Languages)
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> 1-Click Guest Switch
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Single Fixed Language
                            </td>
                        </tr>

                        <!-- Row 8 -->
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 text-slate-200 font-medium">
                                Remote Diagnostics & Device Telemetry
                            </td>
                            <td class="py-4 px-6 font-bold text-teal-300 bg-[#1A9C9E]/10 border-x border-[#1A9C9E]/20">
                                <i class="fa-solid fa-circle-check text-teal-400 mr-2"></i> 24/7 Cloud Monitoring
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                <i class="fa-solid fa-circle-xmark text-slate-500 mr-2"></i> Guest Complaint Required
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>


<!-- 6. MULTI LANGUAGE SUPPORT SECTION -->
<section class="py-20 lg:py-28 bg-[#08152B] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Text Content & Language Badges -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                    <i class="fa-solid fa-globe text-[10px]"></i>
                    <span>Global Guest Hospitality</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight leading-tight">
                    Multi Language Support
                </h2>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                    Welcome international guests in their native language with one-touch interface localization. Eliminate language friction and provide a comfortable home-like in-room environment.
                </p>

                <!-- Language Pill Badges Grid -->
                <div class="pt-2 flex flex-wrap gap-2 sm:gap-2.5">
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20 shadow-sm">
                        English
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                        Español
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        Français
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        Deutsch
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                        العربية
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        日本語
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        中文 (Chinese)
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        Русский
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                        Português
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        Italiano
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        हिन्दी (Hindi)
                    </span>
                </div>

                <div class="pt-4 flex items-center space-x-6 text-xs text-slate-400">
                    <span class="flex items-center"><i class="fa-solid fa-check text-teal-400 mr-2"></i> Auto-detect from PMS reservation</span>
                    <span class="flex items-center"><i class="fa-solid fa-check text-teal-400 mr-2"></i> 1-touch remote toggle</span>
                </div>
            </div>

            <!-- Right Mockup Image -->
            <div class="lg:col-span-6">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="{{ asset('uploads/2026/11/Sunset-Resort-Language-Selection-UI-1-1-1.png') }}" 
                         alt="PaxTV Language Switcher Interface" 
                         class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#08152B]/50 via-transparent to-transparent"></div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 7. HOTEL INFORMATION & SERVICES SECTION -->
<section id="features" class="py-20 lg:py-28 bg-[#050B18]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Mockup Image -->
            <div class="lg:col-span-6 order-2 lg:order-1">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="{{ asset('uploads/2026/11/hotel-information-service.png') }}" 
                         alt="PaxTV Hotel Services UI" 
                         class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050B18]/50 via-transparent to-transparent"></div>
                </div>
            </div>

            <!-- Right Content & Circular Feature Badges -->
            <div class="lg:col-span-6 order-1 lg:order-2 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                    <i class="fa-solid fa-bell-concierge text-[10px]"></i>
                    <span>Guest Services & Amenities</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight leading-tight">
                    Hotel Information & Services
                </h2>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                    Empower guests to explore dining, room amenities, spa bookings, local city guides, and hotel policies directly on their TV without calling front desk.
                </p>

                <!-- 6 Circular Features in 3x2 Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                    
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Hotel Info</h4>
                        <p class="text-[11px] text-slate-400">Overview, contact & emergency desk</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-bed"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Room Info</h4>
                        <p class="text-[11px] text-slate-400">Specifications & amenities list</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Amenities</h4>
                        <p class="text-[11px] text-slate-400">Dining, pool, gym & spa hours</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Our City Guide</h4>
                        <p class="text-[11px] text-slate-400">Top tourist spots & dining guide</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Flights Radar</h4>
                        <p class="text-[11px] text-slate-400">Live flight arrival & departures</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-white/10 text-center space-y-2 hover:border-[#1A9C9E]/50 transition-all">
                        <div class="w-12 h-12 mx-auto rounded-full bg-[#1A9C9E]/20 text-teal-300 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-cloud-sun"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Weather</h4>
                        <p class="text-[11px] text-slate-400">Local live weather updates</p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>


<!-- 8. LIVE TV & ENTERTAINMENT SECTION -->
<section class="py-20 lg:py-28 bg-[#08152B] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Text Content & Feature Pills -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                    <i class="fa-solid fa-satellite-dish text-[10px]"></i>
                    <span>Seamless Streaming & Broadcast</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight leading-tight">
                    Live TV & Entertainment
                </h2>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                    Deliver flawless IPTV, satellite channels, and popular OTT streaming apps like Netflix, YouTube, Prime Video, and Disney+ with crystal clear 4K picture quality.
                </p>

                <!-- Features Grid -->
                <div class="grid grid-cols-2 gap-3.5 pt-2">
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-tv text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">IPTV Broadcast Channels</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-film text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">4K Ultra-HD Streaming</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-mobile-screen text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">Wireless Screen Cast</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-list-check text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">Custom Channel EPG Guide</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-shield-halved text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">Auto-Wipe Privacy</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                        <i class="fa-solid fa-lock text-[#22B5B7] text-base"></i>
                        <span class="text-xs font-bold text-slate-200">Parental PIN Control</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" 
                            data-open-popup="popup-demo"
                            class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-lg shadow-[#1A9C9E]/30 cursor-pointer">
                        <span>Get Channel Demo</span>
                        <i class="fas fa-chevron-right ml-2 text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Right Mockup Image -->
            <div class="lg:col-span-6">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="{{ asset('uploads/2026/11/Live-TV-ent.png') }}" 
                         alt="PaxTV Live Entertainment UI" 
                         class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#08152B]/50 via-transparent to-transparent"></div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 9. GALLERY SECTION -->
<section id="gallery" class="py-20 lg:py-28 bg-[#050B18]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-14">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                <i class="fa-solid fa-images text-[10px]"></i>
                <span>Real-World Installations</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight">
                Gallery
            </h2>
            <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Explore PaxTV Launcher powering smart in-room displays in premier resorts, boutique destinations, and global hotel properties.
            </p>
        </div>

        <!-- Gallery Grid Matching Reference Design -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/Twilight-Luxury-Resort-by-the-Sea-1-1-1.png') }}" 
                     alt="Oceanfront Luxury Suite" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Oceanfront Resort Suite Display</span>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/Luxury-Teal-Smart-Hotel-Suite-1-1.png') }}" 
                     alt="Executive Smart TV Setup" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Executive Suite Smart Display</span>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/About-us.png') }}" 
                     alt="Hospitality In-Room Display" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Five-Star Guest Room Integration</span>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-1-1-1.png') }}" 
                     alt="Luxury Bedroom Smart TV Setup" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Deluxe King Room Ambient Setup</span>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-1-1-1.png') }}" 
                     alt="Presidential Suite Smart TV" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Presidential Suite Display</span>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10 group aspect-4/3">
                <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-1-1-1.png') }}" 
                     alt="Boutique Hotel In-Room TV" 
                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                    <span class="text-xs font-bold text-white">Boutique Resort TV Architecture</span>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 10. TRUSTED BY HOTELS (TESTIMONIALS) -->
<section class="py-20 lg:py-28 bg-[#08152B]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-bold text-teal-400 font-mono uppercase tracking-wider">
                <i class="fa-solid fa-star text-[10px]"></i>
                <span>Customer Stories</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-heading tracking-tight">
                Trusted By Hotels
            </h2>
            <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Join hundreds of forward-thinking hotels elevating their guest experience and simplifying in-room technology with PaxTV.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Review 1 -->
            <div class="glass-card rounded-2xl p-6 bg-[#0E2556]/60 border border-white/10 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic">
                        "PaxTV Launcher has made our guest entertainment intuitive and beautiful. In-room dining orders increased by 28% after displaying digital menus on the TV screens."
                    </p>
                </div>
                <div class="pt-3 border-t border-white/10">
                    <div class="text-xs font-bold text-white">Rajesh Malhotra</div>
                    <div class="text-[11px] text-teal-300">General Manager, Grand Heritage Mumbai</div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="glass-card rounded-2xl p-6 bg-[#0E2556]/60 border border-white/10 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic">
                        "Deploying PaxTV on 180 smart TVs took less than a day. Our guests love the fast casting and automatic Netflix credential wipe upon checkout."
                    </p>
                </div>
                <div class="pt-3 border-t border-white/10">
                    <div class="text-xs font-bold text-white">Ananya Sen</div>
                    <div class="text-[11px] text-teal-300">Operations Director, SeaView Resort Goa</div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="glass-card rounded-2xl p-6 bg-[#0E2556]/60 border border-white/10 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic">
                        "The live flight radar widget is a huge hit among our business travelers heading to Kempegowda Airport. The best hospitality tech investment this year."
                    </p>
                </div>
                <div class="pt-3 border-t border-white/10">
                    <div class="text-xs font-bold text-white">Vikramaditya Rao</div>
                    <div class="text-[11px] text-teal-300">IT Head, Silicon City Hotel Bengaluru</div>
                </div>
            </div>

            <!-- Review 4 -->
            <div class="glass-card rounded-2xl p-6 bg-[#0E2556]/60 border border-white/10 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs space-x-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic">
                        "Centralized cloud control saved our engineering team countless hours. We update seasonal promotions across all rooms in seconds from the web dashboard."
                    </p>
                </div>
                <div class="pt-3 border-t border-white/10">
                    <div class="text-xs font-bold text-white">Carlos Mendes</div>
                    <div class="text-[11px] text-teal-300">Resort Director, Azure Palms Maldives</div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 11. CTA BANNER ("REQUEST A DEMO TODAY") -->
<section class="relative py-24 lg:py-32 overflow-hidden">
    <!-- Background Image with Gradient Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('uploads/2026/11/Twilight-Luxury-Resort-by-the-Sea-1-1-1.png') }}" 
             alt="Twilight Luxury Resort" 
             class="w-full h-full object-cover object-center filter brightness-[0.6] contrast-[1.1]" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#050B18]/95 via-[#050B18]/85 to-[#050B18]/70"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-teal-500/15 border border-teal-500/25 text-xs font-bold text-teal-300 font-mono uppercase tracking-wider">
            <i class="fa-solid fa-sparkles text-[10px]"></i>
            <span>Get Started Today</span>
        </div>

        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white font-heading tracking-tight leading-tight">
            Request a Demo Today
        </h2>

        <p class="text-base sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Discover how PaxTV transforms your hotel TVs into an interactive, revenue-generating guest channel with zero hardware lock-in.
        </p>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <button type="button" 
                    data-open-popup="popup-demo"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-full text-base font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-xl shadow-[#1A9C9E]/35 hover:scale-[1.02] cursor-pointer">
                <span>Request a Demo</span>
                <i class="fas fa-chevron-right ml-2.5 text-xs"></i>
            </button>

            <a href="{{ route('hotel.register') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 rounded-full text-base font-bold text-teal-300 hover:text-white bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/20 transition-all duration-200">
                <i class="fa-solid fa-hotel mr-2 text-sm"></i>
                <span>Register Hotel Property</span>
            </a>
        </div>
    </div>
</section>

@endsection
