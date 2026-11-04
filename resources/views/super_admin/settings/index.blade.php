@extends('layouts.super_admin')

@section('title', 'System Settings & Branding - Super Admin')
@section('page_title', 'System Settings & Branding')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Smooth responsive on Mobile -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">System Settings & Branding</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Configure white-label platform branding, application logos, browser favicons, public contact details, and payment integrations.</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-0.5 w-full sm:w-auto">
            <a href="{{ route('super-admin.payment-settings.index') }}" 
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-credit-card text-emerald-600 text-xs"></i>
                <span>Payment Gateways</span>
            </a>

            <button type="submit" 
                    form="settingsForm" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </div>

    <!-- Error Alerts (Success is handled globally by super_admin layout) -->
    @if($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Stats Metric Cards (2x2 on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- 1. Active Platform Title -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-signature"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight truncate" title="{{ $setting->app_name }}">
                    {{ $setting->app_name }}
                </div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Platform Title</div>
            </div>
        </div>

        <!-- 2. Short Tag Name -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-tag"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight truncate">
                    {{ $setting->app_short_name ?? $setting->app_name }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Short Mobile Tag</div>
            </div>
        </div>

        <!-- 3. Active Payment Gateway -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-emerald-600 tracking-tight leading-tight truncate">
                    {{ strtoupper($activeGateway->name ?? $activeGateway->code ?? 'ICICI') }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Active Payment Gateway</div>
            </div>
        </div>

        <!-- 4. Active Media Assets -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">Live Active</div>
                <div class="text-[10px] sm:text-xs font-semibold text-emerald-600 truncate mt-0.5">Logo & Favicon Deployed</div>
            </div>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form id="settingsForm" action="{{ route('super-admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Platform Titles & Identity -->
        <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                        <i class="fa-solid fa-signature text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Platform Titles & Identity</h3>
                        <p class="text-xs text-slate-500 font-medium">Names displayed on browser titles, navigation headers, and automated communication.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-mono font-extrabold rounded-md bg-purple-50 text-[#150A42] border border-purple-100">
                    Required
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Application Full Name -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Application Full Name <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] text-slate-400 font-normal">Max 100 chars</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-hotel"></i>
                        </span>
                        <input type="text" 
                               name="app_name" 
                               value="{{ old('app_name', $setting->app_name) }}" 
                               required
                               placeholder="e.g. DigiHotel Smart Hospitality"
                               class="w-full pl-10 pr-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400">Main brand title displayed across all portals, landing pages, and tab bars.</p>
                </div>

                <!-- Short Brand Name -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Short / Compact Brand Name</span>
                        <span class="text-[10px] text-slate-400 font-normal">Max 50 chars</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" 
                               name="app_short_name" 
                               value="{{ old('app_short_name', $setting->app_short_name) }}"
                               placeholder="e.g. DigiHotel"
                               class="w-full pl-10 pr-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400">Used in mobile headers, collapsed navigation bars, and system push notifications.</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Main Application Logo -->
        <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                        <i class="fa-solid fa-image text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Main Application Logo</h3>
                        <p class="text-xs text-slate-500 font-medium">Platform brand logo used across all portals, auth screens, and landing pages.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-mono font-bold rounded-md bg-slate-100 text-slate-600">
                    PNG / SVG / WEBP
                </span>
            </div>

            <!-- Guidelines Callout -->
            <div class="p-3.5 rounded-2xl bg-purple-50/50 border border-purple-100/80 flex items-start space-x-3">
                <div class="w-7 h-7 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center shrink-0 mt-0.5 text-xs">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="text-[11px] text-slate-600 space-y-0.5">
                    <span class="font-bold text-slate-800 block">Logo Upload Guidelines:</span>
                    <p>Recommended Dimensions: <strong class="text-slate-800">250px (W) × 60px (H)</strong> (landscape 4:1 ratio). Transparent PNG or SVG is recommended. Max 2MB.</p>
                </div>
            </div>

            <!-- Previews & Upload Box -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                <!-- Current / Preview Logo Box -->
                <div class="md:col-span-5 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Live Logo Previews</label>
                    <div class="p-4 rounded-2xl bg-[#0A051B] border border-slate-800 flex flex-col items-center justify-center min-h-[110px] text-center relative group">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-bold bg-white/10 text-slate-300">Dark Background</span>
                        <img id="logoPreviewDark" src="{{ app_logo_url() }}" alt="App Logo" class="max-h-12 max-w-full object-contain filter drop-shadow">
                    </div>
                    <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200 flex flex-col items-center justify-center min-h-[90px] text-center relative">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-bold bg-slate-200 text-slate-600">Light Background</span>
                        <img id="logoPreviewLight" src="{{ app_logo_url() }}" alt="App Logo" class="max-h-10 max-w-full object-contain">
                    </div>
                </div>

                <!-- Upload Drag & Drop Area -->
                <div class="md:col-span-7 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Upload New Logo</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-[#150A42] rounded-2xl p-6 text-center bg-[#F8F9FD] hover:bg-purple-50/20 transition-all cursor-pointer relative group" onclick="document.getElementById('app_logo').click()">
                        <input type="file" name="app_logo" id="app_logo" accept=".png,.jpg,.jpeg,.svg,.webp" class="hidden" onchange="handleLogoChange(event)">
                        
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-2xs">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800 group-hover:text-[#150A42] transition-colors">
                            Click here to browse logo file
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            PNG, SVG, WEBP, or JPG up to 2MB (Auto-optimized to WebP)
                        </div>
                        <div id="logoSelectedInfo" class="mt-3 text-xs font-bold text-emerald-600 hidden flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="logoFileName">File chosen</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Leave blank to retain current application logo intact.</p>
                </div>
            </div>
        </div>

        <!-- Section 3: Favicon Media Upload -->
        <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0 shadow-2xs">
                        <i class="fa-solid fa-icons text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Browser Tab Favicon</h3>
                        <p class="text-xs text-slate-500 font-medium">Small browser icon displayed next to the page title in browser tabs.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-mono font-bold rounded-md bg-slate-100 text-slate-600">
                    ICO / PNG / SVG
                </span>
            </div>

            <!-- Guidelines Callout -->
            <div class="p-3.5 rounded-2xl bg-amber-50/50 border border-amber-100/80 flex items-start space-x-3">
                <div class="w-7 h-7 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="text-[11px] text-slate-600 space-y-0.5">
                    <span class="font-bold text-slate-800 block">Favicon Upload Guidelines:</span>
                    <p>Recommended Dimensions: <strong class="text-slate-800">32px × 32px</strong> or <strong class="text-slate-800">64px × 64px</strong> (Square 1:1 ratio). ICO or square PNG is recommended. Max 1MB.</p>
                </div>
            </div>

            <!-- Favicon Previews & Upload Box -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                <!-- Browser Tab Simulation Preview -->
                <div class="md:col-span-5 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Browser Tab Simulation</label>
                    <div class="p-4 rounded-2xl bg-[#0A051B] border border-slate-800 space-y-3">
                        <div class="bg-slate-800/90 rounded-xl px-3 py-2 flex items-center space-x-2.5 border border-slate-700/80 max-w-xs shadow-inner">
                            <img id="faviconPreview" src="{{ app_favicon_url() }}" alt="Favicon" class="w-4 h-4 rounded-xs object-contain">
                            <span class="text-xs text-slate-200 font-semibold truncate">{{ $setting->app_name }} - Portal</span>
                            <i class="fa-solid fa-xmark text-[10px] text-slate-400 ml-auto"></i>
                        </div>
                        <div class="flex items-center space-x-3 pt-1">
                            <div class="p-2.5 rounded-xl bg-slate-800 border border-slate-700 text-center">
                                <img id="faviconPreview32" src="{{ app_favicon_url() }}" alt="32x32" class="w-7 h-7 object-contain mx-auto">
                                <span class="text-[10px] text-slate-400 mt-1 block font-mono">32×32</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-800 border border-slate-700 text-center">
                                <img id="faviconPreview64" src="{{ app_favicon_url() }}" alt="64x64" class="w-10 h-10 object-contain mx-auto">
                                <span class="text-[10px] text-slate-400 mt-1 block font-mono">64×64</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div class="md:col-span-7 space-y-3">
                    <label class="text-xs font-bold text-slate-700 block">Upload New Favicon</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-amber-400 rounded-2xl p-6 text-center bg-[#F8F9FD] hover:bg-amber-50/20 transition-all cursor-pointer relative group" onclick="document.getElementById('app_favicon').click()">
                        <input type="file" name="app_favicon" id="app_favicon" accept=".ico,.png,.svg" class="hidden" onchange="handleFaviconChange(event)">
                        
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform shadow-2xs">
                            <i class="fa-solid fa-icons text-xl"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800 group-hover:text-amber-700 transition-colors">
                            Click here to select favicon file
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            ICO, PNG, or SVG up to 1MB
                        </div>
                        <div id="faviconSelectedInfo" class="mt-3 text-xs font-bold text-emerald-600 hidden flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="favFileName">File chosen</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Leave blank to retain current browser favicon.</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Public Footer & Support Contacts -->
        <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                    <i class="fa-solid fa-circle-nodes text-base"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Footer & Public Support Contacts</h3>
                    <p class="text-xs text-slate-500 font-medium">Standard copyright statement and public support contact details.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Footer Copyright Statement</label>
                    <input type="text" 
                           name="footer_text" 
                           value="{{ old('footer_text', $setting->footer_text) }}"
                           placeholder="e.g. © 2026 DigiHotel Platform. All rights reserved."
                           class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    <p class="text-[11px] text-slate-400">Displayed at bottom of landing, portal, and authentication layouts.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Public Contact Email</label>
                        <input type="email" 
                               name="contact_email" 
                               value="{{ old('contact_email', $setting->contact_email) }}"
                               placeholder="support@paxtvnetwork.com"
                               class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Public Helpline Phone</label>
                        <input type="text" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', $setting->contact_phone) }}"
                               placeholder="+91 9876543210"
                               class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Payment Gateway Quick Management -->
        <div class="bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 shadow-2xs">
                        <i class="fa-solid fa-credit-card text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Payment Gateway Integration</h3>
                        <p class="text-xs text-slate-500 font-medium">Active multi-gateway payment processing engine for hotel subscriptions.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-mono font-extrabold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Active
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-[#150A42] font-black text-xs font-mono shadow-2xs">
                        <i class="fa-solid fa-money-check-dollar"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block font-mono">Current Primary Gateway</span>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ strtoupper($activeGateway->name ?? $activeGateway->code ?? 'ICICI Eazypay') }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('super-admin.payment-settings.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-extrabold text-xs shadow-2xs transition-all space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-sliders text-xs text-slate-400"></i>
                    <span>Configure Gateways</span>
                </a>
            </div>
        </div>

        <!-- Submit Footer Actions -->
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
            <a href="{{ route('super-admin.dashboard') }}" 
               class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all text-center">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
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
            alert('Selected logo file is ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB. Maximum allowed size is 2 MB.');
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
            alert('Selected favicon file is ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB. Maximum allowed size is 1 MB.');
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
