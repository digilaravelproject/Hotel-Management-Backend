@extends('layouts.hotel_admin')

@section('title', 'Hotel Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 text-white p-8 md:p-10 shadow-xl shadow-indigo-600/20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_50%)] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Live Hotel System</span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Welcome back, {{ Auth::guard('hotel_admin')->user()->owner_name }}! 👋</h2>
                <p class="text-indigo-100 text-sm font-medium leading-relaxed">
                    Manage room devices, active subscriptions, OTT apps visibility, and guest services seamlessly.
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('hotel.devices.index') }}" class="px-5 py-3 rounded-2xl bg-white text-indigo-700 font-bold text-xs shadow-lg hover:bg-indigo-50 transition-all">
                    <i class="fa-solid fa-tv mr-1.5"></i> Connected Devices
                </a>
                <a href="{{ route('hotel.hotel-info') }}" class="px-5 py-3 rounded-2xl bg-indigo-500/30 hover:bg-indigo-500/40 text-white border border-white/20 font-semibold text-xs transition-all">
                    <i class="fa-solid fa-gear mr-1.5"></i> Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">License Key</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-key text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $hotel->license_key ?? 'N/A' }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Unique Hotel Authentication</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Connected TVs</span>
                <div class="w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                    <i class="fa-solid fa-tv text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $deviceCount ?? 0 }} / {{ $hotel->allowed_device_limit ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Rooms Synchronized</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Plan</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-crown text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $hotel->plan ? $hotel->plan->name : 'No Active Plan' }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Subscription Status</p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">In-House Guests</span>
                <div class="w-10 h-10 rounded-2xl bg-pink-50 border border-pink-100 flex items-center justify-center text-pink-600">
                    <i class="fa-solid fa-users text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $guestCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Checked-in Active Guests</p>
            </div>
        </div>
    </div>

    <!-- Quick Action Cards Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('hotel.package') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-indigo-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 text-indigo-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fa-brands fa-google-play"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">OTT Apps Control</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Configure streaming apps (Netflix, Prime, YouTube) for TV screens.</p>
            </div>
        </a>

        <a href="{{ route('hotel.amenities.index') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-violet-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-violet-600/10 text-violet-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-violet-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-spa"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-violet-600 transition-colors">Manage Amenities</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Customize in-room amenities, icons, images and sort order.</p>
            </div>
        </a>

        <a href="{{ route('hotel.hotel-info') }}" class="group bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-pink-500/40 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-pink-600/10 text-pink-600 flex items-center justify-center text-xl shrink-0 group-hover:bg-pink-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-images"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-pink-600 transition-colors">Hotel Media & Gallery</h4>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Upload logo, cover image, TV sliders and room gallery photos.</p>
            </div>
        </a>
    </div>
</div>
@endsection
