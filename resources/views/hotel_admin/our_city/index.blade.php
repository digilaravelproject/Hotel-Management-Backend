@extends('layouts.hotel_admin')

@section('title', 'Manage Our City - Hotel Admin')
@section('page_title', 'Our City & Local Attractions')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-city text-amber-500"></i>
                Our City & Local Attractions
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure local tourist spots, heritage landmarks, distance, descriptions, and 16:9 widescreen visuals for guest smart TVs.</p>
        </div>
        <button onclick="openOurCityModal()" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New City Place</span>
        </button>
    </div>

    <!-- Table of City Places -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active City Attractions List ({{ count($cityPlaces) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Place / Attraction</th>
                        <th class="px-6 py-4">Highlights / Tags (Max 4)</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cityPlaces as $place)
                        @php
                            $tags = $place->attractions ?? $place->features ?? [];
                            if (is_string($tags)) {
                                $decoded = json_decode($tags, true);
                                $tags = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$tags];
                            }
                            $tags = is_array($tags) ? array_values(array_filter($tags)) : [];
                        @endphp
                        <tr id="city-row-{{ $place->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-amber-600">#{{ $place->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($place->image)
                                    <img src="{{ asset($place->image) }}" alt="{{ $place->title }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-amber-50 text-amber-600 font-bold flex items-center justify-center border border-amber-100 text-xs">
                                        #{{ $place->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $place->title }}</td>
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
                                    <span class="text-slate-400 italic text-[11px]">No highlights</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $place->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleOurCityStatus({{ $place->id }})" {{ $place->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-city="{{ json_encode($place) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($place) }})" title="Edit City Place" class="p-2 rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.our-city.destroy', $place->id) }}" method="POST" onsubmit="return confirm('Delete this city attraction?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete City Place" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-city text-4xl block mb-3 text-slate-300"></i>
                                No city attraction items found. Click 'Add New City Place' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('hotel_admin.our_city.modal')
@endsection

@section('scripts')
<script>
    let highlightsCount = 0;
    const maxHighlights = 4;

    function openOurCityModal() {
        const modal = document.getElementById('ourCityFormModal');
        const form = document.getElementById('ourCityForm');
        form.reset();
        form.action = "{{ route('hotel.our-city.store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('formModeBadge').innerText = 'Add Mode';
        document.getElementById('formTitleText').innerText = 'Add New City Attraction';
        document.getElementById('submitBtn').innerText = 'Save Attraction';
        document.getElementById('highlightsContainer').innerHTML = '';
        highlightsCount = 0;
        updateHighlightBadge();
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
        document.getElementById('formTitleText').innerText = 'Edit City Attraction';
        document.getElementById('submitBtn').innerText = 'Update Attraction';

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
        }
    }

    function addHighlightInput(val = '') {
        if (highlightsCount >= maxHighlights) return;
        highlightsCount++;
        const container = document.getElementById('highlightsContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <input type="text" name="attractions[]" value="${(val || '').replace(/"/g, '&quot;')}" placeholder="e.g. Distance: 3.5 km / Entry: Free" 
                   class="flex-1 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-amber-500 transition-all">
            <button type="button" onclick="removeHighlightInput(this)" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors">
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        `;
        container.appendChild(div);
        updateHighlightBadge();
    }

    function removeHighlightInput(btn) {
        btn.parentElement.remove();
        highlightsCount--;
        updateHighlightBadge();
    }

    function updateHighlightBadge() {
        const badge = document.getElementById('specCountBadge');
        badge.innerText = `${highlightsCount} / ${maxHighlights}`;
        const addBtn = document.getElementById('addHighlightBtn');
        if (highlightsCount >= maxHighlights) {
            addBtn.classList.add('opacity-50', 'pointer-events-none');
        } else {
            addBtn.classList.remove('opacity-50', 'pointer-events-none');
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
        const len = textarea ? textarea.value.length : 0;
        document.getElementById('charCounter').innerText = `${500 - len} / 500 chars left`;
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

    function toggleOurCityStatus(id) {
        fetch(`/hotel/our-city/${id}/toggle-status`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('Status updated successfully');
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
