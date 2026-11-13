@extends('layouts.hotel_admin')

@section('title', 'Smart TV Menu Builder & Live Theme Preview - Hotel Admin')
@section('page_title', 'Smart TV Menu Builder')

@section('styles')
<style>
    /* TV Screen Mockup Aspect Ratio & Luxury Midnight Styling */
    .tv-mockup-frame {
        aspect-ratio: 16 / 9.5;
        background: linear-gradient(135deg, #0d0926 0%, #150a42 50%, #060317 100%);
        box-shadow: 0 20px 40px -15px rgba(21, 10, 66, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    }
    
    .sortable-ghost {
        opacity: 0.35;
        background-color: #ede9fe !important;
        border: 2px dashed #8b5cf6 !important;
    }
    
    .sortable-chosen {
        cursor: grabbing !important;
    }
    
    .sortable-drag {
        opacity: 0.95;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
    }
    
    /* Smooth transition on sub-menu dropzone */
    .sub-menu-dropzone {
        min-height: 54px;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    
    .sub-menu-dropzone:empty::after {
        content: 'Drop sub-menu items here';
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50px;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: rgba(248, 250, 252, 0.7);
    }
</style>
@endsection

@section('content')
@php
    $totalCount = 0;
    $visibleCount = 0;
    $hiddenCount = 0;
    $groupCount = 0;

    foreach($currentTree as $node) {
        $totalCount++;
        $isGrp = isset($node['sub_menus']) && is_array($node['sub_menus']);
        if ($isGrp) {
            $groupCount++;
            foreach($node['sub_menus'] as $sub) {
                $totalCount++;
                if (($sub['status'] ?? 'show') !== 'hide') {
                    $visibleCount++;
                } else {
                    $hiddenCount++;
                }
            }
        }
        if (($node['status'] ?? 'show') !== 'hide') {
            $visibleCount++;
        } else {
            $hiddenCount++;
        }
    }

    $connectedTvCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span>TV Experience</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">TV Menus & Live Theme</span>
            </div>
            <div class="flex items-center space-x-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Smart TV Menu Builder & Live Preview</h1>
                <span class="text-xs font-black text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/80 font-mono">
                    <i class="fa-solid fa-paintbrush mr-1 text-purple-600"></i>{{ $themeName }} (#{{ $selectedThemeId }})
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Customize in-room Smart TV launcher menus, groups, and ordering. See updates immediately in real-time without saving.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if($themePreviewUrl)
                <a href="{{ $themePreviewUrl }}" target="_blank" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-purple-600"></i>
                    <span>Full Tab Preview</span>
                </a>
            @endif
            <button type="button" 
                    onclick="document.getElementById('saveMenuBtn').click()" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                <span>Save & Broadcast to TVs</span>
            </button>
        </div>
    </div>

    <!-- Executive KPI Metric Summary Cards (Thin Elegant Borders) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-layer-group text-purple-700"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" id="kpiTotalCount">{{ $totalCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Total Launch Items</div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-eye text-emerald-600"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" id="kpiVisibleCount">{{ $visibleCount }}</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Visible on Smart TVs</div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-slate-50 text-slate-500 border border-slate-200/80 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-eye-slash text-slate-400"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" id="kpiHiddenCount">{{ $hiddenCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Hidden from TVs</div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-folder-tree text-amber-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" id="kpiGroupCount">{{ $groupCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Menu Groups</div>
            </div>
        </div>
    </div>

    <!-- Main Workspace: Left Builder & Right TV Simulation Mockup -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: HIERARCHY BUILDER (lg:col-span-7) -->
        <div class="lg:col-span-7 space-y-4">

            <!-- Control Bar: Search & Status Filters -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           id="menuSearchInput" 
                           placeholder="Filter menus by name or id..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-400"
                           oninput="filterMenus()">
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                    <div class="inline-flex rounded-xl bg-slate-100 p-0.5 space-x-0.5 text-[11px] font-bold">
                        <button type="button" onclick="setStatusFilter('all')" id="filterBtnAll" class="px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all">All</button>
                        <button type="button" onclick="setStatusFilter('visible')" id="filterBtnVis" class="px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 transition-all">Visible</button>
                        <button type="button" onclick="setStatusFilter('hidden')" id="filterBtnHide" class="px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 transition-all">Hidden</button>
                    </div>

                    <button type="button" 
                            id="resetDefaultBtn" 
                            class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-[11px] font-bold transition-all flex items-center space-x-1 cursor-pointer"
                            title="Restore default PaxTV factory launcher menus">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        <span>Reset Defaults</span>
                    </button>
                </div>
            </div>

            <!-- Drag & Drop Hierarchy List -->
            <div id="rootMenuList" class="space-y-3.5 min-h-[300px]">
                @foreach($currentTree as $item)
                    @php
                        $isGroup = isset($item['sub_menus']) && is_array($item['sub_menus']);
                        $isShown = ($item['status'] ?? 'show') !== 'hide';
                        $nodeId = $item['id'] ?? '';
                        $nodeTitle = $item['name'] ?? $nodeId;
                        $subItems = $isGroup ? $item['sub_menus'] : [];
                    @endphp

                    @if($isGroup)
                        <!-- Group Card Container -->
                        <div class="menu-node group-card bg-white border border-purple-200/90 rounded-[24px] p-4 sm:p-5 shadow-xs space-y-3.5 transition-all"
                             data-id="{{ $nodeId }}"
                             data-is-group="1"
                             data-name="{{ strtolower($nodeTitle) }}"
                             data-status="{{ $isShown ? 'visible' : 'hidden' }}">
                            
                            <!-- Group Header Bar -->
                            <div class="flex items-center justify-between pb-3 border-b border-purple-100/70 gap-3">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1.5 rounded-lg hover:bg-purple-50 transition-colors shrink-0" title="Drag to reorder group">
                                        <i class="fa-solid fa-grip-vertical text-sm"></i>
                                    </span>
                                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-folder-open text-sm text-purple-700"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2 flex-wrap">
                                            <h4 class="text-xs sm:text-sm font-black text-slate-900 truncate node-title">{{ $nodeTitle }}</h4>
                                            <span class="child-count-pill text-[10px] font-bold text-purple-700 bg-purple-100/70 px-2 py-0.5 rounded-full font-mono">
                                                {{ count($subItems) }} Sub-items
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-400 block truncate mt-0.5">ID: {{ $nodeId }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 shrink-0">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle group visibility on TV">
                                        <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#150A42]"></div>
                                    </label>
                                    <span class="status-badge text-[11px] font-black font-mono {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $isShown ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Sub-menu Nested Dropzone -->
                            <div class="sub-menu-dropzone pl-2 sm:pl-4 space-y-2.5 p-2 rounded-2xl bg-purple-50/20 border border-dashed border-purple-200" data-parent-id="{{ $nodeId }}">
                                @foreach($subItems as $sub)
                                    @php
                                        $subId = $sub['id'] ?? '';
                                        $subTitle = $sub['name'] ?? $subId;
                                        $subShown = ($sub['status'] ?? 'show') !== 'hide';
                                        $isNetReq = in_array($subId, ['flights', 'weather']);
                                    @endphp
                                    <div class="menu-node sub-item bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs flex items-center justify-between gap-3 hover:border-purple-300 hover:shadow-xs transition-all"
                                         data-id="{{ $subId }}"
                                         data-is-group="0"
                                         data-name="{{ strtolower($subTitle) }}"
                                         data-status="{{ $subShown ? 'visible' : 'hidden' }}">
                                        
                                        <div class="flex items-center space-x-2.5 min-w-0">
                                            <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1 rounded hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder or move out">
                                                <i class="fa-solid fa-grip-vertical text-xs"></i>
                                            </span>
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-cube text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center space-x-1.5 flex-wrap">
                                                    <h5 class="text-xs font-bold text-slate-800 truncate node-title">{{ $subTitle }}</h5>
                                                    @if($isNetReq)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Internet connection required on TV for live flight/weather radar">
                                                            <i class="fa-solid fa-wifi text-[8px] mr-1 text-amber-600"></i> Net Req
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] font-mono text-slate-400 block truncate">ID: {{ $subId }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2.5 shrink-0">
                                            <button type="button" 
                                                    onclick="extractToRoot(this)" 
                                                    class="extract-to-root-btn px-2.5 py-1 rounded-xl border border-slate-200 hover:bg-purple-50 hover:text-[#150A42] hover:border-purple-200 text-slate-600 text-[11px] font-bold transition-all cursor-pointer flex items-center space-x-1" 
                                                    title="Extract this menu to top-level">
                                                <i class="fa-solid fa-arrow-up-from-bracket text-[10px]"></i>
                                                <span class="hidden sm:inline">Extract</span>
                                            </button>
                                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle visibility">
                                                <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $subShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                                <div class="w-7 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#150A42]"></div>
                                            </label>
                                            <span class="status-badge text-[11px] font-black font-mono {{ $subShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                                {{ $subShown ? 'Visible' : 'Hidden' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Standalone Top-Level Menu Card -->
                        @php
                            $isNetReq = in_array($nodeId, ['flights', 'weather']);
                        @endphp
                        <div class="menu-node standalone-item bg-white border border-slate-200/90 rounded-[24px] p-4 shadow-xs flex items-center justify-between gap-3 hover:border-purple-300 hover:shadow-sm transition-all"
                             data-id="{{ $nodeId }}"
                             data-is-group="0"
                             data-name="{{ strtolower($nodeTitle) }}"
                             data-status="{{ $isShown ? 'visible' : 'hidden' }}">
                            
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder">
                                    <i class="fa-solid fa-grip-vertical text-sm"></i>
                                </span>
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-[#150A42] border border-slate-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-cube text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-1.5 flex-wrap">
                                        <h4 class="text-xs sm:text-sm font-black text-slate-900 truncate node-title">{{ $nodeTitle }}</h4>
                                        @if($isNetReq)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Internet connection required on TV for live flight/weather radar">
                                                <i class="fa-solid fa-wifi text-[8px] mr-1 text-amber-600"></i> Net Req
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400 block truncate mt-0.5">ID: {{ $nodeId }}</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2.5 shrink-0">
                                <select onchange="moveIntoGroup(this)" class="move-into-group-select text-[11px] font-bold rounded-xl border border-slate-200 bg-[#F8F9FD] text-slate-700 px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 cursor-pointer">
                                    <option value="">Move into Group...</option>
                                    @foreach($currentTree as $cand)
                                        @if(isset($cand['sub_menus']) && is_array($cand['sub_menus']))
                                            <option value="{{ $cand['id'] }}">{{ $cand['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label class="relative inline-flex items-center cursor-pointer" title="Toggle visibility">
                                    <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                                <span class="status-badge text-[11px] font-black font-mono {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $isShown ? 'Visible' : 'Hidden' }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>

        <!-- RIGHT COLUMN: LIVE GUEST TV SIMULATION & THEME PREVIEW (lg:col-span-5, sticky) -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-6" id="tvMockupSection">

            <!-- TV Mockup Outer Card -->
            <div class="bg-white border border-slate-200/90 rounded-[28px] p-5 sm:p-6 shadow-sm space-y-4">
                
                <!-- Card Header with Theme Info & Mode Toggles -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-3 border-b border-slate-100">
                    <div class="space-y-0.5 min-w-0">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></div>
                            <h3 class="text-xs sm:text-sm font-black text-slate-900 uppercase font-mono tracking-wider truncate">
                                Live Guest TV Simulation
                            </h3>
                        </div>
                        <div class="text-[11px] text-slate-500 font-medium flex items-center space-x-1.5 truncate">
                            <span class="text-slate-400 shrink-0">Theme:</span>
                            <span class="font-black text-[#150A42] font-mono truncate">{{ $themeName }} (#{{ $selectedThemeId }})</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500 font-mono text-[10px]">v{{ $themeVersion }}</span>
                        </div>
                    </div>

                    <!-- Direct Preview & View Switcher -->
                    <div class="flex items-center space-x-2 shrink-0">
                        @if($themePreviewUrl)
                            <button type="button" onclick="reloadTvIframe()" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all cursor-pointer shadow-2xs" title="Refresh Live TV Theme Preview">
                                <i class="fa-solid fa-rotate-right text-[11px]"></i>
                            </button>
                            <a href="{{ $themePreviewUrl }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-purple-50 text-[#150A42] hover:bg-purple-100 border border-purple-200 text-[11px] font-black flex items-center space-x-1.5 transition-all shadow-2xs" title="Open full extracted theme in new tab with real hotel data">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-purple-600"></i>
                                <span>Preview</span>
                            </a>
                        @endif

                        <!-- Display View Switcher -->
                        <div class="inline-flex rounded-xl bg-slate-100 p-0.5 space-x-0.5 text-[10px] font-black font-mono">
                            <button type="button" 
                                    onclick="setTvDisplayMode('theme')" 
                                    id="tvModeThemeBtn" 
                                    class="px-2.5 py-1 rounded-lg transition-all {{ $themePreviewUrl ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-400 cursor-not-allowed' }}" 
                                    {{ $themePreviewUrl ? '' : 'disabled' }}
                                    title="{{ $themePreviewUrl ? 'View real extracted theme app' : 'No extracted theme zip available' }}">
                                Theme App
                            </button>
                            <button type="button" 
                                    onclick="setTvDisplayMode('nav')" 
                                    id="tvModeNavBtn" 
                                    class="px-2.5 py-1 rounded-lg transition-all {{ $themePreviewUrl ? 'text-slate-600 hover:text-slate-900' : 'bg-white text-slate-900 shadow-2xs' }}"
                                    title="View dynamic live navigation bar">
                                Menu Tabs
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Television Physical Casing / Bezel -->
                <div class="relative rounded-2xl p-2 bg-gradient-to-b from-slate-900 to-slate-950 shadow-2xl border-2 border-slate-700/80">
                    
                    <!-- Top Webcam / Sensor Notch -->
                    <div class="absolute top-1 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-slate-800 border border-slate-700"></div>

                    <!-- Inner 16:9 Screen Display -->
                    <div class="tv-mockup-frame rounded-xl overflow-hidden relative flex flex-col justify-between text-white select-none">
                        
                        <!-- VIEW 1: LIVE EXTRACTED THEME IFRAME (Injected with Real Hotel Data) -->
                        <div id="tvThemeIframeContainer" class="w-full h-full absolute inset-0 {{ $themePreviewUrl ? '' : 'hidden' }} bg-slate-950">
                            @if($themePreviewUrl)
                                <iframe id="tvThemeIframe" 
                                        src="{{ $themePreviewUrl }}" 
                                        class="w-full h-full border-0 pointer-events-auto" 
                                        allowfullscreen 
                                        loading="lazy"></iframe>
                            @endif
                        </div>

                        <!-- VIEW 2: DYNAMIC MENU TABS SIMULATION (Fallback / Alternate View) -->
                        <div id="tvNavigationContainer" class="w-full h-full flex flex-col justify-between p-3.5 relative z-10 {{ $themePreviewUrl ? 'hidden' : '' }}" style="{{ $themePreviewImage ? 'background: linear-gradient(rgba(13, 9, 38, 0.75), rgba(6, 3, 23, 0.88)), url(' . $themePreviewImage . ') center/cover no-repeat;' : '' }}">
                            
                            <!-- Top TV Header Bar (Clock, Weather, Hotel Brand) -->
                            <div class="flex items-center justify-between text-[10px] font-mono tracking-tight text-white/90 bg-black/50 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/10">
                                <div class="flex items-center space-x-2 truncate">
                                    <span class="font-extrabold text-amber-300 font-sans truncate">{{ $hotel->hotel_name }}</span>
                                    <span class="text-white/40">•</span>
                                    <span class="text-white/80 font-bold">Room 101</span>
                                </div>
                                <div class="flex items-center space-x-3 shrink-0">
                                    <span class="flex items-center text-amber-300">
                                        <i class="fa-solid fa-cloud-sun mr-1 text-[10px]"></i> 28°C
                                    </span>
                                    <span id="tvMockupClock" class="font-bold text-white">12:00 PM</span>
                                </div>
                            </div>

                            <!-- TV Middle Showcase Greeting -->
                            <div class="my-auto py-3 text-center space-y-1">
                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/10 text-[9px] uppercase tracking-widest text-amber-300 font-bold font-mono border border-white/10">
                                    {{ $themeName }} Launcher Active
                                </span>
                                <h2 class="text-sm sm:text-base font-black tracking-tight text-white drop-shadow-md">
                                    Welcome to {{ Str::limit($hotel->hotel_name, 26) }}
                                </h2>
                                <p class="text-[10px] text-white/70 max-w-xs mx-auto drop-shadow-sm line-clamp-1">
                                    Interactive TV launcher synced in real-time.
                                </p>
                            </div>

                            <!-- TV Bottom Navigation Bar (Mirroring current tree) -->
                            <div class="space-y-1.5">
                                <div class="text-[9px] font-mono uppercase tracking-wider text-amber-300/80 flex items-center justify-between px-1">
                                    <span><i class="fa-solid fa-arrow-pointer mr-1"></i> Active Navigation Tabs</span>
                                    <span id="tvTabCount">0 Tabs</span>
                                </div>
                                
                                <!-- Dynamic TV Menu Tabs Bar -->
                                <div id="tvMockupNavBar" class="flex items-center space-x-1.5 overflow-x-auto py-1 px-1.5 rounded-xl bg-black/60 backdrop-blur-md border border-white/10 scrollbar-none">
                                    <!-- Populated dynamically by JS -->
                                </div>
                            </div>

                            <!-- Bottom TV Submenu Dropdown Popover (Interactive preview on TV) -->
                            <div id="tvSubMenuPopup" class="hidden absolute bottom-14 left-4 right-4 bg-slate-900/95 backdrop-blur-xl border border-white/20 rounded-xl p-2.5 shadow-2xl z-30 space-y-1.5">
                                <div class="flex items-center justify-between pb-1 border-b border-white/10 text-[10px] font-bold text-amber-300">
                                    <span id="tvSubMenuTitle">Sub-menu Items</span>
                                    <button type="button" onclick="closeTvSubmenu()" class="text-white/60 hover:text-white p-0.5">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </div>
                                <div id="tvSubMenuItemsGrid" class="grid grid-cols-2 gap-1.5 text-[10px]">
                                    <!-- Populated dynamically -->
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Bottom Physical TV Stand / Logo Bar -->
                    <div class="flex items-center justify-between px-2 pt-1.5 pb-0.5 text-[8px] font-mono text-slate-500">
                        <span class="font-bold text-slate-400">ACTIVE: {{ strtoupper($themeName) }}</span>
                        <span class="font-black uppercase tracking-[0.2em] text-slate-400">PAX TV HOSPITALITY</span>
                        <span class="text-emerald-500 font-bold">1080p FHD</span>
                    </div>
                </div>

                <!-- Real-Time Sync Indicator Box -->
                <div class="p-3.5 rounded-2xl bg-purple-50/40 border border-purple-100 text-xs space-y-2">
                    <div class="flex items-start space-x-2.5">
                        <i class="fa-solid fa-bolt text-purple-600 text-sm mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                            <strong>Live Real-Time Sync:</strong> The TV Simulation above reflects your drag-and-drop menu order and toggle switches instantly without saving. Click <em>Save & Broadcast to TVs</em> below when ready to push changes to physical guest room screens.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- STICKY BOTTOM SAVE & SYNC CONTROLS BAR -->
    <div class="bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-[28px] p-4 sm:p-5 shadow-xl flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 sticky bottom-5 z-40">
        <div class="flex items-center space-x-3">
            <div id="saveStatusIndicator" class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="saveStatusText">All Changes Synced</span>
            </div>
            <span class="text-xs text-slate-400 font-medium hidden md:inline">Target: <strong>{{ $hotel->hotel_name }}</strong> ({{ $connectedTvCount }} In-Room Displays)</span>
        </div>

        <div class="flex items-center space-x-2.5 justify-end">
            <a href="{{ route('hotel.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs">
                Cancel
            </a>
            <button type="button" 
                    id="saveMenuBtn" 
                    class="px-7 py-3 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/25 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-amber-300"></i>
                <span id="saveBtnText">Save & Broadcast to TVs</span>
            </button>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const itemCatalog = @json($itemCatalog);
    const saveUrl = "{{ url('/hotel/menus') }}";
    const resetUrl = "{{ route('hotel.menus.reset') }}";
    const csrfToken = "{{ csrf_token() }}";
    let isDirty = false;
    let tvDisplayMode = "{{ $themePreviewUrl ? 'theme' : 'nav' }}";
    let currentStatusFilter = 'all';

    // FontAwesome icon lookup for each catalog item
    const iconMap = {
        'hotel_menu': 'fa-hotel',
        'hotel_info': 'fa-circle-info',
        'room_info': 'fa-bed',
        'amenities': 'fa-bell-concierge',
        'apps': 'fa-shapes',
        'language': 'fa-globe',
        'livetv': 'fa-tv',
        'interactive_services': 'fa-wand-magic-sparkles',
        'flights': 'fa-plane-departure',
        'weather': 'fa-cloud-sun',
        'input': 'fa-right-to-bracket',
        'settings': 'fa-gear',
        'ourcity': 'fa-city',
        'screen_cast': 'fa-chromecast'
    };

    // Live clock for TV mockup
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        const clockEl = document.getElementById('tvMockupClock');
        if (clockEl) clockEl.textContent = `${hours}:${minutes} ${ampm}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Toggle TV display mode between 'theme' (iframe) and 'nav' (interactive tabs)
    function setTvDisplayMode(mode) {
        tvDisplayMode = mode;
        const iframeBox = document.getElementById('tvThemeIframeContainer');
        const navBox = document.getElementById('tvNavigationContainer');
        const themeBtn = document.getElementById('tvModeThemeBtn');
        const navBtn = document.getElementById('tvModeNavBtn');

        if (mode === 'theme' && iframeBox) {
            iframeBox.classList.remove('hidden');
            if (navBox) navBox.classList.add('hidden');
            if (themeBtn) themeBtn.className = 'px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs font-extrabold transition-all';
            if (navBtn) navBtn.className = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-extrabold transition-all';
        } else {
            if (iframeBox) iframeBox.classList.add('hidden');
            if (navBox) navBox.classList.remove('hidden');
            if (themeBtn) themeBtn.className = 'px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-extrabold transition-all';
            if (navBtn) navBtn.className = 'px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs font-extrabold transition-all';
        }
    }

    // Initialize Sortable on Root List
    const rootEl = document.getElementById('rootMenuList');
    if (rootEl) {
        new Sortable(rootEl, {
            group: {
                name: 'nested-menus',
                pull: true,
                put: ['nested-menus']
            },
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'sortable-ghost',
            onEnd: () => {
                markDirty();
                refreshStateAndTvMockup();
            }
        });
    }

    // Initialize Sortable on Sub-menu dropzones
    function initDropzones() {
        document.querySelectorAll('.sub-menu-dropzone').forEach(dropzone => {
            if (dropzone._sortableInit) return;
            new Sortable(dropzone, {
                group: {
                    name: 'nested-menus',
                    pull: true,
                    put: ['nested-menus']
                },
                handle: '.drag-handle',
                animation: 180,
                ghostClass: 'sortable-ghost',
                onEnd: () => {
                    markDirty();
                    refreshStateAndTvMockup();
                }
            });
            dropzone._sortableInit = true;
        });
    }
    initDropzones();

    // Mark dirty state
    function markDirty() {
        isDirty = true;
        const badge = document.getElementById('saveStatusIndicator');
        const text = document.getElementById('saveStatusText');
        if (badge && text) {
            badge.className = 'flex items-center space-x-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold font-mono';
            badge.querySelector('span:first-child').className = 'w-2 h-2 rounded-full bg-amber-500 animate-ping';
            text.textContent = 'Unsaved Changes (Live Preview Active)';
        }
    }

    // Toggle node visibility checkbox handler (Clean English: Visible / Hidden)
    function onNodeToggle(input) {
        const node = input.closest('.menu-node');
        if (!node) return;
        const isChecked = input.checked;
        node.dataset.status = isChecked ? 'visible' : 'hidden';

        const badge = node.querySelector('.status-badge');
        if (badge) {
            badge.textContent = isChecked ? 'Visible' : 'Hidden';
            badge.className = 'status-badge text-[11px] font-black font-mono ' + (isChecked ? 'text-emerald-600' : 'text-slate-400');
        }

        markDirty();
        refreshStateAndTvMockup();
    }

    // Extract item out of group to root level
    function extractToRoot(btn) {
        const subItem = btn.closest('.sub-item');
        if (!subItem) return;

        const id = subItem.dataset.id;
        const isChecked = subItem.querySelector('.node-status-checkbox').checked;
        const meta = itemCatalog[id] || { name: id };
        const iconClass = iconMap[id] || 'fa-cube';
        const isNetReq = ['flights', 'weather'].includes(id);

        const escMenuHtml = (v) => String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        const safeIconClass = String(iconClass || 'fa-cube').replace(/[^a-zA-Z0-9_-]/g, '');

        // Collect available group options
        let groupOptionsHtml = '<option value="">Move into Group...</option>';
        document.querySelectorAll('.group-card').forEach(g => {
            const gid = g.dataset.id;
            const gtitle = g.querySelector('.node-title')?.textContent || gid;
            groupOptionsHtml += `<option value="${escMenuHtml(gid)}">${escMenuHtml(gtitle)}</option>`;
        });

        const newCard = document.createElement('div');
        newCard.className = 'menu-node standalone-item bg-white border border-slate-200/90 rounded-[24px] p-4 shadow-xs flex items-center justify-between gap-3 hover:border-purple-300 hover:shadow-sm transition-all';
        newCard.dataset.id = id;
        newCard.dataset.isGroup = '0';
        newCard.dataset.name = (meta.name || id).toLowerCase();
        newCard.dataset.status = isChecked ? 'visible' : 'hidden';

        newCard.innerHTML = `
            <div class="flex items-center space-x-3 min-w-0">
                <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder">
                    <i class="fa-solid fa-grip-vertical text-sm"></i>
                </span>
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-[#150A42] border border-slate-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                    <i class="fa-solid ${safeIconClass} text-sm"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center space-x-1.5 flex-wrap">
                        <h4 class="text-xs sm:text-sm font-black text-slate-900 truncate node-title">${escMenuHtml(meta.name)}</h4>
                        ${isNetReq ? `
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Internet connection required on TV">
                                <i class="fa-solid fa-wifi text-[8px] mr-1 text-amber-600"></i> Net Req
                            </span>` : ''}
                    </div>
                    <span class="text-[10px] font-mono text-slate-400 block truncate mt-0.5">ID: ${escMenuHtml(id)}</span>
                </div>
            </div>
            <div class="flex items-center space-x-2.5 shrink-0">
                <select onchange="moveIntoGroup(this)" class="move-into-group-select text-[11px] font-bold rounded-xl border border-slate-200 bg-[#F8F9FD] text-slate-700 px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 cursor-pointer">
                    ${groupOptionsHtml}
                </select>
                <label class="relative inline-flex items-center cursor-pointer" title="Toggle visibility">
                    <input type="checkbox" class="sr-only peer node-status-checkbox" ${isChecked ? 'checked' : ''} onchange="onNodeToggle(this)">
                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                </label>
                <span class="status-badge text-[11px] font-black font-mono ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
                    ${isChecked ? 'Visible' : 'Hidden'}
                </span>
            </div>
        `;

        subItem.remove();
        rootEl.appendChild(newCard);

        markDirty();
        refreshStateAndTvMockup();
    }

    // Move standalone item into group
    function moveIntoGroup(select) {
        const targetGroupId = select.value;
        if (!targetGroupId) return;

        const standaloneCard = select.closest('.standalone-item');
        if (!standaloneCard) return;

        const targetDropzone = document.querySelector(`.sub-menu-dropzone[data-parent-id="${targetGroupId}"]`);
        if (!targetDropzone) return;

        const id = standaloneCard.dataset.id;
        const isChecked = standaloneCard.querySelector('.node-status-checkbox').checked;
        const meta = itemCatalog[id] || { name: id };
        const iconClass = iconMap[id] || 'fa-cube';
        const isNetReq = ['flights', 'weather'].includes(id);

        const escMenuHtml = (v) => String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        const safeIconClass = String(iconClass || 'fa-cube').replace(/[^a-zA-Z0-9_-]/g, '');

        const newSub = document.createElement('div');
        newSub.className = 'menu-node sub-item bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs flex items-center justify-between gap-3 hover:border-purple-200 hover:shadow-xs transition-all';
        newSub.dataset.id = id;
        newSub.dataset.isGroup = '0';
        newSub.dataset.name = (meta.name || id).toLowerCase();
        newSub.dataset.status = isChecked ? 'visible' : 'hidden';

        newSub.innerHTML = `
            <div class="flex items-center space-x-2.5 min-w-0">
                <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1 rounded hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder or move out">
                    <i class="fa-solid fa-grip-vertical text-xs"></i>
                </span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid ${safeIconClass} text-xs"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center space-x-1.5 flex-wrap">
                        <h5 class="text-xs font-bold text-slate-800 truncate node-title">${escMenuHtml(meta.name)}</h5>
                        ${isNetReq ? `
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Internet connection required on TV">
                                <i class="fa-solid fa-wifi text-[8px] mr-1 text-amber-600"></i> Net Req
                            </span>` : ''}
                    </div>
                    <span class="text-[10px] font-mono text-slate-400 block truncate">ID: ${escMenuHtml(id)}</span>
                </div>
            </div>
            <div class="flex items-center space-x-2.5 shrink-0">
                <button type="button" 
                        onclick="extractToRoot(this)" 
                        class="extract-to-root-btn px-2.5 py-1 rounded-xl border border-slate-200 hover:bg-purple-50 hover:text-[#150A42] hover:border-purple-200 text-slate-600 text-[11px] font-bold transition-all cursor-pointer flex items-center space-x-1" 
                        title="Extract this menu to top-level">
                    <i class="fa-solid fa-arrow-up-from-bracket text-[10px]"></i>
                    <span class="hidden sm:inline">Extract</span>
                </button>
                <label class="relative inline-flex items-center cursor-pointer" title="Toggle visibility">
                    <input type="checkbox" class="sr-only peer node-status-checkbox" ${isChecked ? 'checked' : ''} onchange="onNodeToggle(this)">
                    <div class="w-7 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#150A42]"></div>
                </label>
                <span class="status-badge text-[11px] font-black font-mono ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
                    ${isChecked ? 'Visible' : 'Hidden'}
                </span>
            </div>
        `;

        standaloneCard.remove();
        targetDropzone.appendChild(newSub);

        markDirty();
        refreshStateAndTvMockup();
    }

    // Build Current Menu Hierarchy JSON Array
    function buildMenuHierarchy() {
        const result = [];
        const rootNodes = rootEl.children;

        for (let node of rootNodes) {
            if (!node.classList.contains('menu-node')) continue;

            const id = node.dataset.id;
            const isGroup = node.classList.contains('group-card');
            const status = node.querySelector('.node-status-checkbox')?.checked ? 'show' : 'hide';
            const name = node.querySelector('.node-title')?.textContent || id;

            if (isGroup) {
                const dropzone = node.querySelector('.sub-menu-dropzone');
                const subNodes = dropzone ? dropzone.querySelectorAll('.menu-node') : [];
                const subMenus = [];

                subNodes.forEach(sub => {
                    const subId = sub.dataset.id;
                    const subStatus = sub.querySelector('.node-status-checkbox')?.checked ? 'show' : 'hide';
                    const subName = sub.querySelector('.node-title')?.textContent || subId;
                    subMenus.push({
                        id: subId,
                        name: subName,
                        status: subStatus
                    });
                });

                result.push({
                    id: id,
                    name: name,
                    status: status,
                    sub_menus: subMenus
                });
            } else {
                result.push({
                    id: id,
                    name: name,
                    status: status
                });
            }
        }

        return result;
    }

    // Refresh KPI counts & Live TV Simulation
    function refreshStateAndTvMockup() {
        let total = 0;
        let visible = 0;
        let hidden = 0;
        let groups = 0;

        const navBar = document.getElementById('tvMockupNavBar');
        if (!navBar) return;
        navBar.innerHTML = '';

        const rootNodes = rootEl.children;
        for (let node of rootNodes) {
            if (!node.classList.contains('menu-node')) continue;
            total++;

            const isGroup = node.classList.contains('group-card');
            const isVisible = node.querySelector('.node-status-checkbox')?.checked ?? true;
            const title = node.querySelector('.node-title')?.textContent || node.dataset.id;
            const id = node.dataset.id;
            const icon = iconMap[id] || (isGroup ? 'fa-folder' : 'fa-cube');

            if (isGroup) {
                groups++;
                const dropzone = node.querySelector('.sub-menu-dropzone');
                const subItems = dropzone ? Array.from(dropzone.children).filter(c => c.classList.contains('menu-node')) : [];
                
                // Update child count pill on card
                const pill = node.querySelector('.child-count-pill');
                if (pill) pill.textContent = `${subItems.length} Sub-items`;

                let visibleSubCount = 0;
                const subDataList = [];

                subItems.forEach(sub => {
                    total++;
                    const subIsVis = sub.querySelector('.node-status-checkbox')?.checked ?? true;
                    if (subIsVis) {
                        visible++;
                        visibleSubCount++;
                    } else {
                        hidden++;
                    }
                    subDataList.push({
                        id: sub.dataset.id,
                        name: sub.querySelector('.node-title')?.textContent || sub.dataset.id,
                        visible: subIsVis,
                        icon: iconMap[sub.dataset.id] || 'fa-cube'
                    });
                });

                if (isVisible) {
                    visible++;
                    // Add Tab to TV mockup
                    const tabBtn = document.createElement('button');
                    tabBtn.type = 'button';
                    tabBtn.className = 'px-2 py-1 rounded-lg bg-white/10 hover:bg-white/25 text-white text-[9px] font-bold whitespace-nowrap flex items-center space-x-1 border border-white/15 transition-all shrink-0 cursor-pointer active:scale-95';
                    const safeIcon = String(icon || 'fa-cube').replace(/[^a-zA-Z0-9_-]/g, '');
                    const iconEl = document.createElement('i');
                    iconEl.className = `fa-solid ${safeIcon} text-amber-300 text-[8px]`;
                    const titleSpan = document.createElement('span');
                    titleSpan.textContent = title;
                    const chev = document.createElement('i');
                    chev.className = 'fa-solid fa-chevron-down text-[7px] text-white/60 ml-0.5';
                    tabBtn.appendChild(iconEl);
                    tabBtn.appendChild(titleSpan);
                    tabBtn.appendChild(chev);
                    tabBtn.onclick = () => openTvSubmenu(title, subDataList);
                    navBar.appendChild(tabBtn);
                } else {
                    hidden++;
                }

            } else {
                if (isVisible) {
                    visible++;
                    // Add Tab to TV mockup
                    const tabBtn = document.createElement('div');
                    tabBtn.className = 'px-2 py-1 rounded-lg bg-white/10 text-white text-[9px] font-bold whitespace-nowrap flex items-center space-x-1 border border-white/10 shrink-0';
                    const safeIcon = String(icon || 'fa-cube').replace(/[^a-zA-Z0-9_-]/g, '');
                    const iconEl = document.createElement('i');
                    iconEl.className = `fa-solid ${safeIcon} text-amber-300 text-[8px]`;
                    const titleSpan = document.createElement('span');
                    titleSpan.textContent = title;
                    tabBtn.appendChild(iconEl);
                    tabBtn.appendChild(titleSpan);
                    navBar.appendChild(tabBtn);
                } else {
                    hidden++;
                }
            }
        }

        // Update KPIs
        const totalEl = document.getElementById('kpiTotalCount');
        const visEl = document.getElementById('kpiVisibleCount');
        const hideEl = document.getElementById('kpiHiddenCount');
        const grpEl = document.getElementById('kpiGroupCount');
        const tabCountEl = document.getElementById('tvTabCount');

        if (totalEl) totalEl.textContent = total;
        if (visEl) visEl.textContent = visible;
        if (hideEl) hideEl.textContent = hidden;
        if (grpEl) grpEl.textContent = groups;
        if (tabCountEl) tabCountEl.textContent = `${navBar.children.length} Tabs`;

        // Broadcast real-time menus update to same-origin theme iframe instantly without saving!
        try {
            const iframe = document.getElementById('tvThemeIframe');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage({
                    type: 'UPDATE_MENU',
                    menus: buildMenuHierarchy()
                }, window.location.origin);
            }
        } catch(e) {}

        initDropzones();
    }

    // Reload the TV theme iframe
    function reloadTvIframe() {
        const iframe = document.getElementById('tvThemeIframe');
        if (iframe) {
            iframe.src = iframe.src;
        }
    }

    // Open sub-menu preview popover on TV mockup
    function openTvSubmenu(groupTitle, subItems) {
        const popup = document.getElementById('tvSubMenuPopup');
        const titleEl = document.getElementById('tvSubMenuTitle');
        const grid = document.getElementById('tvSubMenuItemsGrid');
        if (!popup || !titleEl || !grid) return;

        titleEl.textContent = `${groupTitle} (${subItems.filter(s => s.visible).length} Active)`;
        grid.innerHTML = '';

        const activeSubs = subItems.filter(s => s.visible);
        if (activeSubs.length === 0) {
            const emptySpan = document.createElement('span');
            emptySpan.className = 'text-[9px] text-white/50 col-span-2 italic';
            emptySpan.textContent = 'No active items in this group.';
            grid.appendChild(emptySpan);
        } else {
            activeSubs.forEach(s => {
                const row = document.createElement('div');
                row.className = 'flex items-center space-x-1.5 p-1 rounded bg-white/5 border border-white/10 text-white';
                const iconEl = document.createElement('i');
                const safeIcon = String(s.icon || 'fa-cube').replace(/[^a-zA-Z0-9_-]/g, '');
                iconEl.className = `fa-solid ${safeIcon} text-amber-300 text-[8px] w-3`;
                const nameSpan = document.createElement('span');
                nameSpan.className = 'truncate';
                nameSpan.textContent = s.name || '';
                row.appendChild(iconEl);
                row.appendChild(nameSpan);
                grid.appendChild(row);
            });
        }

        popup.classList.remove('hidden');
    }

    function closeTvSubmenu() {
        const popup = document.getElementById('tvSubMenuPopup');
        if (popup) popup.classList.add('hidden');
    }

    // Filter status tabs
    function setStatusFilter(status) {
        currentStatusFilter = status;
        document.getElementById('filterBtnAll').className = status === 'all' ? 'px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all' : 'px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 transition-all';
        document.getElementById('filterBtnVis').className = status === 'visible' ? 'px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all' : 'px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 transition-all';
        document.getElementById('filterBtnHide').className = status === 'hidden' ? 'px-2.5 py-1 rounded-lg bg-white text-slate-900 shadow-2xs transition-all' : 'px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 transition-all';
        filterMenus();
    }

    function filterMenus() {
        const query = (document.getElementById('menuSearchInput')?.value || '').toLowerCase().trim();
        const rootNodes = rootEl.querySelectorAll('.menu-node');

        rootNodes.forEach(node => {
            const name = (node.dataset.name || '').toLowerCase();
            const id = (node.dataset.id || '').toLowerCase();
            const status = node.dataset.status || 'visible';

            const matchesQuery = !query || name.includes(query) || id.includes(query);
            const matchesStatus = currentStatusFilter === 'all' || status === currentStatusFilter;

            if (matchesQuery && matchesStatus) {
                node.style.display = '';
            } else {
                node.style.display = 'none';
            }
        });
    }

    // Save Menus to server via AJAX
    document.getElementById('saveMenuBtn').addEventListener('click', function() {
        const hierarchy = buildMenuHierarchy();
        const btn = document.getElementById('saveMenuBtn');
        const originalText = document.getElementById('saveBtnText').textContent;

        btn.disabled = true;
        document.getElementById('saveBtnText').textContent = 'Broadcasting to Cloud & TVs...';

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menus_hierarchy: hierarchy })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            document.getElementById('saveBtnText').textContent = originalText;

            if (data.status === 'success') {
                isDirty = false;
                const badge = document.getElementById('saveStatusIndicator');
                const text = document.getElementById('saveStatusText');
                if (badge && text) {
                    badge.className = 'flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold font-mono';
                    badge.querySelector('span:first-child').className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
                    text.textContent = 'All Changes Synced & Broadcasted';
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'TV Menus Synced & Broadcasted!',
                        text: data.message || 'Config successfully sent to connected TVs in real time.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    alert('TV Menus Synced & Broadcasted!');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: data.message || 'Something went wrong.'
                    });
                } else {
                    alert(data.message || 'Save failed');
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            document.getElementById('saveBtnText').textContent = originalText;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Could not connect to the menu sync service.'
                });
            } else {
                alert('Server Error: Could not connect to menu sync service.');
            }
        });
    });

    // Reset to Default
    document.getElementById('resetDefaultBtn').addEventListener('click', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Reset TV Menus to Default?',
                text: 'This will restore the factory menu ordering, groups, and standard launcher icons for {{ $hotel->hotel_name }}.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Restore Defaults'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeReset();
                }
            });
        } else {
            if (confirm('Restore factory default menus?')) {
                executeReset();
            }
        }
    });

    function executeReset() {
        fetch(resetUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Factory Menus Restored',
                        text: data.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    location.reload();
                }
            }
        });
    }

    // Initial render of TV mockup
    refreshStateAndTvMockup();
</script>
@endsection
