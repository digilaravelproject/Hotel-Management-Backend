@extends('layouts.distributor')

@section('title', 'Sell Package - Distributor Hub')
@section('page_title', 'Sell Subscription Package')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('distributor.sales.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Back to Sales Ledger</span>
        </a>
    </div>

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
                            <select name="hotel_id" id="hotelSelect" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50 text-slate-800">
                                <option value="">-- Choose Hotel --</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ (old('hotel_id', $selectedHotelId) == $hotel->id) ? 'selected' : '' }} data-rooms="{{ $hotel->room_count }}" data-plan="{{ $hotel->plan ? $hotel->plan->name : 'None' }}" data-expiry="{{ $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') : 'None' }}">
                                        {{ $hotel->hotel_name }} ({{ $hotel->room_count }} Rooms • {{ $hotel->hotel_location }})
                                    </option>
                                @endforeach
                            </select>
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
                            <select name="duration_months" id="durationSelect" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50 text-slate-800">
                                <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>1 Month (Standard)</option>
                                <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 Months (Quarterly)</option>
                                <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 Months (Half-Yearly)</option>
                                <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>12 Months (Annual)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Total Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amountInput" value="{{ old('amount') }}" required class="w-full px-3.5 py-2.5 text-xs font-extrabold text-slate-900 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Payment Method <span class="text-rose-500">*</span></label>
                            <select name="payment_method" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50 text-slate-800">
                                <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI / QR Transfer</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank NEFT / RTGS / IMPS</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash Payment</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque / DD</option>
                                <option value="online_gateway" {{ old('payment_method') == 'online_gateway' ? 'selected' : '' }}>Online Card / Gateway</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Transaction Ref / Notes</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="e.g. UTR #123456 or Inv #09" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary / Checkout Card (1 column) -->
            <div class="space-y-6">
                <div class="bg-gradient-to-b from-slate-900 to-slate-950 text-white rounded-3xl p-6 border border-slate-800 shadow-xl space-y-6 sticky top-28">
                    <div class="border-b border-slate-800 pb-4">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-amber-400">Order Summary</h4>
                        <div class="text-xl font-black mt-1">Package Activation</div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-800">
                            <span class="text-slate-400">Package:</span>
                            <span id="summaryPlanName" class="font-bold text-white">Select plan</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-800">
                            <span class="text-slate-400">Monthly Rate:</span>
                            <span id="summaryPlanRate" class="font-bold text-white">₹0</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-800">
                            <span class="text-slate-400">Duration:</span>
                            <span id="summaryDuration" class="font-bold text-white">1 Month</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm pt-2">
                            <span class="text-slate-300 font-bold">Total Payable:</span>
                            <span id="summaryTotal" class="font-black text-amber-400 text-lg">₹0</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-300 space-y-1">
                        <div class="font-bold flex items-center space-x-1.5">
                            <i class="fa-solid fa-bolt text-amber-400"></i>
                            <span>Instant TV License Extension</span>
                        </div>
                        <p class="text-slate-300 text-[10px] leading-relaxed">Submitting this form immediately activates the plan, updates room limits, and records this sale under your distributor ledger.</p>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Confirm Sale & Activate License</span>
                    </button>
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
        }
    }

    planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
    if (durationSelect) durationSelect.addEventListener('change', calculateTotal);
    calculateTotal();
});
</script>
@endsection
