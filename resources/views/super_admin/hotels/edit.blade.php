@extends('layouts.super_admin')

@section('title', 'Edit Hotel Vendor - Super Admin')
@section('page_title', 'Modify Hotel Vendor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Hotels List
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-8">
        <form action="{{ route('super-admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Owner Credentials Section -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i class="fa-regular fa-user text-rose-600"></i>
                    <h3 class="text-base font-extrabold text-slate-900">Owner Credentials</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Owner Full Name</label>
                        <input type="text" name="owner_name" required value="{{ old('owner_name', $hotel->owner_name) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Email Address</label>
                        <input type="email" name="email" required value="{{ old('email', $hotel->email) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" required value="{{ old('phone', $hotel->phone) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="space-y-1.5 max-w-sm">
                    <label class="text-xs font-bold text-slate-700">Change Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <!-- Hotel Identity Section -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-hotel text-rose-600"></i>
                    <h3 class="text-base font-extrabold text-slate-900">Hotel Properties & Subscription</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Hotel Name</label>
                        <input type="text" name="hotel_name" required value="{{ old('hotel_name', $hotel->hotel_name) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Hotel Location</label>
                        <input type="text" name="hotel_location" required value="{{ old('hotel_location', $hotel->hotel_location) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Room / TV Count Limit</label>
                        <input type="number" name="room_count" required min="1" value="{{ old('room_count', $hotel->room_count) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">TV Welcome Message / Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500">{{ old('description', $hotel->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Subscription Tier Plan</label>
                        <select name="plan_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-rose-500">
                            <option value="" {{ old('plan_id', $hotel->plan_id) === null ? 'selected' : '' }}>None (No Subscription)</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id', $hotel->plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->room_count }} rooms) - ₹{{ number_format($plan->price, 0) }}/mo
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Payment Status</label>
                        <select name="payment_status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-rose-500">
                            <option value="pending" {{ old('payment_status', $hotel->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status', $hotel->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Approval Status</label>
                        <select name="approval_status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-rose-500">
                            <option value="pending" {{ old('approval_status', $hotel->approval_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('approval_status', $hotel->approval_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="disapproved" {{ old('approval_status', $hotel->approval_status) == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Media Section -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i class="fa-regular fa-image text-rose-600"></i>
                    <h3 class="text-base font-extrabold text-slate-900">Hotel Branding Media</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white">
                        @if($hotel->hotel_logo)
                            <div class="mt-2">
                                <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="h-16 rounded-xl border border-slate-200 object-cover">
                            </div>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Upload Hotel Cover Image</label>
                        <input type="file" name="hotel_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white">
                        @if($hotel->hotel_image)
                            <div class="mt-2">
                                <img src="{{ asset($hotel->hotel_image) }}" alt="Cover" class="h-16 rounded-xl border border-slate-200 object-cover">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Add TV Slider Images (Multi-select)</label>
                    <input type="file" name="slider_images[]" accept="image/*" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white">
                    
                    @if($hotel->slider_images && count($hotel->slider_images) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3">
                            @foreach($hotel->slider_images as $path)
                                <div class="relative group rounded-xl border border-slate-200 overflow-hidden aspect-video bg-slate-50">
                                    <img src="{{ asset($path) }}" alt="Slider" class="w-full h-full object-cover">
                                    <div onclick="deleteAdminSlide('{{ $path }}')" class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                        <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.hotels.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function deleteAdminSlide(path) {
        if (!confirm('Remove this slider image?')) return;
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
</script>
@endsection
