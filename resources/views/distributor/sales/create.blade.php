@extends('layouts.distributor')

@section('title', 'Sell Subscription Package - Distributor Hub')
@section('page_title', 'Subscription Settlement & Checkout')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top Breadcrumb & Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('distributor.sales.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Sales Ledger</span>
                </a>
                <span>/</span>
                @if($selectedHotel)
                    <span class="text-slate-600 font-bold truncate max-w-[180px]">{{ $selectedHotel->hotel_name }}</span>
                    <span>/</span>
                @endif
                <span class="text-[#150A42] font-black">Package Checkout</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Sell & Settle Subscription Package</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Select connected TVs, assess statutory taxes, and process online gateway payment with instant tax invoice.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('distributor.invoices.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-file-invoice-dollar text-xs text-purple-600"></i>
                <span>Tax Invoices</span>
            </a>
            <a href="{{ route('distributor.hotels.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-hotel text-xs text-purple-600"></i>
                <span>My Hotels</span>
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

    @if($hotels->isEmpty())
        <div class="bg-white border border-slate-100 rounded-[28px] p-12 text-center space-y-4 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-2xl mx-auto shadow-2xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base">No Hotels Available to Sell Packages</h3>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 font-medium">You must onboard at least one partner hotel under your distributor franchise before selling subscription packages.</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs inline-flex items-center space-x-2 shadow-md shadow-[#150A42]/15 active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>+ Onboard First Hotel</span>
                </a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('distributor.sales.store') }}" id="saleForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf

            <!-- Form Left/Center (7 cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. Billed Hotel Property Card -->
                <div class="bg-white border border-slate-100 rounded-[28px] p-5 sm:p-6 shadow-sm space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg shrink-0 shadow-2xs overflow-hidden">
                                @if($selectedHotel && $selectedHotel->hotel_logo)
                                    <img src="{{ asset($selectedHotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-hotel"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Target Property</span>
                                <h3 id="displayHotelName" class="text-base sm:text-lg font-black text-slate-900 truncate">
                                    {{ $selectedHotel ? $selectedHotel->hotel_name : 'Select Hotel Property' }}
                                </h3>
                                <p id="displayHotelLocation" class="text-xs text-slate-400 font-semibold truncate flex items-center mt-0.5">
                                    <i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[10px]"></i>
                                    <span>{{ $selectedHotel ? ($selectedHotel->city ?? $selectedHotel->hotel_location) : 'Choose a partner hotel below' }}</span>
                                </p>
                            </div>
                        </div>

                        @if($hotels->count() > 1)
                            <button type="button" onclick="toggleHotelSelector()" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] text-xs font-bold border border-purple-200/80 transition-all shrink-0 active:scale-95 cursor-pointer">
                                <i class="fa-solid fa-repeat text-[10px] mr-1"></i>
                                <span>Switch Hotel</span>
                            </button>
                        @endif
                    </div>

                    <!-- Hotel Selector Dropdown (Toggleable or always shown if none pre-selected) -->
                    <div id="hotelSelectorBox" class="{{ $selectedHotel ? 'hidden' : '' }} space-y-2 pt-1 pb-2">
                        <label class="block text-xs font-black text-slate-700">Choose Hotel Property <span class="text-rose-500">*</span></label>
                        <select name="hotel_id" id="hotelSelect" required onchange="onHotelSelectionChanged(this)" class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] font-semibold text-slate-800 transition-all">
                            <option value="">-- Choose Partner Hotel --</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" 
                                        {{ (old('hotel_id', $selectedHotelId) == $hotel->id) ? 'selected' : '' }} 
                                        data-name="{{ $hotel->hotel_name }}"
                                        data-location="{{ $hotel->city ?? $hotel->hotel_location }}"
                                        data-owner="{{ $hotel->owner_name }}"
                                        data-phone="{{ $hotel->phone }}"
                                        data-rooms="{{ $hotel->room_count }}" 
                                        data-existing-tvs="{{ $hotel->existing_tv_count }}"
                                        data-devices="{{ $hotel->connected_devices_count ?? 0 }}"
                                        data-license="{{ $hotel->license_key }}"
                                        data-plan-id="{{ $hotel->plan_id }}"
                                        data-plan-name="{{ $hotel->plan ? $hotel->plan->name : 'No Plan' }}" 
                                        data-expiry="{{ $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') : 'Not Set' }}">
                                    {{ $hotel->hotel_name }} ({{ $hotel->existing_tv_count }} TVs • {{ $hotel->city ?? $hotel->hotel_location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selected Hotel Specs Mini-Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Licensed Rooms</span>
                            <span id="displayHotelRooms" class="font-black text-slate-900 text-sm mt-0.5 block">
                                {{ $selectedHotel ? $selectedHotel->room_count : '—' }} Rms
                            </span>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Connected TVs</span>
                            <span id="displayHotelDevices" class="font-black text-purple-700 text-sm mt-0.5 block">
                                {{ $selectedHotel ? ($selectedHotel->connected_devices_count ?? 0) : '0' }} Screens
                            </span>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Current Plan</span>
                            <span id="displayHotelPlan" class="font-black text-slate-900 text-xs mt-0.5 block truncate">
                                {{ $selectedHotel && $selectedHotel->plan ? $selectedHotel->plan->name : 'Unassigned' }}
                            </span>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expiry Status</span>
                            <span id="displayHotelExpiry" class="font-bold text-slate-700 text-xs mt-0.5 block truncate">
                                {{ $selectedHotel && $selectedHotel->expiry_date ? \Carbon\Carbon::parse($selectedHotel->expiry_date)->format('M d, Y') : 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. TV Screen Quantity Selector (Number of TVs) -->
                @php
                    $baseTvCount = $selectedHotel ? $selectedHotel->existing_tv_count : 10;
                    $defaultTvCount = old('tv_count', $baseTvCount);
                @endphp

                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                                <i class="fa-solid fa-tv"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900">Connected TV Capacity & Screen Slots</h3>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    Pre-filled with hotel's existing <strong class="text-slate-700 font-black" id="headerBaseTvText">{{ $baseTvCount }} TVs</strong> license. You can increase device slots below.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 shrink-0">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-black uppercase font-mono border border-emerald-200/80 flex items-center shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1.5"></span>
                                Active: <span id="badgeBaseTvText" class="ml-1 font-bold">{{ $baseTvCount }}</span> TVs
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-black text-slate-700">
                                Number of Authorized Smart TVs <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-purple-700 font-bold font-mono">
                                Base Quota: <span id="displayBaseTvCountText">{{ $baseTvCount }}</span> TVs
                            </span>
                        </div>

                        <!-- Stepper Input -->
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="adjustTvCount(-1)" class="w-12 h-12 rounded-2xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-2xs">
                                <i class="fa-solid fa-minus text-sm"></i>
                            </button>

                            <div class="relative flex-1">
                                <input type="number" 
                                       name="tv_count" 
                                       id="tvCountInput" 
                                       value="{{ $defaultTvCount }}" 
                                       min="1" 
                                       max="10000" 
                                       required 
                                       class="w-full text-center px-4 py-3 text-lg font-black text-slate-900 rounded-2xl border-2 border-slate-200 focus:border-[#150A42] focus:bg-white focus:outline-none focus:ring-4 focus:ring-purple-500/10 font-mono transition-all">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">Screens</span>
                            </div>

                            <button type="button" onclick="adjustTvCount(1)" class="w-12 h-12 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 text-white font-black text-lg flex items-center justify-center transition-all cursor-pointer shadow-md shadow-[#150A42]/15">
                                <i class="fa-solid fa-plus text-sm text-amber-300"></i>
                            </button>
                        </div>

                        <!-- Quick Expansion Chips -->
                        <div class="space-y-1.5 pt-1">
                            <span class="text-[11px] font-bold text-slate-400 block">Quick TV Count & Expansion:</span>
                            <div id="quickChipsContainer" class="flex flex-wrap items-center gap-2">
                                <button type="button" onclick="setTvCount({{ $baseTvCount }})" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-xs border border-purple-200/80 transition-all cursor-pointer active:scale-95 shadow-2xs">
                                    <i class="fa-solid fa-rotate-left mr-1 text-[10px]"></i> Current ({{ $baseTvCount }} TVs)
                                </button>
                                <button type="button" onclick="setTvCount({{ $baseTvCount + 5 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                    +5 TVs ({{ $baseTvCount + 5 }})
                                </button>
                                <button type="button" onclick="setTvCount({{ $baseTvCount + 10 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                    +10 TVs ({{ $baseTvCount + 10 }})
                                </button>
                                <button type="button" onclick="setTvCount({{ $baseTvCount + 20 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                    +20 TVs ({{ $baseTvCount + 20 }})
                                </button>
                                <button type="button" onclick="setTvCount({{ $baseTvCount + 50 }})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                    +50 TVs ({{ $baseTvCount + 50 }})
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Select Subscription Package Tier -->
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900">Select Subscription Tier</h3>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Per-TV rates with statutory tax compliance and launcher features.</p>
                            </div>
                        </div>

                        <span class="text-xs font-mono font-bold text-slate-400">
                            {{ $plans->count() }} Tiers
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($plans as $plan)
                            @php
                                $isHotelPlan = $selectedHotel && $selectedHotel->plan_id == $plan->id;
                                $effectiveTax = $plan->tax ?: $defaultTax;
                                $taxRate = $effectiveTax ? (float) $effectiveTax->rate : 18.0;
                                $taxName = $effectiveTax ? $effectiveTax->name : 'GST';
                                $taxType = $plan->tax_type ?? 'exclusive';
                                $unitRate = $plan->effective_price_per_tv;
                            @endphp
                            <label class="relative flex flex-col justify-between p-5 rounded-[22px] border-2 cursor-pointer transition-all hover:border-slate-300 bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 has-[:checked]:shadow-xs group">
                                <div>
                                    <div class="flex items-center justify-between mb-2.5">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm font-black shadow-2xs">
                                            <i class="fa-solid fa-cube"></i>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($isHotelPlan)
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800 uppercase tracking-wider">Current</span>
                                            @endif
                                            <input type="radio" 
                                                   name="plan_id" 
                                                   value="{{ $plan->id }}" 
                                                   {{ (old('plan_id', $selectedHotel && $selectedHotel->plan_id ? $selectedHotel->plan_id : ($loop->first ? $plan->id : '')) == $plan->id) ? 'checked' : '' }} 
                                                   data-unit-rate="{{ $unitRate }}"
                                                   data-name="{{ $plan->name }}" 
                                                   data-billing-cycle="{{ $plan->billing_cycle ?? 'monthly' }}"
                                                   data-tax-id="{{ $effectiveTax ? $effectiveTax->id : '' }}"
                                                   data-tax-name="{{ $taxName }}"
                                                   data-tax-rate="{{ $taxRate }}"
                                                   data-tax-type="{{ $taxType }}"
                                                   data-is-split="{{ ($effectiveTax && $effectiveTax->is_split) ? '1' : '0' }}"
                                                   class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                                        </div>
                                    </div>

                                    <div class="font-black text-slate-900 text-sm sm:text-base">{{ $plan->name }}</div>
                                    <div class="text-xl font-black text-[#150A42] font-mono mt-1">
                                        ₹{{ number_format($unitRate, 2) }} 
                                        <span class="text-[11px] text-slate-400 font-sans font-medium">/ TV / month</span>
                                    </div>

                                    <div class="mt-2 flex items-center space-x-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                            {{ $taxName }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                        </span>
                                    </div>

                                    @if($plan->description)
                                        <p class="text-[11px] text-slate-400 mt-2 font-medium line-clamp-2">
                                            {{ $plan->description }}
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
                                        <span>Live Weather, Flight & Hotel Facilities</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. License Duration & Payment Channel -->
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-1.5">License Validity Period <span class="text-rose-500">*</span></label>
                            <select name="duration_months" id="durationSelect" required class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] font-semibold text-slate-800 transition-all cursor-pointer">
                                <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>1 Month (Standard Renewal)</option>
                                <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 Months (Quarterly Pack)</option>
                                <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 Months (Semi-Annual Pack)</option>
                                <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>12 Months (Annual - Best Value)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-1.5">Calculated Total Payable (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" readonly required class="w-full px-4 py-3 text-sm font-black text-slate-900 rounded-2xl border border-slate-200 bg-slate-100/80 cursor-not-allowed font-mono">
                        </div>
                    </div>

                    <!-- Payment Channel Display -->
                    <div class="pt-2">
                        <input type="hidden" name="payment_method" id="paymentMethod" value="online_gateway">
                        <label class="block text-xs font-black text-slate-700 mb-2">Payment Verification Channel <span class="text-rose-500">*</span></label>
                        
                        @if($activeGatewayRecord && $activeGatewayRecord->is_enabled)
                            <div class="p-4 sm:p-5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black shrink-0 shadow-2xs">
                                        @if($activeGatewayRecord->code === 'razorpay')
                                            <i class="fa-solid fa-bolt text-blue-600 text-lg"></i>
                                        @elseif($activeGatewayRecord->code === 'icici')
                                            <i class="fa-solid fa-building-columns text-amber-700 text-lg"></i>
                                        @else
                                            <i class="fa-solid fa-credit-card text-emerald-700 text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-xs font-black text-slate-900">{{ $activeGatewayRecord->name }}</h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Live Gateway
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Automated server-side verification with instant license generation and GST tax invoice.</p>
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
                                    <p class="text-[11px] text-rose-700 mt-0.5">Please contact system administrator to enable an online gateway.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Summary & Tax Breakdown Column (5 cols, sticky) -->
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
                            <span id="summaryHotelName" class="font-black text-slate-900 truncate max-w-[200px]">
                                {{ $selectedHotel ? $selectedHotel->hotel_name : 'Select Hotel' }}
                            </span>
                        </div>

                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Subscription Tier:</span>
                            <span id="summaryPlanName" class="font-black text-slate-900">Select Plan</span>
                        </div>

                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Connected TVs:</span>
                            <span id="summaryTvCount" class="font-black font-mono text-purple-700">10 Smart TVs</span>
                        </div>

                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Rate per TV:</span>
                            <span id="summaryUnitRate" class="font-black font-mono text-slate-900">₹0.00 / TV / mo</span>
                        </div>

                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Billing Duration:</span>
                            <span id="summaryDuration" class="font-black text-slate-900">1 Month</span>
                        </div>

                        <div class="flex justify-between py-1.5 border-b border-slate-100 bg-slate-50/70 p-2.5 rounded-xl">
                            <div>
                                <span class="text-slate-600 font-bold block">Base Subtotal:</span>
                                <span id="summaryFormula" class="text-[10px] text-slate-400 font-mono block">10 TVs × ₹0 × 1 mo</span>
                            </div>
                            <span id="summarySubtotal" class="font-black font-mono text-slate-900 self-center text-sm">₹0.00</span>
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
                            <span id="summaryTotal" class="font-black font-mono text-[#150A42] text-2xl tracking-tight">₹0.00</span>
                        </div>
                    </div>

                    <!-- Automatic Invoice Guarantee -->
                    <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/60 text-[11px] text-purple-950 space-y-1.5">
                        <div class="font-black flex items-center space-x-1.5">
                            <i class="fa-solid fa-file-invoice text-purple-600"></i>
                            <span>Automated Tax Invoice Generation</span>
                        </div>
                        <p class="text-purple-900/80 text-[10px] leading-relaxed font-medium">Upon verified payment, a formal tax-compliant invoice is immediately issued and available for print and download in your Distributor Tax Invoices section.</p>
                    </div>

                    <button type="submit" 
                            id="saleSubmitBtn" 
                            {{ (!$activeGatewayRecord || !$activeGatewayRecord->is_enabled) ? 'disabled' : '' }} 
                            class="w-full py-4 px-4 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-lock text-xs text-amber-300"></i>
                        <span id="saleSubmitLabel">Proceed to Pay ₹<span id="btnPayAmount">0</span> & Settle</span>
                    </button>

                    <p class="text-[10px] text-slate-400 text-center leading-relaxed font-medium">
                        <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i>
                        Payments processed securely via {{ $activeGatewayRecord ? $activeGatewayRecord->name : 'Payment Gateway' }}.
                    </p>
                </div>
            </div>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRadios = document.querySelectorAll('.plan-radio');
    const durationSelect = document.getElementById('durationSelect');
    const tvCountInput = document.getElementById('tvCountInput');
    const amountInput = document.getElementById('amountInput');

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
    const summaryHotelName = document.getElementById('summaryHotelName');
    const hotelSelect = document.getElementById('hotelSelect');
    const btnPayAmount = document.getElementById('btnPayAmount');

    let currentBaseTvs = @json($baseTvCount);

    function updateQuickChips(base) {
        base = Math.max(1, parseInt(base) || 10);
        const container = document.getElementById('quickChipsContainer');
        if (!container) return;

        container.innerHTML = `
            <button type="button" onclick="setTvCount(${base})" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-xs border border-purple-200/80 transition-all cursor-pointer active:scale-95 shadow-2xs">
                <i class="fa-solid fa-rotate-left mr-1 text-[10px]"></i> Current (${base} TVs)
            </button>
            <button type="button" onclick="setTvCount(${base + 5})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                +5 TVs (${base + 5})
            </button>
            <button type="button" onclick="setTvCount(${base + 10})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                +10 TVs (${base + 10})
            </button>
            <button type="button" onclick="setTvCount(${base + 20})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                +20 TVs (${base + 20})
            </button>
            <button type="button" onclick="setTvCount(${base + 50})" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all cursor-pointer active:scale-95">
                +50 TVs (${base + 50})
            </button>
        `;
    }

    function calculateTotal() {
        let selectedPlan = document.querySelector('.plan-radio:checked');
        if (!selectedPlan && planRadios.length > 0) {
            planRadios[0].checked = true;
            selectedPlan = planRadios[0];
        }

        if (!selectedPlan) return;

        const unitRate = parseFloat(selectedPlan.dataset.unitRate) || 150;
        const name = selectedPlan.dataset.name || 'Subscription Plan';
        const tvCount = Math.max(1, parseInt(tvCountInput ? tvCountInput.value : 1) || 1);
        const months = parseInt(durationSelect ? durationSelect.value : 1) || 1;

        const subtotal = Math.round((unitRate * tvCount * months) * 100) / 100;

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

        // Update form values
        if (amountInput) amountInput.value = totalAmount.toFixed(2);

        // Update Summary Elements
        if (summaryPlanName) summaryPlanName.textContent = name;

        if (summaryTvCount) {
            if (tvCount > currentBaseTvs) {
                const added = tvCount - currentBaseTvs;
                summaryTvCount.innerHTML = '<span class="text-slate-900 font-black">' + tvCount + ' Smart TVs</span> <span class="text-[10px] text-emerald-600 font-bold block font-sans">(+' + added + ' TVs Expansion)</span>';
            } else if (tvCount === currentBaseTvs) {
                summaryTvCount.innerHTML = '<span class="text-slate-900 font-black">' + tvCount + ' Smart TVs</span> <span class="text-[10px] text-purple-700 font-bold block font-sans">(Active Hotel License)</span>';
            } else {
                summaryTvCount.textContent = `${tvCount} Smart TVs`;
            }
        }

        if (summaryUnitRate) summaryUnitRate.textContent = `₹${unitRate.toFixed(2)} / TV / mo`;
        if (summaryDuration) summaryDuration.textContent = `${months} Month${months > 1 ? 's' : ''}`;
        if (summaryFormula) summaryFormula.textContent = `${tvCount} TVs × ₹${unitRate.toFixed(2)} × ${months} mo`;
        if (summarySubtotal) summarySubtotal.textContent = `₹${baseSubtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        if (summaryTaxLabel) summaryTaxLabel.textContent = `${taxName} (${taxRate.toFixed(1)}% - ${taxType.charAt(0).toUpperCase() + taxType.slice(1)}):`;
        if (summaryTaxAmount) summaryTaxAmount.textContent = taxAmount > 0 ? `+ ₹${taxAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '₹0.00 (Zero Rated)';

        if (cgstRow && sgstRow) {
            if (taxAmount > 0 && isSplit) {
                cgstRow.classList.remove('hidden');
                sgstRow.classList.remove('hidden');
                if (summaryCgstLabel) summaryCgstLabel.textContent = `CGST (${(taxRate / 2).toFixed(1)}%):`;
                if (summaryCgstAmount) summaryCgstAmount.textContent = `₹${cgst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                if (summarySgstLabel) summarySgstLabel.textContent = `SGST (${(taxRate / 2).toFixed(1)}%):`;
                if (summarySgstAmount) summarySgstAmount.textContent = `₹${sgst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            } else {
                cgstRow.classList.add('hidden');
                sgstRow.classList.add('hidden');
            }
        }

        if (summaryTotal) summaryTotal.textContent = `₹${totalAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        if (btnPayAmount) btnPayAmount.textContent = totalAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    window.adjustTvCount = function(delta) {
        if (!tvCountInput) return;
        let current = parseInt(tvCountInput.value) || 1;
        let next = Math.max(1, Math.min(10000, current + delta));
        tvCountInput.value = next;
        calculateTotal();
    };

    window.setTvCount = function(val) {
        if (!tvCountInput) return;
        tvCountInput.value = Math.max(1, parseInt(val) || 1);
        calculateTotal();
    };

    if (tvCountInput) {
        tvCountInput.addEventListener('input', calculateTotal);
        tvCountInput.addEventListener('change', calculateTotal);
    }

    planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
    if (durationSelect) durationSelect.addEventListener('change', calculateTotal);

    // Initial run
    calculateTotal();

    window.toggleHotelSelector = function() {
        const box = document.getElementById('hotelSelectorBox');
        if (box) {
            box.classList.toggle('hidden');
        }
    };

    window.onHotelSelectionChanged = function(selectElem) {
        const selectedOpt = selectElem.options[selectElem.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) return;

        const name = selectedOpt.dataset.name || 'Select Hotel Property';
        const location = selectedOpt.dataset.location || '—';
        const existingTvs = parseInt(selectedOpt.dataset.existingTvs) || parseInt(selectedOpt.dataset.rooms) || 10;
        const devices = selectedOpt.dataset.devices || '0';
        const planName = selectedOpt.dataset.planName || 'Unassigned';
        const expiry = selectedOpt.dataset.expiry || 'Not Set';
        const planId = selectedOpt.dataset.planId;

        document.getElementById('displayHotelName').textContent = name;
        document.getElementById('displayHotelLocation').innerHTML = '<i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[10px]"></i> ' + location;
        document.getElementById('displayHotelRooms').textContent = existingTvs + ' Rms';
        document.getElementById('displayHotelDevices').textContent = devices + ' Screens';
        document.getElementById('displayHotelPlan').textContent = planName;
        document.getElementById('displayHotelExpiry').textContent = expiry;
        if (summaryHotelName) summaryHotelName.textContent = name;

        // Auto sync TV count with hotel's capacity
        currentBaseTvs = existingTvs;
        const headerBaseTvText = document.getElementById('headerBaseTvText');
        if (headerBaseTvText) headerBaseTvText.textContent = existingTvs + ' TVs';
        const badgeBaseTvText = document.getElementById('badgeBaseTvText');
        if (badgeBaseTvText) badgeBaseTvText.textContent = existingTvs;
        const displayBaseTvCountText = document.getElementById('displayBaseTvCountText');
        if (displayBaseTvCountText) displayBaseTvCountText.textContent = existingTvs;

        if (tvCountInput) {
            tvCountInput.value = existingTvs;
        }

        updateQuickChips(existingTvs);

        // Auto pre-select matching plan if available
        if (planId) {
            const matchRadio = document.querySelector('.plan-radio[value="' + planId + '"]');
            if (matchRadio) {
                matchRadio.checked = true;
            }
        }

        calculateTotal();
    };

    // ---------------------------------------------------------------
    // Online Gateway Checkout Flow
    // ---------------------------------------------------------------
    const form = document.getElementById('saleForm');
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const submitBtn = document.getElementById('saleSubmitBtn');
    const submitLabel = document.getElementById('saleSubmitLabel');
    const orderUrl = @json(route('distributor.sales.order'));

    let checkoutSubmitting = false;

    function setSubmitting(state) {
        if (!submitBtn) return;
        submitBtn.disabled = state;
        if (submitLabel) {
            submitLabel.innerHTML = state 
                ? '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Opening Gateway...' 
                : 'Proceed to Pay ₹<span id="btnPayAmount">' + (amountInput ? parseFloat(amountInput.value || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00') + '</span> & Settle';
        }
    }

    function showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Payment Error',
                text: message,
                confirmButtonColor: '#150A42',
                customClass: { popup: 'rounded-[28px]' }
            });
        } else {
            alert(message);
        }
    }

    if (form) {
        form.addEventListener('submit', async function(e) {
            if (paymentMethodSelect.value !== 'online_gateway' || checkoutSubmitting) {
                return;
            }

            e.preventDefault();

            if (!hotelSelect || !hotelSelect.value) {
                showError('Please select a target hotel before proceeding with payment.');
                return;
            }
            if (!document.querySelector('.plan-radio:checked')) {
                showError('Please select a subscription plan before proceeding with payment.');
                return;
            }

            const currentTvCount = Math.max(1, parseInt(tvCountInput ? tvCountInput.value : 1) || 1);

            setSubmitting(true);
            checkoutSubmitting = true;

            try {
                const orderRes = await fetch(orderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        hotel_id: hotelSelect.value,
                        plan_id: document.querySelector('.plan-radio:checked').value,
                        tv_count: currentTvCount,
                        duration_months: durationSelect.value,
                    }),
                });

                const orderData = await orderRes.json();

                if (!orderRes.ok || !orderData.success) {
                    throw new Error(orderData.message || 'Could not create payment order.');
                }

                if (orderData.gateway === 'razorpay') {
                    if (typeof Razorpay === 'undefined') {
                        throw new Error('Razorpay checkout script failed to load. Please refresh the page and try again.');
                    }

                    const rzp = new Razorpay({
                        key: orderData.key_id,
                        order_id: orderData.order_id,
                        amount: Math.round(parseFloat(orderData.amount) * 100),
                        currency: orderData.currency || 'INR',
                        name: 'PaxTV Hospitality',
                        description: 'TV Subscription License (' + currentTvCount + ' TVs)',
                        prefill: {
                            name: orderData.name || '',
                            email: orderData.email || '',
                            contact: orderData.phone || '',
                        },
                        theme: { color: '#150A42' },
                        handler: function(response) {
                            ['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature'].forEach(function(field) {
                                let input = form.querySelector('input[name="' + field + '"]');
                                if (!input) {
                                    input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = field;
                                    form.appendChild(input);
                                }
                                input.value = response[field] || '';
                            });

                            checkoutSubmitting = false;
                            form.submit();
                        },
                        modal: {
                            ondismiss: function() {
                                checkoutSubmitting = false;
                                setSubmitting(false);
                            },
                        },
                    });

                    rzp.on('payment.failed', function(response) {
                        checkoutSubmitting = false;
                        setSubmitting(false);
                        showError('Payment failed: ' + (response.error?.description || 'Unknown error. Please try again.'));
                    });

                    rzp.open();
                } else if (orderData.gateway === 'payu' || (orderData.payu_params && orderData.checkout_url)) {
                    if (submitLabel) submitLabel.textContent = 'Redirecting to PayU...';
                    const payuForm = document.createElement('form');
                    payuForm.method = 'POST';
                    payuForm.action = orderData.checkout_url;
                    payuForm.style.display = 'none';

                    const params = orderData.payu_params || {};
                    Object.keys(params).forEach(function(key) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = params[key];
                        payuForm.appendChild(input);
                    });

                    document.body.appendChild(payuForm);
                    payuForm.submit();
                } else if (orderData.checkout_url && orderData.checkout_url !== '#') {
                    window.location.href = orderData.checkout_url;
                } else {
                    throw new Error('Active gateway (' + (orderData.gateway || 'Unknown') + ') returned no checkout endpoint. Please contact support.');
                }
            } catch (err) {
                checkoutSubmitting = false;
                setSubmitting(false);
                showError(err.message || 'Something went wrong while starting the payment.');
            }
        });
    }
});
</script>
@endsection
