@extends('layouts.hotel_admin')

@section('title', 'Update Hotel Info')
@section('page_title', 'Update Hotel Info')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 30px; box-shadow: var(--shadow-sm);">
        <h3 style="margin-bottom: 24px; color: var(--bg-dark); font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <i class="fa-solid fa-hotel" style="color: var(--secondary); margin-right: 8px;"></i> Hotel Information
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

        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Hotel Name</label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Location / City</label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required class="form-control">
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>

            <!-- Hotel Logo Field -->
            <div style="display: flex; gap: 24px; margin-bottom: 24px; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label class="form-label">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Recommended: Square ratio, max size 2MB</small>
                    </div>
                </div>
                <div style="text-align: center;">
                    <label class="form-label">Current Logo</label>
                    <div style="width: 100px; height: 100px; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); display: flex; align-items: center; justify-content: center;">
                        @if($hotelAdmin->hotel_logo)
                            <img src="{{ asset($hotelAdmin->hotel_logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-regular fa-image" style="font-size: 32px; color: var(--text-light);"></i>
                        @endif
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>

            <!-- Hotel Image Field -->
            <div style="display: flex; gap: 24px; margin-bottom: 30px; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label class="form-label">Upload Hotel Cover Image</label>
                        <input type="file" name="hotel_image" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Recommended: 16:9 widescreen ratio, max size 4MB</small>
                    </div>
                </div>
                <div style="text-align: center;">
                    <label class="form-label">Current Cover Image</label>
                    <div style="width: 180px; height: 100px; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); display: flex; align-items: center; justify-content: center;">
                        @if($hotelAdmin->hotel_image)
                            <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Hotel Cover" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-regular fa-image" style="font-size: 32px; color: var(--text-light);"></i>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('hotel.dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Hotel Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
