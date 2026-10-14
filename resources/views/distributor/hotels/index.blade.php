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
        <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-xl bg-[#E5A93C] hover:bg-[#C88E26] text-white font-extrabold text-xs shadow-md shadow-[#E5A93C]/20 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Onboard New Hotel</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#64748B] text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by hotel name, owner, license key, or email..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:outline-none focus:ring-2 focus:ring-[#E5A93C]/30 focus:border-[#E5A93C] bg-[#F8FAFC] text-[#1E293B]">
            </div>
            <button type="submit" class="py-2 px-5 rounded-xl bg-[#004B87] hover:bg-[#0056B3] text-white font-bold text-xs transition-all flex items-center space-x-1.5 cursor-pointer">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Search</span>
            </button>
            @if(request('search'))
                <a href="{{ route('distributor.hotels.index') }}" class="py-2 px-3 rounded-xl border border-[#CBD5E1] text-[#64748B] hover:text-[#1E293B] hover:bg-[#F8FAFC] font-bold text-xs transition-all" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Hotels Table Container -->
    <div class="bg-white border border-[#E2E8F0] rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#1E293B]">
                <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
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
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($hotels as $hotel)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/20 text-[#E5A93C] flex items-center justify-center font-bold text-sm shrink-0">
                                        <i class="fa-solid fa-hotel"></i>
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-[#1E293B] text-sm">{{ $hotel->hotel_name }}</div>
                                        <div class="text-[11px] text-[#64748B] font-medium">
                                            <i class="fa-solid fa-location-dot mr-1 text-[#E5A93C]"></i>{{ $hotel->hotel_location }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-bold text-[#1E293B]">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-[#64748B] font-mono">{{ $hotel->email }}</div>
                                <div class="text-[11px] text-[#64748B]">{{ $hotel->phone }}</div>
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A] mr-1.5"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#CBD5E1] mr-1.5"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="px-3 py-1.5 rounded-xl bg-[#E5A93C] hover:bg-[#C88E26] text-white font-extrabold text-xs shadow-xs transition-all inline-flex items-center space-x-1.5">
                                    <i class="fa-solid fa-box-open text-[10px]"></i>
                                    <span>Sell Package</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-2 text-[#64748B]">
                                    <div class="w-12 h-12 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/20 flex items-center justify-center text-[#E5A93C]">
                                        <i class="fa-solid fa-hotel text-xl"></i>
                                    </div>
                                    <p class="font-bold text-[#1E293B] text-sm">No hotels found under your account</p>
                                    <p class="text-xs text-[#64748B]">Get started by onboarding a new partner hotel.</p>
                                    <div class="pt-2">
                                        <a href="{{ route('distributor.hotels.create') }}" class="px-4 py-2 rounded-xl bg-[#E5A93C] hover:bg-[#C88E26] text-white font-bold text-xs shadow-xs">
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
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
