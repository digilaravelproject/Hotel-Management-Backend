@extends('layouts.hotel_admin')

@section('title', 'Global OTT Apps Control - Hotel Admin')
@section('page_title', 'Global OTT Apps Control')

@section('content')
@php
    $totalPlatforms = count($availablePlatforms);
    $activeCount = count(array_intersect($currentGlobalSettings, array_column($availablePlatforms, 'package')));
@endphp

<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span>TV Experience</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">OTT Apps Control</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Global OTT Apps Control</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configure which streaming applications appear on guest Smart TVs across all hotel rooms by default.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.devices.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-tv text-xs text-purple-600"></i>
                <span>Connected TVs</span>
            </a>
            <button type="button" 
                    onclick="document.getElementById('saveGlobalOttBtn').click()" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    <!-- Quick Overview & Status Hero Banner (PaxTV Luxury Midnight Theme) -->
    <div class="bg-white border border-slate-100 rounded-[22px] sm:rounded-[28px] p-5 sm:p-7 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-start sm:items-center space-x-3.5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-2xs shrink-0">
                <i class="fa-brands fa-google-play text-purple-700"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl bg-purple-50 text-[#150A42] font-black text-xs border border-purple-200/60 shadow-2xs">
                        <i class="fa-solid fa-globe mr-1.5 text-purple-600 text-[10px]"></i>
                        Property-Wide Default
                    </span>
                    <span class="text-xs font-bold text-slate-500">Global Sync</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Changes made here automatically apply to all in-room Smart TVs unless an individual room has custom overrides.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 relative z-10 shrink-0">
            <div class="px-3.5 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700 shadow-2xs flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Active Apps:</span>
                <span id="selectedCounterPill" class="font-black text-purple-700">{{ $activeCount }}</span>
                <span class="text-slate-400">/ {{ $totalPlatforms }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Container: Filter Controls & OTT Cards Grid -->
    <div class="bg-white border border-slate-100 rounded-[24px] sm:rounded-[28px] p-5 sm:p-8 shadow-sm space-y-6">

        <!-- Controls Bar: Search & Select All -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-b border-slate-100 pb-5">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       id="ottSearchInput" 
                       placeholder="Filter streaming apps (e.g. Netflix, Prime, YouTube)..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all placeholder:text-slate-400">
            </div>

            @if($totalPlatforms > 0)
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" 
                            id="toggleSelectAllBtn" 
                            onclick="toggleSelectAll()" 
                            class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100/90 text-[#150A42] border border-purple-200/80 font-black text-xs transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer active:scale-95">
                        <i class="fa-solid fa-check-double text-[11px] text-purple-600"></i>
                        <span id="toggleSelectAllText">{{ $activeCount === $totalPlatforms ? 'Deselect All' : 'Select All' }}</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- OTT Toggle Form -->
        <form id="globalOttForm" action="{{ route('hotel.ott-apps') }}" method="POST" class="space-y-6">
            @csrf

            @if($totalPlatforms > 0)
                <!-- Grid of OTT Cards with Real Official Logos & Toggle Switches (Thin Borders, 2 Columns on Mobile) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-4" id="ottPlatformsGrid">
                    @foreach($availablePlatforms as $ott)
                        @php
                            $package = $ott['package'] ?? '';
                            $name = $ott['name'] ?? '';
                            $iconSrc = $ott['icon'] ?? null;
                            $isSelected = in_array($package, $currentGlobalSettings);
                        @endphp

                        <div class="ott-card group relative p-3 sm:p-4 bg-white border rounded-2xl flex flex-col justify-between cursor-pointer hover:border-purple-300 hover:shadow-md transition-all duration-200 select-none {{ $isSelected ? 'border-purple-300/90 bg-gradient-to-b from-white to-purple-50/20 ring-1 ring-purple-400/20 is-selected' : 'border-slate-200/90' }}"
                             data-name="{{ strtolower($name) }}"
                             data-package="{{ strtolower($package) }}"
                             onclick="handleCardClick(this, event)">
                            
                            <!-- Card Header: App Icon & iOS Toggle Switch -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <!-- Real Official Icon Image -->
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl border border-slate-100 bg-white p-1 sm:p-1.5 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center group-hover:scale-105 group-hover:border-purple-300 transition-all">
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

                                <!-- Interactive Luxury Toggle Switch -->
                                <div class="shrink-0" onclick="event.stopPropagation()">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="ott_platforms[]" 
                                               value="{{ $package }}" 
                                               class="sr-only peer ott-checkbox" 
                                               {{ $isSelected ? 'checked' : '' }}
                                               onchange="handleCheckboxChange(this)">
                                        <div class="w-10 h-5.5 sm:w-11 sm:h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 sm:after:h-5 sm:after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- App Info -->
                            <div class="min-w-0 space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-black text-slate-800 group-hover:text-[#150A42] transition-colors leading-tight truncate" title="{{ $name }}">
                                    {{ $name }}
                                </h4>
                                <span class="text-[9px] sm:text-[10px] font-mono text-slate-400 block truncate" title="{{ $package }}">
                                    {{ $package }}
                                </span>
                            </div>

                            <!-- Status Pill Footer -->
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                                <span class="ott-status-text {{ $isSelected ? 'text-emerald-600' : 'text-slate-400' }}">
                                    <i class="fa-solid fa-circle text-[7px] mr-1 {{ $isSelected ? 'text-emerald-500' : 'text-slate-300' }}"></i>
                                    <span>{{ $isSelected ? 'Visible on TV' : 'Disabled' }}</span>
                                </span>
                                <span class="text-[9px] font-mono {{ $isSelected ? 'text-purple-600' : 'text-slate-300' }}">
                                    {{ $isSelected ? 'ENABLED' : 'OFF' }}
                                </span>
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
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">
                    Cancel
                </a>
                <button type="submit" 
                        id="saveGlobalOttBtn"
                        {{ $totalPlatforms === 0 ? 'disabled' : '' }} 
                        class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                    <span>Save Global OTT Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // SweetAlert Toast configuration
    const Toast = typeof Swal !== 'undefined' ? Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    }) : null;

    // Card click handler: toggle checkbox when user clicks anywhere on the card
    function handleCardClick(card, event) {
        // If clicked on input or label, let the native change handler handle it
        if (event.target.tagName.toLowerCase() === 'input' || event.target.closest('label')) {
            return;
        }
        const checkbox = card.querySelector('.ott-checkbox');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            handleCheckboxChange(checkbox);
        }
    }

    // Checkbox change handler: update card styles, status pill, and counters
    function handleCheckboxChange(checkbox) {
        const card = checkbox.closest('.ott-card');
        if (!card) return;

        const isChecked = checkbox.checked;
        const statusText = card.querySelector('.ott-status-text');
        const statusMode = card.querySelector('.ott-status-text + span');

        if (isChecked) {
            card.classList.add('border-purple-300/90', 'bg-gradient-to-b', 'from-white', 'to-purple-50/20', 'ring-1', 'ring-purple-400/20', 'is-selected');
            card.classList.remove('border-slate-200/90');
            if (statusText) {
                statusText.className = 'ott-status-text text-emerald-600';
                statusText.innerHTML = '<i class="fa-solid fa-circle text-[7px] mr-1 text-emerald-500"></i><span>Visible on TV</span>';
            }
            if (statusMode) {
                statusMode.className = 'text-[9px] font-mono text-purple-600';
                statusMode.textContent = 'ENABLED';
            }
        } else {
            card.classList.remove('border-purple-300/90', 'bg-gradient-to-b', 'from-white', 'to-purple-50/20', 'ring-1', 'ring-purple-400/20', 'is-selected');
            card.classList.add('border-slate-200/90');
            if (statusText) {
                statusText.className = 'ott-status-text text-slate-400';
                statusText.innerHTML = '<i class="fa-solid fa-circle text-[7px] mr-1 text-slate-300"></i><span>Disabled</span>';
            }
            if (statusMode) {
                statusMode.className = 'text-[9px] font-mono text-slate-300';
                statusMode.textContent = 'OFF';
            }
        }

        updateCounters();
    }

    // Update active count and Select All button text
    function updateCounters() {
        const allCheckboxes = document.querySelectorAll('.ott-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.ott-checkbox:checked');
        const counterPill = document.getElementById('selectedCounterPill');
        if (counterPill) {
            counterPill.textContent = checkedCheckboxes.length;
        }

        const toggleBtnText = document.getElementById('toggleSelectAllText');
        if (toggleBtnText) {
            if (checkedCheckboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
                toggleBtnText.textContent = 'Deselect All';
            } else {
                toggleBtnText.textContent = 'Select All';
            }
        }
    }

    // Toggle Select All / Deselect All
    function toggleSelectAll() {
        const allCheckboxes = document.querySelectorAll('.ott-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.ott-checkbox:checked');
        const shouldSelectAll = checkedCheckboxes.length < allCheckboxes.length;

        allCheckboxes.forEach(cb => {
            cb.checked = shouldSelectAll;
            handleCheckboxChange(cb);
        });

        if (Toast) {
            Toast.fire({
                icon: 'info',
                title: shouldSelectAll ? 'All platforms selected' : 'All platforms deselected'
            });
        }
    }

    // Client-side live search filter
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('ottSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
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
        }

        // AJAX Form Submission for Smooth Real-Time Updates
        const form = document.getElementById('globalOttForm');
        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const saveBtn = document.getElementById('saveGlobalOttBtn');
                saveBtn.disabled = true;
                const originalHtml = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-xs"></i><span>Saving Global Settings...</span>';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (response.ok && (result.status === 'success' || result.success)) {
                        if (Toast) {
                            Toast.fire({
                                icon: 'success',
                                title: result.message || 'Global OTT Settings updated & synced to TVs!'
                            });
                        }
                    } else {
                        if (Toast) {
                            Toast.fire({
                                icon: 'error',
                                title: result.message || 'Failed to update settings.'
                            });
                        } else {
                            alert(result.message || 'Failed to update settings.');
                        }
                    }
                } catch (error) {
                    console.error('Save error:', error);
                    // Fallback to normal submit if network / CORS error
                    form.submit();
                    return;
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHtml;
                }
            });
        }

        updateCounters();
    });
</script>
@endsection