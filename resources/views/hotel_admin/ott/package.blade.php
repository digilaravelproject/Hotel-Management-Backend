@extends('layouts.hotel_admin')

@section('title', 'My Package - Hotel Admin')
@section('page_title', 'My Subscription Package')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Active Plan Header Card -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-6">
            <div class="space-y-1">
                @if($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse mr-1.5"></span> Plan Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-1.5"></span> Active Plan
                    </span>
                @endif
                <h2 class="text-2xl font-extrabold text-white tracking-tight">{{ $plan ? $plan->name : 'No Active Plan' }}</h2>
                <p class="text-slate-400 text-xs font-medium">{{ $plan->description ?? 'Standard system licensing plan assigned by Super Admin.' }}</p>
            </div>
            
            <div class="text-left sm:text-right">
                <div class="text-3xl font-extrabold text-indigo-400">
                    ₹{{ $plan ? number_format($plan->price, 0) : '0' }}<span class="text-xs text-slate-400 font-normal"> /month</span>
                </div>
                <p class="text-xs text-slate-400 font-medium mt-1">Allowed Devices Limit: <strong class="text-white">{{ $hotel->allowed_device_limit }} TVs</strong></p>
            </div>
        </div>

        <!-- Plan Timestamps & Expiry Information -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Purchase Date</span>
                <p class="text-sm font-extrabold text-white">
                    <i class="fa-regular fa-calendar-check mr-1.5 text-indigo-400"></i>
                    {{ $hotel->purchase_date ? $hotel->purchase_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expiry Date</span>
                <p class="text-sm font-extrabold text-white">
                    <i class="fa-regular fa-clock mr-1.5 text-amber-400"></i>
                    {{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Subscription Status</span>
                @if($hotel->expiry_date)
                    @if($hotel->expiry_date->isPast())
                        <p class="text-sm font-extrabold text-rose-400">
                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i> Expired
                        </p>
                    @else
                        <p class="text-sm font-extrabold text-emerald-400">
                            <i class="fa-solid fa-shield-check mr-1.5"></i> {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} Days Remaining
                        </p>
                    @endif
                @else
                    <p class="text-sm font-extrabold text-slate-400">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <!-- OTT Platforms List -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Included OTT Streaming Apps</h3>
                <p class="text-xs text-slate-500 font-medium">Platforms active under your current hotel subscription tier.</p>
            </div>
            <a href="{{ route('hotel.ott-settings') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition-colors flex items-center space-x-2">
                <i class="fa-solid fa-sliders text-indigo-600"></i>
                <span>Configure Global Visibility</span>
            </a>
        </div>

        @if(count($assignedPlatforms) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assignedPlatforms as $ott)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-xs font-bold text-slate-800">{{ $ott['name'] }}</h4>
                            <span class="text-[10px] font-mono text-slate-400 truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-slate-400 space-y-2">
                <i class="fa-solid fa-tv text-4xl text-slate-300 block"></i>
                <p class="text-xs font-medium">No OTT platforms enabled in your plan yet. Contact Super Admin to upgrade.</p>
            </div>
        @endif
    </div>
</div>
@endsection
