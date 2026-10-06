@extends('layouts.distributor')

@section('title', 'My Hotels - Distributor Hub')
@section('page_title', 'Onboarded Hotels Directory')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">Hotels registered directly under your distributor account. Manage their licenses, room limits, and packages.</p>
        </div>
        <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Onboard New Hotel</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by hotel name, owner, license key, or email..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
            </div>
            <button type="submit" class="py-2 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Search</span>
            </button>
            @if(request('search'))
                <a href="{{ route('distributor.hotels.index') }}" class="py-2 px-3 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-bold text-xs transition-all" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Hotels Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Hotel Property</th>
                        <th class="px-6 py-4">Owner & Contact</th>
                        <th class="px-6 py-4">Rooms</th>
                        <th class="px-6 py-4">Active Plan</th>
                        <th class="px-6 py-4">TV License Key</th>
                        <th class="px-6 py-4">Expiry Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hotels as $hotel)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        <i class="fa-solid fa-hotel"></i>
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $hotel->hotel_name }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">
                                            <i class="fa-solid fa-location-dot mr-1"></i>{{ $hotel->hotel_location }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-bold text-slate-800">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $hotel->email }}</div>
                                <div class="text-[11px] text-slate-400">{{ $hotel->phone }}</div>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-900">
                                {{ $hotel->room_count }} Rooms
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->plan)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        <i class="fa-solid fa-layer-group mr-1 text-[9px]"></i> {{ $hotel->plan->name }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                        No Plan Assigned
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 font-mono text-[11px] font-extrabold text-slate-800 tracking-wider">
                                    {{ $hotel->license_key }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->expiry_date)
                                    @php
                                        $isExpired = \Carbon\Carbon::parse($hotel->expiry_date)->isPast();
                                    @endphp
                                    <span class="text-xs font-bold {{ $isExpired ? 'text-rose-600' : 'text-slate-700' }}">
                                        {{ \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') }}
                                    </span>
                                    @if($isExpired)
                                        <div class="text-[10px] text-rose-500 font-bold">Expired</div>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->status)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-xs transition-all inline-flex items-center space-x-1.5">
                                    <i class="fa-solid fa-box-open text-[10px]"></i>
                                    <span>Sell Package</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                                        <i class="fa-solid fa-hotel text-xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">No hotels found under your account</p>
                                    <p class="text-xs text-slate-400">Get started by onboarding a new partner hotel.</p>
                                    <div class="pt-2">
                                        <a href="{{ route('distributor.hotels.create') }}" class="px-4 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs">
                                            + Onboard Hotel Now
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hotels->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
