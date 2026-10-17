@extends('layouts.hotel_admin')
@section('title', 'Plan & Billing')
@section('page_title', 'Subscription Plan & Billing')
@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="max-w-5xl mx-auto space-y-6">    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div>
    @endif
    <div class="bg-white border border-[#E2E8F0] rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
        <div class="border-b border-[#E2E8F0] pb-4 flex items-center space-x-2">
            <h3 class="text-lg font-extrabold text-[#1E293B] tracking-tight">Current Subscription</h3>
            @if($hotel->expiry_date && $hotel->expiry_date->isPast())
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#FEE2E2] text-[#DC2626] border border-[#DC2626]/30">Expired</span>
            @elseif($hotel->expiry_date && $hotel->expiry_date->diffInDays(now()) <= 7)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#FFEDD5] text-[#EA580C] border border-[#EA580C]/30">Expires Soon</span>
            @elseif($hotel->expiry_date)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/30">Active</span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#F1F5F9] text-[#64748B] border border-[#CBD5E1]">No Plan</span>
            @endif
        </div>        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Current Plan</span>
                <p class="text-sm font-extrabold text-[#1E293B]">{{ $hotel->plan->name ?? 'None' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Expiry Date</span>
                <p class="text-sm font-extrabold text-[#1E293B]">{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Room / TV Limit</span>
                <p class="text-sm font-extrabold text-[#1E293B]">{{ $hotel->room_count }} Rooms</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">License Key</span>
                <p class="text-sm font-extrabold text-[#1E293B] font-mono">{{ $hotel->license_key ?? 'N/A' }}</p>
            </div>
        </div>
    </div>    <!-- Choose Plan -->
    <div class="bg-white border border-[#E2E8F0] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-[#E2E8F0] pb-4">
            <h3 class="text-lg font-extrabold text-[#1E293B] tracking-tight">Purchase / Upgrade / Renew</h3>
            <p class="text-xs text-[#64748B] font-medium mt-1">Pick a plan & duration. Active plans extend from current expiry; expired plans start fresh.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($plans as $plan)
                <label class="relative flex flex-col justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all hover:border-[#C5A059]/60 bg-white has-[:checked]:border-[#C5A059] has-[:checked]:bg-[#F9F5EB]/40">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-[#F9F5EB] text-[#A88440] flex items-center justify-center text-sm font-bold"><i class="fa-solid fa-layer-group"></i></span>
                        <input type="radio" name="plan_id" value="{{ $plan->id }}" data-price="{{ $plan->price }}" data-name="{{ $plan->name }}" class="plan-radio w-4 h-4 text-[#C5A059] border-slate-300 focus:ring-[#C5A059]" {{ $hotel->plan_id == $plan->id ? 'checked' : '' }}>
                    </div>
                    <div>
                        <div class="font-extrabold text-[#1E293B] text-sm">{{ $plan->name }}</div>
                        <div class="text-xl font-black text-[#A88440] mt-1">?{{ number_format($plan->price, 0) }} <span class="text-[10px] text-[#64748B] font-normal">/mo</span></div>
                        <p class="text-[10px] text-[#64748B] mt-1.5">Up to {{ $plan->room_count }} rooms</p>
                        @if($hotel->plan_id == $plan->id)
                            <span class="inline-block mt-2 text-[10px] font-bold text-[#16A34A] bg-[#DCFCE7] px-2 py-0.5 rounded-full">Current Plan</span>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div>
                <label class="block text-xs font-bold text-[#1E293B] mb-1">Duration</label>
                <select id="durationSelect" class="w-full px-3.5 py-2.5 text-xs font-bold rounded-xl border border-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#C5A059]/30 focus:border-[#C5A059] bg-white">
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">12 Months</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#1E293B] mb-1">Total Payable</label>
                <div id="totalBox" class="w-full px-3.5 py-2.5 text-lg font-black rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 text-[#A88440]">?0</div>
            </div>
            <div class="flex items-end">
                <button id="payBtn" type="button" class="w-full py-3 px-4 rounded-xl bg-[#C5A059] hover:bg-[#A88440] text-white font-extrabold text-xs transition-colors shadow-md shadow-[#C5A059]/25">
                    <i class="fa-solid fa-lock mr-1.5"></i> Pay Securely
                </button>
            </div>
        </div>
        <p id="payMsg" class="text-xs text-[#64748B] hidden"></p>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRadios = document.querySelectorAll('.plan-radio');
    const durationSelect = document.getElementById('durationSelect');
    const totalBox = document.getElementById('totalBox');
    const payBtn = document.getElementById('payBtn');
    const payMsg = document.getElementById('payMsg');
    const orderUrl = "{{ route('hotel.subscription.order') }}";
    const purchaseUrl = "{{ route('hotel.subscription.purchase') }}";
    let processing = false;

    function calc() {
        let sel = document.querySelector('.plan-radio:checked');
        if (!sel && planRadios.length > 0) { planRadios[0].checked = true; sel = planRadios[0]; }
        if (!sel) return { amount: 0, planId: null, months: 1 };
        const price = parseFloat(sel.dataset.price) || 0;
        const months = parseInt(durationSelect.value) || 1;
        totalBox.textContent = '₹' + (price * months).toLocaleString();
        return { amount: price * months, planId: sel.value, months: months };
    }
    planRadios.forEach(r => r.addEventListener('change', calc));
    durationSelect.addEventListener('change', calc);
    calc();

    function setMsg(text, isError) {
        payMsg.textContent = text;
        payMsg.className = 'text-xs ' + (isError ? 'text-[#DC2626] font-bold' : 'text-[#64748B]');
    }

    payBtn.addEventListener('click', async function() {
        if (processing) return;
        const d = calc();
        if (!d.planId) { setMsg('Please select a plan first.', true); return; }
        processing = true; payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Initializing...';
        setMsg('', false);
        try {
            const res = await fetch(orderUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ plan_id: d.planId, duration_months: d.months })
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
                key: data.key_id, order_id: data.order_id,
                amount: Math.round(parseFloat(data.amount) * 100), currency: data.currency || 'INR',
                name: 'PaxTV Hospitality', description: 'Plan Subscription',
                prefill: { name: data.name || '', email: data.email || '', contact: data.phone || '' },
                theme: { color: '#C5A059' },
                handler: function(resp) {
                    fetch(purchaseUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ plan_id: d.planId, duration_months: d.months,
                            razorpay_order_id: resp.razorpay_order_id,
                            razorpay_payment_id: resp.razorpay_payment_id,
                            razorpay_signature: resp.razorpay_signature })
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
    function reset() { processing = false; payBtn.disabled = false; payBtn.innerHTML = '<i class="fa-solid fa-lock mr-1.5"></i> Pay Securely'; }
});
</script>
@endsection