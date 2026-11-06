@extends('layouts.hotel_admin')

@section('title', 'My Package & OTT Apps - Hotel Admin')
@section('page_title', 'My Subscription Package')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Bar Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.dashboard') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Subscription Package & OTT</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Active Plan & OTT Streaming Platforms</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage streaming applications permitted on your property's Smart TV screens.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.subscription') }}" class="px-4 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-credit-card text-xs text-amber-300"></i>
                <span>Upgrade / Renew</span>
            </a>
            <a href="{{ route('hotel.ott-settings') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-sliders text-purple-600 text-xs"></i>
                <span>Global TV Controls</span>
            </a>
        </div>
    </div>

    @if($hotel->payment_status !== 'paid' || !$plan || ($hotel->expiry_date && $hotel->expiry_date->isPast()))
        <!-- Unpaid / Inactive Alert Banner -->
        <div class="p-6 rounded-[28px] bg-amber-50 border-2 border-amber-300/80 text-amber-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-200/80 text-amber-900 flex items-center justify-center shrink-0 mt-0.5 shadow-2xs font-black text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-black text-amber-950">Subscription Inactive — Payment Required</h4>
                    <p class="text-xs text-amber-900/90 leading-relaxed font-medium">
                        Your hotel account is currently in <strong>Unpaid / Inactive</strong> status. TV devices cannot connect and panel features remain locked until payment is completed.
                    </p>
                </div>
            </div>
            @if($hotel->distributor)
                <div class="text-left sm:text-right shrink-0 bg-white/80 backdrop-blur-xs px-4 py-2.5 rounded-2xl border border-amber-200 shadow-2xs">
                    <span class="text-[10px] font-black text-amber-800 uppercase tracking-wider block font-mono">Assigned Distributor</span>
                    <span class="text-xs font-black text-amber-950">{{ $hotel->distributor->name }}</span>
                    <p class="text-[11px] text-amber-800 font-bold font-mono mt-0.5">{{ $hotel->distributor->phone ?? $hotel->distributor->email }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Active Plan Header Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div class="space-y-1.5">
                @if($hotel->payment_status !== 'paid')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black uppercase tracking-wider font-mono shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse mr-1.5"></span> Unpaid / Inactive
                    </span>
                @elseif(!$plan)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider font-mono shadow-2xs">
                        No Active Plan
                    </span>
                @elseif($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black uppercase tracking-wider font-mono shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-rose-600 mr-1.5"></span> Plan Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-black uppercase tracking-wider font-mono shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse mr-1.5"></span> Active Plan
                    </span>
                @endif
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $plan ? $plan->name : 'No Active Plan' }}</h2>
                <p class="text-slate-400 text-xs font-medium">{{ $plan->description ?? 'Standard system licensing plan assigned by Super Admin.' }}</p>
            </div>
            
            <div class="text-left sm:text-right">
                <div class="text-3xl font-black font-mono text-[#150A42]">
                    ₹{{ $plan ? number_format($plan->price, 0) : '0' }}<span class="text-xs text-slate-400 font-bold font-sans"> /month</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Allowed Devices Limit: <strong class="text-purple-700 font-black font-mono">{{ $hotel->allowed_device_limit }} TVs</strong></p>
            </div>
        </div>

        <!-- Plan Timestamps & Expiry Information -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
            <div class="bg-[#F8F9FD] border border-slate-200/80 rounded-2xl p-4.5 space-y-1 shadow-2xs">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block font-mono">Purchase Date</span>
                <p class="text-sm font-black text-slate-900 flex items-center">
                    <i class="fa-regular fa-calendar-check mr-2 text-purple-600"></i>
                    <span>{{ $hotel->purchase_date ? $hotel->purchase_date->format('d M, Y') : 'N/A' }}</span>
                </p>
            </div>

            <div class="bg-[#F8F9FD] border border-slate-200/80 rounded-2xl p-4.5 space-y-1 shadow-2xs">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block font-mono">Expiry Date</span>
                <p class="text-sm font-black text-slate-900 flex items-center">
                    <i class="fa-regular fa-clock mr-2 text-amber-600"></i>
                    <span>{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}</span>
                </p>
            </div>

            <div class="bg-[#F8F9FD] border border-slate-200/80 rounded-2xl p-4.5 space-y-1 shadow-2xs">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block font-mono">Subscription Status</span>
                @if($hotel->payment_status !== 'paid')
                    <p class="text-sm font-black text-rose-600 flex items-center">
                        <i class="fa-solid fa-lock mr-2"></i> Unpaid (Locked)
                    </p>
                @elseif(!$plan)
                    <p class="text-sm font-black text-slate-500 flex items-center">
                        <i class="fa-solid fa-circle-minus mr-2"></i> No Plan
                    </p>
                @elseif($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <p class="text-sm font-black text-rose-600 flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> Expired
                    </p>
                @elseif($hotel->expiry_date)
                    <p class="text-sm font-black text-emerald-700 flex items-center">
                        <i class="fa-solid fa-shield-check mr-2 text-emerald-600"></i> {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} Days Remaining
                    </p>
                @else
                    <p class="text-sm font-black text-slate-500">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <!-- OTT Platforms List -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Included OTT Streaming Apps</h3>
                <p class="text-xs text-slate-400 font-medium">Platforms active under your current hotel subscription tier.</p>
            </div>
            <a href="{{ route('hotel.ott-settings') }}" class="px-4 py-2.5 rounded-2xl bg-purple-50 text-[#150A42] hover:bg-purple-100 border border-purple-200/80 font-black text-xs transition-colors flex items-center space-x-2 shadow-2xs cursor-pointer active:scale-95">
                <i class="fa-solid fa-sliders text-purple-600"></i>
                <span>Configure Global Visibility</span>
            </a>
        </div>

        @if(count($assignedPlatforms) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assignedPlatforms as $ott)
                    <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 flex items-center space-x-3.5 shadow-2xs hover:border-purple-300 transition-all">
                        <div class="w-11 h-11 rounded-2xl bg-purple-100 text-[#150A42] flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div class="overflow-hidden min-w-0">
                            <h4 class="text-xs font-black text-slate-900 truncate">{{ $ott['name'] }}</h4>
                            <span class="text-[10px] font-mono text-slate-400 truncate block mt-0.5" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-slate-400 space-y-2 bg-[#F8F9FD] rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-tv text-4xl text-slate-300 block"></i>
                <p class="text-xs font-bold text-slate-700">No OTT platforms enabled in your plan yet.</p>
                <p class="text-[11px] text-slate-400">Contact your distributor or Pax TV support to upgrade package.</p>
            </div>
        @endif
    </div>
</div>
@endsection
