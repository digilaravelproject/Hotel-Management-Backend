@extends('layouts.super_admin')

@section('title', 'Hotel Vendor Details - Super Admin')
@section('page_title', 'Hotel Client Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('super-admin.hotels.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs">
            <i class="fa-solid fa-arrow-left mr-2 text-rose-500"></i> Back to Hotels Directory
        </a>
        <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition-all hover:-translate-y-0.5">
            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Vendor Account
        </a>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xl shadow-slate-100/50">
        <!-- Hero Branding Header -->
        <div class="relative bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-8 sm:p-10 text-white overflow-hidden border-b border-slate-800">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 overflow-hidden shrink-0 flex items-center justify-center p-2 shadow-2xl">
                        @if($hotel->hotel_logo)
                            <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-hotel text-2xl text-rose-400"></i>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $hotel->hotel_name }}</h1>
                        <p class="text-slate-400 text-xs font-medium flex items-center">
                            <i class="fa-solid fa-location-dot mr-1.5 text-rose-400"></i>
                            {{ $hotel->hotel_location }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                    @if($hotel->approval_status === 'approved')
                        <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-circle-check mr-1.5 text-emerald-400"></i> Approved
                        </span>
                    @elseif($hotel->approval_status === 'disapproved')
                        <span class="px-3.5 py-1.5 rounded-full bg-rose-500/20 border border-rose-500/30 text-rose-300 text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-circle-xmark mr-1.5 text-rose-400"></i> Disapproved
                        </span>
                    @else
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-clock mr-1.5 text-amber-400"></i> Pending
                        </span>
                    @endif

                    @if($hotel->status)
                        <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            Active
                        </span>
                    @else
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-500/20 border border-slate-500/30 text-slate-400 text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            Suspended
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Content -->
        <div class="p-6 sm:p-10 space-y-8">
            <!-- 2-Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Owner Credentials Box -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center font-bold">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Owner Credentials</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold">Full Name</span>
                            <span class="font-extrabold text-slate-900">{{ $hotel->owner_name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold">Email Address</span>
                            <span class="font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100">{{ $hotel->email }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-semibold">Phone Line</span>
                            <span class="font-bold text-slate-900">{{ $hotel->phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Licensing & Subscription Box -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-layer-group text-sm"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Licensing & Subscription</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold">Room / TV Limit</span>
                            <span class="font-extrabold text-slate-900 bg-slate-200/70 px-2.5 py-0.5 rounded-md">{{ $hotel->room_count }} TVs Authorized</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold">Active Plan Tier</span>
                            <span class="font-extrabold text-indigo-600">{{ $hotel->plan->name ?? 'None' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-semibold">Payment State</span>
                            <span class="font-extrabold {{ $hotel->payment_status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $hotel->payment_status === 'paid' ? 'Paid (Razorpay)' : 'Unpaid' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- License Key Callout Box -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-6 sm:p-8 text-white border border-slate-800 shadow-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-[11px] font-bold text-rose-400 uppercase tracking-widest block">Connected TV License Key</span>
                        @if($hotel->license_key)
                            <div class="font-mono text-2xl sm:text-3xl font-extrabold text-white tracking-widest drop-shadow-md">
                                {{ $hotel->license_key }}
                            </div>
                        @else
                            <span class="text-xs text-slate-500 italic">No key generated yet. Complete payment first.</span>
                        @endif
                    </div>
                    
                    <span class="px-3.5 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs font-bold">
                        <i class="fa-solid fa-tv mr-1"></i> Valid for {{ $hotel->room_count }} TV Connections
                    </span>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 font-medium gap-2">
                <span><i class="fa-regular fa-calendar mr-1"></i> Registered: {{ $hotel->created_at->format('d M, Y H:i') }}</span>
                <span><i class="fa-solid fa-clock-rotate-left mr-1"></i> Updated: {{ $hotel->updated_at->format('d M, Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
