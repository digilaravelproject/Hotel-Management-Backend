@extends('layouts.app')

@section('title', 'Hotel Registration & Smart TV Licensing - ' . app_name())

@section('styles')
<style>
    /* Premium Modern Hospitality Canvas */
    .hotel-register-canvas {
        background-color: #060E1E;
        background-image: 
            radial-gradient(at 15% 15%, rgba(0, 251, 255, 0.08) 0px, transparent 55%),
            radial-gradient(at 85% 85%, rgba(14, 37, 86, 0.7) 0px, transparent 65%),
            radial-gradient(at 50% 0%, rgba(11, 30, 56, 0.9) 0px, transparent 80%);
    }

    /* Step transitions */
    .wizard-step {
        transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
    }
    .wizard-step.hidden {
        display: none !important;
    }

    /* Plan card active styles */
    .plan-card {
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .plan-card.active {
        border-color: #0A162D !important;
        background-color: #F8FAFC !important;
        box-shadow: 0 10px 25px -5px rgba(10, 22, 45, 0.12), 0 0 0 2px #0A162D !important;
    }
</style>
<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection

@section('content')
<div class="min-h-screen py-6 sm:py-12 px-3 sm:px-6 lg:px-8 hotel-register-canvas relative flex flex-col justify-center">

    <!-- Ambient Subtle Glow (Clean, Professional) -->
    <div class="absolute top-12 left-1/2 -translate-x-1/2 w-full max-w-4xl h-72 bg-[#00fbff]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-3xl w-full mx-auto relative z-10">

        <!-- Header / Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center space-x-3 group">
                @if(app_logo_url())
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-10 sm:h-12 w-auto object-contain drop-shadow-md">
                @else
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-[#0E2556] border border-[#00fbff]/30 flex items-center justify-center text-[#00fbff] text-xl font-black shadow-lg shadow-black/40 group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                @endif
                <div class="text-left">
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight block">
                        PAX<span class="text-[#00fbff]">TV</span>
                    </span>
                    <span class="text-[10px] sm:text-[11px] uppercase tracking-widest text-slate-300 font-semibold block font-mono">
                        Hospitality Cloud Platform
                    </span>
                </div>
            </a>
            <h1 class="text-xl sm:text-3xl font-black text-white mt-3 tracking-tight">
                Hotel Property Registration
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-md mx-auto">
                Set up your hotel property and activate Smart TV Launcher displays in 3 simple steps.
            </p>
        </div>

        <!-- 3-Step Clean SaaS Stepper Header -->
        <div class="mb-5 sm:mb-7 bg-white/10 backdrop-blur-md border border-white/15 p-1.5 sm:p-2 rounded-2xl">
            <div class="grid grid-cols-3 gap-1 sm:gap-2 text-center">
                
                <!-- Step 1 Tab -->
                <div id="stepTab1" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl bg-[#0A162D] text-white shadow-md border border-[#00fbff]/30 transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#00fbff] text-[#0A162D] font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">1</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider block">Step 1</span>
                        <span class="text-[9px] sm:text-[11px] text-slate-200 block truncate">Property & Admin</span>
                    </div>
                </div>

                <!-- Step 2 Tab -->
                <div id="stepTab2" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl text-white/60 hover:text-white transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/10 text-white/70 font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">2</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider block">Step 2</span>
                        <span class="text-[9px] sm:text-[11px] text-white/50 block truncate">Plan & Smart TVs</span>
                    </div>
                </div>

                <!-- Step 3 Tab -->
                <div id="stepTab3" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl text-white/60 hover:text-white transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/10 text-white/70 font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">3</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider block">Step 3</span>
                        <span class="text-[9px] sm:text-[11px] text-white/50 block truncate">Review & Pay</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Main Form Container -->
        <div class="bg-white rounded-3xl p-5 sm:p-8 shadow-2xl border border-slate-100">

            <!-- Global Error / Notice Banner -->
            <div id="globalErrorAlert" class="hidden mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                <div class="font-bold flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                    <span id="globalErrorTitle">Please resolve the following issue:</span>
                </div>
                <div id="globalErrorMessage" class="text-[11px] text-rose-600 pl-1 font-sans"></div>
            </div>

            <form id="hotelRegisterWizardForm" onsubmit="return false;" enctype="multipart/form-data">
                @csrf

                <!-- ======================================================== -->
                <!-- STEP 1: Property Details & Administrator Account         -->
                <!-- ======================================================== -->
                <div id="wizardStep1" class="wizard-step space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#0A162D] font-mono">Step 1 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Property & Account Information</h2>
                        </div>
                        <span class="text-xs text-slate-400 font-medium hidden sm:inline">Fields with <span class="text-rose-500">*</span> are required</span>
                    </div>

                    <!-- Section A: Hotel Property Information -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 text-xs font-bold text-slate-900 uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 text-[#0A162D] flex items-center justify-center text-xs">
                                <i class="fa-solid fa-hotel"></i>
                            </span>
                            <span>Hotel Property Details</span>
                        </div>

                        <!-- Hotel Name & City -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Hotel / Property Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-building absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_hotel_name" 
                                           name="hotel_name" 
                                           required 
                                           placeholder="e.g. Grand Horizon Palace & Resort"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    City <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-city absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_city" 
                                           name="city" 
                                           required 
                                           placeholder="e.g. Mumbai, Goa, Delhi"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Pin Code & Front Desk Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Pin / Postal Code
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_postal_code" 
                                           name="postal_code" 
                                           placeholder="e.g. 400001"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Front Desk / Reception Contact
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-headset absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_desk_phone" 
                                           name="desk_phone" 
                                           placeholder="e.g. +91 22 2345 6789"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Complete Property Address -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Complete Property Address <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                <textarea id="input_hotel_location" 
                                          name="hotel_location" 
                                          rows="2" 
                                          required 
                                          placeholder="Street address, landmark, area, state..."
                                          class="w-full pl-9 pr-3.5 py-2 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Hotel Logo Upload (Optional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                <span>Hotel Logo (Optional)</span>
                                <span class="text-[10px] text-slate-400">PNG, JPG, SVG up to 5MB</span>
                            </label>
                            
                            <div class="flex items-center space-x-3.5 p-3.5 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition-all">
                                <div id="logoPreviewWrapper" class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                                    <i id="logoDefaultIcon" class="fa-solid fa-image text-slate-400 text-lg"></i>
                                    <img id="logoPreviewImg" src="" alt="Preview" class="w-full h-full object-contain hidden">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <label for="hotel_logo_input" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#0A162D] text-xs font-bold text-slate-700 shadow-2xs cursor-pointer transition-all">
                                        <i class="fa-solid fa-upload text-[#0A162D] mr-1.5 text-[11px]"></i>
                                        <span>Choose Logo File</span>
                                    </label>
                                    <input type="file" 
                                           id="hotel_logo_input" 
                                           name="hotel_logo" 
                                           accept="image/*" 
                                           onchange="previewHotelLogo(this)" 
                                           class="sr-only">
                                    <div id="logoFileName" class="text-[11px] text-slate-400 font-medium truncate mt-1">
                                        Logo will appear on guest TVs & welcome screens.
                                    </div>
                                </div>

                                <button type="button" 
                                        id="removeLogoBtn" 
                                        onclick="clearHotelLogo()" 
                                        class="text-xs text-rose-500 hover:text-rose-700 font-bold p-1 hidden">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section B: Administrator Contact & Login Credentials -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center space-x-2 text-xs font-bold text-slate-900 uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 text-[#0A162D] flex items-center justify-center text-xs">
                                <i class="fa-solid fa-user-shield"></i>
                            </span>
                            <span>Hotel Admin Account Credentials</span>
                        </div>

                        <!-- Owner Name, Email, Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Owner / Manager Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_owner_name" 
                                           name="owner_name" 
                                           required 
                                           placeholder="e.g. Rajesh Kumar"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Official Email Address <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="email" 
                                           id="input_email" 
                                           name="email" 
                                           required 
                                           placeholder="manager@hotel.com"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Mobile / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" 
                                           id="input_phone" 
                                           name="phone" 
                                           required 
                                           placeholder="+91 98765 43210"
                                           class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Passwords -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Create Admin Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="password" 
                                           id="input_password" 
                                           name="password" 
                                           required 
                                           minlength="6" 
                                           placeholder="At least 6 characters"
                                           class="w-full pl-9 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                    <button type="button" 
                                            onclick="toggleWizardPassword('input_password', this)" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                        <i class="fa-regular fa-eye-slash text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Confirm Admin Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="password" 
                                           id="input_password_confirmation" 
                                           name="password_confirmation" 
                                           required 
                                           minlength="6" 
                                           placeholder="Repeat password"
                                           class="w-full pl-9 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                    <button type="button" 
                                            onclick="toggleWizardPassword('input_password_confirmation', this)" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                        <i class="fa-regular fa-eye-slash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1 Actions -->
                    <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-[#0A162D] font-bold transition-colors">
                            Already registered? Sign In
                        </a>
                        <button type="button" 
                                onclick="goToStep(2)" 
                                class="px-6 py-3 rounded-2xl bg-[#0A162D] hover:bg-[#0E2556] active:scale-98 text-white font-black text-xs sm:text-sm tracking-wide shadow-md shadow-slate-950/20 transition-all flex items-center space-x-2 cursor-pointer">
                            <span>Continue to Plan & Displays</span>
                            <i class="fa-solid fa-arrow-right text-xs text-[#00fbff]"></i>
                        </button>
                    </div>

                </div>

                <!-- ======================================================== -->
                <!-- STEP 2: Plan Selection, Smart TVs & Live Calculation     -->
                <!-- ======================================================== -->
                <div id="wizardStep2" class="wizard-step hidden space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#0A162D] font-mono">Step 2 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Select Plan & Smart TV Displays</h2>
                        </div>
                        <span class="text-xs text-slate-400 font-medium hidden sm:inline">Transparent live pricing calculation</span>
                    </div>

                    <!-- 1. Subscription Tier Cards -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs sm:text-sm font-black text-slate-900">
                                1. Choose Subscription Tier <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-[#0A162D] font-bold font-mono">
                                {{ $plans->count() }} Available Tiers
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="planCardsContainer">
                            @foreach($plans as $p)
                                @php
                                    $isFirst = $loop->first;
                                    $cycle = strtolower($p->billing_cycle ?? 'monthly');
                                    $monthlyRate = (float) $p->monthly_rate_per_tv;
                                    $annualTotal = (float) $p->annual_total_per_tv;
                                    $effTax = $p->tax ?: $defaultTax;
                                    $taxRate = $effTax ? (float)$effTax->rate : 0.0;
                                    $taxName = $effTax ? $effTax->name : 'No Tax';
                                    $taxType = $p->tax_type ?? 'exclusive';
                                    $totalMenusInSystem = \App\Models\Plan::totalMenusCount();
                                    $menusCount = is_array($p->allowed_menus) ? count($p->allowed_menus) : $totalMenusInSystem;
                                @endphp
                                <label class="plan-card relative flex flex-col justify-between p-4 sm:p-5 rounded-2xl border-2 cursor-pointer transition-all {{ $isFirst ? 'active border-[#0A162D]' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                       onclick="selectPlanCard(this)"
                                       data-id="{{ $p->id }}"
                                       data-name="{{ $p->name }}"
                                       data-monthly-rate="{{ $monthlyRate }}"
                                       data-annual-total="{{ $annualTotal }}"
                                       data-cycle="{{ $cycle }}"
                                       data-tax-rate="{{ $taxRate }}"
                                       data-tax-name="{{ $taxName }}"
                                       data-tax-type="{{ $taxType }}"
                                       data-is-split="{{ ($effTax && $effTax->is_split) ? '1' : '0' }}">
                                    
                                    <input type="radio" 
                                           name="plan_id" 
                                           value="{{ $p->id }}" 
                                           {{ $isFirst ? 'checked' : '' }} 
                                           class="sr-only plan-radio-input">

                                    <div>
                                        <!-- Top Badge Row -->
                                        <div class="flex items-start justify-between gap-1 mb-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider font-mono shrink-0 {{ $cycle === 'yearly' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                                                @if($cycle === 'yearly')
                                                    <i class="fa-solid fa-crown text-[8px] sm:text-[9px] text-amber-600 mr-1"></i>ANNUAL PLAN
                                                @else
                                                    <i class="fa-solid fa-calendar-days text-[8px] sm:text-[9px] text-slate-600 mr-1"></i>MONTHLY PLAN
                                                @endif
                                            </span>
                                            
                                            <!-- Checkmark Icon Bubble -->
                                            <div class="plan-check-bubble w-5 h-5 rounded-full flex items-center justify-center text-xs shrink-0 {{ $isFirst ? 'bg-[#0A162D] text-white' : 'bg-slate-100 text-transparent' }}">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                            </div>
                                        </div>

                                        <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">{{ $p->name }}</h3>
                                        @if($p->description)
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $p->description }}</p>
                                        @endif

                                        <!-- Price Box -->
                                        <div class="mt-2.5 p-2.5 sm:p-3 rounded-xl bg-slate-50/90 border border-slate-200/80 space-y-1">
                                            <div class="flex items-baseline space-x-1 font-mono">
                                                <span class="text-xl sm:text-2xl font-black text-[#0A162D]">₹{{ number_format($monthlyRate, 0) }}</span>
                                                <span class="text-xs text-slate-500 font-sans font-bold">/ TV / month</span>
                                            </div>

                                            @if($cycle === 'yearly')
                                                <div class="text-[10px] font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/80 flex items-center space-x-1">
                                                    <i class="fa-solid fa-crown text-[9px] text-amber-600 shrink-0"></i>
                                                    <span class="truncate">Annual Total: ₹{{ number_format($annualTotal, 0) }} / TV / year</span>
                                                </div>
                                            @else
                                                <div class="text-[10px] font-semibold text-slate-500 flex items-center space-x-1">
                                                    <i class="fa-solid fa-repeat text-[9px] text-slate-400 shrink-0"></i>
                                                    <span>Billed monthly</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Features / OTT Badges -->
                                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center gap-1.5">
                                        @if($p->hasOtt())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-cyan-50 text-cyan-900 border border-cyan-200/60 font-bold text-[10px] font-mono">
                                                <i class="fa-solid fa-play mr-1 text-[8px] text-cyan-600"></i>
                                                <span>OTT: {{ count($p->ott_platforms ?? []) }} Apps</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-medium text-[10px] font-mono">
                                                <span>Standard Apps</span>
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-50 text-slate-700 border border-slate-200 font-medium text-[10px] font-mono">
                                            <span>{{ $menusCount }}/{{ $totalMenusInSystem }} Features</span>
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Smart TVs Count Stepper & Preset Buttons -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs sm:text-sm font-black text-slate-900 flex items-center justify-between">
                            <span>2. Number of Smart TVs / Screens Needed <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-[#0A162D] font-mono font-bold">1 TV = 1 Screen License</span>
                        </label>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center space-x-3 max-w-sm mx-auto sm:mx-0">
                                <button type="button" 
                                        onclick="adjustTvCountStep(-5)" 
                                        class="w-12 h-12 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 font-black text-xl flex items-center justify-center transition-all cursor-pointer shadow-2xs active:scale-95">
                                    -
                                </button>
                                
                                <div class="relative flex-1">
                                    <input type="number" 
                                           id="input_tv_count" 
                                           name="room_count" 
                                           required 
                                           min="1" 
                                           max="5000" 
                                           value="10" 
                                           oninput="recalculatePricingStep()" 
                                           class="w-full text-center py-2.5 text-lg sm:text-xl font-black font-mono rounded-xl border border-slate-200 focus:outline-none focus:border-[#0A162D] focus:ring-4 focus:ring-slate-900/5 bg-white text-slate-900">
                                </div>

                                <button type="button" 
                                        onclick="adjustTvCountStep(5)" 
                                        class="w-12 h-12 rounded-xl bg-[#0A162D] hover:bg-[#0E2556] text-white font-black text-xl flex items-center justify-center transition-all cursor-pointer shadow-md active:scale-95">
                                    +
                                </button>
                            </div>

                            <!-- Quick Preset Buttons (Like in Reference Design) -->
                            <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                <span class="text-[11px] text-slate-500 font-bold mr-1">Quick Presets:</span>
                                <button type="button" onclick="setTvCountStep(10)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#0A162D] hover:text-white border border-slate-200 text-slate-700 text-xs font-bold font-mono transition-all">10 TVs</button>
                                <button type="button" onclick="setTvCountStep(25)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#0A162D] hover:text-white border border-slate-200 text-slate-700 text-xs font-bold font-mono transition-all">25 TVs</button>
                                <button type="button" onclick="setTvCountStep(50)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#0A162D] hover:text-white border border-slate-200 text-slate-700 text-xs font-bold font-mono transition-all">50 TVs</button>
                                <button type="button" onclick="setTvCountStep(100)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#0A162D] hover:text-white border border-slate-200 text-slate-700 text-xs font-bold font-mono transition-all">100 TVs</button>
                                <button type="button" onclick="setTvCountStep(200)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#0A162D] hover:text-white border border-slate-200 text-slate-700 text-xs font-bold font-mono transition-all">200 TVs</button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. LIVE PRICING CALCULATION BOX (Directly Below TVs Stepper) -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs sm:text-sm font-black text-slate-900">
                            3. Live Transparent Price Calculation
                        </label>

                        <!-- Luxury Calculation Card (Matching Reference Design Card) -->
                        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-md">
                            
                            <!-- Header Banner with Grand Total -->
                            <div class="bg-gradient-to-r from-[#0A162D] via-[#0E2556] to-[#0A162D] text-white p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="text-[10px] font-extrabold tracking-widest uppercase text-[#00fbff] block font-mono">
                                        ESTIMATED TOTAL INVESTMENT
                                    </span>
                                    <div class="flex items-baseline space-x-2 mt-0.5">
                                        <span id="liveGrandTotal" class="text-2xl sm:text-3xl font-black font-mono tracking-tight text-white">
                                            ₹0.00
                                        </span>
                                        <span id="liveDurationLabel" class="text-xs text-slate-300 font-semibold font-sans">
                                            / 1 Month
                                        </span>
                                    </div>
                                </div>

                                <div class="text-left sm:text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-bold">
                                        <i class="fa-solid fa-shield-check mr-1.5 text-xs"></i> Zero Setup Fees
                                    </span>
                                </div>
                            </div>

                            <!-- Transparent Formula Breakdown Body -->
                            <div class="bg-white p-4 sm:p-5 space-y-2.5 text-xs sm:text-sm">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Selected Plan Tier:</span>
                                    <span id="livePlanName" class="font-bold text-slate-900">—</span>
                                </div>

                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Licensed TV Displays:</span>
                                    <span id="liveTvCount" class="font-bold font-mono text-slate-900">10 TVs</span>
                                </div>

                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Rate per TV / Month:</span>
                                    <span id="liveUnitRate" class="font-bold font-mono text-slate-900">₹0.00</span>
                                </div>

                                <div class="flex items-center justify-between text-slate-500 text-[11px] pt-1">
                                    <span>Calculation Breakdown:</span>
                                    <span id="liveFormulaText" class="font-mono font-bold text-[#0A162D]">10 TVs × ₹0.00 × 1 mo</span>
                                </div>

                                <div class="border-t border-slate-100 pt-2 flex items-center justify-between font-semibold text-slate-700">
                                    <span>Subtotal:</span>
                                    <span id="liveSubtotal" class="font-black font-mono text-slate-900">₹0.00</span>
                                </div>

                                <div class="flex items-center justify-between text-slate-600" id="liveTaxRow">
                                    <span id="liveTaxLabel">GST (18%):</span>
                                    <span id="liveTaxAmount" class="font-bold font-mono text-slate-800">+ ₹0.00</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Step 2 Actions -->
                    <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" 
                                onclick="goToStep(1)" 
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back to Step 1
                        </button>
                        
                        <button type="button" 
                                onclick="goToStep(3)" 
                                class="px-6 py-3 rounded-2xl bg-[#0A162D] hover:bg-[#0E2556] active:scale-98 text-white font-black text-xs sm:text-sm tracking-wide shadow-md shadow-slate-950/20 transition-all flex items-center space-x-2 cursor-pointer">
                            <span>Proceed to Review & Pay</span>
                            <i class="fa-solid fa-arrow-right text-xs text-[#00fbff]"></i>
                        </button>
                    </div>

                </div>

                <!-- ======================================================== -->
                <!-- STEP 3: Order Review, Terms & Final Payment              -->
                <!-- ======================================================== -->
                <div id="wizardStep3" class="wizard-step hidden space-y-6">
                    
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#0A162D] font-mono">Step 3 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Order Review & Secure Payment</h2>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold border border-emerald-200/80">
                            <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i> 256-Bit SSL Encrypted
                        </span>
                    </div>

                    <!-- Comprehensive Order Review Summary Card -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        
                        <!-- Hotel & Admin Overview -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Registered Hotel Property</span>
                                <h3 id="summaryHotelName" class="text-base font-black text-slate-900 tracking-tight">—</h3>
                                <p id="summaryHotelLocation" class="text-xs text-slate-500 font-medium mt-0.5">—</p>
                            </div>

                            <div class="text-left sm:text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Account Administrator</span>
                                <div id="summaryOwnerName" class="text-xs font-bold text-slate-800">—</div>
                                <div id="summaryOwnerContact" class="text-[11px] text-slate-500 font-mono">—</div>
                            </div>
                        </div>

                        <!-- Licensing & Payment Breakdown -->
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Selected Plan:</span>
                                <div class="flex items-center space-x-1.5">
                                    <span id="summaryPlanCycle" class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-200 text-slate-800">MONTHLY</span>
                                    <span id="summaryPlanName" class="font-extrabold text-slate-900">—</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Total Smart TVs:</span>
                                <span id="summaryTvCount" class="font-black text-slate-900 font-mono">10 TVs</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Billing Duration:</span>
                                <span id="summaryDuration" class="font-bold text-slate-800">1 Month</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Unit Price Per TV:</span>
                                <span id="summaryUnitRate" class="font-bold text-slate-800 font-mono">₹0.00 / TV / mo</span>
                            </div>

                            <div class="flex items-center justify-between pt-1 text-slate-500 text-xs">
                                <span>Calculation:</span>
                                <span id="summaryFormula" class="font-mono font-bold text-[#0A162D] text-[11px]">10 TVs × ₹0.00</span>
                            </div>

                            <div class="border-t border-slate-200 pt-2 flex items-center justify-between font-semibold text-slate-700">
                                <span>Subtotal:</span>
                                <span id="summarySubtotal" class="font-black font-mono text-slate-900">₹0.00</span>
                            </div>

                            <div class="flex items-center justify-between" id="summaryTaxRow">
                                <span id="summaryTaxLabel" class="text-slate-600">GST Tax:</span>
                                <span id="summaryTaxAmount" class="font-bold font-mono text-slate-700">+ ₹0.00</span>
                            </div>

                            <!-- Grand Total Row -->
                            <div class="pt-3 border-t-2 border-slate-200 flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs sm:text-sm font-black text-slate-900 block">Total Payable Now</span>
                                    <span class="text-[10px] text-emerald-700 font-semibold block">Instant Activation & Welcome Email</span>
                                </div>
                                <div class="text-right">
                                    <span id="summaryGrandTotal" class="text-2xl sm:text-3xl font-black text-[#0A162D] font-mono tracking-tight leading-none">
                                        ₹0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Terms & Conditions Consent Checkbox -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <label class="flex items-start space-x-2.5 cursor-pointer select-none">
                            <input type="checkbox" 
                                   id="checkbox_terms_consent" 
                                   name="terms_consent" 
                                   value="1" 
                                   class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#0A162D] focus:ring-[#0A162D] cursor-pointer">
                            <span class="text-xs text-slate-700 leading-relaxed font-medium">
                                I confirm that I am authorized to register this hotel property, and I agree to PaxTV's 
                                <a href="{{ route('privacy-policy') }}" target="_blank" class="text-[#0A162D] font-bold underline hover:text-[#0E2556]">Terms of Service</a> 
                                and Smart TV software licensing policies.
                            </span>
                        </label>
                    </div>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-[11px] text-slate-600 font-medium">
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-key text-[#0A162D] text-xs"></i>
                            <span>Instant TV License Key</span>
                        </div>
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-cloud-arrow-up text-emerald-600 text-xs"></i>
                            <span>Automatic TV Cloud Sync</span>
                        </div>
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-envelope-circle-check text-blue-600 text-xs"></i>
                            <span>APK & Login sent via Email</span>
                        </div>
                    </div>

                    @php
                        $turnstile = app(\App\Services\TurnstileService::class);
                    @endphp
                    @if($turnstile->isEnabled())
                        <!-- Cloudflare Turnstile Anti-Bot Security -->
                        <div class="pt-4 flex justify-center">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstile->getSiteKey() }}" data-theme="light" data-action="signup"></div>
                        </div>
                    @endif

                    <!-- Step 3 Actions: Back + Confirm & Pay Button -->
                    <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <button type="button" 
                                onclick="goToStep(2)" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back to Step 2
                        </button>

                        <button type="button" 
                                id="btnConfirmPay" 
                                onclick="processRegistrationPayment()" 
                                class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-[#0A162D] hover:bg-[#0E2556] active:scale-98 text-white font-black text-sm tracking-wide shadow-lg shadow-slate-950/20 transition-all flex items-center justify-center space-x-2.5 cursor-pointer">
                            <i class="fa-solid fa-lock text-[#00fbff] text-xs"></i>
                            <span id="btnPayText">Confirm & Pay via Online Gateway</span>
                        </button>
                    </div>

                </div>

            </form>

        </div>

        <!-- Footer Copyright -->
        <div class="text-center mt-6 text-xs text-white/50">
            &copy; 2005-2026 Pax TV Launcher Inc. All rights reserved.
        </div>

    </div>

</div>

<!-- Wizard Interactive JavaScript Logic -->
<script>
    let currentStep = 1;

    // Pricing Calculation Engine State
    let pricingState = {
        subtotal: 0,
        taxAmount: 0,
        total: 0,
        tvCount: 10,
        unitRate: 0,
        durationMonths: 1,
        planName: '',
        planId: null,
        isYearly: false,
        taxRate: 0,
        taxName: 'GST',
        taxType: 'exclusive'
    };

    // Plan Card Selection
    function selectPlanCard(card) {
        document.querySelectorAll('.plan-card').forEach(c => {
            c.classList.remove('active');
            c.classList.remove('border-[#0A162D]');
            c.classList.add('border-slate-200');
            const bubble = c.querySelector('.plan-check-bubble');
            if (bubble) {
                bubble.classList.remove('bg-[#0A162D]', 'text-white');
                bubble.classList.add('bg-slate-100', 'text-transparent');
            }
            const radio = c.querySelector('.plan-radio-input');
            if (radio) radio.checked = false;
        });

        card.classList.add('active');
        card.classList.remove('border-slate-200');
        card.classList.add('border-[#0A162D]');
        const bubble = card.querySelector('.plan-check-bubble');
        if (bubble) {
            bubble.classList.remove('bg-slate-100', 'text-transparent');
            bubble.classList.add('bg-[#0A162D]', 'text-white');
        }
        const radio = card.querySelector('.plan-radio-input');
        if (radio) radio.checked = true;

        recalculatePricingStep();
    }

    // Logo Upload Preview
    function previewHotelLogo(input) {
        const file = input.files && input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('logoPreviewImg');
                const defaultIcon = document.getElementById('logoDefaultIcon');
                const removeBtn = document.getElementById('removeLogoBtn');
                const nameLabel = document.getElementById('logoFileName');

                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                if (defaultIcon) defaultIcon.classList.add('hidden');
                if (removeBtn) removeBtn.classList.remove('hidden');
                if (nameLabel) nameLabel.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            };
            reader.readAsDataURL(file);
        }
    }

    function clearHotelLogo() {
        const input = document.getElementById('hotel_logo_input');
        const img = document.getElementById('logoPreviewImg');
        const defaultIcon = document.getElementById('logoDefaultIcon');
        const removeBtn = document.getElementById('removeLogoBtn');
        const nameLabel = document.getElementById('logoFileName');

        if (input) input.value = '';
        if (img) {
            img.src = '';
            img.classList.add('hidden');
        }
        if (defaultIcon) defaultIcon.classList.remove('hidden');
        if (removeBtn) removeBtn.classList.add('hidden');
        if (nameLabel) nameLabel.textContent = 'Logo will appear on guest TVs & welcome screens.';
    }

    // Password Visibility Toggle
    function toggleWizardPassword(id, btn) {
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

    // Smart TVs Stepper
    function adjustTvCountStep(delta) {
        const input = document.getElementById('input_tv_count');
        if (!input) return;
        let val = parseInt(input.value) || 1;
        val = Math.max(1, Math.min(5000, val + delta));
        input.value = val;
        recalculatePricingStep();
    }

    function setTvCountStep(val) {
        const input = document.getElementById('input_tv_count');
        if (!input) return;
        input.value = Math.max(1, Math.min(5000, parseInt(val) || 1));
        recalculatePricingStep();
    }

    // Dynamic Pricing Calculation
    function recalculatePricingStep() {
        const selectedPlan = document.querySelector('.plan-radio-input:checked')?.closest('.plan-card');
        const tvInput = document.getElementById('input_tv_count');
        if (!selectedPlan) return;

        const monthlyRate = parseFloat(selectedPlan.dataset.monthlyRate) || 0;
        const annualTotal = parseFloat(selectedPlan.dataset.annualTotal) || (monthlyRate * 12);
        const cycle = (selectedPlan.dataset.cycle || 'monthly').toLowerCase();
        const isYearly = (cycle === 'yearly');
        const durationMonths = isYearly ? 12 : 1;
        const tvCount = Math.max(1, parseInt(tvInput?.value) || 1);

        const subtotal = Math.round((monthlyRate * tvCount * durationMonths) * 100) / 100;

        const taxRate = parseFloat(selectedPlan.dataset.taxRate) || 0;
        const taxType = selectedPlan.dataset.taxType || 'exclusive';
        const taxName = selectedPlan.dataset.taxName || 'GST';

        let taxAmount = 0;
        let total = subtotal;

        if (taxRate > 0) {
            if (taxType === 'inclusive') {
                taxAmount = Math.round((subtotal - (subtotal / (1 + (taxRate / 100)))) * 100) / 100;
                total = subtotal;
            } else {
                taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
                total = Math.round((subtotal + taxAmount) * 100) / 100;
            }
        }

        pricingState = {
            subtotal,
            taxAmount,
            total,
            tvCount,
            unitRate: monthlyRate,
            annualTotal,
            durationMonths,
            planName: selectedPlan.dataset.name || 'Selected Plan',
            planId: selectedPlan.dataset.id,
            isYearly,
            taxRate,
            taxName,
            taxType
        };

        // Update Step 2 Live Box
        updateLiveBoxUI();
    }

    function updateLiveBoxUI() {
        const liveTotal = document.getElementById('liveGrandTotal');
        const liveDuration = document.getElementById('liveDurationLabel');
        const livePlanName = document.getElementById('livePlanName');
        const liveTvCount = document.getElementById('liveTvCount');
        const liveUnitRate = document.getElementById('liveUnitRate');
        const liveFormula = document.getElementById('liveFormulaText');
        const liveSubtotal = document.getElementById('liveSubtotal');
        const liveTaxRow = document.getElementById('liveTaxRow');
        const liveTaxLabel = document.getElementById('liveTaxLabel');
        const liveTaxAmount = document.getElementById('liveTaxAmount');

        if (liveTotal) liveTotal.textContent = '₹' + pricingState.total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (liveDuration) liveDuration.textContent = pricingState.isYearly ? '/ 1 Year (12 Mo)' : '/ 1 Month';
        if (livePlanName) livePlanName.textContent = pricingState.planName + (pricingState.isYearly ? ' (Annual)' : ' (Monthly)');
        if (liveTvCount) liveTvCount.textContent = pricingState.tvCount + ' Displays';
        if (liveUnitRate) liveUnitRate.textContent = '₹' + pricingState.unitRate.toFixed(2) + ' / TV / mo';
        if (liveFormula) liveFormula.textContent = `${pricingState.tvCount} TVs × ₹${pricingState.unitRate.toFixed(2)} × ${pricingState.durationMonths} mo`;
        if (liveSubtotal) liveSubtotal.textContent = '₹' + pricingState.subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (liveTaxRow && liveTaxLabel && liveTaxAmount) {
            if (pricingState.taxRate > 0) {
                liveTaxRow.style.display = 'flex';
                liveTaxLabel.textContent = `${pricingState.taxName} (${pricingState.taxRate.toFixed(0)}%):`;
                liveTaxAmount.textContent = '+ ₹' + pricingState.taxAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                liveTaxRow.style.display = 'none';
            }
        }
    }

    // Step Navigation & Strict Client Validation
    function goToStep(target) {
        hideErrorAlert();

        // Leaving Step 1 -> Moving forward
        if (currentStep === 1 && target > 1) {
            const hotelName = document.getElementById('input_hotel_name')?.value.trim();
            const city = document.getElementById('input_city')?.value.trim();
            const location = document.getElementById('input_hotel_location')?.value.trim();
            const name = document.getElementById('input_owner_name')?.value.trim();
            const email = document.getElementById('input_email')?.value.trim();
            const phone = document.getElementById('input_phone')?.value.trim();
            const pwd = document.getElementById('input_password')?.value;
            const pwdConf = document.getElementById('input_password_confirmation')?.value;

            if (!hotelName) return showErrorAlert('Please enter the Hotel / Property Name.');
            if (!city) return showErrorAlert('Please enter the City where your hotel is located.');
            if (!location) return showErrorAlert('Please enter the Complete Property Address.');
            if (!name) return showErrorAlert('Please enter the Account Owner / Full Name.');
            if (!email || !email.includes('@')) return showErrorAlert('Please enter a valid official Email Address.');
            if (!phone || phone.length < 7) return showErrorAlert('Please enter a valid Phone / WhatsApp contact number.');
            if (!pwd || pwd.length < 6) return showErrorAlert('Password must be at least 6 characters long.');
            if (pwd !== pwdConf) return showErrorAlert('Passwords do not match. Please verify your confirm password.');
        }

        // Leaving Step 2 -> Moving forward
        if (currentStep === 2 && target > 2) {
            const selectedPlan = document.querySelector('.plan-radio-input:checked');
            const tvCount = parseInt(document.getElementById('input_tv_count')?.value) || 0;

            if (!selectedPlan) return showErrorAlert('Please select a subscription plan tier.');
            if (tvCount < 1) return showErrorAlert('Please specify at least 1 Smart TV for licensing.');

            // Populate Step 3 Review Card
            populateSummary();
        }

        // Switch visible panel
        document.getElementById('wizardStep1').classList.add('hidden');
        document.getElementById('wizardStep2').classList.add('hidden');
        document.getElementById('wizardStep3').classList.add('hidden');

        const activePanel = document.getElementById('wizardStep' + target);
        if (activePanel) activePanel.classList.remove('hidden');

        // Update Stepper Tabs
        updateStepperUI(target);
        currentStep = target;

        // Scroll to card top smoothly
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateStepperUI(step) {
        for (let i = 1; i <= 3; i++) {
            const tab = document.getElementById('stepTab' + i);
            if (!tab) continue;

            const bubble = tab.querySelector('span');

            if (i < step) {
                // Completed
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl bg-emerald-600/90 text-white shadow-xs transition-all';
                if (bubble) bubble.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i>';
            } else if (i === step) {
                // Active
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl bg-[#0A162D] text-white shadow-md border border-[#00fbff]/30 transition-all';
                if (bubble) {
                    bubble.className = 'w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#00fbff] text-[#0A162D] font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0';
                    bubble.textContent = i;
                }
            } else {
                // Upcoming
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2 sm:px-3 rounded-xl text-white/60 hover:text-white transition-all';
                if (bubble) {
                    bubble.className = 'w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/10 text-white/70 font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0';
                    bubble.textContent = i;
                }
            }
        }
    }

    function showErrorAlert(msg) {
        const alert = document.getElementById('globalErrorAlert');
        const text = document.getElementById('globalErrorMessage');
        if (alert && text) {
            text.textContent = msg;
            alert.classList.remove('hidden');
            alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function hideErrorAlert() {
        const alert = document.getElementById('globalErrorAlert');
        if (alert) alert.classList.add('hidden');
    }

    // Populate Final Step 3 Review Card
    function populateSummary() {
        recalculatePricingStep();

        const hotelName = document.getElementById('input_hotel_name')?.value || 'Hotel';
        const city = document.getElementById('input_city')?.value || 'City';
        const address = document.getElementById('input_hotel_location')?.value || '';
        const ownerName = document.getElementById('input_owner_name')?.value || 'Owner';
        const email = document.getElementById('input_email')?.value || '';
        const phone = document.getElementById('input_phone')?.value || '';

        document.getElementById('summaryHotelName').textContent = hotelName;
        document.getElementById('summaryHotelLocation').textContent = city + (address ? ' • ' + address.substring(0, 35) + '...' : '');
        document.getElementById('summaryOwnerName').textContent = ownerName;
        document.getElementById('summaryOwnerContact').textContent = email + ' • ' + phone;

        document.getElementById('summaryPlanName').textContent = pricingState.planName;
        document.getElementById('summaryPlanCycle').textContent = pricingState.isYearly ? 'ANNUAL PLAN' : 'MONTHLY PLAN';
        document.getElementById('summaryTvCount').textContent = pricingState.tvCount + ' Smart TVs';
        document.getElementById('summaryDuration').textContent = pricingState.isYearly ? '1 Year (12 Months)' : '1 Month';
        document.getElementById('summaryUnitRate').textContent = '₹' + pricingState.unitRate.toFixed(2) + ' / TV / mo';

        document.getElementById('summaryFormula').textContent = `${pricingState.tvCount} TVs × ₹${pricingState.unitRate.toFixed(2)}/mo × ${pricingState.durationMonths} mo`;
        document.getElementById('summarySubtotal').textContent = '₹' + pricingState.subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const taxRow = document.getElementById('summaryTaxRow');
        const taxLabel = document.getElementById('summaryTaxLabel');
        const taxAmountEl = document.getElementById('summaryTaxAmount');
        if (pricingState.taxRate > 0) {
            taxRow.style.display = 'flex';
            taxLabel.textContent = `${pricingState.taxName} (${pricingState.taxRate.toFixed(0)}%):`;
            taxAmountEl.textContent = '+ ₹' + pricingState.taxAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            taxRow.style.display = 'none';
        }

        document.getElementById('summaryGrandTotal').textContent = '₹' + pricingState.total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        const btnText = document.getElementById('btnPayText');
        if (btnText) {
            btnText.textContent = `Pay ₹${pricingState.total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} & Launch Portal`;
        }
    }

    // Payment Processing Engine
    async function processRegistrationPayment() {
        hideErrorAlert();

        const termsChecked = document.getElementById('checkbox_terms_consent')?.checked;
        if (!termsChecked) {
            return showErrorAlert('You must agree to the Terms of Service & Software Licensing policies to proceed with payment.');
        }

        const btn = document.getElementById('btnConfirmPay');
        const btnText = document.getElementById('btnPayText');
        const origText = btnText.innerHTML;

        try {
            btn.disabled = true;
            btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Initializing Secure Gateway...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            // Step A: Request Gateway Order
            const orderPayload = {
                plan_id: pricingState.planId,
                room_count: pricingState.tvCount,
                duration_months: pricingState.durationMonths,
                owner_name: document.getElementById('input_owner_name')?.value,
                hotel_name: document.getElementById('input_hotel_name')?.value,
                email: document.getElementById('input_email')?.value,
                phone: document.getElementById('input_phone')?.value,
                'cf-turnstile-response': document.querySelector('[name="cf-turnstile-response"]')?.value || '',
            };

            const orderResponse = await fetch('{{ route("register.create-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include',
                body: JSON.stringify(orderPayload)
            });

            const orderData = await orderResponse.json();

            if (!orderResponse.ok || !orderData.success) {
                const errMsg = orderData.message || (orderData.errors ? Object.values(orderData.errors).flat()[0] : null);
                throw new Error(errMsg || 'Payment Order generation failed. Please try again.');
            }

            // Step B: Launch Razorpay or active gateway
            if (orderData.gateway === 'razorpay') {
                if (typeof Razorpay === 'undefined') {
                    throw new Error('Razorpay SDK failed to load. Please refresh the page.');
                }

                const options = {
                    key: orderData.key_id,
                    order_id: orderData.order_id,
                    amount: Math.round(parseFloat(orderData.amount) * 100),
                    currency: orderData.currency || 'INR',
                    name: 'PaxTV Hospitality',
                    description: `License: ${pricingState.planName} (${pricingState.tvCount} TVs)`,
                    prefill: {
                        name: document.getElementById('input_owner_name')?.value,
                        email: document.getElementById('input_email')?.value,
                        contact: document.getElementById('input_phone')?.value
                    },
                    theme: {
                        color: '#0A162D'
                    },
                    modal: {
                        ondismiss: function() {
                            btn.disabled = false;
                            btnText.innerHTML = origText;
                        }
                    },
                    handler: async function(rzpResponse) {
                        await finalizeHotelRegistration(orderData.order_id, rzpResponse);
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function(resp) {
                    btn.disabled = false;
                    btnText.innerHTML = origText;
                    showErrorAlert('Payment failed: ' + (resp.error.description || 'Transaction was declined by bank or gateway.'));
                });
                rzp.open();
            } else {
                // Fallback / direct verification
                await finalizeHotelRegistration(orderData.order_id, {});
            }

        } catch (err) {
            btn.disabled = false;
            btnText.innerHTML = origText;
            showErrorAlert(err.message || 'Payment processing error occurred.');
        }
    }

    // Finalize Registration and save Hotel Admin with Logo
    async function finalizeHotelRegistration(orderId, paymentResponse) {
        const btnText = document.getElementById('btnPayText');
        if (btnText) btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Activating Hotel License...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const formData = new FormData();

            formData.append('owner_name', document.getElementById('input_owner_name')?.value || '');
            formData.append('email', document.getElementById('input_email')?.value || '');
            formData.append('phone', document.getElementById('input_phone')?.value || '');
            formData.append('password', document.getElementById('input_password')?.value || '');
            formData.append('hotel_name', document.getElementById('input_hotel_name')?.value || '');
            formData.append('city', document.getElementById('input_city')?.value || '');
            formData.append('postal_code', document.getElementById('input_postal_code')?.value || '');
            formData.append('desk_phone', document.getElementById('input_desk_phone')?.value || '');
            formData.append('hotel_location', document.getElementById('input_hotel_location')?.value || '');
            formData.append('room_count', pricingState.tvCount);
            formData.append('plan_id', pricingState.planId);
            formData.append('duration_months', pricingState.durationMonths);
            formData.append('order_id', orderId);

            if (paymentResponse.razorpay_order_id) {
                formData.append('razorpay_order_id', paymentResponse.razorpay_order_id);
            }
            if (paymentResponse.razorpay_payment_id) {
                formData.append('razorpay_payment_id', paymentResponse.razorpay_payment_id);
            }
            if (paymentResponse.razorpay_signature) {
                formData.append('razorpay_signature', paymentResponse.razorpay_signature);
            }

            // Append optional logo file
            const logoInput = document.getElementById('hotel_logo_input');
            if (logoInput && logoInput.files && logoInput.files[0]) {
                formData.append('hotel_logo', logoInput.files[0]);
            }

            const completeResponse = await fetch('{{ route("register.complete") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include',
                body: formData
            });

            const completeData = await completeResponse.json();

            if (!completeResponse.ok || !completeData.success) {
                const errMsg = completeData.message || (completeData.errors ? Object.values(completeData.errors).flat()[0] : null);
                throw new Error(errMsg || 'Hotel account creation could not be completed.');
            }

            // Success: Redirect smoothly to dashboard or success screen
            if (btnText) btnText.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-400 mr-2"></i> Registration Successful! Redirecting...';
            window.location.href = completeData.redirect || '{{ route("hotel.dashboard") }}';

        } catch (err) {
            const btn = document.getElementById('btnConfirmPay');
            if (btn) btn.disabled = false;
            populateSummary();
            showErrorAlert(err.message || 'Registration completion failed. Please contact support.');
        }
    }

    // Initialize state on load
    document.addEventListener('DOMContentLoaded', function() {
        recalculatePricingStep();
    });
</script>

@if(app(\App\Services\TurnstileService::class)->isEnabled())
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endsection
