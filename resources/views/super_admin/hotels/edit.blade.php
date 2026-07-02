@extends('layouts.super_admin')

@section('title', 'Edit Hotel Vendor - Super Admin')
@section('page_title', 'Modify Hotel Vendor')

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

    <div class="card" style="box-shadow: var(--shadow-md);">
        <form action="{{ route('super-admin.hotels.update', $hotel->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-regular fa-user" style="color: var(--primary);"></i> Owner Details
            </h3>
            
            <div class="grid grid-3" style="margin-bottom: 24px;">
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
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                        <i class="fa-regular fa-eye toggle-password" style="color: var(--text-muted);"></i>
                    </div>
                </div>
            </div>

            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-hotel" style="color: var(--primary);"></i> Hotel Settings
            </h3>
            
            <div class="grid grid-3" style="margin-bottom: 24px;">
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

            <div class="grid grid-3" style="margin-bottom: 30px;">
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

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('super-admin.hotels.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
