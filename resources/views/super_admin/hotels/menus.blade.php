@extends('layouts.super_admin')

@section('title', 'Manage TV Menus - ' . $hotel->hotel_name)
@section('page_title', 'Smart TV Menu Builder - ' . $hotel->hotel_name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Breadcrumb & Hotel Overview Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-xs shrink-0">
                <i class="fa-solid fa-list-check text-xl"></i>
            </div>
            <div class="space-y-0.5">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Hotels
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs font-bold text-slate-600">{{ $hotel->hotel_name }}</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $hotel->hotel_name }} — TV Menus</h3>
                <p class="text-xs text-slate-500 font-medium">License Key: <code class="bg-slate-100 px-1.5 py-0.5 rounded font-mono text-indigo-600">{{ $hotel->license_key ?? 'N/A' }}</code> &bull; Rooms: {{ $hotel->room_count }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <button type="button" id="resetDefaultBtn" class="px-4 py-2.5 rounded-2xl border border-rose-200 hover:bg-rose-50 text-rose-600 text-xs font-bold transition-all flex items-center space-x-2">
                <i class="fa-solid fa-rotate-left"></i>
                <span>Reset to Default</span>
            </button>
        </div>
    </div>

    <!-- Instructions / Tips Bar -->
    <div class="bg-gradient-to-r from-amber-50/60 via-indigo-50/40 to-slate-50 border border-amber-200/60 rounded-2xl p-4 text-xs text-slate-600 flex items-center justify-between gap-4">
        <div class="flex items-center space-x-2.5">
            <i class="fa-solid fa-circle-info text-amber-600 text-sm shrink-0"></i>
            <span><strong>Super Admin Override:</strong> Customize this hotel's TV home screen navigation structure. Reorder, group, hide, or extract menus as needed.</span>
        </div>
        <span class="px-2.5 py-1 rounded-full bg-white border border-amber-200 text-amber-700 text-[10px] font-bold uppercase shrink-0">Real-Time Sync</span>
    </div>

    <!-- Menu Builder Area -->
    <div class="space-y-4">
        <div id="rootMenuList" class="space-y-4 min-h-[150px]">
            @foreach($currentTree as $item)
                @php
                    $isGroup = isset($item['sub_menus']) && is_array($item['sub_menus']);
                    $isShown = ($item['status'] ?? 'show') !== 'hide';
                @endphp

                @if($isGroup)
                    <!-- Group / Container Node -->
                    <div class="menu-node group-card bg-white border-2 border-indigo-100/90 rounded-3xl p-5 shadow-sm space-y-4" data-id="{{ $item['id'] }}" data-is-group="1">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center space-x-3">
                                <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </span>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                                        <span>{{ $item['name'] }}</span>
                                        <span class="px-2 py-0.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-bold uppercase">Parent Group</span>
                                    </h4>
                                    <span class="text-[11px] font-mono text-slate-400">ID: {{ $item['id'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                                </label>
                                <span class="status-badge text-[11px] font-bold {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $isShown ? 'Visible' : 'Hidden' }}
                                </span>
                            </div>
                        </div>

                        <!-- Sub-menu dropzone -->
                        <div class="sub-menu-dropzone pl-4 sm:pl-8 space-y-3 min-h-[50px] p-2 rounded-2xl bg-slate-50/60 border border-dashed border-slate-200" data-parent-id="{{ $item['id'] }}">
                            @foreach($item['sub_menus'] as $subItem)
                                @php $isSubShown = ($subItem['status'] ?? 'show') !== 'hide'; @endphp
                                <div class="menu-node sub-item bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-xs flex items-center justify-between gap-3 hover:border-indigo-200 transition-all" data-id="{{ $subItem['id'] }}" data-is-group="0">
                                    <div class="flex items-center space-x-3">
                                        <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                                            <i class="fa-solid fa-grip-vertical"></i>
                                        </span>
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-cube text-xs"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-800">{{ $subItem['name'] }}</h5>
                                            <span class="text-[10px] font-mono text-slate-400">ID: {{ $subItem['id'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <button type="button" class="extract-to-root-btn px-2.5 py-1 rounded-xl border border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-slate-600 text-[11px] font-bold transition-all" title="Extract this menu to top-level">
                                            <i class="fa-solid fa-arrow-up-from-bracket mr-1 text-[10px]"></i> Extract to Root
                                        </button>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isSubShown ? 'checked' : '' }}>
                                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-600"></div>
                                        </label>
                                        <span class="status-badge text-[10px] font-bold {{ $isSubShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $isSubShown ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Standalone Item at Root Level -->
                    <div class="menu-node standalone-item bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm flex items-center justify-between gap-4 hover:border-indigo-200 transition-all" data-id="{{ $item['id'] }}" data-is-group="0">
                        <div class="flex items-center space-x-3.5">
                            <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </span>
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-bars text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900">{{ $item['name'] }}</h4>
                                <span class="text-[11px] font-mono text-slate-400">ID: {{ $item['id'] }}</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3.5">
                            <!-- Move into group selector -->
                            <select class="move-into-group-select text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 focus:outline-none focus:border-indigo-400 bg-white">
                                <option value="">Move into Group...</option>
                                <option value="hotel_menu">Hotel Menu</option>
                                <option value="interactive_services">Interactive Services</option>
                            </select>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer node-status-checkbox" {{ $isShown ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                            </label>
                            <span class="status-badge text-[11px] font-bold {{ $isShown ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $isShown ? 'Visible' : 'Hidden' }}
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Floating / Sticky Save Bar -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex items-center justify-between sticky bottom-6 z-20">
        <div class="text-xs text-slate-500 font-medium">
            <i class="fa-solid fa-cloud-arrow-up text-rose-600 mr-1.5"></i> Changes will sync instantly to all TVs assigned to <strong>{{ $hotel->hotel_name }}</strong>.
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('super-admin.hotels.index') }}" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
            <button type="button" id="saveMenuBtn" class="px-8 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span id="saveBtnText">Save & Sync Hotel TV Menus</span>
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const itemCatalog = @json($itemCatalog);
    const saveUrl = "{{ url('/super-admin/hotels/' . $hotel->id . '/menus') }}";
    const resetUrl = "{{ route('super-admin.hotels.menus.reset', $hotel->id) }}";
    const csrfToken = "{{ csrf_token() }}";

    // Initialize Sortable on Root List
    const rootEl = document.getElementById('rootMenuList');
    new Sortable(rootEl, {
        group: {
            name: 'nested-menus',
            pull: true,
            put: ['nested-menus']
        },
        handle: '.drag-handle',
        animation: 180,
        ghostClass: 'bg-rose-50/80',
        onEnd: updateDomState
    });

    // Initialize Sortable on every Sub-menu Dropzone
    document.querySelectorAll('.sub-menu-dropzone').forEach(dropzone => {
        new Sortable(dropzone, {
            group: {
                name: 'nested-menus',
                pull: true,
                put: ['nested-menus']
            },
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'bg-rose-50/80',
            onEnd: updateDomState
        });
    });

    // Delegate status switch toggles
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('node-status-checkbox')) {
            const badge = e.target.closest('.menu-node').querySelector('.status-badge');
            if (badge) {
                badge.textContent = e.target.checked ? 'Visible' : 'Hidden';
                badge.className = 'status-badge text-[11px] font-bold ' + (e.target.checked ? 'text-emerald-600' : 'text-slate-400');
            }
        }
    });

    // Quick Action: Extract to Root
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.extract-to-root-btn');
        if (btn) {
            const subItem = btn.closest('.sub-item');
            if (subItem) {
                const id = subItem.dataset.id;
                const isChecked = subItem.querySelector('.node-status-checkbox').checked;
                const meta = itemCatalog[id] || { name: id };

                const newCard = document.createElement('div');
                newCard.className = 'menu-node standalone-item bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm flex items-center justify-between gap-4 hover:border-indigo-200 transition-all';
                newCard.dataset.id = id;
                newCard.dataset.isGroup = '0';
                newCard.innerHTML = `
                    <div class="flex items-center space-x-3.5">
                        <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </span>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bars text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900">${meta.name}</h4>
                            <span class="text-[11px] font-mono text-slate-400">ID: ${id}</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3.5">
                        <select class="move-into-group-select text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 focus:outline-none focus:border-indigo-400 bg-white">
                            <option value="">Move into Group...</option>
                            <option value="hotel_menu">Hotel Menu</option>
                            <option value="interactive_services">Interactive Services</option>
                        </select>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer node-status-checkbox" ${isChecked ? 'checked' : ''}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                        </label>
                        <span class="status-badge text-[11px] font-bold ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
                            ${isChecked ? 'Visible' : 'Hidden'}
                        </span>
                    </div>
                `;
                subItem.remove();
                rootEl.appendChild(newCard);
                updateDomState();
            }
        }
    });

    // Quick Action: Move into Group select dropdown
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('move-into-group-select')) {
            const targetGroupId = e.target.value;
            if (!targetGroupId) return;

            const standaloneCard = e.target.closest('.standalone-item');
            if (standaloneCard) {
                const targetDropzone = document.querySelector(`.sub-menu-dropzone[data-parent-id="${targetGroupId}"]`);
                if (targetDropzone) {
                    const id = standaloneCard.dataset.id;
                    const isChecked = standaloneCard.querySelector('.node-status-checkbox').checked;
                    const meta = itemCatalog[id] || { name: id };

                    const newSub = document.createElement('div');
                    newSub.className = 'menu-node sub-item bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-xs flex items-center justify-between gap-3 hover:border-indigo-200 transition-all';
                    newSub.dataset.id = id;
                    newSub.dataset.isGroup = '0';
                    newSub.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </span>
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cube text-xs"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">${meta.name}</h5>
                                <span class="text-[10px] font-mono text-slate-400">ID: ${id}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" class="extract-to-root-btn px-2.5 py-1 rounded-xl border border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-slate-600 text-[11px] font-bold transition-all" title="Extract this menu to top-level">
                                <i class="fa-solid fa-arrow-up-from-bracket mr-1 text-[10px]"></i> Extract to Root
                            </button>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer node-status-checkbox" ${isChecked ? 'checked' : ''}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-600"></div>
                            </label>
                            <span class="status-badge text-[10px] font-bold ${isChecked ? 'text-emerald-600' : 'text-slate-400'}">
                                ${isChecked ? 'Visible' : 'Hidden'}
                            </span>
                        </div>
                    `;
                    standaloneCard.remove();
                    targetDropzone.appendChild(newSub);
                    updateDomState();
                }
            }
        }
    });

    function buildMenuHierarchy() {
        const tree = [];
        const rootNodes = rootEl.children;

        for (let node of rootNodes) {
            if (!node.classList.contains('menu-node')) continue;
            const id = node.dataset.id;
            const meta = itemCatalog[id] || { name: id, icon: 'assets/images/icons/hotelinfo.png' };
            const statusCheckbox = node.querySelector(':scope > div .node-status-checkbox') || node.querySelector('.node-status-checkbox');
            const isShown = statusCheckbox ? statusCheckbox.checked : true;

            const isGroup = node.classList.contains('group-card');
            const itemObj = {
                id: id,
                name: meta.name,
                icon: meta.icon,
                status: isShown ? 'show' : 'hide'
            };

            if (isGroup) {
                const dropzone = node.querySelector('.sub-menu-dropzone');
                const subMenus = [];
                if (dropzone) {
                    for (let subNode of dropzone.children) {
                        if (!subNode.classList.contains('menu-node')) continue;
                        const subId = subNode.dataset.id;
                        const subMeta = itemCatalog[subId] || { name: subId, icon: 'assets/images/icons/hotelinfo.png' };
                        const subCheck = subNode.querySelector('.node-status-checkbox');
                        subMenus.push({
                            id: subId,
                            name: subMeta.name,
                            icon: subMeta.icon,
                            status: (subCheck && subCheck.checked) ? 'show' : 'hide'
                        });
                    }
                }
                itemObj.sub_menus = subMenus;
            }

            tree.push(itemObj);
        }

        return tree;
    }

    function updateDomState() {}

    // Save Menus
    document.getElementById('saveMenuBtn').addEventListener('click', function() {
        const hierarchy = buildMenuHierarchy();
        const btn = document.getElementById('saveMenuBtn');
        const originalText = document.getElementById('saveBtnText').textContent;

        btn.disabled = true;
        document.getElementById('saveBtnText').textContent = 'Saving & Syncing...';

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menus_hierarchy: hierarchy })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            document.getElementById('saveBtnText').textContent = originalText;

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Hotel TV Menus Synced!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: data.message || 'Something went wrong.'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            document.getElementById('saveBtnText').textContent = originalText;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to communicate with the server.'
            });
        });
    });

    // Reset to Default
    document.getElementById('resetDefaultBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Hotel TV Menus?',
            text: 'This will restore the standard default menu ordering, grouping, and icons for this hotel.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, reset to default'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(resetUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reset Successful',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });

</script>
@endsection
