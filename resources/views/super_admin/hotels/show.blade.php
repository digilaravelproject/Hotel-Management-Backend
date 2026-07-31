@extends('layouts.super_admin')

@section('title', 'Hotel Vendor Details - Super Admin')
@section('page_title', 'Hotel Client Overview')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Hotels List
        </a>
        <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit Vendor Account</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-8">
        <!-- Header Profile -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-6 gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $hotel->hotel_name }}</h1>
                <p class="text-xs font-medium text-slate-500"><i class="fa-solid fa-location-dot mr-1 text-rose-500"></i>{{ $hotel->hotel_location }}</p>
            </div>
            <div class="flex items-center space-x-2">
                @if($hotel->approval_status === 'approved')
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase">Approved</span>
                @elseif($hotel->approval_status === 'disapproved')
                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold uppercase">Disapproved</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase">Pending Approval</span>
                @endif

                @if($hotel->status)
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase">Active</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold uppercase">Suspended</span>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div class="space-y-3 bg-slate-50 border border-slate-200/80 p-5 rounded-2xl">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Owner Credentials</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Owner Name:</span>
                        <span class="font-bold text-slate-900">{{ $hotel->owner_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Email Address:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $hotel->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Phone Line:</span>
                        <span class="font-bold text-slate-900">{{ $hotel->phone }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3 bg-slate-50 border border-slate-200/80 p-5 rounded-2xl">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Licensing & Subscription</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Room / TV Limit:</span>
                        <span class="font-bold text-slate-900">{{ $hotel->room_count }} TVs</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Active Plan:</span>
                        <span class="font-bold text-indigo-600">{{ $hotel->plan->name ?? 'None' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Payment Status:</span>
                        <span class="font-bold {{ $hotel->payment_status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $hotel->payment_status === 'paid' ? 'Paid (Razorpay)' : 'Unpaid' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- License Key Box -->
        <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 text-white space-y-2">
            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Connected TV License Key</h4>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                @if($hotel->license_key)
                    <div class="font-mono text-xl sm:text-2xl font-extrabold text-rose-400 tracking-wider">
                        {{ $hotel->license_key }}
                    </div>
                @else
                    <span class="text-xs text-slate-500 italic">No key generated yet. Complete payment first.</span>
                @endif
                <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold">
                    Valid for {{ $hotel->room_count }} TV Connections
                </span>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 font-medium gap-2">
            <span>Registered on: {{ $hotel->created_at->format('d M, Y H:i') }}</span>
            <span>Last Updated: {{ $hotel->updated_at->format('d M, Y H:i') }}</span>
        </div>
    </div>
</div>
@endsection
