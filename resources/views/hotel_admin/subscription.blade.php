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
            @if($hotel->expiry_date && $hotel->expiry_date->isPast())
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-[#FEF2F2] text-[#B91C1C] border border-[#DC2626]/30">Expired</span>
            @elseif($hotel->expiry_date && $hotel->expiry_date->diffInDays(now()) <= 7)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-[#FFFBEB] text-[#B45309] border border-[#EA580C]/30">Expires Soon</span>
            @elseif($hotel->expiry_date)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/30">Active</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase font-mono bg-[#F1F5F9] text-[#6B7280] border border-[#CBD5E1]">No Plan</span>
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

    <!-- Purchase / Upgrade / Renew Flow -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-[#E5E7EB] pb-4">
            <h3 class="text-lg font-extrabold text-[#111827] tracking-tight">Per-TV Subscription Checkout</h3>
            <p class="text-xs text-[#6B7280] font-medium mt-1">Select the number of Connected TVs in your property and choose your preferred billing cycle.</p>
        </div>

        <!-- Step 1: Number of TVs in Hotel -->
        <div class="p-5 rounded-2xl bg-[#F8F9FD] border border-slate-200/90 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-black uppercase font-mono text-[#150A42] tracking-wider block">Step 1</span>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">How many Connected TVs need licensing?</h4>
                </div>
                <span class="text-xs font-mono font-bold text-slate-500">Selected: <strong id="tvCountDisplay" class="text-[#150A42]">30</strong> TVs</span>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 pt-1">
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="adjustHotelTvs(-5)" class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 active:scale-95 flex items-center justify-center font-bold text-sm transition-all shadow-xs cursor-pointer">
                        <i class="fa-solid fa-minus text-xs text-slate-600"></i>
                    </button>
                    <input type="number" id="tvCountInput" min="1" max="1000" value="{{ max(1, $hotel->room_count ?: 30) }}" oninput="calcDynamicPricing()" class="w-28 text-center py-2 px-3 bg-white text-[#150A42] font-mono font-black text-lg rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 shadow-inner">
                    <button type="button" onclick="adjustHotelTvs(5)" class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 active:scale-95 flex items-center justify-center font-bold text-sm transition-all shadow-xs cursor-pointer">
                        <i class="fa-solid fa-plus text-xs text-slate-600"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400 font-bold uppercase">Presets:</span>
                    <button type="button" onclick="setHotelTvs(15)" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#150A42] text-xs font-bold font-mono">15</button>
                    <button type="button" onclick="setHotelTvs(30)" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#150A42] text-xs font-bold font-mono">30</button>
                    <button type="button" onclick="setHotelTvs(50)" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#150A42] text-xs font-bold font-mono">50</button>
                    <button type="button" onclick="setHotelTvs(100)" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#150A42] text-xs font-bold font-mono">100</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Select Plan -->
        <div class="space-y-3">
            <span class="text-xs font-black uppercase font-mono text-[#150A42] tracking-wider block">Step 2: Choose Plan Tier</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($plans as $plan)
                    @php
                        $rate = (float) $plan->effective_price_per_tv;
                        $tax = $plan->tax;
                        $taxRate = $tax ? $tax->rate : 0;
                        $taxType = $plan->tax_type ?? 'exclusive';
                        $cycle = $plan->billing_cycle ?? 'monthly';
                    @endphp
                    <label class="relative flex flex-col justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all hover:border-[#150A42]/60 bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/40">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-1 rounded-xl {{ $cycle === 'yearly' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-[#150A42]' }} text-[10px] font-extrabold uppercase font-mono tracking-wider">
                                {{ ucfirst($cycle) }}
                            </span>
                            <input type="radio" 
                                   name="plan_id" 
                                   value="{{ $plan->id }}" 
                                   data-rate="{{ $rate }}" 
                                   data-cycle="{{ $cycle }}"
                                   data-tax-rate="{{ $taxRate }}"
                                   data-tax-name="{{ $tax ? $tax->name : 'No Tax' }}"
                                   data-tax-type="{{ $taxType }}"
                                   data-name="{{ $plan->name }}" 
                                   class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-[#150A42]" 
                                   {{ ($hotel->plan_id == $plan->id || $loop->first) ? 'checked' : '' }}>
                        </div>
                        <div>
                            <div class="font-extrabold text-[#111827] text-sm">{{ $plan->name }}</div>
                            <div class="text-xl font-black text-[#150A42] mt-1 font-mono">
                                ₹{{ number_format($rate, 0) }} 
                                <span class="text-[10px] text-slate-500 font-sans font-normal">/ TV / {{ $cycle === 'yearly' ? 'yr' : 'mo' }}</span>
                            </div>

                            <!-- Tax Pill -->
                            <div class="mt-2">
                                @if($taxRate > 0)
                                    <span class="inline-block text-[10px] font-bold font-mono px-2 py-0.5 rounded-md {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $tax->name }} ({{ number_format($taxRate, 0) }}% {{ ucfirst($taxType) }})
                                    </span>
                                @else
                                    <span class="inline-block text-[10px] font-mono px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                                        No Tax Applied
                                    </span>
                                @endif
                            </div>

                            @if($hotel->plan_id == $plan->id)
                                <span class="inline-block mt-2 text-[10px] font-bold text-[#15803D] bg-[#F0FDF4] px-2 py-0.5 rounded-full">
                                    Current Plan
                                </span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Step 3: Duration & Invoice Summary Checkout -->
        <div class="p-5 rounded-2xl bg-[#F8FAFC] border border-slate-200/90 space-y-4">
            <span class="text-xs font-black uppercase font-mono text-[#150A42] tracking-wider block">Step 3: Duration & Payment Summary</span>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#111827] mb-1">Duration Multiplier</label>
                    <select id="durationSelect" onchange="calcDynamicPricing()" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 focus:border-[#150A42] bg-white">
                        <option value="1">1 Period</option>
                        <option value="3">3 Periods (Quarterly)</option>
                        <option value="6">6 Periods (Bi-Annual)</option>
                        <option value="12">12 Periods</option>
                    </select>
                </div>

                <div class="sm:col-span-2 space-y-2 bg-white p-4 rounded-xl border border-slate-200/80">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Base Subtotal (<span id="summaryTvCount">30</span> TVs × ₹<span id="summaryRate">150</span>):</span>
                        <span id="summarySubtotal" class="font-mono font-bold text-slate-900">₹4,500</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Applicable GST / Tax (<span id="summaryTaxName">18% GST</span>):</span>
                        <span id="summaryTaxAmount" class="font-mono font-bold text-slate-900">+₹810</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-black text-slate-900 uppercase">Total Payable:</span>
                        <div id="totalBox" class="text-xl sm:text-2xl font-black text-[#150A42] font-mono">₹5,310</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                <button id="payBtn" type="button" class="w-full sm:w-auto py-3 px-8 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs transition-colors shadow-md shadow-[#150A42]/25 cursor-pointer">
                    <i class="fa-solid fa-lock mr-1.5"></i> Pay Securely & Activate
                </button>
            </div>
            <p id="payMsg" class="text-xs text-[#6B7280] hidden text-right"></p>
        </div>
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
        tvCountInput.value = val;
        calcDynamicPricing();
    };

    window.calcDynamicPricing = function() {
        let sel = document.querySelector('.plan-radio:checked');
        if (!sel && planRadios.length > 0) { planRadios[0].checked = true; sel = planRadios[0]; }
        if (!sel) return { amount: 0, planId: null, months: 1, tvCount: 1 };

        const tvCount = Math.max(1, parseInt(tvCountInput.value) || 1);
        const rate = parseFloat(sel.dataset.rate) || 0;
        const taxRate = parseFloat(sel.dataset.taxRate) || 0;
        const taxName = sel.dataset.taxName || 'Tax';
        const taxType = sel.dataset.taxType || 'exclusive';
        const cycle = sel.dataset.cycle || 'monthly';
        const multiplier = cycle === 'yearly' ? 1 : (parseInt(durationSelect.value) || 1);

        document.getElementById('tvCountDisplay').textContent = tvCount;
        document.getElementById('summaryTvCount').textContent = tvCount;
        document.getElementById('summaryRate').textContent = rate.toLocaleString('en-IN');

        let subtotal = 0;
        let taxAmount = 0;
        let total = 0;

        if (taxType === 'inclusive') {
            total = Math.round(tvCount * rate * multiplier * 100) / 100;
            subtotal = Math.round((total / (1 + (taxRate / 100))) * 100) / 100;
            taxAmount = Math.round((total - subtotal) * 100) / 100;
        } else {
            subtotal = Math.round(tvCount * rate * multiplier * 100) / 100;
            taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
            total = Math.round((subtotal + taxAmount) * 100) / 100;
        }

        document.getElementById('summarySubtotal').textContent = '₹' + subtotal.toLocaleString('en-IN');
        document.getElementById('summaryTaxName').textContent = taxRate > 0 ? (taxName + ' ' + taxRate + '% (' + taxType + ')') : 'None';
        document.getElementById('summaryTaxAmount').textContent = taxRate > 0 ? ('+₹' + taxAmount.toLocaleString('en-IN')) : '₹0';
        document.getElementById('totalBox').textContent = '₹' + total.toLocaleString('en-IN');

        return { amount: total, planId: sel.value, months: multiplier, tvCount: tvCount };
    };

    planRadios.forEach(r => r.addEventListener('change', calcDynamicPricing));
    durationSelect.addEventListener('change', calcDynamicPricing);
    tvCountInput.addEventListener('input', calcDynamicPricing);
    calcDynamicPricing();

    function setMsg(text, isError) {
        payMsg.textContent = text;
        payMsg.className = 'text-xs ' + (isError ? 'text-[#B91C1C] font-bold' : 'text-[#6B7280]');
        payMsg.classList.remove('hidden');
    }

    payBtn.addEventListener('click', async function() {
        if (processing) return;
        const d = calcDynamicPricing();
        if (!d.planId) { setMsg('Please select a plan first.', true); return; }
        processing = true; payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Initializing...';
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
                description: 'Connected TV Subscription',
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
                modal: { ondismiss: function() { reset(); } }
            });
            rzp.on('payment.failed', function(r) { setMsg('Payment failed: ' + (r.error?.description || 'Try again.'), true); reset(); });
            rzp.open();
        } catch (e) { setMsg(e.message, true); reset(); }
    });

    function reset() { processing = false; payBtn.disabled = false; payBtn.innerHTML = '<i class="fa-solid fa-lock mr-1.5"></i> Pay Securely & Activate'; }
});
</script>
@endsection
