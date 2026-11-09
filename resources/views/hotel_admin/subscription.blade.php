@extends('layouts.hotel_admin')
@section('title', 'Plan & Billing')
@section('page_title', 'Subscription Plan & Billing')
@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="max-w-6xl mx-auto space-y-4">
    <!-- Active Plan Dashboard Overview (Displayed when subscription is active) -->
    @if(!empty($hasActivePlan))
        <div class="bg-white border border-slate-200/90 rounded-[24px] p-5 sm:p-6 shadow-xs space-y-5">
            <!-- Header Row -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-200/60 flex items-center justify-center text-lg font-black shadow-2xs shrink-0">
                        <i class="fa-solid fa-hotel text-purple-700"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-base sm:text-lg font-black text-slate-900">{{ $hotel->plan->name ?? 'Active Plan' }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-black bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Active License</span>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $hotel->hotel_name ?? 'Hotel' }} • {{ $hotel->city ?? 'Location' }} • {{ strtoupper($hotel->plan->billing_cycle ?? 'Monthly') }} Billing
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                    <button type="button" 
                            onclick="toggleBasePlanRenewal()" 
                            class="px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] border border-purple-200/80 text-xs font-bold transition-all flex items-center space-x-1.5 cursor-pointer shadow-2xs active:scale-95">
                        <i class="fa-solid fa-arrows-rotate text-purple-600 text-[11px]"></i>
                        <span id="toggleRenewalBtnText">Change / Extend Base Plan</span>
                    </button>
                </div>
            </div>

            <!-- 4-Grid Key Metrics -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <!-- 1. Validity -->
                <div class="bg-slate-50/70 border border-slate-200/70 rounded-2xl p-3.5 sm:p-4 space-y-1">
                    <div class="flex items-center justify-between text-slate-400 text-xs">
                        <span class="font-bold font-mono text-[10px] uppercase">Subscription Expiry</span>
                        <i class="fa-solid fa-calendar-check text-purple-600"></i>
                    </div>
                    <div class="text-sm sm:text-base font-black text-slate-900">
                        {{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'Lifetime' }}
                    </div>
                    <div class="flex items-center space-x-1 text-[11px] {{ $remainingDays <= 15 ? 'text-amber-700 font-bold' : 'text-emerald-700 font-semibold' }}">
                        <i class="fa-solid fa-clock text-[10px]"></i>
                        <span>{{ $remainingDays }} Days Left</span>
                    </div>
                </div>

                <!-- 2. Licensed Displays -->
                <div class="bg-slate-50/70 border border-slate-200/70 rounded-2xl p-3.5 sm:p-4 space-y-1">
                    <div class="flex items-center justify-between text-slate-400 text-xs">
                        <span class="font-bold font-mono text-[10px] uppercase">Licensed TVs</span>
                        <i class="fa-solid fa-tv text-purple-600"></i>
                    </div>
                    <div class="text-sm sm:text-base font-black text-[#150A42] font-mono">
                        {{ $hotel->room_count }} Screens
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium">
                        Active In-Room Display Quota
                    </div>
                </div>

                <!-- 3. Connected / Paired Devices -->
                <div class="bg-slate-50/70 border border-slate-200/70 rounded-2xl p-3.5 sm:p-4 space-y-1">
                    <div class="flex items-center justify-between text-slate-400 text-xs">
                        <span class="font-bold font-mono text-[10px] uppercase">TV Cloud Pairing</span>
                        <i class="fa-solid fa-link text-emerald-600"></i>
                    </div>
                    <div class="text-sm sm:text-base font-black text-slate-900 font-mono">
                        {{ $pairedDeviceCount ?? 0 }} / {{ $hotel->room_count }} Paired
                    </div>
                    <div class="text-[11px] text-emerald-700 font-semibold">
                        {{ max(0, $hotel->room_count - ($pairedDeviceCount ?? 0)) }} slots available
                    </div>
                </div>

                <!-- 4. License Key -->
                <div class="bg-slate-50/70 border border-slate-200/70 rounded-2xl p-3.5 sm:p-4 space-y-1">
                    <div class="flex items-center justify-between text-slate-400 text-xs">
                        <span class="font-bold font-mono text-[10px] uppercase">TV License Key</span>
                        <i class="fa-solid fa-key text-purple-600"></i>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="text-xs sm:text-sm font-black font-mono text-purple-950 truncate max-w-[130px]">
                            {{ $hotel->license_key }}
                        </span>
                        <button type="button" 
                                onclick="navigator.clipboard.writeText('{{ $hotel->license_key }}'); alert('License key copied!');"
                                title="Copy License Key" 
                                class="text-slate-400 hover:text-purple-700 p-1 text-xs cursor-pointer">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                    <div class="text-[10px] text-slate-500 font-mono font-bold">
                        Cloud Verified
                    </div>
                </div>
            </div>

            <!-- Features Bar -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3 text-xs text-slate-600">
                <span class="text-[10px] font-bold uppercase tracking-wider font-mono text-slate-400">Included Features:</span>
                <span class="inline-flex items-center space-x-1 font-semibold text-slate-700 bg-slate-100/70 px-2.5 py-1 rounded-lg">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                    <span>TV Launcher & Themes</span>
                </span>
                <span class="inline-flex items-center space-x-1 font-semibold text-slate-700 bg-slate-100/70 px-2.5 py-1 rounded-lg">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                    <span>Hotel Cloud CMS & Remote Controls</span>
                </span>
                <span class="inline-flex items-center space-x-1 font-semibold text-slate-700 bg-slate-100/70 px-2.5 py-1 rounded-lg">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                    <span>Automatic Cloud Sync</span>
                </span>
            </div>
        </div>
    @endif

    <!-- Base Plan Checkout Section (Only displayed directly if Unpaid/Expired; collapsible if Active) -->
    <div id="basePlanCheckoutContainer" class="{{ !empty($hasActivePlan) ? 'hidden' : '' }} space-y-4">
        @if(empty($hasActivePlan))
            <div class="bg-rose-50/80 border border-rose-200/90 rounded-2xl px-4 py-3 flex items-center justify-between gap-3 shadow-2xs">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xs shrink-0 font-bold">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-black text-rose-900 block truncate">
                            {{ $hotel->expiry_date && $hotel->expiry_date->isPast() ? 'Subscription Expired — Renewal Required' : 'Plan Activation Required' }}
                        </span>
                        <p class="text-[11px] text-rose-700 font-medium truncate">
                            Select a plan below and complete payment to unlock hotel features and TV cloud sync.
                        </p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-black bg-rose-100 text-rose-800 border border-rose-200 shrink-0 uppercase tracking-wider">
                    {{ $hotel->expiry_date && $hotel->expiry_date->isPast() ? 'Expired' : 'Unpaid' }}
                </span>
            </div>
        @else
            <div class="bg-purple-50/60 border border-purple-200/80 rounded-2xl px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-info text-purple-700 text-sm"></i>
                    <span class="text-xs font-bold text-purple-900">Select a new plan tier or extend your license duration below:</span>
                </div>
                <button type="button" onclick="toggleBasePlanRenewal()" class="text-xs font-bold text-purple-700 hover:text-purple-950 underline cursor-pointer">
                    Hide Plan Selection
                </button>
            </div>
        @endif

        <!-- Main Checkout: Compact 2-Column Responsive Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- Left Column: Step 1 (TV Stepper) + Step 2 (Plan Cards) (7 cols) -->
            <div class="lg:col-span-7 space-y-4">
                
                <!-- Step 1: Connected TV Stepper -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 1 • Hardware Licensing</span>
                            <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">How many Connected TVs need active licensing?</h4>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-purple-100 text-[#150A42] text-xs font-mono font-black border border-purple-200/80 shadow-2xs">
                            Selected: <strong id="tvCountDisplay" class="text-purple-900 font-black">{{ max(1, $hotel->room_count ?: 30) }}</strong> TVs
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-1">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="adjustHotelTvs(-5)" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 hover:border-purple-300 hover:bg-white active:scale-95 flex items-center justify-center font-bold text-xs text-slate-700 transition-all cursor-pointer shadow-2xs">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" 
                                   id="tvCountInput" 
                                   min="1" 
                                   max="1000" 
                                   value="{{ max(1, $hotel->room_count ?: 30) }}" 
                                   oninput="calcDynamicPricing()" 
                                   class="w-24 text-center py-2 px-2 bg-slate-50 text-[#150A42] font-mono font-black text-lg rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 focus:bg-white shadow-inner">
                            <button type="button" onclick="adjustHotelTvs(5)" class="w-10 h-10 rounded-xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 flex items-center justify-center font-bold text-xs text-white transition-all cursor-pointer shadow-sm">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Presets:</span>
                            <button type="button" onclick="setHotelTvs(15)" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-white border border-slate-200 hover:border-[#150A42] text-[11px] font-bold font-mono text-slate-800 transition-all active:scale-95">15</button>
                            <button type="button" onclick="setHotelTvs(30)" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-white border border-slate-200 hover:border-[#150A42] text-[11px] font-bold font-mono text-slate-800 transition-all active:scale-95">30</button>
                            <button type="button" onclick="setHotelTvs(50)" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-white border border-slate-200 hover:border-[#150A42] text-[11px] font-bold font-mono text-slate-800 transition-all active:scale-95">50</button>
                            <button type="button" onclick="setHotelTvs(100)" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-white border border-slate-200 hover:border-[#150A42] text-[11px] font-bold font-mono text-slate-800 transition-all active:scale-95">100</button>
                            @if($hotel->room_count)
                                <button type="button" onclick="setHotelTvs({{ (int)$hotel->room_count }})" class="px-2.5 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-200 text-[11px] font-black font-mono text-purple-900 transition-all active:scale-95">
                                    <i class="fa-solid fa-rotate-left mr-1 text-[9px]"></i>Reset ({{ (int)$hotel->room_count }})
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 2: Choose Plan Tier -->
                <div class="space-y-2">
                    <span class="text-[10px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 2 • Choose Plan Tier</span>
                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5">
                        @foreach($plans as $plan)
                            @php
                                $monthlyRate = (float) $plan->monthly_rate_per_tv;
                                $annualTotal = (float) $plan->annual_total_per_tv;
                                $billedRate = (float) $plan->billed_amount_per_tv;
                                $tax = $plan->tax;
                                $taxRate = $tax ? $tax->rate : 0;
                                $taxType = $plan->tax_type ?? 'exclusive';
                                $cycle = strtolower($plan->billing_cycle ?? 'monthly');
                                $isCurrentPlan = ($hotel->plan_id == $plan->id);
                                $isPreSelected = ($hotel->plan_id == $plan->id || $loop->first);
                            @endphp
                            <label class="plan-tier-card relative flex flex-col justify-between p-3 sm:p-4 rounded-xl sm:rounded-2xl border-2 cursor-pointer transition-all {{ $isPreSelected ? 'border-[#150A42] bg-purple-50/40 ring-2 ring-purple-500/20 shadow-xs' : 'border-slate-200/90 hover:border-slate-300 bg-white' }}">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        @if($cycle === 'yearly')
                                            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg bg-[#150A42] text-white text-[8px] sm:text-[9px] font-black uppercase font-mono tracking-wider shadow-2xs">
                                                <i class="fa-solid fa-crown text-[7px] sm:text-[8px] text-purple-300 mr-1"></i> Annual
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg bg-purple-100 text-[#150A42] text-[8px] sm:text-[9px] font-black uppercase font-mono tracking-wider">
                                                <i class="fa-solid fa-calendar-days text-[7px] sm:text-[8px] text-purple-600 mr-1"></i> Monthly
                                            </span>
                                        @endif

                                        <input type="radio" 
                                               name="plan_id" 
                                               value="{{ $plan->id }}" 
                                               data-rate="{{ $billedRate }}" 
                                               data-monthly-rate="{{ $monthlyRate }}"
                                               data-annual-total="{{ $annualTotal }}"
                                               data-cycle="{{ $cycle }}"
                                               data-tax-rate="{{ $taxRate }}"
                                               data-tax-name="{{ $tax ? $tax->name : 'GST' }}"
                                               data-tax-type="{{ $taxType }}"
                                               data-is-split="{{ ($tax && $tax->is_split) ? '1' : '0' }}"
                                               data-name="{{ $plan->name }}" 
                                               class="plan-radio w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#150A42] border-slate-300 focus:ring-[#150A42]" 
                                               {{ $isPreSelected ? 'checked' : '' }}>
                                    </div>

                                    <div>
                                        <div class="font-black text-slate-900 text-xs sm:text-sm truncate">{{ $plan->name }}</div>
                                        <div class="text-base sm:text-xl font-black text-[#150A42] mt-0.5 font-mono">
                                            ₹{{ number_format($monthlyRate, 0) }} 
                                            <span class="text-[9px] sm:text-[10px] text-slate-400 font-sans font-medium block sm:inline">/ TV / month</span>
                                        </div>
                                        @if($cycle === 'yearly')
                                            <div class="text-[9px] text-amber-800 font-bold font-mono mt-0.5">
                                                Billed annually: ₹{{ number_format($annualTotal, 0) }} / TV / yr
                                            </div>
                                        @endif

                                        <!-- Tax Pill -->
                                        <div class="mt-1.5">
                                            @if($taxRate > 0)
                                                <span class="inline-flex items-center text-[8px] sm:text-[9px] font-bold font-mono px-1.5 sm:px-2 py-0.5 rounded-md {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-purple-50 text-purple-800 border border-purple-200/80' }}">
                                                    {{ $tax->name }} ({{ number_format($taxRate, 0) }}%)
                                                </span>
                                            @else
                                                <span class="inline-block text-[8px] sm:text-[9px] font-mono px-1.5 sm:px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                                                    No Tax
                                                </span>
                                            @endif
                                        </div>

                                        @if($isCurrentPlan)
                                            <span class="inline-flex items-center mt-1.5 text-[8px] sm:text-[9px] font-black text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shadow-2xs">
                                                <i class="fa-solid fa-circle-check text-[7px] mr-1 text-emerald-600"></i> Active Plan
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2.5 pt-2 border-t border-slate-100 space-y-1 text-[9px] sm:text-[10px] text-slate-600 font-medium">
                                    @if($plan->hasOtt())
                                        <div class="flex items-center space-x-1.5 truncate">
                                            <i class="fa-solid fa-play text-purple-600 text-[9px] shrink-0"></i>
                                            <span class="truncate">{{ count($plan->ott_platforms ?? []) }} Streaming Apps</span>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-1.5 truncate text-slate-400">
                                            <i class="fa-solid fa-ban text-slate-400 text-[9px] shrink-0"></i>
                                            <span class="truncate">OTT Excluded</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <i class="fa-solid fa-bars text-purple-600 text-[9px] shrink-0"></i>
                                        <span class="truncate">{{ is_array($plan->allowed_menus) ? count($plan->allowed_menus) : '10' }} Launcher Menus</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column: Step 3 Duration Selector & Live Itemized Breakdown (5 cols, sticky) -->
            <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-20">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                    
                    <div class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 3 • Settlement Summary</span>
                        <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">Duration & Transparent Invoice Breakdown</h4>
                    </div>

                    <!-- Custom Duration Dropdown (Distributor/SuperAdmin Style) -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-black text-slate-800" id="durationSelectLabel">
                                License Validity Period
                            </label>
                            <span id="durationCycleBadge" class="px-2 py-0.5 rounded-full bg-purple-100 text-[#150A42] text-[9px] font-black uppercase font-mono tracking-wider">
                                Monthly Billing
                            </span>
                        </div>

                        <!-- Hidden native select for script access -->
                        <select name="duration_months" id="durationSelect" required class="sr-only">
                            <option value="1">1 Month (Monthly Pack)</option>
                            <option value="3">3 Months (Quarterly Pack)</option>
                            <option value="6">6 Months (Semi-Annual Pack)</option>
                            <option value="12">12 Months (Annual Pack)</option>
                        </select>

                        <!-- Custom Duration Trigger Button -->
                        <button type="button" 
                                id="durationCustomTriggerBtn" 
                                onclick="toggleDurationCustomDropdown(event)" 
                                class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-purple-300 focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 font-semibold text-slate-800 transition-all flex items-center justify-between shadow-xs cursor-pointer group">
                            <div class="flex items-center space-x-2.5 min-w-0">
                                <div class="w-6 h-6 rounded-lg bg-purple-100 text-[#150A42] flex items-center justify-center text-xs shrink-0 font-bold">
                                    <i class="fa-solid fa-calendar-days text-[11px]"></i>
                                </div>
                                <span id="durationCustomTriggerLabel" class="block font-black text-slate-900 truncate text-xs">
                                    1 Month (Monthly Pack)
                                </span>
                            </div>
                            <i id="durationCustomChevron" class="fa-solid fa-chevron-down text-slate-400 group-hover:text-purple-700 text-[10px] transition-transform duration-200 ml-2"></i>
                        </button>

                        <!-- Floating Custom Dropdown Menu -->
                        <div id="durationCustomMenu" 
                             class="hidden absolute z-50 left-0 right-0 top-full mt-1.5 bg-white rounded-2xl border border-slate-200/90 shadow-2xl p-1.5 max-h-60 overflow-y-auto space-y-1 backdrop-blur-md">
                        </div>
                    </div>

                    <!-- Itemized Breakdown Box -->
                    <div class="space-y-2 text-xs text-slate-600 bg-slate-50/60 p-3.5 rounded-xl border border-slate-100">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Plan</span>
                                <span id="summaryPlanName" class="text-xs font-black text-slate-900">Deluxe Plan</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Screens</span>
                                <span id="summaryTvCount" class="text-xs font-black text-purple-900 font-mono">15 Smart TVs</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <span>Rate per Display:</span>
                            <span id="summaryRateDisplay" class="font-mono font-bold text-slate-800">₹150.00 / TV / mo</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span>Validity Duration:</span>
                            <span id="summaryDurationText" class="font-mono font-bold text-purple-900">1 Month</span>
                        </div>

                        <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                            <div>
                                <span class="block text-slate-700 font-bold">Base Subtotal:</span>
                                <span id="summaryFormula" class="text-[10px] text-purple-700 font-mono font-bold block">15 TVs × ₹150.00 × 1 mo</span>
                            </div>
                            <span id="summarySubtotal" class="font-mono font-black text-slate-900 text-xs">₹2,250.00</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span id="summaryTaxName">Applicable GST / Tax:</span>
                            <span id="summaryTaxAmount" class="font-mono font-bold text-slate-900">+₹405.00</span>
                        </div>

                        <!-- CGST / SGST breakdown rows -->
                        <div id="cgstRow" class="hidden items-center justify-between text-[11px] text-slate-500 pl-2">
                            <span id="summaryCgstLabel">CGST (9%):</span>
                            <span id="summaryCgstAmount" class="font-mono font-bold text-slate-700">₹202.50</span>
                        </div>
                        <div id="sgstRow" class="hidden items-center justify-between text-[11px] text-slate-500 pl-2">
                            <span id="summarySgstLabel">SGST (9%):</span>
                            <span id="summarySgstAmount" class="font-mono font-bold text-slate-700">₹202.50</span>
                        </div>

                        <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-black text-slate-900 uppercase tracking-tight block">Total Payable</span>
                                <span class="text-[9px] text-slate-400 font-medium">Inclusive of GST</span>
                            </div>
                            <div id="totalBox" class="text-xl sm:text-2xl font-black text-[#150A42] font-mono tracking-tight">₹2,655.00</div>
                        </div>
                    </div>

                    <!-- Pay Button -->
                    <div>
                        <button id="payBtn" type="button" class="w-full py-3 px-5 rounded-xl bg-[#150A42] hover:bg-[#1f105e] active:scale-98 text-white font-extrabold text-xs transition-all shadow-md shadow-[#150A42]/20 cursor-pointer flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-lock text-purple-300"></i>
                            <span id="payBtnLabel">Pay Securely <span id="payBtnAmount">₹2,655.00</span> & Activate</span>
                        </button>
                        <p id="payMsg" class="text-xs text-center mt-2 hidden"></p>
                    </div>

                    <div class="text-center text-[10px] text-slate-400 flex items-center justify-center space-x-1.5 font-medium">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>256-Bit SSL Encrypted • Instant TV License Generation</span>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- TV Add-On Component for Active Subscribers Only (Compact) -->
    @if(!empty($hasActivePlan) && !empty($addonPricing))
    <div class="bg-gradient-to-br from-[#150A42] to-[#251068] text-white rounded-2xl p-5 shadow-md shadow-[#150A42]/15 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-3">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 text-purple-300 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-plus-circle"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded-full bg-purple-400/20 text-purple-200 text-[9px] font-black uppercase font-mono tracking-wider border border-purple-300/30">Prorated Add-On</span>
                        <span class="text-[11px] text-white/60 font-mono">Plan: {{ $hotel->plan->name }}</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-white tracking-tight mt-0.5">Need More Screens? Instant TV Capacity Add-On</h3>
                </div>
            </div>

            <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-xl border border-white/15 shrink-0 font-mono text-[11px] font-bold">
                <i class="fa-solid fa-clock text-purple-300"></i>
                <span>Remaining: <strong class="text-white">{{ $remainingDays }} Days</strong></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
            <div class="lg:col-span-7 space-y-2">
                <label class="block text-[11px] font-bold text-white/90">Select Extra TV Screens:</label>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="adjustHotelAddonTvs(-5)" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 active:scale-95 text-white font-black text-sm flex items-center justify-center transition-all cursor-pointer border border-white/15">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>

                    <div class="relative flex-1 flex items-center bg-white/10 rounded-xl border border-white/20 px-2 py-1">
                        <input type="number" 
                               id="hotelAddonTvInput" 
                               value="10" 
                               min="1" 
                               max="1000" 
                               inputmode="numeric" 
                               oninput="calcHotelAddonPricing()"
                               class="w-full text-center py-1 text-base font-black text-white bg-transparent border-0 focus:ring-0 focus:outline-none font-mono">
                        <span class="px-2 py-0.5 rounded-lg bg-purple-500/30 text-purple-200 text-[10px] font-bold uppercase tracking-wider select-none font-mono">
                            Extra TVs
                        </span>
                    </div>

                    <button type="button" onclick="adjustHotelAddonTvs(5)" class="w-9 h-9 rounded-xl bg-purple-500 hover:bg-purple-400 active:scale-95 text-white font-black text-sm flex items-center justify-center transition-all cursor-pointer shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                    <span class="text-[10px] text-white/60 font-bold">Presets:</span>
                    <button type="button" onclick="setHotelAddonTvs(5)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold text-[10px] transition-all cursor-pointer active:scale-95 border border-white/10">+5 TVs</button>
                    <button type="button" onclick="setHotelAddonTvs(10)" class="px-2 py-0.5 rounded-lg bg-purple-500/30 hover:bg-purple-500/40 text-purple-200 font-bold text-[10px] transition-all cursor-pointer active:scale-95 border border-purple-400/40">+10 TVs</button>
                    <button type="button" onclick="setHotelAddonTvs(20)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold text-[10px] transition-all cursor-pointer active:scale-95 border border-white/10">+20 TVs</button>
                    <button type="button" onclick="setHotelAddonTvs(50)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold text-[10px] transition-all cursor-pointer active:scale-95 border border-white/10">+50 TVs</button>
                </div>
            </div>

            <div class="lg:col-span-5 bg-white/10 border border-white/15 rounded-xl p-3.5 space-y-2.5">
                <div class="space-y-1 text-xs text-white/80">
                    <div class="flex justify-between">
                        <span>Prorated Subtotal:</span>
                        <span class="font-mono font-bold text-white" id="hotelAddonSubtotalText">₹{{ number_format($addonPricing['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-white/60 text-[11px]">
                        <span>GST ({{ $addonPricing['tax_rate'] }}%):</span>
                        <span class="font-mono" id="hotelAddonTaxText">₹{{ number_format($addonPricing['tax_amount'], 2) }}</span>
                    </div>
                    <div class="pt-1.5 border-t border-white/15 flex justify-between items-baseline">
                        <span class="font-bold text-white text-xs">Total:</span>
                        <span class="text-lg font-black font-mono text-purple-200" id="hotelAddonTotalText">₹{{ number_format($addonPricing['total_amount'], 2) }}</span>
                    </div>
                </div>

                <button type="button" id="payHotelAddonBtn" class="w-full py-2.5 px-3 rounded-xl bg-purple-600 hover:bg-purple-500 active:scale-98 text-white font-black text-xs transition-all shadow-md shadow-purple-950/30 cursor-pointer flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span id="payHotelAddonBtnText">Pay & Add Extra TVs</span>
                </button>
                <div id="hotelAddonMsg" class="hidden text-center text-xs font-bold py-1"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Tax Invoices & Receipts: ONLY DISPLAYED IF ACTIVE PLAN EXISTS -->
    @if(!empty($hasActivePlan) && !empty($recentInvoices) && $recentInvoices->count() > 0)
    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-xs space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-file-invoice-dollar text-purple-700"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Recent Tax Invoices & Receipts</h3>
                    <p class="text-[11px] text-slate-400">Download or print official GST tax receipts for your records.</p>
                </div>
            </div>
            <a href="{{ route('hotel.invoices.index') }}" class="text-xs font-bold text-purple-700 hover:text-purple-950 flex items-center space-x-1">
                <span>View All Invoices</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-black uppercase tracking-wider text-[10px] font-mono border-b border-slate-100">
                        <th class="py-2.5 px-3">Invoice #</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Plan & Screens</th>
                        <th class="py-2.5 px-3">Total Amount</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentInvoices as $rInv)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="py-3 px-3 font-mono font-bold text-slate-900">{{ $rInv->invoice_number }}</td>
                            <td class="py-3 px-3 text-slate-400 text-[11px]">{{ $rInv->created_at->format('d M, Y') }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $rInv->plan_name }} ({{ $rInv->tv_count }} TVs)</td>
                            <td class="py-3 px-3 font-mono font-black text-slate-900">₹{{ number_format($rInv->total_amount, 2) }}</td>
                            <td class="py-3 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700">
                                    <i class="fa-solid fa-circle-check text-[8px] mr-1"></i> Paid
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('hotel.invoices.show', $rInv->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#150A42] text-white hover:bg-[#1f105e] text-xs font-bold shadow-xs transition-all active:scale-95">
                                    <i class="fa-solid fa-print text-[9px] text-purple-300 mr-1"></i>
                                    <span>Print</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRadios = document.querySelectorAll('.plan-radio');
    const durationSelect = document.getElementById('durationSelect');
    const tvCountInput = document.getElementById('tvCountInput');
    const payBtn = document.getElementById('payBtn');
    const payMsg = document.getElementById('payMsg');
    const orderUrl = "{{ route('hotel.subscription.order') }}";
    const purchaseUrl = "{{ route('hotel.subscription.purchase') }}";
    let processing = false;

    window.adjustHotelTvs = function(delta) {
        let cur = parseInt(tvCountInput.value) || 1;
        cur = Math.max(1, Math.min(1000, cur + delta));
        tvCountInput.value = cur;
        calcDynamicPricing();
    };

    window.setHotelTvs = function(val) {
        tvCountInput.value = Math.max(1, Math.min(1000, parseInt(val) || 1));
        calcDynamicPricing();
    };

    // Render custom dropdown items matching distributor/superadmin
    function renderCustomDurationMenu() {
        const customMenu = document.getElementById('durationCustomMenu');
        const customLabel = document.getElementById('durationCustomTriggerLabel');
        if (!customMenu || !durationSelect) return;

        customMenu.innerHTML = '';
        const options = Array.from(durationSelect.options);

        options.forEach(opt => {
            const isSelected = opt.value === durationSelect.value;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-between cursor-pointer ${
                isSelected 
                    ? 'bg-purple-50 text-[#150A42] border border-purple-200/80 shadow-2xs font-black' 
                    : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'
            }`;
            btn.innerHTML = `
                <div class="flex items-center space-x-2 truncate">
                    <span class="w-1.5 h-1.5 rounded-full ${isSelected ? 'bg-purple-600' : 'bg-slate-300'}"></span>
                    <span class="truncate">${opt.text}</span>
                </div>
                ${isSelected ? '<span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[9px] shrink-0 ml-1.5"><i class="fa-solid fa-check"></i></span>' : ''}
            `;
            btn.onclick = function(e) {
                selectDurationOption(opt.value, opt.text, e);
            };
            customMenu.appendChild(btn);

            if (isSelected && customLabel) {
                customLabel.textContent = opt.text;
            }
        });
    }

    window.toggleDurationCustomDropdown = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const customMenu = document.getElementById('durationCustomMenu');
        const chevron = document.getElementById('durationCustomChevron');
        if (!customMenu) return;

        const isClosed = customMenu.classList.contains('hidden');
        if (isClosed) {
            customMenu.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            customMenu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    };

    window.selectDurationOption = function(val, text, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!durationSelect) return;
        durationSelect.value = val;

        const customLabel = document.getElementById('durationCustomTriggerLabel');
        if (customLabel) customLabel.textContent = text;

        const customMenu = document.getElementById('durationCustomMenu');
        const chevron = document.getElementById('durationCustomChevron');
        if (customMenu) customMenu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');

        renderCustomDurationMenu();
        calcDynamicPricing();
    };

    // Close custom dropdown on outside click
    document.addEventListener('click', function(e) {
        const customMenu = document.getElementById('durationCustomMenu');
        const triggerBtn = document.getElementById('durationCustomTriggerBtn');
        const chevron = document.getElementById('durationCustomChevron');
        if (customMenu && !customMenu.contains(e.target) && triggerBtn && !triggerBtn.contains(e.target)) {
            customMenu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    });

    // Dynamically synchronize options: ONLY MONTHS for Monthly Plan; YEARS for Yearly Plan
    function syncDurationOptions() {
        const sel = document.querySelector('.plan-radio:checked');
        if (!sel || !durationSelect) return;

        const cycle = (sel.dataset.cycle || 'monthly').toLowerCase();
        const isYearly = (cycle === 'yearly');
        const currentVal = parseInt(durationSelect.value) || (isYearly ? 12 : 1);

        const durationLabel = document.getElementById('durationSelectLabel');
        const durationCycleBadge = document.getElementById('durationCycleBadge');

        if (durationLabel) {
            durationLabel.textContent = isYearly ? 'License Validity Period (Annual Pack)' : 'License Validity Period (Monthly Pack)';
        }
        if (durationCycleBadge) {
            durationCycleBadge.textContent = isYearly ? 'Annual Billing' : 'Monthly Billing';
            durationCycleBadge.className = isYearly 
                ? 'px-2 py-0.5 rounded-full bg-[#150A42] text-white text-[9px] font-black uppercase font-mono tracking-wider'
                : 'px-2 py-0.5 rounded-full bg-purple-100 text-[#150A42] text-[9px] font-black uppercase font-mono tracking-wider';
        }

        const isCurrentlyYearlyDropdown = (durationSelect.querySelector('option[value="24"]') !== null && durationSelect.querySelector('option[value="1"]') === null);

        if (isYearly && !isCurrentlyYearlyDropdown) {
            durationSelect.innerHTML = `
                <option value="12">1 Year (12 Months - Annual Pack)</option>
                <option value="24">2 Years (24 Months - 2-Year Pack)</option>
                <option value="36">3 Years (36 Months - 3-Year Pack)</option>
            `;
            durationSelect.value = (currentVal >= 12 && [12, 24, 36].includes(currentVal)) ? String(currentVal) : '12';
        } else if (!isYearly && isCurrentlyYearlyDropdown) {
            // Strictly monthly packs (no multi-year for monthly plans)
            durationSelect.innerHTML = `
                <option value="1">1 Month (Monthly Pack)</option>
                <option value="3">3 Months (Quarterly Pack)</option>
                <option value="6">6 Months (Semi-Annual Pack)</option>
                <option value="12">12 Months (Annual Pack)</option>
            `;
            durationSelect.value = [1, 3, 6, 12].includes(currentVal) ? String(currentVal) : '1';
        }

        renderCustomDurationMenu();
    }

    window.calcDynamicPricing = function() {
        let sel = document.querySelector('.plan-radio:checked');
        if (!sel && planRadios.length > 0) { planRadios[0].checked = true; sel = planRadios[0]; }
        if (!sel) return { amount: 0, planId: null, months: 1, tvCount: 1 };

        // Visual highlight on cards
        document.querySelectorAll('.plan-tier-card').forEach(card => {
            const rad = card.querySelector('.plan-radio');
            if (rad && rad.checked) {
                card.className = 'plan-tier-card relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all border-[#150A42] bg-purple-50/40 ring-2 ring-purple-500/20 shadow-xs';
            } else {
                card.className = 'plan-tier-card relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all border-slate-200/90 hover:border-slate-300 bg-white';
            }
        });

        const tvCount = Math.max(1, parseInt(tvCountInput.value) || 1);
        const rate = parseFloat(sel.dataset.rate) || 0;
        const taxRate = parseFloat(sel.dataset.taxRate) || 0;
        const taxName = sel.dataset.taxName || 'GST';
        const taxType = sel.dataset.taxType || 'exclusive';
        const isSplit = sel.dataset.isSplit === '1';
        const cycle = (sel.dataset.cycle || 'monthly').toLowerCase();
        const isYearly = (cycle === 'yearly');
        const name = sel.dataset.name || 'Selected Plan';

        let months = parseInt(durationSelect.value) || (isYearly ? 12 : 1);
        const multiplier = isYearly ? Math.max(1, Math.round((months / 12) * 100) / 100) : months;

        let subtotal = Math.round((rate * tvCount * multiplier) * 100) / 100;
        let baseSubtotal = subtotal;
        let taxAmount = 0.00;
        let total = subtotal;
        let cgst = 0.00;
        let sgst = 0.00;

        if (taxRate > 0) {
            if (taxType === 'inclusive') {
                taxAmount = Math.round((subtotal - (subtotal / (1 + (taxRate / 100)))) * 100) / 100;
                baseSubtotal = Math.round((subtotal - taxAmount) * 100) / 100;
                total = subtotal;
            } else {
                taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
                total = Math.round((subtotal + taxAmount) * 100) / 100;
                baseSubtotal = subtotal;
            }

            if (isSplit) {
                cgst = Math.round((taxAmount / 2) * 100) / 100;
                sgst = Math.round((taxAmount - cgst) * 100) / 100;
            }
        }

        // DOM elements
        const tvCountDisplay = document.getElementById('tvCountDisplay');
        if (tvCountDisplay) tvCountDisplay.textContent = tvCount;

        const summaryPlanName = document.getElementById('summaryPlanName');
        if (summaryPlanName) summaryPlanName.textContent = name;

        const summaryTvCount = document.getElementById('summaryTvCount');
        if (summaryTvCount) summaryTvCount.textContent = `${tvCount} Smart TVs`;

        const monthlyRate = parseFloat(sel.dataset.monthlyRate) || rate;
        const annualTotal = parseFloat(sel.dataset.annualTotal) || (monthlyRate * 12);
        const summaryRateDisplay = document.getElementById('summaryRateDisplay');
        if (summaryRateDisplay) {
            if (isYearly) {
                summaryRateDisplay.innerHTML = `<span class="text-slate-900 font-bold">₹${monthlyRate.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} / TV / mo</span> <span class="text-[10px] text-amber-800 font-medium block">(Billed annually: ₹${annualTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} / TV / yr)</span>`;
            } else {
                summaryRateDisplay.textContent = `₹${monthlyRate.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} / TV / month`;
            }
        }

        const summaryDurationText = document.getElementById('summaryDurationText');
        if (summaryDurationText) {
            if (isYearly) {
                const yrs = Math.max(1, Math.round(months / 12));
                summaryDurationText.textContent = `${yrs} Year${yrs > 1 ? 's' : ''} (${months} Months)`;
            } else {
                summaryDurationText.textContent = `${months} Month${months > 1 ? 's' : ''}`;
            }
        }

        const summaryFormula = document.getElementById('summaryFormula');
        if (summaryFormula) {
            const timeStr = isYearly 
                ? (multiplier === 1 ? '1 yr' : `${multiplier} yrs`) 
                : (months === 1 ? '1 mo' : `${months} mos`);
            summaryFormula.textContent = `${tvCount} TVs × ₹${rate.toLocaleString('en-IN')} × ${timeStr}`;
        }

        const summarySubtotal = document.getElementById('summarySubtotal');
        if (summarySubtotal) {
            summarySubtotal.textContent = '₹' + baseSubtotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        const summaryTaxName = document.getElementById('summaryTaxName');
        if (summaryTaxName) {
            summaryTaxName.textContent = taxRate > 0 
                ? `${taxName} (${taxRate.toFixed(0)}% ${taxType.charAt(0).toUpperCase() + taxType.slice(1)}):` 
                : 'Statutory Tax:';
        }

        const summaryTaxAmount = document.getElementById('summaryTaxAmount');
        if (summaryTaxAmount) {
            summaryTaxAmount.textContent = taxRate > 0 
                ? `+₹${taxAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` 
                : '₹0.00';
        }

        const cgstRow = document.getElementById('cgstRow');
        const sgstRow = document.getElementById('sgstRow');
        if (taxRate > 0 && isSplit) {
            if (cgstRow) { cgstRow.classList.remove('hidden'); cgstRow.classList.add('flex'); }
            if (sgstRow) { sgstRow.classList.remove('hidden'); sgstRow.classList.add('flex'); }
            const summaryCgstLabel = document.getElementById('summaryCgstLabel');
            if (summaryCgstLabel) summaryCgstLabel.textContent = `CGST (${(taxRate / 2).toFixed(1)}%):`;
            const summaryCgstAmount = document.getElementById('summaryCgstAmount');
            if (summaryCgstAmount) summaryCgstAmount.textContent = `₹${cgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            const summarySgstLabel = document.getElementById('summarySgstLabel');
            if (summarySgstLabel) summarySgstLabel.textContent = `SGST (${(taxRate / 2).toFixed(1)}%):`;
            const summarySgstAmount = document.getElementById('summarySgstAmount');
            if (summarySgstAmount) summarySgstAmount.textContent = `₹${sgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        } else {
            if (cgstRow) { cgstRow.classList.add('hidden'); cgstRow.classList.remove('flex'); }
            if (sgstRow) { sgstRow.classList.add('hidden'); sgstRow.classList.remove('flex'); }
        }

        const totalFormatted = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const totalBox = document.getElementById('totalBox');
        if (totalBox) totalBox.textContent = totalFormatted;

        const payBtnAmount = document.getElementById('payBtnAmount');
        if (payBtnAmount) payBtnAmount.textContent = totalFormatted;

        return { amount: total, planId: sel.value, months: months, tvCount: tvCount };
    };

    planRadios.forEach(r => r.addEventListener('change', () => {
        syncDurationOptions();
        calcDynamicPricing();
    }));
    if (durationSelect) durationSelect.addEventListener('change', calcDynamicPricing);
    if (tvCountInput) tvCountInput.addEventListener('input', calcDynamicPricing);

    // Initial setup
    syncDurationOptions();
    calcDynamicPricing();

    window.toggleBasePlanRenewal = function() {
        const container = document.getElementById('basePlanCheckoutContainer');
        const btnText = document.getElementById('toggleRenewalBtnText');
        if (!container) return;
        const isHidden = container.classList.contains('hidden');
        if (isHidden) {
            container.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Close Plan Selection';
            syncDurationOptions();
            calcDynamicPricing();
            container.scrollIntoView({ behavior: 'smooth' });
        } else {
            container.classList.add('hidden');
            if (btnText) btnText.textContent = 'Change / Extend Base Plan';
        }
    };

    function setMsg(text, isError) {
        if (!payMsg) return;
        payMsg.textContent = text;
        payMsg.className = 'text-xs mt-2 text-center ' + (isError ? 'text-rose-600 font-bold' : 'text-slate-500 font-medium');
        payMsg.classList.remove('hidden');
    }

    function reset() { 
        processing = false; 
        if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
        if (payBtn) {
            payBtn.disabled = false; 
            payBtn.classList.remove('opacity-70', 'cursor-wait');
            const d = calcDynamicPricing();
            const totalFormatted = '₹' + (d.amount ? d.amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
            payBtn.innerHTML = '<i class="fa-solid fa-lock text-purple-300"></i><span id="payBtnLabel">Pay Securely <span id="payBtnAmount">' + totalFormatted + '</span> & Activate</span>';
        }
    }

    if (payBtn) {
        payBtn.addEventListener('click', async function() {
            if (processing) return;
            const d = calcDynamicPricing();
            if (!d.planId) { setMsg('Please select a plan first.', true); return; }
            processing = true; 
            payBtn.disabled = true;
            payBtn.classList.add('opacity-70', 'cursor-wait');
            payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Initializing Payment Gateway...';
            setMsg('', false);

            try {
                const res = await fetch(orderUrl, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest' 
                    },
                    body: JSON.stringify({ plan_id: d.planId, tv_count: d.tvCount, duration_months: d.months })
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Could not create order.');

                if (data.gateway === 'payu' || (data.payu_params && data.checkout_url)) {
                    setMsg('Redirecting to PayU...', false);
                    const payuForm = document.createElement('form');
                    payuForm.method = 'POST';
                    payuForm.action = data.checkout_url;
                    payuForm.style.display = 'none';

                    const params = data.payu_params || {};
                    Object.keys(params).forEach(function(k) {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = k;
                        inp.value = params[k];
                        payuForm.appendChild(inp);
                    });

                    document.body.appendChild(payuForm);
                    payuForm.submit();
                    return;
                }

                if (typeof Razorpay === 'undefined') throw new Error('Razorpay failed to load. Refresh page.');
                const rzp = new Razorpay({
                    key: data.key_id, 
                    order_id: data.order_id,
                    amount: Math.round(parseFloat(data.amount) * 100), 
                    currency: data.currency || 'INR',
                    name: 'PaxTV Hospitality', 
                    description: 'Connected TV Subscription (' + d.tvCount + ' TVs)',
                    prefill: { name: data.name || '', email: data.email || '', contact: data.phone || '' },
                    theme: { color: '#150A42' },
                    handler: function(resp) {
                        fetch(purchaseUrl, {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest' 
                            },
                            body: JSON.stringify({ 
                                plan_id: d.planId, 
                                tv_count: d.tvCount,
                                duration_months: d.months,
                                razorpay_order_id: resp.razorpay_order_id,
                                razorpay_payment_id: resp.razorpay_payment_id,
                                razorpay_signature: resp.razorpay_signature 
                            })
                        }).then(r => r.json()).then(function(pd) {
                            if (pd.success) { window.location.reload(); }
                            else { setMsg(pd.message || 'Activation failed.', true); reset(); }
                        }).catch(function() { setMsg('Network error during activation.', true); reset(); });
                    },
                    modal: { 
                        ondismiss: function() { 
                            reset(); 
                        } 
                    }
                });
                rzp.on('payment.failed', function(r) { setMsg('Payment failed: ' + (r.error?.description || 'Try again.'), true); reset(); });
                rzp.open();
            } catch (e) { setMsg(e.message, true); reset(); }
        });
    }

    // --- Hotel TV Add-On Handling ---
    @if(!empty($hasActivePlan) && !empty($addonPricing))
    const hotelCurrentTvs = {{ (int)$hotel->room_count }};
    const hotelDailyRate = {{ (float)$addonPricing['daily_rate_per_tv'] }};
    const hotelRemainingDays = {{ (int)$remainingDays }};
    const hotelTaxRate = {{ (float)$addonPricing['tax_rate'] }};
    const hotelIsInclusive = "{{ $hotel->plan->tax_type ?? 'exclusive' }}" === 'inclusive';
    const hotelAddonOrderUrl = "{{ route('hotel.subscription.addon-order') }}";
    const hotelAddonPurchaseUrl = "{{ route('hotel.subscription.addon-purchase') }}";

    window.adjustHotelAddonTvs = function(delta) {
        const inp = document.getElementById('hotelAddonTvInput');
        if (!inp) return;
        let cur = parseInt(inp.value) || 0;
        inp.value = Math.max(1, cur + delta);
        window.calcHotelAddonPricing();
    };

    window.setHotelAddonTvs = function(val) {
        const inp = document.getElementById('hotelAddonTvInput');
        if (!inp) return;
        inp.value = val;
        window.calcHotelAddonPricing();
    };

    window.calcHotelAddonPricing = function() {
        const inp = document.getElementById('hotelAddonTvInput');
        if (!inp) return;
        const extraTvs = Math.max(1, parseInt(inp.value) || 1);

        const rawSubtotal = extraTvs * hotelDailyRate * hotelRemainingDays;
        let subtotal = 0;
        let taxAmount = 0;
        let total = 0;

        if (hotelIsInclusive) {
            total = Math.round(rawSubtotal * 100) / 100;
            subtotal = Math.round((total / (1 + (hotelTaxRate / 100))) * 100) / 100;
            taxAmount = Math.round((total - subtotal) * 100) / 100;
        } else {
            subtotal = Math.round(rawSubtotal * 100) / 100;
            taxAmount = Math.round((subtotal * (hotelTaxRate / 100)) * 100) / 100;
            total = Math.round((subtotal + taxAmount) * 100) / 100;
        }

        const subEl = document.getElementById('hotelAddonSubtotalText');
        const taxEl = document.getElementById('hotelAddonTaxText');
        const totEl = document.getElementById('hotelAddonTotalText');

        if (subEl) subEl.innerText = '₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (taxEl) taxEl.innerText = '₹' + taxAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (totEl) totEl.innerText = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        return { extraTvs, total, subtotal, taxAmount };
    };

    const addonBtn = document.getElementById('payHotelAddonBtn');
    const addonMsg = document.getElementById('hotelAddonMsg');
    let addonProcessing = false;

    function setAddonMsg(text, isErr) {
        if (!addonMsg) return;
        addonMsg.innerText = text;
        addonMsg.className = 'text-center text-xs font-bold py-1 ' + (isErr ? 'text-rose-300' : 'text-purple-300');
        addonMsg.classList.remove('hidden');
    }

    if (addonBtn) {
        addonBtn.addEventListener('click', async function() {
            if (addonProcessing) return;
            const p = window.calcHotelAddonPricing();
            if (!p || p.extraTvs < 1) return;

            addonProcessing = true;
            addonBtn.disabled = true;
            addonBtn.classList.add('opacity-70', 'cursor-wait');
            addonBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Initializing...';
            setAddonMsg('', false);

            try {
                const res = await fetch(hotelAddonOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ additional_tvs: p.extraTvs })
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Could not initiate TV add-on payment.');
                }

                if (typeof Razorpay === 'undefined') {
                    throw new Error('Razorpay failed to load. Please refresh.');
                }

                const rzp = new Razorpay({
                    key: data.key_id,
                    order_id: data.order_id,
                    amount: Math.round(parseFloat(data.amount) * 100),
                    currency: data.currency || 'INR',
                    name: 'PaxTV Hospitality',
                    description: 'TV Screen Add-On (+' + p.extraTvs + ' TVs)',
                    prefill: {
                        name: data.name || '',
                        email: data.email || '',
                        contact: data.phone || ''
                    },
                    theme: { color: '#150A42' },
                    handler: function(resp) {
                        fetch(hotelAddonPurchaseUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                additional_tvs: p.extraTvs,
                                razorpay_order_id: resp.razorpay_order_id,
                                razorpay_payment_id: resp.razorpay_payment_id,
                                razorpay_signature: resp.razorpay_signature
                            })
                        }).then(r => r.json()).then(function(pd) {
                            if (pd.success) {
                                window.location.reload();
                            } else {
                                setAddonMsg(pd.message || 'Activation failed.', true);
                                resetAddon();
                            }
                        }).catch(function() {
                            setAddonMsg('Network error during activation.', true);
                            resetAddon();
                        });
                    },
                    modal: {
                        ondismiss: function() {
                            resetAddon();
                        }
                    }
                });

                rzp.on('payment.failed', function(r) {
                    setAddonMsg('Payment failed: ' + (r.error?.description || 'Try again.'), true);
                    resetAddon();
                });

                rzp.open();
            } catch (err) {
                setAddonMsg(err.message, true);
                resetAddon();
            }
        });
    }

    function resetAddon() {
        addonProcessing = false;
        if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
        if (addonBtn) {
            addonBtn.disabled = false;
            addonBtn.classList.remove('opacity-70', 'cursor-wait');
            addonBtn.innerHTML = '<i class="fa-solid fa-lock text-xs mr-1.5"></i><span id="payHotelAddonBtnText">Pay & Add Extra TVs</span>';
        }
    }
    @endif
});
</script>
@endsection
