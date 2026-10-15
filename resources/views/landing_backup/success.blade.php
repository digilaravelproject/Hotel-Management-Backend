@extends('layouts.landing')

@section('title', 'Registration Successful - HotelTV')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 space-y-12">
    <div class="bg-slate-900/90 backdrop-blur-2xl border border-slate-800 rounded-3xl p-8 sm:p-12 text-center shadow-2xl space-y-6 relative overflow-hidden">
        <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
            <i class="fa-solid fa-circle-check text-4xl"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Registration Successful!</h1>
            <p class="text-xs sm:text-sm text-slate-400 max-w-lg mx-auto">Your payment was confirmed. Below is your 16-digit TV license key to link your room screens.</p>
        </div>
        
        <!-- License Key Display Box -->
        <div class="p-6 rounded-2xl bg-slate-950 border-2 border-dashed border-indigo-500/50 space-y-2 inline-block w-full max-w-md">
            <div class="font-mono text-2xl sm:text-3xl font-extrabold text-indigo-400 tracking-widest">{{ $licenseKey }}</div>
            <p class="text-[11px] font-bold text-indigo-300 uppercase tracking-wider">
                <i class="fa-solid fa-tv mr-1"></i> Valid for {{ $hotel->room_count }} Connected TVs
            </p>
        </div>

        <!-- Details Grid -->
        <div class="bg-slate-950/60 border border-slate-800 rounded-2xl p-6 max-w-lg mx-auto text-left text-xs space-y-3">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400 font-medium">Hotel Owner</span>
                <span class="font-bold text-white">{{ $hotel->owner_name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400 font-medium">Hotel Name</span>
                <span class="font-bold text-white">{{ $hotel->hotel_name }} ({{ $hotel->hotel_location }})</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400 font-medium">Plan Activated</span>
                <span class="font-bold text-indigo-400">{{ $hotel->plan->name ?? 'Standard Plan' }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400 font-medium">Username / Email</span>
                <span class="font-mono text-white">{{ $hotel->email }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400 font-medium">Password</span>
                <span class="font-mono text-white">{{ $plainPassword }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">Transaction Reference</span>
                <span class="font-mono text-slate-500 truncate max-w-[200px]">{{ $hotel->razorpay_payment_id }}</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-semibold text-left max-w-lg mx-auto flex items-start space-x-3">
            <i class="fa-solid fa-circle-info text-base mt-0.5 text-amber-400"></i>
            <div>
                <strong>Approval Pending:</strong> Your payment is complete. Once Super Admin approves your account, you will be able to log in.
            </div>
        </div>

        <div class="pt-2">
            <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 px-6 py-3 rounded-xl border border-slate-700 bg-slate-800/80 hover:bg-slate-800 text-white font-bold text-xs transition-all">
                <i class="fa-solid fa-house"></i>
                <span>Return to Homepage</span>
            </a>
        </div>
    </div>
</div>
@endsection
