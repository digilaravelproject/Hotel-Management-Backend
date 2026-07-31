@extends('layouts.super_admin')

@section('title', 'Super Admin Control Center')
@section('page_title', 'Platform Control Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-white p-8 md:p-10 border border-slate-800 shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold">
                    <i class="fa-solid fa-shield-halved mr-1 text-xs"></i> Super Admin Master Control
                </span>
                <h2 class="text-3xl font-extrabold tracking-tight">System Global Metrics Overview</h2>
                <p class="text-slate-400 text-xs font-medium">Monitor active hotels, subscriptions, OTA updates and TV network health.</p>
            </div>
            
            <a href="{{ route('super-admin.hotels.create') }}" class="px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-1.5"></i> Onboard New Hotel
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Hotels</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-hotel text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalHotels ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Registered Clients</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Devices</span>
                <div class="w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                    <i class="fa-solid fa-tv text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalDevices ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Synchronized Smart TVs</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Plans</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-layer-group text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalPlans ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Tier Packages</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">OTA Releases</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-code-branch text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalTemplates ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">APK / ZIP Builds Published</p>
            </div>
        </div>
    </div>

    <!-- Quick Directory Actions Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('super-admin.hotels.index') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-rose-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-600/10 text-rose-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-rose-600 transition-colors">Hotels Directory</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Approve, edit license keys, room counts, and expiration dates.</p>
            </div>
        </a>

        <a href="{{ route('super-admin.plans.index') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-indigo-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 text-indigo-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Subscription Plans</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Create and manage pricing plans, feature limits and OTT bundles.</p>
            </div>
        </a>

        <a href="{{ route('super-admin.tv-templates.index') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-code-branch"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">TV App OTA Releases</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Upload new template zip builds for automatic over-the-air updates.</p>
            </div>
        </a>
    </div>
</div>
@endsection
