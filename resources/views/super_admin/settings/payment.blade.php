@extends('layouts.super_admin')

@section('title', 'Payment Gateway Settings - Super Admin')
@section('page_title', 'Payment Gateway Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-credit-card text-lg"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Active Gateway Configuration</h2>
                <p class="text-xs text-slate-500">Only the enabled gateway will process payments. ICICI remains fully intact.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2"><i class="fa-solid fa-triangle-exclamation text-rose-600"></i><span>Fix errors below</span></div>
            <ul class="list-disc pl-6 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('super-admin.payment-settings.update') }}" method="POST" class="space-y-8">
            @csrf

            @php
                $gatewayCodes = ['icici', 'razorpay', 'payu'];
            @endphp

            @foreach($gatewayCodes as $code)
                @php
                    $gateway = $gateways->firstWhere('code', $code);
                    $name = $gateway ? $gateway->name : ucwords($code);
                    $isEnabled = $gateway ? $gateway->is_enabled : false;
                    $mode = $gateway ? $gateway->mode : 'sandbox';
                    $apiKey = $gateway ? $gateway->api_key : '';
                    $apiSecret = $gateway ? $gateway->api_secret : '';
                    $merchantId = $gateway ? $gateway->merchant_id : '';
                @endphp

                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                    <div class="bg-gradient-to-r from-slate-50 via-white to-slate-50 px-5 py-4 border-b border-slate-200/60 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-[#D4AF37] flex items-center justify-center shadow-xs font-extrabold text-sm">
                                @switch($code)
                                    @case('icici')<i class="fa-solid fa-building-columns"></i>@break
                                    @case('razorpay')<i class="fa-solid fa-bolt"></i>@break
                                    @case('payu')<i class="fa-solid fa-indian-rupee-sign"></i>@break
                                    @default<i class="fa-solid fa-money-check-dollar"></i>
                                @endswitch
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">{{ $name }}</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Code: <span class="font-mono text-slate-500">{{ $code }}</span></p>
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="hidden" name="gateways[{{ $code }}][is_enabled]" value="0">
                            <input type="checkbox" name="gateways[{{ $code }}][is_enabled]" value="1" {{ $isEnabled ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#D4AF37] focus:ring-[#D4AF37]/30">
                            <span class="text-xs font-extrabold text-slate-700">Active</span>
                        </label>
                    </div>

                    <div class="p-5 space-y-4 bg-white">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name_{{ $code }}" class="text-xs font-bold text-slate-700 mb-1.5 block">Gateway Name</label>
                                <input id="name_{{ $code }}" type="text" name="gateways[{{ $code }}][name]" value="{{ $name }}" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 bg-slate-50/50" required>
                            </div>
                            <div>
                                <label for="mode_{{ $code }}" class="text-xs font-bold text-slate-700 mb-1.5 block">Environment</label>
                                <select id="mode_{{ $code }}" name="gateways[{{ $code }}][mode]" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                    <option value="sandbox" {{ $mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                    <option value="live" {{ $mode === 'live' ? 'selected' : '' }}>Live (Production)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="api_key_{{ $code }}" class="text-xs font-bold text-slate-700 mb-1.5 block">API Key</label>
                            <input id="api_key_{{ $code }}" type="text" name="gateways[{{ $code }}][api_key]" value="{{ $apiKey }}" placeholder="e.g. rzp_test_..." class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 bg-slate-50/50 font-mono">
                        </div>
                        <div>
                            <label for="api_secret_{{ $code }}" class="text-xs font-bold text-slate-700 mb-1.5 block">API Secret / Salt</label>
                            <input id="api_secret_{{ $code }}" type="text" name="gateways[{{ $code }}][api_secret]" value="{{ $apiSecret }}" placeholder="e.g. salt..." class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 bg-slate-50/50 font-mono">
                        </div>
                        <div>
                            <label for="merchant_id_{{ $code }}" class="text-xs font-bold text-slate-700 mb-1.5 block">Merchant ID (Optional)</label>
                            <input id="merchant_id_{{ $code }}" type="text" name="gateways[{{ $code }}][merchant_id]" value="{{ $merchantId }}" placeholder="e.g. M1234567" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 bg-slate-50/50 font-mono">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 text-xs font-bold transition-all shadow-xs">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#D4AF37] hover:bg-[#B5952F] text-[#0F2537] font-bold text-xs shadow-md shadow-[#D4AF37]/20 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Gateway Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
