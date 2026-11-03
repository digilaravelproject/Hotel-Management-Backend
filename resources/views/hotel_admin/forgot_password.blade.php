@extends('layouts.app')

@section('title', 'Forgot Password - ' . app_name())

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

    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] bg-purple-600/15 rounded-full blur-[120px] pointer-events-none"></div>

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

        <!-- Password Recovery Card -->
        <div class="w-full bg-white rounded-[28px] p-7 sm:p-9 shadow-2xl border border-slate-100 login-card-shadow text-center">

            <!-- Badge Icon -->
            <div class="w-13 h-13 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center mx-auto shadow-xs mb-4">
                <i class="fa-solid fa-unlock-keyhole text-xl"></i>
            </div>

            <!-- Header Title -->
            <div class="space-y-1 mb-6">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-sans">
                    Forgot Password?
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Enter your registered account email and we'll send you an OTP verification code.
                </p>
            </div>

            <!-- Alerts -->
            @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 text-left">
                    <div class="flex items-center space-x-1.5 font-bold text-rose-900">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Unable to send reset code:</span>
                    </div>
                    <ul class="list-disc pl-4 space-y-0.5 font-medium text-rose-700 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium text-left flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('hotel.forgot-password') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5 text-left">
                    <label class="block text-xs font-bold text-slate-700">Account Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               required 
                               value="{{ old('email') }}" 
                               placeholder="name@company.com" 
                               autocomplete="email" 
                               autofocus
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] transition-all">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-sm shadow-xl shadow-[#150A42]/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer">
                    <span>Send Verification Code</span>
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-[#150A42] transition-colors inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Return to Sign In</span>
                    </a>
                </div>
            </form>

        </div>

        <p class="mt-7 text-xs font-medium text-slate-400 text-center tracking-tight">
            &copy; {{ date('Y') }} {{ app_name() }}. All rights reserved.
        </p>

    </div>
</div>
@endsection
