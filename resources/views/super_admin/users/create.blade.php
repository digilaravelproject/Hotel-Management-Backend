@extends('layouts.super_admin')

@section('title', 'Create User - Super Admin')
@section('page_title', 'Create System User')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Responsive on Mobile -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('super-admin.users.index') }}" class="hover:text-[#150A42] font-semibold transition-colors flex items-center space-x-1">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Users Directory</span>
                </a>
                <span>/</span>
                <span class="font-mono text-slate-400">New User</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans flex items-center space-x-2">
                <span>Create New User</span>
                <span class="px-2 py-0.5 rounded-full bg-purple-50 text-[#150A42] border border-purple-200 text-[10px] font-bold">New Account</span>
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Onboard new platform distributors, hotel administrators, or operations staff with customizable roles and permissions.</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center justify-start sm:justify-end gap-2 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-1 w-full sm:w-auto">
            <a href="{{ route('super-admin.users.index') }}" 
               class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                <span>Cancel</span>
            </a>

            <button type="button" 
                    onclick="document.getElementById('createUserForm').submit()" 
                    class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Create User</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Create Form -->
    <form id="createUserForm" method="POST" action="{{ route('super-admin.users.store') }}" class="space-y-6">
        @csrf

        <!-- Card 1: Basic Profile & Credentials -->
        <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-7 shadow-xs space-y-6">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">1. Basic Profile & Credentials</h3>
                    <p class="text-xs text-slate-500">Provide personal credentials, initial password, and distributor commission rate.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma" 
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all font-medium text-slate-900">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. rahul@hotelpartner.com" 
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all font-medium text-slate-900 font-mono">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Phone Number
                    </label>
                    <div class="relative">
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" 
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all font-medium text-slate-900 font-mono">
                        <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Commission Percentage -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Commission Override (%) <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="commission_percentage" value="{{ old('commission_percentage') }}" placeholder="Leave blank for global default" 
                               class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all font-mono font-medium text-slate-900">
                        <i class="fa-solid fa-percent absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Overrides default commission ({{ \App\Models\CommissionSetting::getSetting()->default_percentage }}%) if user is a distributor.</p>
                </div>

                <!-- Account Status Toggle -->
                <div class="sm:col-span-2 flex flex-col justify-center">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Account State</label>
                    <div class="flex items-center space-x-3 p-2.5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                        <div class="text-xs">
                            <span class="font-extrabold text-slate-800">Account Active Immediately</span>
                            <p class="text-[11px] text-slate-500">Allow this user to log into the portal immediately upon creation.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Fields -->
            <div class="border-t border-slate-100 pt-5">
                <div class="flex items-center space-x-2 mb-3">
                    <i class="fa-solid fa-key text-slate-400 text-xs"></i>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider font-mono">Login Password Setup</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Initial Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="create_password" name="password" required placeholder="Minimum 6 characters" 
                                   class="w-full pl-9 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all text-slate-900 font-medium">
                            <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <button type="button" onclick="togglePass('create_password', 'toggle_icon_1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                <i id="toggle_icon_1" class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="create_password_confirmation" name="password_confirmation" required placeholder="Re-type password" 
                                   class="w-full pl-9 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all text-slate-900 font-medium">
                            <i class="fa-solid fa-shield-check absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <button type="button" onclick="togglePass('create_password_confirmation', 'toggle_icon_2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                <i id="toggle_icon_2" class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Role Assignment -->
        <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">2. System Roles Assignment</h3>
                        <p class="text-xs text-slate-500">Select one or more roles that define this user's primary operating permissions.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                @foreach($roles as $role)
                    @php
                        $isSelected = in_array($role->name, old('roles', ['distributor']));
                    @endphp
                    <label class="relative flex flex-col justify-between p-4.5 rounded-2xl border-2 cursor-pointer transition-all hover:border-purple-300 bg-[#F8F9FD] has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 has-[:checked]:shadow-xs group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm
                                @if($role->name === 'super_admin') bg-purple-100 text-[#150A42]
                                @elseif($role->name === 'distributor') bg-blue-100 text-blue-700
                                @elseif($role->name === 'hotel_admin') bg-emerald-100 text-emerald-700
                                @else bg-slate-200 text-slate-700 @endif shadow-2xs">
                                @if($role->name === 'super_admin') <i class="fa-solid fa-crown"></i>
                                @elseif($role->name === 'distributor') <i class="fa-solid fa-handshake"></i>
                                @elseif($role->name === 'hotel_admin') <i class="fa-solid fa-hotel"></i>
                                @else <i class="fa-solid fa-user-gear"></i> @endif
                            </span>
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $isSelected ? 'checked' : '' }} 
                                   class="w-4.5 h-4.5 text-[#150A42] border-slate-300 rounded-md focus:ring-[#150A42] cursor-pointer">
                        </div>
                        <div>
                            <div class="font-black text-slate-900 text-sm group-hover:text-[#150A42] transition-colors">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                @if($role->name === 'super_admin') Full platform access to all hotels, system settings, financial ledgers, and users.
                                @elseif($role->name === 'distributor') Onboard child hotels, manage commissions, request withdrawals, and track TV sales.
                                @elseif($role->name === 'hotel_admin') Access to individual hotel portal, in-room TV devices, room menus, and guest amenities.
                                @else Standard operating role with custom permissions. @endif
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Card 3: Granular Direct Permissions -->
        <div class="bg-white border border-slate-200/80 rounded-[28px] p-5 sm:p-7 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-4 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">3. Direct Permissions (Optional)</h3>
                        <p class="text-xs text-slate-500">Fine-grained permissions directly granted to this user, bypassing default role limits.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 text-xs bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200/70">
                    <button type="button" onclick="toggleAllPermissions(true)" class="text-[#150A42] hover:underline font-bold cursor-pointer">Select All</button>
                    <span class="text-slate-300">•</span>
                    <button type="button" onclick="toggleAllPermissions(false)" class="text-slate-500 hover:underline font-bold cursor-pointer">Deselect All</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissions as $group => $perms)
                    <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 space-y-3 hover:border-purple-200 transition-all">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center space-x-1.5 font-mono">
                                <span class="w-2 h-2 rounded-full bg-[#150A42]"></span>
                                <span>{{ ucwords($group) }}</span>
                            </h4>
                            <span class="text-[10px] font-mono text-slate-400 font-bold">{{ $perms->count() }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer hover:text-slate-900 group">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }} 
                                           class="perm-checkbox w-4 h-4 text-[#150A42] border-slate-300 rounded focus:ring-[#150A42] cursor-pointer">
                                    <span class="font-medium group-hover:text-slate-900">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom Fixed Action Bar -->
        <div class="flex items-center justify-end space-x-3 pt-2 pb-6">
            <a href="{{ route('super-admin.users.index') }}" 
               class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 cursor-pointer">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Create User</span>
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
function toggleAllPermissions(check) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = check);
}

function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
@endsection
