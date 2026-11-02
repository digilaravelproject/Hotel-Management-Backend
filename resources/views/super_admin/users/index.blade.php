@extends('layouts.super_admin')

@section('title', 'Staff & Partners Directory - Super Admin')
@section('page_title', 'Staff & Partners Directory')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">System Operators & Staff</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage operator accounts, assign administrative roles, and configure security access controls.</p>
        </div>
        <a href="{{ route('super-admin.users.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add New Operator</span>
        </a>
    </div>

    <!-- Quick Stats Metric Cards (2x2 on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total Users -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight leading-tight">{{ $users->total() }}</div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Total Users</div>
            </div>
        </div>

        <!-- Super Admins -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-[#150A42] border border-indigo-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#150A42] font-mono tracking-tight leading-tight">
                    {{ $users->filter(fn($u) => $u->hasRole('super_admin'))->count() }}
                </div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Super Admins</div>
            </div>
        </div>

        <!-- Hotel Admins -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight leading-tight">
                    {{ $users->filter(fn($u) => $u->hasRole('hotel_admin'))->count() }}
                </div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Hotel Admins</div>
            </div>
        </div>

        <!-- Channel Distributors -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 font-mono tracking-tight leading-tight">
                    {{ $users->filter(fn($u) => $u->hasRole('distributor'))->count() }}
                </div>
                <div class="text-xs font-semibold text-slate-500 truncate mt-0.5">Distributors</div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 shadow-xs">
        <form method="GET" action="{{ route('super-admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1 font-mono">Search Operator</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] text-slate-800 font-medium transition-all shadow-inner">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1 font-mono">Assigned Role</label>
                <x-dropdown name="role" label="All Roles" :options="$roles->pluck('name', 'name')->mapWithKeys(fn($name, $value) => [$value => ucwords(str_replace('_', ' ', $name))])->toArray()" :selected="request('role', '')" theme="sa" />
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1 font-mono">Account Status</label>
                <x-dropdown name="status" label="All Statuses" :options="['1' => 'Active Only', '0' => 'Disabled Only']" :selected="request('status', '')" theme="sa" />
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 text-xs rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold shadow-sm transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Apply Filters</span>
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('super-admin.users.index') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all flex items-center justify-center" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- DESKTOP VIEW: High-density Table -->
    <div class="hidden lg:block bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-visible">
        <div class="overflow-visible">
            <table class="w-full text-left text-xs text-slate-800">
                <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3.5">Operator</th>
                        <th class="px-4 py-3.5">Contact Details</th>
                        <th class="px-4 py-3.5">Assigned Roles</th>
                        <th class="px-3 py-3.5">Permissions</th>
                        <th class="px-3 py-3.5 text-center">Status</th>
                        <th class="px-3 py-3.5">Joined Date</th>
                        <th class="px-3 py-3.5 text-center w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr id="user-row-{{ $user->id }}" class="hover:bg-purple-50/20 transition-colors">
                            <!-- Operator Info -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#150A42] to-purple-700 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 font-mono">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-900 text-xs flex items-center space-x-1.5 truncate">
                                            <span class="truncate">{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.2 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[9px] border border-emerald-200 uppercase font-mono">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">ID: #USR-{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-4 py-3.5 space-y-0.5">
                                <div class="font-bold text-slate-800 text-xs font-mono truncate max-w-[200px]">
                                    <a href="mailto:{{ $user->email }}" class="hover:text-[#150A42] transition-colors">{{ $user->email }}</a>
                                </div>
                                <div class="text-[10px] text-slate-500 font-mono">
                                    <i class="fa-solid fa-phone mr-1 text-[9px] text-slate-400"></i>{{ $user->phone ?? 'No phone' }}
                                </div>
                            </td>

                            <!-- Roles -->
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($user->roles as $role)
                                        @if($role->name === 'super_admin')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-50 text-[#150A42] border border-purple-200/80 font-mono uppercase">
                                                <i class="fa-solid fa-shield-halved mr-1 text-[9px]"></i> Super Admin
                                            </span>
                                        @elseif($role->name === 'distributor')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono uppercase">
                                                <i class="fa-solid fa-handshake mr-1 text-[9px]"></i> Distributor
                                            </span>
                                        @elseif($role->name === 'hotel_admin')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200 font-mono uppercase">
                                                <i class="fa-solid fa-hotel mr-1 text-[9px]"></i> Hotel Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </span>
                                        @endif
                                    @empty
                                        <span class="text-[10px] text-slate-400 italic">No roles</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Permissions -->
                            <td class="px-3 py-3.5">
                                @php
                                    $directPermsCount = $user->permissions->count();
                                @endphp
                                @if($directPermsCount > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-[#150A42] border border-purple-100 font-mono" title="{{ $user->permissions->pluck('name')->join(', ') }}">
                                        <i class="fa-solid fa-key mr-1 text-[9px]"></i> {{ $directPermsCount }} Custom
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400 font-mono">Role Inherited</span>
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="px-3 py-3.5 text-center">
                                <label class="relative inline-flex items-center cursor-pointer" title="Toggle user active status">
                                    <input type="checkbox" onchange="toggleUserStatus({{ $user->id }})" {{ $user->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                                <div id="status-label-{{ $user->id }}" class="text-[9px] font-bold font-mono uppercase mt-0.5 {{ $user->status ? 'text-emerald-700' : 'text-slate-400' }}">
                                    {{ $user->status ? 'Active' : 'Disabled' }}
                                </div>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-3 py-3.5 text-slate-500 font-mono text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-3 py-3.5 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('super-admin.users.edit', $user->id) }}" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 flex items-center justify-center transition-all shadow-xs" title="Edit User">
                                        <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete user \'{{ $user->name }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all shadow-xs cursor-pointer" title="Delete User">
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-users-slash"></i>
                                    </div>
                                    <p class="font-extrabold text-slate-800 text-xs">No users match your filters</p>
                                    <p class="text-[11px] text-slate-400">Try adjusting your search terms or role filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- MOBILE VIEW: Touch-Friendly Responsive Cards (block lg:hidden) -->
    <div class="block lg:hidden space-y-3">
        @forelse($users as $user)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#150A42] to-purple-700 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 font-mono">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-extrabold text-slate-900 text-sm truncate flex items-center space-x-1.5">
                                <span class="truncate">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                    <span class="px-1.5 py-0.2 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[9px] border border-emerald-200 uppercase font-mono">You</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono">ID: #USR-{{ $user->id }}</div>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center space-x-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="toggleUserStatus({{ $user->id }})" {{ $user->status ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                    </div>
                </div>

                <!-- Roles & Permissions -->
                <div class="flex flex-wrap gap-1 pt-2 border-t border-slate-100">
                    @forelse($user->roles as $role)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-purple-50 text-[#150A42] border border-purple-200/80 font-mono uppercase">
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @empty
                        <span class="text-[10px] text-slate-400 italic">No roles</span>
                    @endforelse
                </div>

                <!-- Contact & Joined Info -->
                <div class="grid grid-cols-2 gap-2 text-[11px] font-mono pt-1">
                    <div class="truncate text-slate-600">
                        <i class="fa-regular fa-envelope mr-1 text-slate-400"></i>{{ $user->email }}
                    </div>
                    <div class="text-right text-slate-500">
                        <i class="fa-regular fa-calendar mr-1 text-slate-400"></i>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('super-admin.users.edit', $user->id) }}" class="px-3 py-1.5 rounded-xl bg-purple-50 text-[#150A42] text-xs font-bold hover:bg-purple-100 transition-colors flex items-center space-x-1.5">
                        <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                        <span>Edit</span>
                    </a>
                    @if(auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('super-admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete user \'{{ $user->name }}\'?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold hover:bg-rose-100 transition-colors flex items-center space-x-1.5 cursor-pointer">
                                <i class="fa-regular fa-trash-can text-[10px]"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center text-slate-400 space-y-2">
                <i class="fa-solid fa-users-slash text-2xl text-purple-300 block"></i>
                <p class="font-extrabold text-slate-800 text-xs">No users found</p>
                <p class="text-[11px] text-slate-400">Try adjusting your search criteria.</p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div class="pt-2">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function toggleUserStatus(userId) {
    const label = document.getElementById(`status-label-${userId}`);
    fetch(`{{ url('super-admin/users') }}/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (label) {
                if (data.status) {
                    label.textContent = 'Active';
                    label.className = 'text-[9px] font-bold font-mono uppercase mt-0.5 text-emerald-700';
                } else {
                    label.textContent = 'Disabled';
                    label.className = 'text-[9px] font-bold font-mono uppercase mt-0.5 text-slate-400';
                }
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.status ? 'User activated' : 'User disabled',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: { popup: 'rounded-2xl border border-slate-200 font-sans shadow-lg text-xs' }
                });
            }
        } else {
            alert('Failed to update status.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Server communication error.');
    });
}
</script>
@endsection
