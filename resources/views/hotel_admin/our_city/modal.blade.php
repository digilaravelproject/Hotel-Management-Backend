<!-- Add/Edit Our City Modal -->
<div id="ourCityFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span id="formModeBadge" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700">
                    Add Mode
                </span>
                <div>
                    <h3 id="formTitleText" class="text-lg font-black text-slate-900 tracking-tight">Add New City Attraction</h3>
                    <p class="text-[11px] text-slate-400 font-medium">16:9 widescreen visuals for guest smart TV city exploration.</p>
                </div>
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
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3 space-y-1.5">
                            <label for="cityTitleInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Attraction / Place Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="cityTitleInput" 
                                   name="title" 
                                   required 
                                   placeholder="e.g., Shaniwar Wada, Marine Drive, Lotus Temple" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all">
                        </div>
                        <div class="sm:col-span-1 space-y-1.5">
                            <label for="citySrNoInput" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Display Sr <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="citySrNoInput" 
                                   name="sr_no" 
                                   min="1" 
                                   required 
                                   value="{{ count($cityPlaces) + 1 }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all">
                        </div>
                    </div>

                    <!-- 16:9 Image Upload Dropzone -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Place Photo (16:9 Widescreen)
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                <i class="fa-solid fa-expand text-[9px]"></i> 1920 × 1080 px recommended
                            </span>
                        </div>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/80 hover:bg-amber-50/20 rounded-3xl p-5 transition-all text-center flex flex-col items-center justify-center min-h-[200px] relative group cursor-pointer">
                            <input type="file" 
                                   id="cityImageInput" 
                                   name="image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml" 
                                   onchange="handleImagePreview(this)" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <!-- Image Preview Frame -->
                            <div id="previewContainer" class="hidden flex flex-col items-center justify-center space-y-3 z-0 w-full max-w-sm">
                                <div class="relative group/preview w-full aspect-video rounded-2xl overflow-hidden border-2 border-amber-300 shadow-md">
                                    <img id="imagePreview" src="" alt="Place Preview" class="w-full h-full object-cover">
                                    <button type="button" onclick="clearImagePreview(event)" class="absolute top-2 right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1.5 shadow-md transition-all z-20">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-amber-600 font-bold">Click or drag new 16:9 image to replace</p>
                            </div>

                            <div id="uploadPrompt" class="space-y-2 pointer-events-none">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-center mx-auto text-amber-500 group-hover:scale-110 group-hover:bg-[#150A42] group-hover:text-white transition-all">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800">Drag & drop 16:9 image here, or <span class="text-amber-600 underline">Browse</span></p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">PNG, JPG, WEBP • Auto-optimized for crisp 4K & HD TV screens</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Description & Highlights -->
                <div class="lg:col-span-1 bg-slate-50/80 border border-slate-200 rounded-3xl p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <!-- Description Section -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                                <label for="cityDescriptionInput" class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-align-left text-amber-600"></i>
                                    Description
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-200 text-slate-600">Max 500</span>
                            </div>
                            <textarea id="cityDescriptionInput" 
                                      name="description" 
                                      rows="4" 
                                      maxlength="500" 
                                      oninput="updateCharCounter(this)" 
                                      placeholder="Brief history, significance, opening hours, or guest tips..." 
                                      class="w-full p-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none"></textarea>
                            
                            <div class="flex items-center justify-between text-[10px] font-bold">
                                <span class="text-slate-400 font-medium">Character Limit</span>
                                <span id="charCounter" class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                                    500 left
                                </span>
                            </div>
                        </div>

                        <!-- Highlights Section (Max 4) -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-tags text-amber-600"></i>
                                    Highlights / Tags
                                </label>
                                <span id="specCountBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">0 / 4 max</span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium leading-tight">e.g. Distance: 3.5 km, Entry: Free, 9 AM - 6 PM</p>

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
                        <button type="button" onclick="closeOurCityModal()" class="w-1/2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">Cancel</button>
                        <button type="submit" id="submitBtn" class="w-1/2 px-4 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#1E0F5C] text-white text-xs font-extrabold shadow-md shadow-indigo-950/20 transition-all flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span id="submitBtnText">Save Attraction</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quick TV Preview Modal -->
<div id="viewCityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl space-y-4 relative animate-in fade-in zoom-in-95 duration-200">
        <div class="relative w-full aspect-video bg-slate-900">
            <img id="modalViewImage" src="" alt="Place View" class="w-full h-full object-cover">
            <button onclick="closeViewModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="absolute bottom-3 left-3 bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-lg shadow-sm" id="modalViewSrNo">#1</div>
        </div>
        <div class="p-6 space-y-3">
            <h3 id="modalViewTitle" class="text-lg font-black text-slate-900 tracking-tight"></h3>
            <div id="modalViewHighlights" class="flex flex-wrap gap-1.5"></div>
            <p id="modalViewDesc" class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100"></p>
            <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">
                Close TV Preview
            </button>
        </div>
    </div>
</div>
