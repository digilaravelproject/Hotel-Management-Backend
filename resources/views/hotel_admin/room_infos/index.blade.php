@extends('layouts.hotel_admin')

@section('title', 'Manage Room Info - Hotel Admin')
@section('page_title', 'Room Information Management')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bed text-indigo-600"></i>
                Hotel Room Information
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure room categories, features, descriptions (up to 250 chars), specifications (max 4), and 16:9 widescreen visuals for smart TV display.</p>
        </div>
        <button onclick="openRoomInfoModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Room Info</span>
        </button>
    </div>

    <!-- Table of Room Infos -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active Room Information List ({{ count($roomInfos) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Room Title</th>
                        <th class="px-6 py-4">Specifications (Max 4)</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($roomInfos as $info)
                        <tr id="room-info-row-{{ $info->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-indigo-600">#{{ $info->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($info->image)
                                    <img src="{{ asset($info->image) }}" alt="{{ $info->title }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100 text-xs">
                                        #{{ $info->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $info->title }}</td>
                            <td class="px-6 py-4">
                                @if(!empty($info->specifications) && is_array($info->specifications))
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($info->specifications as $spec)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-1 text-indigo-500"></i> {{ $spec }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No specifications</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $info->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleRoomInfoStatus({{ $info->id }})" {{ $info->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-roominfo="{{ json_encode($info) }}" title="Quick Preview" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="triggerEditMode({{ json_encode($info) }})" title="Edit Room Info" class="p-2 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.room-infos.destroy', $info->id) }}" method="POST" onsubmit="return confirm('Delete this room info entry?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Room Info" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-bed text-4xl block mb-3 text-slate-300"></i>
                                No room info items found. Click 'Add New Room Info' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Room Info POPUP MODAL (2-Column Wireframe Layout) -->
<div id="roomInfoFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700">
                    Add Mode
                </span>
                <h3 id="formTitleText" class="text-lg font-extrabold text-slate-900">Create New Room Info Entry</h3>
            </div>
            <button type="button" onclick="closeRoomInfoModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="roomInfoForm" action="{{ route('hotel.room-infos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <!-- Layout Grid inside Modal: Main Area (Left) vs Sidebar Area (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- MAIN CONTENT AREA (Left 2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Top Row: TITLE input & DISPLAY ORDER -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-2">
                            <label for="roomTitleInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                ROOM TITLE / CATEGORY NAME <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="roomTitleInput" 
                                   name="title" 
                                   required 
                                   placeholder="e.g., Deluxe King Suite, Executive Sea View Room" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-2">
                            <label for="roomSrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                SR NO <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="roomSrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($roomInfos) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all">
                        </div>
                    </div>

                    <!-- UPLOAD IMAGE BOX (Strict 16:9 Ratio - 1920x1080 Widescreen Display) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                UPLOAD ROOM PHOTO / IMAGE
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px (16:9)
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/80 hover:bg-indigo-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[220px] relative group cursor-pointer">
                            <input type="file" 
                                   id="roomImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Container (Strict 16:9 Widescreen Frame) -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-xs">
                                <div class="relative group/preview w-full">
                                    <img id="imagePreview" src="" alt="Room Preview" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl border-2 border-indigo-200 shadow-md">
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
                                    <p class="text-sm font-extrabold text-slate-800">Drag & drop room image here, or <span class="text-indigo-600 underline">Browse</span></p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-1">
                                        Required Size: <span class="font-bold text-slate-700">1920 × 1080 px (16:9 Widescreen)</span> • PNG, SVG, JPG, WEBP (Max 5MB • Auto-compressed to WebP ≤ 1MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CONFIGURATION (Right Sidebar: Description & Specifications Panel) -->
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-5">
                    <div class="space-y-5">
                        
                        <!-- Description Section with 250 Character Limit -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="roomDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-indigo-600"></i>
                                    DESCRIPTION
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                    Max 250
                                </span>
                            </div>

                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                Enter room details shown on guest TV screen.
                            </p>

                            <textarea id="roomDescriptionInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="250" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Provide room details, comfort description, layout details..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all resize-none"></textarea>
                            
                            <!-- Real-time Interactive Character Counter -->
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span id="charLimitWarning" class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                    250 / 250 characters remaining
                                </span>
                            </div>
                        </div>

                        <!-- Room Specifications Section (Max 4 Features) -->
                        <div class="space-y-3 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-list-check text-indigo-600"></i>
                                    ROOM SPECIFICATIONS
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Max 4
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium leading-tight">
                                Key room highlights (e.g. King Bed, Balcony View, Jacuzzi, Smart AC).
                            </p>

                            <div id="specificationsContainer" class="space-y-2">
                                <!-- Dynamic input rows generated via JS -->
                            </div>

                            <button type="button" id="addSpecBtn" onclick="addSpecField()" class="w-full py-2 px-3 rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center space-x-1.5 transition-all">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add Specification (<span id="specCountBadge">0</span>/4)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar Tips -->
                    <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-[11px] text-indigo-900 space-y-1 mt-3">
                        <p class="font-bold flex items-center gap-1">
                            <i class="fa-solid fa-tv text-indigo-600"></i> 16:9 TV Display Format:
                        </p>
                        <p class="text-[10px] text-indigo-700 leading-tight">High resolution 1920×1080 px images show full-screen on guest room TVs with crisp specification badges.</p>
                    </div>
                </div>

            </div>

            <!-- Modal Action Buttons Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-100">
                <button type="button" onclick="closeRoomInfoModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" id="submitFormBtn" class="px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitFormBtnText">Save Room Info</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal (16:9 Preview) -->
<div id="viewRoomInfoModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 text-center space-y-4 shadow-2xl">
        <div id="viewImagePreview"></div>
        <h3 id="viewTitle" class="text-lg font-extrabold text-slate-900"></h3>
        
        <!-- View Specifications Pills -->
        <div id="viewSpecsContainer" class="flex flex-wrap items-center justify-center gap-1.5"></div>

        <p id="viewDescription" class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-left"></p>
        
        <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">Close Preview</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const storeUrl = "{{ route('hotel.room-infos.store') }}";
    const maxSpecsAllowed = 4;

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

    function addSpecField(value = '') {
        const container = document.getElementById('specificationsContainer');
        const currentCount = container.children.length;
        if (currentCount >= maxSpecsAllowed) return;

        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 spec-row';
        div.innerHTML = `
            <div class="relative flex-1">
                <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                <input type="text" 
                       name="specifications[]" 
                       value="${value.replace(/"/g, '&quot;')}" 
                       placeholder="e.g. King Bed / Balcony View" 
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
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-100 text-indigo-700';
        document.getElementById('formTitleText').textContent = 'Create New Room Info Entry';
        document.getElementById('submitFormBtnText').textContent = 'Save Room Info';

        form.reset();
        clearImagePreview();
        document.getElementById('specificationsContainer').innerHTML = '';
        addSpecField(''); // Default 1 empty spec field
        updateCharCounter(document.getElementById('roomDescriptionInput'));
    }

    function triggerEditMode(info) {
        const modal = document.getElementById('roomInfoFormModal');
        const form = document.getElementById('roomInfoForm');

        form.action = `/hotel/room-infos/${info.id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';

        document.getElementById('formModeBadge').textContent = 'Edit Mode';
        document.getElementById('formModeBadge').className = 'px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-700';
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
        if (e) e.preventDefault();
        document.getElementById('roomImageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadPrompt').classList.remove('hidden');
    }

    function openViewModal(btn) {
        const info = JSON.parse(btn.getAttribute('data-roominfo'));
        document.getElementById('viewTitle').textContent = info.title;
        document.getElementById('viewDescription').textContent = info.description || 'No description provided.';
        
        // Specs preview
        const specsContainer = document.getElementById('viewSpecsContainer');
        specsContainer.innerHTML = '';
        if (info.specifications && Array.isArray(info.specifications) && info.specifications.length > 0) {
            info.specifications.forEach(spec => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100';
                span.innerHTML = `<i class="fa-solid fa-circle-check text-[10px] mr-1 text-indigo-500"></i> ${spec}`;
                specsContainer.appendChild(span);
            });
        }

        const imgContainer = document.getElementById('viewImagePreview');
        if (info.image) {
            imgContainer.innerHTML = `<img src="/${info.image}" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl mx-auto border-2 border-indigo-100 shadow-md">`;
        } else {
            imgContainer.innerHTML = `<div style="aspect-ratio: 16 / 9; width: 100%;" class="rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto border border-indigo-100">#${info.sr_no}</div>`;
        }
        document.getElementById('viewRoomInfoModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewRoomInfoModal').classList.add('hidden');
    }

    function toggleRoomInfoStatus(id) {
        fetch(`/hotel/room-infos/${id}/toggle-status`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const descInput = document.getElementById('roomDescriptionInput');
        if (descInput) updateCharCounter(descInput);
    });
</script>
@endsection
