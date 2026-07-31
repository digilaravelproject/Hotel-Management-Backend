@extends('layouts.super_admin')

@section('title', 'Manage Hotels - Super Admin')
@section('page_title', 'Hotel Vendors Directory')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">View, edit credentials, approve accounts, and toggle active status of hotel clients.</p>
        </div>
        <a href="{{ route('super-admin.hotels.create') }}" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Hotel Vendor</span>
        </a>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Hotel Client</th>
                        <th class="px-6 py-4">Owner & Contact</th>
                        <th class="px-6 py-4">Room Limit & Plan</th>
                        <th class="px-6 py-4">License Key</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Approval</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hotels as $hotel)
                        <tr id="hotel-row-{{ $hotel->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                        @if($hotel->hotel_logo)
                                            <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-hotel text-slate-400 text-sm"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm">{{ $hotel->hotel_name }}</h4>
                                        <span class="text-[11px] text-slate-400 font-medium"><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i>{{ $hotel->hotel_location }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-bold text-slate-800">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $hotel->email }}</div>
                                <div class="text-[11px] text-slate-400">{{ $hotel->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900">{{ $hotel->room_count }} Rooms</div>
                                @if($hotel->plan)
                                    <span class="inline-block text-[11px] font-bold text-rose-600">{{ $hotel->plan->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono">
                                @if($hotel->license_key)
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-[11px]">
                                        {{ $hotel->license_key }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Not Generated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->payment_status === 'paid')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] uppercase">Paid</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-bold text-[10px] uppercase">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleStatus({{ $hotel->id }})" {{ $hotel->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-600"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4">
                                <select onchange="updateApproval({{ $hotel->id }}, this.value)" class="px-3 py-1.5 rounded-xl border text-xs font-bold focus:outline-none transition-colors {{ $hotel->approval_status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : ($hotel->approval_status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-300' : 'bg-rose-50 text-rose-700 border-rose-300') }}">
                                    <option value="pending" {{ $hotel->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $hotel->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="disapproved" {{ $hotel->approval_status == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="p-2 rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50 transition-colors" title="Manage Amenities">
                                        <i class="fa-solid fa-spa"></i>
                                    </a>
                                    <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="p-2 rounded-lg border border-slate-200 text-emerald-600 hover:bg-emerald-50 transition-colors" title="View TVs">
                                        <i class="fa-solid fa-tv"></i>
                                    </a>
                                    <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors" title="View details">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="p-2 rounded-lg border border-slate-200 text-violet-600 hover:bg-violet-50 transition-colors" title="Edit credentials">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('super-admin.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Delete this vendor?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors" title="Delete account">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-hotel text-3xl block mb-2 text-slate-300"></i>
                                No registered hotel vendors found. Click 'Add New Hotel Vendor' to register one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleStatus(id) {
        fetch(`/super-admin/hotels/${id}/toggle-status`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
    }

    function updateApproval(id, status) {
        fetch(`/super-admin/hotels/${id}/toggle-approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ approval_status: status })
        });
    }
</script>
@endsection
