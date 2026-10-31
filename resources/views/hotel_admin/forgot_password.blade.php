@extends('layouts.app')

@section('title', 'Forgot Password - ' . app_name() . ' Hotel Portal')

@section('styles')
<!-- Typography & Custom Theme Styling -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<style>
    .font-display, .font-mono {
        font-family: 'Space Mono', monospace;
    }
    .purple-mesh-bg {
        background: radial-gradient(circle at 15% 15%, rgba(124, 58, 237, 0.22) 0%, transparent 40%),
                    radial-gradient(circle at 85% 85%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                    radial-gradient(circle at 50% 50%, rgba(147, 51, 234, 0.08) 0%, transparent 60%),
                    #0D091B;
    }
    .glow-orb {
        filter: blur(64px);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col justify-between purple-mesh-bg text-slate-100 relative selection:bg-purple-600 selection:text-white">

    <!-- Ambient Glowing Orbs in Background -->
    <div class="absolute -top-28 -left-28 w-96 h-96 bg-purple-600/20 rounded-full glow-orb pointer-events-none"></div>
    <div class="absolute -bottom-28 -right-28 w-96 h-96 bg-indigo-600/18 rounded-full glow-orb pointer-events-none"></div>

    <!-- Geometric Accent Rings -->
    <div class="absolute top-16 right-16 w-60 h-60 border border-purple-500/10 rounded-full pointer-events-none hidden xl:block"></div>
    <div class="absolute bottom-16 left-12 w-48 h-48 border border-indigo-500/10 rounded-full pointer-events-none hidden xl:block"></div>

    <!-- Top Navigation Header -->
    <header class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/70 backdrop-blur-xl border-b border-white/[0.08] relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                    </picture>
                </a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-purple-500/15 text-purple-200 border border-purple-400/30">
                    <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse mr-2"></span>
                    Password Recovery
                </span>
            </div>

            <a href="{{ route('hotel.login') }}" class="px-3.5 py-1.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-semibold transition-all inline-flex items-center space-x-2 backdrop-blur-md">
                <i class="fa-solid fa-arrow-left text-xs text-purple-400"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-4 lg:py-6 w-full flex items-center justify-center relative z-10">
        <div class="w-full max-w-[430px] mx-auto">
            <div class="bg-white rounded-3xl p-7 sm:p-9 shadow-2xl shadow-purple-950/40 border border-purple-100/60 relative text-center">

                <!-- Badge Icon -->
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-purple-600/30 border border-purple-400/30 mb-4">
                    <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
                </div>

                <!-- Header Title -->
                <div class="space-y-1 mb-5">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Forgot Password?</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed">
                        Enter your registered property email address and we will dispatch a 6-digit OTP verification code.
                    </p>
                </div>

                <!-- Alerts -->
                @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1 text-left">
                        <div class="flex items-center space-x-1.5 font-bold text-red-800">
                            <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                            <span>Unable to send reset code:</span>
                        </div>
                        <ul class="list-disc pl-4 space-y-0.5 font-medium text-red-700 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium text-left flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-red-600 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('hotel.forgot-password') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-slate-700">Property Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-500 hover:via-violet-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Send OTP Verification Code</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>

                    <div class="text-center pt-2">
                        <a href="{{ route('hotel.login') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors inline-flex items-center space-x-1.5">
                            <i class="fa-solid fa-arrow-left text-xs text-purple-600"></i>
                            <span>Return to Hotel Sign In</span>
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/80 backdrop-blur-md border-t border-white/[0.08] text-xs text-slate-400 relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2.5 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-purple-300 hover:underline">Digi Emperor</a></p>
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span class="text-white/20">|</span>
                <a href="{{ route('contact-us') }}" class="hover:text-white transition-colors">Support</a>
            </div>
        </div>
    </footer>
</div>
@endsection
