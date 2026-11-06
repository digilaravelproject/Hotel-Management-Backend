@extends('layouts.hotel_admin')

@section('title', 'TV Themes & Styling - Hotel Admin')
@section('page_title', 'TV Themes & Styling')

@section('content')
<div class="space-y-8">
    <!-- Header Banner Card (PaxTV Signature Modern Theme) -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="space-y-2 max-w-xl relative z-10">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-purple-50 text-purple-700 text-xs font-black border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-palette text-purple-600"></i>
                <span>In-Room TV Experience Customizer</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">Choose Your Hotel's TV Theme</h2>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                Select the visual theme and layout style for your in-room smart TVs. When you switch themes, all connected TVs in your hotel receive an instant Over-The-Air (OTA) signal to update their interface.
            </p>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-center shrink-0 min-w-[180px] shadow-2xs relative z-10">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Current Active Theme</span>
            <span class="text-2xl sm:text-3xl font-black text-[#150A42]">Theme #{{ $selectedThemeId }}</span>
            <div class="inline-flex items-center space-x-1.5 text-xs text-emerald-600 font-bold mt-1.5">
                <i class="fa-solid fa-circle-check text-[11px]"></i>
                <span>Running on TV Screens</span>
            </div>
        </div>
    </div>

    <!-- Themes Grid -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-tv text-purple-600"></i>
                <span>Available TV Themes</span>
            </h3>
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $activeThemes->count() }} Theme{{ $activeThemes->count() == 1 ? '' : 's' }} Available
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activeThemes as $theme)
                @php
                    $isCurrentTheme = ($selectedThemeId == $theme->theme_id);
                @endphp
                <div class="bg-white border {{ $isCurrentTheme ? 'border-purple-600 ring-2 ring-purple-600/20 shadow-md' : 'border-slate-100 hover:border-purple-200 hover:shadow-md' }} rounded-[28px] overflow-hidden shadow-sm flex flex-col justify-between transition-all group">
                    <!-- Theme Preview Header -->
                    <div class="relative aspect-video bg-slate-900 overflow-hidden flex items-center justify-center">
                        @if($theme->preview_image && Storage::disk('public')->exists($theme->preview_image))
                            <img src="{{ Storage::url($theme->preview_image) }}" alt="{{ $theme->theme_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                                <i class="fa-solid fa-tv text-4xl text-purple-400/80"></i>
                                <span class="text-xs font-bold text-slate-200">{{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">Build v{{ $theme->version }}</span>
                            </div>
                        @endif

                        <!-- Badges Overlay -->
                        <div class="absolute top-3 left-3 flex items-center space-x-2">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-black border border-white/20">
                                Theme ID: {{ $theme->theme_id }}
                            </span>
                            @if($theme->theme_id == 1)
                                <span class="px-2.5 py-1 rounded-xl bg-purple-600 text-white text-[10px] font-black">
                                    Default
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-xl bg-[#150A42] text-white text-[10px] font-bold font-mono">
                                v{{ $theme->version }}
                            </span>
                        </div>
                    </div>

                    <!-- Theme Details & Action Body -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h4 class="font-black text-slate-900 text-base">
                                {{ $theme->theme_name ?? ('Theme #' . $theme->theme_id) }}
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                High-definition interactive layout optimized for hotel in-room TV screens with OTT apps, room service menus, and hotel amenities.
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100">
                            @if($isCurrentTheme)
                                <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-sm text-emerald-600"></i>
                                    <span>Active Theme on Your TVs</span>
                                </div>
                            @else
                                <form action="{{ route('hotel.themes.select') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="theme_id" value="{{ $theme->theme_id }}">
                                    <button type="submit" class="w-full py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95">
                                        <i class="fa-solid fa-wand-magic-sparkles text-amber-300"></i>
                                        <span>Activate Theme #{{ $theme->theme_id }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Theme 1 Card if no builds uploaded yet -->
                <div class="bg-white border border-purple-600 ring-2 ring-purple-600/20 rounded-[28px] overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="aspect-video bg-slate-900 flex flex-col items-center justify-center text-slate-400 space-y-2 p-6 text-center">
                        <i class="fa-solid fa-tv text-4xl text-purple-400"></i>
                        <span class="text-xs font-bold text-slate-300">Theme #1: Classic TV Theme</span>
                        <span class="text-[10px] text-slate-500 font-mono">Default System Build</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <h4 class="font-black text-slate-900 text-base">Theme #1: Classic TV Theme</h4>
                            <p class="text-xs text-slate-500 font-medium">The standard in-room TV interface running by default on your hotel's screens.</p>
                        </div>
                        <div class="w-full py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-black text-xs flex items-center justify-center space-x-2">
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
