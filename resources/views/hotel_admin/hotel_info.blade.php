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
            
            <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Hotel Name</label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">City</label>
                    <input type="text" name="city" value="{{ old('city', $hotelAdmin->city) }}" placeholder="e.g. Pune / Mumbai" class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Location / Address</label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Description / About Hotel / TV Welcome Message</label>
                <textarea name="description" rows="4" class="form-control" placeholder="Enter a brief welcome message or description of your hotel to display on the screens..." style="resize: vertical; font-family: inherit;">{{ old('description', $hotelAdmin->description) }}</textarea>
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">This message will be shown on the home page of connected TV screens.</small>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>

            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">2. Emergency Contacts & Quick Support</h4>

            @php
                $emergency = $hotelAdmin->emergency_contacts ?? [];
            @endphp

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Reception Contact</label>
                    <input type="text" name="reception_contact" value="{{ old('reception_contact', $emergency['reception'] ?? '') }}" placeholder="Ext. 0 / +91 89758 45684" class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Dining Contact</label>
                    <input type="text" name="dining_contact" value="{{ old('dining_contact', $emergency['dining'] ?? '') }}" placeholder="Ext. 102 (24x7 Available)" class="form-control">
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Medical / SOS Contact</label>
                    <input type="text" name="medical_contact" value="{{ old('medical_contact', $emergency['medical_sos'] ?? '') }}" placeholder="Ext. 999 (Emergency Desk)" class="form-control">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Emergency Email</label>
                    <input type="email" name="emergency_email" value="{{ old('emergency_email', $emergency['email'] ?? $hotelAdmin->email) }}" placeholder="support@hotel.com" class="form-control">
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>

            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">3. Key Hotel Amenities / Highlights (App TV Display)</h4>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">These featured amenities will be displayed directly inside the TV app hotel information section.</p>

            @php
                $currentAmenities = $hotelAdmin->hotel_amenities ?? [];
            @endphp

            <div id="amenities-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                @foreach($currentAmenities as $index => $amenity)
                    <div class="amenity-item" style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" name="hotel_amenities[]" value="{{ $amenity }}" placeholder="e.g. 📶 High-Speed Free Wi-Fi" class="form-control">
                        <button type="button" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger); padding: 8px 12px;" onclick="this.parentElement.remove()">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline" style="margin-bottom: 24px; font-size: 13px;" onclick="addAmenityField()">
                <i class="fa-solid fa-plus"></i> Add Highlight Amenity
            </button>

            <script>
                function addAmenityField() {
                    const container = document.getElementById('amenities-container');
                    const div = document.createElement('div');
                    div.className = 'amenity-item';
                    div.style.cssText = 'display: flex; gap: 10px; align-items: center;';
                    div.innerHTML = `
                        <input type="text" name="hotel_amenities[]" placeholder="e.g. 🏊 Infinity Swimming Pool" class="form-control">
                        <button type="button" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger); padding: 8px 12px;" onclick="this.parentElement.remove()">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                    container.appendChild(div);
                }
            </script>

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>
            
            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">4. Branding Assets (Logo & Widescreen Cover)</h4>

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
                        <label class="form-label">Upload Main Hotel Image</label>
                        <input type="file" name="hotel_image" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Main photo of the hotel property to be displayed in hotel info section</small>
                    </div>
                </div>
                <div style="text-align: center;">
                    <label class="form-label">Current Hotel Image</label>
                    <div style="width: 180px; height: 100px; border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                        @if($hotelAdmin->hotel_image)
                            <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Hotel Image" style="width: 100%; height: 100%; object-fit: cover;">
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

            <div style="border-top: 1px solid var(--border-color); margin: 24px 0;"></div>
            
            <h4 style="font-size: 15px; font-weight: 700; color: var(--bg-dark); margin-bottom: 6px;">5. Hotel Photos / Gallery Images</h4>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">Add photos of your hotel rooms, pool, lobby, restaurant etc. to display under the Hotel Photos section in the TV App.</p>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Add Hotel Photos (Multi-select)</label>
                <input type="file" name="hotel_gallery_images[]" class="form-control" accept="image/*" multiple>
                <small style="color: var(--text-muted); display: block; margin-top: 6px;">Max 20 photos allowed. (Hold Ctrl to select multiple)</small>
            </div>

            @if($hotelAdmin->hotel_gallery_images && count($hotelAdmin->hotel_gallery_images) > 0)
                <label class="form-label" style="margin-bottom: 8px; display: block;">Active Hotel Photos Gallery ({{ count($hotelAdmin->hotel_gallery_images) }} / 20)</label>
                <div class="media-preview-container">
                    @foreach($hotelAdmin->hotel_gallery_images as $path)
                        <div class="media-preview-item" id="gallery-{{ md5($path) }}">
                            <img src="{{ asset($path) }}" alt="Hotel Photo">
                            <div class="delete-btn-overlay" onclick="deleteGalleryPhoto('{{ $path }}')">
                                <button type="button" class="delete-icon" title="Remove photo">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 20px; background-color: var(--bg-main); border: 1px dashed var(--border-color); border-radius: var(--radius-md); text-align: center; color: var(--text-muted); font-size: 14px;">
                    No hotel photos uploaded yet. Upload images of your hotel property to show in the TV App.
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

    function deleteGalleryPhoto(path) {
        if (!confirm('Are you sure you want to remove this hotel photo?')) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('hotel.hotel-info.delete-gallery') }}";
        
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
