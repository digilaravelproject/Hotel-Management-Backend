@extends('layouts.hotel_admin')

@section('title', 'Manage Aminities')
@section('page_title', 'Manage Hotel Aminities')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .amenities-table th, .amenities-table td {
        vertical-align: middle;
    }

    .amenity-icon-preview {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    /* CSS Toast */
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
        <p style="color: var(--text-muted); font-size: 14px;">Configure, update, or remove the guest aminities available at your hotel.</p>
    </div>
    <button onclick="openAddModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Aminity
    </button>
</div>

<!-- Table list -->
<div class="table-responsive">
    <table class="table amenities-table">
        <thead>
            <tr>
                <th style="width: 70px;">Icon</th>
                <th>Aminity Name</th>
                <th>Description</th>
                <th style="width: 120px;">Status</th>
                <th style="width: 150px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($amenities as $amenity)
                <tr id="amenity-row-{{ $amenity->id }}">
                    <td>
                        <div class="amenity-icon-preview">
                            <i class="{{ $amenity->icon }}"></i>
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--bg-dark); font-size: 15px;">{{ $amenity->name }}</strong>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 14px;">{{ $amenity->description ?: 'No description provided.' }}</span>
                    </td>
                    <td>
                        <!-- Status Toggle Switch -->
                        <label class="switch">
                            <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }})" {{ $amenity->status ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <button onclick="openViewModal({{ json_encode($amenity) }})" class="btn btn-outline btn-sm" title="View details" style="padding: 8px 10px;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button onclick="openEditModal({{ json_encode($amenity) }})" class="btn btn-outline btn-sm" title="Edit aminity" style="padding: 8px 10px;">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('hotel.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this aminity?');" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-danger-hover" title="Delete aminity" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                        <i class="fa-solid fa-spa" style="font-size: 40px; display: block; margin-bottom: 16px; color: var(--text-light);"></i>
                        No hotel aminities found. Click 'Add New Aminity' to create one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Amenity Modal -->
<div id="addAmenityModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add New Aminity</h3>
            <button onclick="closeAddModal()" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('hotel.amenities.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Aminity Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Free High-Speed Wi-Fi">
                </div>
                <div class="form-group">
                    <label class="form-label">FontAwesome Icon Class</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. fa-solid fa-wifi" value="fa-solid fa-square-check">
                    <small style="color: var(--text-muted); display: block; margin-top: 4px;">Use classes from FontAwesome v6 (e.g. <code>fa-solid fa-wifi</code>, <code>fa-solid fa-dumbbell</code>)</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe the aminity for guests..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Aminity</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Amenity Modal -->
<div id="editAmenityModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Aminity</h3>
            <button onclick="closeEditModal()" class="modal-close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Aminity Name</label>
                    <input type="text" name="name" id="editName" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">FontAwesome Icon Class</label>
                    <input type="text" name="icon" id="editIcon" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- View Amenity Modal -->
<div id="viewAmenityModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Aminity Details</h3>
            <button onclick="closeViewModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 30px 20px;">
            <div id="viewIconContainer" style="width: 70px; height: 70px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px auto;">
                <i id="viewIcon"></i>
            </div>
            <h3 id="viewName" style="font-size: 22px; font-weight: 700; color: var(--bg-dark); margin-bottom: 8px;"></h3>
            <span id="viewStatusBadge" class="badge"></span>
            
            <div style="border-top: 1px solid var(--border-color); margin: 24px 0 16px 0;"></div>
            
            <p id="viewDescription" style="color: var(--text-muted); line-height: 1.6; font-size: 15px; margin: 0;"></p>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" onclick="closeViewModal()" class="btn btn-outline" style="min-width: 120px;">Close</button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastNotification" class="toast-notification">
    <i class="fa-regular fa-circle-check" style="color: var(--success); font-size: 20px;"></i>
    <span id="toastMessage">Status updated successfully</span>
</div>
@endsection

@section('scripts')
<script>
    // Modal Selectors
    const addModal = document.getElementById('addAmenityModal');
    const editModal = document.getElementById('editAmenityModal');
    const viewModal = document.getElementById('viewAmenityModal');
    const editForm = document.getElementById('editForm');
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');

    // Add Modal control
    function openAddModal() {
        addModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        addModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Edit Modal control
    function openEditModal(amenity) {
        editForm.action = `/hotel/amenities/${amenity.id}`;
        document.getElementById('editName').value = amenity.name;
        document.getElementById('editIcon').value = amenity.icon;
        document.getElementById('editDescription').value = amenity.description || '';
        editModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        editModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // View Modal control
    function openViewModal(amenity) {
        document.getElementById('viewIcon').className = amenity.icon;
        document.getElementById('viewName').textContent = amenity.name;
        document.getElementById('viewDescription').textContent = amenity.description || 'No description provided for this aminity.';
        
        const badge = document.getElementById('viewStatusBadge');
        if (amenity.status) {
            badge.textContent = 'Active';
            badge.className = 'badge badge-success';
        } else {
            badge.textContent = 'Inactive';
            badge.className = 'badge badge-danger';
        }
        
        viewModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeViewModal() {
        viewModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Show Notification Toast
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

    // Toggle Amenity Status AJAX
    function toggleAmenityStatus(id) {
        fetch(`/hotel/amenities/${id}/toggle-status`, {
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
            showToast('Server error while toggling status', false);
        });
    }
</script>
@endsection
