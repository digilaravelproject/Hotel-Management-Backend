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
                    <a href="#home" class="text-[#5EC9CB] transition-colors duration-200">
                        Home
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#about" class="text-white hover:text-[#5EC9CB] transition-colors duration-200">
                        About
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#solutions" class="text-white hover:text-[#5EC9CB] transition-colors duration-200">
                        Solution
                    </a>
                    <span class="text-white/40 px-3.5 select-none font-light text-xs">|</span>

                    <a href="#contactus" data-open-popup="popup-demo" class="text-white hover:text-[#5EC9CB] transition-colors duration-200 cursor-pointer">
                        Contact Us
                    </a>
                </nav>

                <!-- Dual Action Buttons: Login + Request Demo -->
                <div class="flex items-center gap-3 shrink-0">
                    
                    <!-- Login Button -->
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-4 py-[11px] rounded-[10px] text-[13px] font-semibold text-white/95 hover:text-white bg-white/10 hover:bg-white/20 border border-white/25 transition-all duration-200 font-heading">
                        <i class="fa-solid fa-user mr-1.5 text-xs text-[#5EC9CB]"></i>
                        <span>Login</span>
                    </a>

                    <!-- Request Demo Button -->
                    <button type="button" 
                            data-open-popup="popup-demo"
                            style="background-color: #1A9C9E;"
                            class="inline-flex items-center justify-center px-[18px] py-[12px] rounded-[10px] text-[13px] font-semibold text-[#0E2556] font-heading hover:bg-[#22B5B7] transition-all duration-200 shadow-md hover:scale-[1.02] cursor-pointer">
                        <span>Request Demo</span>
                        <i class="fas fa-chevron-right ml-2 text-xs"></i>
                    </button>

                </div>

            </div>

            <!-- Mobile Actions (Login & Request Demo) + Hamburger Toggle -->
            <div class="flex md:hidden items-center gap-1.5 sm:gap-2">
                <!-- Mobile Login Button -->
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-2 sm:px-2.5 py-1.5 rounded-[8px] text-[11px] sm:text-[12px] font-semibold text-white/95 hover:text-white bg-white/10 hover:bg-white/20 border border-white/25 transition-all font-heading">
                    <i class="fa-solid fa-user mr-1 text-[10px] text-[#5EC9CB]"></i>
                    <span>Login</span>
                </a>

                <!-- Mobile Request Demo Button -->
                <button type="button" 
                        data-open-popup="popup-demo"
                        style="background-color: #1A9C9E;"
                        class="inline-flex items-center justify-center px-2.5 sm:px-3 py-1.5 rounded-[8px] text-[11px] sm:text-[12px] font-semibold text-[#0E2556] font-heading hover:bg-[#22B5B7] transition-all shadow-sm cursor-pointer whitespace-nowrap">
                    <span>Request Demo</span>
                </button>

                <!-- Mobile Hamburger Toggle -->
                <button type="button" 
                        id="mobileMenuToggle" 
                        aria-label="Toggle mobile menu"
                        class="p-2 rounded-lg bg-black/40 border border-white/20 text-white hover:bg-black/60 transition-colors">
                    <i class="fas fa-bars text-sm sm:text-base" id="mobileMenuIcon"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Navigation Menu -->
    <div id="mobileMenuDropdown" class="hidden md:hidden bg-[#0E2556]/98 backdrop-blur-2xl px-6 pt-3 pb-6 space-y-2.5 shadow-2xl">
        <a href="#home" class="block px-3 py-2 rounded-lg text-base font-bold text-[#14B8A6]">
            Home
        </a>
        <a href="#about" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            About
        </a>
        <a href="#solutions" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            Solution
        </a>
        <a href="#contactus" data-open-popup="popup-demo" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/5">
            Contact Us
        </a>
        <div class="pt-3 border-t border-white/10 space-y-2.5">
            <a href="{{ route('hotel.register') }}" class="block px-3 py-2.5 rounded-lg text-base font-bold text-teal-300 bg-teal-500/10">
                <i class="fa-solid fa-hotel mr-2"></i> Register Hotel
            </a>
            <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium text-white bg-white/10 hover:bg-white/20 text-center">
                <i class="fa-solid fa-user mr-1.5 text-xs text-[#5EC9CB]"></i> Login
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

<!-- Sticky Header Scroll Script (Dark Bluish Shade #0E2556 on Scroll, Zero Bottom White Line) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('mainHeader');
        const headerInner = document.getElementById('headerInner');
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const menuDropdown = document.getElementById('mobileMenuDropdown');
        const menuIcon = document.getElementById('mobileMenuIcon');

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
