@extends('web.layouts.app')

@section('title', 'Pax TV Launcher - Smart TV Experiences for Hotels')

@section('content')

<!-- 1. HERO SECTION (Matching Reference Image 2 Exactly) -->
<section id="home" 
         class="relative w-full overflow-hidden bg-[#050B18]"
         style="min-height: 740px;">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('uploads/2026/11/PAXTV_Hotel_Banner_1500x700_HD.png') }}" 
             alt="PaxTV Hotel Suite Smart Display" 
             class="w-full h-full object-cover object-center lg:object-[center_right]" />
        
        <!-- Base Dark Overlay (25%) -->
        <div class="absolute inset-0 bg-black/25"></div>

        <!-- Multi-Stop Left-to-Right Dark Gradient for Text Contrast (Crisp typography without darkening the Smart TV) -->
        <div class="absolute inset-0" 
             style="background:linear-gradient(90deg, rgb(5 11 24 / 30%) 0%, rgb(5 11 24 / 30%) 32%, rgba(5, 11, 24, 0.40) 55%, rgba(5, 11, 24, 0.05) 75%, rgba(5, 11, 24, 0) 100%);"></div>

        <!-- Top Gradient for Header Contrast -->
        <div class="absolute inset-0"></div>
    </div>

    <!-- Hero Content Container (Explicit 190px top padding guarantees zero collision with header logo) -->
    <div class="relative z-10 max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 w-full"
         style="padding-top: 190px; padding-bottom: 120px;">
        <div class="max-w-[560px] space-y-4 sm:space-y-5">
            
            <!-- Category Eyebrow in Teal -->
            <div class="text-[#5EC9CB] font-semibold text-xs tracking-[2px] uppercase font-mono">
                PREMIUM HOTEL TV SOLUTION
            </div>

            <!-- Main Exact Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-bold text-white font-heading leading-[1.18] tracking-tight">
                Transform Every Guest<br>
                Stay with Smarter TV<br>
                Experiences
            </h1>

            <!-- Exact Descriptive Subtitle -->
            <p class="text-sm sm:text-base text-slate-200 font-normal leading-[1.7] max-w-[500px]">
                An all-in-one Android TV &amp; Google TV hospitality solution designed to elevate guest entertainment and simplify hotel TV management.
            </p>

            <!-- Dual Exact Action Buttons -->
            <div class="pt-2 flex flex-wrap items-center gap-3.5">
                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-[10px] text-sm sm:text-base font-semibold text-[#0E2556] bg-white hover:bg-slate-100 transition-all duration-200 shadow-xl hover:scale-[1.02] cursor-pointer">
                    <span>Request Demo</span>
                </button>

                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-[10px] text-sm sm:text-base font-semibold text-white bg-black/25 hover:bg-white/10 backdrop-blur-md border border-white/30 transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-play text-xs mr-2 text-white"></i>
                    <span>Watch Video</span>
                </button>
            </div>

        </div>
    </div>
</section>


<!-- 2. VALUE PROPOSITION FEATURE BAR (Exact Match to Reference Design) -->
<section class="relative z-20 w-full" 
         style="background-image: linear-gradient(160deg, #F5FAFB 0%, #E2F1F2 100%);">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8"
         style="padding-top: 50px; padding-bottom: 50px;">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 items-center">
            
            <!-- Feature 1: Customizable UI -->
            <div class="flex items-center space-x-3.5 group">
                <div class="w-[54px] h-[54px] rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-105"
                     style="background-color: #0E2556; box-shadow: 0 8px 18px -6px rgba(14, 37, 86, 0.45);">
                    <i class="fas fa-sliders-h text-xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-[15px] sm:text-[16px] text-[#0E2556] leading-snug">
                        Customizable UI
                    </h3>
                    <p class="font-sans text-[13px] text-[#475569] leading-relaxed mt-0.5">
                        Branded experience for your hotel
                    </p>
                </div>
            </div>

            <!-- Feature 2: Multi-Language -->
            <div class="flex items-center space-x-3.5 group">
                <div class="w-[54px] h-[54px] rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-105"
                     style="background-color: #0E2556; box-shadow: 0 8px 18px -6px rgba(14, 37, 86, 0.45);">
                    <i class="far fa-comments text-xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-[15px] sm:text-[16px] text-[#0E2556] leading-snug">
                        Multi-Language
                    </h3>
                    <p class="font-sans text-[13px] text-[#475569] leading-relaxed mt-0.5">
                        Global guest support
                    </p>
                </div>
            </div>

            <!-- Feature 3: Hotel Information -->
            <div class="flex items-center space-x-3.5 group">
                <div class="w-[54px] h-[54px] rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-105"
                     style="background-color: #0E2556; box-shadow: 0 8px 18px -6px rgba(14, 37, 86, 0.45);">
                    <i class="far fa-building text-xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-[15px] sm:text-[16px] text-[#0E2556] leading-snug">
                        Hotel Information
                    </h3>
                    <p class="font-sans text-[13px] text-[#475569] leading-relaxed mt-0.5">
                        Showcase amenities and services
                    </p>
                </div>
            </div>

            <!-- Feature 4: Secure & Reliable -->
            <div class="flex items-center space-x-3.5 group">
                <div class="w-[54px] h-[54px] rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-105"
                     style="background-color: #0E2556; box-shadow: 0 8px 18px -6px rgba(14, 37, 86, 0.45);">
                    <i class="fas fa-shield-alt text-xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-[15px] sm:text-[16px] text-[#0E2556] leading-snug">
                        Secure &amp; Reliable
                    </h3>
                    <p class="font-sans text-[13px] text-[#475569] leading-relaxed mt-0.5">
                        Built for performance and guest privacy
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 3. ABOUT US: PAXTV LAUNCHER SECTION (Exact Match to Reference Design) -->
<section id="about" class="w-full bg-white" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Image Showcase -->
            <div class="lg:col-span-6">
                <div class="relative overflow-hidden rounded-[10px]">
                    <img src="{{ asset('uploads/2026/11/About-us.png') }}" 
                         alt="PAXTV Launcher In-Room Hospitality Smart TV" 
                         class="w-full h-auto object-cover rounded-[10px]" />
                </div>
            </div>

            <!-- Right Text Content -->
            <div class="lg:col-span-6 space-y-5">
                <!-- Eyebrow -->
                <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    ABOUT US
                </div>

                <!-- Title -->
                <h2 class="text-3xl sm:text-[37px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                    PAXTV Launcher
                </h2>

                <!-- Paragraph 1 -->
                <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                    PAXTV Launcher is an India-headquartered hospitality technology company focused on transforming hotel entertainment and digital guest experiences. Our smart TV solutions help hotels deliver a fully branded, interactive, and personalised in-room experience.
                </p>

                <!-- Paragraph 2 -->
                <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                    PAXTV Launcher is a smart hotel TV platform that brings live TV, IPTV, OTT applications, multilingual entertainment, and hotel services together in one seamless interface. With screen casting, real-time information, personalised welcome messages, and promotional features, our platform helps hotels enhance guest satisfaction, streamline communication, promote in-house services, and unlock new revenue opportunities.
                </p>

                <!-- Action Button -->
                <div class="pt-2">
                    <a href="#solutions" 
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-[10px] text-[15px] font-semibold text-white bg-[#0E2556] hover:bg-[#1A9C9E] transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-0.5 cursor-pointer">
                        <span>Read More &rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 4. OUR SOLUTIONS SECTION (Exact Match to Reference Design) -->
<section id="solutions" 
         class="relative w-full overflow-hidden" 
         style="background-image: url('{{ asset('uploads/2026/11/our-solution.png') }}'); background-position: top center; background-size: cover; padding-top: 70px; padding-bottom: 70px;">
    
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 68% width on desktop so the smart TV on the right of the background image remains clearly visible -->
        <div class="w-full lg:w-[68%] space-y-6">
            
            <!-- Section Header -->
            <div class="space-y-3">
                <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    TAILORED FOR YOUR HOTEL
                </div>
                <h2 class="text-3xl sm:text-[39px] font-bold text-white font-heading leading-[1.2] tracking-tight">
                    Our Solutions
                </h2>
                <p class="text-[15px] sm:text-[16px] text-[#B0B1B1] font-normal leading-[1.7] max-w-2xl font-sans">
                    A complete in-room entertainment and guest engagement solution designed for modern hospitality needs.
                </p>
            </div>

            <!-- 3 Floating White Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 pt-3">
                
                <!-- Card 1: Hotel TV Application -->
                <div class="bg-white rounded-[18px] p-6 sm:p-7 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                     style="box-shadow: 0px 10px 30px 0px rgba(14, 37, 86, 0.15);">
                    <div class="space-y-4">
                        <div class="w-11 h-11 rounded-[8px] flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 8px 18px -8px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-desktop text-lg text-white"></i>
                        </div>
                        <h4 class="font-heading font-semibold text-[19px] sm:text-[20px] text-[#0E2556] leading-tight">
                            Hotel TV Application
                        </h4>
                        <p class="font-sans text-[13.5px] text-[#475569] leading-[1.6]">
                            Custom branded interface with all essential features.
                        </p>
                    </div>
                    <div class="pt-6">
                        <button type="button" 
                                data-open-popup="popup-hotel-tv"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-[10px] text-[13px] font-semibold text-white bg-[#0E2556] hover:bg-[#1A9C9E] transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer">
                            <span>Learn More &rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Card 2: System Integration -->
                <div class="bg-white rounded-[18px] p-6 sm:p-7 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                     style="box-shadow: 0px 10px 30px 0px rgba(14, 37, 86, 0.15);">
                    <div class="space-y-4">
                        <div class="w-11 h-11 rounded-[8px] flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 8px 18px -8px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-cogs text-lg text-white"></i>
                        </div>
                        <h4 class="font-heading font-semibold text-[19px] sm:text-[20px] text-[#0E2556] leading-tight">
                            System Integration
                        </h4>
                        <p class="font-sans text-[13.5px] text-[#475569] leading-[1.6]">
                            Integrate with hotel PMS, services and third-party apps.
                        </p>
                    </div>
                    <div class="pt-6">
                        <button type="button" 
                                data-open-popup="popup-demo"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-[10px] text-[13px] font-semibold text-white bg-[#0E2556] hover:bg-[#1A9C9E] transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer">
                            <span>Learn More &rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Ongoing Support -->
                <div class="bg-white rounded-[18px] p-6 sm:p-7 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                     style="box-shadow: 0px 10px 30px 0px rgba(14, 37, 86, 0.15);">
                    <div class="space-y-4">
                        <div class="w-11 h-11 rounded-[8px] flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 8px 18px -8px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-headset text-lg text-white"></i>
                        </div>
                        <h4 class="font-heading font-semibold text-[19px] sm:text-[20px] text-[#0E2556] leading-tight">
                            Ongoing Support
                        </h4>
                        <p class="font-sans text-[13.5px] text-[#475569] leading-[1.6]">
                            Dedicated support and regular updates for seamless operation.
                        </p>
                    </div>
                    <div class="pt-6">
                        <button type="button" 
                                data-open-popup="popup-demo"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-[10px] text-[13px] font-semibold text-white bg-[#0E2556] hover:bg-[#1A9C9E] transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer">
                            <span>Learn More &rarr;</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>


<!-- 5. FEATURE COMPARISON MATRIX ("GO BEYOND ENTERTAINMENT") (Exact Match to Reference Design) -->
<section class="relative w-full" 
         style="background-image: linear-gradient(160deg, #F5FAFB 0%, #E2F1F2 100%); padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Left-Aligned Section Header -->
        <div class="max-w-3xl space-y-3 mb-10">
            <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                SEE WHAT MAKES PAX TV DIFFERENT
            </div>
            <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                Go Beyond Entertainment. Deliver a Connected Guest Experience.
            </h2>
            <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                Modern hospitality deserves more than conventional television. Pax TV Hospitality, powered by Pax TV Launcher, brings entertainment, hotel information, and guest-facing services together through an intuitive TV experience.
            </p>
        </div>

        <!-- 3-Column Comparison Table Card (Matching User Reference Image Exactly) -->
        <div class="bg-white rounded-[18px] overflow-hidden shadow-lg border border-slate-200/60">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[760px]">
                    <thead>
                        <tr class="border-b border-slate-200/60">
                            <th class="py-5 px-6 text-xs font-bold text-[#0E2556] uppercase tracking-wider w-[32%] font-heading">
                                EXPERIENCE &amp; CAPABILITIES
                            </th>
                            <th class="py-5 px-6 text-xs font-bold text-white uppercase tracking-wider w-[36%] bg-[#0E2556] font-heading">
                                PAX TV HOSPITALITY
                            </th>
                            <th class="py-5 px-6 text-xs font-bold text-[#64748B] uppercase tracking-wider w-[32%] font-heading">
                                CONVENTIONAL HOTEL TV
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[14px]">
                        <!-- Row 1: Guest Experience -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Guest Experience
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Interactive, personalised viewing experience</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Primarily standard television viewing
                            </td>
                        </tr>

                        <!-- Row 2: Brand Identity -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Brand Identity
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Custom-branded TV interface</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Limited branding flexibility
                            </td>
                        </tr>

                        <!-- Row 3: Hotel Information -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Hotel Information
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Property details and guest information on screen</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Printed guides or separate communication
                            </td>
                        </tr>

                        <!-- Row 4: Content Management -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Content Management
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Centralised content control</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Often managed individually across devices
                            </td>
                        </tr>

                        <!-- Row 5: TV Navigation -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                TV Navigation
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Hospitality-focused launcher interface</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Standard TV navigation
                            </td>
                        </tr>

                        <!-- Row 6: Hotel Services -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Hotel Services
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Convenient on-screen service discovery</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Often requires separate channels or assistance
                            </td>
                        </tr>

                        <!-- Row 7: Multi-Room Management -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-[#0E2556] font-semibold">
                                Multi-Room Management
                            </td>
                            <td class="py-4 px-6 text-[#0E2556] font-medium bg-[#F0F8FA]/70">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-circle-check text-[#1A9C9E] text-base shrink-0"></i>
                                    <span>Designed for streamlined deployment across rooms</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                Typically involves more manual configuration
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>


<!-- 6. MULTI LANGUAGE SUPPORT SECTION (Exact Match to Reference Design) -->
<section class="relative w-full overflow-hidden" 
         style="background-image: linear-gradient(135deg, #0E2556 0%, #146A82 100%); padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Text Content & Language Badges (58% width on desktop) -->
            <div class="lg:col-span-7 space-y-5">
                <div class="text-[#5EC9CB] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    GLOBAL GUEST EXPERIENCE
                </div>

                <h2 class="text-3xl sm:text-[39px] font-bold text-white font-heading leading-[1.2] tracking-tight">
                    Multi-Language Support
                </h2>

                <p class="text-[15px] sm:text-[16px] text-[#CBD5E1] font-normal leading-[1.7] font-sans">
                    Cater to international guests with multiple language options and easy navigation.
                </p>

                <!-- Language Pill Buttons Grid -->
                <div class="pt-2 flex flex-wrap gap-2.5">
                    <!-- Active Language: English -->
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white text-[#0E2556] shadow-sm transition-all duration-200">
                        English
                    </button>
                    
                    <!-- Other Languages -->
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        हिंदी
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        मराठी
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        ગુજરાતી
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        தமிழ்
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        ಕನ್ನಡ
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        తెలుగు
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        বাংলা
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        Français
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        Deutsch
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        Español
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        Português
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        中文
                    </button>
                    <button type="button" 
                            class="px-4.5 py-2 rounded-[10px] text-[13px] font-semibold bg-white/[0.04] text-white border border-white/45 hover:bg-white hover:text-[#0E2556] transition-all duration-200">
                        عربى
                    </button>
                </div>
            </div>

            <!-- Right Mockup Image (42% width on desktop) -->
            <div class="lg:col-span-5">
                <div class="relative rounded-[10px] overflow-hidden shadow-2xl">
                    <img src="{{ asset('uploads/2026/11/Sunset-Resort-Language-Selection-UI-1-1-1.png') }}" 
                         alt="PaxTV Language Switcher Interface" 
                         class="w-full h-auto object-cover rounded-[10px]" />
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 7. HOTEL INFORMATION & SERVICES SECTION (Light Theme: White Background) -->
<section id="features" class="w-full bg-white" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Mockup Image -->
            <div class="lg:col-span-6 order-2 lg:order-1">
                <div class="relative rounded-[12px] overflow-hidden shadow-xl border border-slate-200/60">
                    <img src="{{ asset('uploads/2026/11/hotel-information-service.png') }}" 
                         alt="PaxTV Hotel Services UI" 
                         class="w-full h-auto object-cover rounded-[12px]" />
                </div>
            </div>

            <!-- Right Content & Circular Feature Badges -->
            <div class="lg:col-span-6 order-1 lg:order-2 space-y-5">
                <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    EVERYTHING YOUR GUESTS NEED
                </div>

                <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                    Hotel Information &amp; Services
                </h2>

                <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                    Showcase hotel amenities, dining options, facilities and local attractions directly on the TV.
                </p>

                <!-- 6 Features Grid (2 columns on mobile, 3 on tablet/desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3">
                    
                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="far fa-building text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Hotel Information</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="far fa-map text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">City Guide</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-utensils text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Dining &amp; Food</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-landmark text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Local Attractions</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-concierge-bell text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">In-Room Services</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-[#F5FAFB] border border-[#E2F1F2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-plane text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Flight Information</span>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>


<!-- 8. LIVE TV & ENTERTAINMENT SECTION (Light Theme Gradient) -->
<section class="relative w-full" 
         style="background-image: linear-gradient(160deg, #F5FAFB 0%, #E2F1F2 100%); padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Text Content & Feature Pills -->
            <div class="lg:col-span-6 space-y-5">
                <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    NON-STOP ENTERTAINMENT
                </div>

                <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                    Live TV &amp; Entertainment
                </h2>

                <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                    Deliver seamless live TV, movies and guest entertainment with an intuitive interface.
                </p>

                <!-- 6 Features Grid (2 columns on mobile, 3 on tablet/desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3">
                    
                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-tv text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Live TV Channels</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-film text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">On-Demand Movies</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-globe text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Web Applications</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fab fa-chromecast text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Screen Cast</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-heart text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">Recommendations</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-[12px] bg-white border border-slate-200/70 shadow-sm">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #0E2556; box-shadow: 0 4px 12px -3px rgba(14, 37, 86, 0.4);">
                            <i class="fas fa-play text-sm text-white"></i>
                        </div>
                        <span class="text-[13.5px] font-semibold text-[#0E2556]">OTT &amp; Streaming</span>
                    </div>

                </div>

            </div>

            <!-- Right Mockup Image -->
            <div class="lg:col-span-6">
                <div class="relative rounded-[12px] overflow-hidden shadow-xl border border-slate-200/60">
                    <img src="{{ asset('uploads/2026/11/Live-TV-ent.png') }}" 
                         alt="PaxTV Live Entertainment UI" 
                         class="w-full h-auto object-cover rounded-[12px]" />
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 9. GALLERY SECTION (Light Theme: White Background) -->
<section id="gallery" class="w-full bg-white" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl space-y-3 mb-10">
            <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                REAL IMPLEMENTATIONS
            </div>
            <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                Gallery
            </h2>
            <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                See how our Hotel TV solution enhances guest experiences in premium properties worldwide.
            </p>
        </div>

        <!-- Gallery Grid (1 Large Image on Left, 4 Smaller Images in 2x2 Grid on Right matching Elementor) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <!-- Left Large Image -->
            <div class="lg:col-span-5 rounded-[12px] overflow-hidden shadow-md border border-slate-200/60">
                <img src="{{ asset('uploads/2026/11/Luxury-Suite-at-Tropical-Sunset-e1791468262686-1-1-1.png') }}" 
                     alt="Luxury Suite PaxTV" 
                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
            </div>

            <!-- Right 4 Images in 2x2 Grid -->
            <div class="lg:col-span-7 grid grid-cols-2 gap-5">
                <div class="rounded-[12px] overflow-hidden shadow-md border border-slate-200/60 aspect-video">
                    <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_19-PM-4-1-1-1.png') }}" 
                         alt="PaxTV Room Showcase" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                </div>
                <div class="rounded-[12px] overflow-hidden shadow-md border border-slate-200/60 aspect-video">
                    <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_17-PM-2-1-1-1.png') }}" 
                         alt="PaxTV Room Showcase" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                </div>
                <div class="rounded-[12px] overflow-hidden shadow-md border border-slate-200/60 aspect-video">
                    <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_18-PM-3-1-1-1.png') }}" 
                         alt="PaxTV Room Showcase" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                </div>
                <div class="rounded-[12px] overflow-hidden shadow-md border border-slate-200/60 aspect-video">
                    <img src="{{ asset('uploads/2026/11/ChatGPT-Image-Oct-8-2026-07_25_15-PM-1-1-1-1.png') }}" 
                         alt="PaxTV Room Showcase" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 10. TRUSTED BY HOTELS (Light Theme Gradient) -->
<section class="relative w-full" 
         style="background-image: linear-gradient(160deg, #F5FAFB 0%, #E2F1F2 100%); padding-top: 60px; padding-bottom: 60px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl space-y-3 mb-10">
            <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                WHAT HOTELIERS SAY
            </div>
            <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                Trusted By Hotels
            </h2>
            <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                Hear from hotel teams who have upgraded their in-room guest experience with PAX TV.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Review 1 -->
            <div class="bg-white rounded-[16px] p-6 border border-slate-200/60 shadow-md flex flex-col justify-between space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <p class="text-[14px] text-[#475569] leading-relaxed italic">
                    "Guests find the TV interface simple and our hotel information is always up to date. It has become part of the in-room experience."
                </p>
                <div class="pt-3 border-t border-slate-100">
                    <div class="text-[14px] font-bold text-[#0E2556]">General Manager</div>
                    <div class="text-[12px] text-[#15888A] font-medium">City Business Hotel</div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white rounded-[16px] p-6 border border-slate-200/60 shadow-md flex flex-col justify-between space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <p class="text-[14px] text-[#475569] leading-relaxed italic">
                    "Multi-language support made a clear difference for our international guests. We now get far fewer questions at the front desk."
                </p>
                <div class="pt-3 border-t border-slate-100">
                    <div class="text-[14px] font-bold text-[#0E2556]">Front Office Manager</div>
                    <div class="text-[12px] text-[#15888A] font-medium">Boutique Hotel</div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white rounded-[16px] p-6 border border-slate-200/60 shadow-md flex flex-col justify-between space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <p class="text-[14px] text-[#475569] leading-relaxed italic">
                    "The installation was smooth and the team supported us even after go-live. The system has been stable and reliable."
                </p>
                <div class="pt-3 border-t border-slate-100">
                    <div class="text-[14px] font-bold text-[#0E2556]">Operations Head</div>
                    <div class="text-[12px] text-[#15888A] font-medium">Resort &amp; Spa</div>
                </div>
            </div>

            <!-- Review 4 -->
            <div class="bg-white rounded-[16px] p-6 border border-slate-200/60 shadow-md flex flex-col justify-between space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <p class="text-[14px] text-[#475569] leading-relaxed italic">
                    "Screen casting and live channels are used by almost every guest. Our in-room services orders have also improved."
                </p>
                <div class="pt-3 border-t border-slate-100">
                    <div class="text-[14px] font-bold text-[#0E2556]">IT Manager</div>
                    <div class="text-[12px] text-[#15888A] font-medium">Business Hotel Chain</div>
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
