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
                        <p class="text-[11px] text-slate-400 mt-1">Current room limit: <span class="font-bold text-slate-700">{{ $hotel->room_count }} rooms</span>.</p>
                    </div>

                    <!-- 2. Distributor Partner Custom Dropdown -->
                    @php
                        $selectedDistId = old('distributor_id', $hotel->distributor_id);
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
                        $currentPayment = old('payment_status', $hotel->payment_status);
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
                        $currentApproval = old('approval_status', $hotel->approval_status);
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Approval Status <span class="text-rose-500">*</span></label>
                        <x-dropdown name="approval_status" required theme="sa">
                            <option value="approved" {{ $currentApproval === 'approved' ? 'selected' : '' }}>Approved (Active immediately)</option>
                            <option value="pending" {{ $currentApproval === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="disapproved" {{ $currentApproval === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                        </x-dropdown>
                    </div>

                    <!-- 5. TV Interface Theme -->
                    @php
                        $selectedTheme = old('selected_theme_id', $hotel->selected_theme_id ?? ($defaultThemeId ?? 1));
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Assigned TV Theme <span class="text-rose-500">*</span></label>
                        <x-dropdown name="selected_theme_id" required theme="sa">
                            @if(isset($themes) && $themes->isNotEmpty())
                                @foreach($themes as $th)
                                    <option value="{{ $th->theme_id }}" {{ (int)$selectedTheme === (int)$th->theme_id ? 'selected' : '' }}>
                                        Theme #{{ $th->theme_id }} - {{ $th->theme_name ?? 'Default Layout' }} {{ (int)$th->theme_id === (int)($defaultThemeId ?? 1) ? '(Default)' : '' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1" {{ (int)$selectedTheme === 1 ? 'selected' : '' }}>Theme #1 (Default Classic)</option>
                                <option value="2" {{ (int)$selectedTheme === 2 ? 'selected' : '' }}>Theme #2 (Modern Luxury)</option>
                            @endif
                        </x-dropdown>
                        <p class="text-[11px] text-slate-400 mt-1">Smart TVs in this hotel will run this interface style.</p>
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
                <a href="{{ route('super-admin.hotels.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-sa-primary shadow-sm">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
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

function deleteAdminSlide(path) {
    Swal.fire({
        title: 'Remove Slider Image?',
        text: 'Are you sure you want to remove this slider image from hotel TV?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
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
