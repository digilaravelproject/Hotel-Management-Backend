@extends('layouts.super_admin')

@section('title', 'Global Commission Settings - Super Admin')
@section('page_title', 'Commission Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-100 text-[#150A42] text-xs font-bold font-mono">
                    <i class="fa-solid fa-percent text-purple-600"></i>
                    <span>DISTRIBUTOR REVENUE SHARE</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Global Commission Settings</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-xl">
                    Configure the default commission percentage rewarded to distributors when they onboard new hotels or sell package renewals. Individual distributor overrides take precedence over this global rate.
                </p>
            </div>
            <div class="shrink-0 p-4 bg-purple-50/70 border border-purple-100 rounded-2xl flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-white border border-purple-100 flex items-center justify-center text-[#150A42] text-xl font-bold shadow-xs">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider font-mono">Current Default</div>
                    <div class="text-xl font-extrabold text-slate-900 font-mono">{{ number_format($setting->default_percentage ?? 10.00, 2) }}%</div>
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
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
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
    <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        <form action="{{ route('super-admin.commissions.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="default_percentage" class="block text-sm font-bold text-slate-800">
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
                           class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-200 text-slate-900 font-bold font-mono focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all text-base">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    This global percentage applies to any distributor whose personal commission override is not specifically set.
                </p>
            </div>

            <!-- Precedence Info Card -->
            <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 text-xs space-y-2 text-slate-600">
                <div class="font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-[#150A42]"></i>
                    <span>Commission Hierarchy Rule</span>
                </div>
                <p class="leading-relaxed">
                    1. <strong>Individual Override:</strong> Configured in <em>Super Admin &rarr; Staff & Partners &rarr; Edit User</em>. Takes highest priority.<br>
                    2. <strong>Global Fallback:</strong> Used automatically if individual commission is left blank.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="btn-sa-primary px-6 py-2.5 text-xs shadow-md flex items-center space-x-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Commission Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection