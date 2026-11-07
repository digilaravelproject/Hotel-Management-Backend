@extends('layouts.hotel_admin')

@section('title', 'Hotel Profile & Brand Identity - Hotel Admin')
@section('page_title', 'Hotel Profile & Media')

@section('content')
@php
    $emergency = $hotelAdmin->emergency_contacts ?? [];
    $amenities = $hotelAdmin->hotel_amenities ?? [];
    $sliders = $hotelAdmin->slider_images ?? [];
    $sliderCount = count($sliders);
    $amenityCount = count($amenities);
    $emergencyCount = (!empty($emergency['reception']) ? 1 : 0) + 
                      (!empty($emergency['dining']) ? 1 : 0) + 
                      (!empty($emergency['medical_sos']) ? 1 : 0) + 
                      (!empty($emergency['email']) ? 1 : 0);
@endphp

<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">
    
    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span>Hotel CMS</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">Profile & Media</span>
            </div>
            <div class="flex items-center space-x-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Hotel Profile & Brand Identity</h1>
                <span class="text-xs font-black text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/80 font-mono">
                    <i class="fa-solid fa-tv mr-1 text-purple-600"></i>In-Room TV CMS
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage property details, emergency desk speed-dials, amenities, and 16:9 widescreen Smart TV welcome carousel media.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.facilities.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-hotel text-purple-600 text-xs"></i>
                <span>Facilities & Areas</span>
            </a>
            <button type="button" 
                    onclick="document.getElementById('saveHotelInfoBtn').click()" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    <!-- Executive KPI Metrics Summary Cards (Thin Elegant Borders) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        
        <!-- 1. Property Name & Location -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-hotel text-purple-700"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">{{ $hotelAdmin->hotel_name }}</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">{{ $hotelAdmin->city ?? $hotelAdmin->hotel_location }}</div>
            </div>
        </div>

        <!-- 2. TV Welcome Carousel Slides -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-images text-indigo-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $sliderCount }} / 10</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Active TV Slides</div>
            </div>
        </div>

        <!-- 3. Key Amenities & Highlights -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-bell-concierge text-emerald-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $amenityCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">TV Highlight Amenities</div>
            </div>
        </div>

        <!-- 4. Emergency Contacts -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-phone-volume text-amber-500"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $emergencyCount }} / 4</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Desk Speed-Dials Set</div>
            </div>
        </div>
    </div>

    <!-- Main Profile & Settings Form Card (PaxTV Luxury Theme) -->
    <div class="bg-white border border-slate-200/90 rounded-[28px] p-5 sm:p-8 lg:p-10 shadow-xs space-y-8">
        
        <form action="{{ route('hotel.hotel-info') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="hotelInfoForm">
            @csrf
            
            <!-- SECTION 1: General Profile Info -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-xs font-black font-mono shadow-2xs">1</span>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 tracking-tight">General Property Profile</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Core hotel identity details displayed on TV splash screen, header, and guest invoices.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700">Hotel Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotelAdmin->hotel_name) }}" required 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700">City / District <span class="text-slate-400 font-normal">(Used for weather & flight radar)</span></label>
                        <input type="text" name="city" value="{{ old('city', $hotelAdmin->city) }}" placeholder="e.g. Mumbai, Pune, Goa" 
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700">Location / Full Address <span class="text-rose-500">*</span></label>
                        <input type="text" name="hotel_location" value="{{ old('hotel_location', $hotelAdmin->hotel_location) }}" required placeholder="Street, Area, Landmark"
                            class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-700">Description & Smart TV Welcome Message</label>
                    <textarea name="description" rows="3" placeholder="Enter an executive overview of your hotel, welcome greeting, or guest guidelines..." 
                        class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-medium text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">{{ old('description', $hotelAdmin->description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 2: Emergency Desk Contacts -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-xs font-black font-mono shadow-2xs">2</span>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 tracking-tight">In-Room Speed-Dial & Support Extensions</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Mapped to TV remote speed-dials and displayed under Hotel Info & Room Service menus.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-headset text-purple-600"></i>
                            <span>Front Desk / Reception</span>
                        </label>
                        <input type="text" name="reception_contact" value="{{ old('reception_contact', $emergency['reception'] ?? '') }}" placeholder="Ext. 0 / +91 98765 43210" 
                            class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-utensils text-amber-500"></i>
                            <span>Dining / Room Service</span>
                        </label>
                        <input type="text" name="dining_contact" value="{{ old('dining_contact', $emergency['dining'] ?? '') }}" placeholder="Ext. 101 (24x7 Service)" 
                            class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-kit-medical text-rose-500"></i>
                            <span>Medical & SOS Desk</span>
                        </label>
                        <input type="text" name="medical_contact" value="{{ old('medical_contact', $emergency['medical_sos'] ?? '') }}" placeholder="Ext. 911 / Medical Desk" 
                            class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-slate-700 flex items-center space-x-1.5">
                            <i class="fa-regular fa-envelope text-sky-500"></i>
                            <span>Emergency Email</span>
                        </label>
                        <input type="email" name="emergency_email" value="{{ old('emergency_email', $emergency['email'] ?? $hotelAdmin->email) }}" placeholder="concierge@hotel.com" 
                            class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Key Highlights & Amenities -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-xs font-black font-mono shadow-2xs">3</span>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 tracking-tight">Key Hotel Highlights & Amenities</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Highlighted perks featured on the in-room Smart TV home screen banner.</p>
                    </div>
                </div>

                <!-- One-Click Preset Suggestions -->
                <div class="space-y-2">
                    <span class="text-[11px] font-bold text-slate-500 block">Quick-add popular amenities:</span>
                    <div class="flex items-center gap-2 flex-wrap">
                        @foreach(['🏊 Infinity Pool', '📶 High-Speed Wi-Fi', '🍳 Complimentary Breakfast', '🏋️ 24/7 Fitness Center', '💆 Luxury Spa & Sauna', '🚗 Free Valet Parking', '🍸 Rooftop Sky Bar', '☕ Coffee & Tea Lounge'] as $preset)
                            <button type="button" onclick="addPresetAmenity('{{ $preset }}')" class="px-2.5 py-1 rounded-xl bg-purple-50/70 hover:bg-purple-100 text-purple-900 border border-purple-200/60 text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-2xs flex items-center space-x-1">
                                <i class="fa-solid fa-plus text-[10px] text-purple-600"></i>
                                <span>{{ $preset }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Dynamic Amenities List -->
                <div id="amenities-container" class="space-y-2.5 pt-2">
                    @foreach($amenities as $index => $amenity)
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xs shrink-0 font-mono font-bold">
                                {{ $index + 1 }}
                            </div>
                            <input type="text" name="hotel_amenities[]" value="{{ $amenity }}" placeholder="e.g. 📶 High-Speed Free Wi-Fi" 
                                class="flex-1 px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
                            <button type="button" onclick="this.parentElement.remove(); updateAmenityCounter();" class="w-9 h-9 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center shrink-0 cursor-pointer shadow-2xs active:scale-95" title="Remove highlight">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addAmenityField()" class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl border border-purple-200 bg-purple-50 text-[#150A42] hover:bg-purple-100 font-black text-xs transition-colors cursor-pointer shadow-2xs active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Custom Highlight</span>
                </button>
            </div>

            <!-- SECTION 4: Branding Media (Logo & Main Cover Visual) -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-xs font-black font-mono shadow-2xs">4</span>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 tracking-tight">Branding Media (Logo & Cover Photo)</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Hotel logo appears on TV header and guest receipts. Main cover photo serves as TV backdrop visual.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    
                    <!-- 1. Hotel Logo Upload Tile -->
                    <div class="p-5 rounded-3xl bg-[#F8F9FD] border border-slate-200/90 space-y-4 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-stamp text-purple-600"></i>
                                <span>Hotel Brand Logo</span>
                            </label>
                            <span class="text-[10px] font-mono text-slate-400">PNG, SVG, WEBP</span>
                        </div>

                        <input type="file" name="hotel_logo" accept="image/*" 
                               onchange="previewImageInput(this, 'logo-preview', 'logo-preview-box')"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:cursor-pointer file:transition-all cursor-pointer">

                        <div id="logo-preview-box" class="{{ $hotelAdmin->hotel_logo ? '' : 'hidden' }} flex items-center space-x-3 bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs">
                            <img id="logo-preview" src="{{ $hotelAdmin->hotel_logo ? asset($hotelAdmin->hotel_logo) : '' }}" alt="Logo" class="w-14 h-14 rounded-xl object-contain border border-slate-100 bg-white p-1">
                            <div>
                                <span class="text-xs font-black text-emerald-700 flex items-center">
                                    <i class="fa-solid fa-circle-check mr-1.5"></i> Active Hotel Logo
                                </span>
                                <p class="text-[10px] text-slate-400 font-medium">Rendered on in-room TV header bar</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Main Hotel Cover Photo (16:9) -->
                    <div class="p-5 rounded-3xl bg-[#F8F9FD] border border-slate-200/90 space-y-4 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-image text-purple-600"></i>
                                <span>Main Cover Photo (16:9 Widescreen)</span>
                            </label>
                            <span class="text-[10px] font-mono text-slate-400">1920x1080 Recommended</span>
                        </div>

                        <input type="file" name="hotel_image" accept="image/*" 
                               onchange="previewImageInput(this, 'cover-preview', 'cover-preview-box')"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:cursor-pointer file:transition-all cursor-pointer">

                        <div id="cover-preview-box" class="{{ $hotelAdmin->hotel_image ? '' : 'hidden' }} flex items-center space-x-3 bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs">
                            <img id="cover-preview" src="{{ $hotelAdmin->hotel_image ? asset($hotelAdmin->hotel_image) : '' }}" alt="Cover" class="w-20 h-12 rounded-xl object-cover border border-slate-100 bg-slate-900">
                            <div>
                                <span class="text-xs font-black text-emerald-700 flex items-center">
                                    <i class="fa-solid fa-circle-check mr-1.5"></i> Active Cover Visual
                                </span>
                                <p class="text-[10px] text-slate-400 font-medium">Main TV background banner</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 3. TV Welcome Carousel Slider Images Section -->
                <div class="p-5 sm:p-7 rounded-[28px] bg-[#F8F9FD] border border-slate-200/90 space-y-5 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
                        <div class="space-y-1">
                            <label class="text-sm font-black text-slate-900 flex items-center space-x-2">
                                <i class="fa-solid fa-images text-purple-600 text-base"></i>
                                <span>Smart TV Welcome Carousel Slides</span>
                            </label>
                            <p class="text-xs text-slate-500 font-medium">High-definition 16:9 widescreen images that cycle automatically on guest TV screens. (Up to 10 total)</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-purple-50 border border-purple-200/80 text-[#150A42] shrink-0 self-start sm:self-auto flex items-center space-x-1.5 shadow-2xs font-mono">
                            <i class="fa-solid fa-layer-group text-purple-600"></i>
                            <span>{{ $sliderCount }}/10 Active Slides</span>
                        </span>
                    </div>

                    <!-- Upload Input -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Select Slides to Upload (Multiple Files Supported)</label>
                        <input type="file" name="slider_images[]" id="sliderImagesInput" accept="image/jpeg,image/png,image/webp,image/jpg" multiple onchange="previewSelectedSliders(this)"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#150A42] file:text-white hover:file:bg-[#1f105e] file:transition-all cursor-pointer">
                        <p class="text-[10px] text-slate-400 font-medium">Supported: WEBP, JPG, PNG (Max 10MB each). Auto-optimized to 16:9 for high performance TV rendering.</p>
                    </div>

                    <!-- Staged New Uploads Preview -->
                    <div id="new-slider-preview-wrapper" class="hidden space-y-2 pt-3 border-t border-slate-200">
                        <div class="text-[11px] font-black text-[#150A42] flex items-center space-x-1.5">
                            <i class="fa-solid fa-cloud-arrow-up text-xs text-purple-600"></i>
                            <span>Selected for Upload (Will be saved upon form submit):</span>
                        </div>
                        <div id="new-slider-previews" class="grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                    </div>

                    <!-- Existing Active Sliders -->
                    @if($sliderCount > 0)
                        <div class="space-y-2 pt-3 border-t border-slate-200">
                            <div class="text-[11px] font-black text-slate-700 uppercase tracking-wider flex items-center space-x-1.5 font-mono">
                                <i class="fa-solid fa-layer-group text-slate-400"></i>
                                <span>Current Active TV Slides ({{ $sliderCount }})</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                                @foreach($sliders as $index => $path)
                                    <div class="slider-card-item relative group rounded-2xl border border-slate-200 overflow-hidden aspect-video bg-slate-900 shadow-2xs">
                                        <img src="{{ asset($path) }}" alt="Slider #{{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        
                                        <!-- Top Badge: Slide Index -->
                                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#150A42]/90 backdrop-blur-xs text-white text-[10px] font-black font-mono shadow-xs border border-white/10">
                                            Slide #{{ $index + 1 }}
                                        </span>

                                        <!-- Hover Action Overlay -->
                                        <div class="absolute inset-0 bg-[#0B0424]/75 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 backdrop-blur-2xs">
                                            <button type="button" onclick="deleteSliderImage('{{ $path }}', this)" title="Remove slide from TV carousel"
                                                    class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-black shadow-lg flex items-center space-x-1.5 cursor-pointer active:scale-95 transition-all">
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
                            <i class="fa-solid fa-images text-3xl text-purple-300 block mb-1"></i>
                            <p class="text-xs font-black text-slate-800">No TV slider images uploaded yet.</p>
                            <p class="text-[11px] text-slate-400 font-medium">Upload photos above to showcase amenities, restaurant specials, and room packages on guest TVs.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Controls Bar -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">
                    Cancel
                </a>
                <button type="submit" 
                        id="saveHotelInfoBtn"
                        class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
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
    function addAmenityField(val = '') {
        const container = document.getElementById('amenities-container');
        const count = container.children.length + 1;
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2.5';
        div.innerHTML = `
            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xs shrink-0 font-mono font-bold">${count}</div>
            <input type="text" name="hotel_amenities[]" value="${val}" placeholder="e.g. 🏊 Infinity Swimming Pool" 
                class="flex-1 px-4 py-2.5 bg-[#F8F9FD] border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#150A42] focus:ring-4 focus:ring-purple-500/10 focus:bg-white transition-all shadow-2xs">
            <button type="button" onclick="this.parentElement.remove(); updateAmenityCounter();" class="w-9 h-9 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center shrink-0 cursor-pointer shadow-2xs active:scale-95" title="Remove highlight">
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        `;
        container.appendChild(div);
        if (!val) {
            div.querySelector('input').focus();
        }
    }

    function addPresetAmenity(val) {
        addAmenityField(val);
    }

    function updateAmenityCounter() {
        const container = document.getElementById('amenities-container');
        Array.from(container.children).forEach((child, index) => {
            const badge = child.querySelector('.font-mono');
            if (badge) badge.textContent = index + 1;
        });
    }

    function previewImageInput(input, imgId, boxId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
                document.getElementById(boxId).classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
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
                title: 'Remove TV Welcome Slide?',
                text: 'This image will be removed from your in-room Smart TV welcome carousel.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#150A42',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Remove Slide',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeSliderDelete(imagePath, element);
                }
            });
        } else {
            if (confirm('Remove this slide from TV carousel?')) {
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
