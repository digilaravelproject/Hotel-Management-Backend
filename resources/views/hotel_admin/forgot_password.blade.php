@extends('layouts.app')

@section('title', 'Forgot Password - ' . app_name() . ' Hotel Portal')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-serif-lux {
        font-family: 'Playfair Display', Georgia, serif;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#F8FAFC] text-[#1E293B] relative overflow-hidden">
    <!-- Header Bar -->
    <header class="w-full px-6 lg:px-12 py-5 bg-white border-b border-[#E2E8F0] shadow-xs relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#F9F5EB] text-[#6E1841] border border-[#C5A059]/40">
                    <i class="fa-solid fa-key text-[10px] text-[#C5A059] mr-1.5"></i> Password Recovery
                </span>
            </div>

            <a href="{{ route('hotel.login') }}" class="px-3.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#64748B] hover:text-[#1E293B] text-xs font-semibold transition-all inline-flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left text-[11px] text-[#C5A059]"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-48 bg-gradient-to-b from-[#6E1841]/5 via-transparent to-transparent pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 space-y-7">
                <div class="text-center space-y-2.5">
                    <div class="w-14 h-14 rounded-2xl bg-[#6E1841] text-[#C5A059] flex items-center justify-center mx-auto shadow-md shadow-[#6E1841]/20 border border-[#8A1C56]">
                        <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
                    </div>
                    <h1 class="font-serif-lux text-2xl sm:text-3xl font-semibold text-[#1E293B] tracking-tight">Forgot Password?</h1>
                    <p class="text-xs text-[#64748B] font-normal leading-relaxed">
                        Enter your registered property email address and we will dispatch a 6-digit OTP verification code.
                    </p>
                </div>

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs space-y-1.5">
                        <div class="flex items-center space-x-2 font-bold">
                            <i class="fa-solid fa-circle-exclamation text-[#DC2626]"></i>
                            <span>Unable to send reset code:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('hotel.forgot-password') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-[#1E293B]">
                            Property Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#C5A059] focus:ring-2 focus:ring-[#C5A059]/20 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-[#C5A059] hover:bg-[#A88440] text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#C5A059]/25 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Send OTP Verification Code</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>

                    <div class="text-center pt-2">
                        <a href="{{ route('hotel.login') }}" class="text-xs font-bold text-[#64748B] hover:text-[#1E293B] transition-colors inline-flex items-center space-x-1.5">
                            <i class="fa-solid fa-arrow-left text-[11px] text-[#C5A059]"></i>
                            <span>Return to Hotel Sign In</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full px-6 lg:px-12 py-4 bg-white border-t border-[#E2E8F0] text-xs text-[#64748B] relative z-20">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-[#6E1841] hover:underline">Digi Emperor</a></p>
        </div>
    </footer>
</div>
@endsection
