@extends('layouts.hotel_admin')
@section('title', 'Plan & Billing')
@section('page_title', 'Subscription Plan & Billing')
@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="max-w-5xl mx-auto space-y-6">
    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center space-x-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold flex items-center space-x-2">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Current Active Subscription Details -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
        <div class="border-b border-[#E5E7EB] pb-4 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <h3 class="text-base sm:text-lg font-extrabold text-[#111827] tracking-tight">Active Hotel Subscription</h3>
            </div>
            @if($hotel->payment_status !== 'paid' || !$hotel->plan_id)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">Payment Required (Unpaid)</span>
            @elseif($hotel->expiry_date && $hotel->expiry_date->isPast())
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">Expired</span>
            @elseif($hotel->expiry_date && $hotel->expiry_date->diffInDays(now()) <= 7)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">Expires Soon</span>
            @elseif($hotel->expiry_date)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">Active</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs">No Plan</span>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-1">
            <div class="p-4 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] space-y-1">
                <span class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider block">Current Plan</span>
                <p class="text-sm font-extrabold text-[#111827] truncate">{{ $hotel->plan->name ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] space-y-1">
                <span class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider block">Expiry Date</span>
                <p class="text-sm font-extrabold text-[#111827]">{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] space-y-1">
                <span class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider block">Connected TVs</span>
                <p class="text-sm font-extrabold text-[#111827]">{{ $hotel->room_count }} Displays</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] space-y-1">
                <span class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider block">License Key</span>
                <p class="text-sm font-extrabold text-[#111827] font-mono truncate" title="{{ $hotel->license_key }}">{{ $hotel->license_key ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- TV Add-On Component for Active Subscribers -->
    @if(!empty($hasActivePlan) && !empty($addonPricing))
    <div class="bg-gradient-to-br from-[#150A42] to-[#251068] text-white rounded-3xl p-6 sm:p-8 shadow-lg shadow-[#150A42]/15 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 text-amber-300 flex items-center justify-center text-xl shrink-0 shadow-inner">
                    <i class="fa-solid fa-plus-circle"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-black uppercase font-mono tracking-wider border border-amber-300/30">Prorated Add-On</span>
                        <span class="text-xs text-white/60 font-mono">Plan: {{ $hotel->plan->name }}</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-white tracking-tight mt-0.5">Need More Screens? Instant TV Capacity Add-On</h3>
                    <p class="text-xs text-white/70 font-medium mt-0.5">Add extra TV displays co-termed with your active plan. Only pay for the remaining {{ $remainingDays }} days.</p>
                </div>
            </div>

            <div class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-2xl border border-white/15 shrink-0 font-mono text-xs font-bold">
                <i class="fa-solid fa-clock text-amber-300"></i>
                <span>Remaining: <strong class="text-white">{{ $remainingDays }} Days</strong> (till {{ $hotel->expiry_date->format('M d, Y') }})</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- Left: Stepper & Presets (7 cols) -->
            <div class="lg:col-span-7 space-y-4">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-white/90">Select Additional TV Screens to Add:</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="adjustHotelAddonTvs(-5)" class="w-12 h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:scale-95 text-white font-black text-lg flex items-center justify-center transition-all cursor-pointer border border-white/15">
                            <i class="fa-solid fa-minus text-sm"></i>
                        </button>

                        <div class="relative flex-1 flex items-center bg-white/10 rounded-2xl border-2 border-white/20 focus-within:border-amber-300 transition-all px-3 py-1">
                            <input type="number" 
                                   id="hotelAddonTvInput" 
                                   value="10" 
                                   min="1" 
                                   max="1000" 
                                   inputmode="numeric" 
                                   oninput="calcHotelAddonPricing()"
                                   class="w-full text-center py-2 text-xl font-black text-white bg-transparent border-0 focus:ring-0 focus:outline-none font-mono">
                            <div class="shrink-0 pl-2">
                                <span class="px-2.5 py-1 rounded-xl bg-amber-400 text-[#150A42] text-[11px] font-black uppercase tracking-wider select-none font-mono">
                                    Extra TVs
                                </span>
                            </div>
                        </div>

                        <button type="button" onclick="adjustHotelAddonTvs(5)" class="w-12 h-12 rounded-2xl bg-amber-400 hover:bg-amber-300 active:scale-95 text-[#150A42] font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-lg shadow-amber-400/20">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="text-[11px] font-bold text-white/60">Quick Presets:</span>
                        <button type="button" onclick="setHotelAddonTvs(5)" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-all cursor-pointer active:scale-95 border border-white/10">+5 TVs</button>
                        <button type="button" onclick="setHotelAddonTvs(10)" class="px-3 py-1.5 rounded-xl bg-amber-400/20 hover:bg-amber-400/30 text-amber-300 font-black text-xs transition-all cursor-pointer active:scale-95 border border-amber-300/40">+10 TVs</button>
                        <button type="button" onclick="setHotelAddonTvs(20)" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-all cursor-pointer active:scale-95 border border-white/10">+20 TVs</button>
                        <button type="button" onclick="setHotelAddonTvs(50)" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-all cursor-pointer active:scale-95 border border-white/10">+50 TVs</button>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between text-xs">
                    <span class="text-white/80">Current Licensed Displays: <strong class="text-white">{{ (int)$hotel->room_count }} TVs</strong></span>
                    <span class="text-amber-300 font-bold font-mono">➔ New Capacity: <strong id="hotelAddonNewTotalText">{{ (int)$hotel->room_count + 10 }} TVs</strong></span>
                </div>
            </div>

            <!-- Right: Cost Summary & Instant Checkout Button (5 cols) -->
            <div class="lg:col-span-5 bg-white/10 border border-white/15 rounded-2xl p-5 space-y-3.5">
                <div class="space-y-1.5 text-xs text-white/80">
                    <div class="flex justify-between">
                        <span>Prorated Subtotal ({{ $remainingDays }} days):</span>
                        <span class="font-mono font-bold text-white" id="hotelAddonSubtotalText">₹{{ number_format($addonPricing['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-white/60">
                        <span>GST ({{ $addonPricing['tax_rate'] }}%):</span>
                        <span class="font-mono" id="hotelAddonTaxText">₹{{ number_format($addonPricing['tax_amount'], 2) }}</span>
                    </div>
                    <div class="pt-2 border-t border-white/15 flex justify-between items-baseline">
                        <span class="font-bold text-white text-sm">Total Add-On:</span>
                        <span class="text-2xl font-black font-mono text-amber-300" id="hotelAddonTotalText">₹{{ number_format($addonPricing['total_amount'], 2) }}</span>
                    </div>
                </div>

                <button type="button" id="payHotelAddonBtn" class="w-full py-3 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 active:scale-98 text-[#150A42] font-black text-sm transition-all shadow-lg shadow-amber-400/25 cursor-pointer flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span id="payHotelAddonBtnText">Pay & Add Extra TVs</span>
                </button>

                <div id="hotelAddonMsg" class="hidden text-center text-xs font-bold py-1"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Purchase / Upgrade / Renew Flow -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-[#E5E7EB] pb-4">
            <h3 class="text-lg font-extrabold text-[#111827] tracking-tight">Per-TV Subscription Checkout</h3>
            <p class="text-xs text-[#6B7280] font-medium mt-1">Select the number of Connected TVs in your property and choose your preferred billing cycle.</p>
        </div>

        <!-- Step 1: Number of TVs in Hotel -->
        <div class="p-5 sm:p-6 rounded-2xl bg-[#F8F9FD] border border-slate-200/90 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-[11px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 1 • Hardware Licensing</span>
                    <h4 class="text-base font-extrabold text-slate-900 mt-0.5">How many Connected TVs need licensing?</h4>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full bg-purple-100 text-[#150A42] text-xs font-mono font-black border border-purple-200/80 shadow-2xs">
                        Selected: <strong id="tvCountDisplay" class="text-purple-900">{{ max(1, $hotel->room_count ?: 30) }}</strong> TVs
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 pt-1">
                <div class="flex items-center space-x-2.5">
                    <button type="button" onclick="adjustHotelTvs(-5)" class="w-11 h-11 rounded-xl bg-white border border-slate-200 hover:border-purple-300 hover:bg-slate-50 active:scale-95 flex items-center justify-center font-bold text-sm transition-all shadow-xs cursor-pointer text-slate-700">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>
                    <div class="relative">
                        <input type="number" 
                               id="tvCountInput" 
                               min="1" 
                               max="1000" 
                               value="{{ max(1, $hotel->room_count ?: 30) }}" 
                               oninput="calcDynamicPricing()" 
                               class="w-32 text-center py-2.5 px-3 bg-white text-[#150A42] font-mono font-black text-xl rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 shadow-inner">
                    </div>
                    <button type="button" onclick="adjustHotelTvs(5)" class="w-11 h-11 rounded-xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 flex items-center justify-center font-bold text-sm transition-all shadow-md shadow-[#150A42]/20 cursor-pointer text-white">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[11px] text-slate-400 font-black uppercase tracking-wider">Presets:</span>
                    <button type="button" onclick="setHotelTvs(15)" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-[#150A42] text-xs font-black font-mono text-slate-800 transition-all active:scale-95 shadow-2xs">15 TVs</button>
                    <button type="button" onclick="setHotelTvs(30)" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-[#150A42] text-xs font-black font-mono text-slate-800 transition-all active:scale-95 shadow-2xs">30 TVs</button>
                    <button type="button" onclick="setHotelTvs(50)" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-[#150A42] text-xs font-black font-mono text-slate-800 transition-all active:scale-95 shadow-2xs">50 TVs</button>
                    <button type="button" onclick="setHotelTvs(100)" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-[#150A42] text-xs font-black font-mono text-slate-800 transition-all active:scale-95 shadow-2xs">100 TVs</button>
                    @if($hotel->room_count)
                        <button type="button" onclick="setHotelTvs({{ (int)$hotel->room_count }})" class="px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-200 hover:bg-purple-100 text-xs font-black font-mono text-purple-900 transition-all active:scale-95 shadow-2xs">
                            <i class="fa-solid fa-rotate-left mr-1 text-[10px]"></i> Current ({{ (int)$hotel->room_count }})
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step 2: Select Plan Tier -->
        <div class="space-y-3">
            <span class="text-[11px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 2 • Choose Plan Tier</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($plans as $plan)
                    @php
                        $rate = (float) $plan->effective_price_per_tv;
                        $tax = $plan->tax;
                        $taxRate = $tax ? $tax->rate : 0;
                        $taxType = $plan->tax_type ?? 'exclusive';
                        $cycle = strtolower($plan->billing_cycle ?? 'monthly');
                        $isCurrentPlan = ($hotel->plan_id == $plan->id);
                        $isPreSelected = ($hotel->plan_id == $plan->id || $loop->first);
                    @endphp
                    <label class="plan-tier-card relative flex flex-col justify-between p-5 sm:p-6 rounded-2xl border-2 cursor-pointer transition-all {{ $isPreSelected ? 'border-[#150A42] bg-purple-50/40 ring-2 ring-purple-500/20 shadow-xs' : 'border-slate-200/90 hover:border-slate-300 bg-white' }}">
                        <div>
                            <div class="flex items-center justify-between mb-3.5">
                                @if($cycle === 'yearly')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-[#150A42] text-white text-[10px] font-black uppercase font-mono tracking-wider shadow-2xs">
                                        <i class="fa-solid fa-crown text-[9px] text-purple-300 mr-1.5"></i> Annual Pack
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-purple-100 text-[#150A42] text-[10px] font-black uppercase font-mono tracking-wider">
                                        <i class="fa-solid fa-calendar-days text-[9px] text-purple-600 mr-1.5"></i> Monthly Pack
                                    </span>
                                @endif

                                <input type="radio" 
                                       name="plan_id" 
                                       value="{{ $plan->id }}" 
                                       data-rate="{{ $rate }}" 
                                       data-cycle="{{ $cycle }}"
                                       data-tax-rate="{{ $taxRate }}"
                                       data-tax-name="{{ $tax ? $tax->name : 'GST' }}"
                                       data-tax-type="{{ $taxType }}"
                                       data-is-split="{{ ($tax && $tax->is_split) ? '1' : '0' }}"
                                       data-name="{{ $plan->name }}" 
                                       class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-[#150A42]" 
                                       {{ $isPreSelected ? 'checked' : '' }}>
                            </div>

                            <div>
                                <div class="font-black text-slate-900 text-base">{{ $plan->name }}</div>
                                <div class="text-2xl font-black text-[#150A42] mt-1 font-mono">
                                    ₹{{ number_format($rate, 2) }} 
                                    <span class="text-xs text-slate-400 font-sans font-medium">/ TV / {{ $cycle === 'yearly' ? 'year' : 'month' }}</span>
                                </div>

                                <!-- Tax Pill -->
                                <div class="mt-2.5">
                                    @if($taxRate > 0)
                                        <span class="inline-flex items-center text-[10px] font-bold font-mono px-2.5 py-1 rounded-lg {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-purple-50 text-purple-800 border border-purple-200/80' }}">
                                            {{ $tax->name }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                        </span>
                                    @else
                                        <span class="inline-block text-[10px] font-mono px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">
                                            No Tax Applied
                                        </span>
                                    @endif
                                </div>

                                @if($isCurrentPlan)
                                    <span class="inline-flex items-center mt-2.5 text-[10px] font-black text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full shadow-2xs">
                                        <i class="fa-solid fa-circle-check text-[9px] mr-1.5 text-emerald-600"></i> Current Active Plan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-3.5 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-600 font-semibold">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                                <span>Complete TV Launcher & Themes</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                                <span>Hotel CMS & Remote Controls</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Step 3: Duration & Invoice Summary Checkout -->
        <div class="p-6 rounded-2xl bg-[#F8FAFC] border border-slate-200/90 space-y-5">
            <div class="border-b border-slate-200/80 pb-3 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase font-mono text-purple-700 tracking-wider block">Step 3 • Settlement Summary</span>
                    <h4 class="text-base font-extrabold text-slate-900 mt-0.5">Duration & Transparent Invoice Breakdown</h4>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Duration Selector (5 cols) -->
                <div class="lg:col-span-5 space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-black text-slate-800" id="durationSelectLabel">
                                License Validity Period
                            </label>
                            <span id="durationCycleBadge" class="px-2.5 py-0.5 rounded-full bg-purple-100 text-[#150A42] text-[10px] font-black uppercase font-mono tracking-wider">
                                Monthly Billing
                            </span>
                        </div>

                        <div class="relative">
                            <select id="durationSelect" 
                                    onchange="calcDynamicPricing()" 
                                    class="w-full pl-10 pr-10 py-3 text-xs sm:text-sm font-bold rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-xs hover:border-purple-300 focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 focus:border-[#150A42] appearance-none cursor-pointer">
                                <option value="1">1 Month (Monthly Pack)</option>
                                <option value="3">3 Months (Quarterly Pack)</option>
                                <option value="6">6 Months (Semi-Annual Pack)</option>
                                <option value="12">12 Months (1 Year - Annual Pack)</option>
                                <option value="24">24 Months (2-Year Pack)</option>
                                <option value="36">36 Months (3-Year Pack)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-purple-600">
                                <i class="fa-solid fa-calendar-days text-sm"></i>
                            </div>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-purple-50/70 border border-purple-100 text-[11px] text-purple-900 font-medium space-y-1">
                        <div class="flex items-center space-x-1.5 font-bold">
                            <i class="fa-solid fa-shield-halved text-purple-700"></i>
                            <span>Instant Auto-Activation Guarantee</span>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Upon online settlement, TV licenses and cloud sync are activated immediately with zero downtime.
                        </p>
                    </div>
                </div>

                <!-- Live Itemized Invoice Breakdown (7 cols) -->
                <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3.5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Order Target</span>
                            <span id="summaryPlanName" class="text-sm font-extrabold text-slate-900">Plan</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Screens</span>
                            <span id="summaryTvCount" class="text-sm font-extrabold text-purple-900 font-mono">15 Smart TVs</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Licensed Unit Rate:</span>
                            <span id="summaryRateDisplay" class="font-mono font-bold text-slate-800">₹150.00 / TV / month</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span>Validity Duration:</span>
                            <span id="summaryDurationText" class="font-mono font-bold text-purple-900">1 Month</span>
                        </div>

                        <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                            <div>
                                <span class="block text-slate-700 font-bold">Base Subtotal:</span>
                                <span id="summaryFormula" class="text-[11px] text-purple-700 font-mono font-bold block">15 TVs × ₹150.00 × 1 mo</span>
                            </div>
                            <span id="summarySubtotal" class="font-mono font-black text-slate-900 text-sm">₹2,250.00</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span id="summaryTaxName">Applicable GST / Tax:</span>
                            <span id="summaryTaxAmount" class="font-mono font-bold text-slate-900">+₹405.00</span>
                        </div>

                        <!-- CGST / SGST breakdown rows -->
                        <div id="cgstRow" class="hidden items-center justify-between text-[11px] text-slate-500 pl-3">
                            <span id="summaryCgstLabel">CGST (9%):</span>
                            <span id="summaryCgstAmount" class="font-mono font-bold text-slate-700">₹202.50</span>
                        </div>
                        <div id="sgstRow" class="hidden items-center justify-between text-[11px] text-slate-500 pl-3">
                            <span id="summarySgstLabel">SGST (9%):</span>
                            <span id="summarySgstAmount" class="font-mono font-bold text-slate-700">₹202.50</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t-2 border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-tight block">Total Payable</span>
                            <span class="text-[10px] text-slate-400 font-medium">Inclusive of all taxes & fees</span>
                        </div>
                        <div id="totalBox" class="text-2xl sm:text-3xl font-black text-[#150A42] font-mono tracking-tight">₹2,655.00</div>
                    </div>

                    <div class="pt-2">
                        <button id="payBtn" type="button" class="w-full py-3.5 px-6 rounded-xl bg-[#150A42] hover:bg-[#1f105e] active:scale-98 text-white font-extrabold text-sm transition-all shadow-md shadow-[#150A42]/25 cursor-pointer flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-lock text-purple-300"></i>
                            <span id="payBtnLabel">Pay Securely <span id="payBtnAmount">₹2,655.00</span> & Activate</span>
                        </button>
                        <p id="payMsg" class="text-xs text-center mt-2 hidden"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tax Invoices & Billing History -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-file-invoice-dollar text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Recent Tax Invoices & Receipts</h3>
                    <p class="text-xs text-slate-400">Download or print official GST tax receipts for your subscription payments.</p>
                </div>
            </div>
            <a href="{{ route('hotel.invoices.index') }}" class="text-xs font-bold text-purple-700 hover:text-purple-950 flex items-center space-x-1">
                <span>View All Invoices</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        @if(!empty($recentInvoices) && $recentInvoices->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 font-black uppercase tracking-wider text-[10px] font-mono border-b border-slate-100">
                            <th class="py-3 px-3">Invoice #</th>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3">Plan & Screens</th>
                            <th class="py-3 px-3">Total Amount</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentInvoices as $rInv)
                            <tr class="hover:bg-purple-50/20 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-900">{{ $rInv->invoice_number }}</td>
                                <td class="py-3.5 px-3 text-slate-400">{{ $rInv->created_at->format('d M, Y') }}</td>
                                <td class="py-3.5 px-3 font-bold text-slate-900">{{ $rInv->plan_name }} ({{ $rInv->tv_count }} TVs)</td>
                                <td class="py-3.5 px-3 font-mono font-black text-slate-900">₹{{ number_format($rInv->total_amount, 2) }}</td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <i class="fa-solid fa-circle-check text-[9px] mr-1"></i> Paid
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <a href="{{ route('hotel.invoices.show', $rInv->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#150A42] text-white hover:bg-[#1f105e] text-xs font-bold shadow-xs transition-all active:scale-95">
                                        <i class="fa-solid fa-print text-[10px] text-purple-300 mr-1.5"></i>
                                        <span>Print</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-6 text-slate-400 text-xs">
                No past invoices yet. Once a subscription or TV add-on payment is confirmed, official receipts will be archived here.
            </div>
        @endif
    </div>
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
                ? 'px-2.5 py-0.5 rounded-full bg-[#150A42] text-white text-[10px] font-black uppercase font-mono tracking-wider'
                : 'px-2.5 py-0.5 rounded-full bg-purple-100 text-[#150A42] text-[10px] font-black uppercase font-mono tracking-wider';
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
            durationSelect.innerHTML = `
                <option value="1">1 Month (Monthly Pack)</option>
                <option value="3">3 Months (Quarterly Pack)</option>
                <option value="6">6 Months (Semi-Annual Pack)</option>
                <option value="12">12 Months (1 Year - Annual Pack)</option>
                <option value="24">24 Months (2-Year Pack)</option>
                <option value="36">36 Months (3-Year Pack)</option>
            `;
            durationSelect.value = [1, 3, 6, 12, 24, 36].includes(currentVal) ? String(currentVal) : '1';
        }
    }

    window.calcDynamicPricing = function() {
        let sel = document.querySelector('.plan-radio:checked');
        if (!sel && planRadios.length > 0) { planRadios[0].checked = true; sel = planRadios[0]; }
        if (!sel) return { amount: 0, planId: null, months: 1, tvCount: 1 };

        // Visual highlight on cards
        document.querySelectorAll('.plan-tier-card').forEach(card => {
            const rad = card.querySelector('.plan-radio');
            if (rad && rad.checked) {
                card.className = 'plan-tier-card relative flex flex-col justify-between p-5 sm:p-6 rounded-2xl border-2 cursor-pointer transition-all border-[#150A42] bg-purple-50/40 ring-2 ring-purple-500/20 shadow-xs';
            } else {
                card.className = 'plan-tier-card relative flex flex-col justify-between p-5 sm:p-6 rounded-2xl border-2 cursor-pointer transition-all border-slate-200/90 hover:border-slate-300 bg-white';
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

        const summaryRateDisplay = document.getElementById('summaryRateDisplay');
        if (summaryRateDisplay) {
            summaryRateDisplay.textContent = `₹${rate.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} / TV / ${isYearly ? 'year' : 'month'}`;
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

    // Initial run
    syncDurationOptions();
    calcDynamicPricing();

    function setMsg(text, isError) {
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
        const newTotal = hotelCurrentTvs + extraTvs;

        const newTotalEl = document.getElementById('hotelAddonNewTotalText');
        if (newTotalEl) newTotalEl.innerText = newTotal + ' TVs';

        const rawSubtotal = extraTvs * hotelDailyRate * hotelRemainingDays;
        let subtotal = Math.round(rawSubtotal * 100) / 100;
        let tax = 0;
        let total = subtotal;

        if (hotelTaxRate > 0) {
            if (hotelIsInclusive) {
                total = subtotal;
                subtotal = Math.round((total / (1 + (hotelTaxRate / 100))) * 100) / 100;
                tax = Math.round((total - subtotal) * 100) / 100;
            } else {
                tax = Math.round((subtotal * (hotelTaxRate / 100)) * 100) / 100;
                total = Math.round((subtotal + tax) * 100) / 100;
            }
        }

        const subEl = document.getElementById('hotelAddonSubtotalText');
        const taxEl = document.getElementById('hotelAddonTaxText');
        const totEl = document.getElementById('hotelAddonTotalText');

        if (subEl) subEl.innerText = '₹' + subtotal.toFixed(2);
        if (taxEl) taxEl.innerText = '₹' + tax.toFixed(2);
        if (totEl) totEl.innerText = '₹' + total.toFixed(2);
    };

    const payAddonBtn = document.getElementById('payHotelAddonBtn');
    const addonMsg = document.getElementById('hotelAddonMsg');
    let addonProcessing = false;

    function setAddonMsg(txt, isErr) {
        if (!addonMsg) return;
        addonMsg.innerText = txt;
        addonMsg.className = 'text-center text-xs font-bold py-1 ' + (isErr ? 'text-rose-300' : 'text-amber-300');
        addonMsg.classList.remove('hidden');
    }

    if (payAddonBtn) {
        payAddonBtn.addEventListener('click', async function() {
            if (addonProcessing) return;
            addonProcessing = true;
            payAddonBtn.disabled = true;
            payAddonBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Initializing Gateway...';
            setAddonMsg('Securing payment order...', false);

            const extraTvs = Math.max(1, parseInt(document.getElementById('hotelAddonTvInput').value) || 1);

            try {
                const res = await fetch(hotelAddonOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ additional_tvs: extraTvs })
                });

                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Could not initiate add-on order.');

                if (data.gateway === 'payu' || (data.payu_params && data.checkout_url)) {
                    setAddonMsg('Redirecting to PayU...', false);
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

                if (typeof Razorpay === 'undefined') throw new Error('Razorpay failed to load. Please refresh.');
                const rzp = new Razorpay({
                    key: data.key_id,
                    order_id: data.order_id,
                    amount: Math.round(parseFloat(data.amount) * 100),
                    currency: data.currency || 'INR',
                    name: 'PaxTV Hospitality',
                    description: 'TV Screen Add-On (+' + extraTvs + ' TVs)',
                    prefill: { name: data.name || '', email: data.email || '', contact: data.phone || '' },
                    theme: { color: '#150A42' },
                    handler: function(resp) {
                        setAddonMsg('Verifying payment and unlocking screens...', false);
                        fetch(hotelAddonPurchaseUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                additional_tvs: extraTvs,
                                razorpay_order_id: resp.razorpay_order_id,
                                razorpay_payment_id: resp.razorpay_payment_id,
                                razorpay_signature: resp.razorpay_signature
                            })
                        }).then(r => r.json()).then(function(pd) {
                            if (pd.success) {
                                window.location.reload();
                            } else {
                                setAddonMsg(pd.message || 'Add-on activation failed.', true);
                                resetAddon();
                            }
                        }).catch(function() {
                            setAddonMsg('Network error during activation.', true);
                            resetAddon();
                        });
                    },
                    modal: { ondismiss: function() { resetAddon(); } }
                });

                rzp.on('payment.failed', function(r) {
                    setAddonMsg('Payment failed: ' + (r.error?.description || 'Try again.'), true);
                    resetAddon();
                });
                rzp.open();
            } catch (e) {
                setAddonMsg(e.message, true);
                resetAddon();
            }
        });

        function resetAddon() {
            addonProcessing = false;
            payAddonBtn.disabled = false;
            payAddonBtn.innerHTML = '<i class="fa-solid fa-lock text-xs mr-1.5"></i> Pay & Add Extra TVs';
        }
    }
    @endif
});
</script>
@endsection
