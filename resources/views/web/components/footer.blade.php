<footer class="bg-[#030712] border-t border-white/10 text-slate-400 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8">
            
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-5">
                <a href="#home" class="inline-block">
                    <img src="{{ asset('uploads/2026/11/PAX-TV-LAUNCHER-2-2.png') }}" 
                         alt="Pax TV Launcher" 
                         class="h-10 w-auto object-contain" />
                </a>
                <p class="text-sm leading-relaxed text-slate-400 max-w-sm">
                    Advanced Hotel TV solutions that combine premium entertainment, digital concierge information, and cloud hospitality services into a single, intuitive in-room application.
                </p>
                <!-- Social Links -->
                <div class="flex items-center space-x-3 pt-2">
                    <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-[#22B5B7] hover:border-[#1A9C9E]/40 hover:bg-[#1A9C9E]/10 transition-all">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-[#22B5B7] hover:border-[#1A9C9E]/40 hover:bg-[#1A9C9E]/10 transition-all">
                        <i class="fab fa-instagram text-xs"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-[#22B5B7] hover:border-[#1A9C9E]/40 hover:bg-[#1A9C9E]/10 transition-all">
                        <i class="fab fa-linkedin-in text-xs"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-[#22B5B7] hover:border-[#1A9C9E]/40 hover:bg-[#1A9C9E]/10 transition-all">
                        <i class="fab fa-youtube text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4 font-heading">
                    Quick Links
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#about" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> About Us</a></li>
                    <li><a href="#solutions" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Our Solutions</a></li>
                    <li><a href="#features" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Hotel Services</a></li>
                    <li><a href="#gallery" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Gallery</a></li>
                    <li><a href="{{ route('hotel.register') }}" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Register Hotel</a></li>
                </ul>
            </div>

            <!-- Solutions Links -->
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4 font-heading">
                    Solutions
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#hotel-tv" data-open-popup="popup-hotel-tv" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Hotel TV Launcher</a></li>
                    <li><a href="#system-integration" data-open-popup="popup-integration" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> PMS & Cast Integration</a></li>
                    <li><a href="#ongoing-support" data-open-popup="popup-support" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> 24/7 Cloud Support</a></li>
                    <li><a href="#solutions" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> Multi-Language UI</a></li>
                    <li><a href="#solutions" class="hover:text-[#22B5B7] transition-colors flex items-center"><i class="fas fa-angle-right text-xs text-[#1A9C9E] mr-2"></i> OTT Privacy Auto-Wipe</a></li>
                </ul>
            </div>

            <!-- Contact & Action -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4 font-heading">
                    Get In Touch
                </h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li class="flex items-start space-x-2.5">
                        <i class="fas fa-location-dot text-[#1A9C9E] mt-1 text-xs"></i>
                        <span>Mumbai & Bangalore, India</span>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <i class="fas fa-envelope text-[#1A9C9E] mt-1 text-xs"></i>
                        <span>contact@paxtvlauncher.com</span>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <i class="fas fa-phone text-[#1A9C9E] mt-1 text-xs"></i>
                        <span>+91 99000 00000</span>
                    </li>
                </ul>
                <div class="pt-2">
                    <button type="button" 
                            data-open-popup="popup-demo"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-md shadow-[#1A9C9E]/20">
                        <span>Schedule Demo</span>
                        <i class="fas fa-chevron-right ml-1.5 text-[10px]"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Copyright & Compliance Sub-Footer -->
        <div class="mt-14 pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} Pax TV Launcher Inc. All rights reserved.
            </div>
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-slate-400 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-slate-400 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-slate-400 transition-colors">Security</a>
                <a href="{{ route('login') }}" class="hover:text-[#22B5B7] transition-colors font-medium">Hotel Staff Login</a>
            </div>
        </div>
    </div>
</footer>
