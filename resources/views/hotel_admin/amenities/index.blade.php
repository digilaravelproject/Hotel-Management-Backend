@extends('layouts.hotel_admin')

@section('title', 'Manage Amenities - Hotel Admin')
@section('page_title', 'Manage Hotel Amenities')

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

    .amenity-img-preview {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 1px solid var(--border-color);
        background-color: #f8fafc;
    }

    .amenity-icon-fallback {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
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
        <p style="color: var(--text-muted); font-size: 14px;">Configure, reorder, or update guest amenities available at your hotel for smart TV display.</p>
    </div>
    <button onclick="openAddModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Amenity
    </button>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">
        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
    </div>
@endif

<!-- Table list -->
<div class="table-responsive">
    <table class="table amenities-table">
        <thead>
            <tr>
                <th style="width: 80px;">Sr No</th>
                <th style="width: 80px;">Image</th>
                <th>Amenity Title</th>
                <th>Description</th>
                <th style="width: 120px;">Status</th>
                <th style="width: 150px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($amenities as $amenity)
                <tr id="amenity-row-{{ $amenity->id }}">
                    <td>
                        <span style="font-weight: 700; color: var(--primary); font-size: 15px;">#{{ $amenity->sr_no }}</span>
                    </td>
                    <td>
                        @if($amenity->image)
                            <img src="{{ asset($amenity->image) }}" alt="{{ $amenity->name }}" class="amenity-img-preview">
                        @else
                            <div class="amenity-img-preview" style="display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                                #{{ $amenity->sr_no }}
                            </div>
                        @endif
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
                            <button onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}" class="btn btn-outline btn-sm" title="View details" style="padding: 8px 10px;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button onclick="openEditModal(this)" data-amenity="{{ json_encode($amenity) }}" class="btn btn-outline btn-sm" title="Edit amenity" style="padding: 8px 10px;">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('hotel.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this amenity?');" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-danger-hover" title="Delete amenity" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                        <i class="fa-solid fa-spa" style="font-size: 40px; display: block; margin-bottom: 16px; color: var(--text-light);"></i>
                        No hotel amenities found. Click 'Add New Amenity' to create one.
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
            <h3>Add New Amenity</h3>
            <button onclick="closeAddModal()" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('hotel.amenities.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateImageSize('add_image_input')">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Serial Number / Display Order</label>
                    <input type="number" name="sr_no" min="1" required class="form-control" value="{{ count($amenities) + 1 }}" placeholder="e.g. 1">
                </div>
                <div class="form-group">
                    <label class="form-label">Amenity Title</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Swimming Pool, Fitness Gym">
                </div>
                <div class="form-group">
                    <label class="form-label">Amenity Image (Max 2MB: JPG, PNG, WEBP, SVG)</label>
                    <input type="file" name="image" id="add_image_input" accept=".jpg,.jpeg,.png,.webp,.svg" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe the amenity for hotel guests..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Amenity</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Amenity Modal -->
<div id="editAmenityModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Amenity</h3>
            <button onclick="closeEditModal()" class="modal-close">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" onsubmit="return validateImageSize('edit_image_input')">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Serial Number / Display Order</label>
                    <input type="number" name="sr_no" id="editSrNo" min="1" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Amenity Title</label>
                    <input type="text" name="name" id="editName" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Change Image (Max 2MB: JPG, PNG, WEBP, SVG)</label>
                    <input type="file" name="image" id="edit_image_input" accept=".jpg,.jpeg,.png,.webp,.svg" class="form-control">
                    <div id="editImageCurrent" style="margin-top: 6px; font-size: 12px; color: var(--text-muted);"></div>
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
            <h3>Amenity Details</h3>
            <button onclick="closeViewModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 30px 20px;">
            <div id="viewImagePreview" style="margin-bottom: 20px;"></div>
            <h3 id="viewName" style="font-size: 22px; font-weight: 700; color: var(--bg-dark); margin-bottom: 4px;"></h3>
            <div style="font-size: 13px; color: var(--primary); font-weight: 700; margin-bottom: 12px;">Display Order #<span id="viewSrNo"></span></div>
            <span id="viewStatusBadge" class="badge"></span>
            
            <div style="border-top: 1px solid var(--border-color); margin: 20px 0 16px 0;"></div>
            
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
    const addModal = document.getElementById('addAmenityModal');
    const editModal = document.getElementById('editAmenityModal');
    const viewModal = document.getElementById('viewAmenityModal');
    const editForm = document.getElementById('editForm');
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');

    function validateImageSize(inputId) {
        const input = document.getElementById(inputId);
        if (input && input.files && input.files[0]) {
            const fileSize = input.files[0].size / 1024 / 1024; // MB
            if (fileSize > 2) {
                alert('Image size exceeds 2 MB limit! Please choose a smaller image file under 2 MB.');
                return false;
            }
        }
        return true;
    }

    function openAddModal() {
        addModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        addModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(button) {
        const amenity = JSON.parse(button.getAttribute('data-amenity'));
        editForm.action = `/hotel/amenities/${amenity.id}`;
        document.getElementById('editSrNo').value = amenity.sr_no;
        document.getElementById('editName').value = amenity.name;
        document.getElementById('editDescription').value = amenity.description || '';
        
        const currentContainer = document.getElementById('editImageCurrent');
        if (amenity.image) {
            currentContainer.innerHTML = 'Current image: <a href="/' + amenity.image + '" target="_blank" style="color: var(--primary);">View Current File</a>';
        } else {
            currentContainer.innerHTML = 'No image uploaded yet.';
        }

        editModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        editModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openViewModal(button) {
        const amenity = JSON.parse(button.getAttribute('data-amenity'));
        document.getElementById('viewName').textContent = amenity.name;
        document.getElementById('viewSrNo').textContent = amenity.sr_no;
        document.getElementById('viewDescription').textContent = amenity.description || 'No description provided for this amenity.';
        
        const imgContainer = document.getElementById('viewImagePreview');
        if (amenity.image) {
            imgContainer.innerHTML = `<img src="/${amenity.image}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border-color);">`;
        } else {
            imgContainer.innerHTML = `<div style="width: 70px; height: 70px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 24px; margin: 0 auto;">#${amenity.sr_no}</div>`;
        }

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
