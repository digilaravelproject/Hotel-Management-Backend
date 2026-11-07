@extends('layouts.hotel_admin')

@section('title', 'Manage Amenities - Hotel Admin')
@section('page_title', 'Hotel Amenities Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header with Breadcrumbs & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-xs">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <a href="{{ route('hotel.dashboard') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                </a>
                <span>/</span>
                <span class="text-slate-600">TV Content</span>
                <span>/</span>
                <span class="text-indigo-600 font-bold">Guest Amenities</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-spa text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Guest Amenities</h2>
                    <p class="text-xs text-slate-500 font-medium">Configure luxury services, wellness & leisure offerings rendered on in-room 16:9 Smart TVs.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openAmenityModal()" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 hover:shadow-lg transition-all flex items-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Amenity</span>
            </button>
        </div>
    </div>

    <!-- 4 KPI Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Total -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Catalog</span>
                <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-spa"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ count($amenities) }}</span>
                <span class="text-[11px] font-bold text-slate-400">Amenities</span>
            </div>
        </div>

        <!-- Active on TV -->
        @php
            $activeCount = $amenities->where('status', true)->count();
            $hiddenCount = $amenities->where('status', false)->count();
            $imagesCount = $amenities->filter(fn($a) => !empty($a->image))->count();
        @endphp
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Visible on TV</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-tv"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $activeCount }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Broadcasting
                </span>
            </div>
        </div>

        <!-- Hidden / Inactive -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Hidden / Draft</span>
                <div class="w-9 h-9 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-700">{{ $hiddenCount }}</span>
                <span class="text-[11px] font-bold text-slate-400">Disabled</span>
            </div>
        </div>

        <!-- 16:9 Media Assets -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">16:9 TV Visuals</span>
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-photo-film"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-purple-700">{{ $imagesCount }}</span>
                <span class="text-[11px] font-bold text-slate-400">Widescreen</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="amenitySearchInput" oninput="filterAmenities()" placeholder="Filter amenities by name or description..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
            <button onclick="setFilter('all')" id="filter-btn-all" class="filter-btn active px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#150A42] text-white transition-all whitespace-nowrap">
                All ({{ count($amenities) }})
            </button>
            <button onclick="setFilter('active')" id="filter-btn-active" class="filter-btn px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200/80 transition-all whitespace-nowrap">
                Active ({{ $activeCount }})
            </button>
            <button onclick="setFilter('hidden')" id="filter-btn-hidden" class="filter-btn px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200/80 transition-all whitespace-nowrap">
                Hidden ({{ $hiddenCount }})
            </button>
        </div>
    </div>

    <!-- Desktop Table View (Hidden on mobile) -->
    <div class="hidden md:block bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/90 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">Order</th>
                        <th class="px-6 py-4 w-40">16:9 Visual</th>
                        <th class="px-6 py-4">Amenity Details</th>
                        <th class="px-6 py-4">TV Description</th>
                        <th class="px-6 py-4 w-32 text-center">TV Visibility</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($amenities as $amenity)
                        <tr id="amenity-row-{{ $amenity->id }}" 
                            data-name="{{ strtolower($amenity->name) }}" 
                            data-desc="{{ strtolower($amenity->description ?? '') }}" 
                            data-status="{{ $amenity->status ? 'active' : 'hidden' }}"
                            class="amenity-item hover:bg-slate-50/80 transition-colors">
                            <!-- Order -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-black text-xs">
                                    #{{ $amenity->sr_no }}
                                </span>
                            </td>

                            <!-- 16:9 Image -->
                            <td class="px-6 py-4">
                                <div class="relative group cursor-pointer" onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}">
                                    @if($amenity->image)
                                        <div class="w-32 aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-xs relative bg-slate-900">
                                            <img src="{{ asset($amenity->image) }}" alt="{{ $amenity->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                                <i class="fa-solid fa-expand"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-32 aspect-video rounded-xl bg-slate-100 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-200/60 transition-colors">
                                            <i class="fa-regular fa-image text-lg"></i>
                                            <span class="text-[9px] font-bold mt-1">No 16:9 visual</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Amenity Details -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-slate-900 leading-snug">{{ $amenity->name }}</h4>
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/60">
                                            <i class="fa-solid fa-spa text-[9px] mr-1 text-indigo-500"></i> Hotel Amenity
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 max-w-sm line-clamp-2 leading-relaxed">
                                    {{ $amenity->description ?: 'No description entered. Display will show default amenity layout.' }}
                                </p>
                            </td>

                            <!-- TV Visibility Switch -->
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }}, this)" {{ $amenity->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                                <span class="block text-[10px] font-bold mt-1 status-label-{{ $amenity->id }} {{ $amenity->status ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $amenity->status ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}" title="Quick TV Preview" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($amenity) }})" title="Edit Amenity" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteAmenity({{ $amenity->id }}, '{{ addslashes($amenity->name) }}')" title="Delete Amenity" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                    <form id="delete-amenity-form-{{ $amenity->id }}" action="{{ route('hotel.amenities.destroy', $amenity->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center text-2xl mb-3">
                                    <i class="fa-solid fa-spa"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No Amenities Configured</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Start showcasing your luxury hotel offerings by clicking "Add New Amenity".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Responsive Cards View (2-card layout for small screens) -->
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="mobileCardsContainer">
        @forelse($amenities as $amenity)
            <div id="amenity-card-{{ $amenity->id }}" 
                 data-name="{{ strtolower($amenity->name) }}" 
                 data-desc="{{ strtolower($amenity->description ?? '') }}" 
                 data-status="{{ $amenity->status ? 'active' : 'hidden' }}"
                 class="amenity-item bg-white p-4 rounded-3xl border border-slate-200/90 shadow-xs space-y-3 relative">
                
                <!-- 16:9 Image with Order & Status Overlay -->
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-900 border border-slate-200">
                    @if($amenity->image)
                        <img src="{{ asset($amenity->image) }}" alt="{{ $amenity->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-indigo-50/50 text-indigo-600">
                            <i class="fa-solid fa-spa text-2xl"></i>
                            <span class="text-[10px] font-bold mt-1">16:9 Format</span>
                        </div>
                    @endif
                    <div class="absolute top-2.5 left-2.5 bg-black/70 backdrop-blur-md text-white text-[10px] font-black px-2 py-0.5 rounded-lg border border-white/10">
                        #{{ $amenity->sr_no }}
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $amenity->status ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-300' }}">
                            {{ $amenity->status ? 'TV Live' : 'Hidden' }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-1">
                    <h4 class="text-sm font-extrabold text-slate-900 line-clamp-1">{{ $amenity->name }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $amenity->description ?: 'No description provided.' }}
                    </p>
                </div>

                <!-- Controls & Actions -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }}, this)" {{ $amenity->status ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>

                    <div class="flex items-center gap-1.5">
                        <button onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                        <button onclick="triggerEditMode({{ json_encode($amenity) }})" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-700 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button type="button" onclick="confirmDeleteAmenity({{ $amenity->id }}, '{{ addslashes($amenity->name) }}')" class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- handled in desktop empty state -->
        @endforelse
    </div>
</div>

<!-- Add/Edit Amenity POPUP MODAL (2-Column Wireframe Dialog) -->
<div id="amenityFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <div>
                    <h3 id="formTitleText" class="text-lg font-black text-slate-900 tracking-tight">Create New Amenity</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Standard 16:9 widescreen format for Smart TV rendering.</p>
                </div>
            </div>
            <button type="button" onclick="closeAmenityModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="amenityForm" action="{{ route('hotel.amenities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- MAIN CONTENT AREA (Left 2 Cols) -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Title & Display Order -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-1.5">
                            <label for="amenityNameInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Amenity Name / Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="amenityNameInput" 
                                   name="name" 
                                   required 
                                   placeholder="e.g., Infinity Rooftop Pool, Ayurveda Wellness Spa" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-1.5">
                            <label for="amenitySrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Display Sr <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="amenitySrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($amenities) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                    </div>

                    <!-- 16:9 Image Upload Dropzone -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Visual Media (16:9 Widescreen)
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px recommended
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[200px] relative group cursor-pointer">
                            <input type="file" 
                                   id="amenityImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Frame -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-sm">
                                <div class="relative group/preview w-full aspect-video rounded-2xl overflow-hidden border-2 border-indigo-200 shadow-md">
                                    <img id="imagePreview" src="" alt="Amenity Preview" class="w-full h-full object-cover">
                                    <button type="button" onclick="clearImagePreview(event)" class="absolute top-2 right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all z-20">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-indigo-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <!-- Upload Prompt -->
                            <div id="uploadPrompt" class="space-y-2 pointer-events-none">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center mx-auto text-indigo-600 group-hover:scale-110 group-hover:bg-[#150A42] group-hover:text-white transition-all">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800">Drag & drop photo here, or <span class="text-indigo-600 underline">Browse</span></p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">PNG, JPG, WEBP • Max 5MB (Auto-compressed for fast TV OTA load)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CONFIGURATION (Right Col) -->
                <div class="lg:col-span-1 bg-slate-50/80 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                            <label for="amenityDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-align-left text-indigo-600"></i>
                                Description
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-200 text-slate-600">Max 250</span>
                        </div>

                        <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                            Details shown when a guest focuses this amenity on TV.
                        </p>

                        <div class="space-y-2">
                            <textarea id="amenityDescriptionInput" 
                                      name="description" 
                                      rows="5" 
                                      maxlength="250" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Provide operating hours, location on property, and guest instructions..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <div class="flex items-center justify-between text-[10px] font-bold">
                                <span id="charLimitWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    250 left
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- TV Format Badge -->
                    <div class="p-3 bg-indigo-50/80 border border-indigo-100 rounded-2xl text-[11px] text-indigo-950 space-y-1">
                        <p class="font-black flex items-center gap-1.5">
                            <i class="fa-solid fa-tv text-indigo-600"></i> Smart TV Resolution:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-relaxed">
                            16:9 aspect ratio prevents black bars or image stretching across hospitality TV models.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-5 mt-5 border-t border-slate-100">
                <button type="button" onclick="closeAmenityModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" id="submitFormBtn" class="px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitFormBtnText">Save Amenity</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal (16:9 TV Preview) -->
<div id="viewAmenityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl space-y-4 relative animate-in fade-in zoom-in-95 duration-200">
        <div class="relative w-full aspect-video bg-slate-900 overflow-hidden" id="viewImagePreview"></div>
        <div class="p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h3 id="viewName" class="text-lg font-black text-slate-900 tracking-tight"></h3>
                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" id="viewSrBadge"></span>
            </div>
            <p id="viewDescription" class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100"></p>
            <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">
                Close TV Preview
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const storeUrl = "{{ route('hotel.amenities.store') }}";
    let activeFilter = 'all';

    function filterAmenities() {
        const query = document.getElementById('amenitySearchInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.amenity-item');
        
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const desc = item.getAttribute('data-desc') || '';
            const status = item.getAttribute('data-status') || '';
            
            const matchesQuery = !query || name.includes(query) || desc.includes(query);
            const matchesFilter = (activeFilter === 'all') || (activeFilter === status);
            
            if (matchesQuery && matchesFilter) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function setFilter(type) {
        activeFilter = type;
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('active', 'bg-[#150A42]', 'text-white');
            b.classList.add('bg-slate-100', 'text-slate-600');
        });
        const activeBtn = document.getElementById(`filter-btn-${type}`);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-[#150A42]', 'text-white');
            activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
        }
        filterAmenities();
    }

    function updateCharCounter(textarea) {
        const maxLength = 250;
        const currentLength = textarea ? textarea.value.length : 0;
        const remaining = maxLength - currentLength;
        const counterEl = document.getElementById('charCounter');
        const warningEl = document.getElementById('charLimitWarning');

        if (!counterEl) return;
        counterEl.textContent = `${remaining} left`;

        if (remaining <= 15) {
            counterEl.className = 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200 animate-pulse';
            if (warningEl) {
                warningEl.textContent = 'Near limit!';
                warningEl.className = 'text-rose-600 font-bold';
            }
        } else {
            counterEl.className = 'text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100';
            if (warningEl) {
                warningEl.textContent = 'Character Limit';
                warningEl.className = 'text-slate-400 font-medium';
            }
        }
    }

    function openAmenityModal() {
        resetFormToAddMode();
        document.getElementById('amenityFormModal').classList.remove('hidden');
        document.getElementById('amenityNameInput').focus();
    }

    function closeAmenityModal() {
        document.getElementById('amenityFormModal').classList.add('hidden');
        document.getElementById('amenityForm').reset();
        clearImagePreview();
    }

    function resetFormToAddMode() {
        const form = document.getElementById('amenityForm');
        form.action = storeUrl;
        document.getElementById('methodContainer').innerHTML = '';
        
        document.getElementById('formModeBadge').textContent = 'Add Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700';
        document.getElementById('formTitleText').textContent = 'Create New Amenity';
        document.getElementById('submitFormBtnText').textContent = 'Save Amenity';

        form.reset();
        clearImagePreview();
        updateCharCounter(document.getElementById('amenityDescriptionInput'));
    }

    function triggerEditMode(amenity) {
        const modal = document.getElementById('amenityFormModal');
        const form = document.getElementById('amenityForm');

        form.action = `/hotel/amenities/${amenity.id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';

        document.getElementById('formModeBadge').textContent = 'Edit Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700';
        document.getElementById('formTitleText').textContent = `Editing: ${amenity.name}`;
        document.getElementById('submitFormBtnText').textContent = 'Update Amenity';

        document.getElementById('amenityNameInput').value = amenity.name;
        document.getElementById('amenitySrNoInput').value = amenity.sr_no;
        document.getElementById('amenityDescriptionInput').value = amenity.description || '';

        updateCharCounter(document.getElementById('amenityDescriptionInput'));

        if (amenity.image) {
            document.getElementById('imagePreview').src = `/${amenity.image}`;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadPrompt').classList.add('hidden');
        } else {
            clearImagePreview();
        }

        modal.classList.remove('hidden');
        document.getElementById('amenityNameInput').focus();
    }

    function handleImagePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('hidden');
                document.getElementById('uploadPrompt').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImagePreview(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        document.getElementById('amenityImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function openViewModal(btn) {
        const amenity = JSON.parse(btn.getAttribute('data-amenity'));
        document.getElementById('viewName').textContent = amenity.name;
        document.getElementById('viewSrBadge').textContent = `#${amenity.sr_no}`;
        document.getElementById('viewDescription').textContent = amenity.description || 'No description provided.';
        const imgContainer = document.getElementById('viewImagePreview');
        if (amenity.image) {
            imgContainer.innerHTML = `<img src="/${amenity.image}" class="w-full h-full object-cover">`;
        } else {
            imgContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-indigo-900/40 text-indigo-200 font-bold text-base">#${amenity.sr_no} • No 16:9 Image</div>`;
        }
        document.getElementById('viewAmenityModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewAmenityModal').classList.add('hidden');
    }

    function toggleAmenityStatus(id, checkbox) {
        const isChecked = checkbox.checked;
        const statusLabels = document.querySelectorAll(`.status-label-${id}`);
        statusLabels.forEach(l => {
            l.textContent = isChecked ? 'Visible' : 'Hidden';
            l.className = `block text-[10px] font-bold mt-1 status-label-${id} ${isChecked ? 'text-emerald-600' : 'text-slate-400'}`;
        });

        // Update row and card attributes
        const row = document.getElementById(`amenity-row-${id}`);
        if (row) row.setAttribute('data-status', isChecked ? 'active' : 'hidden');
        const card = document.getElementById(`amenity-card-${id}`);
        if (card) card.setAttribute('data-status', isChecked ? 'active' : 'hidden');

        fetch(`/hotel/amenities/${id}/toggle-status`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            }
        }).then(res => {
            if (window.Swal) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: `Amenity is now ${isChecked ? 'visible' : 'hidden'} on TVs`
                });
            }
        });
    }

    function confirmDeleteAmenity(id, name) {
        if (window.Swal) {
            Swal.fire({
                title: 'Delete Amenity?',
                text: `Are you sure you want to remove "${name}" from your Smart TV catalog?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-amenity-form-${id}`).submit();
                }
            });
        } else {
            if (confirm(`Delete amenity "${name}"?`)) {
                document.getElementById(`delete-amenity-form-${id}`).submit();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('amenityDescriptionInput');
        if (descInput) updateCharCounter(descInput);
    });
</script>
@endsection
