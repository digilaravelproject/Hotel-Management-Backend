@extends('layouts.hotel_admin')

@section('title', 'Smart TV Themes & Styling - Hotel Admin')
@section('page_title', 'TV Themes & Styling')

@section('content')
@php
    $connectedTvCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
    $totalThemesCount = $activeThemes->count();
    $currentActiveTheme = $activeThemes->firstWhere('theme_id', $selectedThemeId);
    $activeThemeName = $currentActiveTheme ? ($currentActiveTheme->theme_name ?? 'Theme #' . $currentActiveTheme->theme_id) : ('Theme #' . $selectedThemeId);
    $activeThemeVersion = $currentActiveTheme ? $currentActiveTheme->version : '1.0';
@endphp

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span>TV Experience</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">TV Themes & Styling</span>
            </div>
            <div class="flex items-center space-x-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Smart TV Themes & Experience</h1>
                <span class="text-xs font-black text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/80 font-mono">
                    <i class="fa-solid fa-paintbrush mr-1 text-purple-600"></i>{{ $activeThemeName }} Active
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Customize the visual appearance, launcher aesthetics, and navigation layout on all guest room Smart TVs.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.menus.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-list-check text-xs text-purple-600"></i>
                <span>Configure TV Menus</span>
            </a>
            <a href="{{ route('hotel.ott-apps') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-brands fa-google-play text-xs text-purple-600"></i>
                <span>OTT Apps Control</span>
            </a>
        </div>
    </div>

    <!-- Executive KPI Metrics Summary Cards (Thin Elegant Borders) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        
        <!-- 1. Active Theme -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-palette text-purple-700"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-base sm:text-lg font-black text-slate-900 tracking-tight truncate">{{ $activeThemeName }}</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Theme #{{ $selectedThemeId }} • v{{ $activeThemeVersion }}</div>
            </div>
        </div>

        <!-- 2. Available Themes -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-layer-group text-indigo-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ max($totalThemesCount, 1) }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Available Themes</div>
            </div>
        </div>

        <!-- 3. Connected Displays -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-tv text-emerald-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $connectedTvCount }} TVs</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Synced with Active Theme</div>
            </div>
        </div>

        <!-- 4. OTA Deployment Signal -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-bolt text-amber-500"></i>
            </div>
            <div class="min-w-0">
                <div class="text-sm sm:text-base font-black text-slate-900 tracking-tight">Instant OTA</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Over-The-Air Cloud Push</div>
            </div>
        </div>
    </div>

    <!-- Hero Guidance Banner (PaxTV Luxury Midnight Theme) -->
    <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[28px] p-5 sm:p-7 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-start sm:items-center space-x-3.5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-2xs shrink-0">
                <i class="fa-solid fa-wand-magic-sparkles text-purple-700"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl bg-purple-50 text-[#150A42] font-black text-xs border border-purple-200/60 shadow-2xs">
                        <i class="fa-solid fa-broadcast-tower mr-1.5 text-purple-600 text-[10px]"></i>
                        Zero In-Room Downtime
                    </span>
                    <span class="text-xs font-bold text-slate-500">OTA TV Signal</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    When you switch themes below, connected room TVs receive an instant cloud broadcast signal to load the new interface without requiring a restart.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 relative z-10 shrink-0">
            <a href="{{ route('hotel.menus.index') }}" class="px-4 py-2 rounded-xl bg-purple-50 text-[#150A42] hover:bg-purple-100 border border-purple-200/80 font-black text-xs transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-purple-600"></i>
                <span>Open Live Simulation in Menus</span>
            </a>
        </div>
    </div>

    <!-- Themes Grid Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-tv text-purple-600"></i>
                <span>Available TV UI Themes</span>
            </h3>
            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-full shadow-2xs">
                {{ max($activeThemes->count(), 1) }} Theme{{ $activeThemes->count() == 1 ? '' : 's' }} Available
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @forelse($activeThemes as $theme)
                @php
                    $isCurrentTheme = ($selectedThemeId == $theme->theme_id);
                    $previewImg = null;
                    if ($theme->preview_image) {
                        if (Storage::disk('public')->exists($theme->preview_image)) {
                            $previewImg = Storage::url($theme->preview_image);
                        } elseif (file_exists(public_path($theme->preview_image))) {
                            $previewImg = asset($theme->preview_image);
                        }
                    }
                @endphp

                <div class="bg-white border rounded-[28px] overflow-hidden flex flex-col justify-between transition-all group duration-200 {{ $isCurrentTheme ? 'border-purple-300/90 ring-2 ring-purple-400/25 shadow-md bg-gradient-to-b from-white to-purple-50/15' : 'border-slate-200/90 hover:border-purple-300 hover:shadow-lg' }}">
                    
                    <!-- Theme Visual Preview Frame -->
                    <div class="relative aspect-video bg-slate-900 overflow-hidden flex items-center justify-center">
                        @if($previewImg)
                            <img src="{{ $previewImg }}" 
                                 alt="{{ $theme->theme_name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-purple-900/40 border border-purple-500/30 flex items-center justify-center text-purple-300 text-xl shadow-inner">
                                    <i class="fa-solid fa-tv"></i>
                                </div>
                                <span class="text-xs font-black text-slate-200 tracking-tight">{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">Build v{{ $theme->version }}</span>
                            </div>
                        @endif

                        <!-- Top Badges Overlay -->
                        <div class="absolute top-3 left-3 flex items-center space-x-1.5">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-black border border-white/20 shadow-2xs font-mono">
                                Theme ID: {{ $theme->theme_id }}
                            </span>
                            @if($theme->theme_id == 1)
                                <span class="px-2 py-0.5 rounded-xl bg-purple-600 text-white text-[10px] font-black shadow-2xs">
                                    Default
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-3 right-3 flex items-center space-x-1">
                            <span class="px-2.5 py-1 rounded-xl bg-[#150A42]/90 backdrop-blur-md text-white text-[10px] font-bold font-mono border border-white/10 shadow-2xs">
                                v{{ $theme->version }}
                            </span>
                        </div>

                        <!-- Active Ribbon Overlay if selected -->
                        @if($isCurrentTheme)
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-center py-1.5 px-3 rounded-xl bg-emerald-500/90 backdrop-blur-md text-white text-xs font-black shadow-lg space-x-1.5">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                <span>Running Live on Hotel TVs</span>
                            </div>
                        @endif
                    </div>

                    <!-- Theme Details & Action Body -->
                    <div class="p-5 sm:p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="font-black text-slate-900 text-base group-hover:text-[#150A42] transition-colors truncate">
                                    {{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}
                                </h4>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $isCurrentTheme ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $isCurrentTheme ? 'Active' : 'Ready' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium line-clamp-2">
                                High-definition interactive layout optimized for hotel Smart TV displays with OTT streaming, in-room dining, and guest services.
                            </p>
                        </div>

                        <div class="pt-3.5 border-t border-slate-100 flex items-center gap-2">
                            @if($isCurrentTheme)
                                <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs flex items-center justify-center space-x-2 shadow-2xs">
                                    <i class="fa-solid fa-circle-check text-sm text-emerald-600"></i>
                                    <span>Currently Active Theme</span>
                                </div>
                            @else
                                <form action="{{ route('hotel.themes.select') }}" method="POST" class="w-full" onsubmit="return confirmThemeSwitch('{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}', {{ $theme->theme_id }});">
                                    @csrf
                                    <input type="hidden" name="theme_id" value="{{ $theme->theme_id }}">
                                    <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95">
                                        <i class="fa-solid fa-wand-magic-sparkles text-xs text-amber-300"></i>
                                        <span>Activate Theme #{{ $theme->theme_id }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Theme 1 Card if no builds uploaded yet -->
                <div class="bg-white border border-purple-300/90 ring-2 ring-purple-400/20 rounded-[28px] overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="aspect-video bg-slate-900 flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-purple-900/40 border border-purple-500/30 flex items-center justify-center text-purple-300 text-xl shadow-inner">
                            <i class="fa-solid fa-tv"></i>
                        </div>
                        <span class="text-xs font-black text-slate-300">Theme #1: Classic TV Theme</span>
                        <span class="text-[10px] text-slate-500 font-mono">Default System Build</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <h4 class="font-black text-slate-900 text-base">Theme #1: Classic TV Theme</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">The standard in-room TV interface running by default across all hotel screens.</p>
                        </div>
                        <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs flex items-center justify-center space-x-2 shadow-2xs">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span>Currently Active Theme</span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmThemeSwitch(themeName, themeId) {
        if (typeof Swal !== 'undefined') {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Switch In-Room TV Theme?',
                html: `Are you sure you want to activate <strong>${themeName}</strong>?<br><br><span class="text-xs text-slate-500">Connected in-room Smart TVs will update their interface automatically.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#150A42',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Activate Theme',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        return confirm(`Switch to ${themeName}? Connected in-room Smart TVs will update automatically.`);
    }
</script>
@endsection
