@extends('layouts.hotel_admin')

@section('title', 'Manage In-House Guests - Hotel Admin')
@section('page_title', 'In-House Guests')

@section('content')
@php
    $now = now();
    $activeGuests = $guests->filter(function($g) use ($now) {
        return $now->gte($g->check_in_datetime) && (!$g->check_out_datetime || $now->lte($g->check_out_datetime));
    });
    $scheduledGuests = $guests->filter(function($g) use ($now) {
        return $now->lt($g->check_in_datetime);
    });
    $checkedOutGuests = $guests->filter(function($g) use ($now) {
        return $g->check_out_datetime && $now->gt($g->check_out_datetime);
    });

    $activeCount = $activeGuests->count();
    $scheduledCount = $scheduledGuests->count();
    $checkedOutCount = $checkedOutGuests->count();
    $distinctRoomsCount = $activeGuests->pluck('room_number')->unique()->count();
    $totalCount = $guests->count();
@endphp

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hotel Admin</span>
                <span>/</span>
                <span class="text-[#150A42] font-black">Guest Management</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">In-House Guests & Room Allocation</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage guest check-ins, personalized Smart TV welcome screens, and automated checkout schedules.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if(request()->filled('room'))
                <a href="{{ route('hotel.guests.index') }}" class="px-3.5 py-2.5 rounded-2xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-filter-circle-xmark text-slate-400"></i>
                    <span>Clear Filter (Room {{ request('room') }})</span>
                </a>
            @endif
            <button type="button" 
                    onclick="openAddModal()" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white text-xs font-black shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-user-plus text-xs text-amber-300"></i>
                <span>Check In New Guest</span>
            </button>
        </div>
    </div>

    <!-- Executive KPI Metrics Summary Cards (Thin Borders & Luxury Theme) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        
        <!-- 1. Active In-House Guests -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $activeCount }}</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Active In-House</div>
            </div>
        </div>

        <!-- 2. Occupied TV Rooms -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-door-open text-purple-700"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-1.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $distinctRoomsCount }}</span>
                    <span class="text-[10px] font-mono font-bold text-purple-700 bg-purple-100/70 px-1.5 py-0.5 rounded">TV Sync</span>
                </div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Occupied Rooms</div>
            </div>
        </div>

        <!-- 3. Scheduled Check-Ins -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $scheduledCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Scheduled Check-Ins</div>
            </div>
        </div>

        <!-- 4. Past Check-Outs -->
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-slate-50 text-slate-500 border border-slate-200/80 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $checkedOutCount }}</div>
                <div class="text-[11px] sm:text-xs font-bold text-slate-500 truncate">Past Check-Outs</div>
            </div>
        </div>
    </div>

    <!-- Main Guests Directory Card -->
    <div class="bg-white border border-slate-100 rounded-[24px] sm:rounded-[32px] p-4 sm:p-6 shadow-sm space-y-5">
        
        <!-- Controls Bar: Live Search & Category Tabs -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 border-b border-slate-100 pb-5">
            
            <!-- Live Search Bar -->
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       id="guestSearchInput" 
                       placeholder="Search by guest name, mobile, or room (e.g. 101)..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center p-1 bg-slate-100 rounded-2xl overflow-x-auto max-w-full no-scrollbar">
                <button type="button" onclick="filterGuestStatus('all', this)" class="guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-black transition-all bg-white text-[#150A42] shadow-2xs whitespace-nowrap">
                    All Guests ({{ $totalCount }})
                </button>
                <button type="button" onclick="filterGuestStatus('active', this)" class="guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap flex items-center space-x-1">
                    <span>Active In-House</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                </button>
                <button type="button" onclick="filterGuestStatus('scheduled', this)" class="guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap">
                    Scheduled ({{ $scheduledCount }})
                </button>
                <button type="button" onclick="filterGuestStatus('checked_out', this)" class="guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap">
                    Checked Out ({{ $checkedOutCount }})
                </button>
            </div>
        </div>

        <!-- Desktop High-End Table View (Visible on Tablet & Desktop) -->
        <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200/80">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Guest</th>
                            <th class="px-5 py-3.5">Room & TV Screen</th>
                            <th class="px-5 py-3.5">Mobile Contact</th>
                            <th class="px-5 py-3.5">Check-In Datetime</th>
                            <th class="px-5 py-3.5">Check-Out Datetime</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="desktopGuestTableBody">
                        @forelse($guests as $guest)
                            @php
                                if ($now->lt($guest->check_in_datetime)) {
                                    $statusKey = 'scheduled';
                                    $statusText = 'Scheduled';
                                    $statusClass = 'bg-sky-50 text-sky-700 border-sky-200/70';
                                    $statusDot = 'bg-sky-500';
                                } elseif ($guest->check_out_datetime && $now->gt($guest->check_out_datetime)) {
                                    $statusKey = 'checked_out';
                                    $statusText = 'Checked Out';
                                    $statusClass = 'bg-slate-100 text-slate-600 border-slate-200/80';
                                    $statusDot = 'bg-slate-400';
                                } else {
                                    $statusKey = 'active';
                                    $statusText = 'Active In-House';
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                                    $statusDot = 'bg-emerald-500 animate-pulse';
                                }
                            @endphp
                            <tr class="guest-row hover:bg-purple-50/20 transition-colors"
                                data-name="{{ strtolower($guest->name) }}"
                                data-room="{{ strtolower($guest->room_number) }}"
                                data-mobile="{{ strtolower($guest->mobile_number) }}"
                                data-status="{{ $statusKey }}">
                                
                                <!-- Guest Avatar & Name -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#150A42] to-purple-700 text-white font-black flex items-center justify-center text-xs shadow-2xs shrink-0">
                                            {{ strtoupper(substr($guest->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-extrabold text-slate-900 text-sm block tracking-tight truncate">{{ $guest->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $guest->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Room Number Link -->
                                <td class="px-5 py-4">
                                    <a href="{{ route('hotel.devices.index') }}?room_no={{ urlencode($guest->room_number) }}" 
                                       title="View Room {{ $guest->room_number }} TV Details"
                                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-purple-50/80 hover:bg-purple-100 border border-purple-200/80 text-[#150A42] font-black text-xs transition-all shadow-2xs group">
                                        <i class="fa-solid fa-tv text-purple-600 text-xs group-hover:scale-110 transition-transform"></i>
                                        <span>Room {{ $guest->room_number }}</span>
                                    </a>
                                </td>

                                <!-- Mobile Number -->
                                <td class="px-5 py-4 font-mono font-medium text-slate-600">
                                    <span class="inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-phone text-[10px] text-slate-400"></i>
                                        <span>{{ $guest->mobile_number }}</span>
                                    </span>
                                </td>

                                <!-- Check-In -->
                                <td class="px-5 py-4 text-slate-600 font-medium">
                                    <div class="font-bold text-slate-800">{{ $guest->check_in_datetime->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-mono text-slate-400">{{ $guest->check_in_datetime->format('h:i A') }}</div>
                                </td>

                                <!-- Check-Out -->
                                <td class="px-5 py-4 text-slate-600 font-medium">
                                    @if($guest->check_out_datetime)
                                        <div class="font-bold text-slate-800">{{ $guest->check_out_datetime->format('M d, Y') }}</div>
                                        <div class="text-[10px] font-mono text-slate-400">{{ $guest->check_out_datetime->format('h:i A') }}</div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Open Check-in</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full border text-[11px] font-bold {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                        <span>{{ $statusText }}</span>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center space-x-1.5">
                                        @if($statusKey !== 'checked_out')
                                            <form action="{{ route('hotel.guests.checkout', $guest->id) }}" method="POST" onsubmit="return confirm('Check out {{ $guest->name }} from Room {{ $guest->room_number }}?');" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        title="Check Out Guest"
                                                        class="px-3 py-1.5 rounded-xl border border-amber-200 text-amber-800 bg-amber-50 hover:bg-amber-100 font-bold text-xs transition-colors shadow-2xs active:scale-95 cursor-pointer">
                                                    <i class="fa-solid fa-right-from-bracket mr-1 text-[10px]"></i>
                                                    <span>Checkout</span>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" 
                                                onclick="openEditModal(this)" 
                                                data-guest="{{ json_encode($guest) }}" 
                                                title="Edit Guest Details"
                                                class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 flex items-center justify-center transition-all shadow-2xs cursor-pointer active:scale-95">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="{{ route('hotel.guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Permanently delete guest record for {{ $guest->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Delete Guest"
                                                    class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all shadow-2xs cursor-pointer active:scale-95">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyTableRow">
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-lg">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800">No Guests Registered Yet</h4>
                                    <p class="text-xs text-slate-500 mt-1">Click "Check In New Guest" to allocate a room and welcome screen.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Compact Cards View (2 Columns or Stacked on Mobile) -->
        <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3" id="mobileGuestCards">
            @forelse($guests as $guest)
                @php
                    if ($now->lt($guest->check_in_datetime)) {
                        $mStatusKey = 'scheduled';
                        $mStatusText = 'Scheduled';
                        $mStatusClass = 'bg-sky-50 text-sky-700 border-sky-200/70';
                        $mStatusDot = 'bg-sky-500';
                    } elseif ($guest->check_out_datetime && $now->gt($guest->check_out_datetime)) {
                        $mStatusKey = 'checked_out';
                        $mStatusText = 'Checked Out';
                        $mStatusClass = 'bg-slate-100 text-slate-600 border-slate-200/80';
                        $mStatusDot = 'bg-slate-400';
                    } else {
                        $mStatusKey = 'active';
                        $mStatusText = 'Active In-House';
                        $mStatusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                        $mStatusDot = 'bg-emerald-500 animate-pulse';
                    }
                @endphp
                <div class="guest-card bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs space-y-3"
                     data-name="{{ strtolower($guest->name) }}"
                     data-room="{{ strtolower($guest->room_number) }}"
                     data-mobile="{{ strtolower($guest->mobile_number) }}"
                     data-status="{{ $mStatusKey }}">
                    
                    <!-- Card Top Header -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#150A42] to-purple-700 text-white font-black flex items-center justify-center text-xs shadow-2xs shrink-0">
                                {{ strtoupper(substr($guest->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-slate-900 truncate leading-tight">{{ $guest->name }}</h4>
                                <span class="text-[10px] font-mono text-slate-400 block truncate">{{ $guest->mobile_number }}</span>
                            </div>
                        </div>

                        <!-- Room Link Badge -->
                        <a href="{{ route('hotel.devices.index') }}?room_no={{ urlencode($guest->room_number) }}" class="px-2.5 py-1 rounded-lg bg-purple-50 text-[#150A42] font-black text-[11px] border border-purple-200/80 flex items-center space-x-1 shrink-0">
                            <i class="fa-solid fa-tv text-[9px] text-purple-600"></i>
                            <span>R-{{ $guest->room_number }}</span>
                        </a>
                    </div>

                    <!-- Stay Timing Details -->
                    <div class="bg-slate-50 rounded-xl p-2.5 text-[11px] text-slate-600 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-[10px]">Check-In:</span>
                            <span class="font-bold text-slate-800">{{ $guest->check_in_datetime->format('M d, h:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-[10px]">Check-Out:</span>
                            <span class="font-bold text-slate-800">{{ $guest->check_out_datetime ? $guest->check_out_datetime->format('M d, h:i A') : 'Open Check-in' }}</span>
                        </div>
                    </div>

                    <!-- Status & Actions Bottom Row -->
                    <div class="flex items-center justify-between pt-1 border-t border-slate-100 gap-2">
                        <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full border text-[10px] font-bold {{ $mStatusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $mStatusDot }}"></span>
                            <span>{{ $mStatusText }}</span>
                        </span>

                        <div class="flex items-center space-x-1.5">
                            @if($mStatusKey !== 'checked_out')
                                <form action="{{ route('hotel.guests.checkout', $guest->id) }}" method="POST" onsubmit="return confirm('Check out {{ $guest->name }}?');" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold">
                                        Checkout
                                    </button>
                                </form>
                            @endif
                            <button type="button" 
                                    onclick="openEditModal(this)" 
                                    data-guest="{{ json_encode($guest) }}" 
                                    class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:text-[#150A42] flex items-center justify-center">
                                <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                            </button>
                            <form action="{{ route('hotel.guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Delete guest?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center">
                                    <i class="fa-regular fa-trash-can text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 font-medium bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    No guests registered. Click "Check In New Guest".
                </div>
            @endforelse
        </div>

        <!-- Empty Search State -->
        <div id="noSearchMatches" class="hidden text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-lg">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="text-sm font-black text-slate-800">No matching guests found</h4>
            <p class="text-xs text-slate-500 mt-1">Try searching with a different guest name, room number, or mobile.</p>
        </div>
    </div>
</div>

<!-- Add Guest Check-In Modal (PaxTV Luxury Theme) -->
<div id="addGuestModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white border border-slate-200 rounded-[28px] w-full max-w-lg p-5 sm:p-7 space-y-5 shadow-2xl animate-in fade-in duration-200 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-purple-100/50 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">New Guest Check-In</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Assign room number & enable Smart TV personalization</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="addGuestForm" action="{{ route('hotel.guests.store') }}" method="POST" class="space-y-4 relative z-10" data-swal-bypass="true" data-ajax-form="true">
            @csrf

            <!-- Guest Name -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                    <i class="fa-solid fa-user text-purple-600 text-[10px]"></i>
                    <span>Guest Full Name *</span>
                </label>
                <input type="text" 
                       name="name" 
                       required 
                       placeholder="e.g. John Doe, Mr. Sharma" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
            </div>

            <!-- Mobile & Room Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-phone text-purple-600 text-[10px]"></i>
                        <span>Mobile Number *</span>
                    </label>
                    <input type="text" 
                           name="mobile_number" 
                           required 
                           placeholder="+91 98765 43210" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-door-open text-purple-600 text-[10px]"></i>
                        <span>Room Number *</span>
                    </label>
                    <input type="text" 
                           name="room_number" 
                           list="pairedRoomsList"
                           required 
                           placeholder="e.g. 101, 204" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                    <datalist id="pairedRoomsList">
                        @if(isset($hotelRooms))
                            @foreach($hotelRooms as $r)
                                <option value="{{ $r }}">Room {{ $r }}</option>
                            @endforeach
                        @endif
                    </datalist>
                </div>
            </div>

            <!-- Datetime Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-regular fa-calendar-check text-purple-600 text-[10px]"></i>
                        <span>Check-In Datetime *</span>
                    </label>
                    <input type="datetime-local" 
                           name="check_in_datetime" 
                           required 
                           value="{{ date('Y-m-d\TH:i') }}" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                        <span>Check-Out (Optional)</span>
                    </label>
                    <input type="datetime-local" 
                           name="check_out_datetime" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>
            </div>

            <!-- TV Screen Live Welcome Note -->
            <div class="p-3 bg-purple-50/70 border border-purple-200/70 rounded-2xl text-[11px] text-purple-900 flex items-center space-x-2.5">
                <i class="fa-solid fa-tv text-purple-600 text-sm shrink-0"></i>
                <span>Smart TV in this room will automatically display a personalized <strong>"Welcome, [Guest Name]"</strong> greeting screen.</span>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="addGuestBtn" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-check text-xs text-amber-300"></i>
                    <span>Register Guest</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Guest Modal (PaxTV Luxury Theme) -->
<div id="editGuestModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white border border-slate-200 rounded-[28px] w-full max-w-lg p-5 sm:p-7 space-y-5 shadow-2xl animate-in fade-in duration-200 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-purple-100/50 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Edit Guest Details</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Update stay duration, phone, or assigned room number</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editGuestForm" method="POST" class="space-y-4 relative z-10" data-swal-bypass="true" data-ajax-form="true">
            @csrf
            @method('PUT')

            <!-- Guest Name -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                    <i class="fa-solid fa-user text-purple-600 text-[10px]"></i>
                    <span>Guest Full Name *</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="editName" 
                       required 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
            </div>

            <!-- Mobile & Room Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-phone text-purple-600 text-[10px]"></i>
                        <span>Mobile Number *</span>
                    </label>
                    <input type="text" 
                       name="mobile_number" 
                       id="editMobile" 
                       required 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-door-open text-purple-600 text-[10px]"></i>
                        <span>Room Number *</span>
                    </label>
                    <input type="text" 
                           name="room_number" 
                           id="editRoom" 
                           list="pairedRoomsList"
                           required 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>
            </div>

            <!-- Datetime Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-regular fa-calendar-check text-purple-600 text-[10px]"></i>
                        <span>Check-In Datetime *</span>
                    </label>
                    <input type="datetime-local" 
                           name="check_in_datetime" 
                           id="editCheckIn" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center space-x-1.5">
                        <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                        <span>Check-Out Datetime</span>
                    </label>
                    <input type="datetime-local" 
                           name="check_out_datetime" 
                           id="editCheckOut" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/90 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="editGuestBtn" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs flex items-center space-x-2 shadow-lg shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() {
        document.body.style.overflow = 'hidden';
        document.getElementById('addGuestModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.body.style.overflow = '';
        document.getElementById('addGuestModal').classList.add('hidden');
        document.getElementById('addGuestForm').reset();
    }

    function openEditModal(btn) {
        try {
            const guest = JSON.parse(btn.getAttribute('data-guest'));
            document.getElementById('editGuestForm').action = `/hotel/guests/${guest.id}`;
            document.getElementById('editName').value = guest.name || '';
            document.getElementById('editMobile').value = guest.mobile_number || '';
            document.getElementById('editRoom').value = guest.room_number || '';
            
            if (guest.check_in_datetime) {
                document.getElementById('editCheckIn').value = guest.check_in_datetime.replace(' ', 'T').substring(0, 16);
            }
            if (guest.check_out_datetime) {
                document.getElementById('editCheckOut').value = guest.check_out_datetime.replace(' ', 'T').substring(0, 16);
            } else {
                document.getElementById('editCheckOut').value = '';
            }
            
            document.body.style.overflow = 'hidden';
            document.getElementById('editGuestModal').classList.remove('hidden');
        } catch (e) {
            console.error('Error opening edit modal:', e);
        }
    }

    function closeEditModal() {
        document.body.style.overflow = '';
        document.getElementById('editGuestModal').classList.add('hidden');
    }

    // Live Status Filter
    let activeStatusFilter = 'all';
    function filterGuestStatus(status, button) {
        activeStatusFilter = status;
        document.querySelectorAll('.guest-filter-btn').forEach(btn => {
            btn.className = 'guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-all whitespace-nowrap';
        });
        button.className = 'guest-filter-btn px-3 py-1.5 rounded-xl text-[11px] font-black transition-all bg-white text-[#150A42] shadow-2xs whitespace-nowrap';
        applyGuestFilters();
    }

    // Live Search & Filtering
    document.getElementById('guestSearchInput')?.addEventListener('input', function() {
        applyGuestFilters();
    });

    function applyGuestFilters() {
        const query = (document.getElementById('guestSearchInput')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.guest-row');
        const cards = document.querySelectorAll('.guest-card');

        let matchCount = 0;

        function checkMatch(el) {
            const name = el.getAttribute('data-name') || '';
            const room = el.getAttribute('data-room') || '';
            const mobile = el.getAttribute('data-mobile') || '';
            const status = el.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || room.includes(query) || mobile.includes(query);
            const matchesStatus = (activeStatusFilter === 'all') || (status === activeStatusFilter);

            return matchesQuery && matchesStatus;
        }

        rows.forEach(row => {
            if (checkMatch(row)) {
                row.style.display = '';
                matchCount++;
            } else {
                row.style.display = 'none';
            }
        });

        cards.forEach(card => {
            if (checkMatch(card)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        const noMatchesBox = document.getElementById('noSearchMatches');
        if (noMatchesBox) {
            noMatchesBox.style.display = matchCount === 0 && (rows.length > 0) ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const Toast = typeof Swal !== 'undefined' ? Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        }) : null;

        // Add Guest AJAX Submit
        const addForm = document.getElementById('addGuestForm');
        if (addForm) {
            addForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('addGuestBtn');
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-xs"></i><span>Registering...</span>';

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    });
                    const result = await response.json();
                    if (response.ok && (result.status === 'success' || result.success)) {
                        closeAddModal();
                        if (Toast) {
                            Toast.fire({ icon: 'success', title: result.message || 'Guest Checked In & Synced to TV!' });
                        }
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        if (Toast) {
                            Toast.fire({ icon: 'error', title: result.message || 'Check-in failed.' });
                        } else {
                            alert(result.message || 'Check-in failed.');
                        }
                    }
                } catch (err) {
                    if (Toast) Toast.fire({ icon: 'error', title: 'Network error. Please try again.' });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            });
        }

        // Edit Guest AJAX Submit
        const editForm = document.getElementById('editGuestForm');
        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('editGuestBtn');
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-xs"></i><span>Saving...</span>';

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    });
                    const result = await response.json();
                    if (response.ok && (result.status === 'success' || result.success)) {
                        closeEditModal();
                        if (Toast) {
                            Toast.fire({ icon: 'success', title: result.message || 'Guest Details Updated & Synced to TV!' });
                        }
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        if (Toast) {
                            Toast.fire({ icon: 'error', title: result.message || 'Update failed.' });
                        } else {
                            alert(result.message || 'Update failed.');
                        }
                    }
                } catch (err) {
                    if (Toast) Toast.fire({ icon: 'error', title: 'Network error. Please try again.' });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            });
        }
    });
</script>
@endsection
