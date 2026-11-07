@extends('layouts.hotel_admin')

@section('title', 'Manage Room Info - Hotel Admin')
@section('page_title', 'Room Information Management')

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
                <span class="text-indigo-600 font-bold">Room Information</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-bed text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Room Information</h2>
                    <p class="text-xs text-slate-500 font-medium">Configure room categories, layout highlights, up to 4 specifications, and 16:9 widescreen visuals for guest TVs.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openRoomInfoModal()" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 hover:shadow-lg transition-all flex items-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Room Info</span>
            </button>
        </div>
    </div>

    <!-- 4 KPI Stat Cards -->
    @php
        $activeCount = $roomInfos->where('status', true)->count();
        $hiddenCount = $roomInfos->where('status', false)->count();
        $imagesCount = $roomInfos->filter(fn($r) => !empty($r->image))->count();
        $totalSpecs = 0;
        foreach($roomInfos as $r) {
            if(!empty($r->specifications) && is_array($r->specifications)) {
                $totalSpecs += count($r->specifications);
            }
        }
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Total -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Room Categories</span>
                <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-bed"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ count($roomInfos) }}</span>
                <span class="text-[11px] font-bold text-slate-400">Total Types</span>
            </div>
        </div>

        <!-- Active on TV -->
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

        <!-- Hidden / Draft -->
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

        <!-- Specifications -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Features</span>
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-purple-700">{{ $totalSpecs }}</span>
                <span class="text-[11px] font-bold text-slate-400">Specifications</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="roomSearchInput" oninput="filterRoomInfos()" placeholder="Filter room types by title, specs, or description..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
            <button onclick="setFilter('all')" id="filter-btn-all" class="filter-btn active px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#150A42] text-white transition-all whitespace-nowrap">
                All ({{ count($roomInfos) }})
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
                        <th class="px-6 py-4">Room Category</th>
                        <th class="px-6 py-4">Specifications (Max 4)</th>
                        <th class="px-6 py-4">TV Description</th>
                        <th class="px-6 py-4 w-32 text-center">TV Visibility</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($roomInfos as $info)
                        @php
                            $specsStr = is_array($info->specifications) ? implode(' ', $info->specifications) : '';
                        @endphp
                        <tr id="room-info-row-{{ $info->id }}" 
                            data-title="{{ strtolower($info->title) }}" 
                            data-specs="{{ strtolower($specsStr) }}"
                            data-desc="{{ strtolower($info->description ?? '') }}" 
                            data-status="{{ $info->status ? 'active' : 'hidden' }}"
                            class="room-item hover:bg-slate-50/80 transition-colors">
                            <!-- Order -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-black text-xs">
                                    #{{ $info->sr_no }}
                                </span>
                            </td>

                            <!-- 16:9 Visual -->
                            <td class="px-6 py-4">
                                <div class="relative group cursor-pointer" onclick="openViewModal(this)" data-roominfo="{{ json_encode($info) }}">
                                    @if($info->image)
                                        <div class="w-32 aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-xs relative bg-slate-900">
                                            <img src="{{ asset($info->image) }}" alt="{{ $info->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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

                            <!-- Room Category -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-slate-900 leading-snug">{{ $info->title }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/60">
                                        <i class="fa-solid fa-door-open text-[9px] mr-1 text-indigo-500"></i> Room Category
                                    </span>
                                </div>
                            </td>

                            <!-- Specifications -->
                            <td class="px-6 py-4">
                                @if(!empty($info->specifications) && is_array($info->specifications) && count($info->specifications) > 0)
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($info->specifications as $spec)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-1 text-emerald-500"></i> {{ $spec }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No specifications added</span>
                                @endif
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 max-w-xs line-clamp-2 leading-relaxed">
                                    {{ $info->description ?: 'No description provided.' }}
                                </p>
                            </td>

                            <!-- TV Visibility -->
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleRoomInfoStatus({{ $info->id }}, this)" {{ $info->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                                <span class="block text-[10px] font-bold mt-1 status-label-{{ $info->id }} {{ $info->status ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $info->status ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick="openViewModal(this)" data-roominfo="{{ json_encode($info) }}" title="Quick TV Preview" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($info) }})" title="Edit Room Info" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteRoomInfo({{ $info->id }}, '{{ addslashes($info->title) }}')" title="Delete Room Info" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                    <form id="delete-roominfo-form-{{ $info->id }}" action="{{ route('hotel.room-infos.destroy', $info->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center text-2xl mb-3">
                                    <i class="fa-solid fa-bed"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No Room Info Found</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Configure room categories and in-room comfort specifications by clicking "Add New Room Info".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Responsive Cards View (2-card layout for small screens) -->
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="mobileCardsContainer">
        @forelse($roomInfos as $info)
            @php
                $specsStr = is_array($info->specifications) ? implode(' ', $info->specifications) : '';
            @endphp
            <div id="room-info-card-{{ $info->id }}" 
                 data-title="{{ strtolower($info->title) }}" 
                 data-specs="{{ strtolower($specsStr) }}"
                 data-desc="{{ strtolower($info->description ?? '') }}" 
                 data-status="{{ $info->status ? 'active' : 'hidden' }}"
                 class="room-item bg-white p-4 rounded-3xl border border-slate-200/90 shadow-xs space-y-3 relative">
                
                <!-- 16:9 Image with Order & Status -->
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-900 border border-slate-200">
                    @if($info->image)
                        <img src="{{ asset($info->image) }}" alt="{{ $info->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-indigo-50/50 text-indigo-600">
                            <i class="fa-solid fa-bed text-2xl"></i>
                            <span class="text-[10px] font-bold mt-1">16:9 Format</span>
                        </div>
                    @endif
                    <div class="absolute top-2.5 left-2.5 bg-black/70 backdrop-blur-md text-white text-[10px] font-black px-2 py-0.5 rounded-lg border border-white/10">
                        #{{ $info->sr_no }}
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $info->status ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-300' }}">
                            {{ $info->status ? 'TV Live' : 'Hidden' }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-1.5">
                    <h4 class="text-sm font-extrabold text-slate-900 line-clamp-1">{{ $info->title }}</h4>
                    
                    @if(!empty($info->specifications) && is_array($info->specifications))
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($info->specifications, 0, 3) as $spec)
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-slate-100 text-slate-600">
                                    {{ $spec }}
                                </span>
                            @endforeach
                            @if(count($info->specifications) > 3)
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-indigo-50 text-indigo-600">+{{ count($info->specifications) - 3 }}</span>
                            @endif
                        </div>
                    @endif

                    <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $info->description ?: 'No description provided.' }}
                    </p>
                </div>

                <!-- Controls & Actions -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" onchange="toggleRoomInfoStatus({{ $info->id }}, this)" {{ $info->status ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>

                    <div class="flex items-center gap-1.5">
                        <button onclick="openViewModal(this)" data-roominfo="{{ json_encode($info) }}" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                        <button onclick="triggerEditMode({{ json_encode($info) }})" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-700 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button type="button" onclick="confirmDeleteRoomInfo({{ $info->id }}, '{{ addslashes($info->title) }}')" class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 flex items-center justify-center text-xs">
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

