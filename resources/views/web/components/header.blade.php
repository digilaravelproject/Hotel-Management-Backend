<header id="mainHeader" class="absolute top-0 left-0 right-0 z-50 w-full transition-all duration-300 bg-transparent">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-24 sm:h-28">
            
            <!-- Brand Logo (Prominent High-Definition Size matching Reference Exactly) -->
            <div class="shrink-0 flex items-center">
                <a href="#home" class="flex items-center group">
                    <img src="{{ asset('uploads/2026/11/PAX-TV-LAUNCHER-2-2.png') }}" 
                         alt="Pax TV Launcher" 
                         class="h-14 sm:h-16 lg:h-[68px] w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                </a>
            </div>

            <!-- Desktop Navigation Links (Exact Match to Reference Design) -->
            <div class="hidden md:flex items-center space-x-5 lg:space-x-7">
                <nav class="flex items-center text-[15px]">
                    <a href="#home" class="font-semibold text-[#14B8A6] hover:text-[#22D3EE] transition-colors duration-200">
                        Home
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#about" class="font-medium text-white hover:text-[#14B8A6] transition-colors duration-200">
                        About
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#solutions" class="font-medium text-white hover:text-[#14B8A6] transition-colors duration-200">
                        Solution
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#solutions" class="font-medium text-white hover:text-[#14B8A6] transition-colors duration-200">
                        Gallery
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#contactus" data-open-popup="popup-demo" class="font-medium text-white hover:text-[#14B8A6] transition-colors duration-200 cursor-pointer">
                        Contact Us
                    </a>
                </nav>

                <!-- Request Demo Action Button (Exact Match to Reference Design) -->
                <button type="button" 
                        data-open-popup="popup-demo"
                        style="background-color: rgb(26, 156, 158);"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-[10px] text-sm font-semibold text-[#0E2556] hover:bg-[#22B5B7] transition-all duration-200 shadow-md hover:scale-[1.02] cursor-pointer shrink-0">
                    <span>Request Demo</span>
                    <i class="fas fa-chevron-right ml-2 text-xs"></i>
                </button>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <div class="flex md:hidden items-center">
                <button type="button" 
                        id="mobileMenuToggle" 
                        aria-label="Toggle mobile menu"
                        class="p-2.5 rounded-xl bg-black/40 border border-white/20 text-white hover:bg-black/60 transition-colors">
                    <i class="fas fa-bars text-lg" id="mobileMenuIcon"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Navigation Menu -->
    <div id="mobileMenuDropdown" class="hidden md:hidden border-t border-white/10 bg-[#050B18]/95 backdrop-blur-2xl px-6 pt-3 pb-6 space-y-2.5">
        <a href="#home" class="block px-3 py-2 rounded-lg text-base font-bold text-[#14B8A6]">
            Home
        </a>
        <a href="#about" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            About
        </a>
        <a href="#solutions" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            Solution
        </a>
        <a href="#solutions" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            Gallery
        </a>
        <a href="#contactus" data-open-popup="popup-demo" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            Contact Us
        </a>
        <div class="pt-3 border-t border-white/10 space-y-2">
            <a href="{{ route('hotel.register') }}" class="block px-3 py-2.5 rounded-lg text-base font-bold text-teal-300 bg-teal-500/10">
                <i class="fa-solid fa-hotel mr-2"></i> Register Hotel
            </a>
            <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-300">
                Sign In
            </a>
            <button type="button" 
                    data-open-popup="popup-demo"
                    style="background-color: rgb(26, 156, 158);"
                    class="w-full mt-2 flex items-center justify-center px-5 py-3 rounded-[10px] text-base font-semibold text-[#0E2556] hover:bg-[#22B5B7] transition-all">
                <span>Request Demo</span>
                <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </button>
        </div>
    </div>
</header>

<!-- Header Scroll Script: Transparent at top, subtle dark glass on scroll -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('mainHeader');
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const menuDropdown = document.getElementById('mobileMenuDropdown');
        const menuIcon = document.getElementById('mobileMenuIcon');

        function onScroll() {
            if (window.scrollY > 40) {
                header.classList.add('bg-[#050B18]/90', 'backdrop-blur-xl', 'border-b', 'border-white/10', 'shadow-lg');
                header.classList.remove('bg-transparent');
            } else {
                header.classList.remove('bg-[#050B18]/90', 'backdrop-blur-xl', 'border-b', 'border-white/10', 'shadow-lg');
                header.classList.add('bg-transparent');
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        if (toggleBtn && menuDropdown) {
            toggleBtn.addEventListener('click', function () {
                const isHidden = menuDropdown.classList.contains('hidden');
                if (isHidden) {
                    menuDropdown.classList.remove('hidden');
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                } else {
                    menuDropdown.classList.add('hidden');
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            });

            menuDropdown.querySelectorAll('a, button').forEach(item => {
                item.addEventListener('click', () => {
                    menuDropdown.classList.add('hidden');
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                });
            });
        }
    });
</script>
