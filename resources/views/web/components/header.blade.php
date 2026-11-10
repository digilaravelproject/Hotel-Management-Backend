<header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300 bg-transparent">
    <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">
        <div id="headerInner" class="flex items-center justify-between py-[18px] transition-all duration-300">
            
            <!-- Brand Logo (Responsive Height: ~48px mobile, 64px sm, 68px desktop) -->
            <div class="shrink-0 flex items-center">
                <a href="#home" class="flex items-center group">
                    <img src="{{ asset('uploads/2026/11/PAX-TV-LAUNCHER-2-2.png') }}" 
                         alt="Pax TV Launcher" 
                         class="h-12 sm:h-14 lg:h-[68px] w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                </a>
            </div>

            <!-- Desktop Navigation Links (Gallery Removed, Exact Styling) -->
            <div class="hidden md:flex items-center space-x-6 lg:space-x-7">
                <nav class="flex items-center font-heading text-[14px] font-medium">
                    <a href="#home" class="text-[#00fbff] transition-colors duration-200">
                        Home
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#about" class="text-white hover:text-[#00fbff] transition-colors duration-200">
                        About
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#solutions" class="text-white hover:text-[#00fbff] transition-colors duration-200">
                        Solution
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#contactus" data-open-popup="popup-demo" class="text-white hover:text-[#00fbff] transition-colors duration-200 cursor-pointer">
                        Contact Us
                    </a>
                </nav>

                <!-- Dual Action Buttons: Login + Request Demo -->
                <div class="flex items-center gap-3 shrink-0">
                    
                    <!-- Login Button -->
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-4 py-[11px] rounded-[10px] text-[13px] font-semibold text-white/95 hover:text-white bg-white/10 hover:bg-white/20 border border-white/25 transition-all duration-200 font-heading">
                        <i class="fa-solid fa-user mr-1.5 text-xs text-[#00fbff]"></i>
                        <span>Login</span>
                    </a>

                    <!-- Request Demo Button -->
                    <button type="button" 
                            data-open-popup="popup-demo"
                            style="background-color: #00fbff;"
                            class="inline-flex items-center justify-center px-[18px] py-[12px] rounded-[10px] text-[13px] font-bold text-[#0E2556] font-heading hover:bg-[#33fcff] transition-all duration-200 shadow-[0_4px_18px_rgba(0,251,255,0.35)] hover:scale-[1.02] cursor-pointer">
                        <span>Request Demo</span>
                        <i class="fas fa-chevron-right ml-2 text-xs"></i>
                    </button>

                </div>

            </div>

            <!-- Mobile Actions (Login & Request Demo) + Hamburger Toggle (Unified Height & Styling) -->
            <div class="flex md:hidden items-center gap-1.5 sm:gap-2">
                <!-- Mobile Login Button -->
                <a href="{{ route('login') }}" 
                   class="h-9 sm:h-[38px] px-2.5 sm:px-3 inline-flex items-center justify-center rounded-[9px] text-[11.5px] sm:text-[12px] font-semibold text-white/95 hover:text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-xs transition-all font-heading active:scale-95">
                    <i class="fa-solid fa-user mr-1 text-[10px] text-[#00fbff]"></i>
                    <span>Login</span>
                </a>

                <!-- Mobile Request Demo Button -->
                <button type="button" 
                        data-open-popup="popup-demo"
                        style="background-color: #00fbff;"
                        class="h-9 sm:h-[38px] px-3 sm:px-3.5 inline-flex items-center justify-center rounded-[9px] text-[11.5px] sm:text-[12px] font-bold text-[#0E2556] font-heading hover:bg-[#33fcff] transition-all shadow-[0_2px_12px_rgba(0,251,255,0.35)] cursor-pointer whitespace-nowrap active:scale-95">
                    <span>Request Demo</span>
                </button>

                <!-- Mobile Hamburger Toggle (Matches Login/Demo Height, Rounded & Glassmorphic Style) -->
                <button type="button" 
                        id="mobileMenuToggle" 
                        aria-label="Toggle mobile menu"
                        class="h-9 w-9 sm:h-[38px] sm:w-[38px] inline-flex items-center justify-center rounded-[9px] bg-white/10 hover:bg-white/20 border border-white/20 text-white backdrop-blur-xs transition-all duration-200 cursor-pointer active:scale-95 shrink-0 focus:outline-none">
                    <svg id="hamburgerIconOpen" class="w-[18px] h-[18px] text-white transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                    <svg id="hamburgerIconClose" class="w-[18px] h-[18px] text-[#00fbff] hidden transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Navigation Menu -->
    <div id="mobileMenuDropdown" class="hidden md:hidden bg-[#0A162D]/98 backdrop-blur-2xl px-5 pt-3 pb-6 space-y-2 border-b border-white/10 shadow-2xl transition-all duration-300">
        <a href="#home" class="block px-3.5 py-2.5 rounded-lg text-[15px] font-semibold text-[#00fbff] bg-[#00fbff]/10">
            Home
        </a>
        <a href="#about" class="block px-3.5 py-2.5 rounded-lg text-[15px] font-medium text-white/90 hover:text-white hover:bg-white/5 transition-colors">
            About
        </a>
        <a href="#solutions" class="block px-3.5 py-2.5 rounded-lg text-[15px] font-medium text-white/90 hover:text-white hover:bg-white/5 transition-colors">
            Solution
        </a>
        <a href="#contactus" data-open-popup="popup-demo" class="block px-3.5 py-2.5 rounded-lg text-[15px] font-medium text-white/90 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
            Contact Us
        </a>
        <div class="pt-3 border-t border-white/10 space-y-2.5">
            <a href="{{ route('hotel.register') }}" class="block px-3.5 py-2.5 rounded-lg text-[14px] font-semibold text-[#00fbff] bg-[#00fbff]/10">
                <i class="fa-solid fa-hotel mr-2"></i> Register Hotel
            </a>
            <a href="{{ route('login') }}" class="block px-3.5 py-2.5 rounded-lg text-[14px] font-semibold text-white bg-white/10 hover:bg-white/20 text-center">
                <i class="fa-solid fa-user mr-1.5 text-xs text-[#00fbff]"></i> Login
            </a>
            <button type="button" 
                    data-open-popup="popup-demo"
                    style="background-color: #00fbff;"
                    class="w-full mt-2 flex items-center justify-center px-5 py-3 rounded-[10px] text-[14px] font-bold text-[#0E2556] hover:bg-[#33fcff] transition-all shadow-[0_4px_18px_rgba(0,251,255,0.35)] cursor-pointer">
                <span>Request Demo</span>
                <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </button>
        </div>
    </div>
</header>

<!-- Sticky Header Scroll Script (Dark Bluish Shade #0E2556 on Scroll, Zero Bottom White Line) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('mainHeader');
        const headerInner = document.getElementById('headerInner');
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const menuDropdown = document.getElementById('mobileMenuDropdown');
        const iconOpen = document.getElementById('hamburgerIconOpen');
        const iconClose = document.getElementById('hamburgerIconClose');

        function onScroll() {
            if (window.scrollY > 30) {
                // Scrolled: Dark Bluish Shade (#0E2556) with backdrop blur and NO white line
                header.classList.add('bg-[#0E2556]/95', 'backdrop-blur-md', 'shadow-xl');
                header.classList.remove('bg-transparent');
                if (headerInner) {
                    headerInner.classList.remove('py-[18px]');
                    headerInner.classList.add('py-3');
                }
            } else {
                // At top: Clean transparent background
                header.classList.remove('bg-[#0E2556]/95', 'backdrop-blur-md', 'shadow-xl');
                header.classList.add('bg-transparent');
                if (headerInner) {
                    headerInner.classList.add('py-[18px]');
                    headerInner.classList.remove('py-3');
                }
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        function setMenuState(isOpen) {
            if (isOpen) {
                menuDropdown.classList.remove('hidden');
                if (iconOpen) iconOpen.classList.add('hidden');
                if (iconClose) iconClose.classList.remove('hidden');
            } else {
                menuDropdown.classList.add('hidden');
                if (iconOpen) iconOpen.classList.remove('hidden');
                if (iconClose) iconClose.classList.add('hidden');
            }
        }

        if (toggleBtn && menuDropdown) {
            toggleBtn.addEventListener('click', function () {
                const isHidden = menuDropdown.classList.contains('hidden');
                setMenuState(isHidden);
            });

            menuDropdown.querySelectorAll('a, button').forEach(item => {
                item.addEventListener('click', () => {
                    setMenuState(false);
                });
            });
        }
    });
</script>
