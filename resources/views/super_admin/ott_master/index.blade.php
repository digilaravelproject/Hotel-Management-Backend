@extends('layouts.super_admin')

@section('title', 'Manage Applications & OTT Master - Super Admin')
@section('page_title', 'Applications Master Catalog')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shadow-xs shrink-0">
                <i class="fa-solid fa-play text-xl"></i>
            </div>
            <div class="space-y-0.5">
                <div class="inline-flex items-center space-x-2 px-2.5 py-0.5 rounded-full bg-purple-50 text-[#150A42] text-[10px] font-bold font-mono uppercase tracking-wider mb-1">
                    <span>OTT & TV APPS REPOSITORY</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Applications & OTT Master Catalog</h3>
                <p class="text-xs text-slate-500 font-medium">Add and manage custom applications, Play Store, and streaming catalog available to subscription plans.</p>
            </div>
        </div>

        <button type="button" onclick="openAddAppModal()" class="btn-sa-primary px-5 py-2.5 text-xs shadow-md flex items-center space-x-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Application</span>
        </button>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-[26px] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800 border-collapse">
                <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-16 text-center font-mono">Sr No</th>
                        <th class="px-5 py-3.5">App / Platform Name</th>
                        <th class="px-5 py-3.5">Package Name (Play Store ID)</th>
                        <th class="px-5 py-3.5 text-center w-28">Status</th>
                        <th class="px-5 py-3.5 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($platforms as $index => $app)
                        <tr id="app-row-{{ $app->id }}" class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-3.5 text-center font-mono text-slate-400 font-bold">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-3.5 font-extrabold text-slate-900 text-xs">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center shrink-0 shadow-2xs">
                                        <i class="fa-solid fa-play text-xs"></i>
                                    </div>
                                    <span>{{ $app->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <code class="px-2 py-0.5 rounded-md bg-[#F8F9FD] border border-slate-200 font-mono text-slate-700 text-[11px]">{{ $app->package_name }}</code>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <label class="relative inline-flex items-center cursor-pointer" title="Toggle active status">
                                    <input type="checkbox" onchange="toggleAppStatus({{ $app->id }})" {{ $app->status ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                                </label>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="inline-flex items-center space-x-1.5 justify-center">
                                    <button type="button" onclick='openEditAppModal(@json($app))' class="w-8 h-8 rounded-xl border border-slate-200 hover:border-purple-200 bg-white hover:bg-purple-50 text-slate-500 hover:text-[#150A42] flex items-center justify-center transition-all cursor-pointer" title="Edit App">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('super-admin.ott-master.destroy', $app->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteApp(this)" class="w-8 h-8 rounded-xl border border-slate-200 hover:border-rose-200 bg-white hover:bg-rose-50 text-slate-500 hover:text-rose-600 flex items-center justify-center transition-all cursor-pointer" title="Delete App">
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
                                    <i class="fa-solid fa-play"></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700 mb-0.5">No Applications Registered</h4>
                                <p class="text-[11px] text-slate-400">Click "Add New Application" to add apps to your OTT master catalog.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Add App Modal -->
<div id="addAppModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h4 class="text-sm font-extrabold text-slate-900">Add New Application / OTT</h4>
            <button type="button" onclick="closeAddAppModal()" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('super-admin.ott-master.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Application Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Netflix / Hotstar" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium">
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Android Package Name (Play Store ID) <span class="text-rose-500">*</span></label>
                <input type="text" name="package_name" required placeholder="e.g. com.netflix.ninja" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-mono">
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeAddAppModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-xs">Add Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit App Modal -->
<div id="editAppModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h4 class="text-sm font-extrabold text-slate-900">Edit Application / OTT</h4>
            <button type="button" onclick="closeEditAppModal()" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editAppForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Application Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-medium">
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Android Package Name (Play Store ID) <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_package_name" name="package_name" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white text-slate-900 font-mono">
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                <button type="button" onclick="closeEditAppModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-xs">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddAppModal() {
        document.getElementById('addAppModal').classList.remove('hidden');
    }
    function closeAddAppModal() {
        document.getElementById('addAppModal').classList.add('hidden');
    }
    function openEditAppModal(app) {
        document.getElementById('editAppForm').action = '/super-admin/ott-master/' + app.id;
        document.getElementById('edit_name').value = app.name;
        document.getElementById('edit_package_name').value = app.package_name;
        document.getElementById('editAppModal').classList.remove('hidden');
    }
    function closeEditAppModal() {
        document.getElementById('editAppModal').classList.add('hidden');
    }

    function confirmDeleteApp(btn) {
        Swal.fire({
            title: 'Delete Application?',
            text: 'Are you sure you want to remove this app from the master catalog?',
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

    function toggleAppStatus(id) {
        fetch('/super-admin/ott-master/' + id + '/toggle-status')
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
                }
            })
            .catch(err => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error updating status',
                    showConfirmButton: false,
                    timer: 2500
                });
            });
    }
</script>
@endsection