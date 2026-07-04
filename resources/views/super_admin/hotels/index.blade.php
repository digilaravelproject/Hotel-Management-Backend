@extends('layouts.super_admin')

@section('title', 'Manage Hotels - Super Admin')
@section('page_title', 'Hotel Vendor Management')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .approval-select {
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        background-color: white;
        cursor: pointer;
        outline: none;
    }
    
    .approval-select.pending { color: var(--warning-dark); background-color: var(--warning-light); border-color: var(--warning); }
    .approval-select.approved { color: var(--success-dark); background-color: var(--success-light); border-color: var(--success); }
    .approval-select.disapproved { color: var(--danger-dark); background-color: var(--danger-light); border-color: var(--danger); }
    
    /* Live toast styling */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--bg-dark);
        color: white;
        padding: 14px 24px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="action-header">
    <div>
        <p style="color: var(--text-muted); font-size: 14px;">View, edit, approve, and toggle status of all registered hotel vendors.</p>
    </div>
    <a href="{{ route('super-admin.hotels.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Hotel Vendor
    </a>
</div>

<!-- Hotels List Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Hotel Info</th>
                <th>Owner Details</th>
                <th>Rooms Limit</th>
                <th>License Key</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Approval</th>
                <th style="width: 170px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hotels as $hotel)
                <tr id="hotel-row-{{ $hotel->id }}">
                    <td>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); flex-shrink: 0; display: flex; align-items: center; justify-content: center;" title="Hotel Logo">
                                @if($hotel->hotel_logo)
                                    <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-hotel" style="color: var(--text-light); font-size: 16px;"></i>
                                @endif
                            </div>
                            <div style="width: 70px; height: 44px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); overflow: hidden; background-color: var(--bg-main); flex-shrink: 0; display: flex; align-items: center; justify-content: center;" title="Hotel Cover Image">
                                @if($hotel->hotel_image)
                                    <img src="{{ asset($hotel->hotel_image) }}" alt="Cover" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-regular fa-image" style="color: var(--text-light); font-size: 16px;"></i>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--bg-dark); font-size: 15px;">{{ $hotel->hotel_name }}</div>
                                <small style="color: var(--text-muted);"><i class="fa-solid fa-location-dot" style="margin-right: 4px;"></i>{{ $hotel->hotel_location }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $hotel->owner_name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $hotel->email }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $hotel->phone }}</div>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: var(--bg-dark);">{{ $hotel->room_count }} Rooms</span>
                        @if($hotel->plan)
                            <div style="font-size: 12px; color: var(--primary); font-weight: 500;">{{ $hotel->plan->name }}</div>
                        @endif
                    </td>
                    <td>
                        @if($hotel->license_key)
                            <code style="background-color: var(--primary-light); color: var(--primary-hover); padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 13px;">{{ $hotel->license_key }}</code>
                        @else
                            <span style="color: var(--text-light); font-style: italic;">Not Generated</span>
                        @endif
                    </td>
                    <td>
                        @if($hotel->payment_status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-danger">Pending</span>
                        @endif
                    </td>
                    <td>
                        <!-- Status Toggle switch -->
                        <label class="switch">
                            <input type="checkbox" onchange="toggleStatus({{ $hotel->id }})" {{ $hotel->status ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>
                        <!-- Approval Dropdown -->
                        <select onchange="updateApproval({{ $hotel->id }}, this.value)" class="approval-select {{ $hotel->approval_status }}">
                            <option value="pending" {{ $hotel->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $hotel->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="disapproved" {{ $hotel->approval_status == 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                        </select>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="btn btn-outline btn-sm" title="Manage Aminities" style="padding: 8px 10px; color: var(--primary); border-color: rgba(99, 102, 241, 0.2);">
                                <i class="fa-solid fa-spa"></i>
                            </a>
                            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="btn btn-outline btn-sm" title="View details" style="padding: 8px 10px;">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="btn btn-outline btn-sm" title="Edit credentials" style="padding: 8px 10px;">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('super-admin.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor? This action is permanent.');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-danger-hover" title="Delete account" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                        <i class="fa-regular fa-folder-open" style="font-size: 40px; display: block; margin-bottom: 16px;"></i>
                        No registered hotel admins found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Dynamic toast container -->
<div id="statusToast" class="toast-notification">
    <i class="fa-regular fa-circle-check" style="color: var(--success); font-size: 20px;"></i>
    <span id="toastMessage">Status updated successfully</span>
</div>
@endsection

@section('scripts')
<script>
    const toast = document.getElementById('statusToast');
    const toastMsg = document.getElementById('toastMessage');

    function showToast(message, isSuccess = true) {
        toastMsg.textContent = message;
        const icon = toast.querySelector('i');
        if (isSuccess) {
            icon.className = 'fa-regular fa-circle-check';
            icon.style.color = 'var(--success)';
        } else {
            icon.className = 'fa-regular fa-circle-xmark';
            icon.style.color = 'var(--danger)';
        }
        
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Toggle Active Status AJAX
    function toggleStatus(id) {
        fetch(`/super-admin/hotels/${id}/toggle-status`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
            } else {
                showToast('Failed to update status', false);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Server error while updating status', false);
        });
    }

    // Toggle Approval Status AJAX
    function updateApproval(id, status) {
        const select = document.querySelector(`#hotel-row-${id} .approval-select`);
        
        fetch(`/super-admin/hotels/${id}/toggle-approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ approval_status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update select CSS class
                select.className = `approval-select ${status}`;
                showToast(data.message);
            } else {
                showToast('Failed to update approval status', false);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Server error while updating approval', false);
        });
    }
</script>
@endsection
