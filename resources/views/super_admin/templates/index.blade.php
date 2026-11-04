@extends('layouts.super_admin')

@section('title', 'Themes Management - Super Admin')
@section('page_title', 'Themes Management')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Smooth responsive on Mobile -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Themes Management</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Manage Android TV themes, deploy new theme packages, preview live layouts, and purge old build archives to keep server disk storage clean.</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center justify-start xl:justify-end gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-1 w-full xl:w-auto">
            @if($inactiveBuildsCount > 0)
                <form action="{{ route('super-admin.templates.purge-inactive') }}" method="POST" class="inline-flex items-center m-0 shrink-0" onsubmit="return confirm('Purge {{ $inactiveBuildsCount }} old inactive build archives? All old ZIP files and unzipped web folders will be deleted permanently to free server disk space.')">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 shadow-2xs transition-all space-x-1.5 cursor-pointer whitespace-nowrap shrink-0"
                            title="Delete all unused inactive builds from storage">
                        <i class="fa-solid fa-broom text-rose-500 text-xs"></i>
                        <span>Purge {{ $inactiveBuildsCount }} Old Builds</span>
                    </button>
                </form>
            @endif

            <button type="button" 
                    onclick="resetFormToNewTheme({{ $nextThemeId }})" 
                    class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-plus text-slate-400 text-xs"></i>
                <span>New Theme #{{ $nextThemeId }}</span>
            </button>

            <a href="#uploadTemplateCard" 
               class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                <span>Upload Package</span>
            </a>
        </div>
    </div>

    <!-- Alert / Notification Placeholder for Ajax Upload -->
    <div id="js-alert-container"></div>

    @if($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Stats Metric Cards (Responsive Grid: 2 cols on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- 1. Total Themes -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $totalThemesCount }}</div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Themes Configured</div>
            </div>
        </div>

        <!-- 2. Active OTA Builds -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight leading-tight">{{ $activeBuildsCount }}</div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Active Live Builds</div>
            </div>
        </div>

        <!-- 3. Global Default Theme -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight truncate">
                    Theme #{{ $defaultThemeId }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-amber-700 truncate mt-0.5" title="{{ $defaultThemeName }}">
                    {{ $defaultThemeName }} (Default)
                </div>
            </div>
        </div>

        <!-- 4. Total Releases & Inactive Cleanup -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ $inactiveBuildsCount > 0 ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }} flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid {{ $inactiveBuildsCount > 0 ? 'fa-hard-drive' : 'fa-boxes-packing' }}"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $totalBuildsCount }}</div>
                <div class="text-[10px] sm:text-xs font-semibold {{ $inactiveBuildsCount > 0 ? 'text-rose-600' : 'text-slate-500' }} truncate mt-0.5">
                    @if($inactiveBuildsCount > 0)
                        {{ $inactiveBuildsCount }} Inactive (Purge)
                    @else
                        Clean Storage
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Registered Themes Overview -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-[#150A42] flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900 tracking-tight font-sans">
                    Registered TV Themes Catalog
                </h3>
            </div>
            <span class="text-xs text-slate-500 font-semibold bg-slate-100 px-3 py-1 rounded-full border border-slate-200/60">
                {{ $existingThemes->count() }} Theme{{ $existingThemes->count() === 1 ? '' : 's' }} Configured
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @forelse($existingThemes as $theme)
                @php
                    $latestBuild = $templates->where('theme_id', $theme->theme_id)->where('is_active', true)->first() 
                                    ?? $templates->where('theme_id', $theme->theme_id)->first();
                    $releasesCount = $templates->where('theme_id', $theme->theme_id)->count();
                    $isDefault = ((int)$theme->theme_id === (int)$defaultThemeId);
                @endphp
                <div class="bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md hover:border-purple-200 transition-all group">
                    <div class="space-y-3.5">
                        <!-- Top Info Row -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-100 flex items-center justify-center text-[#150A42] font-black text-lg overflow-hidden shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                                    @if($theme->preview_image && Storage::disk('public')->exists($theme->preview_image))
                                        <img src="{{ Storage::url($theme->preview_image) }}" alt="Preview" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-mono text-sm font-extrabold">#{{ $theme->theme_id }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-purple-50 text-[#150A42] border border-purple-100/80 font-mono">
                                            Theme ID: {{ $theme->theme_id }}
                                        </span>
                                        @if($isDefault)
                                            <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 flex items-center space-x-1">
                                                <i class="fa-solid fa-star text-[8px] text-amber-500"></i>
                                                <span>Default</span>
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="font-extrabold text-slate-900 text-sm mt-1 truncate" title="{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}">
                                        {{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}
                                    </h4>
                                </div>
                            </div>

                            <div class="flex items-center space-x-1.5 shrink-0">
                                @if($latestBuild && $latestBuild->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-[10px] uppercase flex items-center space-x-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Active</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase">
                                        Inactive
                                    </span>
                                @endif

                                @if(!$isDefault)
                                    <form action="{{ route('super-admin.templates.destroy-theme', $theme->theme_id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete Theme #{{ $theme->theme_id }} ({{ addslashes($theme->theme_name ?? '') }}) and all its uploaded package builds? This will permanently delete its ZIP archives and extracted files to free server storage.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-7 h-7 rounded-lg border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-colors flex items-center justify-center cursor-pointer shadow-2xs"
                                                title="Delete this entire Theme and free all associated disk storage">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Build Details Pill Box -->
                        <div class="bg-[#F8F9FD] border border-slate-100 rounded-2xl p-3 text-xs space-y-1.5 text-slate-600 font-medium">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Latest Build:</span>
                                <span class="font-mono font-extrabold text-[#150A42] bg-white px-2 py-0.5 rounded-md border border-slate-200/60 shadow-2xs">
                                    v{{ $latestBuild ? $latestBuild->version : '1.0' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Total Releases:</span>
                                <span class="font-bold text-slate-800">{{ $releasesCount }} Build{{ $releasesCount === 1 ? '' : 's' }}</span>
                            </div>
                            @if($latestBuild && $latestBuild->created_at)
                                <div class="flex justify-between items-center text-[11px]">
                                    <span class="text-slate-400">Last Deployed:</span>
                                    <span class="text-slate-500 font-semibold">{{ $latestBuild->created_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="selectThemeForUpdate({{ $theme->theme_id }}, '{{ addslashes($theme->theme_name ?? '') }}')" 
                                    class="flex-1 py-2 sm:py-2.5 rounded-xl border border-purple-200 bg-purple-50/50 hover:bg-purple-100/60 text-[#150A42] font-extrabold text-xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer shadow-2xs">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                                <span>Upload Update</span>
                            </button>

                            @if($latestBuild && $latestBuild->extracted_path)
                                <a href="{{ route('super-admin.templates.preview', $latestBuild->id) }}" target="_blank"
                                   class="py-2 sm:py-2.5 px-3.5 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100/60 text-emerald-700 font-extrabold text-xs transition-all flex items-center justify-center space-x-1.5 shadow-2xs"
                                   title="Open Live Web Preview of Theme in new tab">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    <span>Preview</span>
                                </a>
                            @else
                                <span class="py-2 sm:py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 font-bold text-xs flex items-center justify-center space-x-1.5 cursor-not-allowed" 
                                      title="No extracted preview found. Upload a ZIP to enable preview.">
                                    <i class="fa-solid fa-eye-slash text-xs"></i>
                                    <span>No Preview</span>
                                </span>
                            @endif
                        </div>

                        @if($isDefault)
                            <div class="w-full py-2 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 font-bold text-[11px] flex items-center justify-center space-x-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i>
                                <span>Global Default Theme for New Suites</span>
                            </div>
                        @else
                            <form action="{{ route('super-admin.templates.set-default', $theme->theme_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2 rounded-xl border border-slate-200 hover:border-amber-300 text-slate-600 hover:text-amber-700 hover:bg-amber-50/50 font-bold text-[11px] transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                                    <i class="fa-regular fa-star text-amber-500"></i>
                                    <span>Set as Global Default</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-slate-200/80 rounded-3xl p-10 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center mx-auto text-xl">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h4 class="text-sm font-extrabold text-slate-800">No Themes Registered Yet</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto font-medium">Upload your first Android TV template ZIP archive using the deployment box below to initiate Theme #1.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Upload / Update Theme Card -->
    <div id="uploadTemplateCard" class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight" id="formHeaderTitle">
                        Upload or Update TV Theme Package
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Upload a ZIP package for Theme ID 1 (replace/update) or Theme ID 2+ (new theme variation).</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="resetFormToNewTheme({{ $nextThemeId }})" 
                        class="text-xs font-bold text-[#150A42] hover:text-[#201063] bg-purple-50 hover:bg-purple-100 border border-purple-200 px-3.5 py-1.5 rounded-xl transition-all flex items-center space-x-1.5 cursor-pointer whitespace-nowrap">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>New Theme #{{ $nextThemeId }}</span>
                </button>
            </div>
        </div>
        
        <form id="uploadTemplateForm" action="{{ route('super-admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" data-ajax-form="true" data-no-loader="true">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-5">
                <!-- Theme ID -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Theme ID *</span>
                        <span id="themeIdBadge" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-purple-50 text-[#150A42]">ID: 1</span>
                    </label>
                    <input type="number" 
                           id="themeIdField" 
                           name="theme_id" 
                           value="{{ $existingThemes->count() > 0 ? 1 : 1 }}" 
                           min="1" 
                           required 
                           oninput="updateThemeIdBadge(this.value)"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 text-xs font-extrabold text-slate-900 bg-[#F8F9FD] focus:bg-white transition-all font-mono">
                    <span class="text-[11px] text-slate-400 font-medium block">Select <strong>1</strong> to update default Theme 1, or <strong>2+</strong> for additional styles.</span>
                </div>

                <!-- Theme Name -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Theme Display Name</label>
                    <input type="text" 
                           id="themeNameField" 
                           name="theme_name" 
                           placeholder="e.g. Classic Luxury or Modern Dark" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 text-xs font-semibold text-slate-800 bg-[#F8F9FD] focus:bg-white transition-all">
                    <span class="text-[11px] text-slate-400 font-medium block">Displayed to Hotel Admins when choosing their TV theme.</span>
                </div>

                <!-- Custom Version (Optional) -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Version (Optional)</span>
                        <span class="text-[10px] text-slate-400 font-mono font-normal">Auto: +0.5</span>
                    </label>
                    <input type="text" 
                           id="customVersionField" 
                           name="custom_version" 
                           placeholder="e.g. 1.0 or 2.5" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 text-xs font-medium text-slate-800 bg-[#F8F9FD] focus:bg-white transition-all font-mono">
                    <span class="text-[11px] text-slate-400 font-medium block">Leave blank to automatically increment latest version.</span>
                </div>
            </div>

            <!-- Upload Zone Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-5 pt-3 border-t border-slate-100">
                <!-- Theme Preview Image -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Theme Preview Screenshot</span>
                        <span class="text-[10px] text-slate-400 font-medium">PNG, JPG, WEBP (Max 5MB)</span>
                    </label>
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-2xl p-4 bg-[#F8F9FD] hover:bg-purple-50/20 transition-all text-center cursor-pointer group" onclick="document.getElementById('previewImageField').click()">
                        <input type="file" 
                               id="previewImageField" 
                               name="preview_image" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleImageSelection(this)">
                        
                        <div id="imageUploadPlaceholder" class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center mx-auto text-base group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-700">Choose Preview Image</div>
                            <p class="text-[11px] text-slate-400">Visual mockup displayed to hotel suites during template preview</p>
                        </div>

                        <div id="imageUploadPreviewContainer" class="hidden items-center justify-center space-x-3">
                            <img id="imageUploadPreview" src="#" alt="Preview" class="w-12 h-12 object-cover rounded-xl border border-purple-200 shadow-2xs">
                            <div class="text-left min-w-0">
                                <div id="imageUploadName" class="text-xs font-extrabold text-slate-900 truncate max-w-[200px]"></div>
                                <div class="text-[10px] text-emerald-600 font-bold flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Ready to upload</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Template Package ZIP -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Template Package ZIP Archive *</span>
                        <span class="text-[10px] text-slate-400 font-medium">.zip (Max 50MB)</span>
                    </label>
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-[#150A42] rounded-2xl p-4 bg-[#F8F9FD] hover:bg-purple-50/20 transition-all text-center cursor-pointer group" onclick="document.getElementById('templateFileField').click()">
                        <input type="file" 
                               id="templateFileField" 
                               name="template_file" 
                               required 
                               accept=".zip" 
                               class="hidden"
                               onchange="handleZipSelection(this)">
                        
                        <div id="zipUploadPlaceholder" class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-base group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-file-zipper"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-700">Choose Template ZIP Package</div>
                            <p class="text-[11px] text-slate-400">Contains index.html, JS bundles, and assets for TV devices</p>
                        </div>

                        <div id="zipUploadPreviewContainer" class="hidden items-center justify-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-[#150A42] text-white flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-file-zipper"></i>
                            </div>
                            <div class="text-left min-w-0">
                                <div id="zipUploadName" class="text-xs font-extrabold text-slate-900 truncate max-w-[200px]"></div>
                                <div id="zipUploadSize" class="text-[10px] text-slate-500 font-mono"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-t border-slate-100">
                <div class="text-[11px] text-slate-400 font-medium flex items-center space-x-1.5">
                    <i class="fa-solid fa-shield-halved text-emerald-500 shrink-0"></i>
                    <span>ZIP is auto-extracted and synced across all hotel TVs in real time.</span>
                </div>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="button" onclick="resetFormToNewTheme({{ $nextThemeId }})" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs transition-colors whitespace-nowrap">
                        Reset Form
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer whitespace-nowrap">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Upload & Deploy Theme Build</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Theme Build Release Log Table -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
        <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                    <i class="fa-solid fa-history text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Theme Build Release Log</h3>
                    <p class="text-xs text-slate-500 font-medium">Historical audit trail of all deployed template packages (newest builds first).</p>
                </div>
            </div>

            <!-- Actions & Filter by Theme Pills (Horizontal Swipe on mobile) -->
            <div class="flex items-center flex-wrap sm:flex-nowrap gap-2 w-full sm:w-auto overflow-x-auto no-scrollbar py-0.5">
                @if($inactiveBuildsCount > 0)
                    <form action="{{ route('super-admin.templates.purge-inactive') }}" method="POST" class="inline shrink-0" onsubmit="return confirm('Purge {{ $inactiveBuildsCount }} old inactive build archives? All old ZIP files and unzipped web folders will be deleted permanently to free server disk space.')">
                        @csrf
                        @if(request('theme_id'))
                            <input type="hidden" name="theme_id" value="{{ request('theme_id') }}">
                        @endif
                        <button type="submit" 
                                class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-all flex items-center space-x-1.5 cursor-pointer shadow-2xs whitespace-nowrap shrink-0">
                            <i class="fa-solid fa-broom text-xs text-rose-500"></i>
                            <span>Purge Inactive</span>
                        </button>
                    </form>
                @endif

                <div class="flex items-center gap-1.5 flex-nowrap shrink-0">
                    <a href="{{ route('super-admin.templates.index') }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all whitespace-nowrap shrink-0 {{ !request('theme_id') ? 'bg-[#150A42] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All ({{ $totalBuildsCount }})
                    </a>
                    @foreach($existingThemes as $t)
                        <a href="{{ route('super-admin.templates.index', ['theme_id' => $t->theme_id]) }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all whitespace-nowrap shrink-0 {{ request('theme_id') == $t->theme_id ? 'bg-[#150A42] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Theme #{{ $t->theme_id }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if($templates->count() > 0)
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left text-xs text-slate-700 min-w-[720px]">
                    <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Theme</th>
                            <th class="px-4 py-3.5">Version</th>
                            <th class="px-4 py-3.5">Package File</th>
                            <th class="px-4 py-3.5">Deployed At</th>
                            <th class="px-4 py-3.5">Package Size</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($templates as $template)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Theme ID & Name -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-xs font-bold text-[#150A42] overflow-hidden shrink-0 border border-purple-100 shadow-2xs">
                                            @if($template->preview_image && Storage::disk('public')->exists($template->preview_image))
                                                <img src="{{ Storage::url($template->preview_image) }}" alt="Preview" class="w-full h-full object-cover">
                                            @else
                                                <span class="font-mono font-extrabold">#{{ $template->theme_id }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-xs">
                                                {{ $template->theme_name ?? ('Theme #' . $template->theme_id) }}
                                            </div>
                                            <span class="text-[10px] font-mono font-bold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded border border-purple-100/60">
                                                Theme ID: {{ $template->theme_id }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Version -->
                                <td class="px-4 py-4">
                                    <span class="font-mono font-extrabold text-[#150A42] text-xs bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200/60 shadow-2xs">
                                        v{{ $template->version }}
                                    </span>
                                </td>

                                <!-- Package File Link -->
                                <td class="px-4 py-4">
                                    <a href="{{ Storage::url($template->file_path) }}" target="_blank" class="text-purple-700 hover:text-purple-900 hover:underline font-mono text-[11px] font-semibold flex items-center space-x-1.5">
                                        <i class="fa-solid fa-file-zipper text-purple-400"></i>
                                        <span class="truncate max-w-[180px]">{{ basename($template->file_path) }}</span>
                                    </a>
                                </td>

                                <!-- Deployed At -->
                                <td class="px-4 py-4 text-slate-500 text-xs">
                                    <div>{{ $template->created_at->format('d M Y, h:i A') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $template->created_at->diffForHumans() }}</div>
                                </td>

                                <!-- Package Size -->
                                <td class="px-4 py-4 font-mono font-bold text-slate-700 text-xs">
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

                                <!-- Status Badge -->
                                <td class="px-4 py-4 text-center">
                                    @if($template->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-[10px] uppercase space-x-1 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Active (Theme #{{ $template->theme_id }})</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase whitespace-nowrap">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($template->extracted_path)
                                            <a href="{{ route('super-admin.templates.preview', $template->id) }}" target="_blank"
                                               class="p-2 rounded-lg border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 font-bold text-xs transition-colors shadow-2xs"
                                               title="Live Web Preview">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('super-admin.templates.toggle-active', $template->id) }}" method="POST" class="inline">
                                            @csrf
                                            @if($template->is_active)
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50 font-extrabold text-[11px] transition-colors cursor-pointer shadow-2xs whitespace-nowrap"
                                                        title="Deactivate this release for Theme #{{ $template->theme_id }}">
                                                    Deactivate
                                                </button>
                                            @else
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100/60 text-emerald-700 font-extrabold text-[11px] transition-colors cursor-pointer shadow-2xs whitespace-nowrap"
                                                        title="Set this build as active for Theme #{{ $template->theme_id }}">
                                                    Activate
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Delete Build Action -->
                                        <form action="{{ route('super-admin.templates.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete Build v{{ $template->version }} for Theme #{{ $template->theme_id }}? The ZIP package and unzipped files will be removed from disk to free storage.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-lg border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-400 hover:text-rose-600 font-bold text-xs transition-colors cursor-pointer shadow-2xs"
                                                    title="Delete this build and free disk storage">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $templates->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400 font-medium space-y-2">
                <i class="fa-solid fa-boxes-stacked text-3xl block text-slate-300"></i>
                <p class="text-xs">No template build releases found for this filter.</p>
            </div>
        @endif
    </div>
</div>

<!-- Upload Progress Modal Overlay -->
<div id="uploadProgressModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-[28px] w-full max-w-md p-6 sm:p-7 space-y-5 shadow-2xl">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-xl shrink-0 shadow-xs">
                <i class="fa-solid fa-cloud-arrow-up animate-bounce"></i>
            </div>
            <div class="min-w-0">
                <h4 id="progressStatusText" class="text-sm font-black text-slate-900 tracking-tight">Uploading Theme Build...</h4>
                <p id="progressSubText" class="text-xs text-slate-400 font-medium mt-0.5">Deploying theme assets onto production storage...</p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-mono font-bold">
                <span class="text-slate-500">Progress</span>
                <span id="progressPercentageText" class="text-[#150A42]">0%</span>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                <div id="progressBarFill" class="h-full bg-gradient-to-r from-[#150A42] to-purple-600 rounded-full transition-all duration-200" style="width: 0%;"></div>
            </div>
        </div>

        <div class="p-3 bg-purple-50/50 rounded-xl border border-purple-100/60 text-[11px] text-purple-900 font-medium flex items-center space-x-2">
            <i class="fa-solid fa-circle-info text-purple-500 shrink-0"></i>
            <span>Please keep this window open while the build extracts and hotel caches invalidate.</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateThemeIdBadge(val) {
        const badge = document.getElementById('themeIdBadge');
        if (badge) {
            badge.textContent = 'ID: ' + (val || '1');
        }
    }

    function selectThemeForUpdate(themeId, themeName) {
        document.getElementById('themeIdField').value = themeId;
        updateThemeIdBadge(themeId);
        document.getElementById('themeNameField').value = themeName;
        document.getElementById('formHeaderTitle').textContent = 'Update Theme #' + themeId + (themeName ? ' (' + themeName + ')' : '');
        document.getElementById('uploadTemplateCard').scrollIntoView({ behavior: 'smooth' });
    }

    function resetFormToNewTheme(nextId) {
        document.getElementById('themeIdField').value = nextId;
        updateThemeIdBadge(nextId);
        document.getElementById('themeNameField').value = 'Theme #' + nextId;
        document.getElementById('customVersionField').value = '1.0';
        document.getElementById('formHeaderTitle').textContent = 'Create New Theme #' + nextId;
        document.getElementById('uploadTemplateCard').scrollIntoView({ behavior: 'smooth' });
    }

    function handleImageSelection(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imageUploadPreview').src = e.target.result;
                document.getElementById('imageUploadName').textContent = file.name;
                document.getElementById('imageUploadPlaceholder').classList.add('hidden');
                document.getElementById('imageUploadPreviewContainer').classList.remove('hidden');
                document.getElementById('imageUploadPreviewContainer').classList.add('flex');
            };
            reader.readAsDataURL(file);
        }
    }

    function handleZipSelection(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            document.getElementById('zipUploadName').textContent = file.name;
            document.getElementById('zipUploadSize').textContent = sizeMb + ' MB';
            document.getElementById('zipUploadPlaceholder').classList.add('hidden');
            document.getElementById('zipUploadPreviewContainer').classList.remove('hidden');
            document.getElementById('zipUploadPreviewContainer').classList.add('flex');
        }
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
                alertContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                        <span>Please select a .zip template file to upload.</span>
                    </div>`;
                return;
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            // Open Progress Modal
            modal.classList.remove('hidden');
            progressFill.style.width = '0%';
            percentageText.textContent = '0%';
            statusText.textContent = 'Uploading Package...';
            subText.textContent = 'Sending theme ZIP archive to production server...';
            submitBtn.disabled = true;

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressFill.style.width = percent + '%';
                    percentageText.textContent = percent + '%';

                    if (percent === 100) {
                        statusText.textContent = 'Extracting & Validating Build...';
                        subText.textContent = 'Unzipping assets and invalidating hotel TV caches...';
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
                        alertContainer.innerHTML = `
                            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2">
                                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                                <div>${errMsg}</div>
                            </div>`;
                    } catch(err) {
                        alertContainer.innerHTML = `
                            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2">
                                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                                <span>Upload failed with status code ${xhr.status}.</span>
                            </div>`;
                    }
                }
            });

            xhr.addEventListener('error', function() {
                modal.classList.add('hidden');
                submitBtn.disabled = false;
                alertContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2">
                        <i class="fa-solid fa-wifi text-rose-500"></i>
                        <span>Network error occurred while uploading. Please check your connection.</span>
                    </div>`;
            });

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    });
</script>
@endsection
