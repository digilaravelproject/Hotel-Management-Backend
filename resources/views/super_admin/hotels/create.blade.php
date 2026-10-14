@extends('layouts.super_admin')

@section('title', 'Add Hotel Vendor - Super Admin')
@section('page_title', 'Create Hotel Vendor')

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
        <form action="{{ route('super-admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Owner Credentials -->
            <div class="space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">1. Owner Profile & Credentials</h3>
                        <p class="text-xs text-slate-400">Primary administrative account credentials for hotel portal access.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Owner Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="e.g. Rajesh Sharma" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. admin@grandhotel.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. +91 98765 43210" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                </div>

                <div class="max-w-md">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Account Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="passwordInput" name="password" required placeholder="Minimum 6 characters" class="w-full pl-3.5 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="passwordEyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">This password will be used by the hotel admin to log in to their management panel.</p>
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
                        <input type="text" name="hotel_name" value="{{ old('hotel_name') }}" required placeholder="e.g. The Grand Palace & Suites" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Mumbai" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Location / Full Address <span class="text-rose-500">*</span></label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location') }}" required placeholder="e.g. Bandra Kurla Complex, Bandra East, Mumbai, Maharashtra 400051" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Welcome Message / Tagline</label>
                    <textarea name="description" rows="2" placeholder="e.g. Welcome to The Grand Palace. Enjoy our luxurious suites, dining, and smart in-room TV experience." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">{{ old('description') }}</textarea>
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
                        $selectedPlanId = old('plan_id', '');
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Subscription Plan</label>
                        <x-dropdown name="plan_id" label="None (Custom / Trial Package)" :selected="$selectedPlanId" theme="sa">
                            <option value="">None (Custom / Trial Package)</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ (string)$plan->id === (string)$selectedPlanId ? 'selected' : '' }}>
                                    {{ $plan->name }} (Up to {{ $plan->room_count }} Rooms • ₹{{ number_format($plan->price, 0) }}/mo)
                                </option>
                            @endforeach
                        </x-dropdown>
                        <p class="text-[11px] text-slate-400 mt-1">Room / TV pairing limit will be automatically applied from the selected plan.</p>
                    </div>

                    <!-- 2. Distributor Partner Custom Dropdown -->
                    @php
                        $selectedDistId = old('distributor_id', '');
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Onboarded By (Distributor Partner)</label>
                        <x-dropdown name="distributor_id" label="Direct Vendor (No Distributor Attached)" :selected="$selectedDistId" theme="sa">
                            <option value="">Direct Vendor (No Distributor Attached)</option>
                            @if(isset($distributors))
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ (string)$distributor->id === (string)$selectedDistId ? 'selected' : '' }}>
                                        {{ $distributor->name }} — {{ $distributor->email }}
                                    </option>
                                @endforeach
                            @endif
                        </x-dropdown>
                        <p class="text-[11px] text-slate-400 mt-1">Associate this hotel under a registered channel partner for sales tracking.</p>
                    </div>

                    <!-- 3. Payment Status Custom Dropdown -->
                    @php
                        $currentPayment = old('payment_status', 'paid');
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Status <span class="text-rose-500">*</span></label>
                        <x-dropdown name="payment_status" required theme="sa">
                            <option value="paid" {{ $currentPayment === 'paid' ? 'selected' : '' }}>Paid (License Key Generated & Active)</option>
                            <option value="pending" {{ $currentPayment === 'pending' ? 'selected' : '' }}>Pending (Awaiting Payment Confirmation)</option>
                        </x-dropdown>
                    </div>

                    <!-- 4. Approval Status Custom Dropdown -->
                    @php
                        $currentApproval = old('approval_status', 'approved');
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Approval Status <span class="text-rose-500">*</span></label>
                        <x-dropdown name="approval_status" required theme="sa">
                            <option value="approved" {{ $currentApproval === 'approved' ? 'selected' : '' }}>Approved (Active immediately)</option>
                            <option value="pending" {{ $currentApproval === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="disapproved" {{ $currentApproval === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                        </x-dropdown>
                    </div>
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Disapproved</span>
                                    </div>
                                    <i class="fa-solid fa-check text-xs text-rose-600 check-indicator {{ $currentApproval === 'disapproved' ? 'is-selected' : '' }}" style="display: {{ $currentApproval === 'disapproved' ? 'inline-block' : 'none' }} !important;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Branding & Media (Optional) -->
            <div class="space-y-5 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-regular fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">4. Branding & Media Assets <span class="text-xs font-normal text-slate-400">(Optional)</span></h3>
                        <p class="text-xs text-slate-400">Upload hotel logo and cover image for the TV interface.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Logo</label>
                        <input type="file" name="hotel_logo" id="hotelLogoInput" accept="image/*" onchange="previewImage(this, 'logoPreview', 'logoPlaceholder')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                        <div id="logoPreviewWrapper" class="mt-3 flex items-center space-x-3">
                            <div id="logoPlaceholder" class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                <i class="fa-regular fa-image text-lg"></i>
                            </div>
                            <img id="logoPreview" src="#" alt="Logo Preview" class="hidden h-16 w-16 rounded-2xl border border-slate-200 object-cover shadow-xs">
                            <p class="text-[11px] text-slate-400">Square PNG or WebP recommended (up to 5MB).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" id="hotelCoverInput" accept="image/*" onchange="previewImage(this, 'coverPreview', 'coverPlaceholder')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                        <div id="coverPreviewWrapper" class="mt-3 flex items-center space-x-3">
                            <div id="coverPlaceholder" class="w-24 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-panorama text-lg"></i>
                            </div>
                            <img id="coverPreview" src="#" alt="Cover Preview" class="hidden h-16 w-24 rounded-2xl border border-slate-200 object-cover shadow-xs">
                            <p class="text-[11px] text-slate-400">16:9 Landscape image for TV backdrop (up to 10MB).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.hotels.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-sa-primary shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create Hotel Vendor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>

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
</script>
@endsection