<!-- Add/Edit Room Info POPUP MODAL (2-Column Wireframe Dialog) -->
<div id="roomInfoFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <div>
                    <h3 id="formTitleText" class="text-lg font-black text-slate-900 tracking-tight">Create New Room Info</h3>
                    <p class="text-[11px] text-slate-400 font-medium">16:9 widescreen visuals with up to 4 specification highlights.</p>
                </div>
            </div>
            <button type="button" onclick="closeRoomInfoModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="roomInfoForm" action="{{ route('hotel.room-infos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- MAIN CONTENT AREA (Left 2 Cols) -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Title & Display Order -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-1.5">
                            <label for="roomTitleInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Room Title / Category Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="roomTitleInput" 
                                   name="title" 
                                   required 
                                   placeholder="e.g., Deluxe King Suite, Executive Sea-View Villa" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-1.5">
                            <label for="roomSrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Display Sr <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="roomSrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($roomInfos) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                    </div>

                    <!-- 16:9 Image Upload Dropzone -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Room Visual (16:9 Widescreen)
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px recommended
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[200px] relative group cursor-pointer">
                            <input type="file" 
                                   id="roomImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Frame -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-sm">
                                <div class="relative group/preview w-full aspect-video rounded-2xl overflow-hidden border-2 border-indigo-200 shadow-md">
                                    <img id="imagePreview" src="" alt="Room Preview" class="w-full h-full object-cover">
                                    <button type="button" onclick="clearImagePreview(event)" class="absolute top-2 right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all z-20">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-indigo-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <!-- Default Prompt -->
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

                <!-- SIDEBAR CONFIGURATION (Right Col: Description & Specs) -->
                <div class="lg:col-span-1 bg-slate-50/80 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <!-- Description Section -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="roomDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-indigo-600"></i>
                                    Description
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-200 text-slate-600">Max 250</span>
                            </div>

                            <textarea id="roomDescriptionInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="250" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Bed size, room area, orientation, balcony details..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <div class="flex items-center justify-between text-[10px] font-bold">
                                <span id="charLimitWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    250 left
                                </span>
                            </div>
                        </div>

                        <!-- Room Specifications Section (Max 4 Features) -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-list-check text-indigo-600"></i>
                                    Specifications
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span id="specCountBadge">0</span> / 4 max
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium leading-tight">
                                Highlights (e.g. King Bed, 55" Smart TV, Rain Shower, Balcony).
                            </p>

                            <div id="specificationsContainer" class="space-y-2">
                                <!-- Dynamic specs inserted here -->
                            </div>

                            <button type="button" id="addSpecBtn" onclick="addSpecField()" class="w-full py-2 px-3 rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center space-x-1.5 transition-all">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add Specification</span>
                            </button>
                        </div>
                    </div>

                    <!-- TV Tips -->
                    <div class="p-3 bg-indigo-50/80 border border-indigo-100 rounded-2xl text-[11px] text-indigo-950 space-y-1 mt-2">
                        <p class="font-black flex items-center gap-1.5">
                            <i class="fa-solid fa-tv text-indigo-600"></i> Guest TV Rendering:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-relaxed">
                            Specifications appear as crisp badges overlaid on the 16:9 photo on in-room TV screens.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-5 mt-5 border-t border-slate-100">
                <button type="button" onclick="closeRoomInfoModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" id="submitFormBtn" class="px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitFormBtnText">Save Room Info</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal (16:9 TV Preview) -->
<div id="viewRoomInfoModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl space-y-4 relative animate-in fade-in zoom-in-95 duration-200">
        <div class="relative w-full aspect-video bg-slate-900 overflow-hidden" id="viewImagePreview"></div>
        <div class="p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h3 id="viewTitle" class="text-lg font-black text-slate-900 tracking-tight"></h3>
                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" id="viewSrBadge"></span>
            </div>
            
            <!-- Specifications Preview Chips -->
            <div id="viewSpecsContainer" class="flex flex-wrap gap-1.5"></div>

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
    const storeUrl = "{{ route('hotel.room-infos.store') }}";
    const maxSpecsAllowed = 4;
    let activeFilter = 'all';

    function filterRoomInfos() {
        const query = document.getElementById('roomSearchInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.room-item');
        
        items.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            const specs = item.getAttribute('data-specs') || '';
            const desc = item.getAttribute('data-desc') || '';
            const status = item.getAttribute('data-status') || '';
            
            const matchesQuery = !query || title.includes(query) || specs.includes(query) || desc.includes(query);
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
        filterRoomInfos();
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

    function addSpecField(value = '') {
        const container = document.getElementById('specificationsContainer');
        const currentCount = container.children.length;
        if (currentCount >= maxSpecsAllowed) return;

        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 spec-row';
        div.innerHTML = `
            <div class="relative flex-1">
                <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                <input type="text" 
                       name="specifications[]" 
                       value="${value.replace(/"/g, '&quot;')}" 
                       placeholder="e.g. King Bed / Sea View / Jacuzzi" 
                       maxlength="100" 
                       class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10 transition-all">
            </div>
            <button type="button" onclick="removeSpecField(this)" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        `;
        container.appendChild(div);
        updateSpecButtonState();
    }

    function removeSpecField(btn) {
        btn.closest('.spec-row').remove();
        updateSpecButtonState();
    }

    function updateSpecButtonState() {
        const container = document.getElementById('specificationsContainer');
        const count = container.children.length;
        document.getElementById('specCountBadge').textContent = count;
        const addBtn = document.getElementById('addSpecBtn');
        if (count >= maxSpecsAllowed) {
            addBtn.disabled = true;
            addBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            addBtn.disabled = false;
            addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function openRoomInfoModal() {
        resetFormToAddMode();
        document.getElementById('roomInfoFormModal').classList.remove('hidden');
        document.getElementById('roomTitleInput').focus();
    }

    function closeRoomInfoModal() {
        document.getElementById('roomInfoFormModal').classList.add('hidden');
        document.getElementById('roomInfoForm').reset();
        clearImagePreview();
    }

    function resetFormToAddMode() {
        const form = document.getElementById('roomInfoForm');
        form.action = storeUrl;
        document.getElementById('methodContainer').innerHTML = '';
        
        document.getElementById('formModeBadge').textContent = 'Add Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700';
        document.getElementById('formTitleText').textContent = 'Create New Room Info';
        document.getElementById('submitFormBtnText').textContent = 'Save Room Info';

        form.reset();
        clearImagePreview();
        document.getElementById('specificationsContainer').innerHTML = '';
        addSpecField('');
        updateCharCounter(document.getElementById('roomDescriptionInput'));
    }

    function triggerEditMode(info) {
        const modal = document.getElementById('roomInfoFormModal');
        const form = document.getElementById('roomInfoForm');

        form.action = `/hotel/room-infos/${info.id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';

        document.getElementById('formModeBadge').textContent = 'Edit Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700';
        document.getElementById('formTitleText').textContent = `Editing: ${info.title}`;
        document.getElementById('submitFormBtnText').textContent = 'Update Room Info';

        document.getElementById('roomTitleInput').value = info.title;
        document.getElementById('roomSrNoInput').value = info.sr_no;
        document.getElementById('roomDescriptionInput').value = info.description || '';

        updateCharCounter(document.getElementById('roomDescriptionInput'));

        // Load specifications
        const specsContainer = document.getElementById('specificationsContainer');
        specsContainer.innerHTML = '';
        if (info.specifications && Array.isArray(info.specifications) && info.specifications.length > 0) {
            info.specifications.slice(0, 4).forEach(spec => addSpecField(spec));
        } else {
            addSpecField('');
        }

        if (info.image) {
            document.getElementById('imagePreview').src = `/${info.image}`;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadPrompt').classList.add('hidden');
        } else {
            clearImagePreview();
        }

        modal.classList.remove('hidden');
        document.getElementById('roomTitleInput').focus();
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
        document.getElementById('roomImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function openViewModal(btn) {
        const info = JSON.parse(btn.getAttribute('data-roominfo'));
        document.getElementById('viewTitle').textContent = info.title;
        document.getElementById('viewSrBadge').textContent = `#${info.sr_no}`;
        document.getElementById('viewDescription').textContent = info.description || 'No description provided.';
        
        // Specs preview
        const specsContainer = document.getElementById('viewSpecsContainer');
        specsContainer.innerHTML = '';
        if (info.specifications && Array.isArray(info.specifications) && info.specifications.length > 0) {
            info.specifications.forEach(spec => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100';
                span.innerHTML = `<i class="fa-solid fa-circle-check text-[10px] mr-1.5 text-emerald-500"></i> ${spec}`;
                specsContainer.appendChild(span);
            });
        }

        const imgContainer = document.getElementById('viewImagePreview');
        if (info.image) {
            imgContainer.innerHTML = `<img src="/${info.image}" class="w-full h-full object-cover">`;
        } else {
            imgContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-indigo-900/40 text-indigo-200 font-bold text-base">#${info.sr_no} • No 16:9 Image</div>`;
        }
        document.getElementById('viewRoomInfoModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewRoomInfoModal').classList.add('hidden');
    }

    function toggleRoomInfoStatus(id, checkbox) {
        const isChecked = checkbox.checked;
        const statusLabels = document.querySelectorAll(`.status-label-${id}`);
        statusLabels.forEach(l => {
            l.textContent = isChecked ? 'Visible' : 'Hidden';
            l.className = `block text-[10px] font-bold mt-1 status-label-${id} ${isChecked ? 'text-emerald-600' : 'text-slate-400'}`;
        });

        const row = document.getElementById(`room-info-row-${id}`);
        if (row) row.setAttribute('data-status', isChecked ? 'active' : 'hidden');
        const card = document.getElementById(`room-info-card-${id}`);
        if (card) card.setAttribute('data-status', isChecked ? 'active' : 'hidden');

        fetch(`/hotel/room-infos/${id}/toggle-status`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(() => {
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
                    title: `Room info is now ${isChecked ? 'visible' : 'hidden'} on TVs`
                });
            }
        });
    }

    function confirmDeleteRoomInfo(id, title) {
        if (window.Swal) {
            Swal.fire({
                title: 'Delete Room Info?',
                text: `Are you sure you want to remove "${title}" from your Smart TV catalog?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-roominfo-form-${id}`).submit();
                }
            });
        } else {
            if (confirm(`Delete room info "${title}"?`)) {
                document.getElementById(`delete-roominfo-form-${id}`).submit();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('roomDescriptionInput');
        if (descInput) updateCharCounter(descInput);
    });
</script>
@endsection
