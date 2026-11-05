@extends('layouts.distributor')

@section('title', 'My Hotels - Distributor Hub')
@section('page_title', 'Partner Hotels Directory')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Button (1-line responsive) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Onboarded Hotels</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Hotels registered directly under your distributor account. Manage their licenses, room limits, and packages.</p>
        </div>
        <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 shrink-0 cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Onboard New Hotel</span>
        </a>
    </div>

    <!-- Modern Search Bar -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-4 sm:p-5 shadow-sm">
        <form method="GET" action="{{ route('distributor.hotels.index') }}" class="flex items-center space-x-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by hotel name, owner, license key, or email..." class="w-full pl-10 pr-4 py-2.5 text-xs rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] bg-slate-50/70 hover:bg-white focus:bg-white text-slate-800 font-semibold transition-all placeholder:text-slate-400">
            </div>
            <button type="submit" class="py-2.5 px-6 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs transition-all flex items-center space-x-1.5 cursor-pointer shadow-sm active:scale-95">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <span>Search</span>
            </button>
            @if(request('search'))
                <a href="{{ route('distributor.hotels.index') }}" class="py-2.5 px-4 rounded-2xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-xs transition-all flex items-center space-x-1 active:scale-95" title="Reset Search">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span class="hidden sm:inline">Reset</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Hotels Table Container -->
    <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800">
                <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[11px]">
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
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-hotel"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-sm">{{ $hotel->hotel_name }}</div>
                                        <div class="text-[11px] text-slate-400 font-semibold flex items-center space-x-1 mt-0.5">
                                            <i class="fa-solid fa-location-dot text-purple-600 text-[10px]"></i>
                                            <span>{{ $hotel->hotel_location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-bold text-slate-900">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $hotel->email }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $hotel->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-900 text-sm">{{ $hotel->room_count }}</span>
                                <span class="text-slate-400 text-[11px] font-semibold">Rooms</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->plan)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                                        <i class="fa-solid fa-layer-group mr-1.5 text-[9px] text-blue-600"></i>
                                        <span>{{ $hotel->plan->name }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        No Plan Assigned
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200/80 font-mono text-[11px] font-black text-slate-800 tracking-wider shadow-2xs">
                                    <span>{{ $hotel->license_key }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $hotel->license_key }}'); this.innerHTML='<i class=\'fa-solid fa-check text-emerald-600 text-[10px]\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy text-[10px]\'></i>', 1500);" class="text-slate-400 hover:text-slate-700 p-1" title="Copy License Key">
                                        <i class="fa-regular fa-copy text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->expiry_date)
                                    @php
                                        $isExpired = \Carbon\Carbon::parse($hotel->expiry_date)->isPast();
                                    @endphp
                                    <div class="text-xs font-black {{ $isExpired ? 'text-rose-600' : 'text-slate-900' }}">
                                        {{ \Carbon\Carbon::parse($hotel->expiry_date)->format('M d, Y') }}
                                    </div>
                                    @if($isExpired)
                                        <span class="inline-flex px-1.5 py-0.2 rounded-md bg-rose-50 text-rose-600 font-extrabold text-[9px] border border-rose-200 mt-0.5">Expired</span>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">Active License</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="px-3.5 py-1.5 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white border border-purple-200/80 font-black text-xs shadow-2xs transition-all inline-flex items-center space-x-1.5 active:scale-95">
                                    <i class="fa-solid fa-box-open text-[10px]"></i>
                                    <span>Sell Package</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-14">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-hotel"></i>
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">No hotels found under your account</p>
                                    <p class="text-xs text-slate-400">Get started by onboarding a new partner hotel property.</p>
                                    <div class="pt-3">
                                        <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 inline-flex items-center space-x-1.5 active:scale-95">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Onboard Hotel Now</span>
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
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
