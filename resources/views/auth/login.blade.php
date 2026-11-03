@extends('layouts.app')

@section('title', 'Sign In - ' . app_name())

@section('styles')
<style>
    .login-cosmic-bg {
        background: radial-gradient(ellipse at 50% 10%, #1e0f54 0%, #0d0628 55%, #050212 100%);
    }
    .login-card-shadow {
        box-shadow: 0 25px 60px -15px rgba(5, 2, 18, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 login-cosmic-bg relative overflow-hidden selection:bg-purple-600 selection:text-white">

    <!-- Subtle Ambient Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] bg-purple-600/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none hidden md:block"></div>

    <div class="w-full max-w-[430px] mx-auto relative z-10 flex flex-col items-center">

        <!-- Logo -->
        <div class="mb-7 text-center">
            <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105" title="{{ app_name() }}">
                <picture>
                    <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" 
                         alt="{{ app_name() }}" 
                         class="h-12 sm:h-14 w-auto object-contain drop-shadow-xl">
                </picture>
            </a>
        </div>

        <!-- Main Authentication Card -->
        <div class="w-full bg-white rounded-[28px] p-7 sm:p-9 shadow-2xl border border-slate-100 login-card-shadow">

            <!-- Card Heading -->
            <div class="text-left space-y-1.5 mb-6">
                <h1 class="text-2xl sm:text-[26px] font-extrabold text-slate-900 tracking-tight font-sans">
                    Welcome back
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Sign in with your email to access your account dashboard.
                </p>
            </div>

            <!-- Flash & Error Alerts -->
            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-5 p-3.5 rounded-2xl bg-purple-50 border border-purple-200 text-purple-900 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-info text-purple-600 text-sm shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2.5 font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1.5 shadow-2xs">
                    <div class="flex items-center space-x-2 font-bold text-rose-900">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                        <span>Authentication failed:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 pl-1 text-rose-700 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Unified Multi-Role Sign In Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5 text-left">
                    <label for="email" class="block text-xs font-bold text-slate-700">
                        Email Address <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}" 
                               placeholder="name@company.com" 
                               autocomplete="email" 
                               autofocus
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5 text-left">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-slate-700">
                            Password <span class="text-rose-500 font-black">*</span>
                        </label>
                        @if(Route::has('hotel.forgot-password'))
                            <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-bold text-[#150A42] hover:underline transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-lock text-xs"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               autocomplete="current-password"
                               class="w-full pl-10 pr-11 py-2.5 sm:py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] transition-all">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', 'eyeIcon')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" 
                                aria-label="Toggle password visibility">
                            <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-slate-900 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember" 
                               {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 rounded-md border-slate-300 text-[#150A42] focus:ring-[#150A42]/20 cursor-pointer">
                        <span>Remember this device</span>
                    </label>
                </div>

                <!-- Primary Submit Button -->
                <button type="submit" 
                        class="w-full py-3 sm:py-3.5 px-5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-sm shadow-xl shadow-[#150A42]/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Subtle Security Note -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-center space-x-2 text-xs text-slate-400 font-medium">
                <i class="fa-solid fa-shield-halved text-[11px] text-emerald-600"></i>
                <span>Enterprise Encrypted Authentication</span>
            </div>

        </div>

        <!-- Subtle Bottom Copyright Text -->
        <p class="mt-7 text-xs font-medium text-slate-400 text-center tracking-tight">
            &copy; {{ date('Y') }} {{ app_name() }}. All rights reserved.
        </p>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
