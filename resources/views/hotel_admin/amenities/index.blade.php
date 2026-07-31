@extends('layouts.hotel_admin')

@section('title', 'Manage Amenities - Hotel Admin')
@section('page_title', 'Hotel Amenities Management')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">Configure, reorder, or update guest amenities available at your hotel for smart TV display.</p>
        </div>
        <button onclick="openAddModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Amenity</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-20">Image</th>
                        <th class="px-6 py-4">Amenity Title</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($amenities as $amenity)
                        <tr id="amenity-row-{{ $amenity->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-indigo-600">#{{ $amenity->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($amenity->image)
                                    <img src="{{ asset($amenity->image) }}" alt="{{ $amenity->name }}" class="w-11 h-11 rounded-xl object-cover border border-slate-200">
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100 text-xs">
                                        #{{ $amenity->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $amenity->name }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $amenity->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleAmenityStatus({{ $amenity->id }})" {{ $amenity->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="openViewModal(this)" data-amenity="{{ json_encode($amenity) }}" class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button onclick="openEditModal(this)" data-amenity="{{ json_encode($amenity) }}" class="p-2 rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Delete this amenity?');" class="inline">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-spa text-3xl block mb-2 text-slate-300"></i>
                                No hotel amenities found. Click 'Add New Amenity' to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addAmenityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900">Add New Amenity</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('hotel.amenities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Display Order (Sr No)</label>
                <input type="number" name="sr_no" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500" value="{{ count($amenities) + 1 }}">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Amenity Title</label>
                <input type="text" name="name" required placeholder="e.g. Infinity Pool, Gym" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Amenity Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Description</label>
                <textarea name="description" rows="3" placeholder="Brief details for TV display..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md">Add Amenity</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editAmenityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900">Edit Amenity</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Display Order (Sr No)</label>
                <input type="number" name="sr_no" id="editSrNo" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Amenity Title</label>
                <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Change Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Description</label>
                <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewAmenityModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-sm p-6 text-center space-y-4 shadow-2xl">
        <div id="viewImagePreview"></div>
        <h3 id="viewName" class="text-lg font-extrabold text-slate-900"></h3>
        <p id="viewDescription" class="text-xs text-slate-500 leading-relaxed"></p>
        <button type="button" onclick="closeViewModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Close</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() { document.getElementById('addAmenityModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addAmenityModal').classList.add('hidden'); }

    function openEditModal(btn) {
        const amenity = JSON.parse(btn.getAttribute('data-amenity'));
        document.getElementById('editForm').action = `/hotel/amenities/${amenity.id}`;
        document.getElementById('editSrNo').value = amenity.sr_no;
        document.getElementById('editName').value = amenity.name;
        document.getElementById('editDescription').value = amenity.description || '';
        document.getElementById('editAmenityModal').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('editAmenityModal').classList.add('hidden'); }

    function openViewModal(btn) {
        const amenity = JSON.parse(btn.getAttribute('data-amenity'));
        document.getElementById('viewName').textContent = amenity.name;
        document.getElementById('viewDescription').textContent = amenity.description || 'No description provided.';
        const imgContainer = document.getElementById('viewImagePreview');
        if (amenity.image) {
            imgContainer.innerHTML = `<img src="/${amenity.image}" class="w-20 h-20 rounded-2xl mx-auto object-cover border border-slate-200">`;
        } else {
            imgContainer.innerHTML = `<div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 font-bold text-lg flex items-center justify-center mx-auto">#${amenity.sr_no}</div>`;
        }
        document.getElementById('viewAmenityModal').classList.remove('hidden');
    }
    function closeViewModal() { document.getElementById('viewAmenityModal').classList.add('hidden'); }

    function toggleAmenityStatus(id) {
        fetch(`/hotel/amenities/${id}/toggle-status`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
    }
</script>
@endsection
