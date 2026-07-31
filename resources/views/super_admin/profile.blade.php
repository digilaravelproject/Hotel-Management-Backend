@extends('layouts.super_admin')

@section('title', 'My Profile - Super Admin')
@section('page_title', 'Account Security & Credentials')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                <i class="fa-regular fa-user text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Super Admin Credentials</h3>
                <p class="text-xs text-slate-500 font-medium">Manage master administrator login email address and account password.</p>
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

        <form action="{{ route('super-admin.profile') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Master Email Address</label>
                <input type="email" name="email" value="{{ old('email', $superAdmin->email) }}" required 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">
                    Update Password <span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">New Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-rose-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('super-admin.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all hover:-translate-y-0.5">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
