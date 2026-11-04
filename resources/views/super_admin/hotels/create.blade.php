@extends('layouts.super_admin')

@section('title', 'Add Hotel Vendor - Super Admin')
@section('page_title', 'Create Hotel Vendor')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Navigation & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.hotels.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Hotels Directory</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42]">New Registration</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Onboard New Hotel Vendor</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Register a new client hotel, assign TV limits, licensing plan, and distributor partner.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.hotels.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 text-xs font-bold transition-all shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <!-- Error Banner -->
    @if(isset($errors) && $errors->any())
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-2 shadow-xs animate-shake">
            <div class="font-bold flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>Please fix the following validation errors before saving:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 pl-4 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card Form -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-8 lg:p-10 shadow-xs">
        <form id="createHotelForm" action="{{ route('super-admin.hotels.store') }}" method="POST" class="space-y-8 sm:space-y-10">
            @csrf

            <!-- SECTION 1: Owner Profile & Credentials (Responsive) -->
            <div class="space-y-5">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 1</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Owner Profile & Credentials</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Primary administrative credentials for the hotel manager portal.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Owner Full Name -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Owner Full Name <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="owner_name" 
                                   value="{{ old('owner_name') }}" 
                                   required 
                                   placeholder="e.g. Rajesh Sharma" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Email Address <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="e.g. admin@grandhotel.com" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Phone Number <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   placeholder="e.g. +91 98765 43210" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-medium placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Account Password Field -->
                <div class="max-w-lg pt-1">
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Portal Login Password <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" 
                               id="passwordInput" 
                               name="password" 
                               required 
                               placeholder="Minimum 6 characters" 
                               class="w-full pl-10 pr-11 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 focus:outline-none p-1 cursor-pointer transition-colors"
                                title="Toggle visibility">
                            <i id="passwordEyeIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 font-medium">Used by hotel management to authenticate into their dashboard portal.</p>
                </div>
            </div>

            <!-- SECTION 2: Hotel Identity & Indian Cities Datalist -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] border border-purple-100">Step 2</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Hotel Identity & Details</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Establish hotel name, geography, and in-room greeting details.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Hotel Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Hotel Name <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="hotel_name" 
                                   value="{{ old('hotel_name') }}" 
                                   required 
                                   placeholder="e.g. The Oberoi Grand View & Suites" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- City with Indian Cities Autocomplete List -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            City / Destination <span class="text-purple-600 font-semibold text-[11px]">(Select from List)</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="city" 
                                   list="indian_cities_list"
                                   value="{{ old('city') }}" 
                                   placeholder="Select or search Indian city..." 
                                   autocomplete="off"
                                   onclick="if(this.showPicker){try{this.showPicker();}catch(e){}}"
                                   class="w-full pl-10 pr-8 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400 cursor-pointer">
                            <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none opacity-60"></i>
                        </div>
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Hotel Physical Address <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               name="hotel_location" 
                               value="{{ old('hotel_location') }}" 
                               required 
                               placeholder="e.g. Bandra Kurla Complex, Bandra East, Mumbai, Maharashtra 400051" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                    </div>
                </div>

                <!-- Welcome Description / Tagline -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Welcome Greeting / TV Header Tagline
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              placeholder="e.g. Welcome to The Oberoi Grand View. Experience premier comfort, in-room gourmet dining, and smart entertainment." 
                              class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400 resize-y">{{ old('description') }}</textarea>
                    <p class="text-xs text-slate-400 mt-1.5 font-medium">Broadcasted on the in-room TV home banner as a personalized greeting message.</p>
                </div>
            </div>

            <!-- SECTION 3: Distributor, Subscription Plan, TVs & Payment Action -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 3</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Subscription Plan, TVs & Channel Partner</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Select distributor, assign per-TV subscription tier, configure device capacity, and set payment workflow.</p>
                        </div>
                    </div>
                </div>

                <!-- 1. Assigned Distributor / Channel Partner (Top of Step 3) -->
                @php
                    $selectedDistId = old('distributor_id', '');
                @endphp
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 sm:p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80">
                    <div class="space-y-0.5">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">
                            1. Select Channel Partner / Distributor
                        </label>
                        <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Link this hotel to a channel partner or Pax TV direct.</p>
                    </div>
                    <div class="w-full sm:w-80 md:w-96 shrink-0">
                        <x-dropdown name="distributor_id" label="Direct Enterprise (No Distributor Partner)" :selected="$selectedDistId" theme="sa" wrapClass="w-full" selectClass="px-3.5 py-2 text-xs font-semibold">
                            @if(isset($distributors))
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ (string)$distributor->id === (string)$selectedDistId ? 'selected' : '' }}>
                                        {{ $distributor->name }} — {{ $distributor->email }}
                                    </option>
                                @endforeach
                            @endif
                        </x-dropdown>
                    </div>
                </div>

                <!-- 2. Selectable Subscription Plan Cards -->
                @php
                    $selectedPlanId = old('plan_id', $plans->first()?->id ?? '');
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs sm:text-sm font-bold text-slate-700">
                            2. Choose Per-TV Subscription Tier <span class="text-rose-500 font-black">*</span>
                        </label>
                        <span class="text-xs text-slate-400 font-medium">Select Monthly or Yearly billing tier</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="planSelectionContainer">
                        @foreach($plans as $plan)
                            @php
                                $isCurrentPlan = (string)$plan->id === (string)$selectedPlanId;
                                $taxRate = $plan->tax ? (float)$plan->tax->rate : 0.0;
                                $taxType = $plan->tax_type ?? 'exclusive';
                            @endphp
                            <label class="plan-card-label relative flex flex-col justify-between p-4 sm:p-5 rounded-2xl border-2 transition-all cursor-pointer select-none {{ $isCurrentPlan ? 'border-[#150A42] bg-purple-50/30 shadow-md ring-2 ring-[#150A42]/10' : 'border-slate-200 bg-white hover:border-purple-300 hover:bg-slate-50/60' }}"
                                   data-plan-id="{{ $plan->id }}"
                                   data-price-per-tv="{{ $plan->effective_price_per_tv }}"
                                   data-cycle="{{ $plan->billing_cycle ?? 'monthly' }}"
                                   data-tax-rate="{{ $taxRate }}"
                                   data-tax-type="{{ $taxType }}"
                                   data-tax-name="{{ $plan->tax ? $plan->tax->name : 'No Tax' }}">
                                <input type="radio" 
                                       name="plan_id" 
                                       value="{{ $plan->id }}" 
                                       {{ $isCurrentPlan ? 'checked' : '' }} 
                                       class="sr-only plan-radio"
                                       onchange="onPlanSelected(this)">

                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider font-mono {{ ($plan->billing_cycle ?? 'monthly') === 'yearly' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-[#150A42]' }}">
                                            {{ strtoupper($plan->billing_cycle ?? 'monthly') }} PLAN
                                        </span>
                                        <div class="plan-check-icon w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $isCurrentPlan ? 'bg-[#150A42] text-white' : 'bg-slate-100 text-transparent' }}">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">{{ $plan->name }}</h3>
                                    <div class="mt-2 flex items-baseline space-x-1 font-mono">
                                        <span class="text-2xl font-black text-slate-900">₹{{ number_format($plan->effective_price_per_tv, 0) }}</span>
                                        <span class="text-xs text-slate-500 font-sans">/ TV / {{ ($plan->billing_cycle ?? 'monthly') === 'yearly' ? 'yr' : 'mo' }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                                    <span>
                                        @if($plan->tax)
                                            <i class="fa-solid fa-shield-halved text-purple-600 mr-1"></i> {{ $plan->tax->name }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                        @else
                                            <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Tax Exempt (0%)
                                        @endif
                                    </span>
                                    <span class="font-bold text-slate-700 font-mono">{{ $plan->ott_count }} Apps</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Connected TVs Input & Live Pricing Breakdown -->
                @php
                    $currentTvCount = old('room_count', 30);
                @endphp
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-700">
                                3. Number of Connected Smart TVs <span class="text-rose-500 font-black">*</span>
                            </label>
                            <span class="text-xs text-slate-400 font-medium">Specify how many hardware TVs will be paired in guest rooms</span>
                        </div>

                        <!-- Stepper Counter Input -->
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="adjustTvCount(-5)" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-base flex items-center justify-center transition-all cursor-pointer">
                                -
                            </button>
                            <input type="number" 
                                   id="hotel_tv_input" 
                                   name="room_count" 
                                   value="{{ $currentTvCount }}" 
                                   min="1" 
                                   max="5000" 
                                   required 
                                   oninput="recalculatePricing()" 
                                   class="w-24 text-center py-2 text-base font-black font-mono rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#150A42] bg-[#F8F9FD] text-slate-900">
                            <button type="button" onclick="adjustTvCount(5)" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-base flex items-center justify-center transition-all cursor-pointer">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Quick Select Chips -->
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono mr-1">Quick Select:</span>
                        @foreach([15, 30, 50, 100, 200] as $preset)
                            <button type="button" onclick="setTvCount({{ $preset }})" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono border border-slate-200 bg-[#F8F9FD] hover:bg-purple-50 hover:text-[#150A42] hover:border-purple-200 text-slate-600 transition-all cursor-pointer">
                                {{ $preset }} TVs
                            </button>
                        @endforeach
                    </div>

                    <!-- Live Dynamic Calculation Card -->
                    <div class="p-4 rounded-xl bg-gradient-to-r from-purple-50/70 via-indigo-50/40 to-slate-50 border border-purple-100/80 text-xs space-y-2 font-mono">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Base Rate:</span>
                            <span class="font-bold text-slate-900" id="liveCalcRate">₹150.00 / TV</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Subtotal (<span id="liveCalcTvCount">30</span> TVs):</span>
                            <span class="font-bold text-slate-900" id="liveCalcSubtotal">₹4,500.00</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600" id="liveTaxRow">
                            <span id="liveTaxLabel">GST (18.0% Exclusive):</span>
                            <span class="font-bold text-slate-900" id="liveTaxAmount">+ ₹810.00</span>
                        </div>
                        <div class="pt-2 border-t border-purple-200/60 flex items-center justify-between text-slate-900 font-sans">
                            <span class="font-black text-sm">Estimated Total Payable:</span>
                            <span class="font-black text-lg text-[#150A42] font-mono" id="liveCalcTotal">₹5,310.00</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Payment Timing & Workflow Options -->
                <div class="space-y-3">
                    <label class="block text-xs sm:text-sm font-bold text-slate-700">
                        4. Payment Timing & Activation Workflow
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Option A: Waiting for Payment (DEFAULT) -->
                        <label class="payment-action-card relative flex flex-col justify-between p-4 sm:p-5 rounded-2xl border-2 border-[#150A42] bg-purple-50/20 shadow-xs cursor-pointer transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900">Waiting for Payment (Self-Pay)</h4>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-[#150A42] font-mono mt-0.5">DEFAULT SELECTION</span>
                                    </div>
                                </div>
                                <input type="radio" 
                                       name="payment_action" 
                                       value="pay_later" 
                                       checked 
                                       class="text-[#150A42] focus:ring-[#150A42] h-4 w-4 mt-1"
                                       onchange="onPaymentActionChange(this)">
                            </div>
                            <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                Hotel account is created immediately with <strong>Waiting for Payment</strong> status. Hotel owner can log in to their dashboard and settle payment securely via UPI, Card, or NetBanking to activate live TV streaming.
                            </p>
                        </label>

                        <!-- Option B: Pay Now via Checkout -->
                        <label class="payment-action-card relative flex flex-col justify-between p-4 sm:p-5 rounded-2xl border-2 border-slate-200 bg-white hover:border-purple-300 shadow-xs cursor-pointer transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid fa-credit-card"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900">Pay Now (Instant Checkout)</h4>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 font-mono mt-0.5">SUPER ADMIN CHECKOUT</span>
                                    </div>
                                </div>
                                <input type="radio" 
                                       name="payment_action" 
                                       value="pay_now" 
                                       class="text-[#150A42] focus:ring-[#150A42] h-4 w-4 mt-1"
                                       onchange="onPaymentActionChange(this)">
                            </div>
                            <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                Account is created in database and immediately redirects to the official Checkout page to settle payment. TV pairing license key is activated and streaming devices unlocked immediately.
                            </p>
                        </label>
                    </div>
                </div>

                <!-- 5. Default In-Room TV Theme Style & Approval Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 pt-2">
                    @php
                        $selectedTheme = old('selected_theme_id', $defaultThemeId ?? 1);
                    @endphp
                    <div class="space-y-1.5">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700">
                            Default In-Room TV Theme Style <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="max-w-md">
                            <x-dropdown name="selected_theme_id" required theme="sa" wrapClass="w-full" selectClass="px-3.5 py-2 text-xs font-semibold">
                                @if(isset($themes) && $themes->isNotEmpty())
                                    @foreach($themes as $th)
                                        <option value="{{ $th->theme_id }}" {{ (int)$selectedTheme === (int)$th->theme_id ? 'selected' : '' }}>
                                            Theme #{{ $th->theme_id }} - {{ $th->theme_name ?? 'Standard Layout' }} {{ (int)$th->theme_id === (int)($defaultThemeId ?? 1) ? '(Recommended Default)' : '' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="1" {{ (int)$selectedTheme === 1 ? 'selected' : '' }}>Theme #1 (Default Classic Cosmos)</option>
                                    <option value="2" {{ (int)$selectedTheme === 2 ? 'selected' : '' }}>Theme #2 (Modern Luxury Noir)</option>
                                @endif
                            </x-dropdown>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">Smart TV UI theme rendered on guest-room hardware.</p>
                    </div>

                    @php
                        $currentApproval = old('approval_status', 'approved');
                    @endphp
                    <div class="space-y-1.5">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700">
                            Approval Status <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="max-w-xs">
                            <x-dropdown name="approval_status" required theme="sa" wrapClass="w-full" selectClass="px-3.5 py-2 text-xs font-bold">
                                <option value="approved" {{ $currentApproval === 'approved' ? 'selected' : '' }}>Approved (Active on Network)</option>
                                <option value="pending" {{ $currentApproval === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="disapproved" {{ $currentApproval === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                            </x-dropdown>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">Platform compliance gate for TV hardware pairing.</p>
                    </div>
                </div>
            </div>

            <!-- Sticky-Friendly Bottom Action Bar -->
            <div class="pt-6 sm:pt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
                <a href="{{ route('super-admin.hotels.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold text-center transition-all">
                    Cancel & Return
                </a>

                <div class="flex items-center space-x-3">
                    <button type="submit" id="saveHotelBtn" class="btn-sa-primary w-full sm:w-auto px-7 py-3 text-xs sm:text-sm font-bold shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus text-xs" id="btnIcon"></i>
                        <span id="btnText">Register Hotel Vendor</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Indian Cities Datalist Include -->
@include('partials.indian_cities_datalist')
@endsection

@section('scripts')
<script>
// Password toggle
function togglePasswordVisibility() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('passwordEyeIcon');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Plan selection
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

    recalculatePricing();
}

// TV counter stepper
function adjustTvCount(delta) {
    const input = document.getElementById('hotel_tv_input');
    if (!input) return;
    let current = parseInt(input.value) || 1;
    current = Math.max(1, Math.min(5000, current + delta));
    input.value = current;
    recalculatePricing();
}

function setTvCount(val) {
    const input = document.getElementById('hotel_tv_input');
    if (!input) return;
    input.value = Math.max(1, Math.min(5000, parseInt(val) || 1));
    recalculatePricing();
}

// Live calculation
function recalculatePricing() {
    const selectedPlan = document.querySelector('.plan-radio:checked')?.closest('.plan-card-label');
    const tvInput = document.getElementById('hotel_tv_input');
    if (!selectedPlan || !tvInput) return;

    const ratePerTv = parseFloat(selectedPlan.getAttribute('data-price-per-tv')) || 0;
    const cycle = selectedPlan.getAttribute('data-cycle') || 'monthly';
    const taxRate = parseFloat(selectedPlan.getAttribute('data-tax-rate')) || 0;
    const taxType = selectedPlan.getAttribute('data-tax-type') || 'exclusive';
    const taxName = selectedPlan.getAttribute('data-tax-name') || 'No Tax';
    const tvCount = Math.max(1, parseInt(tvInput.value) || 1);

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

    const btnText = document.getElementById('btnText');
    const btnIcon = document.getElementById('btnIcon');
    if (radio.value === 'pay_now') {
        if (btnText) btnText.textContent = 'Register & Proceed to Payment Checkout →';
        if (btnIcon) btnIcon.className = 'fa-solid fa-credit-card text-xs';
    } else {
        if (btnText) btnText.textContent = 'Register Hotel Vendor';
        if (btnIcon) btnIcon.className = 'fa-solid fa-plus text-xs';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    recalculatePricing();
});
</script>
@endsection
