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


<!-- 7. HOTEL INFORMATION & SERVICES SECTION (Light Ice-Blue Gradient matching Reference Image 1:1) -->
<section id="features" class="relative w-full" 
         style="background-image: linear-gradient(160deg, #F5FAFB 0%, #E2F1F2 100%); padding-top: 70px; padding-bottom: 70px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Image Showcase -->
            <div class="lg:col-span-6 order-2 lg:order-1">
                <div class="relative rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ asset('uploads/2026/11/hotel-information-service.png') }}" 
                         alt="PaxTV Hotel Information & Services UI" 
                         class="w-full h-auto object-cover rounded-2xl" />
                </div>
            </div>

            <!-- Right Content & 6 Feature Icons (Stacked Icon on Top, Label Below) -->
            <div class="lg:col-span-6 order-1 lg:order-2 space-y-6">
                
                <!-- Eyebrow -->
                <div class="text-[#15888A] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    EVERYTHING YOUR GUESTS NEED
                </div>

                <!-- Section Heading -->
                <h2 class="text-3xl sm:text-[38px] font-bold text-[#0E2556] font-heading leading-[1.2] tracking-tight">
                    Hotel Information &amp; Services
                </h2>

                <!-- Subtitle -->
                <p class="text-[15px] sm:text-[16px] text-[#475569] font-normal leading-[1.7] font-sans">
                    Showcase hotel amenities, dining options, facilities and local attractions directly on the TV.
                </p>

                <!-- 6 Features Grid (3 Columns x 2 Rows: Icon on top, Label below, Centered) -->
                <div class="grid grid-cols-3 gap-y-7 gap-x-4 pt-3">
                    
                    <!-- 1. Hotel Information -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="far fa-building text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            Hotel Information
                        </span>
                    </div>

                    <!-- 2. City Guide -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="far fa-map text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            City Guide
                        </span>
                    </div>

                    <!-- 3. Dining & Restaurants -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-utensils text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            Dining &amp; Restaurants
                        </span>
                    </div>

                    <!-- 4. Local Attractions -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-landmark text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            Local Attractions
                        </span>
                    </div>

                    <!-- 5. In-Room Services -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-concierge-bell text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            In-Room Services
                        </span>
                    </div>

                    <!-- 6. Flight Information -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3"
                             style="background-color: #0E2556; box-shadow: 0 8px 20px -6px rgba(14, 37, 86, 0.45);">
                            <i class="fas fa-plane text-xl text-white"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-[#0E2556] leading-snug">
                            Flight Information
                        </span>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>


<!-- 8. LIVE TV & ENTERTAINMENT SECTION (Deep Navy to Cyan Gradient matching Reference Image 1:1) -->
<section class="relative w-full" 
         style="background-image: linear-gradient(135deg, #0E2556 0%, #146A82 100%); padding-top: 70px; padding-bottom: 70px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Text Content & 6 Feature Icons (White Circles on Dark Gradient) -->
            <div class="lg:col-span-6 space-y-6">
                
                <!-- Eyebrow -->
                <div class="text-[#5EC9CB] font-semibold text-xs tracking-[2px] uppercase font-mono">
                    NON-STOP ENTERTAINMENT
                </div>

                <!-- Section Heading -->
                <h2 class="text-3xl sm:text-[38px] font-bold text-white font-heading leading-[1.2] tracking-tight">
                    Live TV &amp; Entertainment
                </h2>

                <!-- Subtitle -->
                <p class="text-[15px] sm:text-[16px] text-[#CBD5E1] font-normal leading-[1.7] font-sans">
                    Deliver seamless live TV, movies and guest entertainment with an intuitive interface.
                </p>

                <!-- 6 Features Grid (3 Columns x 2 Rows: White circle on top, White label below, Centered) -->
                <div class="grid grid-cols-3 gap-y-7 gap-x-4 pt-3">
                    
                    <!-- 1. Live TV Channels -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fas fa-tv text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            Live TV Channels
                        </span>
                    </div>

                    <!-- 2. On-Demand Movies -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fas fa-film text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            On-Demand Movies
                        </span>
                    </div>

                    <!-- 3. Web Applications -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fas fa-globe text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            Web Applications
                        </span>
                    </div>

                    <!-- 4. Screen Cast -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fab fa-chromecast text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            Screen Cast
                        </span>
                    </div>

                    <!-- 5. Personalised Recommendations -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fas fa-heart text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            Personalised Recommendations
                        </span>
                    </div>

                    <!-- 6. On-Demand Movies (or OTT) -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-3 shadow-lg">
                            <i class="fas fa-film text-xl text-[#0E2556]"></i>
                        </div>
                        <span class="text-[14px] font-semibold text-white leading-snug">
                            On-Demand Movies
                        </span>
                    </div>

                </div>

            </div>

            <!-- Right Image Showcase -->
            <div class="lg:col-span-6">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('uploads/2026/11/Live-TV-ent.png') }}" 
                         alt="PaxTV Live Entertainment UI" 
                         class="w-full h-auto object-cover rounded-2xl" />
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 9. TRUSTED BY HOTELS (Exact Match to Reference Carousel Slider) -->
<section id="reviews" class="relative w-full bg-white" style="padding-top: 70px; padding-bottom: 70px;">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
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

        <!-- Slider Carousel Outer Wrapper -->
        <div class="relative px-2 sm:px-4">
            
            <!-- Left Chevron Arrow -->
            <button type="button" 
                    id="hotelReviewPrev"
                    aria-label="Previous Reviews"
                    class="absolute -left-2 sm:-left-3 lg:-left-5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-[#0E2556] hover:text-[#15888A] transition-colors cursor-pointer select-none">
                <i class="fas fa-chevron-left text-base sm:text-lg"></i>
            </button>

            <!-- Slider Viewport Track -->
            <div class="overflow-hidden py-4 -my-4">
                <div id="hotelReviewTrack" 
                     class="flex transition-transform duration-500 ease-out"
                     style="gap: 24px;">
                    
                    <!-- Review Card 1 (From Screenshot) -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <!-- 5 Teal Stars -->
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    The installation was smooth and the team supported us even after go-live. The system has been stable and reliable.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Operations Head</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Resort &amp; Spa</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 2 (From Screenshot) -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <!-- 5 Teal Stars -->
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Screen casting and live channels are used by almost every guest. Our in-room services orders have also improved.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">IT Manager</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Business Hotel Chain</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 3 (From Screenshot) -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <!-- 5 Teal Stars -->
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Branding the interface with our hotel identity gave the rooms a more premium feel without any extra effort from our team.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Hotel Director</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Heritage Hotel</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 4 (From Screenshot) -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <!-- 5 Teal Stars -->
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Local attractions and dining guides on the TV keep guests informed and help us promote our own restaurants.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Marketing Manager</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Luxury Hotel</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 5 -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Guests find the TV interface simple and our hotel information is always up to date. It has become part of the in-room experience.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">General Manager</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">City Business Hotel</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 6 -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Multi-language support made a clear difference for our international guests. We now get far fewer questions at the front desk.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Front Office Manager</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Boutique Hotel</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 7 -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    The cloud management portal saves our staff hours of manual TV configuration across all guest rooms.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Chief Technology Officer</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Grand Horizon Hotel</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 8 -->
                    <div class="shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                        <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center space-x-1 text-[#15888A] mb-4 text-xs">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="text-[13.5px] sm:text-[14px] text-[#475569] leading-relaxed font-sans">
                                    Guest satisfaction scores for in-room entertainment jumped by 40% within the first month of deployment.
                                </p>
                            </div>
                            <div class="pt-6 mt-auto">
                                <div class="text-[15px] font-bold text-[#0E2556] font-heading leading-tight">Guest Experience Director</div>
                                <div class="text-[12.5px] text-[#15888A] font-medium mt-1">Coastal Bay Suites</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Chevron Arrow -->
            <button type="button" 
                    id="hotelReviewNext"
                    aria-label="Next Reviews"
                    class="absolute -right-2 sm:-right-3 lg:-right-5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-[#0E2556] hover:text-[#15888A] transition-colors cursor-pointer select-none">
                <i class="fas fa-chevron-right text-base sm:text-lg"></i>
            </button>

        </div>

        <!-- Pagination Dots (5 Dots matching Reference Image) -->
        <div id="hotelReviewDots" class="flex items-center justify-center space-x-2 pt-8">
            <button type="button" data-dot="0" aria-label="Slide 1" class="w-5 h-2 rounded-full bg-[#0E2556] transition-all duration-300 cursor-pointer"></button>
            <button type="button" data-dot="1" aria-label="Slide 2" class="w-2 h-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer"></button>
            <button type="button" data-dot="2" aria-label="Slide 3" class="w-2 h-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer"></button>
            <button type="button" data-dot="3" aria-label="Slide 4" class="w-2 h-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer"></button>
            <button type="button" data-dot="4" aria-label="Slide 5" class="w-2 h-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer"></button>
        </div>

    </div>
