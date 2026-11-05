@extends('layouts.distributor')

@section('title', 'My Hotels - Distributor Hub')
@section('page_title', 'Partner Hotels Directory')

@section('styles')
<style>
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
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Partner Hotels Directory</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage hotel clients, TV pairing licenses, room limits, and recurring subscription packages.</p>
        </div>
        <a href="{{ route('distributor.hotels.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-2 active:scale-95 cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>+ Onboard New Hotel</span>
        </a>
    </div>

    <!-- Quick Stats Metric Cards (5 Columns matching Super Admin) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- Total Hotels -->
        <div class="bg-white border border-slate-100 rounded-[24px] p-4 sm:p-5 shadow-sm flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">{{ $stats['total'] ?? $hotels->total() }}</div>
                <div class="text-xs font-bold text-slate-400 truncate mt-0.5">Total Hotels</div>
            </div>
        </div>

        <!-- Active Hotels -->
        <div class="bg-white border border-slate-100 rounded-[24px] p-4 sm:p-5 shadow-sm flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight leading-tight">{{ $stats['active'] ?? 0 }}</div>
                <div class="text-xs font-bold text-slate-400 truncate mt-0.5">Active Hotels</div>
            </div>
        </div>

        <!-- Paid & Active -->
        <div class="bg-white border border-slate-100 rounded-[24px] p-4 sm:p-5 shadow-sm flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">{{ $stats['paid'] ?? 0 }}</div>
                <div class="text-xs font-bold text-slate-400 truncate mt-0.5">Paid & Active</div>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white border border-slate-100 rounded-[24px] p-4 sm:p-5 shadow-sm flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-black text-amber-600 tracking-tight leading-tight">{{ $stats['expiring'] ?? 0 }}</div>
                <div class="text-xs font-bold text-slate-400 truncate mt-0.5">Expiring Soon</div>
            </div>
        </div>

        <!-- Expired Plans -->
        <div class="bg-white border border-slate-100 rounded-[24px] p-4 sm:p-5 shadow-sm flex items-center space-x-3.5 hover:shadow-md transition-all col-span-2 md:col-span-1">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-black {{ ($stats['expired'] ?? 0) > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight leading-tight">{{ $stats['expired'] ?? 0 }}</div>
                <div class="text-xs font-bold text-slate-400 truncate mt-0.5">Expired Plans</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-4 sm:p-5 shadow-sm space-y-3.5">
        <form method="GET" action="{{ route('distributor.hotels.index') }}" id="searchForm" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 min-w-0">
            <!-- Hidden filter preserve -->
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <!-- Search Input -->
            <div class="relative flex-1 min-w-0">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" id="hotelSearchInput" value="{{ request('search') }}" placeholder="Search by hotel name, owner, city, email, phone, or license key..." class="w-full pl-10 pr-9 py-2.5 text-xs sm:text-sm rounded-2xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] placeholder:text-slate-400 text-slate-800 font-semibold transition-all">
                @if(request('search'))
                    <a href="{{ route('distributor.hotels.index', array_filter(['filter' => request('filter')])) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1" title="Clear Search">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                    </a>
                @endif
            </div>

            <button type="submit" class="py-2.5 px-6 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm active:scale-95 shrink-0">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Search</span>
            </button>
        </form>

        <!-- Filter Pills Bar -->
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 pt-3 border-t border-slate-100">
            @php $currentFilter = request('filter', 'all'); @endphp
            <a href="{{ route('distributor.hotels.index', array_filter(['search' => request('search')])) }}" class="filter-pill {{ $currentFilter === 'all' ? 'active' : '' }}">
                <span>All Hotels</span>
                <span class="ml-1 opacity-80 font-mono font-bold">({{ $stats['total'] ?? $hotels->total() }})</span>
            </a>
            <a href="{{ route('distributor.hotels.index', array_filter(['filter' => 'active', 'search' => request('search')])) }}" class="filter-pill {{ $currentFilter === 'active' ? 'active' : '' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                <span>Live Active</span>
                <span class="ml-1 opacity-70 font-mono font-bold">({{ $stats['active'] ?? 0 }})</span>
            </a>
            <a href="{{ route('distributor.hotels.index', array_filter(['filter' => 'paid', 'search' => request('search')])) }}" class="filter-pill {{ $currentFilter === 'paid' ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group text-[9px] mr-1.5 text-blue-500"></i>
                <span>Paid Plans</span>
                <span class="ml-1 opacity-70 font-mono font-bold">({{ $stats['paid'] ?? 0 }})</span>
            </a>
            <a href="{{ route('distributor.hotels.index', array_filter(['filter' => 'expiring', 'search' => request('search')])) }}" class="filter-pill {{ $currentFilter === 'expiring' ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left text-[9px] mr-1.5 text-amber-500"></i>
                <span>Expiring Soon</span>
                <span class="ml-1 opacity-80 font-mono font-bold">({{ $stats['expiring'] ?? 0 }})</span>
            </a>
            <a href="{{ route('distributor.hotels.index', array_filter(['filter' => 'expired', 'search' => request('search')])) }}" class="filter-pill {{ $currentFilter === 'expired' ? 'active' : '' }}">
                <i class="fa-solid fa-triangle-exclamation text-[9px] mr-1.5 text-rose-500"></i>
                <span>Expired</span>
                <span class="ml-1 opacity-80 font-mono font-bold">({{ $stats['expired'] ?? 0 }})</span>
            </a>
        </div>
    </div>

    <!-- Empty State -->
    @if($hotels->isEmpty())
        <div class="bg-white border border-slate-100 rounded-[28px] p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-[#150A42] mx-auto flex items-center justify-center text-2xl mb-4 border border-purple-100 shadow-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <h3 class="text-base font-black text-slate-900 mb-1">No Partner Hotels Found</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto mb-6 font-medium">
                @if(request('search') || request('filter'))
                    No hotels matched your search query or filter. Try clearing filters to see all properties.
                @else
                    Get started by onboarding your first hotel client. You can assign TV room quotas and sell subscription plans.
                @endif
            </p>
            @if(request('search') || request('filter'))
                <a href="{{ route('distributor.hotels.index') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset All Filters</span>
                </a>
            @else
                <a href="{{ route('distributor.hotels.create') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Onboard First Hotel</span>
                </a>
            @endif
        </div>
    @else
        <!-- DESKTOP / TABLET VIEW: High-density responsive table -->
        <div class="hidden md:block bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-800">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-4">Hotel Property</th>
                            <th class="px-5 py-4">Owner & Contact</th>
                            <th class="px-4 py-4">Rooms & Plan</th>
                            <th class="px-4 py-4">TV License Key</th>
                            <th class="px-4 py-4">Subscription & Expiry</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($hotels as $hotel)
                            @php
                                $isExpired = $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->isPast() : false;
                                $isExpiringSoon = $hotel->expiry_date && !$isExpired && \Carbon\Carbon::parse($hotel->expiry_date)->diffInDays(now()) <= 30;
                            @endphp
                            <tr class="hover:bg-purple-50/20 transition-colors">
                                <!-- Hotel Property -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3.5">
                                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-sm shrink-0 shadow-2xs overflow-hidden">
                                            @if($hotel->hotel_logo)
                                                <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-hotel"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-[240px]">
                                            <div class="font-black text-slate-900 text-sm truncate" title="{{ $hotel->hotel_name }}">
                                                {{ $hotel->hotel_name }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-semibold truncate flex items-center mt-0.5">
                                                <i class="fa-solid fa-location-dot mr-1 text-purple-600 text-[10px]"></i>
                                                <span class="truncate">{{ $hotel->city ?? $hotel->hotel_location }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Owner & Contact -->
                                <td class="px-5 py-4 space-y-0.5">
                                    <div class="font-bold text-slate-900 text-xs">{{ $hotel->owner_name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono truncate max-w-[180px]">
                                        <a href="mailto:{{ $hotel->email }}" class="hover:text-purple-700 transition-colors" title="{{ $hotel->email }}">{{ $hotel->email }}</a>
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium">
                                        <a href="tel:{{ $hotel->phone }}" class="hover:text-purple-700 transition-colors flex items-center">
                                            <i class="fa-solid fa-phone mr-1 text-[9px] text-slate-400"></i>
                                            <span>{{ $hotel->phone }}</span>
                                        </a>
                                    </div>
                                </td>

                                <!-- Rooms & Plan -->
                                <td class="px-4 py-4">
                                    <div class="font-black text-slate-900 text-xs">
                                        {{ $hotel->room_count }} <span class="text-slate-400 font-semibold">Rooms</span>
                                    </div>
                                    <div class="mt-1">
                                        @if($hotel->plan)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                                                <i class="fa-solid fa-layer-group mr-1 text-[8px] text-blue-600"></i>
                                                <span>{{ $hotel->plan->name }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                                No Plan Assigned
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- TV License Key -->
                                <td class="px-4 py-4">
                                    <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200/80 font-mono text-[11px] font-black text-slate-800 tracking-wider shadow-2xs">
                                        <span>{{ $hotel->license_key }}</span>
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $hotel->license_key }}'); this.innerHTML='<i class=\'fa-solid fa-check text-emerald-600 text-[10px]\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy text-[10px]\'></i>', 1500);" class="text-slate-400 hover:text-slate-700 p-0.5 cursor-pointer" title="Copy License Key">
                                            <i class="fa-regular fa-copy text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Subscription & Expiry -->
                                <td class="px-4 py-4">
                                    @if($hotel->expiry_date)
                                        <div class="font-black text-xs {{ $isExpired ? 'text-rose-600' : 'text-slate-900' }}">
                                            {{ \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') }}
                                        </div>
                                        @if($isExpired)
                                            <span class="inline-flex items-center px-2 py-0.2 rounded-md bg-rose-50 text-rose-600 text-[9px] font-black border border-rose-200 mt-0.5">
                                                Expired
                                            </span>
                                        @elseif($isExpiringSoon)
                                            <span class="inline-flex items-center px-2 py-0.2 rounded-md bg-amber-50 text-amber-700 text-[9px] font-black border border-amber-200 mt-0.5">
                                                Expiring Soon
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[10px] text-emerald-600 font-bold mt-0.5">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-1"></i> Active Plan
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Not set</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-4 text-center">
                                    @if($hotel->status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="px-4 py-4 text-center">
                                    <div class="inline-flex items-center space-x-1.5">
                                        @if(!$isExpired && $hotel->plan && $hotel->expiry_date && \Carbon\Carbon::parse($hotel->expiry_date)->isFuture())
                                            <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id, 'mode' => 'addon']) }}" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-400 text-amber-900 border border-amber-200/80 font-black text-xs shadow-2xs transition-all inline-flex items-center space-x-1 active:scale-95 cursor-pointer" title="Add-On Extra TV Screens">
                                                <i class="fa-solid fa-plus text-[10px] text-amber-700"></i>
                                                <span>Add TVs</span>
                                            </a>
                                        @endif
                                        <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white border border-purple-200/80 font-black text-xs shadow-2xs transition-all inline-flex items-center space-x-1.5 active:scale-95 cursor-pointer" title="Sell or Renew Subscription Package">
                                            <i class="fa-solid fa-box-open text-[10px]"></i>
                                            <span>{{ $isExpired ? 'Renew' : 'Package' }}</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE VIEW: High-density cards -->
        <div class="md:hidden space-y-3.5">
            @foreach($hotels as $hotel)
                @php
                    $isExpired = $hotel->expiry_date ? \Carbon\Carbon::parse($hotel->expiry_date)->isPast() : false;
                    $isExpiringSoon = $hotel->expiry_date && !$isExpired && \Carbon\Carbon::parse($hotel->expiry_date)->diffInDays(now()) <= 30;
                @endphp
                <div class="bg-white border border-slate-100 rounded-[24px] p-4 shadow-sm space-y-3">
                    <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-sm shrink-0">
                                @if($hotel->hotel_logo)
                                    <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-hotel"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-slate-900 truncate">{{ $hotel->hotel_name }}</h4>
                                <p class="text-[11px] text-slate-400 font-semibold truncate">{{ $hotel->city ?? $hotel->hotel_location }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $hotel->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }} shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $hotel->status ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }} mr-1"></span>
                            {{ $hotel->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                            <div class="text-[10px] uppercase font-bold text-slate-400">Owner & Contact</div>
                            <div class="font-bold text-slate-800 truncate mt-0.5">{{ $hotel->owner_name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $hotel->phone }}</div>
                        </div>

                        <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                            <div class="text-[10px] uppercase font-bold text-slate-400">Plan & Capacity</div>
                            <div class="font-black text-slate-900 mt-0.5">{{ $hotel->room_count }} Rooms</div>
                            <div class="text-[10px] font-bold text-purple-700 truncate">{{ $hotel->plan ? $hotel->plan->name : 'No Plan' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="min-w-0">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">TV License Key</span>
                            <span class="font-mono font-black text-[11px] text-slate-800 truncate block">{{ $hotel->license_key }}</span>
                        </div>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $hotel->license_key }}'); this.innerHTML='<i class=\'fa-solid fa-check text-emerald-600\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 1500);" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-bold shadow-2xs shrink-0 cursor-pointer">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>

                    <div class="pt-1 flex items-center space-x-2">
                        @if(!$isExpired && $hotel->plan && $hotel->expiry_date && \Carbon\Carbon::parse($hotel->expiry_date)->isFuture())
                            <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id, 'mode' => 'addon']) }}" class="py-2.5 px-3 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300/80 font-black text-xs flex items-center justify-center space-x-1.5 transition-all shadow-2xs active:scale-95 shrink-0">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add TVs</span>
                            </a>
                        @endif
                        <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="flex-1 py-2.5 px-3 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs flex items-center justify-center space-x-1.5 transition-all shadow-sm active:scale-95">
                            <i class="fa-solid fa-box-open text-xs"></i>
                            <span>{{ $isExpired ? 'Renew Package' : 'Sell Package' }}</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($hotels->hasPages())
            <div class="bg-white border border-slate-100 rounded-[28px] p-4 shadow-sm">
                {{ $hotels->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
