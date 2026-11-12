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

        <div class="space-y-6">
            @foreach($gatewayCodes as $code)
                @php
                    $gateway = $gateways->firstWhere('code', $code);
                    $name = $gateway ? $gateway->name : ucwords($code);
                    $isEnabled = $gateway ? (bool) $gateway->is_enabled : false;
                    $mode = $gateway ? $gateway->mode : 'sandbox';

                    $sandboxKey = $gateway ? $gateway->getSandboxKey() : '';
                    $sandboxSecret = $gateway ? $gateway->getSandboxSecret() : '';
                    $sandboxMerchantId = $gateway ? $gateway->getSandboxMerchantId() : '';

                    $liveKey = $gateway ? $gateway->getLiveKey() : '';
                    $liveSecret = $gateway ? $gateway->getLiveSecret() : '';
                    $liveMerchantId = $gateway ? $gateway->getLiveMerchantId() : '';
                @endphp

                <div class="bg-white border {{ $isEnabled ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-slate-200/80' }} rounded-[26px] p-5 sm:p-7 shadow-xs space-y-6 transition-all" id="gateway-card-{{ $code }}">
                    
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

                    <!-- Display Title & Mode Selector Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/70">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Display Gateway Title <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="gateways[{{ $code }}][name]" 
                                   value="{{ $name }}" 
                                   required
                                   class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700">Environment Mode</label>
                                <span id="mode-badge-{{ $code }}" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full {{ $mode === 'live' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $mode === 'live' ? '● Live Active' : '● Test Active' }}
                                </span>
                            </div>
                            <select name="gateways[{{ $code }}][mode]" 
                                    id="mode-select-{{ $code }}"
                                    onchange="updateModeUI('{{ $code }}', this.value)"
                                    class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                                <option value="sandbox" {{ $mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Test Mode)</option>
                                <option value="live" {{ $mode === 'live' ? 'selected' : '' }}>Live (Production Mode)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dual Credentials Container: Side-by-Side on Desktop -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                        <!-- SECTION 1: SANDBOX CREDENTIALS -->
                        <div id="sandbox-box-{{ $code }}" class="rounded-2xl p-4 sm:p-5 border {{ $mode === 'sandbox' ? 'border-amber-300 bg-amber-50/30 ring-2 ring-amber-400/10' : 'border-slate-200/90 bg-white' }} space-y-3.5 transition-all">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div class="flex items-center space-x-2">
                                    <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-flask"></i>
                                    </span>
                                    <h4 class="text-xs font-black text-slate-800">Sandbox (Test Mode) Keys</h4>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="clearKeys('sandbox', '{{ $code }}')" 
                                            title="Clear Sandbox Keys"
                                            class="text-[10px] font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200/60 transition-all flex items-center space-x-1 cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-[9px]"></i>
                                        <span>Clear</span>
                                    </button>
                                    <span id="sandbox-status-{{ $code }}" class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $mode === 'sandbox' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $mode === 'sandbox' ? 'CURRENTLY ACTIVE' : 'STANDBY' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Sandbox Key / Merchant Key</label>
                                <input type="text" 
                                       name="gateways[{{ $code }}][sandbox_key]" 
                                       value="{{ $sandboxKey }}" 
                                       autocomplete="off"
                                       placeholder="{{ $code === 'razorpay' ? 'rzp_test_...' : 'Sandbox API Key' }}" 
                                       class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Sandbox Secret / Salt</label>
                                <div class="relative">
                                    <input type="hidden" name="gateways[{{ $code }}][clear_sandbox_secret]" id="clear-sandbox-secret-{{ $code }}" value="0">
                                    <input type="password" 
                                           id="pwd-sandbox-{{ $code }}"
                                           name="gateways[{{ $code }}][sandbox_secret]" 
                                           value="" 
                                           oninput="const f=document.getElementById('clear-sandbox-secret-{{ $code }}'); if(f && this.value!=='') f.value='0';"
                                           autocomplete="new-password"
                                           placeholder="{{ !empty($sandboxSecret) ? '•••••••••••• (Configured — leave blank to keep)' : 'Enter Sandbox Secret Key' }}" 
                                           class="w-full pl-3.5 pr-10 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                                    <button type="button" onclick="toggleSecret('pwd-sandbox-{{ $code }}', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Sandbox Merchant ID (Optional)</label>
                                <input type="text" 
                                       name="gateways[{{ $code }}][sandbox_merchant_id]" 
                                       value="{{ $sandboxMerchantId }}" 
                                       autocomplete="off"
                                       placeholder="e.g. test_mid_123" 
                                       class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            </div>
                        </div>

                        <!-- SECTION 2: LIVE CREDENTIALS -->
                        <div id="live-box-{{ $code }}" class="rounded-2xl p-4 sm:p-5 border {{ $mode === 'live' ? 'border-emerald-300 bg-emerald-50/30 ring-2 ring-emerald-500/10' : 'border-slate-200/90 bg-white' }} space-y-3.5 transition-all">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div class="flex items-center space-x-2">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </span>
                                    <h4 class="text-xs font-black text-slate-800">Live (Production Mode) Keys</h4>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="clearKeys('live', '{{ $code }}')" 
                                            title="Clear Live Keys"
                                            class="text-[10px] font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200/60 transition-all flex items-center space-x-1 cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-[9px]"></i>
                                        <span>Clear</span>
                                    </button>
                                    <span id="live-status-{{ $code }}" class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $mode === 'live' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $mode === 'live' ? 'CURRENTLY ACTIVE' : 'STANDBY' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Live Key / Merchant Key</label>
                                <input type="text" 
                                       name="gateways[{{ $code }}][live_key]" 
                                       value="{{ $liveKey }}" 
                                       autocomplete="off"
                                       placeholder="{{ $code === 'razorpay' ? 'rzp_live_...' : 'Live API Key' }}" 
                                       class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Live Secret / Encryption Salt</label>
                                <div class="relative">
                                    <input type="hidden" name="gateways[{{ $code }}][clear_live_secret]" id="clear-live-secret-{{ $code }}" value="0">
                                    <input type="password" 
                                           id="pwd-live-{{ $code }}"
                                           name="gateways[{{ $code }}][live_secret]" 
                                           value="" 
                                           oninput="const f=document.getElementById('clear-live-secret-{{ $code }}'); if(f && this.value!=='') f.value='0';"
                                           autocomplete="new-password"
                                           placeholder="{{ !empty($liveSecret) ? '•••••••••••• (Configured — leave blank to keep)' : 'Enter Live Secret Key' }}" 
                                           class="w-full pl-3.5 pr-10 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                    <button type="button" onclick="toggleSecret('pwd-live-{{ $code }}', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-600">Live Merchant ID (Optional)</label>
                                <input type="text" 
                                       name="gateways[{{ $code }}][live_merchant_id]" 
                                       value="{{ $liveMerchantId }}" 
                                       autocomplete="off"
                                       placeholder="e.g. 10293847" 
                                       class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>
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

<script>
    function updateModeUI(code, mode) {
        const sandboxBox = document.getElementById(`sandbox-box-${code}`);
        const liveBox = document.getElementById(`live-box-${code}`);
        const sandboxStatus = document.getElementById(`sandbox-status-${code}`);
        const liveStatus = document.getElementById(`live-status-${code}`);
        const badge = document.getElementById(`mode-badge-${code}`);

        if (mode === 'live') {
            if (badge) {
                badge.className = 'text-[10px] font-mono font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200';
                badge.innerText = '● Live Active';
            }
            if (liveBox) {
                liveBox.className = 'rounded-2xl p-4 sm:p-5 border border-emerald-300 bg-emerald-50/30 ring-2 ring-emerald-500/10 space-y-3.5 transition-all';
            }
            if (sandboxBox) {
                sandboxBox.className = 'rounded-2xl p-4 sm:p-5 border border-slate-200/90 bg-white space-y-3.5 transition-all';
            }
            if (liveStatus) {
                liveStatus.className = 'text-[10px] font-black px-2 py-0.5 rounded-full bg-emerald-600 text-white';
                liveStatus.innerText = 'CURRENTLY ACTIVE';
            }
            if (sandboxStatus) {
                sandboxStatus.className = 'text-[10px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-500';
                sandboxStatus.innerText = 'STANDBY';
            }
        } else {
            if (badge) {
                badge.className = 'text-[10px] font-mono font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200';
                badge.innerText = '● Test Active';
            }
            if (sandboxBox) {
                sandboxBox.className = 'rounded-2xl p-4 sm:p-5 border border-amber-300 bg-amber-50/30 ring-2 ring-amber-400/10 space-y-3.5 transition-all';
            }
            if (liveBox) {
                liveBox.className = 'rounded-2xl p-4 sm:p-5 border border-slate-200/90 bg-white space-y-3.5 transition-all';
            }
            if (sandboxStatus) {
                sandboxStatus.className = 'text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-500 text-white';
                sandboxStatus.innerText = 'CURRENTLY ACTIVE';
            }
            if (liveStatus) {
                liveStatus.className = 'text-[10px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-500';
                liveStatus.innerText = 'STANDBY';
            }
        }
    }

    function toggleSecret(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }
    function clearKeys(type, code) {
        const keyInput = document.querySelector(`input[name="gateways[${code}][${type}_key]"]`);
        const secretInput = document.querySelector(`input[name="gateways[${code}][${type}_secret]"]`);
        const midInput = document.querySelector(`input[name="gateways[${code}][${type}_merchant_id]"]`);
        const clearFlag = document.getElementById(`clear-${type}-secret-${code}`);
        if (clearFlag) {
            clearFlag.value = '1';
        }
        if (keyInput) {
            keyInput.value = '';
            keyInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (secretInput) {
            secretInput.value = '';
            secretInput.placeholder = 'Secret marked for removal on Save';
            secretInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (midInput) {
            midInput.value = '';
            midInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
</script>
    </form>
</div>
@endsection
