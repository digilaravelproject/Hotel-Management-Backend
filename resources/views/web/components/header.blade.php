<header class="sticky top-0 z-50 w-full transition-all duration-300 bg-[#050B18]/85 backdrop-blur-xl border-b border-white/10" id="mainHeader">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Brand Logo -->
            <div class="shrink-0 flex items-center">
                <a href="#home" class="flex items-center space-x-3 group">
                    <img src="{{ asset('uploads/2026/11/PAX-TV-LAUNCHER-2-2.png') }}" 
                         alt="Pax TV Launcher" 
                         class="h-9 sm:h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                </a>
            </div>

            <!-- Desktop Navigation Menu -->
            <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                <a href="#home" class="px-3.5 py-2 text-sm font-medium text-slate-200 hover:text-[#22B5B7] rounded-lg transition-colors duration-200">
                    Home
                </a>
                <a href="#about" class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-[#22B5B7] rounded-lg transition-colors duration-200">
                    About
                </a>
                <a href="#solutions" class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-[#22B5B7] rounded-lg transition-colors duration-200">
                    Solutions
                </a>
                <a href="#features" class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-[#22B5B7] rounded-lg transition-colors duration-200">
                    Features
                </a>
                <a href="#gallery" class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-[#22B5B7] rounded-lg transition-colors duration-200">
                    Gallery
                </a>
                <a href="{{ route('hotel.register') }}" class="px-3.5 py-2 text-sm font-semibold text-teal-300 hover:text-white hover:bg-[#1A9C9E]/20 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-hotel mr-1 text-xs"></i> Register Hotel
                </a>
                <a href="{{ route('login') }}" class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white rounded-lg transition-colors duration-200">
                    Sign In
                </a>
            </nav>

            <!-- CTA Actions (Desktop) -->
            <div class="hidden lg:flex items-center space-x-4">
                <button type="button" 
                        data-open-popup="popup-demo"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all duration-200 shadow-lg shadow-[#1A9C9E]/25 hover:shadow-[#1A9C9E]/40 hover:scale-[1.02] cursor-pointer">
                    <span>Request Demo</span>
                    <i class="fas fa-chevron-right ml-2 text-xs"></i>
                </button>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex lg:hidden items-center">
                <button type="button" 
                        id="mobileMenuToggle" 
                        aria-label="Toggle mobile menu"
                        class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 hover:text-white hover:bg-white/10 transition-colors">
                    <i class="fas fa-bars text-lg" id="mobileMenuIcon"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Navigation Menu -->
    <div id="mobileMenuDropdown" class="hidden lg:hidden border-t border-white/10 bg-[#050B18]/95 backdrop-blur-2xl px-4 pt-3 pb-6 space-y-2">
        <a href="#home" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-200 hover:bg-white/5 hover:text-[#22B5B7]">
            Home
        </a>
        <a href="#about" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-300 hover:bg-white/5 hover:text-[#22B5B7]">
            About
        </a>
        <a href="#solutions" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-300 hover:bg-white/5 hover:text-[#22B5B7]">
            Solutions
        </a>
        <a href="#features" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-300 hover:bg-white/5 hover:text-[#22B5B7]">
            Features
        </a>
        <a href="#gallery" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-300 hover:bg-white/5 hover:text-[#22B5B7]">
            Gallery
        </a>
        <div class="pt-3 border-t border-white/10 space-y-2">
            <a href="{{ route('hotel.register') }}" class="block px-3 py-2.5 rounded-lg text-base font-bold text-teal-300 bg-teal-500/10 hover:bg-teal-500/20">
                <i class="fa-solid fa-hotel mr-2"></i> Register Hotel
            </a>
            <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-200 hover:bg-white/5">
                Sign In
            </a>
            <button type="button" 
                    data-open-popup="popup-demo"
                    class="w-full mt-2 flex items-center justify-center px-5 py-3 rounded-xl text-base font-bold text-white bg-[#1A9C9E] hover:bg-[#22B5B7] transition-all shadow-lg shadow-[#1A9C9E]/30">
                <span>Request Demo</span>
                <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </button>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const menuDropdown = document.getElementById('mobileMenuDropdown');
        const menuIcon = document.getElementById('mobileMenuIcon');

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

            // Close mobile menu on clicking any link
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
