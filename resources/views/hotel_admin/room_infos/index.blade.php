@extends('layouts.hotel_admin')

@section('title', 'Manage Room Info - Hotel Admin')
@section('page_title', 'Room Information Management')

@section('content')
<div class="space-y-8">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bed text-indigo-600"></i>
                Hotel Room Information
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Configure room details, features, images, and description for TV app presentation.</p>
        </div>
        <button onclick="openRoomInfoModal()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Room Info</span>
        </button>
    </div>

    <!-- Table of Room Infos -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-500"></i>
                Active Room Information List ({{ count($roomInfos) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 w-20">Sr No</th>
                        <th class="px-6 py-4 w-36">Image (16:9)</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 w-36 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($roomInfos as $info)
                        <tr id="room-info-row-{{ $info->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-indigo-600">#{{ $info->sr_no }}</td>
                            <td class="px-6 py-4">
                                @if($info->image)
                                    <img src="{{ asset($info->image) }}" alt="{{ $info->title }}" style="aspect-ratio: 16 / 9; width: 88px; object-fit: cover;" class="rounded-xl border border-slate-200 shadow-xs">
                                @else
                                    <div style="aspect-ratio: 16 / 9; width: 88px;" class="rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100 text-xs">
                                        #{{ $info->sr_no }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                                <span class="flex items-center gap-2">
                                    <i class="{{ $info->icon ?: 'fa-solid fa-bed' }} text-indigo-500"></i>
                                    {{ $info->title }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $info->description ?: 'No description provided.' }}</td>
                            <td class="px-6 py-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleRoomInfoStatus({{ $info->id }})" {{ $info->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <button onclick="triggerEditMode({{ json_encode($info) }})" title="Edit Room Info" class="p-2 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('hotel.room-infos.destroy', $info->id) }}" method="POST" onsubmit="return confirm('Delete this room info entry?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Room Info" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-bed text-4xl block mb-3 text-slate-300"></i>
                                No room info items found. Click 'Add New Room Info' to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="roomInfoFormModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 id="modalTitle" class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bed text-indigo-600"></i>
                <span>Add New Room Info</span>
            </h3>
            <button onclick="closeRoomInfoModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="roomInfoForm" action="{{ route('hotel.room-infos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Display Serial No *</label>
                    <input type="number" name="sr_no" id="sr_no" value="{{ count($roomInfos) + 1 }}" required min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Icon Class (FontAwesome)</label>
                    <input type="text" name="icon" id="icon" placeholder="fa-solid fa-bed" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Title *</label>
                <input type="text" name="title" id="title" placeholder="e.g. Deluxe Room Setup / In-Room Mini Bar" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Image (16:9 Format, Max 5MB)</label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Write room info details..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeRoomInfoModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 font-bold text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                    Save Room Info
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const storeUrl = "{{ route('hotel.room-infos.store') }}";

function openRoomInfoModal() {
    const modal = document.getElementById('roomInfoFormModal');
    const form = document.getElementById('roomInfoForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');

    form.reset();
    form.action = storeUrl;
    methodInput.value = 'POST';
    modalTitle.querySelector('span').innerText = 'Add New Room Info';

    modal.classList.remove('hidden');
}

function closeRoomInfoModal() {
    document.getElementById('roomInfoFormModal').classList.add('hidden');
}

function triggerEditMode(info) {
    const modal = document.getElementById('roomInfoFormModal');
    const form = document.getElementById('roomInfoForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');

    form.action = `/hotel/room-infos/${info.id}`;
    methodInput.value = 'PUT';
    modalTitle.querySelector('span').innerText = 'Edit Room Info';

    document.getElementById('sr_no').value = info.sr_no;
    document.getElementById('title').value = info.title;
    document.getElementById('icon').value = info.icon || '';
    document.getElementById('description').value = info.description || '';

    modal.classList.remove('hidden');
}

function toggleRoomInfoStatus(id) {
    fetch(`/hotel/room-infos/${id}/toggle-status`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            console.log(data.message);
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
