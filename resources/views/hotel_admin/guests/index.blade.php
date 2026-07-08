@extends('layouts.hotel_admin')

@section('title', 'Manage Guests')
@section('page_title', 'Guest Management')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .guests-table th, .guests-table td {
        vertical-align: middle;
    }

    .guest-avatar {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-align: center;
    }

    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-scheduled {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-checkedout {
        background-color: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }
</style>
@endsection

@section('content')
<div class="action-header">
    <div>
        <p style="color: var(--text-muted); font-size: 14px;">Register new guests, manage check-in/out schedules, and track room allocations.</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        @if(request()->filled('room'))
            <a href="{{ route('hotel.guests.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-filter-circle-xmark"></i> Clear Filter (Room {{ request('room') }})
            </a>
        @endif
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Guest
        </button>
    </div>
</div>

<!-- Table list -->
<div class="table-responsive">
    <table class="table guests-table">
        <thead>
            <tr>
                <th style="width: 60px;"></th>
                <th>Guest Name</th>
                <th>Mobile Number</th>
                <th>Room Number</th>
                <th>Check-in Datetime</th>
                <th>Check-out Datetime</th>
                <th>Status</th>
                <th style="width: 180px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $guest)
                @php
                    $now = now();
                    if ($now->lt($guest->check_in_datetime)) {
                        $statusText = 'Scheduled';
                        $statusClass = 'status-scheduled';
                    } elseif ($guest->check_out_datetime && $now->gt($guest->check_out_datetime)) {
                        $statusText = 'Checked Out';
                        $statusClass = 'status-checkedout';
                    } else {
                        $statusText = 'Active';
                        $statusClass = 'status-active';
                    }
                @endphp
                <tr>
                    <td>
                        <div class="guest-avatar">
                            {{ strtoupper(substr($guest->name, 0, 1)) }}
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--bg-dark); font-size: 15px;">{{ $guest->name }}</strong>
                    </td>
                    <td>
                        <span style="color: var(--text-main); font-size: 14px;">{{ $guest->mobile_number }}</span>
                    </td>
                    <td>
                        <a href="{{ route('hotel.devices.index') }}?room_no={{ urlencode($guest->room_number) }}" 
                           class="badge" 
                           style="background-color: var(--primary-light); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.2); font-weight: 600;" 
                           title="View Connected Devices for Room {{ $guest->room_number }}">
                            Room {{ $guest->room_number }} <i class="fa-solid fa-tv" style="margin-left: 4px; font-size: 10px;"></i>
                        </a>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 14px;">{{ $guest->check_in_datetime->format('Y-m-d H:i') }}</span>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 14px;">
                            {{ $guest->check_out_datetime ? $guest->check_out_datetime->format('Y-m-d H:i') : 'Open Check-in' }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                            @if($statusText !== 'Checked Out')
                                <form action="{{ route('hotel.guests.checkout', $guest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to check out this guest?');" style="display: inline; margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline" style="color: var(--warning); border-color: rgba(245, 158, 11, 0.3); background-color: rgba(245, 158, 11, 0.05);" title="Check Out Guest">
                                        <i class="fa-solid fa-right-from-bracket"></i> Checkout
                                    </button>
                                </form>
                            @endif
                            <button onclick="openEditModal({{ json_encode($guest) }})" class="btn btn-outline btn-sm" title="Edit guest" style="padding: 8px 10px;">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('hotel.guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this guest?');" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-danger-hover" title="Remove guest" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                        <i class="fa-solid fa-users" style="font-size: 40px; display: block; margin-bottom: 16px; color: var(--text-light);"></i>
                        No guests found. Click 'Add Guest' to register one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Guest Modal -->
<div id="addGuestModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add New Guest</h3>
            <button onclick="closeAddModal()" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('hotel.guests.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Guest Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. John Doe">
                </div>
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" required class="form-control" placeholder="e.g. +1234567890">
                </div>
                <div class="form-group">
                    <label class="form-label">Room Number</label>
                    <input type="text" name="room_number" required class="form-control" placeholder="e.g. 101">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-in Date & Time</label>
                    <input type="datetime-local" name="check_in_datetime" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-out Date & Time (Optional)</label>
                    <input type="datetime-local" name="check_out_datetime" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Guest</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Guest Modal -->
<div id="editGuestModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Guest Details</h3>
            <button onclick="closeEditModal()" class="modal-close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Guest Name</label>
                    <input type="text" name="name" id="editName" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" id="editMobile" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Room Number</label>
                    <input type="text" name="room_number" id="editRoom" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-in Date & Time</label>
                    <input type="datetime-local" name="check_in_datetime" id="editCheckIn" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-out Date & Time (Optional)</label>
                    <input type="datetime-local" name="check_out_datetime" id="editCheckOut" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const addModal = document.getElementById('addGuestModal');
    const editModal = document.getElementById('editGuestModal');
    const editForm = document.getElementById('editForm');

    function openAddModal() {
        addModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        addModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(guest) {
        editForm.action = `/hotel/guests/${guest.id}`;
        document.getElementById('editName').value = guest.name;
        document.getElementById('editMobile').value = guest.mobile_number;
        document.getElementById('editRoom').value = guest.room_number;
        
        const formatDateTime = (dateStr) => {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const pad = (n) => n.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        };
        
        document.getElementById('editCheckIn').value = formatDateTime(guest.check_in_datetime);
        document.getElementById('editCheckOut').value = formatDateTime(guest.check_out_datetime);

        editModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        editModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
