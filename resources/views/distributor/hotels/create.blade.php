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

                <!-- City with Indian Cities Autocomplete Dropdown List -->
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        City / Destination <span class="text-purple-600 font-semibold text-[11px]">(Select from List)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                        </div>
                        <input type="text" 
                               name="city" 
                               list="indian_cities_list"
                               value="{{ old('city') }}" 
                               placeholder="Select or search Indian city..." 
                               autocomplete="off"
                               onclick="if(this.showPicker){try{this.showPicker();}catch(e){}}"
                               class="w-full pl-10 pr-8 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white transition-all text-slate-900 placeholder-slate-400 font-semibold cursor-pointer">
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none opacity-60"></i>
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

        <!-- Section 2: Initial Subscription Tier -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 border border-purple-100 text-lg font-black shadow-2xs">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">2. Select Subscription Tier</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Select per-TV licensing package or assign later after vendor onboarding.</p>
                    </div>
                </div>
                <span class="self-start sm:self-auto text-[11px] font-black text-purple-700 bg-purple-50 border border-purple-200/60 px-3 py-1 rounded-full shadow-2xs">
                    Per-TV Licensing
                </span>
            </div>

            <!-- Plan Cards Grid -->
            @php
                $selectedPlanId = old('plan_id', $plans->first()?->id ?? '');
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="planSelectionContainer">
                <!-- Option: Assign Later / No Initial Plan -->
                @php
                    $isAssignLater = (string)old('plan_id', '') === '';
                @endphp
                <label class="plan-card-label group relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all select-none {{ $isAssignLater ? 'border-[#150A42] bg-purple-50/30 shadow-md ring-2 ring-[#150A42]/10' : 'border-slate-200 bg-white hover:border-purple-300 hover:bg-slate-50/60' }}"
                       data-plan-id=""
                       data-price-per-tv="0"
                       data-cycle="monthly"
                       data-max-tvs="5000"
                       data-tax-rate="0"
                       data-tax-type="exclusive"
                       data-tax-name="No Tax"
                       data-name="Assign Later">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shadow-2xs">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </span>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-600 uppercase font-mono tracking-wider">
                                    Deferred
                                </span>
                                <input type="radio" 
                                       name="plan_id" 
                                       value="" 
                                       {{ $isAssignLater ? 'checked' : '' }} 
                                       class="sr-only plan-radio"
                                       onchange="onPlanSelected(this)">
                                <div class="plan-check-icon w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $isAssignLater ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-transparent' }}">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                        <div class="font-black text-slate-900 text-sm">Assign Plan Later</div>
                        <div class="text-base font-black text-slate-500 mt-1 font-mono">
                            ₹0.00 <span class="text-[11px] font-sans font-normal text-slate-400">/ TV</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-relaxed font-medium">
                            Onboard property with pending status. Settle payment anytime via Sell Package portal.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-slate-400">
                        <span>No upfront charge</span>
                    </div>
                </label>

                <!-- Active Plans -->
                @foreach($plans as $plan)
                    @php
                        $isCurrentPlan = (string)$plan->id === (string)$selectedPlanId;
                        $cycle = strtolower($plan->billing_cycle ?? 'monthly');
                        $unitRate = (float) $plan->effective_price_per_tv;
                        $tax = $plan->tax ?: $defaultTax;
                        $taxRate = $tax ? (float)$tax->rate : 0.0;
                        $taxType = $plan->tax_type ?? 'exclusive';
                        $taxName = $tax ? $tax->name : 'GST';
                        $maxTvs = (int) ($plan->room_count ?: 5000);
                    @endphp
                    <label class="plan-card-label group relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all select-none {{ $isCurrentPlan ? 'border-[#150A42] bg-purple-50/30 shadow-md ring-2 ring-[#150A42]/10' : 'border-slate-200 bg-white hover:border-purple-300 hover:bg-slate-50/60' }}"
                           data-plan-id="{{ $plan->id }}"
                           data-price-per-tv="{{ $unitRate }}"
                           data-cycle="{{ $cycle }}"
                           data-max-tvs="{{ $maxTvs }}"
                           data-tax-rate="{{ $taxRate }}"
                           data-tax-type="{{ $taxType }}"
                           data-tax-name="{{ $taxName }}"
                           data-name="{{ $plan->name }}">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-1.5">
                                    <span class="w-10 h-10 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm font-black shadow-2xs shrink-0">
                                        <i class="fa-solid fa-cube"></i>
                                    </span>
                                    @if($cycle === 'yearly')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-100 text-amber-900 uppercase font-mono tracking-wider border border-amber-300 shadow-2xs">
                                            <i class="fa-solid fa-crown mr-1 text-[8px] text-amber-600"></i> Yearly
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-purple-100 text-[#150A42] uppercase font-mono tracking-wider border border-purple-200 shadow-2xs">
                                            <i class="fa-solid fa-calendar-days mr-1 text-[8px] text-purple-600"></i> Monthly
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" 
                                           name="plan_id" 
                                           value="{{ $plan->id }}" 
                                           {{ $isCurrentPlan ? 'checked' : '' }} 
                                           class="sr-only plan-radio"
                                           onchange="onPlanSelected(this)">
                                    <div class="plan-check-icon w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $isCurrentPlan ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-transparent' }}">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="font-black text-slate-900 text-sm">{{ $plan->name }}</div>
                            <div class="text-xl font-black text-[#150A42] font-mono mt-1">
                                ₹{{ number_format($unitRate, 2) }}
                                <span class="text-[11px] text-slate-400 font-sans font-medium">/ TV / {{ $cycle === 'yearly' ? 'year' : 'month' }}</span>
                            </div>

                            <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                                @if($plan->room_count)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-tv mr-1 text-[9px]"></i> Max {{ $plan->room_count }} TVs
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $taxName }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-600">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                                <span>Full Launcher & Widgets</span>
                            </span>
                            <span class="text-purple-700 font-mono text-[10px]">{{ $plan->ott_count }} Apps</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Connected Smart TVs (with Distributor & Plan Limits) -->
        @php
            $currentTvCount = (int) old('room_count', 30);
        @endphp
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 border border-purple-100 text-lg font-black shadow-2xs">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">3. Number of Connected Smart TVs</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Specify hotel screen capacity (capped by tier maximum limit).</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span id="planLimitBadge" class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black font-mono bg-purple-50 text-[#150A42] border border-purple-200/80 shadow-2xs">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-purple-600 text-xs"></i>
                        <span>Plan Limit: <strong id="maxTvDisplay" class="font-mono text-purple-900">5000</strong> TVs</span>
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-0.5">
                            Total TV Hardware Limit <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 font-medium">Hardware Android TVs authorized for pairing in guest rooms.</p>
                    </div>

                    <!-- Stepper Counter Input -->
                    <div class="flex items-center space-x-2.5">
                        <button type="button" onclick="adjustTvCount(-5)" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-2xs">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <div class="relative">
                            <input type="number" 
                                   id="hotel_tv_input" 
                                   name="room_count" 
                                   value="{{ $currentTvCount }}" 
                                   min="1" 
                                   max="5000" 
                                   required 
                                   oninput="onTvInputChanged()" 
                                   style="-moz-appearance: textfield; -webkit-appearance: none; appearance: none; margin: 0;"
                                   class="w-28 text-center py-2.5 text-lg font-black font-mono rounded-2xl border-2 border-slate-200 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 bg-[#F8F9FD] focus:bg-white text-slate-900 shadow-2xs transition-all">
                        </div>
                        <button type="button" onclick="adjustTvCount(5)" class="w-11 h-11 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 text-white font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-md shadow-[#150A42]/15">
                            <i class="fa-solid fa-plus text-xs text-amber-300"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono mr-1">Quick Presets:</span>
                    @foreach([10, 20, 30, 50, 100] as $preset)
                        <button type="button" 
                                onclick="setTvCount({{ $preset }})" 
                                data-preset="{{ $preset }}"
                                class="quick-preset-btn px-3 py-1.5 rounded-xl text-xs font-bold font-mono border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:text-[#150A42] hover:border-purple-200 text-slate-700 transition-all cursor-pointer active:scale-95">
                            {{ $preset }} TVs
                        </button>
                    @endforeach
                </div>

                <!-- Dynamic Warning Banner when exceeding limit -->
                <div id="tvLimitWarningBox" class="hidden p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center space-x-2.5 shadow-2xs">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm shrink-0"></i>
                    <span id="tvLimitWarningText">Selected tier has an authorized limit of 50 TVs. Total count automatically capped.</span>
                </div>

                <!-- Live Dynamic Pricing Breakdown Card (Super Admin style) -->
                <div id="livePricingCard" class="p-5 rounded-2xl bg-gradient-to-r from-purple-50/70 via-indigo-50/40 to-slate-50 border border-purple-100/90 text-xs space-y-2.5 font-mono">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Base Rate:</span>
                        <span class="font-bold text-slate-900" id="liveCalcRate">₹150.00 / TV / mo</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Subtotal (<span id="liveCalcTvCount">30</span> Connected TVs):</span>
                        <span class="font-bold text-slate-900" id="liveCalcSubtotal">₹4,500.00</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600" id="liveTaxRow">
                        <span id="liveTaxLabel">GST (18.0% Exclusive):</span>
                        <span class="font-bold text-slate-900" id="liveTaxAmount">+ ₹810.00</span>
                    </div>
                    <div class="pt-2.5 border-t border-purple-200/60 flex items-center justify-between text-slate-900 font-sans">
                        <div>
                            <span class="font-black text-sm block">Estimated Initial License (30 Days):</span>
                            <span class="text-[10px] text-slate-400 font-medium font-sans">Calculated based on selected screen capacity</span>
                        </div>
                        <span class="font-black text-xl sm:text-2xl text-[#150A42] font-mono tracking-tight" id="liveCalcTotal">₹5,310.00</span>
                    </div>
                </div>

                <div id="noPlanPricingCard" class="hidden p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-info text-purple-600 text-sm shrink-0"></i>
                    <span>Plan assignment deferred. Hotel property will be registered with <strong id="noPlanTvCountDisplay" class="text-slate-900 font-mono">30</strong> TV capacity with "Waiting for Payment" status.</span>
                </div>
            </div>
        </div>

        <!-- Section 4: Payment Timing & Workflow Options -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center shrink-0 border border-purple-100 text-lg font-black shadow-2xs">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">4. Payment Timing & Activation Workflow</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Choose whether to settle license immediately or leave in Waiting for Payment.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Option A: Waiting for Payment (DEFAULT) -->
                <label class="payment-action-card relative flex flex-col justify-between p-5 rounded-2xl border-2 border-[#150A42] bg-purple-50/20 shadow-xs cursor-pointer transition-all select-none">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0 font-black shadow-2xs">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900">Waiting for Payment (Self-Pay)</h4>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black bg-purple-100 text-[#150A42] font-mono mt-0.5 uppercase tracking-wider">
                                        Default Selection
                                    </span>
                                </div>
                            </div>
                            <input type="radio" 
                                   name="payment_action" 
                                   value="pay_later" 
                                   checked 
                                   class="text-[#150A42] focus:ring-[#150A42] h-4 w-4 mt-1"
                                   onchange="onPaymentActionChange(this)">
                        </div>
                        <p class="text-xs text-slate-500 mt-3 leading-relaxed font-medium">
                            Hotel account is created immediately with <strong>Waiting for Payment</strong> status and authorized TV quota. You can sell a package anytime from the Sell Package portal.
                        </p>
                    </div>
                </label>

                <!-- Option B: Pay Now via Online Gateway -->
                <label class="payment-action-card relative flex flex-col justify-between p-5 rounded-2xl border-2 border-slate-200 bg-white hover:border-purple-300 shadow-xs cursor-pointer transition-all select-none">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm shrink-0 font-black shadow-2xs">
                                    <i class="fa-solid fa-bolt text-emerald-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900">Pay Now (Instant Gateway)</h4>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800 font-mono mt-0.5 uppercase tracking-wider">
                                        Online Checkout
                                    </span>
                                </div>
                            </div>
                            <input type="radio" 
                                   name="payment_action" 
                                   value="pay_now" 
                                   class="text-[#150A42] focus:ring-[#150A42] h-4 w-4 mt-1"
                                   onchange="onPaymentActionChange(this)">
                        </div>
                        <p class="text-xs text-slate-500 mt-3 leading-relaxed font-medium">
                            Registers hotel in database and redirects immediately to the <strong>Sell Package Checkout</strong> page to settle payment online via Razorpay/PayU and activate license.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Form Actions Bar -->
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2">
            <a href="{{ route('distributor.hotels.index') }}" class="px-5 py-3 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 font-bold text-xs transition-all text-center active:scale-95">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="px-7 py-3.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-check text-xs text-amber-300"></i>
                <span id="submitBtnText">Register Hotel & Authorize TVs</span>
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

    // Plan selection & Limit Enforcement
    function onPlanSelected(radio) {
        document.querySelectorAll('.plan-card-label').forEach(label => {
            label.classList.remove('border-[#150A42]', 'bg-purple-50/30', 'shadow-md', 'ring-2', 'ring-[#150A42]/10');
            label.classList.add('border-slate-200', 'bg-white');
            const checkIcon = label.querySelector('.plan-check-icon');
            if (checkIcon) {
                checkIcon.classList.remove('bg-[#150A42]', 'text-white');
                checkIcon.classList.add('bg-slate-100', 'text-transparent');
            }
        });

        const activeLabel = radio.closest('.plan-card-label');
        if (activeLabel) {
            activeLabel.classList.remove('border-slate-200', 'bg-white');
            activeLabel.classList.add('border-[#150A42]', 'bg-purple-50/30', 'shadow-md', 'ring-2', 'ring-[#150A42]/10');
            const checkIcon = activeLabel.querySelector('.plan-check-icon');
            if (checkIcon) {
                checkIcon.classList.remove('bg-slate-100', 'text-transparent');
                checkIcon.classList.add('bg-[#150A42]', 'text-white');
            }
        }

        enforceTvLimits();
        recalculatePricing();
    }

    function getCurrentMaxTvLimit() {
        const selectedPlan = document.querySelector('.plan-radio:checked')?.closest('.plan-card-label');
        if (!selectedPlan) return 5000;
        const maxVal = parseInt(selectedPlan.getAttribute('data-max-tvs')) || 5000;
        return maxVal > 0 ? maxVal : 5000;
    }

    function enforceTvLimits() {
        const input = document.getElementById('hotel_tv_input');
        const maxLimit = getCurrentMaxTvLimit();
        const displayLimitEl = document.getElementById('maxTvDisplay');
        const warningBox = document.getElementById('tvLimitWarningBox');
        const warningText = document.getElementById('tvLimitWarningText');

        if (displayLimitEl) {
            displayLimitEl.textContent = (maxLimit >= 5000) ? 'Unlimited (5000)' : maxLimit;
        }

        if (input) {
            input.max = maxLimit;
            let current = parseInt(input.value) || 1;
            if (current > maxLimit) {
                input.value = maxLimit;
                if (warningBox && warningText) {
                    warningText.textContent = `Selected tier allows a maximum limit of ${maxLimit} TVs. Quantity automatically adjusted.`;
                    warningBox.classList.remove('hidden');
                }
            } else {
                if (warningBox) warningBox.classList.add('hidden');
            }
        }

        // Dim quick chips that exceed maxLimit
        document.querySelectorAll('.quick-preset-btn').forEach(btn => {
            const pVal = parseInt(btn.getAttribute('data-preset')) || 0;
            if (pVal > maxLimit) {
                btn.classList.add('opacity-40', 'line-through', 'cursor-not-allowed');
                btn.disabled = true;
            } else {
                btn.classList.remove('opacity-40', 'line-through', 'cursor-not-allowed');
                btn.disabled = false;
            }
        });
    }

    function adjustTvCount(delta) {
        const input = document.getElementById('hotel_tv_input');
        if (!input) return;
        const maxLimit = getCurrentMaxTvLimit();
        let current = parseInt(input.value) || 1;
        let next = Math.max(1, Math.min(maxLimit, current + delta));
        input.value = next;
        enforceTvLimits();
        recalculatePricing();
    }

    function setTvCount(val) {
        const input = document.getElementById('hotel_tv_input');
        if (!input) return;
        const maxLimit = getCurrentMaxTvLimit();
        input.value = Math.max(1, Math.min(maxLimit, parseInt(val) || 1));
        enforceTvLimits();
        recalculatePricing();
    }

    function onTvInputChanged() {
        enforceTvLimits();
        recalculatePricing();
    }

    // Live calculation
    function recalculatePricing() {
        const selectedPlan = document.querySelector('.plan-radio:checked')?.closest('.plan-card-label');
        const tvInput = document.getElementById('hotel_tv_input');
        const livePricingCard = document.getElementById('livePricingCard');
        const noPlanPricingCard = document.getElementById('noPlanPricingCard');
        const noPlanTvCountDisplay = document.getElementById('noPlanTvCountDisplay');

        if (!tvInput) return;
        const tvCount = Math.max(1, parseInt(tvInput.value) || 1);

        if (!selectedPlan || !selectedPlan.getAttribute('data-plan-id')) {
            // Assign Plan Later Mode
            if (livePricingCard) livePricingCard.classList.add('hidden');
            if (noPlanPricingCard) noPlanPricingCard.classList.remove('hidden');
            if (noPlanTvCountDisplay) noPlanTvCountDisplay.textContent = tvCount;
            return;
        }

        if (livePricingCard) livePricingCard.classList.remove('hidden');
        if (noPlanPricingCard) noPlanPricingCard.classList.add('hidden');

        const ratePerTv = parseFloat(selectedPlan.getAttribute('data-price-per-tv')) || 0;
        const cycle = selectedPlan.getAttribute('data-cycle') || 'monthly';
        const taxRate = parseFloat(selectedPlan.getAttribute('data-tax-rate')) || 0;
        const taxType = selectedPlan.getAttribute('data-tax-type') || 'exclusive';
        const taxName = selectedPlan.getAttribute('data-tax-name') || 'GST';

        const subtotal = ratePerTv * tvCount;
        let taxAmount = 0;
        let total = subtotal;

        if (taxRate > 0) {
            if (taxType === 'inclusive') {
                taxAmount = subtotal - (subtotal / (1 + (taxRate / 100)));
                total = subtotal;
            } else {
                taxAmount = subtotal * (taxRate / 100);
                total = subtotal + taxAmount;
            }
        }

        const rateEl = document.getElementById('liveCalcRate');
        if (rateEl) {
            rateEl.textContent = '₹' + ratePerTv.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' / TV / ' + (cycle === 'yearly' ? 'yr' : 'mo');
        }

        const countEl = document.getElementById('liveCalcTvCount');
        if (countEl) countEl.textContent = tvCount;

        const subtotalEl = document.getElementById('liveCalcSubtotal');
        if (subtotalEl) {
            subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        const taxRow = document.getElementById('liveTaxRow');
        const taxLabel = document.getElementById('liveTaxLabel');
        const taxAmountEl = document.getElementById('liveTaxAmount');

        if (taxRate > 0) {
            if (taxRow) taxRow.style.display = 'flex';
            if (taxLabel) taxLabel.textContent = taxName + ' (' + taxRate.toFixed(1) + '% ' + (taxType.charAt(0).toUpperCase() + taxType.slice(1)) + '):';
            if (taxAmountEl) taxAmountEl.textContent = '+ ₹' + taxAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            if (taxRow) taxRow.style.display = 'flex';
            if (taxLabel) taxLabel.textContent = 'Tax:';
            if (taxAmountEl) taxAmountEl.textContent = '₹0.00 (Zero Rated)';
        }

        const totalEl = document.getElementById('liveCalcTotal');
        if (totalEl) {
            totalEl.textContent = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // Payment action cards
    function onPaymentActionChange(radio) {
        document.querySelectorAll('.payment-action-card').forEach(card => {
            card.classList.remove('border-[#150A42]', 'bg-purple-50/20');
            card.classList.add('border-slate-200', 'bg-white');
        });

        const activeCard = radio.closest('.payment-action-card');
        if (activeCard) {
            activeCard.classList.remove('border-slate-200', 'bg-white');
            activeCard.classList.add('border-[#150A42]', 'bg-purple-50/20');
        }

        const submitBtnText = document.getElementById('submitBtnText');
        if (submitBtnText) {
            if (radio.value === 'pay_now') {
                submitBtnText.textContent = 'Register & Proceed to Online Payment';
            } else {
                submitBtnText.textContent = 'Register Hotel & Authorize TVs';
            }
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

    // Initial Execution
    document.addEventListener('DOMContentLoaded', function() {
        enforceTvLimits();
        recalculatePricing();
    });
</script>

@include('partials.indian_cities_datalist')
@endsection
