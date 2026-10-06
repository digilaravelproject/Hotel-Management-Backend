@extends('layouts.super_admin')

@section('title', 'Add New User - Super Admin')
@section('page_title', 'Create System User')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('super-admin.users.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Back to Users Directory</span>
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

    <form method="POST" action="{{ route('super-admin.users.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Account Details -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900">1. Basic Profile & Credentials</h3>
                <p class="text-xs text-slate-500">Provide the user's personal details and secure login credentials.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. rahul@hotelpartner.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +91 98765 43210" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-xs font-bold text-slate-700">Account Active immediately</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter password" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 bg-slate-50/50">
                </div>
            </div>
        </div>

        <!-- Section 2: Role Assignment -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900">2. Assign Primary Role</h3>
                <p class="text-xs text-slate-500">Select one or more Spatie roles to determine this user's base privileges.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($roles as $role)
                    @php
                        $isSelected = in_array($role->name, old('roles', ['distributor']));
                    @endphp
                    <label class="relative flex flex-col justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-slate-400 bg-white has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs
                                @if($role->name === 'super_admin') bg-rose-100 text-rose-600
                                @elseif($role->name === 'distributor') bg-amber-100 text-amber-600
                                @elseif($role->name === 'hotel_admin') bg-indigo-100 text-indigo-600
                                @else bg-slate-100 text-slate-600 @endif">
                                @if($role->name === 'super_admin') <i class="fa-solid fa-shield-halved"></i>
                                @elseif($role->name === 'distributor') <i class="fa-solid fa-briefcase"></i>
                                @elseif($role->name === 'hotel_admin') <i class="fa-solid fa-hotel"></i>
                                @else <i class="fa-solid fa-user"></i> @endif
                            </span>
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $isSelected ? 'checked' : '' }} class="w-4 h-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500">
                        </div>
                        <div>
                            <div class="font-extrabold text-slate-900 text-sm">{{ ucwords(str_replace('_', ' ', $role->name)) }}</div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                @if($role->name === 'super_admin') Full system access, all hotels, users, and platform settings.
                                @elseif($role->name === 'distributor') Onboard child hotels and sell licensing packages with sales tracking.
                                @elseif($role->name === 'hotel_admin') Manage individual hotel, TV devices, menus, and amenities.
                                @else Standard role privileges. @endif
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Fine-Grained Permissions (Optional Direct Assignment) -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">3. Direct Permissions (Optional)</h3>
                    <p class="text-xs text-slate-500">Override or grant additional granular permissions directly to this user.</p>
                </div>
                <div class="flex items-center space-x-2 text-[11px]">
                    <button type="button" onclick="toggleAllPermissions(true)" class="text-rose-600 hover:text-rose-700 font-bold">Select All</button>
                    <span class="text-slate-300">•</span>
                    <button type="button" onclick="toggleAllPermissions(false)" class="text-slate-500 hover:text-slate-700 font-bold">Deselect All</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($permissions as $group => $perms)
                    <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>{{ ucwords($group) }} Module</span>
                        </h4>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer hover:text-slate-900">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }} class="perm-checkbox w-4 h-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500">
                                    <span class="font-medium">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('super-admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Create User Account</span>
            </button>
        </div>
    </form>
</div>

<script>
function toggleAllPermissions(check) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = check);
}
</script>
@endsection
