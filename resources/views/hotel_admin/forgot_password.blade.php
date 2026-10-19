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
<div class="min-h-screen flex flex-col justify-between bg-[#F4F5F7] text-[#111827] relative overflow-hidden">
    <!-- Header Bar -->
    <header class="w-full px-6 lg:px-12 py-5 bg-white border-b border-[#E5E7EB] shadow-xs relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#F7F3F5] text-[#2A1820] border border-[#C5A880]/40">
                    <i class="fa-solid fa-key text-[10px] text-[#8C7250] mr-1.5"></i> Password Recovery
                </span>
            </div>

            <a href="{{ route('hotel.login') }}" class="px-3.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F4F5F7] text-[#6B7280] hover:text-[#111827] text-xs font-semibold transition-all inline-flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left text-[11px] text-[#8C7250]"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-48 bg-gradient-to-b from-[#2A1820]/5 via-transparent to-transparent pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white border border-[#E5E7EB] rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 space-y-7">
                <div class="text-center space-y-2.5">
                    <div class="w-14 h-14 rounded-2xl bg-[#2A1820] text-[#8C7250] flex items-center justify-center mx-auto shadow-md shadow-[#2A1820]/20 border border-[#3D242F]">
                        <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
                    </div>
                    <h1 class="font-serif-lux text-2xl sm:text-3xl font-semibold text-[#111827] tracking-tight">Forgot Password?</h1>
                    <p class="text-xs text-[#6B7280] font-normal leading-relaxed">
                        Enter your registered property email address and we will dispatch a 6-digit OTP verification code.
                    </p>
                </div>

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-[#FEF2F2] border border-[#DC2626]/20 text-[#B91C1C] text-xs space-y-1.5">
                        <div class="flex items-center space-x-2 font-bold">
                            <i class="fa-solid fa-circle-exclamation text-[#B91C1C]"></i>
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
                    <div class="p-4 rounded-xl bg-[#FEF2F2] border border-[#DC2626]/20 text-[#B91C1C] text-xs font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('hotel.forgot-password') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-[#111827]">
                            Property Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#6B7280]">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[#F4F5F7] border border-[#CBD5E1] rounded-xl text-sm text-[#111827] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-[#3D242F] hover:bg-[#8C7250] text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#C5A880]/25 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Send OTP Verification Code</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>

                    <div class="text-center pt-2">
                        <a href="{{ route('hotel.login') }}" class="text-xs font-bold text-[#6B7280] hover:text-[#111827] transition-colors inline-flex items-center space-x-1.5">
                            <i class="fa-solid fa-arrow-left text-[11px] text-[#8C7250]"></i>
                            <span>Return to Hotel Sign In</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full px-6 lg:px-12 py-4 bg-white border-t border-[#E5E7EB] text-xs text-[#6B7280] relative z-20">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-[#2A1820] hover:underline">Digi Emperor</a></p>
        </div>
    </footer>
</div>
@endsection
