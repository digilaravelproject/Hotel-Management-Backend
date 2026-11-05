@extends('layouts.super_admin')

@section('title', 'Hotel Vendors Directory - Super Admin')
@section('page_title', 'Hotel Vendors Directory')

@section('styles')
<style>
    /* Status dropdown color styles */
    .approval-select-approved {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }
    .approval-select-pending {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fde68a !important;
    }
    .approval-select-disapproved {
        background-color: #fff1f2 !important;
        color: #be123c !important;
        border-color: #fecdd3 !important;
    }

    /* Filter Pills Sleek Modern Styling & Hover Fix */
    .filter-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.85rem;
        border-radius: 0.85rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .filter-pill:hover:not(.active) {
        background-color: #EDE9FE !important;
        color: #150A42 !important;
        border-color: #DDD6FE !important;
        transform: translateY(-1px);
    }
    .filter-pill.active {
        background-color: #150A42 !important;
        color: #FFFFFF !important;
        font-weight: 800 !important;
        box-shadow: 0 4px 14px rgba(21, 10, 66, 0.25) !important;
        border-color: #150A42 !important;
    }
    .filter-pill.active:hover {
        background-color: #201063 !important;
        color: #FFFFFF !important;
        box-shadow: 0 6px 18px rgba(21, 10, 66, 0.35) !important;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Primary Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Registered Hotels Directory</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage hotel clients, pairing licenses, channel partners, and live streaming status.</p>
        </div>
        <a href="{{ route('super-admin.hotels.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Hotel Vendor</span>
        </a>
    </div>

    @php
        $totalHotelsCount = $hotels->count();
        $activeHotelsCount = $hotels->where('status', true)->count();
        $pendingHotelsCount = $hotels->where('approval_status', 'pending')->count();
        $paidActiveHotelsCount = $hotels->filter(fn($h) => in_array($h->subscription_status, ['active', 'expiring_soon']))->count();
        $expiringSoonHotelsCount = $hotels->filter(fn($h) => $h->subscription_status === 'expiring_soon')->count();
        $expiredHotelsCount = $hotels->filter(fn($h) => $h->subscription_status === 'expired')->count();
        $unpaidHotelsCount = $hotels->filter(fn($h) => in_array($h->subscription_status, ['unpaid', 'pending']))->count();

        // Build Distributor Filter Options
        $distributorOptions = ['' => 'All Distributors (Channel Partners)'];
        $distributorOptions['direct'] = 'Direct Pax TV (No Distributor)';
        if (isset($distributors)) {
            foreach ($distributors as $dist) {
                $countForDist = $hotels->where('distributor_id', $dist->id)->count();
                $distributorOptions[(string)$dist->id] = $dist->name . ($countForDist > 0 ? " ({$countForDist} hotels)" : '');
            }
        }
    @endphp

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- Total Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight" id="statTotalHotels">{{ $totalHotelsCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Total Hotels</div>
            </div>
        </div>

        <!-- Active Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight leading-tight">{{ $activeHotelsCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Active Hotels</div>
            </div>
        </div>

        <!-- Paid / Active Subscriptions -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $paidActiveHotelsCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Paid & Active</div>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight leading-tight">{{ $expiringSoonHotelsCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Expiring Soon</div>
            </div>
        </div>

        <!-- Expired Plans -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all col-span-2 md:col-span-1">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold {{ $expiredHotelsCount > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight leading-tight">{{ $expiredHotelsCount }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Expired Plans</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 sm:p-4 shadow-xs space-y-3.5">
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 min-w-0">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-0">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="hotelSearchInput" placeholder="Search by hotel name, owner, city, email, phone, or license key..." class="w-full pl-10 pr-9 py-2.5 text-xs sm:text-sm rounded-2xl border border-slate-200 bg-[#F3F5FA] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] placeholder:text-slate-400 text-slate-800 font-medium transition-all shadow-inner">
                <button type="button" id="clearSearchBtn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <!-- Distributor Channel Partner Dropdown Filter -->
            <div class="w-full md:w-80 shrink-0">
                <x-dropdown 
                    id="filter_distributor_id" 
                    name="filter_distributor_id" 
                    label="All Distributors" 
                    :options="$distributorOptions" 
                    :selected="request('distributor_id', '')" 
                    onchange="onDistributorFilterChange(this.value)" 
                    wrapClass="w-full" 
                    selectClass="py-2.5 px-3 text-xs font-bold" 
                    theme="sa" 
                />
            </div>
        </div>

        <!-- Filter Pills (Wraps gracefully on all screens, never cut off) -->
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 pt-3 border-t border-slate-100">
            <button type="button" class="filter-pill active" data-filter="all">
                <span>All</span>
                <span class="ml-1 opacity-80 font-mono">({{ $totalHotelsCount }})</span>
            </button>
            <button type="button" class="filter-pill" data-filter="active">
                <span>Live</span>
                <span class="ml-1 opacity-70 font-mono">({{ $activeHotelsCount }})</span>
            </button>
            <button type="button" class="filter-pill" data-filter="paid">
                <span>Paid</span>
                <span class="ml-1 opacity-70 font-mono">({{ $paidActiveHotelsCount }})</span>
            </button>
            @if($expiringSoonHotelsCount > 0)
            <button type="button" class="filter-pill text-amber-700 bg-amber-50/60 border-amber-200" data-filter="expiring">
                <span>Expiring Soon</span>
                <span class="ml-1 opacity-80 font-mono">({{ $expiringSoonHotelsCount }})</span>
            </button>
            @endif
            <button type="button" class="filter-pill {{ $expiredHotelsCount > 0 ? 'text-rose-700 bg-rose-50/60 border-rose-200' : '' }}" data-filter="expired">
                <span>Expired</span>
                <span class="ml-1 opacity-80 font-mono">({{ $expiredHotelsCount }})</span>
            </button>
            <button type="button" class="filter-pill" data-filter="unpaid">
                <span>Unpaid</span>
                <span class="ml-1 opacity-70 font-mono">({{ $unpaidHotelsCount }})</span>
            </button>
            <button type="button" class="filter-pill" data-filter="pending">
                <span>Pending Approval</span>
                <span class="ml-1 opacity-70 font-mono">({{ $pendingHotelsCount }})</span>
            </button>
        </div>
    </div>

    <!-- Empty State for 0 total hotels in system -->
    @if($hotels->isEmpty())
        <div class="bg-white border border-slate-200/80 rounded-3xl p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-[#150A42] mx-auto flex items-center justify-center text-2xl mb-4 border border-purple-100 shadow-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 mb-1">No Hotel Vendors Registered</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">Get started by creating your first hotel client. You can assign TV limit plans, logos, and distributor channels.</p>
            <a href="{{ route('super-admin.hotels.create') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add First Hotel Vendor</span>
            </a>
        </div>
    @else
        <!-- DESKTOP / TABLET VIEW: High-density responsive table with 3-dot Action Menu -->
        <div class="hidden lg:block bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-x-auto">
            <div class="min-w-[1080px]">
                <table class="w-full text-left text-xs text-slate-800 border-separate border-spacing-0">
                    <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3.5 min-w-[240px]">Hotel Client</th>
                            <th class="px-4 py-3.5 min-w-[180px]">Owner & Contact</th>
                            <th class="px-3.5 py-3.5 min-w-[160px]">Plan & TVs</th>
                            <th class="hidden xl:table-cell px-3.5 py-3.5 min-w-[120px]">License Key</th>
                            <th class="px-3 py-3.5 text-center min-w-[120px]">Subscription</th>
                            <th class="px-3 py-3.5 text-center min-w-[85px]">Status</th>
                            <th class="px-3 py-3.5 text-center min-w-[130px]">Approval</th>
                            <th class="px-3 py-3.5 text-center w-14">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="desktopHotelTableBody">
                        @foreach($hotels as $hotel)
                            @php
                                $hotelSearchText = strtolower($hotel->hotel_name . ' ' . $hotel->owner_name . ' ' . $hotel->email . ' ' . $hotel->phone . ' ' . ($hotel->city ?? $hotel->hotel_location) . ' ' . ($hotel->license_key ?? '') . ' ' . ($hotel->distributor ? $hotel->distributor->name : ''));
                                $deviceCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
                                $subBadge = $hotel->subscription_badge;
                            @endphp
                            <tr id="hotel-desktop-row-{{ $hotel->id }}" 
                                class="hotel-item hotel-desktop-row hover:bg-purple-50/20 transition-colors"
                                data-search="{{ $hotelSearchText }}"
                                data-status="{{ $hotel->status ? 'active' : 'inactive' }}"
                                data-approval="{{ $hotel->approval_status }}"
                                data-payment="{{ $hotel->payment_status }}"
                                data-subscription="{{ $subBadge['key'] }}"
                                data-distributor="{{ $hotel->distributor_id ?: 'direct' }}">
                                <!-- 1. Hotel Client -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                            @if($hotel->hotel_logo)
                                                <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-hotel text-slate-400 text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-[280px]">
                                            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-extrabold text-slate-900 text-xs hover:text-[#150A42] transition-colors truncate block" title="{{ $hotel->hotel_name }}">
                                                {{ $hotel->hotel_name }}
                                            </a>
                                            <div class="text-[10px] text-slate-400 font-medium truncate flex items-center mt-0.5">
                                                <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[9px]"></i>
                                                <span class="truncate">{{ $hotel->city ?? $hotel->hotel_location }}</span>
                                            </div>
                                            @if($hotel->distributor)
                                                <span class="inline-flex items-center text-[9px] font-semibold text-[#150A42] bg-purple-50 border border-purple-100 px-1.5 py-0.5 rounded mt-0.5 truncate max-w-[160px]" title="Distributor: {{ $hotel->distributor->name }}">
                                                    <i class="fa-solid fa-handshake mr-1 text-[8px]"></i> {{ Str::limit($hotel->distributor->name, 16) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- 2. Owner & Contact -->
                                <td class="px-4 py-3.5 space-y-0.5">
                                    <div class="font-bold text-slate-800 text-xs truncate max-w-[180px]">{{ $hotel->owner_name }}</div>
                                    <div class="hidden xl:block text-[10px] text-slate-500 font-mono truncate max-w-[180px]">
                                        <a href="mailto:{{ $hotel->email }}" class="hover:text-[#150A42] transition-colors" title="{{ $hotel->email }}">{{ $hotel->email }}</a>
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-mono">
                                        <a href="tel:{{ $hotel->phone }}" class="hover:text-[#150A42] transition-colors flex items-center">
                                            <i class="fa-solid fa-phone mr-1 text-[8px] text-slate-400"></i><span>{{ $hotel->phone }}</span>
                                        </a>
                                    </div>
                                </td>

                                <!-- 3. Plan & Capacity -->
                                <td class="px-3.5 py-3.5">
                                    <div class="font-extrabold text-slate-900 text-xs whitespace-nowrap">{{ $hotel->room_count }} Rms</div>
                                    <div class="flex items-center space-x-1 mt-0.5">
                                        @if($hotel->plan)
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-[#150A42] border border-purple-200/80 font-mono whitespace-nowrap" title="{{ $hotel->plan->name }}">
                                                {{ $hotel->plan->name }}
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 font-mono whitespace-nowrap">
                                                Trial
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 flex items-center whitespace-nowrap font-mono">
                                        <i class="fa-solid fa-tv text-[8px] mr-1 text-slate-400"></i>{{ $deviceCount }} paired
                                    </div>
                                    @if($hotel->license_key)
                                        <div class="xl:hidden mt-1 inline-flex items-center space-x-1 px-1.5 py-0.5 rounded bg-slate-100/90 border border-slate-200 text-slate-700 font-mono text-[9px]">
                                            <span class="truncate max-w-[70px]">{{ $hotel->license_key }}</span>
                                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="text-slate-400 hover:text-[#150A42] p-0.5" title="Copy Key">
                                                <i class="fa-regular fa-copy text-[8px]"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>

                                <!-- 4. License Key (Desktop only) -->
                                <td class="hidden xl:table-cell px-3.5 py-3.5">
                                    @if($hotel->license_key)
                                        <div class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-lg bg-slate-100/90 border border-slate-200 text-slate-800 font-mono text-[10px]">
                                            <span class="tracking-tight select-all truncate max-w-[110px]">{{ $hotel->license_key }}</span>
                                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="text-slate-400 hover:text-[#150A42] transition-colors p-0.5" title="Copy Key">
                                                <i class="fa-regular fa-copy text-[10px]"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">N/A</span>
                                    @endif
                                </td>

                                <!-- 5. Subscription & Expiry Status -->
                                <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full {{ $subBadge['badge_class'] }} border font-bold text-[9px] uppercase tracking-wider font-mono shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $subBadge['dot_class'] }} mr-1.5 {{ $subBadge['key'] === 'expiring_soon' ? 'animate-pulse' : '' }}"></span>
                                            {{ $subBadge['label'] }}
                                        </span>
                                        @if($hotel->expiry_date)
                                            <span class="text-[9px] font-mono mt-0.5 {{ $subBadge['key'] === 'expired' ? 'text-rose-600 font-bold' : ($subBadge['key'] === 'expiring_soon' ? 'text-amber-600 font-bold' : 'text-slate-400 font-medium') }}" title="Expiry: {{ $hotel->expiry_date->format('d M Y, h:i A') }}">
                                                @if($subBadge['key'] === 'expired')
                                                    Exp: {{ $hotel->expiry_date->format('d M Y') }}
                                                @elseif($subBadge['key'] === 'expiring_soon')
                                                    {{ $subBadge['subtext'] }}
                                                @else
                                                    Exp: {{ $hotel->expiry_date->format('d M Y') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-[9px] font-mono text-slate-400 mt-0.5">Perpetual</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- 6. Live Status Toggle -->
                                <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle active status">
                                        <input type="checkbox" onchange="toggleHotelStatus({{ $hotel->id }}, this)" {{ $hotel->status ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                    </label>
                                </td>

                                <!-- 7. Approval Status -->
                                <td class="px-3 py-3.5 text-center">
                                    <x-dropdown id="approval_status_desktop_{{ $hotel->id }}" name="approval_status_{{ $hotel->id }}" label="Approval" :options="['approved'=>'Approved','pending'=>'Pending','disapproved'=>'Disapproved']" :selected="$hotel->approval_status" onchange="updateHotelApproval({{ $hotel->id }}, this.value, this)" wrapClass="w-28 xl:w-32 mx-auto" selectClass="py-1 px-2.5 text-[11px] font-bold" theme="sa" />
                                </td>

                                <!-- 8. 3-Dots Action Menu (Compact, Fixed Viewport Positioning) -->
                                <td class="px-3 py-3.5 text-center relative action-cell">
                                    <div class="relative inline-block text-left action-dropdown-container">
                                        <button type="button" 
                                                id="action-btn-{{ $hotel->id }}"
                                                onclick="toggleActionMenu(event, {{ $hotel->id }}, this)" 
                                                class="action-menu-btn w-8 h-8 rounded-xl border border-slate-200 hover:border-purple-300 bg-white hover:bg-purple-50 text-slate-500 hover:text-[#150A42] flex items-center justify-center transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 cursor-pointer" 
                                                title="Actions">
                                            <i class="fa-solid fa-ellipsis-vertical text-xs pointer-events-none"></i>
                                        </button>

                                        <!-- Floating Action Dropdown Menu -->
                                        <div id="action-dropdown-{{ $hotel->id }}" 
                                             class="action-menu-dropdown hidden absolute right-0 top-full mt-1.5 w-48 bg-white border border-slate-200/90 rounded-2xl shadow-2xl shadow-slate-900/15 p-1.5 z-[99999] divide-y divide-slate-100 text-left">
                                            <div class="py-1">
                                                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#150A42] transition-colors">
                                                    <i class="fa-regular fa-eye w-4 text-center text-slate-400"></i>
                                                    <span>View Details</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-[#150A42] transition-colors">
                                                    <i class="fa-regular fa-pen-to-square w-4 text-center text-purple-600"></i>
                                                    <span>Edit Account</span>
                                                </a>
                                            </div>

                                            <div class="py-1">
                                                @if($hotel->is_expired || $hotel->payment_status !== 'paid')
                                                    <a href="{{ route('super-admin.hotels.pay', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-bold text-emerald-600 hover:bg-emerald-50 transition-colors">
                                                        <i class="fa-solid fa-rotate w-4 text-center text-emerald-500"></i>
                                                        <span>Renew / Settle Plan</span>
                                                    </a>
                                                @else
                                                    <a href="{{ route('super-admin.hotels.addon', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-purple-700 hover:bg-purple-50 transition-colors">
                                                        <i class="fa-solid fa-plus w-4 text-center text-purple-500"></i>
                                                        <span>Add-On TV Slots</span>
                                                    </a>
                                                @endif
                                                <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                    <i class="fa-solid fa-tv w-4 text-center text-emerald-500"></i>
                                                    <span>Connected TVs ({{ $deviceCount }})</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.menus', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                    <i class="fa-solid fa-list-check w-4 text-center text-amber-500"></i>
                                                    <span>TV Menus</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition-colors">
                                                    <i class="fa-solid fa-spa w-4 text-center text-sky-500"></i>
                                                    <span>Hotel Amenities</span>
                                                </a>
                                            </div>

                                            <div class="py-1">
                                                <form id="delete-form-{{ $hotel->id }}" action="{{ route('super-admin.hotels.destroy', $hotel->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeleteHotel({{ $hotel->id }}, '{{ addslashes($hotel->hotel_name) }}')" class="w-full flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors text-left cursor-pointer">
                                                        <i class="fa-regular fa-trash-can w-4 text-center text-rose-500"></i>
                                                        <span>Delete Vendor</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE / TABLET VIEW: Ultra-responsive touch cards (< 1024px) -->
        <div class="block lg:hidden space-y-4" id="mobileHotelCardContainer">
            @foreach($hotels as $hotel)
                @php
                    $hotelSearchText = strtolower($hotel->hotel_name . ' ' . $hotel->owner_name . ' ' . $hotel->email . ' ' . $hotel->phone . ' ' . ($hotel->city ?? $hotel->hotel_location) . ' ' . ($hotel->license_key ?? '') . ' ' . ($hotel->distributor ? $hotel->distributor->name : ''));
                    $deviceCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
                    $subBadge = $hotel->subscription_badge;
                @endphp
                <div id="hotel-mobile-card-{{ $hotel->id }}" 
                     class="hotel-item hotel-mobile-card bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4 transition-all"
                     data-search="{{ $hotelSearchText }}"
                     data-status="{{ $hotel->status ? 'active' : 'inactive' }}"
                     data-approval="{{ $hotel->approval_status }}"
                     data-payment="{{ $hotel->payment_status }}"
                     data-subscription="{{ $subBadge['key'] }}"
                     data-distributor="{{ $hotel->distributor_id ?: 'direct' }}">
                    
                    <!-- Card Header: Logo, Name & Status Badges -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                @if($hotel->hotel_logo)
                                    <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-hotel text-slate-400 text-base"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-extrabold text-slate-900 text-base hover:text-[#150A42] transition-colors truncate block">
                                    {{ $hotel->hotel_name }}
                                </a>
                                <div class="text-xs text-slate-400 font-medium truncate flex items-center mt-0.5">
                                    <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[11px]"></i>
                                    <span>{{ $hotel->city ?? $hotel->hotel_location }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Top Status Pill -->
                        <div class="shrink-0 flex flex-col items-end space-y-1">
                            @if($hotel->status)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase font-mono">
                                    Live
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 uppercase font-mono">
                                    Inactive
                                </span>
                            @endif

                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $subBadge['badge_class'] }} border uppercase font-mono">
                                <span class="w-1.5 h-1.5 rounded-full {{ $subBadge['dot_class'] }} mr-1 {{ $subBadge['key'] === 'expiring_soon' ? 'animate-pulse' : '' }}"></span>
                                {{ $subBadge['label'] }}
                            </span>
                            @if($hotel->expiry_date)
                                <span class="text-[9px] font-mono {{ $subBadge['key'] === 'expired' ? 'text-rose-600 font-bold' : ($subBadge['key'] === 'expiring_soon' ? 'text-amber-600 font-bold' : 'text-slate-400') }}">
                                    @if($subBadge['key'] === 'expired')
                                        Exp: {{ $hotel->expiry_date->format('d M Y') }}
                                    @elseif($subBadge['key'] === 'expiring_soon')
                                        {{ $subBadge['subtext'] }}
                                    @else
                                        Exp: {{ $hotel->expiry_date->format('d M Y') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Distributor Partner if attached -->
                    @if($hotel->distributor)
                        <div class="p-2.5 rounded-xl bg-purple-50/70 border border-purple-100 flex items-center space-x-2 text-xs text-[#150A42]">
                            <i class="fa-solid fa-handshake text-purple-600"></i>
                            <span class="text-[11px] font-semibold">Partner: <strong class="font-bold">{{ $hotel->distributor->name }}</strong></span>
                        </div>
                    @endif

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 text-xs">
                        <!-- Owner Info -->
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider font-mono">Owner Contact</span>
                            <div class="font-bold text-slate-800 text-xs truncate">{{ $hotel->owner_name }}</div>
                            <a href="tel:{{ $hotel->phone }}" class="text-[#150A42] font-semibold text-xs flex items-center hover:underline">
                                <i class="fa-solid fa-phone mr-1 text-[10px]"></i> {{ $hotel->phone }}
                            </a>
                            <a href="mailto:{{ $hotel->email }}" class="text-slate-500 font-mono text-[11px] truncate block hover:underline">
                                {{ $hotel->email }}
                            </a>
                        </div>

                        <!-- Capacity & Plan -->
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider font-mono">Plan & TVs</span>
                            <div class="font-bold text-slate-800 text-xs font-mono">{{ $hotel->room_count }} Rooms Limit</div>
                            @if($hotel->plan)
                                <div class="text-[11px] font-bold text-[#150A42] font-mono">{{ $hotel->plan->name }}</div>
                            @else
                                <div class="text-[11px] text-slate-400 font-mono">Custom / Trial</div>
                            @endif
                            <div class="text-[11px] text-slate-500 flex items-center font-mono">
                                <i class="fa-solid fa-tv mr-1 text-[10px] text-slate-400"></i>
                                <span>{{ $deviceCount }} paired TVs</span>
                            </div>
                        </div>
                    </div>

                    <!-- License Key Bar -->
                    @if($hotel->license_key)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#F8F9FD] border border-slate-200/80">
                            <div class="flex items-center space-x-2 min-w-0">
                                <i class="fa-solid fa-key text-slate-400 text-xs"></i>
                                <span class="font-mono text-xs font-bold text-slate-700 truncate select-all">{{ $hotel->license_key }}</span>
                            </div>
                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-[11px] font-bold hover:text-[#150A42] hover:border-purple-300 shadow-xs flex items-center space-x-1 shrink-0 cursor-pointer">
                                <i class="fa-regular fa-copy text-[10px]"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    @endif

                    <!-- Interactive Controls (Status & Approval) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 p-3 rounded-xl bg-[#F8F9FD] border border-slate-200/60">
                        <div class="flex items-center justify-between sm:justify-start space-x-3">
                            <span class="text-xs font-bold text-slate-700">Live Status:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" onchange="toggleHotelStatus({{ $hotel->id }}, this)" {{ $hotel->status ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#150A42]"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between sm:justify-start space-x-2">
                            <span class="text-xs font-bold text-slate-700">Approval:</span>
                            <x-dropdown id="approval_status_mobile_{{ $hotel->id }}" name="approval_status_{{ $hotel->id }}" label="Approval" :options="['approved'=>'Approved','pending'=>'Pending','disapproved'=>'Disapproved']" :selected="$hotel->approval_status" onchange="updateHotelApproval({{ $hotel->id }}, this.value, this)" wrapClass="w-32" selectClass="py-1 px-2.5 text-xs font-bold" theme="sa" />
                        </div>
                    </div>

                    <!-- Touch-Friendly Action Buttons Grid -->
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 pt-1 border-t border-slate-100">
                        <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-slate-50 hover:bg-purple-50 text-slate-700 hover:text-[#150A42] text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-regular fa-eye text-xs text-slate-500"></i>
                            <span>Details</span>
                        </a>

                        <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-regular fa-pen-to-square text-xs text-purple-600"></i>
                            <span>Edit</span>
                        </a>

                        <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="py-2 px-1 text-center rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-solid fa-tv text-xs text-emerald-600"></i>
                            <span>TVs</span>
                        </a>

                        <a href="{{ route('super-admin.hotels.menus', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-solid fa-list-check text-xs text-amber-600"></i>
                            <span>Menus</span>
                        </a>

                        <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-solid fa-spa text-xs text-sky-600"></i>
                            <span>Spa</span>
                        </a>

                        <button type="button" onclick="confirmDeleteHotel({{ $hotel->id }}, '{{ addslashes($hotel->hotel_name) }}')" class="py-2 px-1 text-center rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1 cursor-pointer">
                            <i class="fa-regular fa-trash-can text-xs text-rose-600"></i>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Matching Search Results State -->
        <div id="noSearchResults" class="hidden bg-white border border-slate-200/80 rounded-3xl p-10 text-center shadow-xs">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 mb-1">No Matching Hotels Found</h4>
            <p class="text-xs text-slate-400 mb-4">No hotel records matched your search query or selected filter criteria.</p>
            <button type="button" onclick="resetFilters()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                Reset Search Filters
            </button>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Toast Notification helper using SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // 0. Toggle 3-Dots Action Menu Dropdown with Bulletproof Viewport (Fixed) Positioning
    function closeAllActionMenus() {
        document.querySelectorAll('.action-menu-dropdown').forEach(d => {
            d.classList.add('hidden');
            d.style.display = 'none';
        });
    }
    window.closeAllActionMenus = closeAllActionMenus;

    function toggleActionMenu(event, id, btnEl) {
        if (event) {
            if (typeof event.preventDefault === 'function') event.preventDefault();
            if (typeof event.stopPropagation === 'function') event.stopPropagation();
        }
        const dropdown = document.getElementById(`action-dropdown-${id}`);
        if (!dropdown) {
            console.warn('Action dropdown not found for ID:', id);
            return;
        }

        const isCurrentlyOpen = dropdown.style.display === 'block';

        // Close any currently open menus
        closeAllActionMenus();

        if (!isCurrentlyOpen) {
            const btn = btnEl || document.getElementById(`action-btn-${id}`) || (event ? event.target?.closest('button') : null);
            if (!btn) return;

            const rect = btn.getBoundingClientRect();
            const dropdownHeight = 240;
            const dropdownWidth = 192; // 12rem

            dropdown.style.position = 'fixed';
            dropdown.style.zIndex = '999999';

            // Top vs Bottom placement depending on available screen viewport
            if (window.innerHeight - rect.bottom < dropdownHeight && rect.top > dropdownHeight) {
                dropdown.style.top = (rect.top - dropdownHeight - 6) + 'px';
            } else {
                dropdown.style.top = (rect.bottom + 6) + 'px';
            }

            // Align right edge of menu with right edge of action button
            let leftPos = rect.right - dropdownWidth;
            if (leftPos < 10) leftPos = 10;
            if (leftPos + dropdownWidth > window.innerWidth - 10) {
                leftPos = window.innerWidth - dropdownWidth - 10;
            }
            dropdown.style.left = leftPos + 'px';

            dropdown.classList.remove('hidden');
            dropdown.style.display = 'block';
            dropdown.style.visibility = 'visible';
            dropdown.style.opacity = '1';
        }
    }
    window.toggleActionMenu = toggleActionMenu;

    // Dismiss dropdown when clicking anywhere outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-dropdown-container') && !e.target.closest('.action-menu-dropdown')) {
            closeAllActionMenus();
        }
    });

    // Dismiss dropdown on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllActionMenus();
        }
    });

    // Close dropdown on window resize or scroll
    window.addEventListener('resize', closeAllActionMenus);
    window.addEventListener('scroll', closeAllActionMenus, true);

    // 1. Copy License Key to Clipboard
    function copyLicenseKey(key) {
        if (!key) return;
        navigator.clipboard.writeText(key).then(() => {
            Toast.fire({
                icon: 'success',
                title: 'License key copied to clipboard!'
            });
        }).catch(() => {
            const temp = document.createElement('input');
            document.body.appendChild(temp);
            temp.value = key;
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            Toast.fire({
                icon: 'success',
                title: 'License key copied!'
            });
        });
    }

    // 2. Toggle Hotel Live Status with Toast Feedback
    function toggleHotelStatus(id, checkbox) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/super-admin/hotels/${id}/toggle-status`, {
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Status updated successfully'
                });

                // Synchronize both desktop row and mobile card status datasets
                const desktopRow = document.getElementById(`hotel-desktop-row-${id}`);
                const mobileCard = document.getElementById(`hotel-mobile-card-${id}`);
                const newStatus = data.status ? 'active' : 'inactive';

                if (desktopRow) desktopRow.setAttribute('data-status', newStatus);
                if (mobileCard) mobileCard.setAttribute('data-status', newStatus);

                // Sync the other checkbox if both exist
                const allCheckboxes = document.querySelectorAll(`input[onchange*="toggleHotelStatus(${id}"]`);
                allCheckboxes.forEach(cb => { cb.checked = data.status; });
            } else {
                checkbox.checked = !checkbox.checked;
                Toast.fire({
                    icon: 'error',
                    title: 'Failed to update hotel status'
                });
            }
        })
        .catch(err => {
            checkbox.checked = !checkbox.checked;
            Toast.fire({
                icon: 'error',
                title: 'Server communication error'
            });
        });
    }

    // 3. Update Hotel Approval Status with Toast Feedback & Visual Styling
    function updateHotelApproval(id, status, selectEl) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/super-admin/hotels/${id}/toggle-approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ approval_status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Approval status updated'
                });

                // Update styling on all select elements and custom dropdowns for this hotel
                const allSelects = document.querySelectorAll(`select[name*="approval_status_${id}"]`);
                allSelects.forEach(sel => {
                    sel.value = status;
                    const selectId = sel.id;
                    const labelText = status.charAt(0).toUpperCase() + status.slice(1);
                    const labelEl = document.getElementById('label_' + selectId);
                    if (labelEl) labelEl.textContent = labelText;
                    if (typeof syncDropdownStatusStyle === 'function') {
                        syncDropdownStatusStyle(selectId, status);
                    }
                });

                // Synchronize data attribute
                const desktopRow = document.getElementById(`hotel-desktop-row-${id}`);
                const mobileCard = document.getElementById(`hotel-mobile-card-${id}`);
                if (desktopRow) desktopRow.setAttribute('data-approval', status);
                if (mobileCard) mobileCard.setAttribute('data-approval', status);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Could not update approval status'
                });
            }
        })
        .catch(err => {
            Toast.fire({
                icon: 'error',
                title: 'Server communication error'
            });
        });
    }

    // 4. Delete Hotel with SweetAlert Confirmation
    function confirmDeleteHotel(id, hotelName) {
        Swal.fire({
            title: 'Delete Hotel Vendor?',
            html: `Are you sure you want to permanently delete <strong>"${hotelName}"</strong>?<br><span class="text-xs text-rose-600 mt-2 block font-semibold">This will unlink all connected TVs, uploaded slides, and client menus.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete vendor',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
                confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md',
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`delete-form-${id}`);
                if (form) form.submit();
            }
        });
    }

    // 5. Fast Client-Side Realtime Search & Filter Functionality
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('hotelSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const filterPills = document.querySelectorAll('.filter-pill');
        const hotelItems = document.querySelectorAll('.hotel-item');
        const noResults = document.getElementById('noSearchResults');

        let currentFilter = 'all';
        let searchQuery = '';
        let currentDistributor = "{{ request('distributor_id', '') }}";

        window.onDistributorFilterChange = function(distId) {
            currentDistributor = distId !== undefined && distId !== null ? String(distId).trim() : '';
            applyFilters();
        };

        const distSelect = document.getElementById('filter_distributor_id');
        if (distSelect) {
            distSelect.addEventListener('change', function() {
                window.onDistributorFilterChange(this.value);
            });
        }

        function applyFilters() {
            let visibleCount = 0;

            hotelItems.forEach(item => {
                const itemSearch = item.getAttribute('data-search') || '';
                const itemStatus = item.getAttribute('data-status') || '';
                const itemApproval = item.getAttribute('data-approval') || '';
                const itemPayment = item.getAttribute('data-payment') || '';
                const itemSubscription = item.getAttribute('data-subscription') || '';
                const itemDistributor = item.getAttribute('data-distributor') || '';

                const matchesQuery = !searchQuery || itemSearch.includes(searchQuery);

                let matchesDistributor = true;
                if (currentDistributor !== '' && currentDistributor !== 'all') {
                    matchesDistributor = itemDistributor === currentDistributor;
                }

                let matchesFilter = true;
                if (currentFilter === 'active') {
                    matchesFilter = itemStatus === 'active';
                } else if (currentFilter === 'pending') {
                    matchesFilter = itemApproval === 'pending';
                } else if (currentFilter === 'paid') {
                    matchesFilter = itemSubscription === 'active' || itemSubscription === 'expiring_soon';
                } else if (currentFilter === 'expiring') {
                    matchesFilter = itemSubscription === 'expiring_soon';
                } else if (currentFilter === 'expired') {
                    matchesFilter = itemSubscription === 'expired';
                } else if (currentFilter === 'unpaid') {
                    matchesFilter = itemSubscription === 'unpaid' || itemSubscription === 'pending';
                }

                if (matchesQuery && matchesFilter && matchesDistributor) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (noResults) {
                if (visibleCount === 0 && hotelItems.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                searchQuery = this.value.trim().toLowerCase();
                if (clearBtn) {
                    if (searchQuery.length > 0) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }
                applyFilters();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                searchQuery = '';
                this.classList.add('hidden');
                applyFilters();
                searchInput.focus();
            });
        }

        filterPills.forEach(pill => {
            pill.addEventListener('click', function () {
                filterPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                applyFilters();
            });
        });

        window.resetFilters = function() {
            if (searchInput) {
                searchInput.value = '';
                searchQuery = '';
            }
            if (clearBtn) clearBtn.classList.add('hidden');

            const distSelect = document.getElementById('filter_distributor_id');
            if (distSelect) {
                distSelect.value = '';
                const distLabel = document.getElementById('label_filter_distributor_id');
                if (distLabel) distLabel.textContent = 'All Distributors (Channel Partners)';
                if (typeof syncDropdownStatusStyle === 'function') {
                    syncDropdownStatusStyle('filter_distributor_id', '');
                }
            }
            currentDistributor = '';

            filterPills.forEach((p, idx) => {
                if (idx === 0) {
                    p.classList.add('active');
                } else {
                    p.classList.remove('active');
                }
            });
            currentFilter = 'all';
            applyFilters();
        };

        // If a distributor was pre-selected via query param, run filter on load
        if (currentDistributor) {
            applyFilters();
        }
    });
</script>
@endsection
