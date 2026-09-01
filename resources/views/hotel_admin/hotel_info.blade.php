@extends('layouts.hotel_admin')

@section('title', 'Hotel Profile Management')
@section('page_title', 'Hotel Profile & Media')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Main Profile & Settings Form Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-8">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-hotel text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hotel Information & Profile Settings</h3>
                <p class="text-xs text-slate-500 font-medium">Update branding, emergency contacts, highlights, and basic hotel information.</p>
            </div>
        </div>

        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Section 1: General Profile Info -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">1</span>
                    <span>General Profile Info</span>
                </h4>
                
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Hotel Name *</label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">City</label>
                        <input type="text" name="city" value="{{ old('city', $hotelAdmin->city) }}" placeholder="e.g. Pune / Mumbai" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Location / Address *</label>
                        <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Description / About Hotel / TV Welcome Message</label>
                    <textarea name="description" rows="3" placeholder="Enter a welcome message or overview of your hotel..." 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">{{ old('description', $hotelAdmin->description) }}</textarea>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Emergency Contacts -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">2</span>
                    <span>Emergency Contacts & Desk Support</span>
                </h4>

                @php $emergency = $hotelAdmin->emergency_contacts ?? []; @endphp

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Reception Desk Contact</label>
                        <input type="text" name="reception_contact" value="{{ old('reception_contact', $emergency['reception'] ?? '') }}" placeholder="Ext. 0 / +91 89758 45684" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Dining / Room Service Contact</label>
                        <input type="text" name="dining_contact" value="{{ old('dining_contact', $emergency['dining'] ?? '') }}" placeholder="Ext. 102 (24x7 Available)" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Medical / SOS Emergency Desk</label>
                        <input type="text" name="medical_contact" value="{{ old('medical_contact', $emergency['medical_sos'] ?? '') }}" placeholder="Ext. 999 (Medical SOS Desk)" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Emergency Support Email</label>
                        <input type="email" name="emergency_email" value="{{ old('emergency_email', $emergency['email'] ?? $hotelAdmin->email) }}" placeholder="support@hotel.com" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 3: Key Highlights -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">3</span>
                    <span>Key Hotel Highlights (App TV Display)</span>
                </h4>

                @php $currentAmenities = $hotelAdmin->hotel_amenities ?? []; @endphp

                <div id="amenities-container" class="space-y-3">
                    @foreach($currentAmenities as $index => $amenity)
                        <div class="flex items-center space-x-3">
                            <input type="text" name="hotel_amenities[]" value="{{ $amenity }}" placeholder="e.g. 📶 High-Speed Free Wi-Fi" 
                                class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addAmenityField()" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold text-xs transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Highlight Amenity</span>
                </button>
            </div>

            <hr class="border-slate-100">

            <!-- Section 4: Branding Media -->
            <div class="space-y-6">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">4</span>
                    <span>Branding Media (Logo & Main Hotel Image)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <label class="text-xs font-bold text-slate-800 block">Upload Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                        @if($hotelAdmin->hotel_logo)
                            <div class="mt-2 flex items-center space-x-3">
                                <img src="{{ asset($hotelAdmin->hotel_logo) }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover border border-slate-300">
                                <span class="text-xs font-semibold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Active Logo</span>
                            </div>
                        @endif
                    </div>

                    <!-- Main Cover Upload -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <label class="text-xs font-bold text-slate-800 block">Upload Main Hotel Cover Photo (16:9)</label>
                        <input type="file" name="hotel_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                        @if($hotelAdmin->hotel_image)
                            <div class="mt-2 flex items-center space-x-3">
                                <img src="{{ asset($hotelAdmin->hotel_image) }}" alt="Cover" class="w-24 h-14 rounded-xl object-cover border border-slate-300">
                                <span class="text-xs font-semibold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Active Cover</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                    Save Profile & Branding Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Section 5: Hotel Facilities & Information Media Cards (Individual Details) -->
    @php
        $rawGallery = $hotelAdmin->hotel_gallery_images ?? [];
        $normalizedGallery = [];
        foreach ($rawGallery as $k => $item) {
            if (is_array($item)) {
                $normalizedGallery[] = [
                    'id' => $item['id'] ?? ('gal_' . $k),
                    'title' => $item['title'] ?? ('Hotel Facility #' . ($k + 1)),
                    'description' => $item['description'] ?? '',
                    'features' => $item['features'] ?? [],
                    'image' => $item['image'] ?? '',
                ];
            } else {
                $normalizedGallery[] = [
                    'id' => 'gal_' . md5($item),
                    'title' => 'Hotel Facility #' . ($k + 1),
                    'description' => '',
                    'features' => [],
                    'image' => $item,
                ];
            }
        }
    @endphp

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-images text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hotel Information & Facilities Media</h3>
                    <p class="text-xs text-slate-500 font-medium">Each facility card carries individual title, 250-char description, up to 4 features, and 16:9 widescreen image.</p>
                </div>
            </div>
            <button type="button" onclick="openHotelMediaModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Hotel Media</span>
            </button>
        </div>

        <!-- Media Grid / Cards List -->
        @if(count($normalizedGallery) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($normalizedGallery as $item)
                    <div class="bg-slate-50/80 border border-slate-200 rounded-3xl overflow-hidden hover:shadow-md transition-all flex flex-col justify-between group">
                        <!-- Image Container (Strict 16:9) -->
                        <div class="relative w-full aspect-video bg-slate-900 overflow-hidden">
                            @if($item['image'])
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500 bg-slate-100 font-bold">
                                    No Image
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 flex items-center space-x-2">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-900/80 text-white backdrop-blur-xs border border-white/10 flex items-center gap-1">
                                    <i class="fa-solid fa-expand text-[9px]"></i> 16:9
                                </span>
                            </div>
                        </div>

                        <!-- Content Details Area -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h4 class="text-base font-extrabold text-slate-900">{{ $item['title'] }}</h4>
                                
                                <!-- Features Badges (Max 4) -->
                                @if(!empty($item['features']) && is_array($item['features']))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($item['features'] as $feature)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-1 text-indigo-500"></i> {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                    {{ $item['description'] ?: 'No description provided.' }}
                                </p>
                            </div>

                            <!-- Card Action Buttons -->
                            <div class="pt-3 border-t border-slate-200/80 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Hotel Media Card</span>
                                <div class="inline-flex items-center space-x-2">
                                    <button type="button" onclick="openPreviewHotelMedia(this)" data-item="{{ json_encode($item) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button type="button" onclick="triggerEditHotelMedia({{ json_encode($item) }})" title="Edit Item" class="p-2 rounded-lg border border-indigo-200 bg-white text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" onclick="deleteHotelMediaItem('{{ $item['id'] }}')" title="Delete Item" class="p-2 rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 transition-colors">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 px-6 rounded-3xl border-2 border-dashed border-slate-200 text-center space-y-3">
                <i class="fa-solid fa-images text-4xl text-slate-300"></i>
                <h4 class="text-sm font-extrabold text-slate-700">No Hotel Facilities Media Added</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Add individual hotel facilities, restaurants, lounge, or views with 16:9 images, 250-char description, and up to 4 features.</p>
                <button type="button" onclick="openHotelMediaModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs inline-flex items-center space-x-1.5 shadow-md hover:bg-indigo-500 transition-all">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add First Media Card</span>
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add/Edit Hotel Media POPUP MODAL (2-Column Wireframe Layout) -->
<div id="hotelMediaModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="hotelMediaModeBadge" class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <h3 id="hotelMediaTitleText" class="text-lg font-extrabold text-slate-900">Add Hotel Facility / Media Item</h3>
            </div>
            <button type="button" onclick="closeHotelMediaModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="hotelMediaForm" action="{{ route('hotel.hotel-info.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Layout Grid: Left (Title + 16:9 Image) vs Right (Description + 4 Features) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Left Area -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="space-y-2">
                        <label for="hotelMediaNameInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            FACILITY / AREA NAME <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="hotelMediaNameInput" 
                               name="title" 
                               required 
                               placeholder="e.g., Rooftop Infinity Pool, Grand Ballroom, Sky Bar" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                    </div>

                    <!-- 16:9 Image Upload Area -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                16:9 WIDESCREEN IMAGE <span id="imageRequiredBadge" class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px (16:9)
                            </span>
                        </div>
                        
                        <div id="mediaDropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[220px] relative group cursor-pointer">
                            <input type="file" 
                                   id="hotelMediaImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleHotelMediaImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Container -->
                            <div id="mediaPreviewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-xs">
                                <div class="relative group/preview w-full">
                                    <img id="mediaImagePreview" src="" alt="Media Preview" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl border-2 border-indigo-200 shadow-md">
                                    <button type="button" onclick="clearHotelMediaImagePreview(event)" class="absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-indigo-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <!-- Upload Prompt -->
                            <div id="mediaUploadPrompt" class="space-y-3 pointer-events-none">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center mx-auto text-indigo-600 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-800">Drag & drop 16:9 photo here, or <span class="text-indigo-600 underline">Browse</span></p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-1">
                                        Required Size: <span class="font-bold text-slate-700">1920 × 1080 px (16:9)</span> • PNG, JPG, WEBP (Max 5MB • Auto-compressed to WebP ≤ 1MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Area -->
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-5">
                    <div class="space-y-5">
                        
                        <!-- Description with 250 Char Limit -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="hotelMediaDescInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-indigo-600"></i>
                                    DESCRIPTION
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                    Max 250
                                </span>
                            </div>

                            <textarea id="hotelMediaDescInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="250" 
                                      oninput="updateMediaCharCounter(this)" 
                                      placeholder="Provide description of this hotel facility / area..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span id="mediaCharWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="mediaCharCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    250 / 250 characters remaining
                                </span>
                            </div>
                        </div>

                        <!-- Key Features (Max 4) -->
                        <div class="space-y-3 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-list-check text-indigo-600"></i>
                                    KEY FEATURES
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Max 4
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium leading-tight">
                                Highlights (e.g. Skyline View, Heated Water, DJ Night).
                            </p>

                            <div id="mediaFeaturesContainer" class="space-y-2">
                                <!-- Generated via JS -->
                            </div>

                            <button type="button" id="addFeatureBtn" onclick="addFeatureField()" class="w-full py-2 px-3 rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center space-x-1.5 transition-all">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add Feature (<span id="featureCountBadge">0</span>/4)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar Tips -->
                    <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-[11px] text-indigo-900 space-y-1 mt-3">
                        <p class="font-bold flex items-center gap-1">
                            <i class="fa-solid fa-tv text-indigo-600"></i> Smart TV Gallery:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-tight">Displayed in high definition on room TVs when guests explore hotel info & facilities.</p>
                    </div>
                </div>

            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-100">
                <button type="button" onclick="closeHotelMediaModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="hotelMediaSubmitBtnText">Save Media Item</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Hotel Media Modal (16:9 Preview) -->
<div id="viewHotelMediaModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 text-center space-y-4 shadow-2xl">
        <div id="viewHotelMediaImagePreview"></div>
        <h3 id="viewHotelMediaTitle" class="text-lg font-extrabold text-slate-900"></h3>
        
        <!-- View Features Pills -->
        <div id="viewHotelMediaFeaturesContainer" class="flex flex-wrap items-center justify-center gap-1.5"></div>

        <p id="viewHotelMediaDesc" class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-left"></p>
        
        <button type="button" onclick="closeViewHotelMediaModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">Close Preview</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const maxFeaturesAllowed = 4;
    const galleryStoreUrl = "{{ route('hotel.hotel-info.gallery.store') }}";

    function addAmenityField() {
        const container = document.getElementById('amenities-container');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-3';
        div.innerHTML = `
            <input type="text" name="hotel_amenities[]" placeholder="e.g. 🏊 Infinity Swimming Pool" 
                class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
            <button type="button" onclick="this.parentElement.remove()" class="p-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                <i class="fa-solid fa-trash text-sm"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function updateMediaCharCounter(textarea) {
        const maxLength = 250;
        const currentLength = textarea.value.length;
        const remaining = maxLength - currentLength;
        const counterEl = document.getElementById('mediaCharCounter');
        const warningEl = document.getElementById('mediaCharWarning');

        counterEl.textContent = `${remaining} / 250 characters remaining`;

        if (remaining <= 15) {
            counterEl.className = 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200 animate-pulse';
            warningEl.textContent = 'Near limit!';
            warningEl.className = 'text-rose-600 font-bold';
        } else {
            counterEl.className = 'text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100';
            warningEl.textContent = 'Character Limit';
            warningEl.className = 'text-slate-400 font-medium';
        }
    }

    function addFeatureField(value = '') {
        const container = document.getElementById('mediaFeaturesContainer');
        const currentCount = container.children.length;
        if (currentCount >= maxFeaturesAllowed) return;

        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 feature-row';
        div.innerHTML = `
            <div class="relative flex-1">
                <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                <input type="text" 
                       name="features[]" 
                       value="${value.replace(/"/g, '&quot;')}" 
                       placeholder="e.g. Heated Water / Panoramic View" 
                       maxlength="100" 
                       class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10 transition-all">
            </div>
            <button type="button" onclick="removeFeatureField(this)" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        `;
        container.appendChild(div);
        updateFeatureButtonState();
    }

    function removeFeatureField(btn) {
        btn.closest('.feature-row').remove();
        updateFeatureButtonState();
    }

    function updateFeatureButtonState() {
        const container = document.getElementById('mediaFeaturesContainer');
        const count = container.children.length;
        document.getElementById('featureCountBadge').textContent = count;
        const addBtn = document.getElementById('addFeatureBtn');
        if (count >= maxFeaturesAllowed) {
            addBtn.disabled = true;
            addBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            addBtn.disabled = false;
            addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function openHotelMediaModal() {
        resetHotelMediaForm();
        document.getElementById('hotelMediaModal').classList.remove('hidden');
        document.getElementById('hotelMediaNameInput').focus();
    }

    function closeHotelMediaModal() {
        document.getElementById('hotelMediaModal').classList.add('hidden');
        document.getElementById('hotelMediaForm').reset();
        clearHotelMediaImagePreview();
    }

    function resetHotelMediaForm() {
        const form = document.getElementById('hotelMediaForm');
        form.action = galleryStoreUrl;
        
        document.getElementById('hotelMediaModeBadge').textContent = 'Add Mode';
        document.getElementById('hotelMediaModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700';
        document.getElementById('hotelMediaTitleText').textContent = 'Add Hotel Facility / Media Item';
        document.getElementById('hotelMediaSubmitBtnText').textContent = 'Save Media Item';
        document.getElementById('hotelMediaImageInput').required = true;

        form.reset();
        clearHotelMediaImagePreview();
        document.getElementById('mediaFeaturesContainer').innerHTML = '';
        addFeatureField('');
        updateMediaCharCounter(document.getElementById('hotelMediaDescInput'));
    }

    function triggerEditHotelMedia(item) {
        const modal = document.getElementById('hotelMediaModal');
        const form = document.getElementById('hotelMediaForm');

        form.action = `/hotel/hotel-info/gallery/${item.id}/update`;
        document.getElementById('hotelMediaModeBadge').textContent = 'Edit Mode';
        document.getElementById('hotelMediaModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-700';
        document.getElementById('hotelMediaTitleText').textContent = `Editing: ${item.title}`;
        document.getElementById('hotelMediaSubmitBtnText').textContent = 'Update Media Item';
        document.getElementById('hotelMediaImageInput').required = false;

        document.getElementById('hotelMediaNameInput').value = item.title;
        document.getElementById('hotelMediaDescInput').value = item.description || '';
        updateMediaCharCounter(document.getElementById('hotelMediaDescInput'));

        // Load features
        const featuresContainer = document.getElementById('mediaFeaturesContainer');
        featuresContainer.innerHTML = '';
        if (item.features && Array.isArray(item.features) && item.features.length > 0) {
            item.features.slice(0, 4).forEach(f => addFeatureField(f));
        } else {
            addFeatureField('');
        }

        if (item.image) {
            document.getElementById('mediaImagePreview').src = `/${item.image}`;
            document.getElementById('mediaPreviewContainer').classList.remove('hidden');
            document.getElementById('mediaUploadPrompt').classList.add('hidden');
        } else {
            clearHotelMediaImagePreview();
        }

        modal.classList.remove('hidden');
        document.getElementById('hotelMediaNameInput').focus();
    }

    function handleHotelMediaImagePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mediaImagePreview').src = e.target.result;
                document.getElementById('mediaPreviewContainer').classList.remove('hidden');
                document.getElementById('mediaUploadPrompt').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearHotelMediaImagePreview(e) {
        if (e) e.preventDefault();
        document.getElementById('hotelMediaImageInput').value = '';
        document.getElementById('mediaImagePreview').src = '';
        document.getElementById('mediaPreviewContainer').classList.add('hidden');
        document.getElementById('mediaUploadPrompt').classList.remove('hidden');
    }

    function openPreviewHotelMedia(btn) {
        const item = JSON.parse(btn.getAttribute('data-item'));
        document.getElementById('viewHotelMediaTitle').textContent = item.title;
        document.getElementById('viewHotelMediaDesc').textContent = item.description || 'No description provided.';
        
        const featuresContainer = document.getElementById('viewHotelMediaFeaturesContainer');
        featuresContainer.innerHTML = '';
        if (item.features && Array.isArray(item.features) && item.features.length > 0) {
            item.features.forEach(f => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100';
                span.innerHTML = `<i class="fa-solid fa-circle-check text-[10px] mr-1 text-indigo-500"></i> ${f}`;
                featuresContainer.appendChild(span);
            });
        }

        const imgContainer = document.getElementById('viewHotelMediaImagePreview');
        if (item.image) {
            imgContainer.innerHTML = `<img src="/${item.image}" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl mx-auto border-2 border-indigo-100 shadow-md">`;
        } else {
            imgContainer.innerHTML = `<div style="aspect-ratio: 16 / 9; width: 100%;" class="rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto border border-indigo-100">No Image</div>`;
        }
        document.getElementById('viewHotelMediaModal').classList.remove('hidden');
    }

    function closeViewHotelMediaModal() {
        document.getElementById('viewHotelMediaModal').classList.add('hidden');
    }

    function deleteHotelMediaItem(id) {
        Swal.fire({
            title: 'Delete Hotel Media Item?',
            text: 'Are you sure you want to delete this media item? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-trash"></i> Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/hotel/hotel-info/gallery/${id}/delete`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('hotelMediaDescInput');
        if (descInput) updateMediaCharCounter(descInput);
    });
</script>
@endsection
