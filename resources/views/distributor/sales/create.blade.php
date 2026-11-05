@extends('layouts.distributor')

@section('title', 'Sell Package - Distributor Hub')
@section('page_title', 'Sell Subscription Package')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('distributor.sales.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/90 shadow-2xs transition-all active:scale-95">
            <i class="fa-solid fa-arrow-left text-[11px] text-slate-400"></i>
            <span>Back to Sales Ledger</span>
        </a>

        <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-400 font-semibold">
            <span>Distributor Hub</span>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 font-extrabold">Sell Package</span>
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
        <div class="bg-white border border-slate-100 rounded-[28px] p-10 text-center space-y-4 shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-2xl mx-auto shadow-2xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base">No Hotels Available to Sell Packages</h3>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 font-medium">You must onboard at least one partner hotel under your account before you can sell or assign subscription packages.</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs inline-flex items-center space-x-2 shadow-md shadow-[#150A42]/15 active:scale-95">
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
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">1. Target Hotel & Package Tier</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Choose which hotel to license and select the subscription plan.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-1.5">Select Hotel Property <span class="text-rose-500">*</span></label>
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
                            <label class="block text-xs font-black text-slate-700 mb-2">Select Subscription Plan <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                @foreach($plans as $plan)
                                    <label class="relative flex flex-col justify-between p-4 sm:p-5 rounded-[22px] border-2 cursor-pointer transition-all hover:border-slate-300 bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 has-[:checked]:shadow-xs">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="w-9 h-9 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-xs font-black shadow-2xs">
                                                <i class="fa-solid fa-layer-group"></i>
                                            </span>
                                            <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id', $loop->first ? $plan->id : '') == $plan->id ? 'checked' : '' }} data-price="{{ $plan->price }}" data-name="{{ $plan->name }}" data-rooms="{{ $plan->room_count }}" class="plan-radio w-4 h-4 text-[#150A42] border-slate-300 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 text-xs sm:text-sm">{{ $plan->name }}</div>
                                            <div class="text-base font-black text-[#150A42] mt-0.5">
                                                ₹{{ number_format($plan->price, 0) }} 
                                                <span class="text-[10px] text-slate-400 font-normal">/mo</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-1 font-medium">Up to {{ $plan->room_count }} rooms license</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Duration & Payment Method -->
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">2. Duration & Payment Details</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Choose validity duration and online payment gateway.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-1.5">License Duration <span class="text-rose-500">*</span></label>
                            <x-dropdown name="duration_months" id="durationSelect" required theme="dist">
                                <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>1 Month (Standard)</option>
                                <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 Months (Quarterly)</option>
                                <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 Months (Half-Yearly)</option>
                                <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>12 Months (Annual)</option>
                            </x-dropdown>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-1.5">Total Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" readonly required class="w-full px-4 py-2.5 text-xs font-black text-slate-900 rounded-2xl border border-slate-200 bg-slate-100/80 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Active Payment Gateway Only -->
                    <div class="pt-2">
                        <input type="hidden" name="payment_method" id="paymentMethod" value="online_gateway">
                        <label class="block text-xs font-black text-slate-700 mb-2">Authorized Payment Gateway <span class="text-rose-500">*</span></label>
                        
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
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Active Gateway
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Secure payment processing with instant license key activation.</p>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-emerald-700 shrink-0 flex items-center space-x-1">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span>Verified Only</span>
                                </div>
                            </div>
                        @else
                            <div class="p-4 sm:p-5 rounded-2xl border-2 border-rose-300 bg-rose-50 flex items-center space-x-3 text-rose-800">
                                <i class="fa-solid fa-triangle-exclamation text-xl shrink-0"></i>
                                <div class="text-xs">
                                    <p class="font-black">No Active Payment Gateway</p>
                                    <p class="text-[11px] text-rose-700 mt-0.5">Please contact administration to enable an online payment gateway.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary / Checkout Card (1 column) -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-7 shadow-sm space-y-6 sticky top-28">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200/60 shadow-2xs">Order Summary</span>
                        <div class="text-xl font-black text-slate-900 mt-2 tracking-tight">Package Activation</div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Package:</span>
                            <span id="summaryPlanName" class="font-black text-slate-900">Select plan</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Monthly Rate:</span>
                            <span id="summaryPlanRate" class="font-black text-slate-900">₹0</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Duration:</span>
                            <span id="summaryDuration" class="font-black text-slate-900">1 Month</span>
                        </div>
                        <div class="flex justify-between py-2.5 text-sm pt-2">
                            <span class="text-slate-900 font-bold">Total Payable:</span>
                            <span id="summaryTotal" class="font-black text-[#150A42] text-xl">₹0</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/60 text-[11px] text-purple-950 space-y-1">
                        <div class="font-black flex items-center space-x-1.5">
                            <i class="fa-solid fa-bolt text-purple-600"></i>
                            <span>Instant Digital License</span>
                        </div>
                        <p class="text-purple-900/80 text-[10px] leading-relaxed font-medium">License validity and TV room quotas are automatically activated as soon as payment is confirmed.</p>
                    </div>

                    <button type="submit" id="saleSubmitBtn" {{ (!$activeGatewayRecord || !$activeGatewayRecord->is_enabled) ? 'disabled' : '' }} class="w-full py-3.5 px-4 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span id="saleSubmitLabel">Proceed to Pay ₹<span id="btnPayAmount">0</span> & Activate</span>
                    </button>

                    <p class="text-[10px] text-slate-400 text-center leading-relaxed font-medium">
                        <i class="fa-solid fa-shield-check text-emerald-600 mr-1"></i>
                        Online gateway payments are verified server-side via {{ $activeGatewayRecord ? $activeGatewayRecord->name : 'active gateway' }}.
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

    form.addEventListener('submit', async function(e) {
        if (paymentMethodSelect.value !== 'online_gateway' || checkoutSubmitting) {
            return;
        }

        e.preventDefault();

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
});
</script>
@endsection
