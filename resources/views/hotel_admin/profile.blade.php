@extends('layouts.hotel_admin')

@section('title', 'Update Profile')
@section('page_title', 'Update Profile')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 30px; box-shadow: var(--shadow-sm);">
        <h3 style="margin-bottom: 24px; color: var(--bg-dark); font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <i class="fa-regular fa-user" style="color: var(--primary); margin-right: 8px;"></i> Personal Profile Details
        </h3>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hotel.profile') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Owner Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name', $hotelAdmin->owner_name) }}" required class="form-control">
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $hotelAdmin->email) }}" required class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $hotelAdmin->phone) }}" required class="form-control">
                </div>
            </div>

            <h4 style="margin: 30px 0 16px 0; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; color: var(--text-muted); font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                Change Password (Leave blank to keep current)
            </h4>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Min 6 characters">
                        <i class="fa-regular fa-eye toggle-password" style="color: var(--text-muted);"></i>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Confirm New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                        <i class="fa-regular fa-eye toggle-password" style="color: var(--text-muted);"></i>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('hotel.dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Profile Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
