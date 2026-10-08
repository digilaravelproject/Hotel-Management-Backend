@extends('layouts.super_admin')

@section('title', 'Edit Hotel Vendor - Super Admin')
@section('page_title', 'Modify Hotel Vendor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('super-admin.hotels.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Back to Hotels Directory</span>
        </a>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card Form -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs">
        <form action="{{ route('super-admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Owner Credentials -->
            <div class="space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">1. Owner Profile & Credentials</h3>
                        <p class="text-xs text-slate-400">Manage administrator profile and reset access credentials.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Owner Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="owner_name" value="{{ old('owner_name', $hotel->owner_name) }}" required placeholder="e.g. Rajesh Sharma" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $hotel->email) }}" required placeholder="e.g. admin@grandhotel.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $hotel->phone) }}" required placeholder="e.g. +91 98765 43210" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                </div>

                <div class="max-w-md">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Change Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span></label>
                    <div class="relative">
                        <input type="password" id="passwordInput" name="password" placeholder="••••••••" class="w-full pl-3.5 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="passwordEyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hotel Identity -->
            <div class="space-y-5 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">2. Hotel Identity & Property Details</h3>
                        <p class="text-xs text-slate-400">Establish property name, geography, and in-room greeting details.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel / Property Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotel->hotel_name) }}" required placeholder="e.g. The Grand Palace & Suites" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">City</label>
                        <input type="text" name="city" value="{{ old('city', $hotel->city) }}" placeholder="e.g. Mumbai" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Location / Full Address <span class="text-rose-500">*</span></label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotel->hotel_location) }}" required placeholder="e.g. Bandra Kurla Complex, Bandra East, Mumbai, Maharashtra 400051" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Welcome Message / Tagline</label>
                    <textarea name="description" rows="2" placeholder="e.g. Welcome to The Grand Palace. Enjoy our luxurious suites, dining, and smart in-room TV experience." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">{{ old('description', $hotel->description) }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Displayed on the TV dashboard header as a personalized welcome greeting for guests.</p>
                </div>
            </div>

            <!-- Section 3: Subscription & Assignment -->
            <div class="space-y-5 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">3. Subscription Tier & Distributor Assignment</h3>
                        <p class="text-xs text-slate-400">Choose software licensing package and attach a distribution channel partner.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- 1. Subscription Plan Custom Dropdown -->
                    @php
                        $selectedPlanId = old('plan_id', $hotel->plan_id);
                        $activePlanObj = $plans->firstWhere('id', $selectedPlanId);
                        $planDisplayTitle = $activePlanObj ? ($activePlanObj->name . ' • ₹' . number_format($activePlanObj->price, 0) . '/mo') : 'None (Custom / Trial Package)';
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Subscription Plan</label>
                        <div class="custom-select-box relative" data-name="plan_id">
                            <input type="hidden" name="plan_id" value="{{ $selectedPlanId }}">
                            <button type="button" class="select-trigger w-full flex items-center justify-between px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 text-slate-800 transition-all text-left">
                                <span class="select-label truncate">{{ $planDisplayTitle }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-2 select-chevron transition-transform duration-200"></i>
                            </button>

                            <!-- Floating Options Menu -->
                            <div class="select-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 max-h-64 overflow-y-auto space-y-1">
                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-600 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ empty($selectedPlanId) ? 'bg-rose-50 text-rose-600 font-bold' : '' }}" data-value="" data-text="None (Custom / Trial Package)" data-active-class="bg-rose-50 text-rose-600 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-ban text-[11px] text-slate-400"></i>
                                        <span>None (Custom / Trial Package)</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ empty($selectedPlanId) ? 'is-selected' : '' }}" style="display: {{ empty($selectedPlanId) ? 'inline-block' : 'none' }} !important;"></i>
                                </div>

                                @foreach($plans as $plan)
                                    @php
                                        $isThisPlan = !empty($selectedPlanId) && ((string)$plan->id === (string)$selectedPlanId);
                                        $planText = $plan->name . ' • ₹' . number_format($plan->price, 0) . '/mo';
                                    @endphp
                                    <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-600 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $isThisPlan ? 'bg-rose-50 text-rose-600 font-bold' : '' }}" data-value="{{ $plan->id }}" data-text="{{ $planText }}" data-active-class="bg-rose-50 text-rose-600 font-bold">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800">{{ $plan->name }}</span>
                                            <span class="text-[11px] text-slate-400">Up to {{ $plan->room_count }} Rooms limit</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200">
                                                ₹{{ number_format($plan->price, 0) }}/mo
                                            </span>
                                            <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ $isThisPlan ? 'is-selected' : '' }}" style="display: {{ $isThisPlan ? 'inline-block' : 'none' }} !important;"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Current room limit: <span class="font-bold text-slate-700">{{ $hotel->room_count }} rooms</span>.</p>
                    </div>

                    <!-- 2. Distributor Partner Custom Dropdown -->
                    @php
                        $selectedDistId = old('distributor_id', $hotel->distributor_id);
                        $activeDistObj = isset($distributors) ? $distributors->firstWhere('id', $selectedDistId) : null;
                        $distDisplayTitle = $activeDistObj ? ($activeDistObj->name . ' — ' . $activeDistObj->email) : 'Direct Vendor (No Distributor Attached)';
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Onboarded By (Distributor Partner)</label>
                        <div class="custom-select-box relative" data-name="distributor_id">
                            <input type="hidden" name="distributor_id" value="{{ $selectedDistId }}">
                            <button type="button" class="select-trigger w-full flex items-center justify-between px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 text-slate-800 transition-all text-left">
                                <span class="select-label truncate">{{ $distDisplayTitle }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-2 select-chevron transition-transform duration-200"></i>
                            </button>

                            <!-- Floating Options Menu -->
                            <div class="select-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 max-h-64 overflow-y-auto space-y-1">
                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-600 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ empty($selectedDistId) ? 'bg-rose-50 text-rose-600 font-bold' : '' }}" data-value="" data-text="Direct Vendor (No Distributor Attached)" data-active-class="bg-rose-50 text-rose-600 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-regular fa-building text-[11px] text-slate-400"></i>
                                        <span>Direct Vendor (No Distributor Attached)</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ empty($selectedDistId) ? 'is-selected' : '' }}" style="display: {{ empty($selectedDistId) ? 'inline-block' : 'none' }} !important;"></i>
                                </div>

                                @if(isset($distributors))
                                    @foreach($distributors as $distributor)
                                        @php
                                            $isThisDist = !empty($selectedDistId) && ((string)$distributor->id === (string)$selectedDistId);
                                            $distText = $distributor->name . ' — ' . $distributor->email;
                                        @endphp
                                        <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-600 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $isThisDist ? 'bg-rose-50 text-rose-600 font-bold' : '' }}" data-value="{{ $distributor->id }}" data-text="{{ $distText }}" data-active-class="bg-rose-50 text-rose-600 font-bold">
                                            <div class="flex items-center space-x-2.5">
                                                <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-bold">
                                                    {{ strtoupper(substr($distributor->name, 0, 1)) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-800">{{ $distributor->name }}</span>
                                                    <span class="text-[11px] text-slate-400 font-mono">{{ $distributor->email }}</span>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ $isThisDist ? 'is-selected' : '' }}" style="display: {{ $isThisDist ? 'inline-block' : 'none' }} !important;"></i>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Associate this hotel under a registered channel partner for sales tracking.</p>
                    </div>

                    <!-- 3. Payment Status Custom Dropdown -->
                    @php
                        $currentPayment = old('payment_status', $hotel->payment_status);
                        $paymentDisplayTitle = $currentPayment === 'paid' ? 'Paid (License Key Generated & Active)' : 'Pending (Awaiting Payment Confirmation)';
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Status <span class="text-rose-500">*</span></label>
                        <div class="custom-select-box relative" data-name="payment_status">
                            <input type="hidden" name="payment_status" value="{{ $currentPayment }}">
                            <button type="button" class="select-trigger w-full flex items-center justify-between px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 text-slate-800 transition-all text-left">
                                <span class="select-label truncate">{{ $paymentDisplayTitle }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-2 select-chevron transition-transform duration-200"></i>
                            </button>

                            <!-- Floating Options Menu -->
                            <div class="select-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 space-y-1">
                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $currentPayment === 'paid' ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}" data-value="paid" data-text="Paid (License Key Generated & Active)" data-active-class="bg-emerald-50 text-emerald-700 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Paid (License Key Generated & Active)</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-emerald-600 check-indicator {{ $currentPayment === 'paid' ? 'is-selected' : '' }}" style="display: {{ $currentPayment === 'paid' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>

                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-amber-50 hover:text-amber-700 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $currentPayment === 'pending' ? 'bg-amber-50 text-amber-700 font-bold' : '' }}" data-value="pending" data-text="Pending (Awaiting Payment Confirmation)" data-active-class="bg-amber-50 text-amber-700 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        <span>Pending (Awaiting Payment Confirmation)</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-amber-600 check-indicator {{ $currentPayment === 'pending' ? 'is-selected' : '' }}" style="display: {{ $currentPayment === 'pending' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Approval Status Custom Dropdown -->
                    @php
                        $currentApproval = old('approval_status', $hotel->approval_status);
                        $approvalDisplayTitle = match($currentApproval) {
                            'pending' => 'Pending Review',
                            'disapproved' => 'Disapproved',
                            default => 'Approved (Active immediately)',
                        };
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Approval Status <span class="text-rose-500">*</span></label>
                        <div class="custom-select-box relative" data-name="approval_status">
                            <input type="hidden" name="approval_status" value="{{ $currentApproval }}">
                            <button type="button" class="select-trigger w-full flex items-center justify-between px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 text-slate-800 transition-all text-left">
                                <span class="select-label truncate">{{ $approvalDisplayTitle }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-2 select-chevron transition-transform duration-200"></i>
                            </button>

                            <!-- Floating Options Menu -->
                            <div class="select-menu hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 space-y-1">
                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $currentApproval === 'approved' ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}" data-value="approved" data-text="Approved (Active immediately)" data-active-class="bg-emerald-50 text-emerald-700 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Approved (Active immediately)</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-emerald-600 check-indicator {{ $currentApproval === 'approved' ? 'is-selected' : '' }}" style="display: {{ $currentApproval === 'approved' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>

                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-amber-50 hover:text-amber-700 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $currentApproval === 'pending' ? 'bg-amber-50 text-amber-700 font-bold' : '' }}" data-value="pending" data-text="Pending Review" data-active-class="bg-amber-50 text-amber-700 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        <span>Pending Review</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-amber-600 check-indicator {{ $currentApproval === 'pending' ? 'is-selected' : '' }}" style="display: {{ $currentApproval === 'pending' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>

                                <div class="select-option px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-700 text-xs font-medium text-slate-700 cursor-pointer flex items-center justify-between transition-colors {{ $currentApproval === 'disapproved' ? 'bg-rose-50 text-rose-700 font-bold' : '' }}" data-value="disapproved" data-text="Disapproved" data-active-class="bg-rose-50 text-rose-700 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Disapproved</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ $currentApproval === 'disapproved' ? 'is-selected' : '' }}" style="display: {{ $currentApproval === 'disapproved' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Timelines -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Subscription Purchase Date</label>
                        <input type="datetime-local" name="purchase_date" value="{{ old('purchase_date', $hotel->purchase_date ? $hotel->purchase_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Subscription Expiry Date <span class="text-slate-400 font-normal">(Extend Access)</span></label>
                        <input type="datetime-local" name="expiry_date" value="{{ old('expiry_date', $hotel->expiry_date ? $hotel->expiry_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                </div>
            </div>

            <!-- Section 4: Branding & Media -->
            <div class="space-y-5 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-regular fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">4. Branding & Media Assets</h3>
                        <p class="text-xs text-slate-400">Update logo, cover backdrop, and interactive TV slider screens.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Logo</label>
                        <input type="file" name="hotel_logo" id="hotelLogoInput" accept="image/*" onchange="previewImage(this, 'logoPreview', 'logoPlaceholder')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                        <div class="mt-3 flex items-center space-x-3">
                            <img id="logoPreview" src="{{ $hotel->hotel_logo ? asset($hotel->hotel_logo) : '#' }}" alt="Logo" class="{{ $hotel->hotel_logo ? '' : 'hidden' }} h-16 w-16 rounded-2xl border border-slate-200 object-cover shadow-xs">
                            <div id="logoPlaceholder" class="{{ $hotel->hotel_logo ? 'hidden' : '' }} w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                <i class="fa-regular fa-image text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400">Square PNG or WebP recommended (up to 5MB).</p>
                        </div>
                    </div>

                    <!-- Cover Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" id="hotelCoverInput" accept="image/*" onchange="previewImage(this, 'coverPreview', 'coverPlaceholder')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                        <div class="mt-3 flex items-center space-x-3">
                            <img id="coverPreview" src="{{ $hotel->hotel_image ? asset($hotel->hotel_image) : '#' }}" alt="Cover" class="{{ $hotel->hotel_image ? '' : 'hidden' }} h-16 w-24 rounded-2xl border border-slate-200 object-cover shadow-xs">
                            <div id="coverPlaceholder" class="{{ $hotel->hotel_image ? 'hidden' : '' }} w-24 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-panorama text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400">16:9 Landscape image for TV backdrop (up to 10MB).</p>
                        </div>
                    </div>
                </div>

                <!-- TV Slider Images -->
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-slate-700">Add TV Slider Images <span class="text-slate-400 font-normal">(Multi-select, max 10 total)</span></label>
                    <input type="file" name="slider_images[]" accept="image/*" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">

                    @if($hotel->slider_images && count($hotel->slider_images) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3">
                            @foreach($hotel->slider_images as $path)
                                <div class="relative group rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-50 shadow-xs">
                                    <img src="{{ asset($path) }}" alt="Slider" class="w-full h-full object-cover">
                                    <div onclick="deleteAdminSlide('{{ $path }}')" class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                        <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md hover:bg-rose-500 transition-all">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.hotels.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-select-box .check-indicator {
        display: none !important;
    }
    .custom-select-box .check-indicator.is-selected {
        display: inline-block !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('.custom-select-box');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.select-trigger');
        const menu = dropdown.querySelector('.select-menu');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const label = dropdown.querySelector('.select-label');
        const chevron = dropdown.querySelector('.select-chevron');
        const items = dropdown.querySelectorAll('.select-option');

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();

            // Close all other dropdowns
            dropdowns.forEach(other => {
                if (other !== dropdown) {
                    other.querySelector('.select-menu').classList.add('hidden');
                    other.querySelector('.select-trigger').classList.remove('ring-2', 'ring-rose-500/30', 'border-rose-500');
                    other.querySelector('.select-chevron').classList.remove('rotate-180');
                }
            });

            const isClosed = menu.classList.contains('hidden');
            if (isClosed) {
                menu.classList.remove('hidden');
                trigger.classList.add('ring-2', 'ring-rose-500/30', 'border-rose-500');
                chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                trigger.classList.remove('ring-2', 'ring-rose-500/30', 'border-rose-500');
                chevron.classList.remove('rotate-180');
            }
        });

        items.forEach(item => {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                const val = this.getAttribute('data-value');
                const txt = this.getAttribute('data-text');

                hiddenInput.value = val;
                label.textContent = txt;

                items.forEach(opt => {
                    opt.classList.remove('bg-rose-50', 'text-rose-600', 'font-bold', 'bg-emerald-50', 'text-emerald-700', 'bg-amber-50', 'text-amber-700', 'text-rose-700');
                    const chk = opt.querySelector('.check-indicator');
                    if (chk) {
                        chk.classList.remove('is-selected');
                        chk.style.setProperty('display', 'none', 'important');
                    }
                });

                const activeClasses = (this.getAttribute('data-active-class') || 'bg-rose-50 text-rose-600 font-bold').split(' ').filter(Boolean);
                this.classList.add(...activeClasses);
                const chk = this.querySelector('.check-indicator');
                if (chk) {
                    chk.classList.add('is-selected');
                    chk.style.setProperty('display', 'inline-block', 'important');
                }

                menu.classList.add('hidden');
                trigger.classList.remove('ring-2', 'ring-rose-500/30', 'border-rose-500');
                chevron.classList.remove('rotate-180');
            });
        });
    });

    // Close when clicking outside
    document.addEventListener('click', function () {
        dropdowns.forEach(dropdown => {
            dropdown.querySelector('.select-menu').classList.add('hidden');
            dropdown.querySelector('.select-trigger').classList.remove('ring-2', 'ring-rose-500/30', 'border-rose-500');
            dropdown.querySelector('.select-chevron').classList.remove('rotate-180');
        });
    });
});

function togglePasswordVisibility() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('passwordEyeIcon');
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
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
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
