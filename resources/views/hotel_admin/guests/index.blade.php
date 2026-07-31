@extends('layouts.hotel_admin')

@section('title', 'Manage Guests - Hotel Admin')
@section('page_title', 'In-House Guests')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">Register new guests, manage check-in/out schedules, and track room allocations.</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(request()->filled('room'))
                <a href="{{ route('hotel.guests.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                    <i class="fa-solid fa-filter-circle-xmark mr-1"></i> Clear Filter (Room {{ request('room') }})
                </a>
            @endif
            <button onclick="openAddModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Guest</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-12"></th>
                        <th class="px-6 py-4">Guest Name</th>
                        <th class="px-6 py-4">Mobile Number</th>
                        <th class="px-6 py-4">Room Number</th>
                        <th class="px-6 py-4">Check-in Datetime</th>
                        <th class="px-6 py-4">Check-out Datetime</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guests as $guest)
                        @php
                            $now = now();
                            if ($now->lt($guest->check_in_datetime)) {
                                $statusText = 'Scheduled';
                                $statusClass = 'bg-sky-50 text-sky-700 border-sky-200';
                            } elseif ($guest->check_out_datetime && $now->gt($guest->check_out_datetime)) {
                                $statusText = 'Checked Out';
                                $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                            } else {
                                $statusText = 'Active';
                                $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-extrabold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($guest->name, 0, 1)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $guest->name }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ $guest->mobile_number }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('hotel.devices.index') }}?room_no={{ urlencode($guest->room_number) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-xs hover:bg-indigo-100 transition-colors">
                                    <span>Room {{ $guest->room_number }}</span>
                                    <i class="fa-solid fa-tv text-[10px]"></i>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $guest->check_in_datetime->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $guest->check_out_datetime ? $guest->check_out_datetime->format('Y-m-d H:i') : 'Open Check-in' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full border text-[11px] font-bold inline-block {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    @if($statusText !== 'Checked Out')
                                        <form action="{{ route('hotel.guests.checkout', $guest->id) }}" method="POST" onsubmit="return confirm('Check out this guest?');" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold text-[11px] transition-colors">
                                                <i class="fa-solid fa-right-from-bracket mr-1"></i> Checkout
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="openEditModal(this)" data-guest="{{ json_encode($guest) }}" class="p-2 rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Delete guest record?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-users text-3xl block mb-2 text-slate-300"></i>
                                No guests registered. Click 'Add New Guest' to check in.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addGuestModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900">Add New Guest Check-In</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('hotel.guests.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Guest Name</label>
                <input type="text" name="name" required placeholder="Guest full name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Mobile Number</label>
                <input type="text" name="mobile_number" required placeholder="+91 98765 43210" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Room Number</label>
                <input type="text" name="room_number" required placeholder="e.g. 101" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Check-In Datetime</label>
                    <input type="datetime-local" name="check_in_datetime" required value="{{ date('Y-m-d\TH:i') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Check-Out (Optional)</label>
                    <input type="datetime-local" name="check_out_datetime" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md">Register Guest</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editGuestModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900">Edit Guest Details</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="editGuestForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Guest Name</label>
                <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Mobile Number</label>
                <input type="text" name="mobile_number" id="editMobile" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Room Number</label>
                <input type="text" name="room_number" id="editRoom" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Check-In Datetime</label>
                    <input type="datetime-local" name="check_in_datetime" id="editCheckIn" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Check-Out Datetime</label>
                    <input type="datetime-local" name="check_out_datetime" id="editCheckOut" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() { document.getElementById('addGuestModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addGuestModal').classList.add('hidden'); }

    function openEditModal(btn) {
        const guest = JSON.parse(btn.getAttribute('data-guest'));
        document.getElementById('editGuestForm').action = `/hotel/guests/${guest.id}`;
        document.getElementById('editName').value = guest.name;
        document.getElementById('editMobile').value = guest.mobile_number;
        document.getElementById('editRoom').value = guest.room_number;
        document.getElementById('editCheckIn').value = guest.check_in_datetime.replace(' ', 'T').substring(0, 16);
        document.getElementById('editCheckOut').value = guest.check_out_datetime ? guest.check_out_datetime.replace(' ', 'T').substring(0, 16) : '';
        document.getElementById('editGuestModal').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('editGuestModal').classList.add('hidden'); }
</script>
@endsection
