@extends('layouts.distributor')

@section('title', 'Sell Package - Distributor Hub')
@section('page_title', 'Sell Subscription Package')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('distributor.sales.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Back to Sales Ledger</span>
        </a>
    </div>

    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($hotels->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-3xl p-8 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl mx-auto">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <h3 class="font-extrabold text-slate-900 text-base">No Hotels Available to Sell Packages</h3>
            <p class="text-xs text-slate-600 max-w-md mx-auto">You must onboard at least one partner hotel under your account before you can sell or assign subscription packages.</p>
            <div>
                <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Onboard Hotel First</span>
                </a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('distributor.sales.store') }}" id="saleForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <!-- Form Left/Center (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Select Hotel & Plan -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-extrabold text-slate-900">1. Target Hotel & Package</h3>
                        <p class="text-xs text-slate-500">Choose which hotel to license and select the subscription tier.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Select Hotel Property <span class="text-rose-500">*</span></label>
                            <x-dropdown name="hotel_id" id="hotelSelect" required theme="dist">
                                <option value="">-- Choose Hotel --</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ (old('hotel_id', $selectedHotelId) == $hotel->id) ? 'selected' : '' }} data-rooms="{{ $hotel->room_count }}" data-plan="{{ $hotel->plan ? $hotel->plan->name : 'None' }}" data-expiry="{{ $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') : 'None' }}">
                                        {{ $hotel->hotel_name }} ({{ $hotel->room_count }} Rooms • {{ $hotel->hotel_location }})
                                    </option>
                                @endforeach
                            </x-dropdown>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Select Subscription Plan <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($plans as $plan)
                                    <label class="relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-slate-400 bg-white has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="w-7 h-7 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                                                <i class="fa-solid fa-layer-group"></i>
                                            </span>
                                            <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id', $loop->first ? $plan->id : '') == $plan->id ? 'checked' : '' }} data-price="{{ $plan->price }}" data-name="{{ $plan->name }}" data-rooms="{{ $plan->room_count }}" class="plan-radio w-4 h-4 text-amber-600 border-slate-300 focus:ring-amber-500">
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-xs">{{ $plan->name }}</div>
                                            <div class="text-sm font-extrabold text-amber-600 mt-0.5">₹{{ number_format($plan->price, 0) }} <span class="text-[10px] text-slate-400 font-normal">/mo</span></div>
                                            <p class="text-[10px] text-slate-500 mt-1">Up to {{ $plan->room_count }} rooms</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Duration & Payment Method -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-extrabold text-slate-900">2. Duration & Payment Details</h3>
                        <p class="text-xs text-slate-500">Choose validity duration and payment collection method.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">License Duration <span class="text-rose-500">*</span></label>
                            <x-dropdown name="duration_months" id="durationSelect" required theme="dist">
                                <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>1 Month (Standard)</option>
                                <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 Months (Quarterly)</option>
                                <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 Months (Half-Yearly)</option>
                                <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>12 Months (Annual)</option>
                            </x-dropdown>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Total Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" readonly required class="w-full px-3.5 py-2.5 text-xs font-extrabold text-slate-900 rounded-xl border border-slate-200 bg-slate-100/80 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Active Payment Gateway Only (No Offline/Unverified Bypasses) -->
                    <div class="pt-2">
                        <input type="hidden" name="payment_method" id="paymentMethod" value="online_gateway">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Authorized Payment Channel <span class="text-rose-500">*</span></label>
                        
                        @if($activeGatewayRecord && $activeGatewayRecord->is_enabled)
                            <div class="p-4 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
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
                                            <h4 class="text-xs font-extrabold text-slate-900">{{ $activeGatewayRecord->name }}</h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Active Gateway
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Secure online processing. Offline / manual payment is disabled.</p>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-emerald-700 shrink-0 flex items-center space-x-1">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span>Verified Only</span>
                                </div>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl border-2 border-rose-300 bg-rose-50 flex items-center space-x-3 text-rose-800">
                                <i class="fa-solid fa-triangle-exclamation text-lg shrink-0"></i>
                                <div class="text-xs">
                                    <p class="font-bold">No Active Payment Gateway</p>
                                    <p class="text-[11px] text-rose-700">Please enable a payment gateway in Super Admin settings before purchasing.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary / Checkout Card (1 column) -->
            <div class="space-y-6">
                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm space-y-6 sticky top-28">
                    <div class="border-b border-[#E2E8F0] pb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#FEF9EE] text-[#004B87] border border-[#E5A93C]/30">Order Summary</span>
                        <div class="text-xl font-extrabold text-[#1E293B] mt-1.5">Package Activation</div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-[#E2E8F0]">
                            <span class="text-[#64748B]">Package:</span>
                            <span id="summaryPlanName" class="font-bold text-[#1E293B]">Select plan</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#E2E8F0]">
                            <span class="text-[#64748B]">Monthly Rate:</span>
                            <span id="summaryPlanRate" class="font-bold text-[#1E293B]">₹0</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#E2E8F0]">
                            <span class="text-[#64748B]">Duration:</span>
                            <span id="summaryDuration" class="font-bold text-[#1E293B]">1 Month</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm pt-2">
                            <span class="text-[#1E293B] font-bold">Total Payable:</span>
                            <span id="summaryTotal" class="font-black text-[#004B87] text-lg">₹0</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#FEF9EE] border border-[#E5A93C]/30 text-[11px] text-[#004B87] space-y-1">
                        <div class="font-bold flex items-center space-x-1.5">
                            <i class="fa-solid fa-bolt text-[#E5A93C]"></i>
                            <span>Mandatory Payment Verification</span>
                        </div>
                        <p class="text-[#64748B] text-[10px] leading-relaxed">License and TV room limits are activated only after payment is completed and verified through the active gateway.</p>
                    </div>

                    <button type="submit" id="saleSubmitBtn" {{ (!$activeGatewayRecord || !$activeGatewayRecord->is_enabled) ? 'disabled' : '' }} class="btn-dist-primary w-full py-3.5 px-4 text-xs shadow-md shadow-[#E5A93C]/20 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span id="saleSubmitLabel">Proceed to Pay ₹<span id="btnPayAmount">0</span> & Activate</span>
                    </button>

                    <p class="text-[10px] text-slate-400 text-center leading-relaxed">
                        <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i>
                        Online gateway payments are verified server-side via {{ $activeGatewayRecord ? $activeGatewayRecord->name : 'active gateway' }} before license activation.
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

    function calculateTotal() {
        let selectedPlan = document.querySelector('.plan-radio:checked');
        if (!selectedPlan && planRadios.length > 0) {
            planRadios[0].checked = true;
            selectedPlan = planRadios[0];
        }

        if (selectedPlan) {
            const price = parseFloat(selectedPlan.dataset.price) || 0;
            const name = selectedPlan.dataset.name || 'Custom Plan';
            const months = parseInt(durationSelect.value) || 1;
            const total = price * months;

            amountInput.value = total.toFixed(2);
            summaryPlanName.textContent = name;
            summaryPlanRate.textContent = `₹${price.toLocaleString()}/mo`;
            summaryDuration.textContent = `${months} Month${months > 1 ? 's' : ''}`;
            summaryTotal.textContent = `₹${total.toLocaleString()}`;

            const btnPayAmount = document.getElementById('btnPayAmount');
            if (btnPayAmount) btnPayAmount.textContent = total.toLocaleString();
        }
    }

    planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
    if (durationSelect) durationSelect.addEventListener('change', calculateTotal);
    calculateTotal();

    // ---------------------------------------------------------------
    // Online Gateway (Active Gateway) checkout flow
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
        alert(message); // simple fallback; session('error') also renders server-side
    }

    form.addEventListener('submit', async function(e) {
        // Only intercept when "Online Card / Gateway" is selected
        if (paymentMethodSelect.value !== 'online_gateway' || checkoutSubmitting) {
            return;
        }

        e.preventDefault();

        // Client-side guard: hotel & plan must be selected before creating an order
        const hotelSelect = document.getElementById('hotelSelect');
        if (!hotelSelect || !hotelSelect.value) {
            showError('Please select a target hotel before proceeding with online payment.');
            return;
        }
        if (!document.querySelector('.plan-radio:checked')) {
            showError('Please select a subscription plan before proceeding with online payment.');
            return;
        }

        setSubmitting(true);
        checkoutSubmitting = true;

        try {
            // 1. Create a Razorpay order on the server
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

            // 2. Route by active gateway
            if (orderData.gateway === 'razorpay') {
                if (typeof Razorpay === 'undefined') {
                    throw new Error('Razorpay checkout script failed to load. Please refresh the page and try again.');
                }

                const rzp = new Razorpay({
                    key: orderData.key_id,
                    order_id: orderData.order_id,
                    amount: Math.round(parseFloat(orderData.amount) * 100), // paise
                    currency: orderData.currency || 'INR',
                    name: 'PaxTV Hospitality',
                    description: 'Package Activation',
                    prefill: {
                        name: orderData.name || '',
                        email: orderData.email || '',
                        contact: orderData.phone || '',
                    },
                    theme: { color: '#E5A93C' },
                    handler: function(response) {
                        // 3. Append gateway response to the form and submit for server-side verification
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

                        checkoutSubmitting = false; // allow real submit through
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
});
</script>
@endsection
