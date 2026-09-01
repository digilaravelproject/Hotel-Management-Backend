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

    <!-- Section 5: Hotel Facilities & Areas Shortcut Banner -->
    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-hotel"></i>
                <span>Dedicated Section</span>
            </div>
            <h3 class="text-xl font-extrabold text-white tracking-tight">Hotel Facilities & Smart TV Media</h3>
            <p class="text-xs text-slate-300 max-w-xl font-medium leading-relaxed">
                Configure your swimming pool, restaurants, lounge, spa, gym, and banquet halls in a dedicated table list view with 16:9 images, 250-character descriptions, and key features.
            </p>
        </div>
        <a href="{{ route('hotel.facilities.index') }}" class="px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/40 transition-all flex items-center space-x-2.5 whitespace-nowrap z-10">
            <span>Manage Hotel Facilities</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection

