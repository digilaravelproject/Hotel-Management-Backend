@extends('layouts.super_admin')

@section('title', 'Subscription Checkout - ' . $hotel->hotel_name)
@section('page_title', 'Subscription Settlement & TV Authorization')

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
                <span class="text-[#150A42]">Subscription Checkout</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Authorize & Settle Subscription</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Generate TV pairing license key and authorize in-room streaming devices.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-regular fa-pen-to-square text-xs text-purple-600"></i>
                <span>Edit Details</span>
            </a>
            <a href="{{ route('super-admin.hotels.index') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <!-- Main Checkout Container Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Hotel & Order Summary (7 Cols) -->
        <div class="lg:col-span-7 space-y-5">
            <!-- Property Card -->
            <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center space-x-3.5 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg shrink-0 shadow-xs">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Billed Hotel Client</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 truncate">{{ $hotel->hotel_name }}</h2>
                        <div class="text-xs text-slate-500 font-medium flex items-center mt-0.5">
                            <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[10px]"></i>
                            <span>{{ $hotel->city ?? $hotel->hotel_location }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider">Owner Contact</span>
                        <div class="font-bold text-slate-800">{{ $hotel->owner_name }}</div>
                        <div class="text-slate-500 font-mono text-[11px]">{{ $hotel->phone }}</div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider">Channel Partner</span>
                        <div class="font-bold text-slate-800">
                            {{ $hotel->distributor ? $hotel->distributor->name : 'Direct Enterprise' }}
                        </div>
                        <div class="text-slate-400 text-[11px]">Pax TV Hospitality Network</div>
                    </div>
                </div>
            </div>

            <!-- Plan Itemization Card -->
            <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">{{ $hotel->plan->name }}</h3>
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100">
                                {{ strtoupper($pricing['billing_cycle']) }} BILLING
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-400 block font-mono">Unit Rate</span>
                        <span class="text-sm font-extrabold text-slate-900 font-mono">₹{{ number_format($pricing['price_per_tv'], 2) }}</span>
                        <span class="text-[10px] text-slate-400 block">/ TV / {{ $pricing['billing_cycle'] === 'yearly' ? 'yr' : 'mo' }}</span>
                    </div>
                </div>

                <!-- TVs Calculation Line -->
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#F8F9FD] border border-slate-200/70 text-xs">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 font-bold">
                            <i class="fa-solid fa-tv text-xs"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-slate-900 text-sm font-mono">{{ $tvCount }} Connected TVs</span>
                            <span class="text-[11px] text-slate-400 block">Hardware device pairing slots</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-mono text-sm font-extrabold text-slate-900">
                            {{ $tvCount }} × ₹{{ number_format($pricing['price_per_tv'], 2) }}
                        </span>
                    </div>
                </div>

                <!-- Tax & Price Breakdown Table -->
                <div class="space-y-2 pt-1 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Base Subtotal:</span>
                        <span class="font-bold font-mono text-slate-900">₹{{ number_format($pricing['subtotal'], 2) }}</span>
                    </div>

                    @if(!empty($pricing['tax_rate']) && $pricing['tax_rate'] > 0)
                        <div class="flex items-center justify-between text-slate-600">
                            <span>
                                {{ $pricing['tax_name'] }} ({{ number_format($pricing['tax_rate'], 1) }}% - {{ ucfirst($pricing['tax_type']) }}):
                            </span>
                            <span class="font-bold font-mono text-slate-900">+ ₹{{ number_format($pricing['tax_amount'], 2) }}</span>
                        </div>
                        @if(!empty($pricing['cgst_amount']) && $pricing['cgst_amount'] > 0)
                            <div class="flex items-center justify-between text-[11px] text-slate-400 pl-3">
                                <span>CGST ({{ number_format($pricing['tax_rate']/2, 1) }}%):</span>
                                <span class="font-mono">₹{{ number_format($pricing['cgst_amount'], 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 pl-3">
                                <span>SGST ({{ number_format($pricing['tax_rate']/2, 1) }}%):</span>
                                <span class="font-mono">₹{{ number_format($pricing['sgst_amount'], 2) }}</span>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-between text-slate-400 text-[11px]">
                            <span>Tax Assessment:</span>
                            <span class="font-mono">0.00 (Zero Rated / No Tax)</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-slate-900">
                        <div>
                            <span class="text-sm font-black">Total Amount Payable:</span>
                            <span class="text-[10px] text-slate-400 block font-medium">All statutory taxes and platform pairings included</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl sm:text-2xl font-black text-[#150A42] font-mono tracking-tight">
                                ₹{{ number_format($pricing['total_amount'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settlement & Payment Action (5 Cols) -->
        <div class="lg:col-span-5 space-y-5">
            <div class="bg-gradient-to-br from-[#150A42] to-[#251068] text-white rounded-[26px] p-6 shadow-xl space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-amber-400 text-base">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-white/70 uppercase tracking-wider font-mono">Secure Settlement</span>
                        <h3 class="text-base font-extrabold tracking-tight">Super Admin Checkout</h3>
                    </div>
                </div>

                <p class="text-xs text-white/80 leading-relaxed">
                    Completing this checkout will record payment confirmation, auto-issue a fresh 16-character TV pairing license key, generate a tax-compliant invoice, and unlock streaming access for <strong class="text-white underline">{{ $tvCount }} connected TVs</strong>.
                </p>

                <form action="{{ route('super-admin.hotels.pay', $hotel->id) }}" method="POST" class="space-y-4 pt-2">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-white/90 mb-1.5">Payment Method Channel</label>
                        <select name="payment_method" class="w-full px-3.5 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400/50">
                            <option value="super_admin_override" class="text-slate-900">Direct Admin Authorized Settlement</option>
                            <option value="razorpay_gateway" class="text-slate-900">Razorpay Payment Gateway</option>
                            <option value="bank_neft_rtgs" class="text-slate-900">Bank Transfer / NEFT / RTGS</option>
                            <option value="upi_direct" class="text-slate-900">UPI Instant Direct Payment</option>
                            <option value="cheque_settlement" class="text-slate-900">Physical Cheque Settlement</option>
                        </select>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 text-xs space-y-1">
                        <div class="flex items-center justify-between text-white/80">
                            <span>Authorized Devices:</span>
                            <span class="font-bold font-mono text-white">{{ $tvCount }} Smart TVs</span>
                        </div>
                        <div class="flex items-center justify-between text-white/80">
                            <span>License Duration:</span>
                            <span class="font-bold font-mono text-white">
                                {{ $pricing['billing_cycle'] === 'yearly' ? '365 Days (1 Year)' : '30 Days (1 Month)' }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-5 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm transition-all shadow-lg shadow-amber-400/25 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span>Confirm & Settle ₹{{ number_format($pricing['total_amount'], 2) }}</span>
                    </button>
                </form>

                <div class="pt-2 text-center">
                    <span class="text-[10px] text-white/60">
                        <i class="fa-solid fa-lock mr-1"></i> End-to-end encrypted • Instant license allocation
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

