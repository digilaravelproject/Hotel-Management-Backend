@extends('layouts.landing')

@section('title', 'Hotel Admin Sign In - PAX TV')

@section('styles')
<!-- Luxury Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --color-gold: #E5A853;
        --color-gold-hover: #F2B660;
        --color-gold-light: #F7D59E;
        --color-gold-dark: #B88035;
        --color-bg-dark: #080B10;
        --color-bg-card: #0F131C;
    }

    .font-serif-lux {
        font-family: 'Playfair Display', 'Cormorant Garamond', 'Cinzel', Georgia, serif !important;
    }

    .btn-gold-pill {
        background-color: #E5A853;
        color: #0A0D14;
        font-weight: 700;
        border-radius: 9999px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 18px rgba(229, 168, 83, 0.28);
    }
    .btn-gold-pill:hover {
        background-color: #F2B660;
        transform: translateY(-1.5px);
        box-shadow: 0 8px 26px rgba(229, 168, 83, 0.42);
    }

    .input-luxury {
        background-color: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #FFFFFF;
        transition: all 0.2s ease-in-out;
    }
    .input-luxury:focus {
        background-color: rgba(255, 255, 255, 0.07);
        border-color: #E5A853;
        outline: none;
        box-shadow: 0 0 0 3px rgba(229, 168, 83, 0.18);
    }
    .input-luxury::placeholder {
        color: #78716C;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#080B10] text-slate-100 relative overflow-hidden">

    <!-- Ambient Luxury Resort Background with Cinematic Vignette -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/landing/demo.svg') }}" 
             onerror="this.onerror=null;this.src='{{ asset('images/landing/demo.jpg') }}';" 
             alt="Luxury Hotel Architecture" 
             class="w-full h-full object-cover opacity-25 filter blur-[1px] scale-105">
        <div class="absolute inset-0 bg-gradient-to-b from-[#080B10]/95 via-[#080B10]/90 to-[#080B10]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(229,168,83,0.15),transparent)]"></div>
    </div>

    <!-- ========================================================================= -->
    <!-- TOP HEADER / BRAND BAR -->
    <!-- ========================================================================= -->
    <header class="relative z-10 w-full px-6 lg:px-16 py-6 border-b border-white/10 backdrop-blur-md bg-[#080B10]/60">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 text-[#E5A853] flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    <svg viewBox="0 0 40 40" class="w-full h-full drop-shadow-sm" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="20,1 25,12 36,9 31,20 39,28 28,31 25,39 20,31 15,39 12,31 1,28 9,20 4,9 15,12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <polygon points="20,7 28,20 20,33 12,20" fill="none" stroke="currentColor" stroke-width="1.4"/>
                        <polygon points="20,13 24,20 20,27 16,20" fill="currentColor"/>
                        <circle cx="20" cy="20" r="2.2" fill="#080B10"/>
                    </svg>
                </div>
                <div class="flex items-center space-x-2.5">
                    <span class="font-serif-lux font-bold text-lg sm:text-xl tracking-[0.22em] text-[#E5A853] uppercase leading-none mt-0.5 group-hover:text-[#F2B660] transition-colors">
                        PAX TV
                    </span>
                    <span class="hidden sm:inline-block px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-[#E5A853]/15 text-[#E5A853] border border-[#E5A853]/30">
                        Hotel Portal
                    </span>
                </div>
            </a>

            <!-- Return to Website Button -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="px-4 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#E5A853]/40 text-stone-200 hover:text-white text-xs font-medium tracking-wide transition-all inline-flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-left text-[11px] text-[#E5A853]"></i>
                    <span>Back to Website</span>
                </a>
            </div>

        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- MAIN CONSOLE CONTAINER (SPLIT SHOWCASE + LOGIN FORM) -->
    <!-- ========================================================================= -->
    <main class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 py-12 lg:py-16 w-full flex items-center justify-center">
        
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            <!-- Left Editorial & Highlights Column (Hidden on Small Screens) -->
            <div class="hidden lg:flex lg:col-span-6 flex-col space-y-8 text-left pr-4">
                
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#E5A853]/10 border border-[#E5A853]/25 text-[#E5A853] text-[11px] font-bold uppercase tracking-[0.2em]">
                        <i class="fa-solid fa-hotel text-xs"></i>
                        <span>HOTEL MANAGEMENT SYSTEM</span>
                    </div>

                    <h1 class="font-serif-lux text-4xl xl:text-5xl font-medium tracking-tight text-white leading-[1.18]">
                        Command Your In-Room Guest Entertainment
                    </h1>

                    <p class="text-sm text-stone-300 leading-relaxed font-normal max-w-lg">
                        Log in to configure live broadcast channels, push hotel promotions, personalize guest welcome screens, and synchronize room televisions across your property in real time.
                    </p>
                </div>

                <!-- 3 Feature Highlights with Golden Badges -->
                <div class="space-y-4 pt-2">
                    
                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white/[0.03] border border-white/10 backdrop-blur-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#E5A853]/15 border border-[#E5A853]/30 text-[#E5A853] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-tv text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white tracking-wide">Fleet Device Management</h4>
                            <p class="text-xs text-stone-400 leading-relaxed">Instantly pair and monitor smart TVs in 500+ rooms using dedicated 16-digit license keys.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white/[0.03] border border-white/10 backdrop-blur-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#E5A853]/15 border border-[#E5A853]/30 text-[#E5A853] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bell-concierge text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white tracking-wide">Digital Guest Concierge</h4>
                            <p class="text-xs text-stone-400 leading-relaxed">Update room service menus, spa bookings, hotel facilities, and interactive guides on the fly.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 rounded-2xl bg-white/[0.03] border border-white/10 backdrop-blur-xs">
                        <div class="w-10 h-10 rounded-xl bg-[#E5A853]/15 border border-[#E5A853]/30 text-[#E5A853] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white tracking-wide">Automated Checkout Privacy</h4>
                            <p class="text-xs text-stone-400 leading-relaxed">Zero guest streaming data stored. All logins and history auto-wiped immediately at checkout.</p>
                        </div>
                    </div>

                </div>

                <!-- Trusted By Badge -->
                <div class="pt-2 flex items-center space-x-3 text-xs text-stone-400">
                    <div class="flex -space-x-1.5 overflow-hidden">
                        <div class="w-6 h-6 rounded-full bg-[#E5A853] text-[#080B10] font-bold text-[9px] flex items-center justify-center border border-[#080B10]">★</div>
                        <div class="w-6 h-6 rounded-full bg-stone-700 text-white font-bold text-[9px] flex items-center justify-center border border-[#080B10]">★</div>
                        <div class="w-6 h-6 rounded-full bg-[#E5A853] text-[#080B10] font-bold text-[9px] flex items-center justify-center border border-[#080B10]">★</div>
                    </div>
                    <span>Powering boutique hotels and 5-star luxury resorts globally.</span>
                </div>

            </div>

            <!-- Right Column: Glassmorphism Login Card -->
            <div class="lg:col-span-6 w-full max-w-md mx-auto">
                
                <div class="relative bg-[#0F131C]/90 backdrop-blur-xl border border-white/15 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-black/80 space-y-7">
                    
                    <!-- Ambient Top Glow on Card -->
                    <div class="absolute -top-px left-10 right-10 h-px bg-gradient-to-r from-transparent via-[#E5A853] to-transparent"></div>

                    <!-- Card Header -->
                    <div class="text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-b from-[#E5A853]/25 to-[#E5A853]/5 border border-[#E5A853]/40 flex items-center justify-center mx-auto shadow-lg shadow-[#E5A853]/10">
                            <i class="fa-solid fa-hotel text-2xl text-[#E5A853]"></i>
                        </div>
                        <h2 class="font-serif-lux text-2xl sm:text-3xl font-medium text-white tracking-tight">
                            Hotel Admin Portal
                        </h2>
                        <p class="text-xs text-stone-400 font-normal">
                            Sign in to configure and monitor your hotel's Smart TV fleet
                        </p>
                    </div>

                    <!-- Flash & Validation Alerts -->
                    @if(session('success'))
                        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-start space-x-2.5">
                            <i class="fa-solid fa-circle-check text-sm text-emerald-400 mt-0.5"></i>
                            <div class="leading-relaxed font-medium">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="p-4 rounded-2xl bg-[#E5A853]/15 border border-[#E5A853]/35 text-[#F7D59E] text-xs flex items-start space-x-2.5">
                            <i class="fa-solid fa-circle-info text-sm text-[#E5A853] mt-0.5"></i>
                            <div class="leading-relaxed font-medium">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs space-y-1.5">
                            <div class="flex items-center space-x-2 font-bold text-rose-200">
                                <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                                <span>Please resolve the following:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1 text-rose-300/90 font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Hotel Sign-in Form -->
                    <form action="{{ route('hotel.login') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Email Input -->
                        <div class="space-y-2 text-left">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-300">
                                Property Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-500">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       required 
                                       value="{{ old('email') }}" 
                                       placeholder="admin@grandresort.com" 
                                       autocomplete="email" 
                                       autofocus
                                       class="input-luxury w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-2 text-left">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-300">
                                    Password
                                </label>
                                @if (Route::has('hotel.forgot-password'))
                                    <a href="{{ route('hotel.forgot-password') }}" class="text-[11px] font-semibold text-[#E5A853] hover:text-[#F2B660] transition-colors">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-500">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input type="password" 
                                       id="passwordInput" 
                                       name="password" 
                                       required 
                                       placeholder="••••••••" 
                                       autocomplete="current-password"
                                       class="input-luxury w-full pl-11 pr-11 py-3 rounded-xl text-sm font-medium">
                                <button type="button" 
                                        onclick="togglePasswordVisibility()" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-stone-400 hover:text-white transition-colors cursor-pointer"
                                        aria-label="Toggle password visibility">
                                    <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me Option -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center space-x-2.5 cursor-pointer text-stone-300 hover:text-white transition-colors select-none">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded bg-white/5 border border-white/20 text-[#E5A853] focus:ring-[#E5A853]/30 focus:ring-offset-0 cursor-pointer">
                                <span>Remember this workstation</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 px-6 btn-gold-pill text-xs tracking-wider uppercase flex items-center justify-center space-x-2 cursor-pointer">
                                <span>Sign In to Hotel Admin</span>
                                <span class="text-sm font-bold font-mono">›</span>
                            </button>
                        </div>

                    </form>

                    <!-- Property Access Request Banner -->
                    <div class="pt-4 border-t border-white/10 text-center space-y-2">
                        <p class="text-xs text-stone-400">
                            Looking to onboard your property or need license keys?
                        </p>
                        <a href="{{ route('landing') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#E5A853] hover:text-[#F2B660] transition-colors">
                            <span>Request a Property Demo & Pricing</span>
                            <span class="font-mono">›</span>
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- ========================================================================= -->
    <!-- BOTTOM FOOTER BAR -->
    <!-- ========================================================================= -->
    <footer class="relative z-10 w-full px-6 lg:px-16 py-6 border-t border-white/10 backdrop-blur-md bg-[#080B10]/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
            <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
            <p class="text-stone-400">
                Developed by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="text-[#E5A853] hover:text-[#F2B660] font-medium transition-colors underline decoration-[#E5A853]/40 underline-offset-2 hover:decoration-[#F2B660]">Digi Emperor</a>
            </p>
            <div class="flex items-center space-x-5">
                <a href="{{ route('privacy-policy') }}" class="hover:text-stone-300 transition-colors">Privacy Policy</a>
                <span class="text-stone-700">|</span>
                <a href="{{ route('contact-us') }}" class="hover:text-stone-300 transition-colors">Support & Contact</a>
            </div>
        </div>
    </footer>

</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs';
        }
    }
</script>
@endsection
