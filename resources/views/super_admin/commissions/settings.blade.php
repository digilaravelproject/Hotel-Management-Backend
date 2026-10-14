@extends('layouts.super_admin')

@section('title', 'Global Commission Settings - Super Admin')
@section('page_title', 'Commission Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Card (Pure White with Soft Gold Tint highlight) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E2E8F0] shadow-sm relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#FBF8F1] border border-[#D4AF37]/30 text-[#0F2537] text-xs font-bold">
                    <i class="fa-solid fa-percent text-[#D4AF37]"></i>
                    <span>Distributor Revenue Share</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B] tracking-tight">Global Commission Settings</h2>
                <p class="text-xs sm:text-sm text-[#64748B] max-w-xl">
                    Configure the default commission percentage rewarded to distributors when they onboard new hotels or sell package renewals. Individual distributor overrides take precedence over this global rate.
                </p>
            </div>
            <div class="shrink-0 p-4 bg-[#FBF8F1] border border-[#D4AF37]/20 rounded-2xl flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-white border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] text-xl font-bold">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <div class="text-[11px] text-[#64748B] uppercase font-bold tracking-wider">Current Default</div>
                    <div class="text-xl font-extrabold text-[#1E293B]">{{ number_format($setting->default_percentage ?? 10.00, 2) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-[#DCFCE7] border border-[#16A34A]/20 text-[#16A34A] text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs space-y-1 shadow-xs">
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
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E2E8F0] shadow-sm">
        <form action="{{ route('super-admin.commissions.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="default_percentage" class="block text-sm font-bold text-[#1E293B]">
                    Default Commission Percentage (%) <span class="text-[#DC2626]">*</span>
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
                           class="w-full px-4 py-3 pr-10 rounded-2xl border border-[#E2E8F0] text-[#1E293B] font-semibold focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/30 focus:border-[#D4AF37] transition-all text-base">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#64748B] font-bold">%</span>
                </div>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    This global percentage applies to any distributor whose personal commission override is not specifically set.
                </p>
            </div>

            <!-- Precedence Info Card -->
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs space-y-2 text-[#64748B]">
                <div class="font-bold text-[#1E293B] flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-[#D4AF37]"></i>
                    <span>Commission Hierarchy Rule</span>
                </div>
                <p>
                    1. <strong>Individual Override:</strong> Configured in <em>Super Admin &rarr; Users &rarr; Edit User</em>. Takes highest priority.<br>
                    2. <strong>Global Fallback:</strong> Used automatically if individual commission is left blank.
                </p>
            </div>

            <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end">
                <button type="submit" 
                        class="px-6 py-3 rounded-2xl bg-[#D4AF37] hover:bg-[#B5952F] text-[#0F2537] font-bold text-sm shadow-md shadow-[#D4AF37]/20 transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
