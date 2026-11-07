@extends('layouts.hotel_admin')

@section('title', 'Room ' . $device->room_no . ' OTT Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' OTT Configuration')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.devices.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Connected TVs</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Room {{ $device->room_no }} OTT</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Room {{ $device->room_no }} Streaming Apps</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Control which OTT streaming apps appear on the Smart TV launcher for Room {{ $device->room_no }}.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if($hasOverride)
                <form action="{{ route('hotel.devices.ott.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default OTT settings?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-2xl border border-amber-200 text-amber-800 bg-amber-50 hover:bg-amber-100 font-black text-xs transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer active:scale-95">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset to Default</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('hotel.devices.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Connected TVs</span>
            </a>
        </div>
    </div>

    <!-- Hero Room Specs & Status Banner (PaxTV Luxury Midnight Theme) -->
    <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[28px] p-5 sm:p-7 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-start sm:items-center space-x-3.5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-2xs shrink-0">
                <i class="fa-brands fa-google-play text-purple-700"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl bg-purple-50 text-[#150A42] font-black text-xs border border-purple-200/60 shadow-2xs">
                        <i class="fa-solid fa-door-closed mr-1.5 text-purple-600 text-[10px]"></i>
                        Room {{ $device->room_no }}
                    </span>
                    @if($device->brand || $device->model)
                        <span class="text-xs font-bold text-slate-600">{{ $device->brand }} {{ $device->model }}</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 font-mono">
                    ID: {{ $device->device_id }} @if($device->mac_address)• MAC: {{ $device->mac_address }}@endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 relative z-10 shrink-0">
            @if($hasOverride)
                <span class="inline-flex items-center px-3 py-1.5 rounded-2xl bg-amber-50 text-amber-800 text-xs font-black border border-amber-200 shadow-2xs">
                    <i class="fa-solid fa-sliders mr-1.5 text-xs text-amber-600"></i> Custom Room Override Active
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-2xl bg-purple-50 text-[#150A42] text-xs font-black border border-purple-200/80 shadow-2xs">
                    <i class="fa-solid fa-globe mr-1.5 text-xs text-purple-600"></i> Inheriting Hotel Global Default
                </span>
            @endif

            <div class="px-3 py-1.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                <span id="selectedCounterPill" class="font-black text-purple-700">{{ count(array_intersect($currentDeviceSettings, array_column($availablePlatforms, 'package'))) }}</span> / {{ count($availablePlatforms) }} Apps Active
            </div>
        </div>
    </div>

    <!-- Main OTT Selection Container -->
    <div class="bg-white border border-slate-100 rounded-[24px] sm:rounded-[28px] p-5 sm:p-8 shadow-sm space-y-6">
        
        <!-- Controls Bar: Search & Select All -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-slate-100 pb-5">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       id="ottSearchInput" 
                       placeholder="Filter streaming apps (e.g. Netflix, YouTube)..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-400">
            </div>

            @if(count($availablePlatforms) > 0)
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" 
                            id="toggleSelectAllBtn" 
                            onclick="toggleSelectAll()" 
                            class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100/90 text-[#150A42] border border-purple-200/80 font-black text-xs transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer active:scale-95">
                        <i class="fa-solid fa-check-double text-[11px] text-purple-600"></i>
                        <span id="toggleSelectAllText">Select All</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Form for submitting overrides -->
        <form id="roomOttForm" action="{{ url('/hotel/devices/' . $device->id . '/ott') }}" method="POST" class="space-y-6">
            @csrf

            @if(count($availablePlatforms) > 0)
                <!-- Grid of OTT Cards with Real Official Logos (Thin Elegant Borders) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-4" id="ottPlatformsGrid">
                    @foreach($availablePlatforms as $ott)
                        @php
                            $package = $ott['package'] ?? '';
                            $name = $ott['name'] ?? '';
                            $iconSrc = $ott['icon'] ?? null;
                            $isSelected = in_array($package, $currentDeviceSettings);
                        @endphp
                        <div class="ott-card group relative p-3 sm:p-4 bg-white border rounded-2xl flex items-center space-x-2.5 sm:space-x-3.5 cursor-pointer hover:border-purple-300 hover:shadow-md transition-all duration-200 select-none {{ $isSelected ? 'border-purple-400 bg-purple-50/25 ring-1 ring-purple-400/20 shadow-xs is-selected' : 'border-slate-200/90' }}"
                             data-name="{{ strtolower($name) }}"
                             data-package="{{ strtolower($package) }}"
                             onclick="toggleOttCard(this)">
                            
                            <!-- Hidden Checkbox for Form Post -->
                            <input type="checkbox" 
                                   name="ott_platforms[]" 
                                   value="{{ $package }}" 
                                   class="ott-checkbox sr-only" 
                                   {{ $isSelected ? 'checked' : '' }}
                                   onchange="handleOttCheckboxChange(this)">

                            <!-- Real Official Icon Image -->
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl border border-slate-100 bg-white p-1 sm:p-1.5 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center group-hover:scale-105 group-hover:border-purple-300 transition-all">
                                @if(!empty($iconSrc))
                                    <img src="{{ $iconSrc }}" 
                                         alt="{{ $name }}" 
                                         class="w-full h-full object-contain"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden w-full h-full bg-[#150A42] rounded-xl flex items-center justify-center text-white text-[10px] sm:text-xs">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-[#150A42] to-purple-600 rounded-xl flex items-center justify-center text-white text-xs sm:text-sm">
                                        <i class="fa-solid fa-play text-amber-300"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Platform Details -->
                            <div class="min-w-0 flex-1 pr-6 sm:pr-7">
                                <h4 class="text-xs sm:text-sm font-black text-slate-800 group-hover:text-[#150A42] transition-colors leading-tight truncate" title="{{ $name }}">
                                    {{ $name }}
                                </h4>
                                <span class="text-[9px] sm:text-[10px] font-mono text-slate-400 block mt-0.5 truncate" title="{{ $package }}">
                                    {{ $package }}
                                </span>
                            </div>

                            <!-- Top-Right Checkbox Badge Indicator (Thin, sleek) -->
                            <div class="absolute top-3 sm:top-3.5 right-3 sm:right-3.5 shrink-0 pointer-events-none">
                                <div class="ott-check-indicator w-5 h-5 rounded-full flex items-center justify-center transition-all {{ $isSelected ? 'bg-[#150A42] text-white shadow-2xs' : 'border border-slate-300 bg-slate-50 group-hover:border-purple-400' }}">
                                    <i class="fa-solid fa-check text-[9px] {{ $isSelected ? 'block' : 'hidden' }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-xs text-slate-400 font-bold bg-[#F8F9FD] rounded-2xl border border-dashed border-slate-200 space-y-2">
                    <i class="fa-solid fa-tv text-3xl text-slate-300 block mb-1"></i>
                    <p class="text-slate-700 font-black">No OTT platforms available in your current Subscription Plan.</p>
                    <p class="text-[11px] text-slate-400">Please upgrade plan or contact support to unlock streaming apps.</p>
                </div>
            @endif

            <!-- Submit Controls Bar -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.devices.index') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">
                    Cancel
                </a>
                <button type="submit" 
                        id="saveRoomOttBtn"
                        {{ count($availablePlatforms) === 0 ? 'disabled' : '' }} 
                        class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                    <span>Save Room {{ $device->room_no }} OTT Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleOttCard(cardElement) {
        const checkbox = cardElement.querySelector('.ott-checkbox');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateCardVisual(cardElement, checkbox.checked);
            updateCounters();
        }
    }

    function handleOttCheckboxChange(checkbox) {
        const cardElement = checkbox.closest('.ott-card');
        if (cardElement) {
            updateCardVisual(cardElement, checkbox.checked);
            updateCounters();
        }
    }

    function updateCardVisual(cardElement, isChecked) {
        const indicator = cardElement.querySelector('.ott-check-indicator');
        const checkIcon = indicator ? indicator.querySelector('i') : null;

        if (isChecked) {
            cardElement.classList.add('border-purple-400', 'bg-purple-50/25', 'ring-1', 'ring-purple-400/20', 'shadow-xs', 'is-selected');
            cardElement.classList.remove('border-slate-200/90', 'border-slate-200');
            if (indicator) {
                indicator.className = 'ott-check-indicator w-5 h-5 rounded-full flex items-center justify-center transition-all bg-[#150A42] text-white shadow-2xs';
            }
            if (checkIcon) checkIcon.classList.remove('hidden');
        } else {
            cardElement.classList.remove('border-purple-400', 'bg-purple-50/25', 'ring-1', 'ring-purple-400/20', 'shadow-xs', 'is-selected');
            cardElement.classList.add('border-slate-200/90');
            if (indicator) {
                indicator.className = 'ott-check-indicator w-5 h-5 rounded-full border border-slate-300 bg-slate-50 flex items-center justify-center transition-all group-hover:border-purple-400';
            }
            if (checkIcon) checkIcon.classList.add('hidden');
        }
    }

    function updateCounters() {
        const allCheckboxes = document.querySelectorAll('.ott-checkbox');
        const checkedCount = document.querySelectorAll('.ott-checkbox:checked').length;
        const total = allCheckboxes.length;

        const counterPill = document.getElementById('selectedCounterPill');
        if (counterPill) counterPill.innerText = checkedCount;

        const toggleBtnText = document.getElementById('toggleSelectAllText');
        if (toggleBtnText) {
            toggleBtnText.innerText = (total > 0 && checkedCount === total) ? 'Deselect All' : 'Select All';
        }
    }

    function toggleSelectAll() {
        const allCheckboxes = document.querySelectorAll('.ott-checkbox');
        const checkedCount = document.querySelectorAll('.ott-checkbox:checked').length;
        const shouldSelect = (checkedCount < allCheckboxes.length);

        allCheckboxes.forEach(cb => {
            cb.checked = shouldSelect;
            const card = cb.closest('.ott-card');
            if (card) updateCardVisual(card, shouldSelect);
        });

        updateCounters();
    }

    // Live search filter
    document.getElementById('ottSearchInput')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.ott-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const pkg = card.getAttribute('data-package') || '';
            if (name.includes(query) || pkg.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', updateCounters);
</script>
@endsection
