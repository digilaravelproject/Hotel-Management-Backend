@extends('layouts.landing')

@section('title', 'Privacy Policy - HotelTV Connect')

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
                <a href="{{ route('contact-us') }}" class="hover:text-indigo-600 transition-colors">Contact Us</a>
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
                <i class="fa-solid fa-shield-halved text-indigo-600"></i>
                <span>Trust & Transparency</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight font-['Syne']">
                Privacy Policy
            </h1>
            <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Simple, clear, and transparent. We explain how HotelTV Connect collects, protects, and respects the data of our hotel partners and their valued guests.
            </p>
            <div class="flex items-center justify-center space-x-6 text-xs text-slate-500 pt-2 font-medium">
                <span><i class="fa-regular fa-calendar-check mr-1.5 text-indigo-500"></i>Effective: September 2026</span>
                <span>•</span>
                <span><i class="fa-solid fa-clock-rotate-left mr-1.5 text-indigo-500"></i>Version 2.4</span>
            </div>
        </div>
    </section>

    <!-- Main Content Layout -->
    <main class="max-w-7xl mx-auto px-6 lg:px-16 py-12 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
            
            <!-- Quick Sticky Navigation Sidebar -->
            <aside class="hidden lg:block lg:col-span-4">
                <div class="sticky top-28 space-y-4 p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-['Syne'] flex items-center space-x-2">
                        <i class="fa-solid fa-list-ul text-indigo-600"></i>
                        <span>Table of Contents</span>
                    </h3>
                    <nav class="space-y-1.5 text-xs font-semibold text-slate-600">
                        <a href="#overview" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">1. Overview & Scope</a>
                        <a href="#information-we-collect" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">2. Information We Collect</a>
                        <a href="#guest-privacy" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">3. In-Room TV & Guest Privacy</a>
                        <a href="#how-we-use-data" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">4. How We Use Information</a>
                        <a href="#data-storage-security" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">5. Data Security & Storage</a>
                        <a href="#third-parties" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">6. Third-Party Integrations</a>
                        <a href="#data-retention" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">7. Data Retention & Deletion</a>
                        <a href="#partner-rights" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">8. Hotel Partner Rights</a>
                        <a href="#contact" class="block px-3 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">9. Contact & Inquiries</a>
                    </nav>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs space-y-2">
                            <span class="font-bold text-indigo-950 block">Have a question?</span>
                            <p class="text-slate-600 leading-relaxed">Our support & compliance team is available to assist.</p>
                            <a href="{{ route('contact-us') }}" class="inline-flex items-center text-indigo-600 font-bold hover:underline">
                                Contact Us <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Policy Content Articles -->
            <div class="lg:col-span-8 space-y-10">

                <!-- Highlights Box -->
                <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-xl shadow-indigo-600/20 space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-white text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Our Privacy Commitment</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold font-['Syne']">Privacy by Design for Hospitality</h2>
                    <p class="text-xs sm:text-sm text-indigo-100 leading-relaxed font-normal">
                        HotelTV Connect is purpose-built for hospitality. We never sell personal data, we never secretly track guest viewing habits, and we automatically wipe in-room smart TV sessions upon guest departure to protect guest confidentiality.
                    </p>
                </div>

                <!-- Section 1 -->
                <section id="overview" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">1</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Overview & Scope</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        This Privacy Policy governs the manner in which <strong>HotelTV Connect</strong> ("we", "our", or "the Platform") collects, uses, maintains, and discloses data collected from subscribers, hotel administrative users ("Hotels"), and the operation of our in-room Smart TV applications deployed on guest room screens.
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        By registering an account, purchasing a subscription plan, or installing our connected device software on hotel televisions, you acknowledge the terms outlined in this policy.
                    </p>
                </section>

                <!-- Section 2 -->
                <section id="information-we-collect" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">2</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Information We Collect</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        To provide smooth television management and guest communication, we collect only the necessary information:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <div class="text-indigo-600 font-bold text-xs flex items-center space-x-2">
                                <i class="fa-solid fa-user-tie"></i>
                                <span>Hotel Account Information</span>
                            </div>
                            <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                                <li>Hotel owner / representative full name</li>
                                <li>Email address & contact telephone</li>
                                <li>Property name, city, and location</li>
                                <li>Room count and subscription tier</li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2">
                            <div class="text-indigo-600 font-bold text-xs flex items-center space-x-2">
                                <i class="fa-solid fa-tv"></i>
                                <span>TV & Hardware Telemetry</span>
                            </div>
                            <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                                <li>Assigned room numbers & license key</li>
                                <li>Device identifiers (MAC address, IP)</li>
                                <li>App version, firmware, and connectivity status</li>
                                <li>Heartbeat timestamps for live status</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Section 3 -->
                <section id="guest-privacy" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">3</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">In-Room Smart TV & Guest Privacy</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Guest privacy is of paramount importance in the hospitality sector. Here are our strict operating rules regarding room TV screens:
                    </p>
                    
                    <div class="space-y-3 pt-1">
                        <div class="flex items-start space-x-3 p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/60 text-xs">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-emerald-950 block">Automatic Session Clear on Check-Out</span>
                                <span class="text-emerald-800">When hotel admin marks a room checked-out or resets the device, all guest-specific cache, third-party logins, and history are wiped automatically.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-200/60 text-xs">
                            <i class="fa-solid fa-eye-slash text-indigo-600 mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-indigo-950 block">No Audio or Video Recording</span>
                                <span class="text-indigo-800">HotelTV Connect does not listen, record audio, or capture camera streams. The software operates purely as an interactive guest experience dashboard.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3.5 rounded-2xl bg-slate-100/80 border border-slate-200 text-xs">
                            <i class="fa-solid fa-hand-holding-hand text-slate-600 mt-0.5 text-base"></i>
                            <div>
                                <span class="font-bold text-slate-900 block">No Tracking of Guest Personal Credentials</span>
                                <span class="text-slate-600">If a guest signs into third-party OTT applications (e.g. Netflix or YouTube) supported by the TV OS, their credentials are authenticated directly with the respective third-party provider and never stored on our servers.</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 4 -->
                <section id="how-we-use-data" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">4</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">How We Use Information</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Collected data is used strictly for the following functional and administrative purposes:
                    </p>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-2 list-disc list-inside">
                        <li><strong>Platform Provisioning:</strong> Authenticating active screens against legitimate 16-digit license keys.</li>
                        <li><strong>Content Synchronization:</strong> Real-time syncing of hotel branding, custom themes, dining menus, and local tourism guides.</li>
                        <li><strong>Emergency Alerts & Messaging:</strong> Pushing instant notifications (e.g., flight status updates, weather advisories, emergency alerts) to selected or all rooms.</li>
                        <li><strong>Billing & Account Maintenance:</strong> Processing subscription renewals and providing tax invoices.</li>
                        <li><strong>Security & Diagnostics:</strong> Monitoring network latency, TV app crashes, and connectivity drops to maintain service reliability.</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section id="data-storage-security" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">5</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Data Security & Storage</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        We implement rigorous organizational, physical, and technical safeguards to keep all account data and telemetry secure:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 text-center space-y-1">
                            <i class="fa-solid fa-lock text-indigo-600 text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">TLS Encryption</h4>
                            <p class="text-[11px] text-slate-500">All data in transit is encrypted using 256-bit SSL/TLS protocol.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 text-center space-y-1">
                            <i class="fa-solid fa-key text-indigo-600 text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">Hashed Credentials</h4>
                            <p class="text-[11px] text-slate-500">Admin passwords and tokens are irreversibly hashed using Bcrypt.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 text-center space-y-1">
                            <i class="fa-solid fa-server text-indigo-600 text-lg mb-1"></i>
                            <h4 class="font-bold text-xs text-slate-800">Secured Clouds</h4>
                            <p class="text-[11px] text-slate-500">Protected behind firewalls with automated daily encrypted backups.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 6 -->
                <section id="third-parties" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">6</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Third-Party Integrations</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        We work only with vetted, enterprise-grade service providers to facilitate platform operations:
                    </p>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-2 list-disc list-inside">
                        <li><strong>Razorpay:</strong> Facilitates PCI-DSS compliant payment processing for hotel subscription plans. Payment card details never touch our application servers.</li>
                        <li><strong>Firebase Cloud Messaging (FCM):</strong> Sends instantaneous push commands to Android TVs for theme updates, instant greetings, and restart commands.</li>
                        <li><strong>Third-Party OTT Services:</strong> Allows hotel guests to launch popular streaming apps. These apps operate independently under their own privacy policies.</li>
                    </ul>
                </section>

                <!-- Section 7 -->
                <section id="data-retention" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">7</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Data Retention & Deletion</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        We retain hotel account information for as long as your subscription is active. Upon cancellation or formal request:
                    </p>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-1.5 list-disc list-inside">
                        <li>Associated room device pairs are decommissioned immediately.</li>
                        <li>Hotel logos, media assets, and menu configurations are removed within 30 days.</li>
                        <li>Financial billing records are retained strictly as required by local tax and accounting regulations.</li>
                    </ul>
                </section>

                <!-- Section 8 -->
                <section id="partner-rights" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">8</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Hotel Partner Rights</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        As a registered hotel partner, you possess complete control over your account data. You may:
                    </p>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-1.5 list-disc list-inside">
                        <li>Access, modify, or update your hotel profile, phone, and logos via the Hotel Admin Dashboard.</li>
                        <li>Request an export of your device pairing data and connected TV fleet metrics.</li>
                        <li>Revoke individual TV device tokens or de-register rooms at any time.</li>
                        <li>Request full account termination by contacting our support team.</li>
                    </ul>
                </section>

                <!-- Section 9 -->
                <section id="contact" class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 text-indigo-600">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center font-bold text-sm">9</span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-['Syne']">Contact & Data Grievance</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        If you have any questions about this Privacy Policy, wish to exercise your data privacy rights, or need technical clarification, please contact our dedicated team:
                    </p>
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-envelope text-indigo-600"></i>
                            <span>Email: <a href="mailto:privacy@hoteltvconnect.com" class="text-indigo-600 hover:underline">privacy@hoteltvconnect.com</a></span>
                        </div>
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-phone text-indigo-600"></i>
                            <span>Phone: +91 (080) 4567-8900</span>
                        </div>
                        <div class="flex items-center space-x-2 font-bold text-slate-800">
                            <i class="fa-solid fa-location-dot text-indigo-600"></i>
                            <span>Office: HotelTV Connect, Outer Ring Road, Bengaluru, Karnataka, India</span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('contact-us') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all">
                            <span>Open Contact Form</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </section>

            </div>
        </div>
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
                        <li><a href="{{ route('privacy-policy') }}" class="text-indigo-400 font-bold hover:underline">Privacy Policy</a></li>
                        <li><a href="{{ route('contact-us') }}" class="hover:text-indigo-400 transition-colors">Contact Support</a></li>
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
