@extends('layouts.distributor')

@section('title', 'Onboard Hotel - Distributor Hub')
@section('page_title', 'Onboard New Partner Hotel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('distributor.hotels.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Back to My Hotels</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('distributor.hotels.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Hotel & Owner Information -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900">1. Hotel & Owner Credentials</h3>
                <p class="text-xs text-slate-500">Provide the hotel brand identity and administrator login credentials.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hotel Property Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="hotel_name" value="{{ old('hotel_name') }}" required placeholder="e.g. Grand Palace Hotel" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Owner / GM Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="e.g. Ramesh Patel" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hotel Admin Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. admin@grandpalace.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Login Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Phone <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. +91 98765 43210" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Number of Rooms / Smart TVs <span class="text-rose-500">*</span></label>
                    <input type="number" name="room_count" value="{{ old('room_count', 25) }}" required min="1" max="1000" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hotel Location / Address <span class="text-rose-500">*</span></label>
                    <input type="text" name="hotel_location" value="{{ old('hotel_location') }}" required placeholder="e.g. Beach Road, Calangute" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Goa" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-slate-50/50">
                </div>
            </div>
        </div>

        <!-- Section 2: Initial Package Selection -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900">2. Initial Licensing Package (Optional)</h3>
                <p class="text-xs text-slate-500">Select an initial plan to activate the hotel immediately with 30 days validity, or leave unassigned to sell a package later.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-slate-400 bg-white has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-ban"></i>
                        </span>
                        <input type="radio" name="plan_id" value="" {{ old('plan_id') == '' ? 'checked' : '' }} class="w-4 h-4 text-amber-600 border-slate-300 focus:ring-amber-500">
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-900 text-sm">No Initial Plan</div>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Create hotel account with pending payment status. You can sell a package anytime later.
                        </p>
                    </div>
                </label>

                @foreach($plans as $plan)
                    <label class="relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-slate-400 bg-white has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'checked' : '' }} class="w-4 h-4 text-amber-600 border-slate-300 focus:ring-amber-500">
                        </div>
                        <div>
                            <div class="font-extrabold text-slate-900 text-sm">{{ $plan->name }}</div>
                            <div class="text-sm font-extrabold text-amber-600 mt-0.5">₹{{ number_format($plan->price, 0) }} <span class="text-[10px] text-slate-400 font-normal">/mo</span></div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Up to {{ $plan->room_count }} rooms. Instant 30 days license activation.
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('distributor.hotels.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Register Hotel & Generate License</span>
            </button>
        </div>
    </form>
</div>
@endsection
