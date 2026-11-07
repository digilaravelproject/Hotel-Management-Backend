@extends('layouts.hotel_admin')

@section('title', 'Manage Our City - Hotel Admin')
@section('page_title', 'Our City & Local Attractions')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header with Breadcrumbs & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-xs">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <a href="{{ route('hotel.dashboard') }}" class="hover:text-amber-600 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i> Dashboard
                </a>
                <span>/</span>
                <span class="text-slate-600">TV Content</span>
                <span>/</span>
                <span class="text-amber-600 font-bold">Our City & Local Guide</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fa-solid fa-city text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Our City & Local Attractions</h2>
                    <p class="text-xs text-slate-500 font-medium">Guide hotel guests to historic landmarks, monuments, dining spots, and shopping centers on Smart TVs.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openOurCityModal()" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 hover:shadow-lg transition-all flex items-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New City Place</span>
            </button>
        </div>
    </div>

    <!-- 4 KPI Stat Cards -->
    @php
        $activeCount = $cityPlaces->where('status', true)->count();
        $hiddenCount = $cityPlaces->where('status', false)->count();
        $imagesCount = $cityPlaces->filter(fn($p) => !empty($p->image))->count();
        $totalAttractions = count($cityPlaces);
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Total -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Attractions</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalAttractions }}</span>
                <span class="text-[11px] font-bold text-slate-400">Landmarks</span>
            </div>
        </div>

        <!-- Visible on TV -->
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

        <!-- 16:9 HD Photos -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">16:9 TV Media</span>
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
            <input type="text" id="citySearchInput" oninput="filterCityPlaces()" placeholder="Filter attractions by name, tags, or description..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all">
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
            <button onclick="setFilter('all')" id="filter-btn-all" class="filter-btn active px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#150A42] text-white transition-all whitespace-nowrap">
                All ({{ $totalAttractions }})
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
                        <th class="px-6 py-4">Attraction / Place</th>
                        <th class="px-6 py-4">Highlights / Tags (Max 4)</th>
                        <th class="px-6 py-4">TV Description</th>
                        <th class="px-6 py-4 w-32 text-center">TV Visibility</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($cityPlaces as $place)
                        @php
                            $tags = $place->attractions ?? $place->features ?? [];
                            if (is_string($tags)) {
                                $decoded = json_decode($tags, true);
                                $tags = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$tags];
                            }
                            $tags = is_array($tags) ? array_values(array_filter($tags)) : [];
                            $tagsStr = implode(' ', $tags);
                        @endphp
                        <tr id="city-row-{{ $place->id }}" 
                            data-title="{{ strtolower($place->title) }}" 
                            data-tags="{{ strtolower($tagsStr) }}" 
                            data-desc="{{ strtolower($place->description ?? '') }}" 
                            data-status="{{ $place->status ? 'active' : 'hidden' }}"
                            class="city-item hover:bg-slate-50/80 transition-colors">
                            <!-- Order -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 font-black text-xs">
                                    #{{ $place->sr_no }}
                                </span>
                            </td>

                            <!-- 16:9 Visual -->
                            <td class="px-6 py-4">
                                <div class="relative group cursor-pointer" onclick="openViewModal(this)" data-city="{{ json_encode($place) }}">
                                    @if($place->image)
                                        <div class="w-32 aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-xs relative bg-slate-900">
                                            <img src="{{ asset($place->image) }}" alt="{{ $place->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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

                            <!-- Details -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <h4 class="text-sm font-extrabold text-slate-900 leading-snug">{{ $place->title }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-location-dot text-[9px] mr-1 text-amber-500"></i> City Attraction
                                    </span>
                                </div>
                            </td>

                            <!-- Highlights / Tags -->
                            <td class="px-6 py-4">
                                @if(!empty($tags) && count($tags) > 0)
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($tags as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-location-dot text-[9px] mr-1 text-amber-500"></i> {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No highlights added</span>
                                @endif
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 max-w-xs line-clamp-2 leading-relaxed">
                                    {{ $place->description ?: 'No description provided.' }}
                                </p>
                            </td>

                            <!-- TV Visibility -->
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleOurCityStatus({{ $place->id }}, this)" {{ $place->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                                <span class="block text-[10px] font-bold mt-1 status-label-{{ $place->id }} {{ $place->status ? 'text-amber-600' : 'text-slate-400' }}">
                                    {{ $place->status ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick="openViewModal(this)" data-city="{{ json_encode($place) }}" title="Quick TV Preview" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($place) }})" title="Edit Attraction" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-700 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteCityPlace({{ $place->id }}, '{{ addslashes($place->title) }}')" title="Delete Attraction" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition-all flex items-center justify-center">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                    <form id="delete-city-form-{{ $place->id }}" action="{{ route('hotel.our-city.destroy', $place->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-2xl mb-3">
                                    <i class="fa-solid fa-city"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800">No City Attractions Configured</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Provide nearby sightseeing guides and travel landmarks by clicking "Add New City Place".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Responsive Cards View (2-card layout for small screens) -->
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="mobileCardsContainer">
        @forelse($cityPlaces as $place)
            @php
                $tags = $place->attractions ?? $place->features ?? [];
                if (is_string($tags)) {
                    $decoded = json_decode($tags, true);
                    $tags = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$tags];
                }
                $tags = is_array($tags) ? array_values(array_filter($tags)) : [];
                $tagsStr = implode(' ', $tags);
            @endphp
            <div id="city-card-{{ $place->id }}" 
                 data-title="{{ strtolower($place->title) }}" 
                 data-tags="{{ strtolower($tagsStr) }}" 
                 data-desc="{{ strtolower($place->description ?? '') }}" 
                 data-status="{{ $place->status ? 'active' : 'hidden' }}"
                 class="city-item bg-white p-4 rounded-3xl border border-slate-200/90 shadow-xs space-y-3 relative">
                
                <!-- 16:9 Image with Order & Status -->
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-900 border border-slate-200">
                    @if($place->image)
                        <img src="{{ asset($place->image) }}" alt="{{ $place->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-amber-50/50 text-amber-600">
                            <i class="fa-solid fa-city text-2xl"></i>
                            <span class="text-[10px] font-bold mt-1">16:9 Format</span>
                        </div>
                    @endif
                    <div class="absolute top-2.5 left-2.5 bg-black/70 backdrop-blur-md text-white text-[10px] font-black px-2 py-0.5 rounded-lg border border-white/10">
                        #{{ $place->sr_no }}
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $place->status ? 'bg-amber-500 text-white' : 'bg-slate-800 text-slate-300' }}">
                            {{ $place->status ? 'TV Live' : 'Hidden' }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-1.5">
                    <h4 class="text-sm font-extrabold text-slate-900 line-clamp-1">{{ $place->title }}</h4>
                    
                    @if(!empty($tags) && count($tags) > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($tags, 0, 3) as $tag)
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $tag }}
                                </span>
                            @endforeach
                            @if(count($tags) > 3)
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-slate-100 text-slate-600">+{{ count($tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif

                    <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $place->description ?: 'No description provided.' }}
                    </p>
                </div>

                <!-- Controls & Actions -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" onchange="toggleOurCityStatus({{ $place->id }}, this)" {{ $place->status ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>

                    <div class="flex items-center gap-1.5">
                        <button onclick="openViewModal(this)" data-city="{{ json_encode($place) }}" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                        <button onclick="triggerEditMode({{ json_encode($place) }})" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-700 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button type="button" onclick="confirmDeleteCityPlace({{ $place->id }}, '{{ addslashes($place->title) }}')" class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 flex items-center justify-center text-xs">
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

