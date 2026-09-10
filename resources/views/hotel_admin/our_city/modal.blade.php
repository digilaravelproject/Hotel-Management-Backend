<!-- Add/Edit Our City Modal -->
<div id="ourCityFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-700">
                    Add Mode
                </span>
                <h3 id="formTitleText" class="text-lg font-extrabold text-slate-900">Add New City Attraction</h3>
            </div>
            <button type="button" onclick="closeOurCityModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="ourCityForm" action="{{ route('hotel.our-city.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 2 Cols: Title, Sr No, 16:9 Image -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-2">
                            <label for="cityTitleInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                ATTRACTION / PLACE NAME <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="cityTitleInput" 
                                   name="title" 
                                   required 
                                   placeholder="e.g., Shaniwar Wada, Marine Drive, Taj Mahal" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-2">
                            <label for="citySrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                SR NO <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="citySrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($cityPlaces) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all">
                        </div>
                    </div>

                    <!-- 16:9 Image Upload -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                UPLOAD PLACE PHOTO / 16:9 VISUAL
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px (16:9)
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/80 hover:bg-amber-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[220px] relative group cursor-pointer">
                            <input type="file" 
                                   id="cityImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Frame -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-xs">
                                <div class="relative group/preview w-full">
                                    <img id="imagePreview" src="" alt="Place Preview" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;" class="rounded-2xl border-2 border-amber-300 shadow-md">
                                    <button type="button" onclick="clearImagePreview(event)" class="absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-amber-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <div id="uploadPrompt" class="space-y-3 pointer-events-none">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center mx-auto text-amber-500 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-800">Drag & drop 16:9 image here, or <span class="text-amber-600 underline">Browse</span></p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-1">
                                        Widescreen 1920×1080 px • Auto-converted to optimized WebP
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Description & Highlights -->
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-5">
                    <div class="space-y-5">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="cityDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-amber-600"></i>
                                    DESCRIPTION
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                    Max 500
                                </span>
                            </div>
                            <textarea id="cityDescriptionInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="500" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Brief history, significance, or guest tips for this place..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none"></textarea>
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                                    500 / 500 chars left
                                </span>
                            </div>
                        </div>

                        <!-- Highlights Section (Max 4) -->
                        <div class="space-y-3 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-tags text-amber-600"></i>
                                    HIGHLIGHTS / TAGS (MAX 4)
                                </label>
                                <span id="specCountBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">0 / 4</span>
                            </div>
                            <p class="text-[11px] text-slate-500">e.g. Distance, Timing, Entry Fee, Best Time to Visit</p>

                            <div id="highlightsContainer" class="space-y-2">
                                <!-- Dynamic highlight inputs inserted here -->
                            </div>

                            <button type="button" id="addHighlightBtn" onclick="addHighlightInput()" class="w-full py-2.5 px-3 rounded-xl border border-dashed border-amber-300 text-amber-700 hover:bg-amber-50 text-xs font-bold transition-colors flex items-center justify-center space-x-1.5">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Add Highlight Tag</span>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-200/80 flex items-center space-x-3">
                        <button type="button" onclick="closeOurCityModal()" class="w-1/2 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">Cancel</button>
                        <button type="submit" id="submitBtn" class="w-1/2 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-lg shadow-amber-500/25 transition-all">Save Attraction</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quick Preview Modal -->
<div id="viewCityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl space-y-4 relative">
        <div class="relative" style="aspect-ratio: 16 / 9;">
            <img id="modalViewImage" src="" alt="Place View" class="w-full h-full object-cover">
            <button onclick="closeViewModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="absolute bottom-3 left-3 bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-lg" id="modalViewSrNo">#1</div>
        </div>
        <div class="p-6 space-y-4">
            <h3 id="modalViewTitle" class="text-xl font-extrabold text-slate-900"></h3>
            <div id="modalViewHighlights" class="flex flex-wrap gap-1.5"></div>
            <p id="modalViewDesc" class="text-sm text-slate-600 leading-relaxed"></p>
        </div>
    </div>
</div>
