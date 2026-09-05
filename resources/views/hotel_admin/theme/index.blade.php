@extends('layouts.hotel_admin')

@section('title', 'TV Themes & Styling - Hotel Admin')
@section('page_title', 'TV Themes & Styling')

@section('content')
<div class="space-y-6">
    <!-- Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center space-x-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Banner Card -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-[11px] font-bold text-indigo-200 border border-white/10">
                <i class="fa-solid fa-palette text-indigo-400"></i>
                <span>In-Room TV Experience Customizer</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-white">Choose Your Hotel's TV Theme</h2>
            <p class="text-xs text-indigo-200/80 leading-relaxed font-medium">
                Select the visual theme and layout style for your in-room smart TVs. When you switch themes, all connected TVs in your hotel receive an instant Over-The-Air (OTA) signal to update their interface.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center shrink-0 min-w-[160px]">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-300 block mb-1">Current Active Theme</span>
            <span class="text-2xl font-black text-white">Theme #{{ $selectedThemeId }}</span>
            <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-300 font-bold mt-1">
                <i class="fa-solid fa-circle-check"></i>
                <span>Running on TV Screens</span>
            </span>
        </div>
    </div>

    <!-- Themes Grid -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-tv text-indigo-600"></i>
                <span>Available TV Themes</span>
            </h3>
            <span class="text-xs font-semibold text-slate-500">
                {{ $activeThemes->count() }} Theme{{ $activeThemes->count() == 1 ? '' : 's' }} Available
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activeThemes as $theme)
                @php
                    $isCurrentTheme = ($selectedThemeId == $theme->theme_id);
                @endphp
                <div class="bg-white border {{ $isCurrentTheme ? 'border-indigo-600 ring-2 ring-indigo-600/20' : 'border-slate-200/80 hover:border-slate-300' }} rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between transition-all group">
                    <!-- Theme Preview Header -->
                    <div class="relative aspect-video bg-slate-900 overflow-hidden flex items-center justify-center">
                        @if($theme->preview_image && Storage::disk('public')->exists($theme->preview_image))
                            <img src="{{ Storage::url($theme->preview_image) }}" alt="{{ $theme->theme_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                                <i class="fa-solid fa-tv text-4xl text-slate-600"></i>
                                <span class="text-xs font-bold text-slate-300">{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">Build v{{ $theme->version }}</span>
                            </div>
                        @endif

                        <!-- Badges Overlay -->
                        <div class="absolute top-3 left-3 flex items-center space-x-2">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-extrabold border border-white/20">
                                Theme ID: {{ $theme->theme_id }}
                            </span>
                            @if($theme->theme_id == 1)
                                <span class="px-2.5 py-1 rounded-xl bg-amber-500/90 backdrop-blur-md text-white text-[10px] font-extrabold">
                                    Default
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-xl bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-bold font-mono">
                                v{{ $theme->version }}
                            </span>
                        </div>
                    </div>

                    <!-- Theme Details & Action Body -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h4 class="font-extrabold text-slate-900 text-base">
                                {{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                High-definition interactive layout optimized for hotel in-room TV screens with OTT apps, room service menus, and hotel amenities.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            @if($isCurrentTheme)
                                <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-xs flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                                    <span>Active Theme on Your TVs</span>
                                </div>
                            @else
                                <form action="{{ route('hotel.themes.select') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="theme_id" value="{{ $theme->theme_id }}">
                                    <button type="submit" class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                                        <span>Activate Theme #{{ $theme->theme_id }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Theme 1 Card if no builds uploaded yet -->
                <div class="bg-white border border-indigo-600 ring-2 ring-indigo-600/20 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="aspect-video bg-slate-900 flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                        <i class="fa-solid fa-tv text-4xl text-slate-600"></i>
                        <span class="text-xs font-bold text-slate-300">Theme #1: Classic TV Theme</span>
                        <span class="text-[10px] text-slate-500 font-mono">Default System Build</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <h4 class="font-extrabold text-slate-900 text-base">Theme #1: Classic TV Theme</h4>
                            <p class="text-xs text-slate-500 font-medium">The standard in-room TV interface running by default on your hotel's screens.</p>
                        </div>
                        <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-xs flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span>Active Theme on Your TVs</span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
