@extends('layouts.distributor')

@section('title', 'Onboard Hotel - Distributor Hub')
@section('page_title', 'Onboard Partner Hotel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('distributor.hotels.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/90 shadow-2xs transition-all active:scale-95">
            <i class="fa-solid fa-arrow-left text-[11px] text-slate-400"></i>
            <span>Back to My Hotels</span>
        </a>

        <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-400 font-semibold">
            <span>Distributor Hub</span>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 font-extrabold">New Hotel Registration</span>
        </div>
    </div>

    <!-- Error Alerts -->
    @if ($errors->any())
        <div class="p-5 rounded-[24px] bg-rose-50/90 border border-rose-200 text-rose-800 text-xs space-y-2 shadow-2xs">
            <div class="font-black flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 pl-2 text-rose-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('distributor.hotels.store') }}" id="onboardHotelForm" class="space-y-6">
        @csrf

        <!-- Section 1: Hotel & Owner Credentials -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 border border-purple-100 text-lg font-black shadow-2xs">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">1. Property & Owner Details</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Hotel property identification and initial manager login credentials.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                <!-- Hotel Name -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Hotel Property Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-building text-xs"></i>
                        </div>
                        <input type="text" 
                               name="hotel_name" 
                               value="{{ old('hotel_name') }}" 
                               required 
                               placeholder="e.g. Grand Palace Resort & Spa" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>

                <!-- Owner / GM Name -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Owner / GM Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user-tie text-xs"></i>
                        </div>
                        <input type="text" 
                               name="owner_name" 
                               value="{{ old('owner_name') }}" 
                               required 
                               placeholder="e.g. Ramesh Patel" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>

                <!-- Admin Email -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Hotel Admin Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="e.g. admin@grandpalace.com" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>

                <!-- Password with Show/Hide Toggle -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Admin Login Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        <input type="password" 
                               id="passwordInput"
                               name="password" 
                               required 
                               placeholder="Minimum 6 characters" 
                               class="w-full pl-10 pr-10 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="passwordToggleIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Contact Phone <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </div>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               placeholder="e.g. +91 98765 43210" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        City / Region
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-city text-xs"></i>
                        </div>
                        <input type="text" 
                               name="city" 
                               value="{{ old('city') }}" 
                               placeholder="e.g. North Goa" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>

                <!-- Hotel Location / Full Address -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        Hotel Location / Full Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                        </div>
                        <input type="text" 
                               name="hotel_location" 
                               value="{{ old('hotel_location') }}" 
                               required 
                               placeholder="e.g. Beach Road, Calangute, Goa 403516" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Initial Package Selection -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 border border-purple-100 text-lg font-black shadow-2xs">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">2. Initial Licensing Package</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Select a plan to activate immediately with 30-day license, or assign later.</p>
                    </div>
                </div>
                <span class="self-start sm:self-auto text-[11px] font-black text-purple-700 bg-purple-50 border border-purple-200/60 px-3 py-1 rounded-full shadow-2xs">
                    Optional Step
                </span>
            </div>

            <!-- Plan Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Option: Assign Later / No Initial Plan -->
                <label class="group relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all hover:border-slate-300 bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 has-[:checked]:shadow-xs">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm transition-colors shadow-2xs">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </span>
                            <input type="radio" 
                                   name="plan_id" 
                                   value="" 
                                   {{ old('plan_id') == '' ? 'checked' : '' }} 
                                   class="w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                        </div>
                        <div class="font-black text-slate-900 text-sm">Assign Plan Later</div>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-relaxed font-medium">
                            Create hotel account with pending payment status. You can sell a package anytime from the Sales Ledger.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-slate-400">
                        <span>No upfront cost</span>
                    </div>
                </label>

                <!-- Active Plans -->
                @foreach($plans as $plan)
                    <label class="group relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all hover:border-slate-300 bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 has-[:checked]:shadow-xs">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="w-10 h-10 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm font-black shadow-2xs">
                                    <i class="fa-solid fa-cube"></i>
                                </span>
                                <input type="radio" 
                                       name="plan_id" 
                                       value="{{ $plan->id }}" 
                                       {{ old('plan_id') == $plan->id ? 'checked' : '' }} 
                                       class="w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                            </div>
                            <div class="font-black text-slate-900 text-sm">{{ $plan->name }}</div>
                            <div class="text-base font-black text-[#150A42] mt-1">
                                ₹{{ number_format($plan->price, 0) }}
                                <span class="text-[11px] text-slate-400 font-semibold">/month</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 leading-relaxed font-medium">
                                Includes Smart TV dashboard, guest apps, and weather/flight widgets.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-emerald-700">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-bolt text-[10px] text-emerald-600"></i>
                                <span>Instant 30D License</span>
                            </span>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Payment & License Note -->
            <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/80 text-xs text-purple-950 flex items-start space-x-3">
                <i class="fa-solid fa-circle-info text-purple-600 mt-0.5 text-sm shrink-0"></i>
                <div class="text-[11px] sm:text-xs leading-relaxed space-y-0.5">
                    <span class="font-black">License Activation Flow:</span>
                    <p class="text-purple-900/80 font-medium">
                        Selecting a plan activates the hotel license key immediately under your distributor ledger. You can purchase recurring renewals from the Sell Package portal.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Actions Bar -->
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2">
            <a href="{{ route('distributor.hotels.index') }}" class="px-5 py-3 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 font-bold text-xs transition-all text-center active:scale-95">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="px-7 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-check text-xs"></i>
                <span id="submitBtnText">Register Hotel & Generate License</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Password visibility toggle
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = document.getElementById('passwordToggleIcon');
        if (!passwordInput || !icon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Submit state feedback
    document.getElementById('onboardHotelForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('submitBtnText');
        if (btn && text) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Registering Hotel...';
        }
    });
</script>
@endsection
