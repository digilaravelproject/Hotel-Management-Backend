@extends('layouts.hotel_admin')

@section('title', 'Hotel Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner (colors.md: Pure White #FFFFFF Card, Champagne Gold #C5A059 CTA) -->
    <div class="relative overflow-hidden rounded-3xl bg-[#FFFFFF] text-[#1E293B] p-8 md:p-10 border border-[#E2E8F0] shadow-xs">
        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#F9F5EB] border border-[#C5A059]/40 text-[#A88440] text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                    <span>Live Hotel System</span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#1E293B]">Welcome back, {{ Auth::guard('hotel_admin')->user()->owner_name }}! 👋</h2>
                <p class="text-[#64748B] text-sm font-medium leading-relaxed">
                    Manage room devices, active subscriptions, OTT apps visibility, and guest services seamlessly.
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Primary CTA Button (colors.md: Champagne Gold #C5A059, hover #A88440) -->
                <a href="{{ route('hotel.devices.index') }}" class="px-5 py-3 rounded-2xl bg-[#C5A059] hover:bg-[#A88440] text-[#FFFFFF] font-extrabold text-xs shadow-md shadow-[#C5A059]/25 transition-all">
                    <i class="fa-solid fa-tv mr-1.5"></i> Connected Devices
                </a>
                <!-- Secondary Button (colors.md: White #FFFFFF, border #CBD5E1, text #1E293B) -->
                <a href="{{ route('hotel.hotel-info') }}" class="px-5 py-3 rounded-2xl bg-[#FFFFFF] hover:bg-[#F8FAFC] text-[#1E293B] border border-[#CBD5E1] font-bold text-xs transition-all">
                    <i class="fa-solid fa-gear mr-1.5 text-[#C5A059]"></i> Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid (colors.md: Pure White Cards, Dark Slate Numbers, Warm Silk #F9F5EB Icon Box with Gold #C5A059) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#64748B] uppercase tracking-wider">License Key</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 flex items-center justify-center text-[#C5A059]">
                    <i class="fa-solid fa-key text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#1E293B] tracking-tight font-mono">{{ $hotel->license_key ?? 'N/A' }}</h3>
                <p class="text-xs text-[#64748B] font-medium mt-1">Unique Hotel Authentication</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#64748B] uppercase tracking-wider">Connected TVs</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 flex items-center justify-center text-[#C5A059]">
                    <i class="fa-solid fa-tv text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1E293B] tracking-tight">{{ $deviceCount ?? 0 }} / {{ $hotel->allowed_device_limit ?? 0 }}</h3>
                <p class="text-xs text-[#64748B] font-medium mt-1">Rooms Synchronized</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#64748B] uppercase tracking-wider">Active Plan</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 flex items-center justify-center text-[#C5A059]">
                    <i class="fa-solid fa-crown text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1E293B] tracking-tight">{{ $hotel->plan ? $hotel->plan->name : 'No Active Plan' }}</h3>
                <p class="text-xs text-[#64748B] font-medium mt-1">Subscription Status</p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md transition-all space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#64748B] uppercase tracking-wider">In-House Guests</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 flex items-center justify-center text-[#C5A059]">
                    <i class="fa-solid fa-users text-base"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1E293B] tracking-tight">{{ $guestCount ?? 0 }}</h3>
                <p class="text-xs text-[#64748B] font-medium mt-1">Checked-in Active Guests</p>
            </div>
        </div>
    </div>

    <!-- Plan Subscription & Expiry Summary Banner -->
    <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-[#E2E8F0] pb-4 gap-4">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-extrabold text-[#1E293B] tracking-tight">Subscription Plan Timeline</h3>
                    @if($hotel->expiry_date)
                        @if($hotel->expiry_date->isPast())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#FEE2E2] text-[#DC2626] border border-[#DC2626]/30">Expired</span>
                        @elseif($hotel->expiry_date->diffInDays(now()) <= 7)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#FFEDD5] text-[#EA580C] border border-[#EA580C]/30">Expires Soon</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/30">Active</span>
                        @endif
                    @endif
                </div>
                <p class="text-xs text-[#64748B] font-medium">Your current licensing plan validity details.</p>
            </div>
            <a href="{{ route('hotel.package') }}" class="px-4 py-2 rounded-xl bg-[#C5A059] hover:bg-[#A88440] text-[#FFFFFF] font-bold text-xs transition-colors">
                View Full Package Features
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Purchase Date</span>
                <p class="text-sm font-extrabold text-[#1E293B]">
                    <i class="fa-regular fa-calendar-check text-[#C5A059] mr-1.5"></i>
                    {{ $hotel->purchase_date ? $hotel->purchase_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Expiry Date</span>
                <p class="text-sm font-extrabold text-[#1E293B]">
                    <i class="fa-regular fa-clock text-[#EA580C] mr-1.5"></i>
                    {{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'N/A' }}
                </p>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-1">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Remaining Time</span>
                @if($hotel->expiry_date)
                    @if($hotel->expiry_date->isPast())
                        <p class="text-sm font-extrabold text-[#DC2626]">
                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i> Expired
                        </p>
                    @else
                        <p class="text-sm font-extrabold text-[#16A34A]">
                            <i class="fa-solid fa-shield-check mr-1.5"></i> {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} Days Left
                        </p>
                    @endif
                @else
                    <p class="text-sm font-extrabold text-[#64748B]">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Action Cards Grid (colors.md: Pure White Cards, Soft Gray Border, Champagne Gold Accents) -->
    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('hotel.package') }}" class="group bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-[#C5A059]/60 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 text-[#C5A059] flex items-center justify-center text-xl shrink-0 group-hover:bg-[#C5A059] group-hover:text-[#FFFFFF] transition-all">
                <i class="fa-brands fa-google-play"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-[#1E293B] group-hover:text-[#A88440] transition-colors">OTT Apps Control</h4>
                <p class="text-xs text-[#64748B] font-medium leading-relaxed">Configure streaming apps (Netflix, Prime, YouTube) for TV screens.</p>
            </div>
        </a>

        <a href="{{ route('hotel.amenities.index') }}" class="group bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-[#C5A059]/60 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 text-[#C5A059] flex items-center justify-center text-xl shrink-0 group-hover:bg-[#C5A059] group-hover:text-[#FFFFFF] transition-all">
                <i class="fa-solid fa-spa"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-[#1E293B] group-hover:text-[#A88440] transition-colors">Manage Amenities</h4>
                <p class="text-xs text-[#64748B] font-medium leading-relaxed">Customize in-room amenities, icons, images and sort order.</p>
            </div>
        </a>

        <a href="{{ route('hotel.room-infos.index') }}" class="group bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-[#C5A059]/60 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 text-[#C5A059] flex items-center justify-center text-xl shrink-0 group-hover:bg-[#C5A059] group-hover:text-[#FFFFFF] transition-all">
                <i class="fa-solid fa-bed"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-[#1E293B] group-hover:text-[#A88440] transition-colors">Manage Room Info</h4>
                <p class="text-xs text-[#64748B] font-medium leading-relaxed">Customize room details, icons, 16:9 images, and sort order.</p>
            </div>
        </a>

        <a href="{{ route('hotel.hotel-info') }}" class="group bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-[#C5A059]/60 transition-all flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F9F5EB] border border-[#C5A059]/35 text-[#C5A059] flex items-center justify-center text-xl shrink-0 group-hover:bg-[#C5A059] group-hover:text-[#FFFFFF] transition-all">
                <i class="fa-solid fa-images"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-bold text-[#1E293B] group-hover:text-[#A88440] transition-colors">Hotel Media & Gallery</h4>
                <p class="text-xs text-[#64748B] font-medium leading-relaxed">Upload logo, cover image, TV sliders and room gallery photos.</p>
            </div>
        </a>
    </div>
</div>
@endsection
