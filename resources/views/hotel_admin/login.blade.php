@extends('layouts.app')

@section('title', 'Hotel Portal Sign In - HotelTV')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-slate-50 text-slate-800 relative overflow-hidden">
    <!-- Background Light Blobs -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-white border border-slate-200/80 rounded-3xl p-8 shadow-xl shadow-slate-200/60 space-y-6 relative z-10">
        <!-- Logo & Header -->
        <div class="text-center space-y-3">
            <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-600/30">
                <i class="fa-solid fa-hotel text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Hotel Admin Portal</h1>
            <p class="text-xs text-slate-500 font-medium">Access and control your Smart TV network</p>
        </div>

        <!-- Validation Alert -->
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('hotel.login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Email Address (Username)</label>
                <div class="relative">
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-700">Password</label>
                    <a href="{{ route('hotel.forgot-password') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Forgot Password?</a>
                </div>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" required placeholder="••••••••" autocomplete="current-password"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all pr-10">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i id="eyeIcon" class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                Sign In to Portal <i class="fa-solid fa-arrow-right-to-bracket ml-1.5 text-xs"></i>
            </button>

            <!-- Back Link -->
            <div class="text-center pt-2">
                <a href="{{ route('landing') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors inline-flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i> Return to Homepage
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-sm';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-sm';
        }
    }
</script>
@endsection
