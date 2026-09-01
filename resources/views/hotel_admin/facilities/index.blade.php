@extends('layouts.hotel_admin')

@section('title', 'Manage Hotel Facilities - Hotel Admin')
@section('page_title', 'Hotel Facilities & Info')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-hotel text-indigo-600"></i>
                Hotel Information & Facilities
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure hotel areas, dining, spa, pools, banquet halls, descriptions (up to 250 chars), features (max 4), and 16:9 widescreen visuals for smart TV display.</p>
        </div>
        <button onclick="openFacilityModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Facility</span>
        </button>
    </div>

    <!-- Table of Hotel Facilities -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active Hotel Facilities List ({{ count($facilities) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Facility / Area Title</th>
                        <th class="px-6 py-4">Key Features (Max 4)</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($facilities as $index => $facility)
                        <tr id="facility-row-{{ $facility['id'] }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-indigo-600">#{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                @if(!empty($facility['image']))
                                    <img src="{{ asset($facility['image']) }}" alt="{{ $facility['title'] }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100 text-xs">
                                        #{{ $index + 1 }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $facility['title'] }}</td>
                            <td class="px-6 py-4">
                                @if(!empty($facility['features']) && is_array($facility['features']))
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($facility['features'] as $feature)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-1 text-indigo-500"></i> {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No features</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $facility['description'] ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-facility="{{ json_encode($facility) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($facility) }})" title="Edit Facility" class="p-2 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.facilities.destroy', $facility['id']) }}" method="POST" onsubmit="return confirm('Delete this hotel facility item?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Facility" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-hotel text-4xl block mb-3 text-slate-300"></i>
                                No hotel facilities added yet. Click 'Add New Facility' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Facility POPUP MODAL (2-Column Wireframe Layout) -->
<div id="facilityFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <h3 id="formTitleText" class="text-lg font-extrabold text-slate-900">Create New Facility Entry</h3>
            </div>
            <button type="button" onclick="closeFacilityModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="facilityForm" action="{{ route('hotel.facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <!-- Layout Grid: Main (Left) vs Sidebar (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- MAIN CONTENT AREA (Left 2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="space-y-2">
                        <label for="facilityTitleInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            FACILITY / AREA NAME <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="facilityTitleInput" 
                               name="title" 
                               required 
                               placeholder="e.g., Rooftop Infinity Pool, Grand Ballroom, Sky Lounge" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                    </div>

                    <!-- UPLOAD IMAGE BOX (Strict 16:9 Ratio - 1920x1080 Widescreen Display) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                UPLOAD FACILITY PHOTO <span id="imageRequiredBadge" class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px (16:9)
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[220px] relative group cursor-pointer">
                            <input type="file" 
                                   id="facilityImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Container (Strict 16:9 Widescreen Frame) -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-xs">
                                <div class="relative group/preview w-full">
                                    <img id="imagePreview" src="" alt="Facility Preview" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl border-2 border-indigo-200 shadow-md">
                                    <button type="button" onclick="clearImagePreview(event)" class="absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-indigo-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <!-- Default Upload Prompt -->
                            <div id="uploadPrompt" class="space-y-3 pointer-events-none">
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

                <!-- SIDEBAR CONFIGURATION (Right Sidebar: Description & Features Panel) -->
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-5">
                    <div class="space-y-5">
                        
                        <!-- Description Section with 250 Character Limit -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="facilityDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-indigo-600"></i>
                                    DESCRIPTION
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                    Max 250
                                </span>
                            </div>

                            <textarea id="facilityDescriptionInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="250" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Provide overview, opening hours, or details for guest TV interface..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <!-- Real-time Interactive Character Counter -->
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span id="charLimitWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    250 / 250 characters remaining
                                </span>
                            </div>
                        </div>

                        <!-- Key Features Section (Max 4 Features) -->
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
                                Highlights (e.g. Heated Water, Skyline View, 24x7 Open).
                            </p>

                            <div id="featuresContainer" class="space-y-2">
                                <!-- Dynamic input rows generated via JS -->
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
                            <i class="fa-solid fa-tv text-indigo-600"></i> 16:9 TV Display Format:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-tight">High resolution 1920×1080 px images show full-screen on guest room TVs with crisp feature badges.</p>
                    </div>
                </div>

            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-100">
                <button type="button" onclick="closeFacilityModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" id="submitFormBtn" class="px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitFormBtnText">Save Facility</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal (16:9 Preview) -->
<div id="viewFacilityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 text-center space-y-4 shadow-2xl">
        <div id="viewImagePreview"></div>
        <h3 id="viewTitle" class="text-lg font-extrabold text-slate-900"></h3>
        
        <!-- View Features Pills -->
        <div id="viewFeaturesContainer" class="flex flex-wrap items-center justify-center gap-1.5"></div>

        <p id="viewDescription" class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-left"></p>
        
        <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">Close Preview</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const storeUrl = "{{ route('hotel.facilities.store') }}";
    const maxFeaturesAllowed = 4;

    function updateCharCounter(textarea) {
        const maxLength = 250;
        const currentLength = textarea.value.length;
        const remaining = maxLength - currentLength;
        const counterEl = document.getElementById('charCounter');
        const warningEl = document.getElementById('charLimitWarning');

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
        const container = document.getElementById('featuresContainer');
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
                       placeholder="e.g. Skyline View / Heated Water" 
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
        const container = document.getElementById('featuresContainer');
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

    function openFacilityModal() {
        resetFormToAddMode();
        document.getElementById('facilityFormModal').classList.remove('hidden');
        document.getElementById('facilityTitleInput').focus();
    }

    function closeFacilityModal() {
        document.getElementById('facilityFormModal').classList.add('hidden');
        document.getElementById('facilityForm').reset();
        clearImagePreview();
    }

    function resetFormToAddMode() {
        const form = document.getElementById('facilityForm');
        form.action = storeUrl;
        document.getElementById('methodContainer').innerHTML = '';
        
        document.getElementById('formModeBadge').textContent = 'Add Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700';
        document.getElementById('formTitleText').textContent = 'Create New Facility Entry';
        document.getElementById('submitFormBtnText').textContent = 'Save Facility';
        document.getElementById('facilityImageInput').required = true;

        form.reset();
        clearImagePreview();
        document.getElementById('featuresContainer').innerHTML = '';
        addFeatureField(''); // Default 1 empty feature field
        updateCharCounter(document.getElementById('facilityDescriptionInput'));
    }

    function triggerEditMode(facility) {
        const modal = document.getElementById('facilityFormModal');
        const form = document.getElementById('facilityForm');

        form.action = `/hotel/facilities/${facility.id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';

        document.getElementById('formModeBadge').textContent = 'Edit Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-700';
        document.getElementById('formTitleText').textContent = `Editing: ${facility.title}`;
        document.getElementById('submitFormBtnText').textContent = 'Update Facility';
        document.getElementById('facilityImageInput').required = false;

        document.getElementById('facilityTitleInput').value = facility.title;
        document.getElementById('facilityDescriptionInput').value = facility.description || '';

        updateCharCounter(document.getElementById('facilityDescriptionInput'));

        // Load features
        const featuresContainer = document.getElementById('featuresContainer');
        featuresContainer.innerHTML = '';
        if (facility.features && Array.isArray(facility.features) && facility.features.length > 0) {
            facility.features.slice(0, 4).forEach(f => addFeatureField(f));
        } else {
            addFeatureField('');
        }

        if (facility.image) {
            document.getElementById('imagePreview').src = `/${facility.image}`;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadPrompt').classList.add('hidden');
        } else {
            clearImagePreview();
        }

        modal.classList.remove('hidden');
        document.getElementById('facilityTitleInput').focus();
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
        if (e) e.preventDefault();
        document.getElementById('facilityImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function openViewModal(btn) {
        const facility = JSON.parse(btn.getAttribute('data-facility'));
        document.getElementById('viewTitle').textContent = facility.title;
        document.getElementById('viewDescription').textContent = facility.description || 'No description provided.';
        
        // Features preview
        const featuresContainer = document.getElementById('viewFeaturesContainer');
        featuresContainer.innerHTML = '';
        if (facility.features && Array.isArray(facility.features) && facility.features.length > 0) {
            facility.features.forEach(f => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100';
                span.innerHTML = `<i class="fa-solid fa-circle-check text-[10px] mr-1 text-indigo-500"></i> ${f}`;
                featuresContainer.appendChild(span);
            });
        }

        const imgContainer = document.getElementById('viewImagePreview');
        if (facility.image) {
            imgContainer.innerHTML = `<img src="/${facility.image}" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl mx-auto border-2 border-indigo-100 shadow-md">`;
        } else {
            imgContainer.innerHTML = `<div style="aspect-ratio: 16 / 9; width: 100%;" class="rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto border border-indigo-100">No Image</div>`;
        }
        document.getElementById('viewFacilityModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewFacilityModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('facilityDescriptionInput');
        if (descInput) updateCharCounter(descInput);
    });
</script>
@endsection
