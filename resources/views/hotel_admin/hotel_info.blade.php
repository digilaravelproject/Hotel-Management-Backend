@extends('layouts.hotel_admin')

@section('title', 'Hotel Profile Management')
@section('page_title', 'Hotel Profile & Media')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Main Profile & Settings Form Card -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 sm:p-10 shadow-sm space-y-8">
        <div class="border-b border-[#E2E8F0] pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 flex items-center justify-center text-[#6E1841]">
                <i class="fa-solid fa-hotel text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#1E293B] tracking-tight">Hotel Information & Profile Settings</h3>
                <p class="text-xs text-[#64748B] font-medium">Update branding, emergency contacts, highlights, and basic hotel information.</p>
            </div>
        </div>

        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Section 1: General Profile Info -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-[#1E293B] uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#6E1841] text-white flex items-center justify-center text-xs">1</span>
                    <span>General Profile Info</span>
                </h4>
                
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Hotel Name *</label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">City</label>
                        <input type="text" name="city" value="{{ old('city', $hotelAdmin->city) }}" placeholder="e.g. Pune / Mumbai" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Location / Address *</label>
                        <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-[#1E293B]">Description / About Hotel / TV Welcome Message</label>
                    <textarea name="description" rows="3" placeholder="Enter a welcome message or overview of your hotel..." 
                        class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">{{ old('description', $hotelAdmin->description) }}</textarea>
                </div>
            </div>

            <hr class="border-[#E2E8F0]">

            <!-- Section 2: Emergency Contacts -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-[#1E293B] uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#6E1841] text-white flex items-center justify-center text-xs">2</span>
                    <span>Emergency Contacts & Desk Support</span>
                </h4>

                @php $emergency = $hotelAdmin->emergency_contacts ?? []; @endphp

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Reception Desk Contact</label>
                        <input type="text" name="reception_contact" value="{{ old('reception_contact', $emergency['reception'] ?? '') }}" placeholder="Ext. 0 / +91 89758 45684" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Dining / Room Service Contact</label>
                        <input type="text" name="dining_contact" value="{{ old('dining_contact', $emergency['dining'] ?? '') }}" placeholder="Ext. 102 (24x7 Available)" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Medical / SOS Emergency Desk</label>
                        <input type="text" name="medical_contact" value="{{ old('medical_contact', $emergency['medical_sos'] ?? '') }}" placeholder="Ext. 999 (Medical SOS Desk)" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Emergency Support Email</label>
                        <input type="email" name="emergency_email" value="{{ old('emergency_email', $emergency['email'] ?? $hotelAdmin->email) }}" placeholder="support@hotel.com" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-semibold text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-[#E2E8F0]">

            <!-- Section 3: Key Highlights -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-[#1E293B] uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#6E1841] text-white flex items-center justify-center text-xs">3</span>
                    <span>Key Hotel Highlights (App TV Display)</span>
                </h4>

                @php $currentAmenities = $hotelAdmin->hotel_amenities ?? []; @endphp

                <div id="amenities-container" class="space-y-3">
                    @foreach($currentAmenities as $index => $amenity)
                        <div class="flex items-center space-x-3">
                            <input type="text" name="hotel_amenities[]" value="{{ $amenity }}" placeholder="e.g. 📶 High-Speed Free Wi-Fi" 
                                class="flex-1 px-4 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2.5 rounded-xl border border-[#FEE2E2] text-[#DC2626] hover:bg-[#FEE2E2]/50 transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addAmenityField()" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl border border-[#C5A059]/40 text-[#6E1841] hover:bg-[#F9F5EB] font-bold text-xs transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Highlight Amenity</span>
                </button>
            </div>

            <hr class="border-[#E2E8F0]">

            <!-- Section 4: Branding Media -->
            <div class="space-y-6">
                <h4 class="text-sm font-bold text-[#1E293B] uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#6E1841] text-white flex items-center justify-center text-xs">4</span>
                    <span>Branding Media (Logo & Main Hotel Image)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div class="p-5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-4">
                        <label class="text-xs font-bold text-[#1E293B] block">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" class="w-full text-xs text-[#64748B] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#6E1841] file:text-white hover:file:bg-[#8A1C56]">
                        @if($hotelAdmin->hotel_logo)
                            <div class="mt-2 flex items-center space-x-3">
                                <img src="{{ asset($hotelAdmin->hotel_logo) }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover border border-[#E2E8F0]">
                                <span class="text-xs font-semibold text-[#16A34A]"><i class="fa-solid fa-circle-check mr-1"></i> Active Logo</span>
                            </div>
                        @endif
                    </div>

                    <!-- Main Cover Upload -->
                    <div class="p-5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-4">
                        <label class="text-xs font-bold text-[#1E293B] block">Upload Main Hotel Cover Photo (16:9)</label>
                        <input type="file" name="hotel_image" accept="image/*" class="w-full text-xs text-[#64748B] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#6E1841] file:text-white hover:file:bg-[#8A1C56]">
                        @if($hotelAdmin->hotel_image)
                            <div class="mt-2 flex items-center space-x-3">
                                <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Cover" class="w-24 h-14 rounded-xl object-cover border border-[#E2E8F0]">
                                <span class="text-xs font-semibold text-[#16A34A]"><i class="fa-solid fa-circle-check mr-1"></i> Active Cover</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TV Welcome Carousel Slider Images -->
                <div class="p-6 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#E2E8F0]">
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-[#1E293B] flex items-center space-x-2">
                                <i class="fa-solid fa-images text-[#C5A059] text-base"></i>
                                <span>TV Welcome Carousel Slider Images</span>
                            </label>
                            <p class="text-xs text-[#64748B]">High-definition 16:9 widescreen images that automatically cycle on guest room TV home screens. (Max 10 total)</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#F9F5EB] border border-[#C5A059]/40 text-[#6E1841] shrink-0 self-start sm:self-auto flex items-center space-x-1.5">
                            <i class="fa-solid fa-layer-group text-[#C5A059]"></i>
                            <span>Active Slides: {{ count($hotelAdmin->slider_images ?? []) }}/10</span>
                        </span>
                    </div>

                    <!-- File Input for Multiple Slider Uploads -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-[#1E293B]">Select Slides to Upload (Multi-Select Supported)</label>
                        <input type="file" name="slider_images[]" id="sliderImagesInput" accept="image/jpeg,image/png,image/webp,image/jpg" multiple onchange="previewSelectedSliders(this)"
                               class="w-full text-xs text-[#64748B] file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#6E1841] file:text-white hover:file:bg-[#8A1C56] file:transition-all cursor-pointer">
                        <p class="text-[10px] text-[#64748B]">Supported: WEBP, JPG, PNG (Max 10MB each). Auto-optimized to 16:9 WebP for high performance.</p>
                    </div>

                    <!-- Live Preview for newly selected files -->
                    <div id="new-slider-preview-wrapper" class="hidden space-y-2 pt-3 border-t border-[#E2E8F0]">
                        <div class="text-[11px] font-bold text-[#6E1841] flex items-center space-x-1.5">
                            <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            <span>Selected for Upload (Will be saved upon form submit):</span>
                        </div>
                        <div id="new-slider-previews" class="grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                    </div>

                    <!-- Existing Saved Sliders Grid -->
                    @if($hotelAdmin->slider_images && count($hotelAdmin->slider_images) > 0)
                        <div class="space-y-2 pt-3 border-t border-[#E2E8F0]">
                            <div class="text-[11px] font-bold text-[#1E293B] uppercase tracking-wider flex items-center space-x-1.5">
                                <i class="fa-solid fa-layer-group text-slate-400"></i>
                                <span>Current Active TV Slides ({{ count($hotelAdmin->slider_images) }})</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach($hotelAdmin->slider_images as $index => $path)
                                    <div class="slider-card-item relative group rounded-2xl border border-[#CBD5E1] overflow-hidden aspect-video bg-slate-900 shadow-xs">
                                        <img src="{{ asset($path) }}" alt="Slider #{{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        
                                        <!-- Top Badge: Slide Index -->
                                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-black/70 backdrop-blur-xs text-white text-[10px] font-bold shadow-xs">
                                            Slide #{{ $index + 1 }}
                                        </span>

                                        <!-- Hover Action Overlay -->
                                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                                            <button type="button" onclick="deleteSliderImage('{{ $path }}', this)" title="Remove slide from TV carousel"
                                                    class="btn-danger px-3 py-1.5 text-xs font-bold shadow-lg flex items-center space-x-1.5">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 px-4 bg-white border border-dashed border-[#CBD5E1] rounded-2xl text-slate-400 space-y-1.5">
                            <i class="fa-solid fa-images text-2xl text-[#C5A059]"></i>
                            <p class="text-xs font-semibold text-[#1E293B]">No TV slider images uploaded yet.</p>
                            <p class="text-[11px] text-[#64748B]">Upload photos above to showcase amenities, restaurant specials, and room packages on guest TVs.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-[#E2E8F0] flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="btn-secondary px-6 py-3">Cancel</a>
                <button type="submit" class="btn-hotel-primary px-8 py-3 flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Profile & Branding Settings</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Section 5: Hotel Facilities & Areas Shortcut Banner -->
    <div class="bg-white border border-[#E2E8F0] text-[#1E293B] rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#F9F5EB] text-[#6E1841] border border-[#C5A059]/30 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-hotel"></i>
                <span>Dedicated Section</span>
            </div>
            <h3 class="text-xl font-extrabold text-[#1E293B] tracking-tight">Hotel Facilities & Smart TV Media</h3>
            <p class="text-xs text-[#64748B] max-w-xl font-medium leading-relaxed">
                Configure your swimming pool, restaurants, lounge, spa, gym, and banquet halls in a dedicated table list view with 16:9 images, 250-character descriptions, and key features.
            </p>
        </div>
        <a href="{{ route('hotel.facilities.index') }}" class="btn-hotel-primary px-6 py-3.5 flex items-center space-x-2.5 whitespace-nowrap z-10">
            <span>Manage Hotel Facilities</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

<!-- Hidden Form for Slider Deletion -->
<form id="deleteSliderForm" action="{{ route('hotel.hotel-info.delete-slider') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="image_path" id="deleteSliderImagePath">
</form>
@endsection

@section('scripts')
<script>
    function addAmenityField() {
        const container = document.getElementById('amenities-container');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-3';
        div.innerHTML = `
            <input type="text" name="hotel_amenities[]" placeholder="e.g. 🏊 Infinity Swimming Pool" 
                class="flex-1 px-4 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
            <button type="button" onclick="this.parentElement.remove()" class="btn-danger p-2.5 text-sm">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function previewSelectedSliders(input) {
        const previewContainer = document.getElementById('new-slider-previews');
        const previewWrapper = document.getElementById('new-slider-preview-wrapper');
        previewContainer.innerHTML = '';

        if (input.files && input.files.length > 0) {
            previewWrapper.classList.remove('hidden');
            Array.from(input.files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative rounded-2xl border border-[#C5A059]/40 overflow-hidden aspect-video bg-slate-900 shadow-xs';
                    card.innerHTML = `
                        <img src="${e.target.result}" alt="New Preview ${index + 1}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#6E1841] text-[#C5A059] text-[10px] font-bold shadow-xs">New #${index + 1}</span>
                        <span class="absolute bottom-1.5 left-2 right-2 truncate px-2 py-0.5 rounded bg-black/70 text-white text-[9px] font-mono">${file.name}</span>
                    `;
                    previewContainer.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewWrapper.classList.add('hidden');
        }
    }

    function deleteSliderImage(imagePath, element) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Remove Slider Image?',
                text: 'This image will be permanently removed from your TV welcome carousel.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5',
                    cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeSliderDelete(imagePath, element);
                }
            });
        } else {
            if (confirm('Remove this slider image from TV carousel?')) {
                executeSliderDelete(imagePath, element);
            }
        }
    }

    function executeSliderDelete(imagePath, element) {
        fetch('{{ route("hotel.hotel-info.delete-slider") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ image_path: imagePath })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = element.closest('.slider-card-item');
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => card.remove(), 300);
                } else {
                    location.reload();
                }
            } else {
                alert(data.message || 'Failed to remove image');
            }
        })
        .catch(() => {
            // Fallback to standard form submit
            const form = document.getElementById('deleteSliderForm');
            document.getElementById('deleteSliderImagePath').value = imagePath;
            form.submit();
        });
    }
</script>
@endsection

