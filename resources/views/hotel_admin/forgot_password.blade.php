@extends('layouts.landing')

@section('title', 'Forgot Password - PAX TV Hotel Portal')

@section('styles')
<!-- Luxury Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --color-gold: #E5A853;
        --color-gold-hover: #F2B660;
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

    <!-- Ambient Luxury Resort Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/landing/demo.svg') }}" 
             onerror="this.onerror=null;this.src='{{ asset('images/landing/demo.jpg') }}';" 
             alt="Luxury Hotel Architecture" 
             class="w-full h-full object-cover opacity-20 filter blur-[1px]">
        <div class="absolute inset-0 bg-gradient-to-b from-[#080B10]/95 via-[#080B10]/90 to-[#080B10]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(229,168,83,0.12),transparent)]"></div>
    </div>

    <!-- Header Bar -->
    <header class="relative z-10 w-full px-6 lg:px-16 py-6 border-b border-white/10 backdrop-blur-md bg-[#080B10]/60">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 text-[#E5A853] flex items-center justify-center">
                    <svg viewBox="0 0 40 40" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="20,1 25,12 36,9 31,20 39,28 28,31 25,39 20,31 15,39 12,31 1,28 9,20 4,9 15,12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <polygon points="20,7 28,20 20,33 12,20" fill="none" stroke="currentColor" stroke-width="1.4"/>
                        <polygon points="20,13 24,20 20,27 16,20" fill="currentColor"/>
                        <circle cx="20" cy="20" r="2.2" fill="#080B10"/>
                    </svg>
                </div>
                <span class="font-serif-lux font-bold text-lg tracking-[0.22em] text-[#E5A853] uppercase leading-none mt-0.5">
                    PAX TV
                </span>
            </a>

            <a href="{{ route('hotel.login') }}" class="px-4 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 hover:border-[#E5A853]/40 text-stone-200 hover:text-white text-xs font-medium tracking-wide transition-all inline-flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-[#E5A853]"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </header>

    <!-- Main Card -->
    <main class="relative z-10 max-w-md mx-auto px-6 py-12 lg:py-16 w-full flex items-center justify-center">
        <div class="w-full bg-[#0F131C]/90 backdrop-blur-xl border border-white/15 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-black/80 space-y-6 relative">
            <div class="absolute -top-px left-10 right-10 h-px bg-gradient-to-r from-transparent via-[#E5A853] to-transparent"></div>

            <div class="text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-[#E5A853]/15 border border-[#E5A853]/35 text-[#E5A853] flex items-center justify-center mx-auto shadow-lg shadow-[#E5A853]/10">
                    <i class="fa-solid fa-key text-2xl"></i>
                </div>
                <h1 class="font-serif-lux text-2xl font-medium text-white tracking-tight">Forgot Password?</h1>
                <p class="text-xs text-stone-400 font-normal leading-relaxed">
                    Enter your registered property email address and we will dispatch a 6-digit OTP verification code.
                </p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs space-y-1">
                    <ul class="list-disc pl-4 space-y-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('hotel.forgot-password') }}" method="POST" class="space-y-5">
                @csrf

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
                               placeholder="admin@hotel.com" 
                               autocomplete="email" 
                               autofocus
                               class="input-luxury w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 btn-gold-pill text-xs tracking-wider uppercase flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Send OTP Code</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('hotel.login') }}" class="text-xs font-medium text-stone-400 hover:text-white transition-colors inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrow-left text-[11px] text-[#E5A853]"></i>
                        <span>Return to Hotel Sign In</span>
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full px-6 lg:px-16 py-6 border-t border-white/10 backdrop-blur-md bg-[#080B10]/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
            <p>© {{ date('Y') }} PAX TV. All rights reserved.</p>
            <p class="text-stone-400">
                Developed by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="text-[#E5A853] hover:text-[#F2B660] font-medium transition-colors">Digi Emperor</a>
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
