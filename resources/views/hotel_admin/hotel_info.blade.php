@extends('layouts.hotel_admin')

@section('title', 'Hotel Profile Management - Hotel Admin')
@section('page_title', 'Hotel Profile & Media')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Top Bar Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.dashboard') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Hotel Profile & Media</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Hotel Profile & Brand Identity</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Customize hotel information, emergency desk speed-dials, amenities, and 16:9 widescreen TV welcome carousel slides.</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('hotel.facilities.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-hotel text-purple-600 text-xs"></i>
                <span>Facilities & Areas</span>
            </a>
            <a href="{{ route('hotel.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <!-- Main Profile & Settings Form Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-10 shadow-sm space-y-8">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] text-xl shadow-2xs shrink-0">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Hotel Information & Profile Settings</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Update branding, emergency contacts, highlights, and basic hotel information.</p>
            </div>
        </div>

        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Section 1: General Profile Info -->
            <div class="space-y-4">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#150A42] text-white flex items-center justify-center text-xs font-mono font-bold">1</span>
                    <span>General Profile Info</span>
                </h4>
                
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Hotel Name <span class="text-rose-500 font-black">*</span></label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">City</label>
                        <input type="text" name="city" value="{{ old('city', $hotelAdmin->city) }}" placeholder="e.g. Pune / Mumbai" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Location / Address <span class="text-rose-500 font-black">*</span></label>
                        <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Description / About Hotel / TV Welcome Message</label>
                    <textarea name="description" rows="3" placeholder="Enter a welcome message or overview of your hotel..." 
                        class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-medium text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">{{ old('description', $hotelAdmin->description) }}</textarea>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Emergency Contacts -->
            <div class="space-y-4">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#150A42] text-white flex items-center justify-center text-xs font-mono font-bold">2</span>
                    <span>Emergency Contacts & Desk Support</span>
                </h4>

                @php $emergency = $hotelAdmin->emergency_contacts ?? []; @endphp

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Reception Desk Contact</label>
                        <input type="text" name="reception_contact" value="{{ old('reception_contact', $emergency['reception'] ?? '') }}" placeholder="Ext. 0 / +91 89758 45684" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Dining / Room Service Contact</label>
                        <input type="text" name="dining_contact" value="{{ old('dining_contact', $emergency['dining'] ?? '') }}" placeholder="Ext. 102 (24x7 Available)" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Medical / SOS Emergency Desk</label>
                        <input type="text" name="medical_contact" value="{{ old('medical_contact', $emergency['medical_sos'] ?? '') }}" placeholder="Ext. 999 (Medical SOS Desk)" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Emergency Support Email</label>
                        <input type="email" name="emergency_email" value="{{ old('emergency_email', $emergency['email'] ?? $hotelAdmin->email) }}" placeholder="support@hotel.com" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 3: Key Highlights -->
            <div class="space-y-4">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#150A42] text-white flex items-center justify-center text-xs font-mono font-bold">3</span>
                    <span>Key Hotel Highlights (App TV Display)</span>
                </h4>

                @php $currentAmenities = $hotelAdmin->hotel_amenities ?? []; @endphp

                <div id="amenities-container" class="space-y-3">
                    @foreach($currentAmenities as $index => $amenity)
                        <div class="flex items-center space-x-3">
                            <input type="text" name="hotel_amenities[]" value="{{ $amenity }}" placeholder="e.g. 📶 High-Speed Free Wi-Fi" 
                                class="flex-1 px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                            <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center shrink-0 cursor-pointer shadow-2xs">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addAmenityField()" class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl border border-purple-200 bg-purple-50 text-[#150A42] hover:bg-purple-100 font-black text-xs transition-colors cursor-pointer shadow-2xs active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Highlight Amenity</span>
                </button>
            </div>

            <hr class="border-slate-100">

            <!-- Section 4: Branding Media -->
            <div class="space-y-6">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#150A42] text-white flex items-center justify-center text-xs font-mono font-bold">4</span>
                    <span>Branding Media (Logo & Main Hotel Image)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div class="p-5 rounded-3xl bg-[#F8F9FD] border border-slate-200/80 space-y-4 shadow-2xs">
                        <label class="text-xs font-black text-slate-800 block">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:cursor-pointer file:transition-all cursor-pointer">
                        @if($hotelAdmin->hotel_logo)
                            <div class="mt-2 flex items-center space-x-3 bg-white p-2.5 rounded-2xl border border-slate-200 shadow-2xs">
                                <img src="{{ asset($hotelAdmin->hotel_logo) }}" alt="Logo" class="w-14 h-14 rounded-xl object-contain border border-slate-100">
                                <div>
                                    <span class="text-xs font-black text-emerald-700 flex items-center"><i class="fa-solid fa-circle-check mr-1.5"></i> Active Hotel Logo</span>
                                    <p class="text-[10px] text-slate-400 font-medium">Displayed across launcher & receipts</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Main Cover Upload -->
                    <div class="p-5 rounded-3xl bg-[#F8F9FD] border border-slate-200/80 space-y-4 shadow-2xs">
                        <label class="text-xs font-black text-slate-800 block">Upload Main Hotel Cover Photo (16:9)</label>
                        <input type="file" name="hotel_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:cursor-pointer file:transition-all cursor-pointer">
                        @if($hotelAdmin->hotel_image)
                            <div class="mt-2 flex items-center space-x-3 bg-white p-2.5 rounded-2xl border border-slate-200 shadow-2xs">
                                <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Cover" class="w-20 h-12 rounded-xl object-cover border border-slate-100">
                                <div>
                                    <span class="text-xs font-black text-emerald-700 flex items-center"><i class="fa-solid fa-circle-check mr-1.5"></i> Active Cover Visual</span>
                                    <p class="text-[10px] text-slate-400 font-medium">Main TV background banner</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TV Welcome Carousel Slider Images -->
                <div class="p-6 rounded-[28px] bg-[#F8F9FD] border border-slate-200/90 space-y-5 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
                        <div class="space-y-1">
                            <label class="text-sm font-black text-slate-900 flex items-center space-x-2">
                                <i class="fa-solid fa-images text-purple-600 text-base"></i>
                                <span>TV Welcome Carousel Slider Images</span>
                            </label>
                            <p class="text-xs text-slate-500 font-medium">High-definition 16:9 widescreen images that automatically cycle on guest room TV home screens. (Max 10 total)</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-purple-50 border border-purple-200/80 text-[#150A42] shrink-0 self-start sm:self-auto flex items-center space-x-1.5 shadow-2xs">
                            <i class="fa-solid fa-layer-group text-purple-600"></i>
                            <span>Active Slides: {{ count($hotelAdmin->slider_images ?? []) }}/10</span>
                        </span>
                    </div>

                    <!-- File Input for Multiple Slider Uploads -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Select Slides to Upload (Multi-Select Supported)</label>
                        <input type="file" name="slider_images[]" id="sliderImagesInput" accept="image/jpeg,image/png,image/webp,image/jpg" multiple onchange="previewSelectedSliders(this)"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:transition-all cursor-pointer">
                        <p class="text-[10px] text-slate-400 font-medium">Supported: WEBP, JPG, PNG (Max 10MB each). Auto-optimized to 16:9 WebP for high performance.</p>
                    </div>

                    <!-- Live Preview for newly selected files -->
                    <div id="new-slider-preview-wrapper" class="hidden space-y-2 pt-3 border-t border-slate-200">
                        <div class="text-[11px] font-black text-[#150A42] flex items-center space-x-1.5">
                            <i class="fa-solid fa-cloud-arrow-up text-xs text-purple-600"></i>
                            <span>Selected for Upload (Will be saved upon form submit):</span>
                        </div>
                        <div id="new-slider-previews" class="grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                    </div>

                    <!-- Existing Saved Sliders Grid -->
                    @if($hotelAdmin->slider_images && count($hotelAdmin->slider_images) > 0)
                        <div class="space-y-2 pt-3 border-t border-slate-200">
                            <div class="text-[11px] font-black text-slate-700 uppercase tracking-wider flex items-center space-x-1.5">
                                <i class="fa-solid fa-layer-group text-slate-400"></i>
                                <span>Current Active TV Slides ({{ count($hotelAdmin->slider_images) }})</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach($hotelAdmin->slider_images as $index => $path)
                                    <div class="slider-card-item relative group rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-900 shadow-2xs">
                                        <img src="{{ asset($path) }}" alt="Slider #{{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        
                                        <!-- Top Badge: Slide Index -->
                                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#150A42]/85 backdrop-blur-xs text-white text-[10px] font-black font-mono shadow-xs border border-white/10">
                                            Slide #{{ $index + 1 }}
                                        </span>

                                        <!-- Hover Action Overlay -->
                                        <div class="absolute inset-0 bg-[#0B0424]/75 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 backdrop-blur-2xs">
                                            <button type="button" onclick="deleteSliderImage('{{ $path }}', this)" title="Remove slide from TV carousel"
                                                    class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-lg flex items-center space-x-1.5 cursor-pointer active:scale-95 transition-all">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 px-4 bg-white border border-dashed border-slate-200 rounded-2xl text-slate-400 space-y-1.5 shadow-2xs">
                            <i class="fa-solid fa-images text-3xl text-purple-300"></i>
                            <p class="text-xs font-black text-slate-800">No TV slider images uploaded yet.</p>
                            <p class="text-[11px] text-slate-400 font-medium">Upload photos above to showcase amenities, restaurant specials, and room packages on guest TVs.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-md shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Profile & Branding Settings</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Section 5: Hotel Facilities & Areas Shortcut Banner -->
    <div class="bg-gradient-to-r from-[#150A42] via-[#1E1156] to-[#2D1B69] text-white rounded-[28px] p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 border border-white/10">
        <div class="space-y-2 text-center md:text-left z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-xs text-purple-200 border border-white/10 text-[10px] font-black uppercase tracking-wider font-mono">
                <i class="fa-solid fa-hotel text-amber-300"></i>
                <span>Dedicated Section</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight">Hotel Facilities & Smart TV Media</h3>
            <p class="text-xs text-purple-200/90 max-w-xl font-medium leading-relaxed">
                Configure your swimming pool, restaurants, lounge, spa, gym, and banquet halls in a dedicated table list view with 16:9 images, 250-character descriptions, and key features.
            </p>
        </div>
        <a href="{{ route('hotel.facilities.index') }}" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-purple-50 text-[#150A42] font-black text-xs shadow-lg transition-all flex items-center space-x-2.5 whitespace-nowrap z-10 active:scale-95 cursor-pointer">
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
                class="flex-1 px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
            <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center shrink-0 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-trash text-xs"></i>
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
                    card.className = 'relative rounded-2xl border border-purple-200 overflow-hidden aspect-video bg-slate-900 shadow-2xs';
                    card.innerHTML = `
                        <img src="${e.target.result}" alt="New Preview ${index + 1}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#150A42]/90 text-purple-200 text-[10px] font-black font-mono shadow-xs">New #${index + 1}</span>
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
                cancelButtonColor: '#6B7280',
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
            const form = document.getElementById('deleteSliderForm');
            document.getElementById('deleteSliderImagePath').value = imagePath;
            form.submit();
        });
    }
</script>
@endsection
