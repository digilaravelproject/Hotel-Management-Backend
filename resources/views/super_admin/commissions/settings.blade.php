@extends('layouts.super_admin')

@section('title', 'Global Commission Settings - Super Admin')
@section('page_title', 'Commission Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -top-10 right-20 w-48 h-48 bg-emerald-600/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">
                    <i class="fa-solid fa-percent"></i>
                    <span>Distributor Revenue Share</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Global Commission Settings</h2>
                <p class="text-xs sm:text-sm text-slate-400 max-w-xl">
                    Configure the default commission percentage rewarded to distributors when they onboard new hotels or sell package renewals. Individual distributor overrides take precedence over this global rate.
                </p>
            </div>
            <div class="shrink-0 p-4 bg-white/5 border border-white/10 rounded-2xl flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl font-bold">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <div class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Current Default</div>
                    <div class="text-xl font-extrabold text-white">{{ number_format($setting->default_percentage ?? 10.00, 2) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600 shrink-0"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('super-admin.commissions.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="default_percentage" class="block text-sm font-bold text-slate-900">
                    Default Commission Percentage (%) <span class="text-rose-500">*</span>
                </label>
                <div class="relative max-w-xs">
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           max="100" 
                           id="default_percentage" 
                           name="default_percentage" 
                           value="{{ old('default_percentage', $setting->default_percentage ?? 10.00) }}" 
                           required
                           class="w-full px-4 py-3 pr-10 rounded-2xl border border-slate-200 text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-base">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    This global percentage applies to any distributor whose personal commission override is not specifically set.
                </p>
            </div>

            <!-- Precedence Info Card -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs space-y-2 text-slate-600">
                <div class="font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-indigo-600"></i>
                    <span>Commission Hierarchy Rule</span>
                </div>
                <p>
                    1. <strong>Individual Override:</strong> Configured in <em>Super Admin &rarr; Users &rarr; Edit User</em>. Takes highest priority.<br>
                    2. <strong>Global Fallback:</strong> Used automatically if individual commission is left blank.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
