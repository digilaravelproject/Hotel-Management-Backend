@extends('layouts.super_admin')

@section('title', 'Manage Applications & OTT Master - Super Admin')
@section('page_title', 'Manage OTTs / Applications Master Catalog')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')
<div class="action-header">
    <div>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Add and manage custom applications, Play Store, and OTT platform catalog for subscription plans.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openAddAppModal()">
        <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Add New Application
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">
        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="table-responsive" style="background-color: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <table class="table">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>App / Platform Name</th>
                <th>Package Name (Play Store ID)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($platforms as $index => $app)
                <tr id="app-row-{{ $app->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; background: #e0f2fe; color: #0369a1; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                <i class="fa-solid fa-play" style="font-size: 12px;"></i>
                            </div>
                            <span style="font-weight: 700; color: #1e293b;">{{ $app->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px; color: var(--text-main); font-weight: 600;">{{ $app->package_name }}</span>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" onchange="toggleAppStatus({{ $app->id }})" {{ $app->status ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn btn-outline btn-sm" onclick='openEditAppModal(@json($app))' title="Edit App">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('super-admin.ott-master.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.2);" title="Delete App">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No applications found in master catalog.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add App Modal -->
<div id="addAppModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add New Application / OTT</h3>
            <button type="button" onclick="closeAddAppModal()" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('super-admin.ott-master.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Application Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Google Play Store">
                </div>
                <div class="form-group">
                    <label class="form-label">Android Package Name (Play Store ID)</label>
                    <input type="text" name="package_name" required class="form-control" placeholder="e.g. com.android.vending">
                </div>
            </div>
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAddAppModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit App Modal -->
<div id="editAppModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Application / OTT</h3>
            <button type="button" onclick="closeEditAppModal()" class="modal-close">&times;</button>
        </div>
        <form id="editAppForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Application Name</label>
                    <input type="text" id="edit_name" name="name" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Android Package Name (Play Store ID)</label>
                    <input type="text" id="edit_package_name" name="package_name" required class="form-control">
                </div>
            </div>
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditAppModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openAddAppModal() {
        document.getElementById('addAppModal').classList.add('active');
    }
    function closeAddAppModal() {
        document.getElementById('addAppModal').classList.remove('active');
    }
    function openEditAppModal(app) {
        document.getElementById('editAppForm').action = '/super-admin/ott-master/' + app.id;
        document.getElementById('edit_name').value = app.name;
        document.getElementById('edit_package_name').value = app.package_name;
        document.getElementById('editAppModal').classList.add('active');
    }
    function closeEditAppModal() {
        document.getElementById('editAppModal').classList.remove('active');
    }
    function toggleAppStatus(id) {
        fetch('/super-admin/ott-master/' + id + '/toggle-status')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                }
            })
            .catch(err => console.error(err));
    }
</script>
@endsection
