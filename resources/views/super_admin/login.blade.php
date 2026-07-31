@extends('layouts.app')

@section('title', 'Super Admin Login - HotelTV')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-slate-950 text-slate-100 relative overflow-hidden">
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-2xl border border-slate-800 rounded-3xl p-8 shadow-2xl shadow-rose-950/40 space-y-6 relative z-10">
        <div class="text-center space-y-3">
            <div class="w-14 h-14 bg-gradient-to-tr from-rose-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-rose-500/30">
                <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Super Admin Portal</h1>
            <p class="text-xs text-slate-400">Master platform access and system control</p>
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

        <form action="{{ route('super-admin.login') }}" method="POST" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-300">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                    class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-300">Password</label>
                <input type="password" name="password" required placeholder="••••••••" autocomplete="current-password"
                    class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all">
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-rose-600/30 transition-all hover:-translate-y-0.5">
                Sign In to Master Control <i class="fa-solid fa-lock ml-1.5 text-xs"></i>
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('landing') }}" class="text-xs font-medium text-slate-400 hover:text-slate-200 transition-colors inline-flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i> Return to Homepage
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
