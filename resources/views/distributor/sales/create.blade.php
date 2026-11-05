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
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Activate smart TV licensing, expand room quotas, and process online gateway payments.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('distributor.hotels.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-hotel text-xs text-purple-600"></i>
                <span>My Hotels</span>
            </a>
            <a href="{{ route('distributor.sales.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-receipt text-xs text-purple-600"></i>
                <span>Sales Ledger</span>
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
                                        data-devices="{{ $hotel->connected_devices_count ?? 0 }}"
                                        data-license="{{ $hotel->license_key }}"
                                        data-plan-id="{{ $hotel->plan_id }}"
                                        data-plan-name="{{ $hotel->plan ? $hotel->plan->name : 'No Plan' }}" 
                                        data-expiry="{{ $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') : 'Not Set' }}">
                                    {{ $hotel->hotel_name }} ({{ $hotel->room_count }} Rms • {{ $hotel->city ?? $hotel->hotel_location }})
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

                    <!-- License Key Quick Copy Bar -->
                    @if($selectedHotel && $selectedHotel->license_key)
                        <div id="licenseBar" class="flex items-center justify-between p-3 rounded-2xl bg-purple-50/60 border border-purple-100 text-xs">
                            <div class="flex items-center space-x-2 min-w-0">
                                <i class="fa-solid fa-key text-purple-600 text-xs"></i>
                                <span class="text-slate-500 font-bold text-[11px]">Hardware License Key:</span>
                                <span id="displayLicenseKey" class="font-mono font-black text-slate-900 text-xs select-all truncate">{{ $selectedHotel->license_key }}</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('displayLicenseKey').innerText); this.innerHTML='<i class=\'fa-solid fa-check text-emerald-600\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 1500);" class="p-1 text-slate-400 hover:text-purple-700 shrink-0 cursor-pointer" title="Copy Key">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- 2. Select Subscription Package Tier -->
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                    <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Select Subscription Tier</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Select a package tier to activate or renew in-room TV services.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($plans as $plan)
                            @php
                                $isHotelPlan = $selectedHotel && $selectedHotel->plan_id == $plan->id;
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
                                                   data-price="{{ $plan->price }}" 
                                                   data-name="{{ $plan->name }}" 
                                                   data-rooms="{{ $plan->room_count }}" 
                                                   class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                                        </div>
                                    </div>

                                    <div class="font-black text-slate-900 text-sm sm:text-base">{{ $plan->name }}</div>
                                    <div class="text-lg font-black text-[#150A42] mt-1">
                                        ₹{{ number_format($plan->price, 0) }} 
                                        <span class="text-[11px] text-slate-400 font-normal">/month</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
                                        Supports up to <strong class="text-slate-700 font-bold">{{ $plan->room_count }}</strong> licensed hotel rooms.
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1 text-[10px] text-slate-500 font-semibold">
                                    <div class="flex items-center space-x-1.5">
                                        <i class="fa-solid fa-check text-emerald-600"></i>
                                        <span>Full In-Room TV Launcher</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <i class="fa-solid fa-check text-emerald-600"></i>
                                        <span>Weather & Flight Status Widgets</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. License Duration & Payment Channel -->
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-5">
                    <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Duration & Payment Settlement</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Select validity period and verified payment gateway.</p>
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
                            <label class="block text-xs font-black text-slate-700 mb-1.5">Calculated Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" readonly required class="w-full px-4 py-3 text-sm font-black text-slate-900 rounded-2xl border border-slate-200 bg-slate-100/80 cursor-not-allowed">
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
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Automated server-side verification with instant license generation.</p>
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

            <!-- Summary & Checkout Column (5 cols, sticky) -->
            <div class="lg:col-span-5 space-y-6 sticky top-24">
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200/60 shadow-2xs">Order Invoice</span>
                        <div class="text-xl font-black text-slate-900 mt-2 tracking-tight">Summary & Checkout</div>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Billed Hotel:</span>
                            <span id="summaryHotelName" class="font-black text-slate-900 truncate max-w-[200px]">
                                {{ $selectedHotel ? $selectedHotel->hotel_name : 'Select Hotel' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Selected Tier:</span>
                            <span id="summaryPlanName" class="font-black text-slate-900">Select Plan</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Base Monthly Rate:</span>
                            <span id="summaryPlanRate" class="font-black text-slate-900">₹0</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Validity Duration:</span>
                            <span id="summaryDuration" class="font-black text-slate-900">1 Month</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm pt-2">
                            <span class="text-slate-900 font-bold">Total Payable:</span>
                            <span id="summaryTotal" class="font-black text-[#150A42] text-2xl tracking-tight">₹0</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/60 text-[11px] text-purple-950 space-y-1">
                        <div class="font-black flex items-center space-x-1.5">
                            <i class="fa-solid fa-bolt text-purple-600"></i>
                            <span>Instant Digital Activation</span>
                        </div>
                        <p class="text-purple-900/80 text-[10px] leading-relaxed font-medium">Smart TV device licenses are immediately validated on Firebase as soon as payment is confirmed.</p>
                    </div>

                    <button type="submit" 
                            id="saleSubmitBtn" 
                            {{ (!$activeGatewayRecord || !$activeGatewayRecord->is_enabled) ? 'disabled' : '' }} 
                            class="w-full py-4 px-4 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span id="saleSubmitLabel">Proceed to Pay ₹<span id="btnPayAmount">0</span> & Activate</span>
                    </button>

                    <p class="text-[10px] text-slate-400 text-center leading-relaxed font-medium">
                        <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i>
                        Online gateway payments are secured via {{ $activeGatewayRecord ? $activeGatewayRecord->name : 'active gateway' }}.
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
    const amountInput = document.getElementById('amountInput');
    const summaryPlanName = document.getElementById('summaryPlanName');
    const summaryPlanRate = document.getElementById('summaryPlanRate');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryHotelName = document.getElementById('summaryHotelName');
    const hotelSelect = document.getElementById('hotelSelect');

    function calculateTotal() {
        let selectedPlan = document.querySelector('.plan-radio:checked');
        if (!selectedPlan && planRadios.length > 0) {
            planRadios[0].checked = true;
            selectedPlan = planRadios[0];
        }

        if (selectedPlan) {
            const price = parseFloat(selectedPlan.dataset.price) || 0;
            const name = selectedPlan.dataset.name || 'Custom Plan';
            const months = parseInt(durationSelect ? durationSelect.value : 1) || 1;
            const total = price * months;

            if (amountInput) amountInput.value = total.toFixed(2);
            if (summaryPlanName) summaryPlanName.textContent = name;
            if (summaryPlanRate) summaryPlanRate.textContent = `₹${price.toLocaleString()}/mo`;
            if (summaryDuration) summaryDuration.textContent = `${months} Month${months > 1 ? 's' : ''}`;
            if (summaryTotal) summaryTotal.textContent = `₹${total.toLocaleString()}`;

            const btnPayAmount = document.getElementById('btnPayAmount');
            if (btnPayAmount) btnPayAmount.textContent = total.toLocaleString();
        }
    }

    planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
    if (durationSelect) durationSelect.addEventListener('change', calculateTotal);
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
        const rooms = selectedOpt.dataset.rooms || '—';
        const devices = selectedOpt.dataset.devices || '0';
        const license = selectedOpt.dataset.license || '—';
        const planName = selectedOpt.dataset.planName || 'Unassigned';
        const expiry = selectedOpt.dataset.expiry || 'Not Set';
        const planId = selectedOpt.dataset.planId;

        document.getElementById('displayHotelName').textContent = name;
        document.getElementById('displayHotelLocation').innerHTML = '<i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[10px]"></i> ' + location;
        document.getElementById('displayHotelRooms').textContent = rooms + ' Rms';
        document.getElementById('displayHotelDevices').textContent = devices + ' Screens';
        document.getElementById('displayHotelPlan').textContent = planName;
        document.getElementById('displayHotelExpiry').textContent = expiry;
        if (summaryHotelName) summaryHotelName.textContent = name;

        const displayLicenseKey = document.getElementById('displayLicenseKey');
        if (displayLicenseKey) displayLicenseKey.textContent = license;

        // Auto pre-select matching plan if available
        if (planId) {
            const matchRadio = document.querySelector('.plan-radio[value="' + planId + '"]');
            if (matchRadio) {
                matchRadio.checked = true;
                calculateTotal();
            }
        }
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
                : 'Proceed to Pay ₹<span id="btnPayAmount">' + (amountInput ? parseFloat(amountInput.value || 0).toLocaleString() : '0') + '</span> & Activate';
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
                        description: 'Package Activation',
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
