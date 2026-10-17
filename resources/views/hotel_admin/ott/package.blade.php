@extends('layouts.hotel_admin')

@section('title', 'My Package - Hotel Admin')
@section('page_title', 'My Subscription Package')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if($hotel->payment_status !== 'paid' || !$plan || ($hotel->expiry_date && $hotel->expiry_date->isPast()))
        <!-- Unpaid / Inactive Alert Banner -->
        <div class="p-6 rounded-2xl bg-amber-50 border-2 border-amber-300/80 text-amber-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-start space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-200/80 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-extrabold text-amber-900">Subscription Inactive — Payment Required</h4>
                    <p class="text-xs text-amber-800/90 leading-relaxed">
                        Your hotel account is currently in <strong>Unpaid / Inactive</strong> status. TV devices cannot connect and panel features remain locked until payment is completed.
                    </p>
                </div>
            </div>
            @if($hotel->distributor)
                <div class="text-left sm:text-right shrink-0 bg-white/70 px-4 py-2 rounded-xl border border-amber-200">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Assigned Distributor</span>
                    <span class="text-xs font-extrabold text-amber-950">{{ $hotel->distributor->name }}</span>
                    <p class="text-[11px] text-amber-800 font-semibold">{{ $hotel->distributor->phone ?? $hotel->distributor->email }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Active Plan Header Card -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[#E2E8F0] pb-6">
            <div class="space-y-1">
                @if($hotel->payment_status !== 'paid')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#DC2626] animate-pulse mr-1.5"></span> Unpaid / Inactive
                    </span>
                @elseif(!$plan)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 border border-slate-300 text-slate-700 text-xs font-bold uppercase tracking-wider">
                        No Active Plan
                    </span>
                @elseif($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#DC2626] mr-1.5"></span> Plan Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#DCFCE7] border border-[#16A34A]/20 text-[#16A34A] text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse mr-1.5"></span> Active Plan
                    </span>
                @endif
                <h2 class="text-2xl font-extrabold text-[#1E293B] tracking-tight">{{ $plan ? $plan->name : 'No Active Plan' }}</h2>
                <p class="text-[#64748B] text-xs font-medium">{{ $plan->description ?? 'Standard system licensing plan assigned by Super Admin.' }}</p>
            </div>
            
            <div class="text-left sm:text-right">
                <div class="text-3xl font-extrabold text-[#6E1841]">
                    ₹{{ $plan ? number_format($plan->price, 0) : '0' }}<span class="text-xs text-[#64748B] font-normal"> /month</span>
                </div>
                <p class="text-xs text-[#64748B] font-medium mt-1">Allowed Devices Limit: <strong class="text-[#1E293B]">{{ $hotel->allowed_device_limit }} TVs</strong></p>
            </div>
        </div>

        <!-- Plan Timestamps & Expiry Information -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div class="bg-[#F9F5EB] border border-[#C5A059]/20 rounded-xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Purchase Date</span>
                <p class="text-sm font-extrabold text-[#1E293B]">
                    <i class="fa-regular fa-calendar-check mr-1.5 text-[#C5A059]"></i>
                    {{ $hotel->purchase_date ? $hotel->purchase_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>

            <div class="bg-[#F9F5EB] border border-[#C5A059]/20 rounded-xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Expiry Date</span>
                <p class="text-sm font-extrabold text-[#1E293B]">
                    <i class="fa-regular fa-clock mr-1.5 text-[#EA580C]"></i>
                    {{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>

            <div class="bg-[#F9F5EB] border border-[#C5A059]/20 rounded-xl p-4 space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Subscription Status</span>
                @if($hotel->payment_status !== 'paid')
                    <p class="text-sm font-extrabold text-rose-600">
                        <i class="fa-solid fa-lock mr-1.5"></i> Unpaid (Locked)
                    </p>
                @elseif(!$plan)
                    <p class="text-sm font-extrabold text-slate-500">
                        <i class="fa-solid fa-circle-minus mr-1.5"></i> No Plan
                    </p>
                @elseif($hotel->expiry_date && $hotel->expiry_date->isPast())
                    <p class="text-sm font-extrabold text-rose-600">
                        <i class="fa-solid fa-circle-exclamation mr-1.5"></i> Expired
                    </p>
                @elseif($hotel->expiry_date)
                    <p class="text-sm font-extrabold text-emerald-600">
                        <i class="fa-solid fa-shield-check mr-1.5"></i> {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} Days Remaining
                    </p>
                @else
                    <p class="text-sm font-extrabold text-[#64748B]">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <!-- OTT Platforms List -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-[#E2E8F0] pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-extrabold text-[#1E293B] tracking-tight">Included OTT Streaming Apps</h3>
                <p class="text-xs text-[#64748B] font-medium">Platforms active under your current hotel subscription tier.</p>
            </div>
            <a href="{{ route('hotel.ott-settings') }}" class="px-4 py-2.5 rounded-xl border border-[#CBD5E1] text-[#1E293B] hover:bg-[#F8FAFC] font-bold text-xs transition-colors flex items-center space-x-2">
                <i class="fa-solid fa-sliders text-[#C5A059]"></i>
                <span>Configure Global Visibility</span>
            </a>
        </div>

        @if(count($assignedPlatforms) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assignedPlatforms as $ott)
                    <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-play"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-xs font-bold text-[#1E293B]">{{ $ott['name'] }}</h4>
                            <span class="text-[10px] font-mono text-[#64748B] truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-[#64748B] space-y-2">
                <i class="fa-solid fa-tv text-4xl text-[#CBD5E1] block"></i>
                <p class="text-xs font-medium">No OTT platforms enabled in your plan yet. Contact Super Admin to upgrade.</p>
            </div>
        @endif
    </div>
</div>
@endsection
