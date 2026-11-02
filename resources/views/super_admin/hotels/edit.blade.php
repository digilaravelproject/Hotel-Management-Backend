@extends('layouts.super_admin')

@section('title', 'Edit Hotel Vendor - Super Admin')
@section('page_title', 'Modify Hotel Vendor')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Navigation & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.hotels.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Hotels Directory</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42]">Edit Property</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $hotel->hotel_name }}</h1>
                <span class="px-2.5 py-1 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 font-mono text-xs font-bold">
                    ID: #{{ $hotel->id }}
                </span>
                @if($hotel->license_key)
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-xl bg-[#F8F9FD] text-slate-700 border border-slate-200 font-mono text-xs font-bold">
                        <i class="fa-solid fa-key text-[10px] text-purple-600"></i>
                        <span class="select-all">{{ $hotel->license_key }}</span>
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Manage administrative credentials, licensing tier, distributor assignment, and media assets.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-regular fa-eye text-xs"></i>
                <span>View Profile</span>
            </a>
            <a href="{{ route('super-admin.hotels.index') }}" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
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
                <span>Please fix the following validation errors before saving:</span>
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
        <form action="{{ route('super-admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 sm:space-y-10">
            @csrf
            @method('PUT')

            <!-- Section 1: Owner Profile & Credentials -->
            <div class="space-y-5">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 1</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Owner Profile & Credentials</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage administrator profile and reset access credentials.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Owner Full Name -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Owner Full Name <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="owner_name" 
                                   value="{{ old('owner_name', $hotel->owner_name) }}" 
                                   required 
                                   placeholder="e.g. Rajesh Sharma" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Email Address <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $hotel->email) }}" 
                                   required 
                                   placeholder="e.g. admin@grandhotel.com" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Phone Number <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone', $hotel->phone) }}" 
                                   required 
                                   placeholder="e.g. +91 98765 43210" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-medium placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Account Password Field -->
                <div class="max-w-md pt-1">
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Change Password <span class="text-xs font-semibold text-slate-400 font-sans">(leave blank to keep current)</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" 
                               id="passwordInput" 
                               name="password" 
                               placeholder="Enter new password (optional)" 
                               class="w-full pl-10 pr-11 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 focus:outline-none p-1 cursor-pointer transition-colors"
                                title="Toggle visibility">
                            <i id="passwordEyeIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 font-medium">Leave this input empty if you do not want to alter the existing account password.</p>
                </div>
            </div>

            <!-- Section 2: Hotel Identity & Property Details -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 2</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Hotel Identity & Property Details</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Establish property name, geography, and in-room greeting details.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Hotel Name -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Hotel / Property Name <span class="text-rose-500 font-black">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="hotel_name" 
                                   value="{{ old('hotel_name', $hotel->hotel_name) }}" 
                                   required 
                                   placeholder="e.g. The Oberoi Grand View & Suites" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            City / Destination
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="city" 
                                   value="{{ old('city', $hotel->city) }}" 
                                   placeholder="e.g. Mumbai" 
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Hotel Physical Address <span class="text-rose-500 font-black">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               name="hotel_location" 
                               value="{{ old('hotel_location', $hotel->hotel_location) }}" 
                               required 
                               placeholder="e.g. Bandra Kurla Complex, Bandra East, Mumbai, Maharashtra 400051" 
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400">
                    </div>
                </div>

                <!-- Welcome Description / Tagline -->
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Welcome Greeting / TV Header Tagline
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              placeholder="e.g. Welcome to The Oberoi Grand View. Experience premier comfort, in-room gourmet dining, and smart entertainment." 
                              class="w-full px-4 py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-medium placeholder:text-slate-400 resize-y">{{ old('description', $hotel->description) }}</textarea>
                    <p class="text-xs text-slate-400 mt-1.5 font-medium">Broadcasted on the in-room TV home banner as a personalized greeting message.</p>
                </div>
            </div>

            <!-- Section 3: Subscription, Partner & TV Settings -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 3</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Subscription Plan & Channel Partner</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage licensing package, distributor association, and access duration.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <!-- 1. Subscription Plan Custom Dropdown -->
                    @php
                        $selectedPlanId = old('plan_id', $hotel->plan_id);
                    @endphp
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Subscription Tier
                        </label>
                        <x-dropdown name="plan_id" label="None (Custom / Trial Package)" :selected="$selectedPlanId" theme="sa">
                            <option value="">None (Custom / Trial Package)</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ (string)$plan->id === (string)$selectedPlanId ? 'selected' : '' }}>
                                    {{ $plan->name }} (Up to {{ $plan->room_count }} Rooms • ₹{{ number_format($plan->price, 0) }}/mo)
                                </option>
                            @endforeach
                        </x-dropdown>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">Current registered capacity: <span class="font-bold text-slate-800 font-mono">{{ $hotel->room_count }} rooms</span>.</p>
                    </div>

                    <!-- 2. Distributor Partner Custom Dropdown -->
                    @php
                        $selectedDistId = old('distributor_id', $hotel->distributor_id);
                    @endphp
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Assigned Distributor / Partner
                        </label>
                        <x-dropdown name="distributor_id" label="Direct Enterprise (No Distributor)" :selected="$selectedDistId" theme="sa">
                            <option value="">Direct Enterprise (No Distributor)</option>
                            @if(isset($distributors))
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ (string)$distributor->id === (string)$selectedDistId ? 'selected' : '' }}>
                                        {{ $distributor->name }} — {{ $distributor->email }}
                                    </option>
                                @endforeach
                            @endif
                        </x-dropdown>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">Link this hotel under a channel partner for commission tracking.</p>
                    </div>

                    <!-- 3. Payment Status Custom Dropdown -->
                    @php
                        $currentPayment = old('payment_status', $hotel->payment_status);
                    @endphp
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Payment Status <span class="text-rose-500 font-black">*</span>
                        </label>
                        <x-dropdown name="payment_status" required theme="sa">
                            <option value="paid" {{ $currentPayment === 'paid' ? 'selected' : '' }}>Paid (License Key Activated)</option>
                            <option value="pending" {{ $currentPayment === 'pending' ? 'selected' : '' }}>Pending (Awaiting Settlement)</option>
                        </x-dropdown>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">Controls active streaming access for connected TV devices.</p>
                    </div>

                    <!-- 4. Approval Status Custom Dropdown -->
                    @php
                        $currentApproval = old('approval_status', $hotel->approval_status);
                    @endphp
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Approval Status <span class="text-rose-500 font-black">*</span>
                        </label>
                        <x-dropdown name="approval_status" required theme="sa">
                            <option value="approved" {{ $currentApproval === 'approved' ? 'selected' : '' }}>Approved (Active on Network)</option>
                            <option value="pending" {{ $currentApproval === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="disapproved" {{ $currentApproval === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                        </x-dropdown>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">Platform compliance gate for in-room TV hardware pairing.</p>
                    </div>

                    <!-- 5. TV Interface Theme -->
                    @php
                        $selectedTheme = old('selected_theme_id', $hotel->selected_theme_id ?? ($defaultThemeId ?? 1));
                    @endphp
                    <div class="sm:col-span-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Default In-Room TV Theme Style <span class="text-rose-500 font-black">*</span>
                        </label>
                        <x-dropdown name="selected_theme_id" required theme="sa">
                            @if(isset($themes) && $themes->isNotEmpty())
                                @foreach($themes as $th)
                                    <option value="{{ $th->theme_id }}" {{ (int)$selectedTheme === (int)$th->theme_id ? 'selected' : '' }}>
                                        Theme #{{ $th->theme_id }} - {{ $th->theme_name ?? 'Standard Layout' }} {{ (int)$th->theme_id === (int)($defaultThemeId ?? 1) ? '(Recommended Default)' : '' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1" {{ (int)$selectedTheme === 1 ? 'selected' : '' }}>Theme #1 (Default Classic Cosmos)</option>
                                <option value="2" {{ (int)$selectedTheme === 2 ? 'selected' : '' }}>Theme #2 (Modern Luxury Noir)</option>
                            @endif
                        </x-dropdown>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">Smart TVs assigned to this hotel will render this interface layout.</p>
                    </div>
                </div>

                <!-- Subscription Timelines -->
                @php
                    $purchaseFormatted = '';
                    if (old('purchase_date')) {
                        $purchaseFormatted = old('purchase_date');
                    } elseif ($hotel->purchase_date) {
                        $purchaseFormatted = $hotel->purchase_date instanceof \Carbon\CarbonInterface 
                            ? $hotel->purchase_date->format('Y-m-d\TH:i') 
                            : \Carbon\Carbon::parse($hotel->purchase_date)->format('Y-m-d\TH:i');
                    }

                    $expiryFormatted = '';
                    if (old('expiry_date')) {
                        $expiryFormatted = old('expiry_date');
                    } elseif ($hotel->expiry_date) {
                        $expiryFormatted = $hotel->expiry_date instanceof \Carbon\CarbonInterface 
                            ? $hotel->expiry_date->format('Y-m-d\TH:i') 
                            : \Carbon\Carbon::parse($hotel->expiry_date)->format('Y-m-d\TH:i');
                    }
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 pt-2">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Subscription Purchase Date
                        </label>
                        <input type="datetime-local" 
                               name="purchase_date" 
                               value="{{ $purchaseFormatted }}" 
                               class="w-full px-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-medium">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Subscription Expiry Date <span class="text-xs font-semibold text-slate-400 font-sans">(Extend Access)</span>
                        </label>
                        <input type="datetime-local" 
                               name="expiry_date" 
                               value="{{ $expiryFormatted }}" 
                               class="w-full px-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-[#150A42]/10 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 transition-all font-mono font-medium">
                    </div>
                </div>
            </div>

            <!-- Section 4: Branding & Media Assets -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base font-extrabold shrink-0 shadow-xs">
                            <i class="fa-regular fa-image"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-[#150A42] font-mono border border-purple-100">Step 4</span>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Branding & Media Assets</h2>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Update logo, cover backdrop, and interactive TV slider screens.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Hotel Logo Dropzone Card -->
                    <div class="p-5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs sm:text-sm font-bold text-slate-700">Hotel Logo</label>
                            <span class="text-[11px] font-mono text-slate-400">1:1 Square PNG/WebP</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Visual Preview Box -->
                            <div class="relative w-20 h-20 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 shadow-xs group">
                                <img id="logoPreview" src="{{ $hotel->hotel_logo ? asset($hotel->hotel_logo) : '#' }}" alt="Logo" class="{{ $hotel->hotel_logo ? '' : 'hidden' }} w-full h-full object-cover">
                                <div id="logoPlaceholder" class="{{ $hotel->hotel_logo ? 'hidden' : '' }} text-center p-2 text-slate-400">
                                    <i class="fa-regular fa-image text-xl mb-1 block"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-wider">Logo</span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 space-y-2">
                                <input type="file" 
                                       name="hotel_logo" 
                                       id="hotelLogoInput" 
                                       accept="image/*" 
                                       onchange="previewImage(this, 'logoPreview', 'logoPlaceholder')" 
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#201063] cursor-pointer transition-colors">
                                <p class="text-xs text-slate-400">Recommended 512×512px. Max file size: 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Cover Backdrop Dropzone Card -->
                    <div class="p-5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs sm:text-sm font-bold text-slate-700">TV Cover Backdrop</label>
                            <span class="text-[11px] font-mono text-slate-400">16:9 HD 1920×1080</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Visual Preview Box -->
                            <div class="relative w-28 h-20 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 shadow-xs group">
                                <img id="coverPreview" src="{{ $hotel->hotel_image ? asset($hotel->hotel_image) : '#' }}" alt="Cover" class="{{ $hotel->hotel_image ? '' : 'hidden' }} w-full h-full object-cover">
                                <div id="coverPlaceholder" class="{{ $hotel->hotel_image ? 'hidden' : '' }} text-center p-2 text-slate-400">
                                    <i class="fa-solid fa-panorama text-xl mb-1 block"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-wider">Cover</span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 space-y-2">
                                <input type="file" 
                                       name="hotel_image" 
                                       id="hotelCoverInput" 
                                       accept="image/*" 
                                       onchange="previewImage(this, 'coverPreview', 'coverPlaceholder')" 
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#201063] cursor-pointer transition-colors">
                                <p class="text-xs text-slate-400">Recommended 1920×1080px landscape. Max: 10MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TV Slider Images Gallery -->
                <div class="p-5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <label class="text-xs sm:text-sm font-bold text-slate-700">In-Room TV Slider Screens</label>
                        <span class="text-xs text-slate-400 font-medium">Multi-select files to upload new carousel slides</span>
                    </div>

                    <input type="file" 
                           name="slider_images[]" 
                           accept="image/*" 
                           multiple 
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#201063] cursor-pointer transition-colors">

                    @if($hotel->slider_images && count($hotel->slider_images) > 0)
                        <div class="pt-3">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-mono">Current Slider Screens ({{ count($hotel->slider_images) }})</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($hotel->slider_images as $path)
                                    <div class="relative group rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-50 shadow-xs">
                                        <img src="{{ asset($path) }}" alt="Slider" class="w-full h-full object-cover">
                                        <div onclick="deleteAdminSlide('{{ $path }}')" class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer p-2 text-center">
                                            <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md hover:bg-rose-500 transition-all mb-1">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </div>
                                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">Delete</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sticky-Friendly Bottom Action Bar -->
            <div class="pt-6 sm:pt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
                <a href="{{ route('super-admin.hotels.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold text-center transition-all">
                    Discard Changes
                </a>

                <div class="flex items-center space-x-3">
                    <button type="submit" class="btn-sa-primary w-full sm:w-auto px-7 py-3 text-xs sm:text-sm font-bold shadow-lg shadow-[#150A42]/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save Property Changes</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePasswordVisibility() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('passwordEyeIcon');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function previewImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    if (!input || !preview) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteAdminSlide(path) {
    Swal.fire({
        title: 'Remove Slider Image?',
        text: 'Are you sure you want to remove this slider image from hotel TV?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md',
            cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('hotel.hotel-info.delete-slider') }}";
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);

            const pathInput = document.createElement('input');
            pathInput.type = 'hidden';
            pathInput.name = 'image_path';
            pathInput.value = path;
            form.appendChild(pathInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
