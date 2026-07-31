@extends('layouts.app')

@section('title', 'Reset Password - HotelTV')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-slate-950 text-slate-100 relative overflow-hidden">
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-violet-600/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-2xl border border-slate-800 rounded-3xl p-8 shadow-2xl shadow-indigo-950/50 space-y-6 relative z-10">
        <div class="text-center space-y-3">
            <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Verification Required</h1>
            <p class="text-xs text-slate-400 leading-relaxed">We sent a 6-digit OTP code to <strong class="text-white">{{ $email }}</strong>. Enter code & set your new password.</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('hotel.reset-password') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-300">6-Digit OTP Code</label>
                <input type="text" name="otp_code" required maxlength="6" placeholder="123456" autofocus
                    class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-center text-xl font-bold tracking-[0.3em] text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-300">New Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-300">Confirm New Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                    class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                Verify & Reset Password <i class="fa-solid fa-circle-check ml-1.5 text-xs"></i>
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-medium text-slate-400 hover:text-slate-200 transition-colors inline-flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i> Request New OTP
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
