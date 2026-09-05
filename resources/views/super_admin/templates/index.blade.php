@extends('layouts.super_admin')

@section('title', 'TV Templates & Themes Management - Super Admin')
@section('page_title', 'TV App Multi-Theme OTA Engine')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div id="js-alert-container"></div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Active Registered Themes Overview -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-indigo-600"></i>
                <span>Registered TV Themes Catalog</span>
            </h3>
            <span class="text-xs text-slate-500 font-medium">{{ $existingThemes->count() }} Theme(s) Configured</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($existingThemes as $theme)
                @php
                    $latestBuild = $templates->where('theme_id', $theme->theme_id)->where('is_active', true)->first() 
                                    ?? $templates->where('theme_id', $theme->theme_id)->first();
                @endphp
                <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs flex flex-col justify-between space-y-4 hover:border-indigo-300 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-lg overflow-hidden shrink-0">
                                @if($theme->preview_image && Storage::disk('public')->exists($theme->preview_image))
                                    <img src="{{ Storage::url($theme->preview_image) }}" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    #{{ $theme->theme_id }}
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">Theme ID: {{ $theme->theme_id }}</span>
                                    @if($theme->theme_id == 1)
                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Default</span>
                                    @endif
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}</h4>
                            </div>
                        </div>
                        @if($latestBuild && $latestBuild->is_active)
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] uppercase">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase">Inactive</span>
                        @endif
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-3 text-xs space-y-1 text-slate-600 font-medium">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Latest Build:</span>
                            <span class="font-bold text-slate-800">v{{ $latestBuild ? $latestBuild->version : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Total Releases:</span>
                            <span class="font-bold text-slate-800">{{ $templates->where('theme_id', $theme->theme_id)->count() }}</span>
                        </div>
                    </div>

                    <button type="button" onclick="selectThemeForUpdate({{ $theme->theme_id }}, '{{ addslashes($theme->theme_name ?? '') }}')" class="w-full py-2.5 rounded-xl border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold text-xs transition-all flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Upload Update for Theme {{ $theme->theme_id }}</span>
                    </button>
                </div>
            @empty
                <div class="col-span-full bg-white border border-slate-200/80 rounded-3xl p-8 text-center text-slate-400">
                    <i class="fa-solid fa-palette text-3xl mb-2 text-slate-300"></i>
                    <p class="text-xs font-semibold">No themes registered yet. Upload your first theme package below.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Upload / Update Theme Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight" id="formHeaderTitle">Upload or Update TV Theme Package</h3>
                    <p class="text-xs text-slate-500 font-medium">Upload a ZIP package for Theme ID 1 (replace/update) or Theme ID 2+ (new theme).</p>
                </div>
            </div>
            <button type="button" onclick="resetFormToNewTheme({{ $nextThemeId }})" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-xl transition-all">
                + Create New Theme #{{ $nextThemeId }}
            </button>
        </div>
        
        <form id="uploadTemplateForm" action="{{ route('super-admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Theme ID -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Theme ID *</span>
                        <span class="text-[10px] text-slate-400 font-normal">e.g. 1, 2, 3</span>
                    </label>
                    <input type="number" id="themeIdField" name="theme_id" value="{{ $existingThemes->count() > 0 ? 1 : 1 }}" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs font-extrabold text-slate-900">
                    <span class="text-[11px] text-slate-400 font-medium block">Use <strong>1</strong> to update Theme 1, or <strong>2+</strong> for additional themes.</span>
                </div>

                <!-- Theme Name -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Theme Name</label>
                    <input type="text" id="themeNameField" name="theme_name" placeholder="e.g. Classic Luxury / Modern Dark" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs font-medium text-slate-800">
                    <span class="text-[11px] text-slate-400 font-medium block">Displayed to Hotel Admin when choosing themes.</span>
                </div>

                <!-- Custom Version (Optional) -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Version (Optional)</span>
                        <span class="text-[10px] text-slate-400 font-normal">Auto: +0.5</span>
                    </label>
                    <input type="text" id="customVersionField" name="custom_version" placeholder="e.g. 1.0 or 2.0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs font-medium text-slate-800">
                    <span class="text-[11px] text-slate-400 font-medium block">Leave blank to auto-increment for this theme.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                <!-- Theme Preview Image -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Theme Preview Screenshot (Optional)</label>
                    <input type="file" id="previewImageField" name="preview_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <span class="text-[11px] text-slate-400 font-medium block">PNG, JPG, or WEBP up to 5MB. Visual mockup for hotels.</span>
                </div>

                <!-- Template Package ZIP -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Template Package (.zip) *</label>
                    <input type="file" id="templateFileField" name="template_file" required accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white">
                    <span class="text-[11px] text-slate-400 font-medium block">ZIP build package containing web assets for Android TV.</span>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" id="submitBtn" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload & Deploy Theme Build</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Template History List -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-history text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Theme Build Release Log</h3>
                <p class="text-xs text-slate-500 font-medium">Full historical record of OTA updates across all registered theme IDs.</p>
            </div>
        </div>

        @if($templates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-4">Theme</th>
                            <th class="px-4 py-4">Version</th>
                            <th class="px-4 py-4">Package Location</th>
                            <th class="px-4 py-4">Uploaded At</th>
                            <th class="px-4 py-4">Size</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($templates as $template)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 overflow-hidden shrink-0 border border-slate-200">
                                            @if($template->preview_image && Storage::disk('public')->exists($template->preview_image))
                                                <img src="{{ Storage::url($template->preview_image) }}" alt="P" class="w-full h-full object-cover">
                                            @else
                                                #{{ $template->theme_id }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900">
                                                {{ $template->theme_name ?? ('Theme ' . $template->theme_id) }}
                                            </div>
                                            <span class="text-[10px] font-bold text-indigo-600">Theme ID: {{ $template->theme_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 font-extrabold text-rose-600 text-sm">v{{ $template->version }}</td>
                                <td class="px-4 py-4 font-mono">
                                    <a href="{{ Storage::url($template->file_path) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold flex items-center space-x-1">
                                        <i class="fa-solid fa-file-zipper text-slate-400"></i>
                                        <span>{{ basename($template->file_path) }}</span>
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-slate-500 font-medium">{{ $template->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-4 py-4 font-medium text-slate-800">
                                    @php
                                        $sizeBytes = 0;
                                        try {
                                            if (Storage::disk('public')->exists($template->file_path)) {
                                                $sizeBytes = Storage::disk('public')->size($template->file_path);
                                            }
                                        } catch(\Exception $e) {}
                                        
                                        $sizeDisplay = '0 KB';
                                        if ($sizeBytes > 0) {
                                            if ($sizeBytes >= 1048576) {
                                                $sizeDisplay = number_format($sizeBytes / 1048576, 2) . ' MB';
                                            } else {
                                                $sizeDisplay = number_format($sizeBytes / 1024, 2) . ' KB';
                                            }
                                        }
                                    @endphp
                                    {{ $sizeDisplay }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($template->is_active)
                                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] uppercase">Active for Theme #{{ $template->theme_id }}</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <form action="{{ route('super-admin.templates.toggle-active', $template->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($template->is_active)
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-[11px]">
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-600 hover:bg-emerald-50 font-bold text-[11px]">
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $templates->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400 font-medium">
                <i class="fa-solid fa-code-branch text-3xl block mb-2 text-slate-300"></i>
                No template builds released yet. Use the upload box above to publish Theme 1 or Theme 2.
            </div>
        @endif
    </div>
</div>

<!-- Upload Progress Modal Overlay -->
<div id="uploadProgressModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between">
            <h4 id="progressStatusText" class="text-sm font-extrabold text-slate-900">Uploading Theme Build...</h4>
            <span id="progressPercentageText" class="text-xs font-mono font-bold text-rose-600">0%</span>
        </div>
        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
            <div id="progressBarFill" class="h-full bg-rose-600 rounded-full transition-all duration-200" style="width: 0%;"></div>
        </div>
        <p id="progressSubText" class="text-[11px] text-slate-400 font-medium">Deploying theme assets onto production storage...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectThemeForUpdate(themeId, themeName) {
        document.getElementById('themeIdField').value = themeId;
        document.getElementById('themeNameField').value = themeName;
        document.getElementById('formHeaderTitle').textContent = 'Update Theme #' + themeId + ' (' + (themeName || 'Theme ' + themeId) + ')';
        document.getElementById('uploadTemplateForm').scrollIntoView({ behavior: 'smooth' });
    }

    function resetFormToNewTheme(nextId) {
        document.getElementById('themeIdField').value = nextId;
        document.getElementById('themeNameField').value = 'Theme ' + nextId;
        document.getElementById('formHeaderTitle').textContent = 'Create New Theme #' + nextId;
        document.getElementById('uploadTemplateForm').scrollIntoView({ behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('uploadTemplateForm');
        const modal = document.getElementById('uploadProgressModal');
        const progressFill = document.getElementById('progressBarFill');
        const percentageText = document.getElementById('progressPercentageText');
        const statusText = document.getElementById('progressStatusText');
        const subText = document.getElementById('progressSubText');
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('js-alert-container');

        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('templateFileField');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Please select a .zip template file to upload.');
                return;
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            // Reset UI
            modal.classList.remove('hidden');
            progressFill.style.width = '0%';
            percentageText.textContent = '0%';
            statusText.textContent = 'Uploading Package...';
            subText.textContent = 'Sending theme zip archive to server...';
            submitBtn.disabled = true;

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressFill.style.width = percent + '%';
                    percentageText.textContent = percent + '%';

                    if (percent === 100) {
                        statusText.textContent = 'Extracting & Validating Build...';
                        subText.textContent = 'Deploying build onto storage and clearing hotel caches...';
                    }
                }
            });

            xhr.addEventListener('load', function() {
                modal.classList.add('hidden');
                submitBtn.disabled = false;

                if (xhr.status >= 200 && xhr.status < 300) {
                    window.location.reload();
                } else {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        let errMsg = res.message || 'Upload failed.';
                        if (res.errors) {
                            errMsg = Object.values(res.errors).flat().join('<br>');
                        }
                        alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">${errMsg}</div>`;
                    } catch(err) {
                        alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">Upload failed with status code ${xhr.status}.</div>`;
                    }
                }
            });

            xhr.addEventListener('error', function() {
                modal.classList.add('hidden');
                submitBtn.disabled = false;
                alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">Network error during upload.</div>`;
            });

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    });
</script>
@endsection
