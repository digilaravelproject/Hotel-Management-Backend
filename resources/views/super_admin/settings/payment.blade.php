@extends('layouts.super_admin')

@section('title', 'Payment Gateway Settings - Super Admin')
@section('page_title', 'Payment Gateway Settings')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Smooth responsive on Mobile -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Payment Gateway Settings</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Configure multi-gateway credentials (ICICI, Razorpay, PayU) and toggle the primary payment gateway for hotel subscription billing.</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-0.5 w-full sm:w-auto">
            <a href="{{ route('super-admin.settings.index') }}" 
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                <span>Platform Settings</span>
            </a>

            <button type="submit" 
                    form="gatewaySettingsForm" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Gateway Settings</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Alert (Success is handled globally by layout) -->
    @if($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Stats Metric Cards (2x2 on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- 1. Active Primary Gateway -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-emerald-600 tracking-tight leading-tight truncate">
                    {{ strtoupper($activeGateway->name ?? $activeGateway->code ?? 'ICICI') }}
                </div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Active Primary Gateway</div>
            </div>
        </div>

        <!-- 2. Active Mode / Environment -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ ($activeGateway && $activeGateway->mode === 'live') ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-server"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight capitalize">
                    {{ $activeGateway ? ($activeGateway->mode === 'live' ? 'Live (Prod)' : 'Sandbox (Test)') : 'Sandbox' }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Environment Mode</div>
            </div>
        </div>

        <!-- 3. Available Gateways -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">3</div>
                <div class="text-[10px] sm:text-xs font-semibold text-purple-700 truncate mt-0.5">ICICI, Razorpay, PayU</div>
            </div>
        </div>

        <!-- 4. Security Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="min-w-0">
                <div class="text-lg sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">Encrypted</div>
                <div class="text-[10px] sm:text-xs font-semibold text-emerald-600 truncate mt-0.5">API Secrets Masked</div>
            </div>
        </div>
    </div>

    <!-- Gateway Settings Form -->
    <form id="gatewaySettingsForm" action="{{ route('super-admin.payment-settings.update') }}" method="POST" class="space-y-6">
        @csrf

        @php
            $gatewayCodes = ['icici', 'razorpay', 'payu'];
        @endphp

        <div class="space-y-5">
            @foreach($gatewayCodes as $code)
                @php
                    $gateway = $gateways->firstWhere('code', $code);
                    $name = $gateway ? $gateway->name : ucwords($code);
                    $isEnabled = $gateway ? (bool) $gateway->is_enabled : false;
                    $mode = $gateway ? $gateway->mode : 'sandbox';
                    $apiKey = $gateway ? $gateway->api_key : '';
                    $apiSecret = $gateway ? $gateway->api_secret : '';
                    $merchantId = $gateway ? $gateway->merchant_id : '';
                @endphp

                <div class="bg-white border {{ $isEnabled ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-slate-200/80' }} rounded-[26px] p-5 sm:p-7 shadow-xs space-y-5 transition-all">
                    
                    <!-- Gateway Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-11 h-11 rounded-2xl {{ $isEnabled ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-purple-50 text-[#150A42] border border-purple-100' }} flex items-center justify-center shadow-2xs font-extrabold text-base shrink-0">
                                @switch($code)
                                    @case('icici')<i class="fa-solid fa-building-columns"></i>@break
                                    @case('razorpay')<i class="fa-solid fa-bolt"></i>@break
                                    @case('payu')<i class="fa-solid fa-indian-rupee-sign"></i>@break
                                    @default<i class="fa-solid fa-money-check-dollar"></i>
                                @endswitch
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-extrabold text-slate-900">{{ $name }}</h3>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 uppercase">
                                        {{ $code }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">
                                    @switch($code)
                                        @case('icici') Direct corporate netbanking, UPI, and card processing via ICICI Eazypay. @break
                                        @case('razorpay') Automated subscription mandates, UPI QR, and instant checkout. @break
                                        @case('payu') Enterprise multi-channel gateway with instant settlement. @break
                                    @endswitch
                                </p>
                            </div>
                        </div>

                        <!-- Active Radio / Checkbox Selector -->
                        <div class="flex items-center space-x-2 shrink-0">
                            <label class="relative inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-xl border {{ $isEnabled ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-slate-50 text-slate-600' }} cursor-pointer select-none transition-all">
                                <input type="hidden" name="gateways[{{ $code }}][is_enabled]" value="0">
                                <input type="checkbox" 
                                       name="gateways[{{ $code }}][is_enabled]" 
                                       value="1" 
                                       {{ $isEnabled ? 'checked' : '' }} 
                                       class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20">
                                <span class="text-xs font-extrabold">
                                    {{ $isEnabled ? 'Active Primary Gateway' : 'Set as Active' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Configuration Fields Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Display Gateway Title <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="gateways[{{ $code }}][name]" 
                                   value="{{ $name }}" 
                                   required
                                   class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Environment Mode</label>
                            <select name="gateways[{{ $code }}][mode]" 
                                    class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                                <option value="sandbox" {{ $mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Test Mode)</option>
                                <option value="live" {{ $mode === 'live' ? 'selected' : '' }}>Live (Production Mode)</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">API Key / Merchant Key</label>
                            <input type="text" 
                                   name="gateways[{{ $code }}][api_key]" 
                                   value="{{ $apiKey }}" 
                                   placeholder="e.g. rzp_live_... or key_..." 
                                   class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">API Secret / Encryption Salt</label>
                            <input type="password" 
                                   name="gateways[{{ $code }}][api_secret]" 
                                   value="{{ $apiSecret }}" 
                                   placeholder="••••••••••••••••" 
                                   class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Merchant ID / Sub-Merchant Code (Optional)</label>
                            <input type="text" 
                                   name="gateways[{{ $code }}][merchant_id]" 
                                   value="{{ $merchantId }}" 
                                   placeholder="e.g. 10293847" 
                                   class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Submit Footer -->
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
            <a href="{{ route('super-admin.dashboard') }}" 
               class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all text-center">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Gateway Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection
