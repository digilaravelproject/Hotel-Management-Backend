@extends('layouts.super_admin')

@section('title', 'User Management & Access Control - Super Admin')
@section('page_title', 'System Users & Role Management')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-[#64748B] font-medium">Manage backend operators, assign Spatie roles (Super Admin, Hotel Admin, Distributor), and configure fine-grained permissions.</p>
        </div>
        <a href="{{ route('super-admin.users.create') }}" class="px-5 py-2.5 rounded-xl bg-[#D4AF37] hover:bg-[#B5952F] text-[#0F2537] font-bold text-xs shadow-md shadow-[#D4AF37]/20 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add New User</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('super-admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Search User</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#64748B] text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/30 focus:border-[#D4AF37] bg-[#F8FAFC] text-[#1E293B]">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Filter by Role</label>
                <x-dropdown name="role" label="All Roles" :options="$roles->pluck('name', 'name')->mapWithKeys(fn($name, $value) => [$value => ucwords(str_replace('_', ' ', $name))])->toArray()" :selected="request('role', '')" theme="sa" />
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Status</label>
                <x-dropdown name="status" label="All Statuses" :options="['1' => 'Active Only', '0' => 'Inactive Only']" :selected="request('status', '')" theme="sa" />
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-sa-primary flex-1 py-2 px-4 text-xs shadow-xs">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Apply Filter</span>
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('super-admin.users.index') }}" class="btn-secondary py-2 px-3 text-xs" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Container -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#1E293B]">
                <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Assigned Roles</th>
                        <th class="px-6 py-4">Permissions</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Joined Date</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr id="user-row-{{ $user->id }}" class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#0F2537] text-[#D4AF37] flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-[#1E293B] text-sm flex items-center space-x-2">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#16A34A] font-bold text-[10px] border border-[#16A34A]/20">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-[#64748B] font-medium">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-medium text-[#1E293B] font-mono">{{ $user->email }}</div>
                                <div class="text-[11px] text-[#64748B]">{{ $user->phone ?? 'No phone added' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($user->roles as $role)
                                        @if($role->name === 'super_admin')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#FBF8F1] text-[#0F2537] border border-[#D4AF37]/40">
                                                <i class="fa-solid fa-shield-halved mr-1 text-[9px] text-[#D4AF37]"></i> Super Admin
                                            </span>
                                        @elseif($role->name === 'distributor')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#FEF9EE] text-[#004B87] border border-[#E5A93C]/40">
                                                <i class="fa-solid fa-briefcase mr-1 text-[9px] text-[#E5A93C]"></i> Distributor
                                            </span>
                                        @elseif($role->name === 'hotel_admin')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#F9F5EB] text-[#6E1841] border border-[#C5A059]/40">
                                                <i class="fa-solid fa-hotel mr-1 text-[9px] text-[#C5A059]"></i> Hotel Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#F8FAFC] text-[#1E293B] border border-[#E2E8F0]">
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </span>
                                        @endif
                                    @empty
                                        <span class="text-[11px] text-[#64748B] italic">No roles assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $directPermsCount = $user->permissions->count();
                                @endphp
                                @if($directPermsCount > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FBF8F1] text-[#0F2537] border border-[#D4AF37]/30" title="{{ $user->permissions->pluck('name')->join(', ') }}">
                                        <i class="fa-solid fa-key mr-1 text-[9px] text-[#D4AF37]"></i> {{ $directPermsCount }} Custom
                                    </span>
                                @else
                                    <span class="text-[11px] text-[#64748B]">Inherited from Role</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleUserStatus({{ $user->id }})" {{ $user->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-[#CBD5E1] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#16A34A]"></div>
                                </label>
                                <span id="status-label-{{ $user->id }}" class="ml-2 text-[11px] font-bold {{ $user->status ? 'text-[#16A34A]' : 'text-[#64748B]' }}">
                                    {{ $user->status ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[#64748B] font-medium">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('super-admin.users.edit', $user->id) }}" class="p-2 rounded-xl border border-[#CBD5E1] text-[#0F2537] hover:bg-[#FBF8F1] hover:text-[#D4AF37] transition-colors" title="Edit User">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete user \'{{ $user->name }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl border border-[#FEE2E2] text-[#DC2626] hover:bg-[#FEE2E2]/50 transition-colors" title="Delete User">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
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
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-users-slash text-xl text-slate-400"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">No users match your criteria</p>
                                    <p class="text-xs text-slate-400">Try adjusting your search terms or filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
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
            if (data.status) {
                label.textContent = 'Active';
                label.className = 'ml-2 text-[11px] font-bold text-emerald-600';
            } else {
                label.textContent = 'Disabled';
                label.className = 'ml-2 text-[11px] font-bold text-slate-400';
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
