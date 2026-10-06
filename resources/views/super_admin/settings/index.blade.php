@extends('layouts.super_admin')

@section('title', 'Application Branding & Settings - Super Admin')
@section('page_title', 'System Settings & Branding')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Top Alert / Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -top-10 right-20 w-48 h-48 bg-indigo-600/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Global White-label Configuration</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Application Identity & Media</h2>
                <p class="text-xs sm:text-sm text-slate-400 max-w-xl">
                    Configure your platform branding, portal logos, and browser favicons. Updates applied here instantly reflect across Super Admin, Hotel Portals, Distributor Hubs, and Guest Landing screens.
                </p>
            </div>
            <div class="flex items-center space-x-3 shrink-0">
                <div class="p-3 bg-white/5 border border-white/10 rounded-2xl flex items-center space-x-3">
                    <img src="{{ app_favicon_url() }}" alt="Favicon" class="w-8 h-8 rounded-lg object-contain bg-white/10 p-1">
                    <div>
                        <div class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Active Brand</div>
                        <div class="text-sm font-extrabold text-white truncate max-w-[120px]">{{ app_setting('app_name', 'DigiHotel') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super-admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Section 1: Basic Identity -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="fa-solid fa-signature text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Platform Titles & Identity</h3>
                        <p class="text-xs text-slate-500 font-medium">Names that show on headers, page titles, and system notices.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-indigo-50 text-indigo-700">Required</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Application Full Name <span class="text-rose-500">*</span></span>
                        <span class="text-[11px] text-slate-400 font-normal">Max 100 chars</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-hotel"></i>
                        </span>
                        <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name) }}" required
                            placeholder="e.g. DigiHotel Smart Hospitality"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-slate-500">Appears in browser title bar, navbar logos, and communications.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Short / Compact Brand Name</span>
                        <span class="text-[11px] text-slate-400 font-normal">Max 50 chars</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" name="app_short_name" value="{{ old('app_short_name', $setting->app_short_name) }}"
                            placeholder="e.g. DigiHotel"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                    <p class="text-[11px] text-slate-500">Used in mobile headers and tight navigational bars.</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Logo Media Upload -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                        <i class="fa-solid fa-image text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Main Application Logo</h3>
                        <p class="text-xs text-slate-500 font-medium">Platform brand logo used across all portals and landing pages.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-100 text-slate-600">Media</span>
            </div>

            <!-- Guidelines Callout -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-info text-sm"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Logo Upload Guidelines:</div>
                        <div class="text-[11px] text-slate-500 space-y-0.5 mt-0.5">
                            <div>• <span class="font-semibold text-slate-700">Recommended Dimensions:</span> <strong>250px (W) × 60px (H)</strong> (landscape 4:1 ratio)</div>
                            <div>• <span class="font-semibold text-slate-700">Supported Formats:</span> <strong>PNG, JPG, JPEG, SVG, WEBP</strong> (Transparent PNG or SVG recommended)</div>
                            <div>• <span class="font-semibold text-slate-700">Maximum Allowed Size:</span> <strong>2 MB (2048 KB)</strong></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previews & Upload Box -->
            <div class="grid md:grid-cols-12 gap-6 items-start">
                <!-- Current / Preview Logo -->
                <div class="md:col-span-5 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Current / Live Preview</label>
                    <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex flex-col items-center justify-center min-h-[140px] text-center relative group">
                        <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300">Dark Background</div>
                        <img id="logoPreviewDark" src="{{ app_logo_url() }}" alt="App Logo" class="max-h-16 max-w-full object-contain filter drop-shadow">
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center min-h-[100px] text-center relative">
                        <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600">Light Background</div>
                        <img id="logoPreviewLight" src="{{ app_logo_url() }}" alt="App Logo" class="max-h-12 max-w-full object-contain">
                    </div>
                </div>

                <!-- Upload Area -->
                <div class="md:col-span-7 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Upload New Logo</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-rose-400 rounded-2xl p-6 text-center bg-slate-50/50 transition-all cursor-pointer relative group" onclick="document.getElementById('app_logo').click()">
                        <input type="file" name="app_logo" id="app_logo" accept=".png,.jpg,.jpeg,.svg,.webp" class="hidden" onchange="handleLogoChange(event)">
                        
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-xs">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800 group-hover:text-rose-600 transition-colors">
                            Click here to browse logo file
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            PNG, SVG, WEBP, JPG up to 2MB
                        </div>
                        <div id="logoSelectedInfo" class="mt-3 text-xs font-semibold text-emerald-600 hidden">
                            <i class="fa-solid fa-check-circle mr-1"></i> <span id="logoFileName">File chosen</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Leave empty to keep the current application logo intact.</p>
                </div>
            </div>
        </div>

        <!-- Section 3: Favicon Media Upload -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-icons text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Browser Tab Favicon</h3>
                        <p class="text-xs text-slate-500 font-medium">Small browser icon displayed next to the page title in tabs.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-100 text-slate-600">Icon</span>
            </div>

            <!-- Guidelines Callout -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-info text-sm"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Favicon Upload Guidelines:</div>
                        <div class="text-[11px] text-slate-500 space-y-0.5 mt-0.5">
                            <div>• <span class="font-semibold text-slate-700">Recommended Dimensions:</span> <strong>32px × 32px</strong> or <strong>64px × 64px</strong> (Square 1:1 ratio)</div>
                            <div>• <span class="font-semibold text-slate-700">Supported Formats:</span> <strong>ICO, PNG, SVG</strong> (ICO or square PNG recommended)</div>
                            <div>• <span class="font-semibold text-slate-700">Maximum Allowed Size:</span> <strong>1 MB (1024 KB)</strong></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Favicon Previews & Upload Box -->
            <div class="grid md:grid-cols-12 gap-6 items-start">
                <!-- Preview Tabs -->
                <div class="md:col-span-5 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Tab Simulation Preview</label>
                    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 space-y-3">
                        <!-- Simulated Chrome Tab -->
                        <div class="bg-slate-800 rounded-xl px-3 py-2 flex items-center space-x-2.5 border border-slate-700/80 max-w-xs shadow-inner">
                            <img id="faviconPreview" src="{{ app_favicon_url() }}" alt="Favicon" class="w-4 h-4 rounded-xs object-contain">
                            <span class="text-xs text-slate-200 font-medium truncate" id="tabTitlePreview">{{ app_setting('app_name', 'DigiHotel') }} - Portal</span>
                            <i class="fa-solid fa-xmark text-[10px] text-slate-400 ml-auto"></i>
                        </div>
                        <div class="flex items-center space-x-3 pt-2">
                            <div class="p-2.5 rounded-xl bg-slate-800 border border-slate-700 text-center">
                                <img id="faviconPreview32" src="{{ app_favicon_url() }}" alt="32x32" class="w-8 h-8 object-contain mx-auto">
                                <span class="text-[10px] text-slate-400 mt-1 block">32×32</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-800 border border-slate-700 text-center">
                                <img id="faviconPreview64" src="{{ app_favicon_url() }}" alt="64x64" class="w-12 h-12 object-contain mx-auto">
                                <span class="text-[10px] text-slate-400 mt-1 block">64×64</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div class="md:col-span-7 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Upload New Favicon</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-amber-400 rounded-2xl p-6 text-center bg-slate-50/50 transition-all cursor-pointer relative group" onclick="document.getElementById('app_favicon').click()">
                        <input type="file" name="app_favicon" id="app_favicon" accept=".ico,.png,.svg" class="hidden" onchange="handleFaviconChange(event)">
                        
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-xs">
                            <i class="fa-solid fa-icons text-xl"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800 group-hover:text-amber-600 transition-colors">
                            Click here to select favicon file
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            ICO, PNG, or SVG up to 1MB
                        </div>
                        <div id="faviconSelectedInfo" class="mt-3 text-xs font-semibold text-emerald-600 hidden">
                            <i class="fa-solid fa-check-circle mr-1"></i> <span id="favFileName">File chosen</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Leave empty to keep current browser favicon.</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Footer Copyright & Support Contact -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-5 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-circle-nodes text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Footer & Public Info</h3>
                    <p class="text-xs text-slate-500 font-medium">Standard copyright notice and master support contact details.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700">Footer Copyright Text</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}"
                        placeholder="e.g. © 2026 DigiHotel Platform. All rights reserved."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    <p class="text-[11px] text-slate-500">Displayed at bottom of landing and authentication layouts.</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 pt-2">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">Public Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}"
                            placeholder="support@hotel.com"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">Public Helpline Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}"
                            placeholder="+91 9876543210"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-4 flex items-center justify-end space-x-4">
            <a href="{{ route('super-admin.dashboard') }}" class="px-6 py-3.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-500 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-bold text-xs shadow-xl shadow-rose-600/30 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save & Apply Platform Branding</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Live logo preview & size validator
    function handleLogoChange(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Check file size (max 2MB = 2097152 bytes)
        const maxBytes = 2 * 1024 * 1024;
        if (file.size > maxBytes) {
            Swal.fire({
                icon: 'error',
                title: 'File Size Exceeded',
                text: 'Selected logo file is ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB. Maximum allowed size is 2 MB.',
                confirmButtonColor: '#e11d48'
            });
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreviewDark').src = e.target.result;
            document.getElementById('logoPreviewLight').src = e.target.result;
            document.getElementById('logoFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            document.getElementById('logoSelectedInfo').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Live favicon preview & size validator
    function handleFaviconChange(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Check file size (max 1MB = 1048576 bytes)
        const maxBytes = 1 * 1024 * 1024;
        if (file.size > maxBytes) {
            Swal.fire({
                icon: 'error',
                title: 'File Size Exceeded',
                text: 'Selected favicon file is ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB. Maximum allowed size is 1 MB.',
                confirmButtonColor: '#e11d48'
            });
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('faviconPreview').src = e.target.result;
            document.getElementById('faviconPreview32').src = e.target.result;
            document.getElementById('faviconPreview64').src = e.target.result;
            document.getElementById('favFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            document.getElementById('faviconSelectedInfo').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
