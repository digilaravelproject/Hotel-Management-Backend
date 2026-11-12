@extends('layouts.super_admin')

@section('title', 'Manage Hotel Amenities - ' . $hotel->hotel_name)
@section('page_title', 'Hotel Amenities - ' . $hotel->hotel_name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Breadcrumb & Hotel Header Card -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shadow-xs shrink-0">
                <i class="fa-solid fa-spa text-xl"></i>
            </div>
            <div class="space-y-0.5">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-bold text-slate-400 hover:text-[#150A42] transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Hotels Directory
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs font-bold text-slate-600">{{ $hotel->hotel_name }}</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $hotel->hotel_name }} — Guest Amenities</h3>
                <p class="text-xs text-slate-500 font-medium">
                    Owner: <span class="font-bold text-slate-700">{{ $hotel->owner_name }}</span> &bull; 
                    Rooms: <span class="font-mono font-bold text-slate-700">{{ $hotel->room_count }}</span> &bull; 
                    License Key: <code class="bg-[#F8F9FD] border border-slate-200 px-1.5 py-0.5 rounded font-mono text-[#150A42] text-[11px]">{{ $hotel->license_key ?? 'N/A' }}</code>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all flex items-center space-x-2">
                <i class="fa-regular fa-eye text-xs"></i>
                <span>View Hotel</span>
            </a>
            <a href="{{ route('super-admin.hotels.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Hotels</span>
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800 border-collapse">
                <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3.5 w-16 text-center">Icon</th>
                        <th class="px-4 py-3.5">Amenity Name</th>
                        <th class="px-4 py-3.5">Description</th>
                        <th class="px-4 py-3.5 text-center w-28">Status</th>
                        <th class="px-4 py-3.5 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($amenities as $amenity)
                        <tr id="amenity-row-{{ $amenity->id }}" class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-4 py-3.5 text-center">
                                <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center mx-auto text-sm shadow-2xs">
                                    <i class="{{ $amenity->icon ?: 'fa-solid fa-circle-check' }}"></i>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900 text-xs">
                                {{ $amenity->name }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 max-w-md">
                                {{ $amenity->description ?: 'No detailed description provided.' }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <label class="relative inline-flex items-center cursor-pointer" title="Toggle amenity status">
                                    <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }})" {{ $amenity->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="inline-flex items-center space-x-1.5 justify-center">
                                    <button type="button" onclick="openEditModal(this)" data-amenity="{{ json_encode($amenity) }}" class="w-8 h-8 rounded-xl border border-slate-200 hover:border-purple-200 bg-white hover:bg-purple-50 text-slate-500 hover:text-[#150A42] flex items-center justify-center transition-all cursor-pointer" title="Edit Amenity">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('super-admin.amenities.destroy', $amenity->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAmenity(this)" class="w-8 h-8 rounded-xl border border-slate-200 hover:border-rose-200 bg-white hover:bg-rose-50 text-slate-500 hover:text-rose-600 flex items-center justify-center transition-all cursor-pointer" title="Delete Amenity">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-[#F8F9FD] border border-slate-200 flex items-center justify-center mx-auto text-slate-400 text-lg mb-3">
                                    <i class="fa-solid fa-spa"></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700 mb-0.5">No Amenities Found</h4>
                                <p class="text-[11px] text-slate-400">This hotel does not have any guest amenities registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Amenity Modal -->
<div id="editAmenityModal" class="fixed inset-0 bg-[#0A051B]/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] border border-slate-200/90 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-extrabold text-slate-900">Edit Amenity</h4>
            <button type="button" onclick="closeEditModal()" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Amenity Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="editName" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">FontAwesome Icon Class</label>
                <input type="text" name="icon" id="editIcon" placeholder="e.g. fa-solid fa-wifi" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-mono">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                <textarea name="description" id="editDescription" rows="3" placeholder="Brief description of the amenity..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium resize-y"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-xs">Update Amenity</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const editModal = document.getElementById('editAmenityModal');
    const editForm = document.getElementById('editForm');

    function openEditModal(button) {
        const amenity = JSON.parse(button.getAttribute('data-amenity'));
        editForm.action = `/super-admin/amenities/${amenity.id}`;
        document.getElementById('editName').value = amenity.name;
        document.getElementById('editIcon').value = amenity.icon || '';
        document.getElementById('editDescription').value = amenity.description || '';
        editModal.classList.remove('hidden');
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
    }

    function confirmDeleteAmenity(btn) {
        Swal.fire({
            title: 'Delete Amenity?',
            text: 'Are you sure you want to remove this amenity from the hotel list?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3xl border border-slate-200 shadow-2xl font-sans',
                confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-md bg-rose-600',
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs bg-slate-200 text-slate-800'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }

    function toggleAmenityStatus(id) {
        fetch(`/super-admin/amenities/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message || 'Status updated',
                    showConfirmButton: false,
                    timer: 2500
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to update status',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
        .catch(err => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Server error while toggling status',
                showConfirmButton: false,
                timer: 3000
            });
        });
    }
</script>
@endsection