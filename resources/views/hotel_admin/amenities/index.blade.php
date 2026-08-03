@extends('layouts.hotel_admin')

@section('title', 'Manage Amenities - Hotel Admin')
@section('page_title', 'Hotel Amenities Management')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-spa text-indigo-600"></i>
                Hotel Guest Amenities
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure, reorder, or update guest amenities available at your hotel for smart TV display (16:9 widescreen format).</p>
        </div>
        <button onclick="openAmenityModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Amenity</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
            <div class="flex items-center space-x-2 font-bold">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-base"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside pl-6 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table of Amenities -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active Amenities List ({{ count($amenities) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Amenity Title</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($amenities as $amenity)
                        <tr id="amenity-row-{{ $amenity->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-indigo-600">#{{ $amenity->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($amenity->image)
                                    <img src="{{ asset($amenity->image) }}" alt="{{ $amenity->name }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100 text-xs">
                                        #{{ $amenity->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $amenity->name }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $amenity->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }})" {{ $amenity->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($amenity) }})" title="Edit Amenity" class="p-2 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Delete this amenity?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Amenity" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-spa text-4xl block mb-3 text-slate-300"></i>
                                No hotel amenities found. Click 'Add New Amenity' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Amenity POPUP MODAL (Wireframe Layout in Floating Dialog) -->
<div id="amenityFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <h3 id="formTitleText" class="text-lg font-extrabold text-slate-900">Create New Amenity Item</h3>
            </div>
            <button type="button" onclick="closeAmenityModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="amenityForm" action="{{ route('hotel.amenities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <!-- Layout Grid inside Modal: Main Area (Left) vs Sidebar Area (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- MAIN CONTENT AREA (Left 2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Top Row: TITLE input & DISPLAY ORDER -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-2">
                            <label for="amenityNameInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                TITLE / AMENITY NAME <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="amenityNameInput" 
                                   name="name" 
                                   required 
                                   placeholder="e.g., Infinity Swimming Pool, Spa & Wellness Center" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-2">
                            <label for="amenitySrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                SR NO <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="amenitySrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($amenities) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                    </div>

                    <!-- UPLOAD IMAGE BOX (Strict 16:9 Ratio - 1920x1080 Widescreen Display) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                UPLOAD IMAGE / ICON
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px (16:9)
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[220px] relative group cursor-pointer">
                            <input type="file" 
                                   id="amenityImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Container (Strict 16:9 Widescreen Frame) -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-xs">
                                <div class="relative group/preview w-full">
                                    <img id="imagePreview" src="" alt="Amenity Preview" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl border-2 border-indigo-200 shadow-md">
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
                                    <p class="text-sm font-extrabold text-slate-800">Drag & drop image file here, or <span class="text-indigo-600 underline">Browse</span></p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-1">
                                        Required Size: <span class="font-bold text-slate-700">1920 × 1080 px (16:9 Widescreen)</span> • PNG, SVG, JPG, WEBP (Max 5MB • Auto-compressed to WebP ≤ 1MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CONFIGURATION (Right Sidebar: Description Panel) -->
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                            <label for="amenityDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-align-left text-indigo-600"></i>
                                DESCRIPTION
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                Contextual Sidebar
                            </span>
                        </div>

                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                            Enter details displayed on room TVs when guests highlight this amenity. Max 100 characters allowed.
                        </p>

                        <div class="space-y-2">
                            <textarea id="amenityDescriptionInput" 
                                      name="description" 
                                      rows="5" 
                                      maxlength="100" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Provide a concise description for guest TV interface..." 
                                      class="w-full p-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <!-- Real-time Interactive Character Counter -->
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span id="charLimitWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    100 / 100 characters remaining
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Tips -->
                    <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-[11px] text-indigo-900 space-y-1">
                        <p class="font-bold flex items-center gap-1">
                            <i class="fa-solid fa-tv text-indigo-600"></i> 16:9 TV Display Format:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-tight">Using 1920×1080 px images ensures crisp full-screen display on HD & 4K hotel smart TVs.</p>
                    </div>
                </div>

            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-100">
                <button type="button" onclick="closeAmenityModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" id="submitFormBtn" class="px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitFormBtnText">Save Amenity</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal (16:9 Preview) -->
<div id="viewAmenityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 text-center space-y-4 shadow-2xl">
        <div id="viewImagePreview"></div>
        <h3 id="viewName" class="text-lg font-extrabold text-slate-900"></h3>
        <p id="viewDescription" class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100"></p>
        <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">Close Preview</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const storeUrl = "{{ route('hotel.amenities.store') }}";

    function updateCharCounter(textarea) {
        const maxLength = 100;
        const currentLength = textarea.value.length;
        const remaining = maxLength - currentLength;
        const counterEl = document.getElementById('charCounter');
        const warningEl = document.getElementById('charLimitWarning');

        counterEl.textContent = `${remaining} / 100 characters remaining`;

        if (remaining <= 10) {
            counterEl.className = 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200 animate-pulse';
            warningEl.textContent = 'Near limit!';
            warningEl.className = 'text-rose-600 font-bold';
        } else {
            counterEl.className = 'text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100';
            warningEl.textContent = 'Character Limit';
            warningEl.className = 'text-slate-400 font-medium';
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
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700';
        document.getElementById('formTitleText').textContent = 'Create New Amenity Item';
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
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-700';
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
        if (e) e.preventDefault();
        document.getElementById('amenityImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function openViewModal(btn) {
        const amenity = JSON.parse(btn.getAttribute('data-amenity'));
        document.getElementById('viewName').textContent = amenity.name;
        document.getElementById('viewDescription').textContent = amenity.description || 'No description provided.';
        const imgContainer = document.getElementById('viewImagePreview');
        if (amenity.image) {
            imgContainer.innerHTML = `<img src="/${amenity.image}" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl mx-auto border-2 border-indigo-100 shadow-md">`;
        } else {
            imgContainer.innerHTML = `<div style="aspect-ratio: 16 / 9; width: 100%;" class="rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto border border-indigo-100">#${amenity.sr_no}</div>`;
        }
        document.getElementById('viewAmenityModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewAmenityModal').classList.add('hidden');
    }

    function toggleAmenityStatus(id) {
        fetch(`/hotel/amenities/${id}/toggle-status`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('amenityDescriptionInput');
        if (descInput) updateCharCounter(descInput);
    });
</script>
@endsection
