@extends('layouts.app')

@section('title', 'Hotel Onboarding & Registration - ' . app_name())

@section('styles')
<style>
    /* Signature Cosmic Canvas */
    .register-cosmic-canvas {
        background: radial-gradient(circle at 50% 10%, #1a0d4a 0%, #0d0628 50%, #050215 100%);
    }

    /* Step Transitions */
    .wizard-step {
        transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
    }
    .wizard-step.hidden {
        display: none !important;
    }

    /* Custom Radio Plan Card Checked State */
    .plan-card.active {
        border-color: #150A42;
        background-color: rgba(245, 243, 255, 0.7);
        box-shadow: 0 4px 14px 0 rgba(21, 10, 66, 0.12);
    }
</style>
<!-- Include Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection

@section('content')
<div class="min-h-screen py-8 sm:py-12 px-4 sm:px-6 lg:px-8 register-cosmic-canvas relative flex flex-col justify-center">

    <!-- Ambient Glow Orbs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-3xl w-full mx-auto relative z-10">

        <!-- Header / Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center space-x-3 group">
                @if(app_logo_url())
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-10 sm:h-12 w-auto object-contain drop-shadow-md">
                @else
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-500 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-purple-600/30 group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                @endif
                <div class="text-left">
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight block">
                        PAX<span class="text-purple-400">TV</span>
                    </span>
                    <span class="text-[10px] sm:text-[11px] uppercase tracking-widest text-white/70 font-semibold block font-mono">
                        Hospitality Launcher Cloud
                    </span>
                </div>
            </a>
            <h1 class="text-xl sm:text-3xl font-black text-white mt-3.5 tracking-tight">
                Hotel Property Registration
            </h1>
            <p class="text-xs sm:text-sm text-white/70 mt-1 max-w-md mx-auto">
                Follow 3 simple steps to activate smart TV launcher licensing for your property.
            </p>
        </div>

        <!-- 3-Step Visual Progress Bar -->
        <div class="mb-6 sm:mb-8 bg-white/10 backdrop-blur-md border border-white/15 p-2 sm:p-3 rounded-2xl sm:rounded-full">
            <div class="grid grid-cols-3 gap-1 sm:gap-2 text-center">
                <!-- Step 1 Indicator -->
                <div id="stepTab1" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full bg-[#150A42] text-white shadow-md transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/20 text-white font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">1</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider block">Step 1</span>
                        <span class="text-[9px] sm:text-[11px] text-purple-200 block truncate">Account & Plan</span>
                    </div>
                </div>

                <!-- Step 2 Indicator -->
                <div id="stepTab2" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full text-white/60 hover:text-white transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/10 text-white/70 font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">2</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider block">Step 2</span>
                        <span class="text-[9px] sm:text-[11px] text-white/50 block truncate">Hotel Property</span>
                    </div>
                </div>

                <!-- Step 3 Indicator -->
                <div id="stepTab3" class="step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full text-white/60 hover:text-white transition-all">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white/10 text-white/70 font-mono font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0">3</span>
                    <div class="text-left leading-tight hidden xs:block sm:block">
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider block">Step 3</span>
                        <span class="text-[9px] sm:text-[11px] text-white/50 block truncate">Confirm & Pay</span>
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
                    <span id="globalErrorTitle">Please resolve errors before continuing:</span>
                </div>
                <div id="globalErrorMessage" class="text-[11px] text-rose-600 pl-1"></div>
            </div>

            <form id="hotelRegisterWizardForm" onsubmit="return false;" enctype="multipart/form-data">
                @csrf

                <!-- ======================================================== -->
                <!-- STEP 1: Basic Account Details & Plan Selection           -->
                <!-- ======================================================== -->
                <div id="wizardStep1" class="wizard-step space-y-5">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-purple-700 font-mono">Step 1 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Account Admin & Plan Selection</h2>
                        </div>
                        <span class="text-xs text-slate-400 font-medium hidden sm:inline">All fields marked with <span class="text-rose-500">*</span> required</span>
                    </div>

                    <!-- Owner Name, Email, Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <!-- Owner Full Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       id="input_owner_name" 
                                       name="owner_name" 
                                       required 
                                       placeholder="e.g. Rahul Sharma"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Official Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="email" 
                                       id="input_email" 
                                       name="email" 
                                       required 
                                       placeholder="admin@hotel.com"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>

                        <!-- Phone / WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       id="input_phone" 
                                       name="phone" 
                                       required 
                                       placeholder="+91 98765 43210"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Plan Selection (Interactive Plan Cards with Full Features) -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs sm:text-sm font-black text-slate-900">
                                Choose Subscription Tier <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-purple-700 font-bold font-mono">
                                {{ $plans->count() }} Tiers Available
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="planCardsContainer">
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
                                <label class="plan-card relative flex flex-col justify-between p-4 sm:p-5 rounded-2xl border-2 cursor-pointer transition-all {{ $isFirst ? 'active border-[#150A42]' : 'border-slate-200 bg-white hover:border-slate-300' }}"
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
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider font-mono {{ $cycle === 'yearly' ? 'bg-amber-100 text-amber-900 border border-amber-300 shadow-2xs' : 'bg-purple-100 text-[#150A42] border border-purple-200 shadow-2xs' }}">
                                                @if($cycle === 'yearly')
                                                    <i class="fa-solid fa-crown text-[9px] text-amber-600 mr-1"></i> YEARLY PLAN
                                                @else
                                                    <i class="fa-solid fa-calendar-days text-[9px] text-purple-600 mr-1"></i> MONTHLY PLAN
                                                @endif
                                            </span>
                                            
                                            <!-- Checkmark Icon -->
                                            <div class="plan-check-bubble w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $isFirst ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-transparent' }}">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                            </div>
                                        </div>

                                        <h3 class="text-base font-black text-slate-900 tracking-tight">{{ $p->name }}</h3>
                                        @if($p->description)
                                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $p->description }}</p>
                                        @endif

                                        <!-- Price Box (Matching Reference Design) -->
                                        <div class="mt-2.5 p-3 rounded-xl bg-slate-50/90 border border-slate-200/80 space-y-1.5">
                                            <div class="flex items-baseline space-x-1 font-mono">
                                                <span class="text-2xl font-black text-[#150A42]">₹{{ number_format($monthlyRate, 0) }}</span>
                                                <span class="text-xs text-slate-500 font-sans font-bold">/ TV / month</span>
                                            </div>

                                            @if($cycle === 'yearly')
                                                <div class="text-[11px] font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200/80 flex items-center space-x-1.5">
                                                    <i class="fa-solid fa-crown text-[10px] text-amber-600"></i>
                                                    <span>Billed annually at ₹{{ number_format($annualTotal, 0) }} / TV / year</span>
                                                </div>
                                            @else
                                                <div class="text-[11px] font-semibold text-slate-500 flex items-center space-x-1.5">
                                                    <i class="fa-solid fa-calendar-days text-[10px] text-purple-600"></i>
                                                    <span>Billed monthly</span>
                                                </div>
                                            @endif

                                            <!-- Tax & Max Limit -->
                                            <div class="pt-0.5 flex items-center space-x-2 flex-wrap gap-1 text-[10px]">
                                                @if($taxRate > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-bold font-mono {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                                        <i class="fa-solid fa-shield-halved mr-1 text-[9px]"></i>
                                                        {{ $taxName }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-semibold font-mono bg-slate-100 text-slate-600 border border-slate-200">
                                                        Tax Exempt
                                                    </span>
                                                @endif

                                                @if($p->room_count)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-semibold font-mono bg-purple-50 text-[#150A42] border border-purple-100">
                                                        Max {{ $p->room_count }} TVs
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Features & OTT Badges -->
                                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center gap-1.5">
                                        @if($p->hasOtt())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-purple-50 text-[#150A42] border border-purple-200/60 font-bold text-[11px] font-mono shadow-2xs">
                                                <i class="fa-solid fa-play mr-1 text-[9px] text-purple-600"></i>
                                                <span>OTT: {{ count($p->ott_platforms ?? []) }} Apps</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 font-bold text-[11px] font-mono">
                                                <i class="fa-solid fa-ban mr-1 text-[9px] text-slate-400"></i>
                                                <span>OTT Disabled</span>
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-[11px] font-mono shadow-2xs">
                                            <i class="fa-solid fa-bars mr-1 text-[9px] text-slate-500"></i>
                                            <span>{{ $menusCount }}/{{ $totalMenusInSystem }} Menus</span>
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Password & Confirm Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
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
                                       placeholder="Minimum 6 characters"
                                       class="w-full pl-9 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
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
                                       placeholder="Repeat your password"
                                       class="w-full pl-9 pr-10 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                                <button type="button" 
                                        onclick="toggleWizardPassword('input_password_confirmation', this)" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                    <i class="fa-regular fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mandatory Terms Consent Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-start space-x-2.5 cursor-pointer select-none">
                            <input type="checkbox" 
                                   id="checkbox_terms_consent" 
                                   name="terms_consent" 
                                   value="1" 
                                   class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#150A42] focus:ring-purple-500 cursor-pointer">
                            <span class="text-xs text-slate-600 leading-relaxed font-medium">
                                I confirm that I am authorized to register this hotel, and I agree to PaxTV's 
                                <a href="{{ route('privacy-policy') }}" target="_blank" class="text-purple-700 font-bold underline hover:text-purple-900">Terms of Service</a> 
                                and Smart TV software licensing policies.
                            </span>
                        </label>
                    </div>

                    <!-- Step 1 Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-purple-700 font-bold transition-colors">
                            Already registered? Sign In
                        </a>
                        <button type="button" 
                                onclick="goToStep(2)" 
                                class="px-6 py-3 rounded-2xl bg-[#150A42] hover:bg-purple-900 active:scale-98 text-white font-black text-xs sm:text-sm tracking-wide shadow-md shadow-purple-950/20 transition-all flex items-center space-x-2 cursor-pointer">
                            <span>Next: Hotel Property Details</span>
                            <i class="fa-solid fa-arrow-right text-xs text-amber-300"></i>
                        </button>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- STEP 2: Hotel Property & Hardware TV Details             -->
                <!-- ======================================================== -->
                <div id="wizardStep2" class="wizard-step hidden space-y-5">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-purple-700 font-mono">Step 2 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Hotel Property & Display Specifications</h2>
                        </div>
                        <span class="text-xs text-slate-400 font-medium hidden sm:inline">Configure TV displays for this property</span>
                    </div>

                    <!-- Hotel Name & Room / Connected TV Stepper -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Hotel Property Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Hotel / Property Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-hotel absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       id="input_hotel_name" 
                                       name="hotel_name" 
                                       required 
                                       placeholder="e.g. Radisson Blu Hotel & Resort"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>

                        <!-- Number of Connected Smart TVs Stepper -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                <span>Total Smart TVs Needed <span class="text-rose-500">*</span></span>
                                <span class="text-[10px] text-purple-700 font-mono font-bold">1 TV = 1 Screen License</span>
                            </label>
                            
                            <div class="flex items-center space-x-2">
                                <button type="button" 
                                        onclick="adjustTvCountStep(-5)" 
                                        class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-base flex items-center justify-center transition-all cursor-pointer">
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
                                           class="w-full text-center py-2 text-sm sm:text-base font-black font-mono rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900">
                                </div>

                                <button type="button" 
                                        onclick="adjustTvCountStep(5)" 
                                        class="w-10 h-10 rounded-xl bg-[#150A42] hover:bg-purple-900 text-white font-black text-base flex items-center justify-center transition-all cursor-pointer">
                                    +
                                </button>
                            </div>

                            <!-- Quick Preset Chips -->
                            <div class="flex items-center gap-1.5 pt-2 flex-wrap">
                                <span class="text-[10px] text-slate-400 font-bold">Quick:</span>
                                <button type="button" onclick="setTvCountStep(10)" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-bold font-mono">10 TVs</button>
                                <button type="button" onclick="setTvCountStep(25)" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-bold font-mono">25 TVs</button>
                                <button type="button" onclick="setTvCountStep(50)" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-bold font-mono">50 TVs</button>
                                <button type="button" onclick="setTvCountStep(100)" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-bold font-mono">100 TVs</button>
                            </div>
                        </div>
                    </div>

                    <!-- City, Full Address, Pin Code -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-1">
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
                                       placeholder="e.g. Mumbai, Goa"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>

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
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
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
                                       placeholder="e.g. 022 1234 5678"
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Full Location / Address -->
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
                                      class="w-full pl-9 pr-3.5 py-2 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-500/10 bg-slate-50 focus:bg-white text-slate-900 transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Hotel Logo (Optional with Drag/Drop & Thumbnail Preview) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>Hotel Logo (Optional)</span>
                            <span class="text-[10px] text-slate-400 font-sans">PNG, JPG, SVG, WebP up to 5MB</span>
                        </label>
                        
                        <div class="flex items-center space-x-3.5 p-3.5 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition-all">
                            <div id="logoPreviewWrapper" class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                                <i id="logoDefaultIcon" class="fa-solid fa-image text-slate-400 text-lg"></i>
                                <img id="logoPreviewImg" src="" alt="Preview" class="w-full h-full object-contain hidden">
                            </div>

                            <div class="min-w-0 flex-1">
                                <label for="hotel_logo_input" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-purple-600 text-xs font-bold text-slate-700 shadow-2xs cursor-pointer transition-all">
                                    <i class="fa-solid fa-upload text-purple-600 mr-1.5 text-[11px]"></i>
                                    <span>Choose Hotel Logo</span>
                                </label>
                                <input type="file" 
                                       id="hotel_logo_input" 
                                       name="hotel_logo" 
                                       accept="image/*" 
                                       onchange="previewHotelLogo(this)" 
                                       class="sr-only">
                                <div id="logoFileName" class="text-[11px] text-slate-400 font-medium truncate mt-1">
                                    Will be displayed on in-room TV launcher and guest welcome screens.
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

                    <!-- Step 2 Navigation -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" 
                                onclick="goToStep(1)" 
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back to Step 1
                        </button>
                        <button type="button" 
                                onclick="goToStep(3)" 
                                class="px-6 py-3 rounded-2xl bg-[#150A42] hover:bg-purple-900 active:scale-98 text-white font-black text-xs sm:text-sm tracking-wide shadow-md shadow-purple-950/20 transition-all flex items-center space-x-2 cursor-pointer">
                            <span>Review Order & Payment</span>
                            <i class="fa-solid fa-arrow-right text-xs text-amber-300"></i>
                        </button>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- STEP 3: Review Order Breakdown & Payment Confirmation     -->
                <!-- ======================================================== -->
                <div id="wizardStep3" class="wizard-step hidden space-y-5">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-purple-700 font-mono">Step 3 of 3</span>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Order Review & Online Gateway Payment</h2>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold border border-emerald-200/80">
                            <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i> SSL 256-Bit Encrypted
                        </span>
                    </div>

                    <!-- Order Summary Box -->
                    <div class="p-4 sm:p-6 rounded-2xl bg-[#F8FAFC] border border-slate-200/90 space-y-4">
                        
                        <!-- Mini Hotel + Owner Overview -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/70">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Registered Property</span>
                                <h3 id="summaryHotelName" class="text-base font-black text-slate-900 tracking-tight">—</h3>
                                <p id="summaryHotelLocation" class="text-xs text-slate-500 font-medium mt-0.5">—</p>
                            </div>

                            <div class="text-left sm:text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Account Owner</span>
                                <div id="summaryOwnerName" class="text-xs font-bold text-slate-800">—</div>
                                <div id="summaryOwnerContact" class="text-[11px] text-slate-500 font-mono">—</div>
                            </div>
                        </div>

                        <!-- Subscription & Licensing Breakdown Table -->
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Selected Plan:</span>
                                <div class="flex items-center space-x-1.5">
                                    <span id="summaryPlanCycle" class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-100 text-[#150A42]">MONTHLY</span>
                                    <span id="summaryPlanName" class="font-extrabold text-slate-900">—</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Authorized TV Displays:</span>
                                <span id="summaryTvCount" class="font-black text-slate-900 font-mono">10 TVs</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Subscription Duration:</span>
                                <span id="summaryDuration" class="font-bold text-slate-800">1 Month</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Unit Price Per TV:</span>
                                <span id="summaryUnitRate" class="font-bold text-slate-800 font-mono">₹0.00 / TV / mo</span>
                            </div>

                            <div class="flex items-center justify-between pt-1 text-slate-500 text-xs">
                                <span>Calculation Formula:</span>
                                <span id="summaryFormula" class="font-mono font-bold text-purple-900 text-[11px]">10 TVs × ₹0.00</span>
                            </div>

                            <div class="border-t border-slate-200/80 pt-2 flex items-center justify-between">
                                <span class="font-semibold text-slate-700">Subtotal:</span>
                                <span id="summarySubtotal" class="font-black font-mono text-slate-900">₹0.00</span>
                            </div>

                            <div class="flex items-center justify-between" id="summaryTaxRow">
                                <span id="summaryTaxLabel" class="text-slate-600">GST Tax:</span>
                                <span id="summaryTaxAmount" class="font-bold font-mono text-slate-700">+ ₹0.00</span>
                            </div>

                            <!-- Grand Total Banner -->
                            <div class="pt-3 border-t-2 border-slate-200 flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs sm:text-sm font-black text-slate-900 block">Total Payable Now</span>
                                    <span class="text-[10px] text-emerald-700 font-semibold block">Instant Activation & Welcome Email</span>
                                </div>
                                <div class="text-right">
                                    <span id="summaryGrandTotal" class="text-2xl sm:text-3xl font-black text-[#150A42] font-mono tracking-tight leading-none">
                                        ₹0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Trust Indicators -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-[11px] text-slate-600 font-medium">
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-key text-purple-600 text-xs"></i>
                            <span>Instant License Key</span>
                        </div>
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-cloud-arrow-up text-emerald-600 text-xs"></i>
                            <span>Automatic TV Cloud Sync</span>
                        </div>
                        <div class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-envelope-circle-check text-blue-600 text-xs"></i>
                            <span>Credentials Sent via Email</span>
                        </div>
                    </div>

                    <!-- Step 3 Actions: Back + Confirm & Pay Button -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <button type="button" 
                                onclick="goToStep(2)" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Edit Property Details
                        </button>

                        <button type="button" 
                                id="btnConfirmPay" 
                                onclick="processRegistrationPayment()" 
                                class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] via-purple-950 to-[#150A42] hover:opacity-95 active:scale-98 text-white font-black text-sm tracking-wide shadow-lg shadow-purple-950/25 transition-all flex items-center justify-center space-x-2.5 cursor-pointer">
                            <i class="fa-solid fa-lock text-amber-300 text-xs"></i>
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

    // Plan Card Selection
    function selectPlanCard(card) {
        document.querySelectorAll('.plan-card').forEach(c => {
            c.classList.remove('active', 'border-[#150A42]');
            c.classList.add('border-slate-200');
            const bubble = c.querySelector('.plan-check-bubble');
            if (bubble) {
                bubble.classList.remove('bg-[#150A42]', 'text-white');
                bubble.classList.add('bg-slate-100', 'text-transparent');
            }
            const radio = c.querySelector('.plan-radio-input');
            if (radio) radio.checked = false;
        });

        card.classList.add('active', 'border-[#150A42]');
        card.classList.remove('border-slate-200');
        const bubble = card.querySelector('.plan-check-bubble');
        if (bubble) {
            bubble.classList.remove('bg-slate-100', 'text-transparent');
            bubble.classList.add('bg-[#150A42]', 'text-white');
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
        if (nameLabel) nameLabel.textContent = 'Will be displayed on in-room TV launcher and guest welcome screens.';
    }

    // Password Toggle
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

    // Stepper Counters
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

    // Step Navigation & Validation
    function goToStep(target) {
        hideErrorAlert();

        // Validation when leaving Step 1
        if (currentStep === 1 && target > 1) {
            const name = document.getElementById('input_owner_name')?.value.trim();
            const email = document.getElementById('input_email')?.value.trim();
            const phone = document.getElementById('input_phone')?.value.trim();
            const pwd = document.getElementById('input_password')?.value;
            const pwdConf = document.getElementById('input_password_confirmation')?.value;
            const termsChecked = document.getElementById('checkbox_terms_consent')?.checked;
            const selectedPlan = document.querySelector('.plan-radio-input:checked');

            if (!name) return showErrorAlert('Please enter the Account Owner / Full Name.');
            if (!email || !email.includes('@')) return showErrorAlert('Please enter a valid official Email Address.');
            if (!phone || phone.length < 7) return showErrorAlert('Please enter a valid Phone / WhatsApp contact number.');
            if (!selectedPlan) return showErrorAlert('Please select a subscription plan tier.');
            if (!pwd || pwd.length < 6) return showErrorAlert('Password must be at least 6 characters long.');
            if (pwd !== pwdConf) return showErrorAlert('Passwords do not match. Please verify your confirm password.');
            if (!termsChecked) return showErrorAlert('You must agree to the Terms of Service & Software Licensing policies to continue.');
        }

        // Validation when leaving Step 2
        if (currentStep === 2 && target > 2) {
            const hotelName = document.getElementById('input_hotel_name')?.value.trim();
            const tvCount = parseInt(document.getElementById('input_tv_count')?.value) || 0;
            const city = document.getElementById('input_city')?.value.trim();
            const location = document.getElementById('input_hotel_location')?.value.trim();

            if (!hotelName) return showErrorAlert('Please enter your Hotel / Property Name.');
            if (tvCount < 1) return showErrorAlert('Please specify at least 1 Smart TV for licensing.');
            if (!city) return showErrorAlert('Please specify the City where the property is located.');
            if (!location) return showErrorAlert('Please provide the Complete Property Address.');

            // Update Summary for Step 3
            populateSummary();
        }

        // Switch visible panel
        document.getElementById('wizardStep1').classList.add('hidden');
        document.getElementById('wizardStep2').classList.add('hidden');
        document.getElementById('wizardStep3').classList.add('hidden');

        const activePanel = document.getElementById('wizardStep' + target);
        if (activePanel) activePanel.classList.remove('hidden');

        // Update Stepper Badges
        updateStepperUI(target);
        currentStep = target;

        // Scroll smoothly to top of card on mobile
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateStepperUI(step) {
        for (let i = 1; i <= 3; i++) {
            const tab = document.getElementById('stepTab' + i);
            if (!tab) continue;

            const bubble = tab.querySelector('span');

            if (i < step) {
                // Completed
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full bg-emerald-600/90 text-white shadow-xs transition-all';
                if (bubble) bubble.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i>';
            } else if (i === step) {
                // Active
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full bg-[#150A42] text-white shadow-md ring-2 ring-purple-400/30 transition-all';
                if (bubble) bubble.textContent = i;
            } else {
                // Upcoming
                tab.className = 'step-indicator flex items-center justify-center space-x-2 py-2 px-2.5 sm:px-4 rounded-xl sm:rounded-full text-white/60 hover:text-white transition-all';
                if (bubble) bubble.textContent = i;
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

    // Pricing Calculation Engine
    let pricingState = {
        subtotal: 0,
        taxAmount: 0,
        total: 0,
        tvCount: 10,
        unitRate: 0,
        durationMonths: 1,
        planName: '',
        isYearly: false
    };

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

        const unitRate = isYearly ? (monthlyRate) : monthlyRate;
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
    }

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
        document.getElementById('summaryUnitRate').textContent = '₹' + pricingState.unitRate.toFixed(2) + ' / TV / month' + (pricingState.isYearly ? ' (Annual Total: ₹' + pricingState.annualTotal.toFixed(2) + ')' : '');

        document.getElementById('summaryFormula').textContent = `${pricingState.tvCount} TVs × ₹${pricingState.unitRate.toFixed(2)}/mo × ${pricingState.durationMonths} mo`;
        document.getElementById('summarySubtotal').textContent = '₹' + pricingState.subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const taxRow = document.getElementById('summaryTaxRow');
        const taxLabel = document.getElementById('summaryTaxLabel');
        const taxAmountEl = document.getElementById('summaryTaxAmount');
        if (pricingState.taxRate > 0) {
            taxRow.style.display = 'flex';
            taxLabel.textContent = `${pricingState.taxName} (${pricingState.taxRate.toFixed(0)}% ${pricingState.taxType}):`;
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
            };

            const orderResponse = await fetch('{{ route("register.create-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
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
                        color: '#150A42'
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
                // Fallback / direct verification for mock or other gateways
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
@endsection
