@extends('layouts.app')

@section('title', 'Hotel Registration - ' . app_name())

@section('styles')
<style>
    .register-cosmic-canvas {
        background: radial-gradient(circle at 50% 15%, #1d1148 0%, #0f0828 50%, #060312 100%);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 register-cosmic-canvas relative overflow-hidden">
    
    <!-- Background Ambient Glow -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-xl w-full relative z-10">

        <!-- Top Header & Branding -->
        <div class="text-center mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center space-x-2.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-500 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-purple-600/30 group-hover:scale-105 transition-all">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="text-left">
                    <span class="text-xl font-black text-white tracking-tight block">PAX<span class="text-purple-400">TV</span></span>
                    <span class="text-[10px] uppercase tracking-widest text-white/60 font-semibold block font-mono">Hospitality Launcher</span>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-white mt-4 tracking-tight">Register Your Hotel</h1>
            <p class="text-xs sm:text-sm text-white/70 mt-1 max-w-sm mx-auto">
                Quickly onboard your property to deploy customized smart TV launcher software.
            </p>
        </div>

        <!-- Registration Card Container -->
        <div class="bg-white/95 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-8 shadow-2xl">
            
            <!-- Validation Error Feedback -->
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                    <div class="font-bold flex items-center space-x-1.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                        <span>Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] text-rose-600 space-y-0.5 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('hotel.register.submit') }}" class="space-y-4">
                @csrf

                <!-- Hotel Property Name & Owner Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Hotel / Property Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-hotel absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="hotel_name" 
                                   value="{{ old('hotel_name') }}" 
                                   required 
                                   placeholder="e.g. Grand Royal Resort"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Contact Person / Owner <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="owner_name" 
                                   value="{{ old('owner_name') }}" 
                                   required 
                                   placeholder="e.g. John Doe"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Email & Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Official Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="hotel@domain.com"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Phone / Mobile <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   placeholder="+91 98765 43210"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                        </div>
                    </div>
                </div>

                <!-- City & Number of Smart TVs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            City / Location <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="city" 
                                   value="{{ old('city') }}" 
                                   required 
                                   placeholder="e.g. Mumbai, Goa, Delhi"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Total Connected TVs / Rooms
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-tv absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="number" 
                                   name="room_count" 
                                   min="1" 
                                   value="{{ old('room_count', 10) }}" 
                                   placeholder="10"
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all font-mono">
                        </div>
                    </div>
                </div>

                <!-- Preferred Plan Selection (Optional) -->
                @if(isset($plans) && $plans->count() > 0)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Select Subscription Plan
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="plan_id" 
                                    class="w-full pl-10 pr-8 py-2.5 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all appearance-none">
                                @foreach($plans as $p)
                                    <option value="{{ $p->id }}" {{ old('plan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} (₹{{ number_format($p->monthly_rate_per_tv, 0) }}/TV/mo — {{ strtoupper($p->billing_cycle ?? 'monthly') }})
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                @endif

                <!-- Password & Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="password" 
                                   id="reg_password"
                                   name="password" 
                                   required 
                                   minlength="6"
                                   placeholder="Minimum 6 characters"
                                   class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                            <button type="button" 
                                    onclick="toggleRegPassword('reg_password', this)" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                <i class="fa-regular fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="password" 
                                   id="reg_password_confirmation"
                                   name="password_confirmation" 
                                   required 
                                   minlength="6"
                                   placeholder="Re-type password"
                                   class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-800 transition-all">
                            <button type="button" 
                                    onclick="toggleRegPassword('reg_password_confirmation', this)" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                <i class="fa-regular fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Terms notice -->
                <p class="text-[11px] text-slate-500 text-center pt-1">
                    By submitting, you agree to Pax TV Launcher's terms of service and software licensing policies.
                </p>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#150A42] via-purple-900 to-[#150A42] hover:opacity-95 active:scale-[0.99] text-white font-extrabold text-sm tracking-wide shadow-lg shadow-purple-900/20 transition-all cursor-pointer flex items-center justify-center space-x-2">
                        <span>Create Hotel Account</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>

                <!-- Existing Account Link -->
                <div class="pt-2 text-center text-xs text-slate-600">
                    Already registered? 
                    <a href="{{ route('login') }}" class="font-extrabold text-purple-700 hover:text-purple-900 underline ml-1">
                        Sign In to Portal
                    </a>
                </div>

            </form>

        </div>

        <div class="text-center mt-6 text-xs text-white/50">
            &copy; 2005-2026 Pax TV Launcher Inc. All rights reserved.
        </div>

    </div>
</div>

<script>
function toggleRegPassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection
