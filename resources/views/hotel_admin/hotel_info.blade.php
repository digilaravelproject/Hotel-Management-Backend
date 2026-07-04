@extends('layouts.hotel_admin')

@section('title', 'Hotel Profile Management')
@section('page_title', 'Hotel Profile Management')

@section('styles')
<style>
    .media-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }

    .media-preview-item {
        position: relative;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        overflow: hidden;
        aspect-ratio: 16/9;
        background-color: var(--bg-main);
        box-shadow: var(--shadow-sm);
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
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 14px;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
    }

    .delete-icon:hover {
        transform: scale(1.1);
        background-color: var(--danger-dark);
    }
</style>
@endsection

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div class="card" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 30px; box-shadow: var(--shadow-sm);">
        <h3 style="margin-bottom: 24px; color: var(--bg-dark); font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-hotel" style="color: var(--primary);"></i> Manage Hotel Information & Media
        </h3>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">1. General Profile Info</h4>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Hotel Name</label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Location / Address</label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Description / TV Welcome Message</label>
                <textarea name="description" rows="4" class="form-control" placeholder="Enter a brief welcome message or description of your hotel to display on the screens..." style="resize: vertical; font-family: inherit;">{{ old('description', $hotelAdmin->description) }}</textarea>
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">This message will be shown on the home page of connected TV screens.</small>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>
            
            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">2. Branding Assets (Logo & Widescreen Cover)</h4>

            <!-- Hotel Logo Field -->
            <div style="display: flex; gap: 24px; margin-bottom: 24px; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label class="form-label">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Recommended: Square ratio (e.g. 512x512), max size 2MB</small>
                    </div>
                </div>
                <div style="text-align: center;">
                    <label class="form-label">Current Logo</label>
                    <div style="width: 100px; height: 100px; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                        @if($hotelAdmin->hotel_logo)
                            <img src="{{ asset($hotelAdmin->hotel_logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-regular fa-image" style="font-size: 32px; color: var(--text-light);"></i>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hotel Image Field -->
            <div style="display: flex; gap: 24px; margin-bottom: 24px; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label class="form-label">Upload Hotel Cover Image</label>
                        <input type="file" name="hotel_image" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Recommended: 16:9 widescreen ratio (e.g. 1920x1080), max size 4MB</small>
                    </div>
                </div>
                <div style="text-align: center;">
                    <label class="form-label">Current Cover Image</label>
                    <div style="width: 180px; height: 100px; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                        @if($hotelAdmin->hotel_image)
                            <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Hotel Cover" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-regular fa-image" style="font-size: 32px; color: var(--text-light);"></i>
                        @endif
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>
            
            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 6px;">3. TV Screen Slider Images</h4>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">Add up to 10 photos that will slide automatically on your connected TV home screens.</p>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Add Slider Images (Multi-select)</label>
                <input type="file" name="slider_images[]" class="form-control" accept="image/*" multiple>
                <small style="color: var(--text-muted); display: block; margin-top: 6px;">Widescreen 16:9 ratio recommended. Max 4MB per file. (Hold Ctrl to select multiple)</small>
            </div>

            @if($hotelAdmin->slider_images && count($hotelAdmin->slider_images) > 0)
                <label class="form-label" style="margin-bottom: 8px; display: block;">Active Slider Gallery ({{ count($hotelAdmin->slider_images) }} / 10)</label>
                <div class="media-preview-container">
                    @foreach($hotelAdmin->slider_images as $path)
                        <div class="media-preview-item" id="slide-{{ md5($path) }}">
                            <img src="{{ asset($path) }}" alt="Slider Image">
                            <div class="delete-btn-overlay" onclick="deleteSlide('{{ $path }}')">
                                <button type="button" class="delete-icon" title="Remove slide">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 20px; background-color: var(--bg-main); border: 1px dashed var(--border-color); border-radius: var(--radius-md); text-align: center; color: var(--text-muted); font-size: 14px;">
                    No slider images uploaded. Upload widescreen images to show them on TVs.
                </div>
            @endif

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 24px; margin-top: 30px;">
                <a href="{{ route('hotel.dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Profile & Media</button>
            </div>
        </form>
    </div>
</div>

<script>
    function deleteSlide(path) {
        if (!confirm('Are you sure you want to remove this slider image from the TV rotation?')) {
            return;
        }

        // Create virtual form and submit
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
