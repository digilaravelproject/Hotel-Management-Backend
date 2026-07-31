@extends('layouts.hotel_admin')

@section('title', 'Update Profile')
@section('page_title', 'Account & Security')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-regular fa-user text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Personal Account Details</h3>
                <p class="text-xs text-slate-500 font-medium">Update your account identity and login credentials.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hotel.profile') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Owner / Director Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name', $hotelAdmin->owner_name) }}" required 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $hotelAdmin->email) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $hotelAdmin->phone) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">
                    Change Password <span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">New Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