@include('hotel_admin.our_city.modal')
@endsection

@section('scripts')
<script>
    let highlightsCount = 0;
    const maxHighlights = 4;
    let activeFilter = 'all';

    function filterCityPlaces() {
        const query = document.getElementById('citySearchInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.city-item');
        
        items.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            const tags = item.getAttribute('data-tags') || '';
            const desc = item.getAttribute('data-desc') || '';
            const status = item.getAttribute('data-status') || '';
            
            const matchesQuery = !query || title.includes(query) || tags.includes(query) || desc.includes(query);
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
        filterCityPlaces();
    }

    function openOurCityModal() {
        const modal = document.getElementById('ourCityFormModal');
        const form = document.getElementById('ourCityForm');
        form.reset();
        form.action = "{{ route('hotel.our-city.store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('formModeBadge').innerText = 'Add Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700';
        document.getElementById('formTitleText').innerText = 'Add New City Attraction';
        document.getElementById('submitBtnText').innerText = 'Save Attraction';
        document.getElementById('highlightsContainer').innerHTML = '';
        highlightsCount = 0;
        addHighlightInput('');
        clearImagePreview();
        updateCharCounter(document.getElementById('cityDescriptionInput'));
        modal.classList.remove('hidden');
    }

    function closeOurCityModal() {
        document.getElementById('ourCityFormModal').classList.add('hidden');
    }

    function triggerEditMode(place) {
        openOurCityModal();
        const form = document.getElementById('ourCityForm');
        form.action = `/hotel/our-city/${place.id}`;
        document.getElementById('methodContainer').innerHTML = `@method('PUT')`;
        document.getElementById('formModeBadge').innerText = 'Edit Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700';
        document.getElementById('formTitleText').innerText = `Editing: ${place.title}`;
        document.getElementById('submitBtnText').innerText = 'Update Attraction';

        document.getElementById('cityTitleInput').value = place.title || '';
        document.getElementById('citySrNoInput').value = place.sr_no || 1;
        document.getElementById('cityDescriptionInput').value = place.description || '';
        updateCharCounter(document.getElementById('cityDescriptionInput'));

        if (place.image) {
            document.getElementById('imagePreview').src = `/${place.image}`;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadPrompt').classList.add('hidden');
        }

        document.getElementById('highlightsContainer').innerHTML = '';
        highlightsCount = 0;

        let tags = place.attractions || place.features || [];
        if (typeof tags === 'string') {
            try { tags = JSON.parse(tags); } catch(e) { tags = []; }
        }
        if (Array.isArray(tags) && tags.length > 0) {
            tags.forEach(tag => addHighlightInput(tag));
        } else {
            addHighlightInput('');
        }
    }

    function addHighlightInput(val = '') {
        if (highlightsCount >= maxHighlights) return;
        highlightsCount++;
        const container = document.getElementById('highlightsContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 highlight-row';
        div.innerHTML = `
            <div class="relative flex-1">
                <i class="fa-solid fa-location-dot absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                <input type="text" name="attractions[]" value="${(val || '').replace(/"/g, '&quot;')}" placeholder="e.g. Distance: 3.5 km / Entry: Free" 
                       maxlength="100"
                       class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-amber-500 transition-all">
            </div>
            <button type="button" onclick="removeHighlightInput(this)" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        `;
        container.appendChild(div);
        updateHighlightBadge();
    }

    function removeHighlightInput(btn) {
        btn.closest('.highlight-row').remove();
        highlightsCount--;
        updateHighlightBadge();
    }

    function updateHighlightBadge() {
        const badge = document.getElementById('specCountBadge');
        if (badge) badge.innerText = `${highlightsCount} / ${maxHighlights}`;
        const addBtn = document.getElementById('addHighlightBtn');
        if (addBtn) {
            if (highlightsCount >= maxHighlights) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    function handleImagePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('hidden');
                document.getElementById('uploadPrompt').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImagePreview(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        document.getElementById('cityImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function updateCharCounter(textarea) {
        const max = 500;
        const len = textarea ? textarea.value.length : 0;
        const remaining = max - len;
        const counterEl = document.getElementById('charCounter');
        if (counterEl) counterEl.innerText = `${remaining} left`;
    }

    function openViewModal(btn) {
        const place = JSON.parse(btn.getAttribute('data-city'));
        document.getElementById('modalViewTitle').innerText = place.title;
        document.getElementById('modalViewSrNo').innerText = `#${place.sr_no}`;
        document.getElementById('modalViewDesc').innerText = place.description || 'No description provided.';
        document.getElementById('modalViewImage').src = place.image ? `/${place.image}` : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="337" fill="%23f1f5f9"><rect width="100%" height="100%"/></svg>';

        const tagBox = document.getElementById('modalViewHighlights');
        tagBox.innerHTML = '';
        let tags = place.attractions || place.features || [];
        if (typeof tags === 'string') {
            try { tags = JSON.parse(tags); } catch(e) { tags = []; }
        }
        if (Array.isArray(tags)) {
            tags.forEach(t => {
                const sp = document.createElement('span');
                sp.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200';
                sp.innerText = t;
                tagBox.appendChild(sp);
            });
        }
        document.getElementById('viewCityModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewCityModal').classList.add('hidden');
    }

    function toggleOurCityStatus(id, checkbox) {
        const isChecked = checkbox.checked;
        const statusLabels = document.querySelectorAll(`.status-label-${id}`);
        statusLabels.forEach(l => {
            l.textContent = isChecked ? 'Visible' : 'Hidden';
            l.className = `block text-[10px] font-bold mt-1 status-label-${id} ${isChecked ? 'text-amber-600' : 'text-slate-400'}`;
        });

        const row = document.getElementById(`city-row-${id}`);
        if (row) row.setAttribute('data-status', isChecked ? 'active' : 'hidden');
        const card = document.getElementById(`city-card-${id}`);
        if (card) card.setAttribute('data-status', isChecked ? 'active' : 'hidden');

        fetch(`/hotel/our-city/${id}/toggle-status`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
                    title: `Attraction is now ${isChecked ? 'visible' : 'hidden'} on TVs`
                });
            }
        });
    }

    function confirmDeleteCityPlace(id, title) {
        if (window.Swal) {
            Swal.fire({
                title: 'Delete City Attraction?',
                text: `Are you sure you want to remove "${title}" from your Smart TV city guide?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-city-form-${id}`).submit();
                }
            });
        } else {
            if (confirm(`Delete attraction "${title}"?`)) {
                document.getElementById(`delete-city-form-${id}`).submit();
            }
        }
    }
</script>
@endsection
