@extends('layouts.super_admin')

@section('title', 'Edit Hotel Vendor - Super Admin')
@section('page_title', 'Modify Hotel Vendor')

@section('styles')
<style>
    .media-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
        margin-top: 8px;
    }

    .media-preview-item {
        position: relative;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        aspect-ratio: 16/9;
        background-color: var(--bg-main);
    }

    .media-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .delete-btn-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
        cursor: pointer;
    }

    .media-preview-item:hover .delete-btn-overlay {
        opacity: 1;
    }

    .delete-icon {
        color: white;
        background-color: var(--danger);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 12px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }
</style>
@endsection

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('super-admin.hotels.index') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to list
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-md); background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 30px;">
        <form action="{{ route('super-admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-regular fa-user" style="color: var(--primary);"></i> Owner Details
            </h3>
            
            <div class="grid grid-3" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" required value="{{ old('owner_name', $hotel->owner_name) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" required value="{{ old('email', $hotel->email) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" required value="{{ old('phone', $hotel->phone) }}" class="form-control">
                </div>
            </div>

            <div class="grid grid-3" style="margin-bottom: 24px; grid-template-columns: 1fr;">
                <div class="form-group" style="max-width: 350px;">
                    <label class="form-label">Password (Leave blank to keep current)</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-hotel" style="color: var(--primary);"></i> Hotel Settings
            </h3>
            
            <div class="grid grid-3" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Hotel Name</label>
                    <input type="text" name="hotel_name" required value="{{ old('hotel_name', $hotel->hotel_name) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Hotel Location</label>
                    <input type="text" name="hotel_location" required value="{{ old('hotel_location', $hotel->hotel_location) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Room / TV Count</label>
                    <input type="number" name="room_count" required min="1" value="{{ old('room_count', $hotel->room_count) }}" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Description / TV Welcome Message</label>
                <textarea name="description" rows="3" class="form-control" placeholder="TV welcome message..." style="resize: vertical; font-family: inherit;">{{ old('description', $hotel->description) }}</textarea>
            </div>

            <div class="grid grid-3" style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Pricing Plan</label>
                    <select name="plan_id" class="form-control">
                        <option value="" {{ old('plan_id', $hotel->plan_id) === null ? 'selected' : '' }}>None (No Plan Subscription)</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id', $hotel->plan_id) == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} (Up to {{ $plan->room_count }} rooms) - ₹{{ number_format($plan->price, 0) }}/mo
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" required class="form-control">
                        <option value="pending" {{ old('payment_status', $hotel->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('payment_status', $hotel->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Approval Status</label>
                    <select name="approval_status" required class="form-control">
                        <option value="pending" {{ old('approval_status', $hotel->approval_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('approval_status', $hotel->approval_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="disapproved" {{ old('approval_status', $hotel->approval_status) == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                    </select>
                </div>
            </div>

            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-regular fa-image" style="color: var(--primary);"></i> Media Attachments
            </h3>

            <div class="grid grid-2" style="margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Upload Hotel Logo</label>
                    <input type="file" name="hotel_logo" class="form-control" accept="image/*">
                    @if($hotel->hotel_logo)
                        <div style="margin-top: 8px;">
                            <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" style="height: 60px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Upload Hotel Cover Image</label>
                    <input type="file" name="hotel_image" class="form-control" accept="image/*">
                    @if($hotel->hotel_image)
                        <div style="margin-top: 8px;">
                            <img src="{{ asset($hotel->hotel_image) }}" alt="Cover" style="height: 60px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Add Slider Images (Multi-select)</label>
                <input type="file" name="slider_images[]" class="form-control" accept="image/*" multiple>
                
                @if($hotel->slider_images && count($hotel->slider_images) > 0)
                    <div class="media-preview-container">
                        @foreach($hotel->slider_images as $path)
                            <div class="media-preview-item" id="admin-slide-{{ md5($path) }}">
                                <img src="{{ asset($path) }}" alt="Slider">
                                <div class="delete-btn-overlay" onclick="deleteAdminSlide('{{ $path }}')">
                                    <button type="button" class="delete-icon" title="Remove slide">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 30px;">
                <a href="{{ route('super-admin.hotels.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function deleteAdminSlide(path) {
        if (!confirm('Are you sure you want to remove this slider image?')) {
            return;
        }

        // We can reuse the hotel owner's route if needed, or submit virtual form
        const form = document.createElement('form');
        form.method = 'POST';
        // Post to the delete endpoint (Hotel Admin has the endpoint, we can also map one for admin)
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
