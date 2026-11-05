@extends('layouts.super_admin')

@section('title', 'Review & Settle Subscription Renewal - ' . $hotel->hotel_name)
@section('page_title', 'Subscription Renewal & Checkout')

@section('content')
<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none !important;
        appearance: none !important;
        margin: 0 !important;
        display: none !important;
    }
    input[type="number"] {
        -moz-appearance: textfield !important;
        appearance: textfield !important;
    }
</style>

<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Breadcrumb & Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.hotels.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Hotels Directory</span>
                </a>
                <span>/</span>
                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="hover:text-[#150A42] transition-colors">
                    {{ $hotel->hotel_name }}
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Renewal Checkout</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Review & Settle Subscription Renewal</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Itemized TV device licensing, duration term, statutory GST assessment, and verified gateway settlement.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.invoices.index') }}" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-file-invoice-dollar text-xs text-purple-600"></i>
                <span>Tax Invoices</span>
            </a>
            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-5 rounded-[24px] bg-rose-50/90 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-2xs">
            <div class="font-black flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-4 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super-admin.hotels.pay', $hotel->id) }}" id="renewalForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        @csrf

        <!-- Left / Main Column (7 cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- 1. Billed Hotel Property Card -->
            <div class="bg-white border border-slate-100 rounded-[28px] p-5 sm:p-6 shadow-sm space-y-4 relative overflow-hidden">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg shrink-0 shadow-2xs overflow-hidden">
                            @if($hotel->hotel_logo)
                                <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-hotel"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Billed Hotel Property</span>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 truncate">
                                {{ $hotel->hotel_name }}
                            </h3>
                            <p class="text-xs text-slate-400 font-semibold truncate flex items-center mt-0.5">
                                <i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[10px]"></i>
                                <span>{{ $hotel->city ?? $hotel->hotel_location ?? 'Not specified' }}</span>
                            </p>
                        </div>
                    </div>

                    <div>
                        @php
                            $isExpired = !$hotel->expiry_date || \Carbon\Carbon::parse($hotel->expiry_date)->isPast();
                        @endphp
                        @if($isExpired)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse mr-1.5"></span> Subscription Expired
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span> Active until {{ \Carbon\Carbon::parse($hotel->expiry_date)->format('d M, Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-1">
                    <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider block">Owner Contact</span>
                        <div class="font-bold text-slate-800 truncate mt-0.5">{{ $hotel->owner_name }}</div>
                        <div class="text-slate-500 font-mono text-[11px]">{{ $hotel->phone }}</div>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider block">Channel Partner</span>
                        <div class="font-bold text-slate-800 truncate mt-0.5">
                            {{ $hotel->distributor ? $hotel->distributor->name : 'Direct Enterprise' }}
                        </div>
                        <div class="text-slate-400 text-[11px]">Pax TV Hospitality Network</div>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider block">Hardware License</span>
                        <div class="font-bold text-purple-700 font-mono text-xs mt-0.5">{{ $tvCount }} Allowed Displays</div>
                        <div class="text-slate-400 text-[11px]">{{ $hotel->connectedDevices->count() ?? 0 }} Paired Active TVs</div>
                    </div>
                </div>
            </div>

            <!-- 2. Subscription Plan Selection -->
            <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Select Subscription Tier</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Choose the Smart TV license package for this hotel property.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($allPlans as $p)
                        @php
                            $isSelected = ($p->id == $hotel->plan_id);
                            $effTax = $p->tax;
                            $tRate = $effTax ? (float) $effTax->rate : 0.0;
                            $tName = $effTax ? $effTax->name : 'GST';
                            $tType = $p->tax_type ?? 'exclusive';
                            $pCycle = strtolower($p->billing_cycle ?? 'monthly');
                            $uRate = (float) $p->effective_price_per_tv;
                        @endphp
                        <label class="plan-card relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all {{ $isSelected ? 'border-[#150A42] bg-purple-50/30 shadow-xs' : 'border-slate-200/90 hover:border-slate-300 bg-white' }}">
                            <div>
                                <div class="flex items-start justify-between">
                                    <div class="w-8 h-8 rounded-xl bg-purple-100/70 text-[#150A42] flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-tv"></i>
                                    </div>
                                    <input type="radio" 
                                           name="plan_id" 
                                           value="{{ $p->id }}" 
                                           {{ $isSelected ? 'checked' : '' }}
                                           data-name="{{ $p->name }}"
                                           data-unit-rate="{{ $uRate }}"
                                           data-billing-cycle="{{ $pCycle }}"
                                           data-tax-name="{{ $tName }}"
                                           data-tax-rate="{{ $tRate }}"
                                           data-tax-type="{{ $tType }}"
                                           data-is-split="{{ ($effTax && $effTax->is_split) ? '1' : '0' }}"
                                           class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                                </div>

                                <div class="font-black text-slate-900 text-sm sm:text-base mt-2">{{ $p->name }}</div>
                                <div class="text-xl font-black text-[#150A42] font-mono mt-1">
                                    ₹{{ number_format($uRate, 2) }}
                                    <span class="text-[11px] text-slate-400 font-sans font-medium">/ TV / {{ $pCycle === 'yearly' ? 'year' : 'month' }}</span>
                                </div>

                                <div class="mt-2 flex items-center space-x-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                        {{ $tName }} ({{ number_format($tRate, 0) }}% {{ ucfirst($tType) }})
                                    </span>
                                </div>

                                @if($p->description)
                                    <p class="text-[11px] text-slate-400 mt-2 font-medium line-clamp-2">
                                        {{ $p->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[10px] text-slate-600 font-semibold">
                                <div class="flex items-center space-x-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                                    <span>Full In-Room TV Launcher & Themes</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-[11px]"></i>
                                    <span>Cloud Remote Broadcast & Updates</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 3. Authorized TV Hardware Slots (Matching Distributor Stepper & Quick Chips) -->
            <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-tv"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Number of Authorized Smart TVs</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                Pre-filled with hotel's existing <strong class="text-slate-700 font-black">{{ $tvCount }} TVs</strong> license. You can increase device slots below.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-black uppercase font-mono border border-emerald-200/80 flex items-center shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1.5"></span>
                            Active: <span class="ml-1 font-bold">{{ $tvCount }}</span> TVs
                        </span>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-black text-slate-700">
                            Authorized TV Hardware Slots <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-purple-700 font-bold font-mono">
                            Base Quota: <span>{{ $tvCount }}</span> TVs
                        </span>
                    </div>

                    <!-- Modern Stepper Input with - and + and Screens badge -->
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="adjustTvCount(-1)" 
                                class="w-12 h-12 rounded-2xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-2xs shrink-0">
                            <i class="fa-solid fa-minus text-sm"></i>
                        </button>

                        <div class="relative flex-1 flex items-center bg-white rounded-2xl border-2 border-slate-200 focus-within:border-[#150A42] focus-within:ring-4 focus-within:ring-purple-500/10 transition-all px-3 py-1 shadow-2xs">
                            <input type="text" 
                                   name="tv_count" 
                                   id="tvCountInput" 
                                   value="{{ $tvCount }}" 
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   autocomplete="off"
                                   required 
                                   class="w-full text-center py-2 text-xl font-black text-slate-900 border-0 focus:ring-0 focus:outline-none font-mono">
                            <div class="shrink-0 pl-2">
                                <span class="px-2.5 py-1 rounded-xl bg-purple-50 border border-purple-200/70 text-[11px] font-black text-[#150A42] uppercase tracking-wider select-none font-mono flex items-center gap-1">
                                    <i class="fa-solid fa-display text-[10px] text-purple-600"></i>
                                    <span>Screens</span>
                                </span>
                            </div>
                        </div>

                        <button type="button" 
                                onclick="adjustTvCount(1)" 
                                class="w-12 h-12 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 text-white font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-md shadow-[#150A42]/15 shrink-0">
                            <i class="fa-solid fa-plus text-sm text-amber-300"></i>
                        </button>
                    </div>

                    <!-- Quick TV Expansion Chips -->
                    <div class="space-y-1.5 pt-1">
                        <span class="text-[11px] font-bold text-slate-400 block">Quick TV Count Selection:</span>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" onclick="setTvCount({{ $tvCount }})" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-xs border border-purple-200/80 transition-all cursor-pointer active:scale-95 shadow-2xs">
                                <i class="fa-solid fa-rotate-left mr-1 text-[10px]"></i> Current ({{ $tvCount }} TVs)
                            </button>
                            <button type="button" onclick="setTvCount({{ $tvCount + 5 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                +5 TVs ({{ $tvCount + 5 }})
                            </button>
                            <button type="button" onclick="setTvCount({{ $tvCount + 10 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                +10 TVs ({{ $tvCount + 10 }})
                            </button>
                            <button type="button" onclick="setTvCount({{ $tvCount + 20 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                +20 TVs ({{ $tvCount + 20 }})
                            </button>
                            <button type="button" onclick="setTvCount({{ $tvCount + 50 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                +50 TVs ({{ $tvCount + 50 }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Duration & Payment Settlement -->
            <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Duration & Payment Settlement</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Select validity term and verified payment gateway.</p>
                    </div>
                </div>

                <div class="relative">
                    <label class="block text-xs font-black text-slate-700 mb-1.5">
                        <span id="durationSelectLabel">License Validity Period</span> <span class="text-rose-500">*</span>
                    </label>

                    <!-- Hidden native select for form submit and script access -->
                    <select name="duration_months" id="durationSelect" required class="sr-only">
                        <option value="1">1 Month (Monthly Pack)</option>
                        <option value="3">3 Months (Quarterly Pack)</option>
                        <option value="6">6 Months (Semi-Annual Pack)</option>
                        <option value="12" selected>12 Months (1 Year - Annual Pack)</option>
                        <option value="24">24 Months (2-Year Pack)</option>
                        <option value="36">36 Months (3-Year Pack)</option>
                    </select>

                    <!-- Custom Duration Trigger Button -->
                    <button type="button" 
                            id="durationCustomTriggerBtn" 
                            onclick="toggleDurationCustomDropdown(event)" 
                            class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-purple-300 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] font-semibold text-slate-800 transition-all flex items-center justify-between shadow-xs cursor-pointer group">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 text-[#150A42] flex items-center justify-center text-xs shrink-0 font-bold">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <span id="durationCustomTriggerLabel" class="block font-black text-slate-900 truncate text-xs sm:text-sm">
                                12 Months (1 Year - Annual Pack)
                            </span>
                        </div>
                        <i id="durationCustomChevron" class="fa-solid fa-chevron-down text-slate-400 group-hover:text-purple-700 text-xs transition-transform duration-200 ml-2"></i>
                    </button>

                    <!-- Floating Custom Dropdown Menu -->
                    <div id="durationCustomMenu" 
                         class="hidden absolute z-50 left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200/90 shadow-2xl p-1.5 max-h-60 overflow-y-auto space-y-1 backdrop-blur-md">
                    </div>
                </div>

                <!-- Payment Verification Channel (Matching Distributor Layout) -->
                <div class="pt-2">
                    <input type="hidden" name="payment_method" id="paymentMethod" value="online_gateway">
                    <label class="block text-xs font-black text-slate-700 mb-2">Payment Verification Channel <span class="text-rose-500">*</span></label>
                    
                    @if($activeGateway && $activeGateway->is_enabled)
                        <div class="p-4 sm:p-5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black shrink-0 shadow-2xs">
                                    @if($activeGateway->code === 'razorpay')
                                        <i class="fa-solid fa-bolt text-blue-600 text-lg"></i>
                                    @elseif($activeGateway->code === 'icici')
                                        <i class="fa-solid fa-building-columns text-amber-700 text-lg"></i>
                                    @else
                                        <i class="fa-solid fa-credit-card text-emerald-700 text-lg"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-xs font-black text-slate-900">{{ $activeGateway->name }}</h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Live Gateway
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Automated server-side verification with instant license renewal and statutory GST tax invoice.</p>
                                </div>
                            </div>
                            <div class="text-xs font-bold text-emerald-700 shrink-0 flex items-center space-x-1">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Verified Gateway</span>
                            </div>
                        </div>
                    @else
                        <div class="p-4 sm:p-5 rounded-2xl border-2 border-rose-300 bg-rose-50 flex items-center space-x-3 text-rose-800">
                            <i class="fa-solid fa-triangle-exclamation text-xl shrink-0"></i>
                            <div class="text-xs">
                                <p class="font-black">No Active Payment Gateway</p>
                                <p class="text-[11px] text-rose-700 mt-0.5">Please enable an online payment gateway from Gateway Settings to process automated settlements.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>

        <!-- Right Column: Tax Assessment & Settlement (5 cols, sticky) -->
        <div class="lg:col-span-5 space-y-6 sticky top-24">
            <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200/60 shadow-2xs font-mono">
                            Tax Invoice Assessment
                        </span>
                        <span class="text-[11px] font-bold text-emerald-600 flex items-center">
                            <i class="fa-solid fa-shield-check mr-1"></i> GST Compliant
                        </span>
                    </div>
                    <div class="text-xl font-black text-slate-900 mt-2 tracking-tight">Order Breakdown & Total</div>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Itemized TV licensing cost and statutory taxes.</p>
                </div>

                <!-- Itemized Specs Table -->
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Billed Hotel:</span>
                        <span class="font-black text-slate-900 truncate max-w-[200px]">
                            {{ $hotel->hotel_name }}
                        </span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Subscription Tier:</span>
                        <span id="summaryPlanName" class="font-black text-slate-900">{{ $hotel->plan->name }}</span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Connected TVs:</span>
                        <span id="summaryTvCount" class="font-black font-mono text-purple-700">{{ $tvCount }} Smart TVs</span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Rate per TV:</span>
                        <span id="summaryUnitRate" class="font-black font-mono text-slate-900">
                            ₹{{ number_format($hotel->plan->effective_price_per_tv, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Billing Duration:</span>
                        <span id="summaryDuration" class="font-black text-slate-900">12 Months (1 Year)</span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-100 bg-slate-50/70 p-2.5 rounded-xl">
                        <div>
                            <span class="text-slate-600 font-bold block">Base Subtotal:</span>
                            <span id="summaryFormula" class="text-[10px] text-slate-400 font-mono block">
                                {{ $tvCount }} TVs × ₹{{ number_format($hotel->plan->effective_price_per_tv, 2) }}
                            </span>
                        </div>
                        <span id="summarySubtotal" class="font-black font-mono text-slate-900 self-center text-sm">
                            ₹{{ number_format($pricing['subtotal'], 2) }}
                        </span>
                    </div>

                    <!-- Tax Breakdown Rows -->
                    <div id="taxBreakdownBox" class="space-y-1.5 pt-1">
                        <div class="flex justify-between text-slate-600">
                            <span id="summaryTaxLabel">GST (18% Exclusive):</span>
                            <span id="summaryTaxAmount" class="font-mono font-bold text-slate-900">+ ₹0.00</span>
                        </div>
                        <div id="cgstRow" class="flex justify-between text-[11px] text-slate-400 pl-3">
                            <span id="summaryCgstLabel">CGST (9%):</span>
                            <span id="summaryCgstAmount" class="font-mono">₹0.00</span>
                        </div>
                        <div id="sgstRow" class="flex justify-between text-[11px] text-slate-400 pl-3">
                            <span id="summarySgstLabel">SGST (9%):</span>
                            <span id="summarySgstAmount" class="font-mono">₹0.00</span>
                        </div>
                    </div>

                    <!-- Total Payable Line -->
                    <div class="pt-3 border-t-2 border-slate-900 flex justify-between items-center text-slate-900">
                        <div>
                            <span class="text-sm font-black block">Total Payable:</span>
                            <span class="text-[10px] text-slate-400 block font-medium">All taxes & TV pairing licenses included</span>
                        </div>
                        <span id="summaryTotal" class="font-black font-mono text-[#150A42] text-2xl tracking-tight">
                            ₹{{ number_format($pricing['total_amount'], 2) }}
                        </span>
                    </div>
                </div>

                <!-- Automatic Invoice Guarantee Box -->
                <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/60 text-[11px] text-purple-950 space-y-1.5">
                    <div class="font-black flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-invoice text-purple-600"></i>
                        <span>Automated Tax Invoice Generation</span>
                    </div>
                    <p class="text-purple-900/80 text-[10px] leading-relaxed font-medium">Upon verified payment, a formal tax-compliant invoice is immediately issued with serial numbering and downloadable under Tax Invoices.</p>
                </div>

                <button type="submit" 
                        id="submitBtn" 
                        class="w-full py-4 px-4 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-lock text-xs text-amber-300"></i>
                    <span id="submitBtnLabel">Confirm & Settle Renewal ₹{{ number_format($pricing['total_amount'], 2) }}</span>
                </button>

                <p class="text-[10px] text-slate-400 text-center leading-relaxed font-medium">
                    <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i>
                    Payments processed securely via {{ $activeGateway ? $activeGateway->name : 'Payment Gateway' }}.
                </p>
            </div>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRadios = document.querySelectorAll('.plan-radio');
    const durationSelect = document.getElementById('durationSelect');
    const tvCountInput = document.getElementById('tvCountInput');

    const summaryPlanName = document.getElementById('summaryPlanName');
    const summaryTvCount = document.getElementById('summaryTvCount');
    const summaryUnitRate = document.getElementById('summaryUnitRate');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryFormula = document.getElementById('summaryFormula');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryTaxLabel = document.getElementById('summaryTaxLabel');
    const summaryTaxAmount = document.getElementById('summaryTaxAmount');
    const summaryCgstLabel = document.getElementById('summaryCgstLabel');
    const summaryCgstAmount = document.getElementById('summaryCgstAmount');
    const summarySgstLabel = document.getElementById('summarySgstLabel');
    const summarySgstAmount = document.getElementById('summarySgstAmount');
    const cgstRow = document.getElementById('cgstRow');
    const sgstRow = document.getElementById('sgstRow');
    const summaryTotal = document.getElementById('summaryTotal');
    const submitBtnLabel = document.getElementById('submitBtnLabel');

    window.adjustTvCount = function(delta) {
        if (!tvCountInput) return;
        let current = parseInt(tvCountInput.value) || 1;
        let newVal = Math.max(1, current + delta);
        tvCountInput.value = newVal;
        calculateTotal();
    };

    window.setTvCount = function(count) {
        if (!tvCountInput) return;
        tvCountInput.value = Math.max(1, parseInt(count) || 1);
        calculateTotal();
    };

    function renderCustomDurationMenu() {
        const customMenu = document.getElementById('durationCustomMenu');
        const customLabel = document.getElementById('durationCustomTriggerLabel');
        if (!customMenu || !durationSelect) return;

        customMenu.innerHTML = '';
        const selectedVal = String(durationSelect.value);

        Array.from(durationSelect.options).forEach(opt => {
            const isSelected = String(opt.value) === selectedVal;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `w-full p-2.5 rounded-xl text-left transition-all flex items-center justify-between hover:bg-purple-50 group cursor-pointer text-xs font-bold ${isSelected ? 'bg-purple-50 text-[#150A42]' : 'text-slate-700'}`;
            btn.innerHTML = `
                <div class="flex items-center space-x-2 truncate">
                    <span class="w-1.5 h-1.5 rounded-full ${isSelected ? 'bg-purple-600' : 'bg-slate-300'}"></span>
                    <span class="truncate">${opt.text}</span>
                </div>
                ${isSelected ? '<span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] shrink-0 ml-2"><i class="fa-solid fa-check"></i></span>' : ''}
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
        calculateTotal();
    };

    function syncDurationOptions() {
        let selectedPlan = document.querySelector('.plan-radio:checked');
        if (!selectedPlan && planRadios.length > 0) {
            selectedPlan = planRadios[0];
        }
        if (!selectedPlan || !durationSelect) return;

        const cycle = (selectedPlan.dataset.billingCycle || 'monthly').toLowerCase();
        const isYearly = (cycle === 'yearly');
        const currentVal = parseInt(durationSelect.value) || (isYearly ? 12 : 1);

        const durationLabel = document.getElementById('durationSelectLabel');
        if (durationLabel) {
            durationLabel.textContent = isYearly ? 'License Validity Period (Annual Pack)' : 'License Validity Period (Monthly Pack)';
        }

        const isCurrentlyYearlyDropdown = (durationSelect.querySelector('option[value="24"]') !== null && durationSelect.querySelector('option[value="1"]') === null);

        if (isYearly && !isCurrentlyYearlyDropdown) {
            durationSelect.innerHTML = `
                <option value="12">1 Year (12 Months - Annual Pack)</option>
                <option value="24">2 Years (24 Months - 2-Year Pack)</option>
                <option value="36">3 Years (36 Months - 3-Year Pack)</option>
            `;
            durationSelect.value = (currentVal >= 12 && [12, 24, 36].includes(currentVal)) ? currentVal : 12;
        } else if (!isYearly && isCurrentlyYearlyDropdown) {
            durationSelect.innerHTML = `
                <option value="1">1 Month (Monthly Pack)</option>
                <option value="3">3 Months (Quarterly Pack)</option>
                <option value="6">6 Months (Semi-Annual Pack)</option>
                <option value="12">12 Months (1 Year - Annual Pack)</option>
                <option value="24">24 Months (2-Year Pack)</option>
                <option value="36">36 Months (3-Year Pack)</option>
            `;
            durationSelect.value = [1, 3, 6, 12, 24, 36].includes(currentVal) ? currentVal : 1;
        }

        renderCustomDurationMenu();
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const customMenu = document.getElementById('durationCustomMenu');
        const triggerBtn = document.getElementById('durationCustomTriggerBtn');
        const chevron = document.getElementById('durationCustomChevron');
        if (customMenu && !customMenu.contains(e.target) && triggerBtn && !triggerBtn.contains(e.target)) {
            customMenu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    });

    function calculateTotal() {
        let selectedPlan = document.querySelector('.plan-radio:checked');
        if (!selectedPlan && planRadios.length > 0) {
            selectedPlan = planRadios[0];
            selectedPlan.checked = true;
        }
        if (!selectedPlan) return;

        // Card highlight styling
        document.querySelectorAll('.plan-card').forEach(card => {
            const radio = card.querySelector('.plan-radio');
            if (radio && radio.checked) {
                card.className = 'plan-card relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all border-[#150A42] bg-purple-50/30 shadow-xs';
            } else {
                card.className = 'plan-card relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all border-slate-200/90 hover:border-slate-300 bg-white';
            }
        });

        const name = selectedPlan.dataset.name || 'Plan';
        const unitRate = parseFloat(selectedPlan.dataset.unitRate) || 0;
        const cycle = (selectedPlan.dataset.billingCycle || 'monthly').toLowerCase();
        const isYearly = (cycle === 'yearly');

        let tvCount = parseInt(tvCountInput.value) || 1;
        if (tvCount < 1) tvCount = 1;

        let months = parseInt(durationSelect.value) || (isYearly ? 12 : 1);

        const multiplier = isYearly ? Math.max(1, Math.round((months / 12) * 100) / 100) : months;
        let subtotal = Math.round((unitRate * tvCount * multiplier) * 100) / 100;

        const taxRate = parseFloat(selectedPlan.dataset.taxRate) || 0;
        const taxName = selectedPlan.dataset.taxName || 'GST';
        const taxType = selectedPlan.dataset.taxType || 'exclusive';
        const isSplit = selectedPlan.dataset.isSplit === '1';

        let taxAmount = 0.00;
        let totalAmount = subtotal;
        let baseSubtotal = subtotal;
        let cgst = 0.00;
        let sgst = 0.00;

        if (taxRate > 0) {
            if (taxType === 'inclusive') {
                taxAmount = Math.round((subtotal - (subtotal / (1 + (taxRate / 100)))) * 100) / 100;
                baseSubtotal = Math.round((subtotal - taxAmount) * 100) / 100;
                totalAmount = subtotal;
            } else {
                taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
                totalAmount = Math.round((subtotal + taxAmount) * 100) / 100;
                baseSubtotal = subtotal;
            }

            if (isSplit) {
                cgst = Math.round((taxAmount / 2) * 100) / 100;
                sgst = Math.round((taxAmount - cgst) * 100) / 100;
            }
        }

        // Update Summary Elements
        if (summaryPlanName) summaryPlanName.textContent = name;
        if (summaryTvCount) summaryTvCount.textContent = `${tvCount} Smart TVs`;
        if (summaryUnitRate) {
            summaryUnitRate.textContent = `₹${unitRate.toFixed(2)} / TV / ${isYearly ? 'year' : 'month'}`;
        }
        if (summaryDuration) {
            if (isYearly) {
                const yrs = Math.max(1, Math.round(months / 12));
                summaryDuration.textContent = `${yrs} Year${yrs > 1 ? 's' : ''} (${months} Months)`;
            } else {
                summaryDuration.textContent = `${months} Month${months > 1 ? 's' : ''}`;
            }
        }

        if (summaryFormula) {
            const timeStr = isYearly 
                ? (multiplier === 1 ? '1 yr' : `${multiplier} yrs`) 
                : (months === 1 ? '1 mo' : `${months} mos`);
            summaryFormula.textContent = `${tvCount} TVs × ₹${unitRate.toFixed(2)} × ${timeStr}`;
        }

        if (summarySubtotal) {
            summarySubtotal.textContent = `₹${baseSubtotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }

        if (taxRate > 0) {
            if (summaryTaxLabel) {
                summaryTaxLabel.textContent = `${taxName} (${taxRate.toFixed(0)}% ${taxType.charAt(0).toUpperCase() + taxType.slice(1)}):`;
            }
            if (summaryTaxAmount) {
                summaryTaxAmount.textContent = `+ ₹${taxAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            }

            if (isSplit) {
                if (cgstRow) cgstRow.style.display = 'flex';
                if (sgstRow) sgstRow.style.display = 'flex';
                if (summaryCgstLabel) summaryCgstLabel.textContent = `CGST (${(taxRate / 2).toFixed(1)}%):`;
                if (summaryCgstAmount) summaryCgstAmount.textContent = `₹${cgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                if (summarySgstLabel) summarySgstLabel.textContent = `SGST (${(taxRate / 2).toFixed(1)}%):`;
                if (summarySgstAmount) summarySgstAmount.textContent = `₹${sgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            } else {
                if (cgstRow) cgstRow.style.display = 'none';
                if (sgstRow) sgstRow.style.display = 'none';
            }
        } else {
            if (summaryTaxLabel) summaryTaxLabel.textContent = 'Statutory Tax:';
            if (summaryTaxAmount) summaryTaxAmount.textContent = '₹0.00 (Zero Rated)';
            if (cgstRow) cgstRow.style.display = 'none';
            if (sgstRow) sgstRow.style.display = 'none';
        }

        const totalFormatted = `₹${totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        if (summaryTotal) summaryTotal.textContent = totalFormatted;
        if (submitBtnLabel) submitBtnLabel.textContent = `Confirm & Settle Renewal ${totalFormatted}`;
    }

    planRadios.forEach(r => r.addEventListener('change', () => {
        syncDurationOptions();
        calculateTotal();
    }));
    if (durationSelect) durationSelect.addEventListener('change', calculateTotal);
    if (tvCountInput) {
        tvCountInput.addEventListener('input', calculateTotal);
        tvCountInput.addEventListener('change', calculateTotal);
    }

    // Initial run
    syncDurationOptions();
    calculateTotal();
});
</script>
@endsection
