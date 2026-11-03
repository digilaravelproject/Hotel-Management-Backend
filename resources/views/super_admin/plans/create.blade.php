@extends('layouts.super_admin')

@section('title', 'Add Pricing Plan - Super Admin')
@section('page_title', 'Create Subscription Plan')

@section('styles')
<style>
    .ott-card {
        border-color: #E2E8F0 !important;
        background-color: #FFFFFF !important;
        transition: all 0.15s ease-in-out !important;
    }
    .ott-card:hover {
        border-color: #CBD5E1 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05) !important;
    }
    .ott-card.is-selected {
        border-color: #150A42 !important;
        background-color: #FAF8FF !important;
        box-shadow: 0 4px 16px rgba(21, 10, 66, 0.08) !important;
    }
    .ott-card.is-selected .ott-check-indicator {
        background-color: #150A42 !important;
        border-color: #150A42 !important;
        color: #FFFFFF !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Navigation & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.plans.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Subscription Plans</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42]">New Tier</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Create Subscription Tier</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Configure software package title, TV fleet ceiling limit, monthly pricing, and authorized streaming apps.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.plans.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <!-- Error Banner -->
    @if(isset($errors) && $errors->any())
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-2 shadow-xs animate-shake">
            <div class="font-bold flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>Please correct the errors below before saving:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 pl-4 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card Form -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-8 lg:p-10 shadow-xs">
        <form action="{{ route('super-admin.plans.store') }}" method="POST" class="space-y-8 sm:space-y-10">
            @csrf

            <!-- Section 1: Per-TV Plan Specifications & Financials -->
            <div class="space-y-6">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 1</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Per-TV Pricing & Tax Configuration</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Define subscription cycle, per-TV base rate, and GST tax application rules.</p>
                        </div>
                    </div>
                </div>

                <!-- Plan Name -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Plan Title / Package Name <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               name="name" 
                               required 
                               value="{{ old('name', 'Hospitality Smart TV Pro') }}" 
                               placeholder="e.g. Monthly Standard Connected TV Plan" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-semibold placeholder:text-slate-400">
                    </div>
                </div>

                <!-- Billing Cycle Selector (Monthly vs Yearly) -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-2">
                        Billing Cycle Frequency <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all hover:border-purple-300 has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/50">
                            <input type="radio" name="billing_cycle" value="monthly" {{ old('billing_cycle', 'monthly') === 'monthly' ? 'checked' : '' }} onchange="calculateFormPreview()" class="sr-only">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0 mr-3">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div>
                                <div class="text-xs sm:text-sm font-extrabold text-slate-900">Monthly Plan</div>
                                <div class="text-[11px] text-slate-500">Billed per Connected TV every 30 days</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all hover:border-purple-300 has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/50">
                            <input type="radio" name="billing_cycle" value="yearly" {{ old('billing_cycle') === 'yearly' ? 'checked' : '' }} onchange="calculateFormPreview()" class="sr-only">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-sm shrink-0 mr-3">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                            <div>
                                <div class="text-xs sm:text-sm font-extrabold text-slate-900">Yearly Plan</div>
                                <div class="text-[11px] text-slate-500">Billed per Connected TV every 365 days</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Price Per TV -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Price Per Connected TV (₹ INR) <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">₹</span>
                            <input type="number" 
                                   id="input_price_per_tv"
                                   name="price_per_tv" 
                                   required 
                                   min="0" 
                                   step="0.01" 
                                   value="{{ old('price_per_tv', 150) }}" 
                                   oninput="calculateFormPreview()"
                                   placeholder="e.g. 150" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-bold placeholder:text-slate-400">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 font-medium">Hotel selects TV count during subscription (e.g. 30 TVs × ₹150 = ₹4,500 base).</p>
                    </div>

                    <!-- Max Room / TV Limit (Optional ceiling) -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Max TV Capacity Allowance <span class="text-xs font-semibold text-slate-400 font-sans">(Safety Ceiling)</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-tv absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="number" 
                                   name="room_count" 
                                   min="1" 
                                   value="{{ old('room_count', 500) }}" 
                                   placeholder="e.g. 500" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-bold placeholder:text-slate-400">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 font-medium">Upper limit of TVs a single hotel property can license under this tier.</p>
                    </div>
                </div>

                <!-- Tax Configuration -->
                @php
                    $defaultTaxId = null;
                    $taxDropdownOptions = ['' => 'No Tax (0%)'];
                    $taxRatesMap = ['' => 0];
                    $taxNamesMap = ['' => 'No Tax'];
                    foreach($taxes as $t) {
                        if ($t->is_default && !$defaultTaxId) {
                            $defaultTaxId = (string) $t->id;
                        }
                        $splitInfo = $t->is_split ? " - {$t->split_rate_1}% {$t->split_name_1} + {$t->split_rate_2}% {$t->split_name_2}" : '';
                        $taxDropdownOptions[(string)$t->id] = "{$t->name} (" . number_format($t->rate, 0) . "%{$splitInfo})";
                        $taxRatesMap[(string)$t->id] = (float) $t->rate;
                        $taxNamesMap[(string)$t->id] = $t->name;
                    }
                    $selectedTaxId = (string) old('tax_id', $defaultTaxId ?? '');
                @endphp
                <div class="p-4 sm:p-5 rounded-2xl bg-[#F8FAFC] border border-slate-200/90 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-shield-halved text-purple-600 text-sm"></i>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">Tax / GST Rules</h3>
                        </div>
                        <a href="{{ route('super-admin.taxes.index') }}" target="_blank" class="text-[11px] font-bold text-purple-700 hover:underline flex items-center space-x-1">
                            <span>Manage Taxes</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <!-- Select Applicable Tax using Global Custom Dropdown -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Select Applicable Tax</label>
                            <x-dropdown 
                                name="tax_id" 
                                id="select_tax_id" 
                                label="No Tax (0%)" 
                                :options="$taxDropdownOptions" 
                                :selected="$selectedTaxId" 
                                wrapClass="w-full" 
                                selectClass="w-full py-2.5 px-3.5 text-xs font-semibold bg-white border-slate-200 hover:border-purple-300" 
                                theme="sa" />
                        </div>

                        <!-- Tax Treatment (Inclusive vs Exclusive) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tax Calculation Mode</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label class="flex items-center justify-center px-3 py-2.5 rounded-xl border cursor-pointer text-xs font-bold transition-all hover:bg-purple-50 has-[:checked]:bg-[#150A42] has-[:checked]:text-white has-[:checked]:border-[#150A42]">
                                    <input type="radio" name="tax_type" value="exclusive" {{ old('tax_type', 'exclusive') === 'exclusive' ? 'checked' : '' }} onchange="calculateFormPreview()" class="sr-only">
                                    <span>Exclusive (+ Tax)</span>
                                </label>
                                <label class="flex items-center justify-center px-3 py-2.5 rounded-xl border cursor-pointer text-xs font-bold transition-all hover:bg-purple-50 has-[:checked]:bg-[#150A42] has-[:checked]:text-white has-[:checked]:border-[#150A42]">
                                    <input type="radio" name="tax_type" value="inclusive" {{ old('tax_type') === 'inclusive' ? 'checked' : '' }} onchange="calculateFormPreview()" class="sr-only">
                                    <span>Inclusive (Tax in Price)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Real-Time Live Preview Calculation Table -->
                    <div class="mt-3 p-3.5 rounded-xl bg-white border border-slate-200/80 space-y-2">
                        <div class="text-[11px] font-extrabold uppercase font-mono tracking-wider text-purple-900 flex flex-wrap items-center justify-between gap-1">
                            <span><i class="fa-solid fa-calculator mr-1"></i> Live TV Pricing Matrix</span>
                            <span id="preview_tax_badge" class="text-[10px] px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold border border-purple-200">18% GST (Exclusive)</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center font-mono">
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-500 font-sans truncate">10 Connected TVs</div>
                                <div id="preview_10" class="text-xs font-extrabold text-[#150A42] mt-0.5 truncate">₹1,770</div>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-500 font-sans truncate">25 Connected TVs</div>
                                <div id="preview_25" class="text-xs font-extrabold text-[#150A42] mt-0.5 truncate">₹4,425</div>
                            </div>
                            <div class="p-2 rounded-lg bg-purple-50 border border-purple-100">
                                <div class="text-[10px] text-purple-700 font-sans font-bold truncate">50 Connected TVs</div>
                                <div id="preview_50" class="text-xs font-black text-[#150A42] mt-0.5 truncate">₹8,850</div>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-500 font-sans truncate">100 Connected TVs</div>
                                <div id="preview_100" class="text-xs font-extrabold text-[#150A42] mt-0.5 truncate">₹17,700</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan Description -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Plan Description & Hotel Entitlements <span class="text-xs font-semibold text-slate-400 font-sans">(Optional)</span>
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              placeholder="Describe hotel amenities, smart launcher features, streaming bundles, and remote management..." 
                              class="w-full px-4 py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400 resize-y">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Included OTT Platforms (With Real Official Icons & Live Edit/Add/Delete) -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 2</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Included OTT Platforms</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Streaming entertainment applications unlocked and pre-installed for this tier.</p>
                        </div>
                    </div>

                    <!-- Right Controls: Sleek Single-Line Toolbar -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <label class="inline-flex items-center space-x-2 px-3 py-2 rounded-xl bg-purple-50/80 border border-purple-200/80 text-xs font-bold text-[#150A42] hover:bg-purple-100/70 cursor-pointer select-none transition-colors shadow-2xs">
                            <input type="checkbox" id="selectAllOtt" class="w-4 h-4 rounded border-slate-300 text-[#150A42] focus:ring-[#150A42]/20 cursor-pointer">
                            <span>Select All</span>
                        </label>

                        <span id="selectedCountBadge" class="px-3 py-2 rounded-xl text-xs font-bold font-mono bg-white text-slate-700 border border-slate-200 shadow-2xs">
                            0 selected
                        </span>

                        <a href="{{ route('super-admin.ott-master.index') }}" 
                           class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5"
                           title="Open Applications Master Catalog">
                            <i class="fa-solid fa-layer-group text-[11px] text-purple-600"></i>
                            <span class="hidden sm:inline">Master Catalog</span>
                        </a>

                        <button type="button" 
                                onclick="openAddOttModal()" 
                                class="btn-sa-primary px-3.5 py-2 text-xs font-bold shadow-xs flex items-center space-x-1.5 cursor-pointer">
                            <i class="fa-solid fa-plus text-[11px]"></i>
                            <span>Add OTT</span>
                        </button>
                    </div>
                </div>

                @php
                    $oldPlatforms = old('ott_platforms', []);
                    if (!is_array($oldPlatforms)) {
                        $oldPlatforms = [];
                    }
                @endphp

                <!-- Real Image Icon Grid with Generous Title Room & Top-Right Checkmarks -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 p-3.5 sm:p-5 bg-[#F8F9FD] border border-slate-200/80 rounded-2xl" id="ottCardsGrid">
                    @foreach($ottPlatforms as $ott)
                        @php
                            $ottId = $ott['id'] ?? 0;
                            $package = $ott['package'] ?? '';
                            $name = $ott['name'] ?? '';
                            $iconSrc = $ott['icon'] ?? null;
                            $isSelected = in_array($package, $oldPlatforms);
                        @endphp
                        <div id="ott-card-wrapper-{{ $ottId ?: Str::slug($package) }}" 
                             class="ott-card group relative p-3.5 sm:p-4 bg-white border-2 border-slate-200/90 rounded-2xl flex items-center space-x-3.5 cursor-pointer hover:border-purple-300 hover:shadow-xs transition-all select-none {{ $isSelected ? 'is-selected' : '' }}"
                             onclick="handleCardClick(event, this)">
                            
                            <!-- Hidden Checkbox -->
                            <input type="checkbox" 
                                   name="ott_platforms[]" 
                                   value="{{ $package }}" 
                                   class="ott-checkbox sr-only" 
                                   {{ $isSelected ? 'checked' : '' }}
                                   onchange="handleOttCheckboxChange(this)">

                            <!-- Real Official Icon Image -->
                            <div class="w-12 h-12 rounded-xl border border-slate-200/80 bg-white p-1.5 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center group-hover:scale-105 group-hover:border-purple-300 transition-all">
                                @if($iconSrc)
                                    <img src="{{ $iconSrc }}" 
                                         alt="{{ $name }}" 
                                         class="w-full h-full object-contain"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden w-full h-full bg-[#150A42] rounded-lg flex items-center justify-center text-white text-xs">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-[#150A42] rounded-lg flex items-center justify-center text-white text-xs">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Platform Details (Generous full-width text) -->
                            <div class="min-w-0 flex-1 pr-7">
                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-[#150A42] transition-colors leading-snug line-clamp-1" title="{{ $name }}">
                                    {{ $name }}
                                </h4>
                                <span class="text-[10px] sm:text-[11px] font-mono text-slate-400 block mt-0.5 truncate" title="{{ $package }}">
                                    {{ $package }}
                                </span>
                            </div>

                            <!-- Top-Right Checkbox Badge -->
                            <div class="absolute top-3.5 right-3.5 shrink-0 pointer-events-none">
                                <div class="ott-check-indicator w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all bg-white group-hover:border-[#150A42]">
                                    <i class="fa-solid fa-check text-[9px] {{ $isSelected ? 'block' : 'hidden' }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sticky-Friendly Bottom Action Bar -->
            <div class="pt-6 sm:pt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
                <a href="{{ route('super-admin.plans.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold text-center transition-all">
                    Discard & Return
                </a>

                <div class="flex items-center space-x-3">
                    <button type="submit" class="btn-sa-primary w-full sm:w-auto px-7 py-3 text-xs sm:text-sm font-bold shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Create Subscription Plan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================== -->
<!-- 1. ADD NEW OTT MODAL (Multipart Form with Icon Upload) -->
<!-- ========================================================== -->
<div id="addOttModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900">Add New OTT / App</h4>
            </div>
            <button type="button" onclick="closeAddOttModal()" class="w-8 h-8 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('super-admin.ott-master.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Application Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. JioCinema / Sony LIV" class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Android Package Name (Play Store ID) <span class="text-rose-500">*</span></label>
                <input type="text" name="package_name" required placeholder="e.g. com.sony.liv" class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-mono">
            </div>

            <!-- Icon Upload Field -->
            <div class="space-y-1.5 pt-1">
                <label class="block text-xs font-bold text-slate-700">App Icon / Logo Image <span class="text-slate-400 font-normal">(PNG, SVG, WebP)</span></label>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-[#F8F9FD] border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                        <img id="addIconPreview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <i id="addIconPlaceholder" class="fa-solid fa-image text-slate-400 text-sm"></i>
                    </div>
                    <input type="file" 
                           name="icon" 
                           accept="image/*,.svg" 
                           onchange="previewOttImage(this, 'addIconPreview', 'addIconPlaceholder')"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#201063] cursor-pointer">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeAddOttModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-xs">Add Application</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================== -->
<!-- 2. EDIT OTT MODAL (Multipart Form with Icon Upload) -->
<!-- ========================================================== -->
<div id="editOttModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900">Edit OTT / Application</h4>
            </div>
            <button type="button" onclick="closeEditOttModal()" class="w-8 h-8 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editOttForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Application Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_ott_name" name="name" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Android Package Name (Play Store ID) <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_ott_package" name="package_name" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-mono">
            </div>

            <!-- Icon Upload Field -->
            <div class="space-y-1.5 pt-1">
                <label class="block text-xs font-bold text-slate-700">Change App Icon / Logo <span class="text-slate-400 font-normal">(leave blank to keep current)</span></label>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-[#F8F9FD] border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                        <img id="editIconPreview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <i id="editIconPlaceholder" class="fa-solid fa-image text-slate-400 text-sm"></i>
                    </div>
                    <input type="file" 
                           name="icon" 
                           accept="image/*,.svg" 
                           onchange="previewOttImage(this, 'editIconPreview', 'editIconPlaceholder')"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#201063] cursor-pointer">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeEditOttModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-xs">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete OTT Form -->
<form id="deleteOttMasterForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
const taxRatesMap = @json($taxRatesMap);
const taxNamesMap = @json($taxNamesMap);

function calculateFormPreview() {
    const pricePerTvInput = document.getElementById('input_price_per_tv');
    const rate = parseFloat(pricePerTvInput ? pricePerTvInput.value : 0) || 0;

    const selectTax = document.getElementById('select_tax_id');
    const selectedTaxId = selectTax ? String(selectTax.value || '') : '';
    const taxRate = selectedTaxId && taxRatesMap[selectedTaxId] !== undefined ? parseFloat(taxRatesMap[selectedTaxId]) : 0;
    const taxName = selectedTaxId && taxNamesMap[selectedTaxId] ? taxNamesMap[selectedTaxId] : 'No Tax';

    const taxTypeEl = document.querySelector('input[name="tax_type"]:checked');
    const taxType = taxTypeEl ? taxTypeEl.value : 'exclusive';

    const badge = document.getElementById('preview_tax_badge');
    if (badge) {
        if (taxRate > 0) {
            badge.textContent = `${taxRate}% ${taxName} (${taxType.charAt(0).toUpperCase() + taxType.slice(1)})`;
            badge.className = `text-[10px] px-2 py-0.5 rounded-md font-bold ${taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200'}`;
        } else {
            badge.textContent = 'No Tax (0%)';
            badge.className = 'text-[10px] px-2 py-0.5 rounded-md font-semibold bg-slate-100 text-slate-600 border border-slate-200';
        }
    }

    const tiers = [10, 25, 50, 100];
    tiers.forEach(tvCount => {
        let total = 0;
        if (taxType === 'inclusive') {
            total = Math.round(tvCount * rate * 100) / 100;
        } else {
            const subtotal = Math.round(tvCount * rate * 100) / 100;
            const taxAmount = Math.round(subtotal * (taxRate / 100) * 100) / 100;
            total = subtotal + taxAmount;
        }
        const el = document.getElementById(`preview_${tvCount}`);
        if (el) el.textContent = '₹' + total.toLocaleString('en-IN');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAllOtt');
    const ottCheckboxes = document.querySelectorAll('.ott-checkbox');
    const countBadge = document.getElementById('selectedCountBadge');

    function syncCardState(cb) {
        const card = cb.closest('.ott-card');
        const indicator = card ? card.querySelector('.ott-check-indicator') : null;
        const icon = indicator ? indicator.querySelector('i') : null;

        if (card) {
            if (cb.checked) {
                card.classList.add('is-selected');
                if (icon) icon.classList.remove('hidden');
            } else {
                card.classList.remove('is-selected');
                if (icon) icon.classList.add('hidden');
            }
        }
    }

    function updateCounterAndSelectAll() {
        const total = ottCheckboxes.length;
        const checkedCount = document.querySelectorAll('.ott-checkbox:checked').length;

        if (countBadge) {
            countBadge.textContent = `${checkedCount} of ${total} selected`;
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (total > 0 && total === checkedCount);
            selectAllCheckbox.indeterminate = (checkedCount > 0 && checkedCount < total);
        }
    }

    window.handleCardClick = function(event, card) {
        if (event.target.closest('button') || event.target.closest('a') || event.target.closest('input')) {
            return;
        }
        const cb = card.querySelector('.ott-checkbox');
        if (cb) {
            cb.checked = !cb.checked;
            syncCardState(cb);
            updateCounterAndSelectAll();
        }
    };

    window.handleOttCheckboxChange = function(cb) {
        syncCardState(cb);
        updateCounterAndSelectAll();
    };

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            ottCheckboxes.forEach(cb => {
                cb.checked = isChecked;
                syncCardState(cb);
            });
            updateCounterAndSelectAll();
        });
    }

    ottCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            syncCardState(this);
            updateCounterAndSelectAll();
        });
        syncCardState(cb);
    });

    const taxSelect = document.getElementById('select_tax_id');
    if (taxSelect) {
        taxSelect.addEventListener('change', calculateFormPreview);
    }

    updateCounterAndSelectAll();
    calculateFormPreview();
});