</section>


<!-- 10. CTA BANNER ("REQUEST A DEMO TODAY" - Exact Match to Reference Image 1:1) -->
<section id="contactus" class="relative overflow-hidden border-b-2 border-[#15888A]" 
         style="padding-top: 85px; padding-bottom: 85px;">
    
    <!-- Background Luxury Resort Image with Rich Multi-Layer Dark Navy Overlay (Exact Match to Reference) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('uploads/2026/11/Twilight-Luxury-Resort-by-the-Sea-1-1-1.png') }}" 
             alt="Twilight Luxury Resort" 
             class="w-full h-full object-cover object-right lg:object-[center_right] filter brightness-[0.75] contrast-[1.1]" />
        
        <!-- Base Blue Night Ambiance Tint -->
        <div class="absolute inset-0 bg-[#071A36]/50"></div>

        <!-- Multi-Stop Left-to-Right Navy Gradient (Solid Midnight Navy on Left, Rich Ocean Blue Filter on Right) -->
        <div class="absolute inset-0" 
             style="background: linear-gradient(90deg, #06152B 0%, #071B38 30%, rgba(7, 27, 56, 0.94) 52%, rgba(8, 33, 68, 0.72) 75%, rgba(9, 38, 78, 0.50) 100%);"></div>
    </div>

    <!-- Left-Aligned Content Container -->
    <div class="relative z-10 max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-[580px] space-y-4 sm:space-y-5">
            
            <!-- Category Eyebrow in Teal -->
            <div class="text-[#5EC9CB] font-semibold text-xs tracking-[2px] uppercase font-mono">
                READY TO TRANSFORM YOUR GUEST EXPERIENCE?
            </div>

            <!-- Exact Headline -->
            <h2 class="text-3xl sm:text-4xl lg:text-[46px] font-bold text-white font-heading leading-[1.2] tracking-tight">
                Request a Demo Today
            </h2>

            <!-- Exact Descriptive Subtitle -->
            <p class="text-sm sm:text-base text-[#CBD5E1] font-normal leading-[1.7] max-w-[520px]">
                Discover how our Hotel TV solution can add value to your property and delight your guests.
            </p>

            <!-- Dual Action Buttons (Request Demo & Contact Us) -->
            <div class="pt-3 flex flex-wrap items-center gap-3.5">
                
                <!-- Request Demo Button (Solid White with Navy Text) -->
                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-[10px] text-sm sm:text-base font-semibold text-[#0E2556] bg-white hover:bg-slate-100 transition-all duration-200 shadow-xl hover:scale-[1.02] cursor-pointer">
                    <span>Request Demo</span>
                </button>

                <!-- Contact Us Button (Navy Outline / Dark Semi-Transparent) -->
                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-7 py-3 rounded-[10px] text-sm sm:text-base font-semibold text-white bg-[#0A1931]/60 hover:bg-white/10 backdrop-blur-md border border-white/35 transition-all duration-200 cursor-pointer">
                    <span>Contact Us</span>
                </button>

            </div>

        </div>
    </div>
</section>

<!-- Reviews Slider JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('hotelReviewTrack');
        const prevBtn = document.getElementById('hotelReviewPrev');
        const nextBtn = document.getElementById('hotelReviewNext');
        const dotsContainer = document.getElementById('hotelReviewDots');

        if (!track || !prevBtn || !nextBtn || !dotsContainer) return;

        const cards = track.children;
        const totalCards = cards.length;
        let currentIndex = 0;
        const totalDots = 5;

        function getVisibleCards() {
            if (window.innerWidth >= 1024) return 4;
            if (window.innerWidth >= 640) return 2;
            return 1;
        }

        function getMaxIndex() {
            const visible = getVisibleCards();
            return Math.max(0, totalCards - visible);
        }

        function updateSlider(index) {
            const maxIndex = getMaxIndex();
            currentIndex = Math.max(0, Math.min(index, maxIndex));

            if (cards.length > 0) {
                const cardWidth = cards[0].getBoundingClientRect().width;
                const gap = 24; // 24px gap matching layout
                const offset = currentIndex * (cardWidth + gap);
                track.style.transform = `translateX(-${offset}px)`;
            }

            // Update Pagination Dots
            const dots = dotsContainer.querySelectorAll('button');
            const activeDotIndex = maxIndex > 0 ? Math.round((currentIndex / maxIndex) * (dots.length - 1)) : 0;

            dots.forEach((dot, i) => {
                if (i === activeDotIndex) {
                    dot.className = 'w-5 h-2 rounded-full bg-[#0E2556] transition-all duration-300 cursor-pointer';
                } else {
                    dot.className = 'w-2 h-2 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300 cursor-pointer';
                }
            });
        }

        prevBtn.addEventListener('click', function () {
            const maxIndex = getMaxIndex();
            const next = currentIndex > 0 ? currentIndex - 1 : maxIndex;
            updateSlider(next);
        });

        nextBtn.addEventListener('click', function () {
            const maxIndex = getMaxIndex();
            const next = currentIndex < maxIndex ? currentIndex + 1 : 0;
            updateSlider(next);
        });

        dotsContainer.querySelectorAll('button').forEach((dot, dotIdx) => {
            dot.addEventListener('click', function () {
                const maxIndex = getMaxIndex();
                const targetIndex = Math.round((dotIdx / (totalDots - 1)) * maxIndex);
                updateSlider(targetIndex);
            });
        });

        window.addEventListener('resize', function () {
            updateSlider(currentIndex);
        });

        // Touch swipe support
        let startX = 0;
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        track.addEventListener('touchend', (e) => {
            const diffX = startX - e.changedTouches[0].clientX;
            if (diffX > 40) {
                nextBtn.click();
            } else if (diffX < -40) {
                prevBtn.click();
            }
        });

        // Auto slide every 5 seconds (pausing on hover)
        let autoPlayTimer = setInterval(() => {
            nextBtn.click();
        }, 5000);

        track.parentElement.addEventListener('mouseenter', () => clearInterval(autoPlayTimer));
        track.parentElement.addEventListener('mouseleave', () => {
            clearInterval(autoPlayTimer);
            autoPlayTimer = setInterval(() => nextBtn.click(), 5000);
        });

        // Initial setup
        updateSlider(0);
    });
</script>

@endsection
