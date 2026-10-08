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
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Primary Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Registered Hotels Directory</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage hotel clients, pairing licenses, channel partners, and live streaming status.</p>
        </div>
        <a href="{{ route('super-admin.hotels.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/25 transition-all hover:-translate-y-0.5 space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Hotel Vendor</span>
        </a>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-bold shrink-0">
                <i class="fa-solid fa-hotel text-slate-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight" id="statTotalHotels">{{ $hotels->count() }}</div>
                <div class="text-[11px] font-semibold text-slate-400 truncate">Total Hotels</div>
            </div>
        </div>

        <!-- Active Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-emerald-700 leading-tight">{{ $hotels->where('status', true)->count() }}</div>
                <div class="text-[11px] font-semibold text-slate-400 truncate">Active Platforms</div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0">
                <i class="fa-solid fa-clock text-amber-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-amber-700 leading-tight">{{ $hotels->where('approval_status', 'pending')->count() }}</div>
                <div class="text-[11px] font-semibold text-slate-400 truncate">Pending Review</div>
            </div>
        </div>

        <!-- Paid Subscriptions -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold shrink-0">
                <i class="fa-solid fa-credit-card text-indigo-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-indigo-700 leading-tight">{{ $hotels->where('payment_status', 'paid')->count() }}</div>
                <div class="text-[11px] font-semibold text-slate-400 truncate">Paid Packages</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="hotelSearchInput" placeholder="Search by hotel name, owner, city, email, phone, or license key..." class="w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:outline-none focus:ring-2 focus:ring-rose-500/25 focus:border-rose-500 placeholder-slate-400 transition-all">
                <button type="button" id="clearSearchBtn" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <!-- Filter Pills -->
            <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
                <button type="button" class="filter-pill active px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-rose-600 text-white shadow-xs" data-filter="all">
                    All <span class="ml-1 opacity-80 font-normal">({{ $hotels->count() }})</span>
                </button>
                <button type="button" class="filter-pill px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-all" data-filter="active">
                    Active <span class="ml-1 opacity-70 font-normal">({{ $hotels->where('status', true)->count() }})</span>
                </button>
                <button type="button" class="filter-pill px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-all" data-filter="pending">
                    Pending <span class="ml-1 opacity-70 font-normal">({{ $hotels->where('approval_status', 'pending')->count() }})</span>
                </button>
                <button type="button" class="filter-pill px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-all" data-filter="paid">
                    Paid <span class="ml-1 opacity-70 font-normal">({{ $hotels->where('payment_status', 'paid')->count() }})</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State for 0 total hotels in system -->
    @if($hotels->isEmpty())
        <div class="bg-white border border-slate-200/80 rounded-3xl p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 mx-auto flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 mb-1">No Hotel Vendors Registered</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto mb-6">Get started by creating your first hotel client. You can assign TV limit plans, logos, and distributor channels.</p>
            <a href="{{ route('super-admin.hotels.create') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add First Hotel Vendor</span>
            </a>
        </div>
    @else
        <!-- DESKTOP / TABLET VIEW: High-density responsive table with 3-dot Action Menu -->
        <div class="hidden lg:block bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-visible">
            <div class="overflow-visible">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/90 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[10px] xl:text-[11px]">
                        <tr>
                            <th class="px-2.5 py-3 xl:px-4 xl:py-3.5">Hotel Client</th>
                            <th class="px-2 py-3 xl:px-4 xl:py-3.5">Owner & Contact</th>
                            <th class="px-2 py-3 xl:px-3 xl:py-3.5">Plan & TVs</th>
                            <th class="hidden xl:table-cell px-2.5 py-3 xl:px-3 xl:py-3.5">License Key</th>
                            <th class="px-1.5 py-3 xl:px-2.5 xl:py-3.5 text-center">Payment</th>
                            <th class="px-1.5 py-3 xl:px-2.5 xl:py-3.5 text-center">Status</th>
                            <th class="px-2 py-3 xl:px-3 xl:py-3.5 text-center">Approval</th>
                            <th class="px-2 py-3 xl:px-3 xl:py-3.5 text-center w-10">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="desktopHotelTableBody">
                        @foreach($hotels as $hotel)
                            @php
                                $hotelSearchText = strtolower($hotel->hotel_name . ' ' . $hotel->owner_name . ' ' . $hotel->email . ' ' . $hotel->phone . ' ' . ($hotel->city ?? $hotel->hotel_location) . ' ' . ($hotel->license_key ?? '') . ' ' . ($hotel->distributor ? $hotel->distributor->name : ''));
                                $deviceCount = $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0;
                            @endphp
                            <tr id="hotel-desktop-row-{{ $hotel->id }}" 
                                class="hotel-item hotel-desktop-row hover:bg-slate-50/80 transition-colors"
                                data-search="{{ $hotelSearchText }}"
                                data-status="{{ $hotel->status ? 'active' : 'inactive' }}"
                                data-approval="{{ $hotel->approval_status }}"
                                data-payment="{{ $hotel->payment_status }}">
                                <!-- 1. Hotel Client -->
                                <td class="px-2.5 py-3 xl:px-4 xl:py-3.5">
                                    <div class="flex items-center space-x-2 xl:space-x-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                            @if($hotel->hotel_logo)
                                                <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-hotel text-slate-400 text-xs"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-[100px] xl:max-w-[160px]">
                                            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-extrabold text-slate-900 text-xs hover:text-rose-600 transition-colors truncate block" title="{{ $hotel->hotel_name }}">
                                                {{ $hotel->hotel_name }}
                                            </a>
                                            <div class="text-[10px] text-slate-400 font-medium truncate flex items-center mt-0.5">
                                                <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[9px]"></i>
                                                <span class="truncate">{{ $hotel->city ?? $hotel->hotel_location }}</span>
                                            </div>
                                            @if($hotel->distributor)
                                                <span class="inline-flex items-center text-[9px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded mt-0.5 truncate max-w-[95px] xl:max-w-[120px]" title="Distributor: {{ $hotel->distributor->name }}">
                                                    <i class="fa-solid fa-handshake mr-1 text-[8px]"></i> {{ Str::limit($hotel->distributor->name, 10) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- 2. Owner & Contact -->
                                <td class="px-2 py-3 xl:px-4 xl:py-3.5 space-y-0.5">
                                    <div class="font-bold text-slate-800 text-xs truncate max-w-[95px] xl:max-w-[130px]">{{ $hotel->owner_name }}</div>
                                    <div class="hidden xl:block text-[10px] text-slate-500 font-mono truncate max-w-[130px]">
                                        <a href="mailto:{{ $hotel->email }}" class="hover:text-rose-600 transition-colors" title="{{ $hotel->email }}">{{ $hotel->email }}</a>
                                    </div>
                                    <div class="text-[10px] text-slate-500">
                                        <a href="tel:{{ $hotel->phone }}" class="hover:text-rose-600 transition-colors flex items-center">
                                            <i class="fa-solid fa-phone mr-1 text-[8px] text-slate-400"></i><span class="truncate max-w-[85px] xl:max-w-none">{{ $hotel->phone }}</span>
                                        </a>
                                    </div>
                                </td>

                                <!-- 3. Plan & Capacity -->
                                <td class="px-2 py-3 xl:px-3 xl:py-3.5">
                                    <div class="font-extrabold text-slate-900 text-xs whitespace-nowrap">{{ $hotel->room_count }} Rms</div>
                                    <div class="flex items-center space-x-1 mt-0.5">
                                        @if($hotel->plan)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-50 text-rose-600 border border-rose-200 truncate max-w-[80px]">
                                                {{ $hotel->plan->name }}
                                            </span>
                                        @else
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-medium bg-slate-100 text-slate-500">
                                                Trial
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 flex items-center whitespace-nowrap">
                                        <i class="fa-solid fa-tv text-[8px] mr-1 text-slate-400"></i>{{ $deviceCount }} paired
                                    </div>
                                    @if($hotel->license_key)
                                        <div class="xl:hidden mt-1 inline-flex items-center space-x-1 px-1.5 py-0.2 rounded bg-slate-100/90 border border-slate-200 text-slate-700 font-mono text-[9px]">
                                            <span class="truncate max-w-[50px]">{{ $hotel->license_key }}</span>
                                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="text-slate-400 hover:text-rose-600 p-0.5" title="Copy Key">
                                                <i class="fa-regular fa-copy text-[8px]"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>

                                <!-- 4. License Key (Desktop only) -->
                                <td class="hidden xl:table-cell px-2.5 py-3 xl:px-3 xl:py-3.5">
                                    @if($hotel->license_key)
                                        <div class="inline-flex items-center space-x-1 px-1.5 xl:px-2 py-0.5 rounded-lg bg-slate-100/90 border border-slate-200 text-slate-800 font-mono text-[10px]">
                                            <span class="tracking-tight select-all truncate max-w-[70px] xl:max-w-[85px]">{{ $hotel->license_key }}</span>
                                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="text-slate-400 hover:text-rose-600 transition-colors p-0.5" title="Copy Key">
                                                <i class="fa-regular fa-copy text-[10px]"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">N/A</span>
                                    @endif
                                </td>

                                <!-- 5. Payment Status -->
                                <td class="px-1.5 py-3 xl:px-2.5 xl:py-3.5 text-center whitespace-nowrap">
                                    @if($hotel->payment_status === 'paid')
                                        <span class="inline-flex items-center px-1.5 xl:px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[9px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span> Paid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 xl:px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-bold text-[9px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1"></span> Unpaid
                                        </span>
                                    @endif
                                </td>

                                <!-- 6. Live Status Toggle -->
                                <td class="px-1.5 py-3 xl:px-2.5 xl:py-3.5 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle active status">
                                        <input type="checkbox" onchange="toggleHotelStatus({{ $hotel->id }}, this)" {{ $hotel->status ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-rose-600"></div>
                                    </label>
                                </td>

                                <!-- 7. Approval Status -->
                                <td class="px-2 py-3 xl:px-3 xl:py-3.5 text-center">
                                    <select onchange="updateHotelApproval({{ $hotel->id }}, this.value, this)" 
                                            class="approval-select px-1.5 py-1 rounded-lg border text-[10px] xl:text-[11px] font-bold focus:outline-none transition-all max-w-[85px] xl:max-w-none {{ $hotel->approval_status === 'approved' ? 'approval-select-approved' : ($hotel->approval_status === 'pending' ? 'approval-select-pending' : 'approval-select-disapproved') }}">
                                        <option value="approved" {{ $hotel->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="pending" {{ $hotel->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="disapproved" {{ $hotel->approval_status == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                                    </select>
                                </td>

                                <!-- 8. 3-Dots Action Menu (Compact, No Horizontal Overflow) -->
                                <td class="px-2 py-3 xl:px-3 xl:py-3.5 text-center relative">
                                    <div class="relative inline-block text-left action-dropdown-container">
                                        <button type="button" 
                                                onclick="toggleActionMenu(event, {{ $hotel->id }})" 
                                                class="action-menu-btn w-7 h-7 xl:w-8 xl:h-8 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20" 
                                                title="Actions">
                                            <i class="fa-solid fa-ellipsis-vertical text-xs pointer-events-none"></i>
                                        </button>

                                        <!-- Floating Action Dropdown Menu -->
                                        <div id="action-dropdown-{{ $hotel->id }}" 
                                             class="action-menu-dropdown hidden absolute right-0 top-full mt-1.5 w-44 xl:w-48 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-50 divide-y divide-slate-100 text-left">
                                            <div class="py-1">
                                                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                    <i class="fa-regular fa-eye w-4 text-center text-slate-400"></i>
                                                    <span>View Details</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition-colors">
                                                    <i class="fa-regular fa-pen-to-square w-4 text-center text-violet-500"></i>
                                                    <span>Edit Account</span>
                                                </a>
                                            </div>

                                            <div class="py-1">
                                                <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                    <i class="fa-solid fa-tv w-4 text-center text-emerald-500"></i>
                                                    <span>Connected TVs ({{ $deviceCount }})</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.menus', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                    <i class="fa-solid fa-list-check w-4 text-center text-amber-500"></i>
                                                    <span>TV Menus</span>
                                                </a>
                                                <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                                    <i class="fa-solid fa-spa w-4 text-center text-indigo-500"></i>
                                                    <span>Hotel Amenities</span>
                                                </a>
                                            </div>

                                            <div class="py-1">
                                                <form id="delete-form-{{ $hotel->id }}" action="{{ route('super-admin.hotels.destroy', $hotel->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeleteHotel({{ $hotel->id }}, '{{ addslashes($hotel->hotel_name) }}')" class="w-full flex items-center space-x-2.5 px-3 py-1.5 xl:py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors text-left">
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
                @endphp
                <div id="hotel-mobile-card-{{ $hotel->id }}" 
                     class="hotel-item hotel-mobile-card bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4 transition-all"
                     data-search="{{ $hotelSearchText }}"
                     data-status="{{ $hotel->status ? 'active' : 'inactive' }}"
                     data-approval="{{ $hotel->approval_status }}"
                     data-payment="{{ $hotel->payment_status }}">
                    
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
                                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-extrabold text-slate-900 text-base hover:text-rose-600 transition-colors truncate block">
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
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                    Live
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                    Inactive
                                </span>
                            @endif

                            @if($hotel->payment_status === 'paid')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">
                                    Paid
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                    Unpaid
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Distributor Partner if attached -->
                    @if($hotel->distributor)
                        <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-200/60 flex items-center space-x-2 text-xs text-amber-800">
                            <i class="fa-solid fa-handshake text-amber-600"></i>
                            <span class="text-[11px] font-semibold">Partner: <strong class="font-bold">{{ $hotel->distributor->name }}</strong></span>
                        </div>
                    @endif

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 text-xs">
                        <!-- Owner Info -->
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Owner Contact</span>
                            <div class="font-bold text-slate-800 text-xs truncate">{{ $hotel->owner_name }}</div>
                            <a href="tel:{{ $hotel->phone }}" class="text-rose-600 font-semibold text-xs flex items-center hover:underline">
                                <i class="fa-solid fa-phone mr-1 text-[10px]"></i> {{ $hotel->phone }}
                            </a>
                            <a href="mailto:{{ $hotel->email }}" class="text-slate-500 font-mono text-[11px] truncate block hover:underline">
                                {{ $hotel->email }}
                            </a>
                        </div>

                        <!-- Capacity & Plan -->
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Plan & TVs</span>
                            <div class="font-bold text-slate-800 text-xs">{{ $hotel->room_count }} Rooms Limit</div>
                            @if($hotel->plan)
                                <div class="text-[11px] font-bold text-rose-600">{{ $hotel->plan->name }}</div>
                            @else
                                <div class="text-[11px] text-slate-400">Custom / Trial</div>
                            @endif
                            <div class="text-[11px] text-slate-500 flex items-center">
                                <i class="fa-solid fa-tv mr-1 text-[10px] text-slate-400"></i>
                                <span>{{ $deviceCount }} paired TVs</span>
                            </div>
                        </div>
                    </div>

                    <!-- License Key Bar -->
                    @if($hotel->license_key)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center space-x-2 min-w-0">
                                <i class="fa-solid fa-key text-slate-400 text-xs"></i>
                                <span class="font-mono text-xs font-bold text-slate-700 truncate select-all">{{ $hotel->license_key }}</span>
                            </div>
                            <button type="button" onclick="copyLicenseKey('{{ $hotel->license_key }}')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-[11px] font-bold hover:text-rose-600 shadow-xs flex items-center space-x-1 shrink-0">
                                <i class="fa-regular fa-copy text-[10px]"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    @endif

                    <!-- Interactive Controls (Status & Approval) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 p-3 rounded-xl bg-slate-50/70 border border-slate-100">
                        <div class="flex items-center justify-between sm:justify-start space-x-3">
                            <span class="text-xs font-bold text-slate-700">Live Status:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" onchange="toggleHotelStatus({{ $hotel->id }}, this)" {{ $hotel->status ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between sm:justify-start space-x-2">
                            <span class="text-xs font-bold text-slate-700">Approval:</span>
                            <select onchange="updateHotelApproval({{ $hotel->id }}, this.value, this)" 
                                    class="approval-select px-3 py-1.5 rounded-xl border text-xs font-bold focus:outline-none transition-all {{ $hotel->approval_status === 'approved' ? 'approval-select-approved' : ($hotel->approval_status === 'pending' ? 'approval-select-pending' : 'approval-select-disapproved') }}">
                                <option value="approved" {{ $hotel->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $hotel->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disapproved" {{ $hotel->approval_status == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                            </select>
                        </div>
                    </div>

                    <!-- Touch-Friendly Action Buttons Grid -->
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 pt-1 border-t border-slate-100">
                        <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-regular fa-eye text-xs text-slate-500"></i>
                            <span>Details</span>
                        </a>

                        <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-regular fa-pen-to-square text-xs text-violet-600"></i>
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

                        <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="py-2 px-1 text-center rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
                            <i class="fa-solid fa-spa text-xs text-indigo-600"></i>
                            <span>Spa</span>
                        </a>

                        <button type="button" onclick="confirmDeleteHotel({{ $hotel->id }}, '{{ addslashes($hotel->hotel_name) }}')" class="py-2 px-1 text-center rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition-colors flex flex-col items-center justify-center space-y-1">
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

    // 0. Toggle 3-Dots Action Menu Dropdown with Smart Viewport Positioning
    function toggleActionMenu(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(`action-dropdown-${id}`);
        if (!dropdown) return;
        const isClosed = dropdown.classList.contains('hidden');

        // Close any currently open menus
        document.querySelectorAll('.action-menu-dropdown').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('#desktopHotelTableBody tr').forEach(tr => tr.classList.remove('z-30', 'relative'));

        if (isClosed) {
            // Flip upwards if close to viewport bottom
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();
            if (window.innerHeight - rect.bottom < 240) {
                dropdown.classList.remove('top-full', 'mt-1.5');
                dropdown.classList.add('bottom-full', 'mb-1.5');
            } else {
                dropdown.classList.remove('bottom-full', 'mb-1.5');
                dropdown.classList.add('top-full', 'mt-1.5');
            }
            dropdown.classList.remove('hidden');
            btn.closest('tr')?.classList.add('z-30', 'relative');
        }
    }

    // Dismiss dropdown when clicking anywhere outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-dropdown-container')) {
            document.querySelectorAll('.action-menu-dropdown').forEach(d => d.classList.add('hidden'));
            document.querySelectorAll('#desktopHotelTableBody tr').forEach(tr => tr.classList.remove('z-30', 'relative'));
        }
    });

    // Dismiss dropdown on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.action-menu-dropdown').forEach(d => d.classList.add('hidden'));
            document.querySelectorAll('#desktopHotelTableBody tr').forEach(tr => tr.classList.remove('z-30', 'relative'));
        }
    });

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

                // Update styling on all select elements for this hotel
                const allSelects = document.querySelectorAll(`select[onchange*="updateHotelApproval(${id}"]`);
                allSelects.forEach(sel => {
                    sel.value = status;
                    sel.classList.remove('approval-select-approved', 'approval-select-pending', 'approval-select-disapproved');
                    if (status === 'approved') sel.classList.add('approval-select-approved');
                    else if (status === 'pending') sel.classList.add('approval-select-pending');
                    else if (status === 'disapproved') sel.classList.add('approval-select-disapproved');
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
            cancelButtonColor: '#64748b',
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

        function applyFilters() {
            let visibleCount = 0;

            hotelItems.forEach(item => {
                const itemSearch = item.getAttribute('data-search') || '';
                const itemStatus = item.getAttribute('data-status') || '';
                const itemApproval = item.getAttribute('data-approval') || '';
                const itemPayment = item.getAttribute('data-payment') || '';

                const matchesQuery = !searchQuery || itemSearch.includes(searchQuery);

                let matchesFilter = true;
                if (currentFilter === 'active') {
                    matchesFilter = itemStatus === 'active';
                } else if (currentFilter === 'pending') {
                    matchesFilter = itemApproval === 'pending';
                } else if (currentFilter === 'paid') {
                    matchesFilter = itemPayment === 'paid';
                }

                if (matchesQuery && matchesFilter) {
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
                filterPills.forEach(p => {
                    p.classList.remove('active', 'bg-rose-600', 'text-white', 'shadow-xs');
                    p.classList.add('text-slate-600');
                });

                this.classList.add('active', 'bg-rose-600', 'text-white', 'shadow-xs');
                this.classList.remove('text-slate-600');

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

            filterPills.forEach((p, idx) => {
                if (idx === 0) {
                    p.classList.add('active', 'bg-rose-600', 'text-white', 'shadow-xs');
                    p.classList.remove('text-slate-600');
                } else {
                    p.classList.remove('active', 'bg-rose-600', 'text-white', 'shadow-xs');
                    p.classList.add('text-slate-600');
                }
            });
            currentFilter = 'all';
            applyFilters();
        };
    });
</script>
@endsection