// Modal Helpers
function openAddOttModal() {
    document.getElementById('addOttModal').classList.remove('hidden');
}
function closeAddOttModal() {
    document.getElementById('addOttModal').classList.add('hidden');
}

function openEditOttModal(event, id, name, package, iconSrc) {
    if (event) event.stopPropagation();
    document.getElementById('editOttForm').action = '/super-admin/ott-master/' + id;
    document.getElementById('edit_ott_name').value = name;
    document.getElementById('edit_ott_package').value = package;

    const preview = document.getElementById('editIconPreview');
    const placeholder = document.getElementById('editIconPlaceholder');
    if (iconSrc) {
        preview.src = iconSrc;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    } else {
        preview.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    }

    document.getElementById('editOttModal').classList.remove('hidden');
}
function closeEditOttModal() {
    document.getElementById('editOttModal').classList.add('hidden');
}

function previewOttImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    if (!input || !preview) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDeleteOtt(event, id, name) {
    if (event) event.stopPropagation();
    Swal.fire({
        title: 'Delete OTT Platform?',
        html: `Are you sure you want to remove <strong>"${name}"</strong> from the master catalog?<br><span class="text-xs text-rose-500 mt-1 block font-semibold">It will no longer appear in subscription plans.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md bg-rose-600',
            cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs bg-slate-200 text-slate-800'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteOttMasterForm');
            form.action = '/super-admin/ott-master/' + id;
            form.submit();
        }
    });
}
</script>
@endsection
