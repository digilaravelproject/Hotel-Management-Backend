@extends('layouts.super_admin')

@section('title', 'Manage TV Menus - ' . $hotel->hotel_name)
@section('page_title', 'Smart TV Menu Builder - ' . $hotel->hotel_name)

@section('styles')
<style>
    /* TV Screen Mockup Aspect Ratio & Styling */
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

    <!-- Top Hero Header Card -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-7 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
            <!-- Left Info Block -->
            <div class="flex items-start sm:items-center space-x-4 min-w-0">
                <div class="w-14 h-14 rounded-2xl bg-[#150A42] text-white flex items-center justify-center text-xl shrink-0 shadow-md shadow-[#150A42]/20 border border-purple-900/30">
                    <i class="fa-solid fa-layer-group text-amber-300"></i>
                </div>
                <div class="min-w-0 space-y-1">
                    <div class="flex items-center space-x-2 flex-wrap">
                        <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-bold text-slate-400 hover:text-[#150A42] transition-colors flex items-center">
                            <i class="fa-solid fa-arrow-left mr-1.5 text-[10px]"></i>
                            <span>Hotels Directory</span>
                        </a>
                        <span class="text-slate-300">•</span>
                        <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="text-xs font-bold text-slate-600 hover:text-[#150A42] transition-colors truncate">
                            {{ $hotel->hotel_name }}
                        </a>
                        <span class="text-slate-300">•</span>
                        <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-100 font-mono">
                            <i class="fa-solid fa-paintbrush mr-1 text-purple-600"></i>{{ $themeName }} (Theme #{{ $selectedThemeId }})
                        </span>
                    </div>

                    <div class="flex items-center space-x-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans truncate">
                            {{ $hotel->hotel_name }} — TV Navigation Builder
                        </h1>
                    </div>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-medium flex-wrap pt-0.5">
                        <span class="flex items-center text-slate-600">
                            <i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[11px]"></i>
                            {{ $hotel->city ?? $hotel->hotel_location }}
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="flex items-center">
                            <i class="fa-solid fa-key mr-1 text-slate-400 text-[10px]"></i>
                            <code class="bg-[#F8F9FD] border border-slate-200 px-1.5 py-0.5 rounded font-mono text-[#150A42] text-[11px] font-bold select-all">{{ $hotel->license_key ?? 'NO-LICENSE' }}</code>
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold font-mono">
                            <i class="fa-solid fa-tv mr-1 text-[10px] text-emerald-600"></i> {{ $connectedTvCount }} TVs Paired
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Quick Actions -->
            <div class="flex items-center space-x-2.5 w-full lg:w-auto justify-end shrink-0 pt-2 lg:pt-0">
                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50/50 text-xs font-bold transition-all shadow-xs flex items-center space-x-2">
                    <i class="fa-regular fa-eye text-xs text-slate-400"></i>
                    <span>Hotel Profile</span>
                </a>
                <button type="button" id="resetDefaultBtn" class="px-4 py-2.5 rounded-2xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/70 text-rose-700 text-xs font-bold transition-all flex items-center space-x-2 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset Defaults</span>
                </button>
                <button type="button" onclick="scrollToTvPreview()" class="lg:hidden px-4 py-2.5 rounded-2xl bg-[#150A42] text-white text-xs font-bold transition-all flex items-center space-x-2 shadow-xs cursor-pointer">
                    <i class="fa-solid fa-tv text-amber-300 text-xs"></i>
                    <span>View TV Mockup</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 KPI Metrics Stats Bar -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- 1. Total Menu Items -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-list"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight" id="kpiTotalCount">{{ $totalCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Total Menu Items</div>
            </div>
        </div>

        <!-- 2. Active on TV Launcher -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight leading-tight" id="kpiVisibleCount">{{ $visibleCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Active on Guest TVs</div>
            </div>
        </div>

        <!-- 3. Hidden Menus -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-eye-slash"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-700 tracking-tight leading-tight" id="kpiHiddenCount">{{ $hiddenCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Hidden / Dormant</div>
            </div>
        </div>

        <!-- 4. Group Folders -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight" id="kpiGroupCount">{{ $groupCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Parent Groups</div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN WORKSPACE: Tree Builder (Left) + Interactive Live TV Mockup (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: Menu Tree & Reordering (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">

            <!-- Search, Filter & Quick Instructions Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-0">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               id="menuSearchInput" 
                               placeholder="Search TV menu name, category, or ID..." 
                               oninput="filterMenus()" 
                               class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-[#F8F9FD] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] font-medium text-slate-800 placeholder:text-slate-400 transition-all">
                    </div>

                    <!-- Visibility Filter Buttons -->
                    <div class="inline-flex rounded-xl bg-slate-100 p-1 shrink-0 space-x-1" role="group">
                        <button type="button" onclick="setStatusFilter('all')" id="filterBtnAll" class="px-3 py-1 text-xs font-bold rounded-lg bg-white text-slate-800 shadow-2xs transition-all">All</button>
                        <button type="button" onclick="setStatusFilter('visible')" id="filterBtnVisible" class="px-3 py-1 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all">Visible</button>
                        <button type="button" onclick="setStatusFilter('hidden')" id="filterBtnHidden" class="px-3 py-1 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all">Hidden</button>
                    </div>
                </div>

                <!-- Info Hint -->
                <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-100 pt-2.5">
                    <div class="flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrows-up-down text-purple-600 text-xs"></i>
                        <span>Drag handle <strong>(⋮⋮)</strong> to reorder menus or move between parent groups.</span>
                    </div>
                    <span class="hidden sm:inline-block font-mono text-[#150A42] font-semibold">Websocket TV Broadcast Ready</span>
                </div>
            </div>

            <!-- Root Menu Drag-and-Drop Container -->
            <div id="rootMenuList" class="space-y-3.5 min-h-[300px]">
                @foreach($currentTree as $item)
                    @php
                        $isGroup = isset($item['sub_menus']) && is_array($item['sub_menus']);
                        $isShown = ($item['status'] ?? 'show') !== 'hide';
                        $nodeId = $item['id'];
                    @endphp

                    @if($isGroup)
                        <!-- PARENT GROUP CONTAINER CARD -->
                        <div class="menu-node group-card bg-white border-2 border-purple-100/90 rounded-[26px] p-4 sm:p-5 shadow-xs space-y-3.5 hover:border-purple-300 transition-all" 
                             data-id="{{ $nodeId }}" 
                             data-is-group="1"
                             data-name="{{ strtolower($item['name']) }}"
                             data-status="{{ $isShown ? 'visible' : 'hidden' }}">
                            
                            <!-- Group Header Row -->
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 gap-3">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1.5 rounded-lg hover:bg-purple-50 transition-colors shrink-0" title="Drag to reorder group">
                                        <i class="fa-solid fa-grip-vertical text-sm"></i>
                                    </span>
                                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-folder-open text-base"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-sm font-extrabold text-slate-900 truncate node-title">{{ $item['name'] }}</h4>
                                            <span class="px-2 py-0.5 rounded-full bg-purple-50 border border-purple-100 text-[#150A42] text-[10px] font-extrabold uppercase font-mono tracking-wider shrink-0">Parent Group</span>
                                        </div>
                                        <div class="flex items-center space-x-2 mt-0.5 text-[11px] text-slate-400 font-mono">
                                            <span>ID: {{ $nodeId }}</span>
                                            <span>•</span>
                                            <span class="child-count-pill font-bold text-slate-600 font-sans">{{ count($item['sub_menus']) }} Sub-items</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visibility Toggle Switch -->
                                <div class="flex items-center space-x-2.5 shrink-0">
                                    <span class="status-badge text-[11px] font-extrabold font-mono {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $isShown ? 'Visible' : 'Hidden' }}
                                    </span>
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle group visibility">
                                        <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                        <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Sub-menu dropzone -->
                            <div class="sub-menu-dropzone pl-2 sm:pl-6 space-y-2.5 p-2.5 rounded-2xl bg-[#F8F9FD]/80 border border-dashed border-slate-200/90" data-parent-id="{{ $nodeId }}">
                                @foreach($item['sub_menus'] as $subItem)
                                    @php 
                                        $isSubShown = ($subItem['status'] ?? 'show') !== 'hide'; 
                                        $subId = $subItem['id'];
                                    @endphp
                                    <div class="menu-node sub-item bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs flex items-center justify-between gap-3 hover:border-purple-200 hover:shadow-xs transition-all" 
                                         data-id="{{ $subId }}" 
                                         data-is-group="0"
                                         data-name="{{ strtolower($subItem['name']) }}"
                                         data-status="{{ $isSubShown ? 'visible' : 'hidden' }}">
                                        
                                        <div class="flex items-center space-x-2.5 min-w-0">
                                            <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1 rounded hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder or move out">
                                                <i class="fa-solid fa-grip-vertical text-xs"></i>
                                            </span>
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-cube text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h5 class="text-xs font-bold text-slate-800 truncate node-title">{{ $subItem['name'] }}</h5>
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
                                                <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isSubShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                                <div class="w-7 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#150A42]"></div>
                                            </label>
                                            <span class="status-badge text-[11px] font-extrabold font-mono {{ $isSubShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                                {{ $isSubShown ? 'Visible' : 'Hidden' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- STANDALONE ROOT ITEM CARD -->
                        <div class="menu-node standalone-item bg-white border border-slate-200/90 rounded-[24px] p-4 shadow-xs flex items-center justify-between gap-3 hover:border-purple-300 hover:shadow-sm transition-all" 
                             data-id="{{ $nodeId }}" 
                             data-is-group="0"
                             data-name="{{ strtolower($item['name']) }}"
                             data-status="{{ $isShown ? 'visible' : 'hidden' }}">
                            
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-[#150A42] p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0" title="Drag to reorder">
                                    <i class="fa-solid fa-grip-vertical text-sm"></i>
                                </span>
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-[#150A42] border border-slate-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-bars text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 truncate node-title">{{ $item['name'] }}</h4>
                                    <span class="text-[11px] font-mono text-slate-400 block truncate">ID: {{ $nodeId }}</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2.5 shrink-0">
                                <!-- Move into group selector -->
                                <select onchange="moveIntoGroup(this)" class="move-into-group-select text-[11px] font-bold rounded-xl border border-slate-200 bg-[#F8F9FD] text-slate-700 px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 cursor-pointer">
                                    <option value="">Move into Group...</option>
                                    @foreach($currentTree as $grpCheck)
                                        @if(isset($grpCheck['sub_menus']))
                                            <option value="{{ $grpCheck['id'] }}">{{ $grpCheck['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <label class="relative inline-flex items-center cursor-pointer" title="Toggle visibility">
                                    <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }} onchange="onNodeToggle(this)">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                                <span class="status-badge text-[11px] font-extrabold font-mono {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $isShown ? 'Visible' : 'Hidden' }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- RIGHT COLUMN: Interactive Smart TV Live Mockup & Real Theme Preview (5 Cols Sticky) -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-6" id="tvMockupSection">

            <!-- TV Mockup Outer Card -->
            <div class="bg-white border border-slate-200/90 rounded-[28px] p-5 sm:p-6 shadow-sm space-y-4">
                
                <!-- Card Header with Theme Info & Mode Toggles -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-3 border-b border-slate-100">
                    <div class="space-y-0.5 min-w-0">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></div>
                            <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 uppercase font-mono tracking-wider truncate">
                                Live Guest TV Simulation
                            </h3>
                        </div>
                        <div class="text-[11px] text-slate-500 font-medium flex items-center space-x-1.5 truncate">
                            <span class="text-slate-400 shrink-0">Active Suite Theme:</span>
                            <span class="font-extrabold text-[#150A42] font-mono truncate">{{ $themeName }} (#{{ $selectedThemeId }})</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500 font-mono text-[10px]">v{{ $themeVersion }}</span>
                        </div>
                    </div>

                    <!-- Direct Preview & View Switcher -->
                    <div class="flex items-center space-x-2 shrink-0">
                        @if($themePreviewUrl)
                            <a href="{{ $themePreviewUrl }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-purple-50 text-[#150A42] hover:bg-purple-100 border border-purple-200 text-[11px] font-extrabold flex items-center space-x-1.5 transition-all shadow-2xs" title="Open full extracted theme in new tab">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-purple-600"></i>
                                <span>Preview Link</span>
                            </a>
                        @endif

                        <!-- Display View Switcher -->
                        <div class="inline-flex rounded-xl bg-slate-100 p-0.5 space-x-0.5 text-[10px] font-extrabold font-mono">
                            <button type="button" 
                                    onclick="setTvDisplayMode('theme')" 
                                    id="tvModeThemeBtn" 
                                    class="px-2.5 py-1 rounded-lg transition-all {{ $themePreviewUrl ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-400 cursor-not-allowed' }}" 
                                    {{ $themePreviewUrl ? '' : 'disabled' }}
                                    title="{{ $themePreviewUrl ? 'View real extracted theme app' : 'No extracted zip preview available' }}">
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
                        
                        <!-- VIEW 1: LIVE EXTRACTED THEME IFRAME (If available) -->
                        <div id="tvThemeIframeContainer" class="w-full h-full absolute inset-0 {{ $themePreviewUrl ? '' : 'hidden' }} bg-slate-950">
                            @if($themePreviewUrl)
                                <iframe id="tvThemeIframe" 
                                        src="{{ $themePreviewUrl }}" 
                                        class="w-full h-full border-0 pointer-events-auto" 
                                        allowfullscreen 
                                        loading="lazy"></iframe>
                            @endif
                        </div>

                        <!-- VIEW 2: DYNAMIC MENU TABS SIMULATION (Overlay / Fallback) -->
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

                <!-- Live Sync Advice Box -->
                <div class="p-3.5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 text-xs space-y-2">
                    <div class="flex items-start space-x-2.5">
                        <i class="fa-solid fa-bolt text-amber-500 text-sm mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                            <strong>Instant TV Broadcast:</strong> Saving updates this hotel's config in the cloud and dispatches a background push event to all paired Android TVs in rooms.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- STICKY BOTTOM SAVE & SYNC CONTROLS BAR -->
    <div class="bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-[28px] p-4 sm:p-5 shadow-xl flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 sticky bottom-5 z-40">
        <div class="flex items-center space-x-3">
            <div id="saveStatusIndicator" class="flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="saveStatusText">All Changes Synced</span>
            </div>
            <span class="text-xs text-slate-400 font-medium hidden md:inline">Target: <strong>{{ $hotel->hotel_name }}</strong> ({{ $connectedTvCount }} Displays)</span>
        </div>

        <div class="flex items-center space-x-2.5 justify-end">
            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs">
                Cancel
            </a>
            <button type="button" id="saveMenuBtn" class="px-7 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white text-xs font-extrabold shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-amber-300"></i>
                <span id="saveBtnText">Save & Sync to TVs</span>
            </button>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const itemCatalog = @json($itemCatalog);
    const saveUrl = "{{ url('/super-admin/hotels/' . $hotel->id . '/menus') }}";
    const resetUrl = "{{ route('super-admin.hotels.menus.reset', $hotel->id) }}";
    const csrfToken = "{{ csrf_token() }}";
    let isDirty = false;
    let tvDisplayMode = "{{ $themePreviewUrl ? 'theme' : 'nav' }}";

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
            text.textContent = 'Unsaved Changes (Ready to Broadcast)';
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
            badge.className = 'status-badge text-[11px] font-extrabold font-mono ' + (isChecked ? 'text-emerald-600' : 'text-slate-400');
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

        // Collect available group options
        let groupOptionsHtml = '<option value="">Move into Group...</option>';
        document.querySelectorAll('.group-card').forEach(g => {
            const gid = g.dataset.id;
            const gtitle = g.querySelector('.node-title')?.textContent || gid;
            groupOptionsHtml += `<option value="${gid}">${gtitle}</option>`;
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
                    <i class="fa-solid ${iconClass} text-sm"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 truncate node-title">${meta.name}</h4>
                    <span class="text-[11px] font-mono text-slate-400 block truncate">ID: ${id}</span>
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
                <span class="status-badge text-[11px] font-extrabold font-mono ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
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
                    <i class="fa-solid ${iconClass} text-xs"></i>
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs font-bold text-slate-800 truncate node-title">${meta.name}</h5>
                    <span class="text-[10px] font-mono text-slate-400 block truncate">ID: ${id}</span>
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
                <span class="status-badge text-[11px] font-extrabold font-mono ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
                    ${isChecked ? 'Visible' : 'Hidden'}
                </span>
            </div>
        `;

        standaloneCard.remove();
        targetDropzone.appendChild(newSub);

        markDirty();
        refreshStateAndTvMockup();
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
                    tabBtn.innerHTML = `
                        <i class="fa-solid ${icon} text-amber-300 text-[8px]"></i>
                        <span>${title}</span>
                        <i class="fa-solid fa-chevron-down text-[7px] text-white/60 ml-0.5"></i>
                    `;
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
                    tabBtn.innerHTML = `
                        <i class="fa-solid ${icon} text-amber-300 text-[8px]"></i>
                        <span>${title}</span>
                    `;
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

        initDropzones();
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
            grid.innerHTML = '<span class="text-[9px] text-white/50 col-span-2 italic">No active items in this group.</span>';
        } else {
            activeSubs.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'p-1.5 rounded-lg bg-white/10 border border-white/10 flex items-center space-x-1.5 text-white text-[9px] truncate';
                itemDiv.innerHTML = `
                    <i class="fa-solid ${item.icon} text-amber-300 text-[8px] shrink-0"></i>
                    <span class="truncate">${item.name}</span>
                `;
                grid.appendChild(itemDiv);
            });
        }

        popup.classList.remove('hidden');
    }

    function closeTvSubmenu() {
        const popup = document.getElementById('tvSubMenuPopup');
        if (popup) popup.classList.add('hidden');
    }

    // Build JSON Hierarchy matching backend expectation
    function buildMenuHierarchy() {
        const tree = [];
        const rootNodes = rootEl.children;

        for (let node of rootNodes) {
            if (!node.classList.contains('menu-node')) continue;
            const id = node.dataset.id;
            const meta = itemCatalog[id] || { name: id, icon: 'assets/images/icons/hotelinfo.png' };
            const statusCheckbox = node.querySelector(':scope > div .node-status-checkbox') || node.querySelector('.node-status-checkbox');
            const isShown = statusCheckbox ? statusCheckbox.checked : true;

            const isGroup = node.classList.contains('group-card');
            const itemObj = {
                id: id,
                name: meta.name || id,
                icon: meta.icon || 'assets/images/icons/hotelinfo.png',
                status: isShown ? 'show' : 'hide'
            };

            if (isGroup) {
                const dropzone = node.querySelector('.sub-menu-dropzone');
                const subMenus = [];
                if (dropzone) {
                    for (let subNode of dropzone.children) {
                        if (!subNode.classList.contains('menu-node')) continue;
                        const subId = subNode.dataset.id;
                        const subMeta = itemCatalog[subId] || { name: subId, icon: 'assets/images/icons/hotelinfo.png' };
                        const subCheck = subNode.querySelector('.node-status-checkbox');
                        subMenus.push({
                            id: subId,
                            name: subMeta.name || subId,
                            icon: subMeta.icon || 'assets/images/icons/hotelinfo.png',
                            status: (subCheck && subCheck.checked) ? 'show' : 'hide'
                        });
                    }
                }
                itemObj.sub_menus = subMenus;
            }

            tree.push(itemObj);
        }

        return tree;
    }

    // Filter menus in the list
    let currentStatusFilter = 'all';
    function setStatusFilter(filter) {
        currentStatusFilter = filter;
        ['all', 'visible', 'hidden'].forEach(f => {
            const btn = document.getElementById('filterBtn' + f.charAt(0).toUpperCase() + f.slice(1));
            if (!btn) return;
            if (f === filter) {
                btn.className = 'px-3 py-1 text-xs font-bold rounded-lg bg-white text-slate-800 shadow-2xs transition-all';
            } else {
                btn.className = 'px-3 py-1 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all';
            }
        });
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

    function scrollToTvPreview() {
        const sec = document.getElementById('tvMockupSection');
        if (sec) sec.scrollIntoView({ behavior: 'smooth' });
    }

    // Save Menus to server
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
                    badge.className = 'flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold font-mono';
                    badge.querySelector('span:first-child').className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
                    text.textContent = 'All Changes Synced & Broadcasted';
                }

                Swal.fire({
                    icon: 'success',
                    title: 'TV Menus Synced & Broadcasted!',
                    text: data.message || 'Config successfully sent to connected TVs in real time.',
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: data.message || 'Something went wrong.'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            document.getElementById('saveBtnText').textContent = originalText;
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Could not connect to the menu sync service.'
            });
        });
    });

    // Reset to Default
    document.getElementById('resetDefaultBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset TV Menus to Default?',
            text: 'This will restore the factory menu ordering, groups, and all standard launcher icons for {{ $hotel->hotel_name }}.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Restore Defaults'
        }).then((result) => {
            if (result.isConfirmed) {
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Factory Menus Restored',
                            text: data.message,
                            timer: 1800,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });

    // Initial render of TV mockup
    refreshStateAndTvMockup();
</script>
@endsection
