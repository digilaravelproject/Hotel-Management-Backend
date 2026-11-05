@extends('layouts.super_admin')

@section('title', 'TV Screen Add-On - ' . $hotel->hotel_name)
@section('page_title', 'TV Screen Capacity Add-On')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Breadcrumb Header -->
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
                <span class="text-[#150A42] font-black">TV Add-On</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Expand TV Screen Capacity (Add-On)</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Authorize additional in-room smart TVs mid-cycle without modifying subscription expiry date.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Hotel</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold">Please check the input errors:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super-admin.hotels.addon', $hotel->id) }}" method="POST" id="addonForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left Column: Hotel Status & TV Stepper (7 Cols) -->
            <div class="lg:col-span-7 space-y-5">
                <!-- Hotel & Current Plan Card -->
                <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3.5 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg shrink-0 shadow-xs">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Hotel Client</span>
                            <h2 class="text-base sm:text-lg font-extrabold text-slate-900 truncate">{{ $hotel->hotel_name }}</h2>
                            <div class="text-xs text-slate-500 font-medium flex items-center mt-0.5">
                                <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[10px]"></i>
                                <span>{{ $hotel->city ?? $hotel->hotel_location }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metrics Grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Current TVs</span>
                            <span class="font-black text-slate-900 text-sm mt-0.5 block font-mono" id="currentTvDisplay">{{ (int)$hotel->room_count }} TVs</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Active Plan</span>
                            <span class="font-black text-[#150A42] text-xs mt-0.5 block truncate">{{ $hotel->plan ? $hotel->plan->name : 'N/A' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Remaining</span>
                            <span class="font-black text-emerald-700 text-xs mt-0.5 block font-mono">{{ $remainingDays }} Days</span>
                        </div>
                    </div>
                </div>

                <!-- Stepper for Additional TVs -->
                <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-6 shadow-xs space-y-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">How many extra TVs to add?</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">These will be added on top of the existing {{ (int)$hotel->room_count }} TVs.</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="adjustAddonTvs(-5)" class="w-12 h-12 rounded-2xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black text-lg flex items-center justify-center transition-all cursor-pointer shrink-0">
                            <i class="fa-solid fa-minus text-sm"></i>
                        </button>

                        <div class="relative flex-1 flex items-center bg-white rounded-2xl border-2 border-slate-200 focus-within:border-[#150A42] focus-within:ring-4 focus-within:ring-purple-500/10 transition-all px-3 py-1">
                            <input type="number" 
                                   name="additional_tvs" 
                                   id="additionalTvsInput" 
                                   value="{{ $defaultAdditionalTvs }}" 
                                   min="1" 
                                   max="5000" 
                                   inputmode="numeric"
                                   required 
                                   oninput="recalculateAddon()"
                                   class="w-full text-center py-2 text-xl font-black text-slate-900 border-0 focus:ring-0 focus:outline-none font-mono">
                            <div class="shrink-0 pl-2">
                                <span class="px-2.5 py-1 rounded-xl bg-purple-50 border border-purple-200/70 text-[11px] font-black text-[#150A42] uppercase tracking-wider select-none font-mono flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[10px] text-purple-600"></i>
                                    <span>Extra TVs</span>
                                </span>
                            </div>
                        </div>

                        <button type="button" onclick="adjustAddonTvs(5)" class="w-12 h-12 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] active:scale-95 text-white font-black text-lg flex items-center justify-center transition-all cursor-pointer shrink-0 shadow-md shadow-[#150A42]/15">
                            <i class="fa-solid fa-plus text-sm text-amber-300"></i>
                        </button>
                    </div>

                    <!-- Quick Preset Chips -->
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="text-[11px] font-bold text-slate-400">Presets:</span>
                        <button type="button" onclick="setAddonTvs(5)" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all active:scale-95 cursor-pointer">+5 TVs</button>
                        <button type="button" onclick="setAddonTvs(10)" class="px-3 py-1.5 rounded-xl bg-purple-100 hover:bg-purple-200 text-[#150A42] font-black text-xs transition-all active:scale-95 cursor-pointer">+10 TVs</button>
                        <button type="button" onclick="setAddonTvs(20)" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all active:scale-95 cursor-pointer">+20 TVs</button>
                        <button type="button" onclick="setAddonTvs(50)" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all active:scale-95 cursor-pointer">+50 TVs</button>
                    </div>

                    <!-- Quota Result Pill -->
                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between text-xs font-bold text-emerald-900">
                        <span class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>New Total TV Capacity:</span>
                        </span>
                        <span class="font-mono font-black text-base text-emerald-800" id="newTvTotalDisplay">
                            {{ (int)$hotel->room_count + $defaultAdditionalTvs }} TVs
                        </span>
                    </div>

                    <!-- Complimentary / Direct Override Option -->
                    <div class="pt-2 border-t border-slate-100">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_complimentary" id="isComplimentaryCheckbox" value="1" onchange="toggleComplimentary()" class="mt-1 w-4 h-4 rounded text-[#150A42] focus:ring-[#150A42] border-slate-300">
                            <div>
                                <span class="text-xs font-black text-slate-900 block">Complimentary / Direct Quota Upgrade (No Charge)</span>
                                <span class="text-[11px] text-slate-400 font-medium block">Grant additional TV slots without financial charges or invoices.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Notes Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Internal Reference / Notes</label>
                        <input type="text" name="notes" placeholder="e.g. Added 10 screens for 3rd floor expansion" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-[#150A42] focus:outline-none focus:ring-2 focus:ring-[#150A42]/10">
                    </div>
                </div>
            </div>

            <!-- Right Column: Prorated Invoice & Pricing Summary (5 Cols) -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Prorated Cost Breakdown</h3>
                            <p class="text-[11px] text-slate-400 font-medium">Calculated for remaining {{ $remainingDays }} days</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs" id="pricingBreakdownContainer">
                        <div class="flex justify-between text-slate-600">
                            <span>Base Rate per TV:</span>
                            <span class="font-mono font-bold text-slate-900">₹{{ number_format($hotel->plan->effective_price_per_tv, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Daily Rate per TV:</span>
                            <span class="font-mono font-bold text-slate-900">₹<span id="dailyRateText">{{ number_format($pricing['daily_rate_per_tv'], 4) }}</span> / day</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Remaining Plan Days:</span>
                            <span class="font-mono font-bold text-slate-900">{{ $remainingDays }} Days</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Extra TVs:</span>
                            <span class="font-mono font-bold text-purple-700" id="extraTvsText">+{{ $defaultAdditionalTvs }} TVs</span>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex justify-between text-slate-700 font-bold">
                            <span>Prorated Subtotal:</span>
                            <span class="font-mono" id="subtotalText">₹{{ number_format($pricing['subtotal'], 2) }}</span>
                        </div>

                        <div class="flex justify-between text-slate-500">
                            <span>GST ({{ $pricing['tax_rate'] }}%):</span>
                            <span class="font-mono" id="taxAmountText">₹{{ number_format($pricing['tax_amount'], 2) }}</span>
                        </div>

                        <div class="pt-3 border-t-2 border-dashed border-slate-200 flex justify-between items-baseline">
                            <span class="text-sm font-black text-slate-900">Total Settlement:</span>
                            <span class="text-2xl font-black font-mono text-[#150A42]" id="totalAmountText">₹{{ number_format($pricing['total_amount'], 2) }}</span>
                        </div>
                    </div>

                    <div id="complimentaryBadge" class="hidden p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold flex items-center space-x-2">
                        <i class="fa-solid fa-gift text-amber-600"></i>
                        <span>Complimentary TV Capacity (₹0.00 - No Charge)</span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] active:scale-98 text-white font-black text-sm transition-all shadow-md shadow-[#150A42]/20 cursor-pointer flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-check text-amber-300"></i>
                            <span id="submitBtnText">Apply TV Screen Add-On</span>
                        </button>
                    </div>

                    <p class="text-[10px] text-center text-slate-400 font-medium">
                        Subscription expiry date remains <strong class="text-slate-600">{{ $hotel->expiry_date ? $hotel->expiry_date->format('M d, Y') : 'Active' }}</strong>.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const currentTvs = {{ (int)$hotel->room_count }};
    const dailyRatePerTv = {{ (float)$pricing['daily_rate_per_tv'] }};
    const remainingDays = {{ (int)$remainingDays }};
    const taxRate = {{ (float)$pricing['tax_rate'] }};
    const isInclusive = "{{ $hotel->plan->tax_type ?? 'exclusive' }}" === 'inclusive';

    function adjustAddonTvs(delta) {
        const input = document.getElementById('additionalTvsInput');
        let current = parseInt(input.value) || 0;
        input.value = Math.max(1, current + delta);
        recalculateAddon();
    }

    function setAddonTvs(val) {
        document.getElementById('additionalTvsInput').value = val;
        recalculateAddon();
    }

    function recalculateAddon() {
        const extraTvs = Math.max(1, parseInt(document.getElementById('additionalTvsInput').value) || 1);
        const newTotal = currentTvs + extraTvs;
        document.getElementById('newTvTotalDisplay').innerText = newTotal + ' TVs';
        document.getElementById('extraTvsText').innerText = '+' + extraTvs + ' TVs';

        const rawSubtotal = extraTvs * dailyRatePerTv * remainingDays;
        let subtotal = Math.round(rawSubtotal * 100) / 100;
        let taxAmount = 0;
        let total = subtotal;

        if (taxRate > 0) {
            if (isInclusive) {
                total = subtotal;
                subtotal = Math.round((total / (1 + (taxRate / 100))) * 100) / 100;
                taxAmount = Math.round((total - subtotal) * 100) / 100;
            } else {
                taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
                total = Math.round((subtotal + taxAmount) * 100) / 100;
            }
        }

        document.getElementById('subtotalText').innerText = '₹' + subtotal.toFixed(2);
        document.getElementById('taxAmountText').innerText = '₹' + taxAmount.toFixed(2);
        document.getElementById('totalAmountText').innerText = '₹' + total.toFixed(2);
    }

    function toggleComplimentary() {
        const isComp = document.getElementById('isComplimentaryCheckbox').checked;
        const compBadge = document.getElementById('complimentaryBadge');
        const breakdown = document.getElementById('pricingBreakdownContainer');
        const submitText = document.getElementById('submitBtnText');

        if (isComp) {
            compBadge.classList.remove('hidden');
            document.getElementById('totalAmountText').innerText = '₹0.00 (Free)';
            submitText.innerText = 'Grant Complimentary Add-On';
        } else {
            compBadge.classList.add('hidden');
            recalculateAddon();
            submitText.innerText = 'Apply TV Screen Add-On';
        }
    }
</script>
@endsection

