@extends('layouts.hotel_admin')

@section('title', 'Manage Menus - Hotel Admin')
@section('page_title', 'Global TV Menu Visibility')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Top Header Banner -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-xs shrink-0">
                <i class="fa-solid fa-list-check text-xl"></i>
            </div>
            <div class="space-y-0.5">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hotel Global Default TV Menus</h3>
                <p class="text-xs text-slate-500 font-medium">Control which features & menu items are visible on your hotel room Smart TV home screens.</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <span class="px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold">
                <i class="fa-solid fa-tv mr-1 text-indigo-600"></i> Applies to all room TVs
            </span>
        </div>
    </div>

    <!-- Main Settings Form -->
    <form id="menuForm" action="{{ url('/hotel/menus') }}" method="POST" class="space-y-8" data-swal-bypass="true" data-ajax-form="true">
        @csrf

        <!-- Interactive Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $menuIcons = [
                    'flights'     => 'fa-plane-departure',
                    'input'       => 'fa-plug',
                    'languages'   => 'fa-language',
                    'live_tv'     => 'fa-tv',
                    'our_city'    => 'fa-city',
                    'refresh'     => 'fa-arrows-rotate',
                    'screen_cast' => 'fa-display',
                    'settings'    => 'fa-gear',
                    'travel'      => 'fa-compass',
                    'weather'     => 'fa-cloud-sun'
                ];
                $menuColors = [
                    'flights'     => 'bg-sky-50 text-sky-600 border-sky-100',
                    'input'       => 'bg-slate-100 text-slate-700 border-slate-200',
                    'languages'   => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'live_tv'     => 'bg-rose-50 text-rose-600 border-rose-100',
                    'our_city'    => 'bg-amber-50 text-amber-600 border-amber-100',
                    'refresh'     => 'bg-blue-50 text-blue-600 border-blue-100',
                    'screen_cast' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'settings'    => 'bg-violet-50 text-violet-600 border-violet-100',
                    'travel'      => 'bg-teal-50 text-teal-600 border-teal-100',
                    'weather'     => 'bg-cyan-50 text-cyan-600 border-cyan-100'
                ];
            @endphp

            @foreach($defaultMenus as $menu)
                @php
                    $isShown = !isset($currentSettings[$menu['id']]) || $currentSettings[$menu['id']] !== 'hide';
                    $icon = $menuIcons[$menu['id']] ?? 'fa-circle-dot';
                    $colorStyle = $menuColors[$menu['id']] ?? 'bg-indigo-50 text-indigo-600 border-indigo-100';
                @endphp
                
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-11 h-11 rounded-2xl border flex items-center justify-center text-lg {{ $colorStyle }} shrink-0">
                                <i class="fa-solid {{ $icon }}"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900">{{ $menu['name'] }}</h4>
                                <span class="text-[11px] font-mono text-slate-400 font-medium">Key: {{ $menu['id'] }}</span>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="menus[{{ $menu['id'] }}]" value="1" {{ $isShown ? 'checked' : '' }} class="sr-only peer menu-toggle-checkbox">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] status-indicator-container">
                        <span class="text-slate-400 font-semibold">Visibility Status</span>
                        @if($isShown)
                            <span class="text-emerald-600 font-bold flex items-center status-badge"><i class="fa-solid fa-circle text-[8px] mr-1 text-emerald-500"></i> Visible on TV</span>
                        @else
                            <span class="text-slate-400 font-bold flex items-center status-badge"><i class="fa-solid fa-circle text-[8px] mr-1 text-slate-300"></i> Hidden from TV</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Submit Controls -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
            <button type="submit" id="saveMenuBtn" class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span id="saveBtnText">Save Global Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Update status text on toggle change locally
    document.querySelectorAll('.menu-toggle-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const card = this.closest('.bg-white');
            const statusContainer = card.querySelector('.status-indicator-container');
            if (this.checked) {
                statusContainer.innerHTML = '<span class="text-slate-400 font-semibold">Visibility Status</span><span class="text-emerald-600 font-bold flex items-center status-badge"><i class="fa-solid fa-circle text-[8px] mr-1 text-emerald-500"></i> Visible on TV</span>';
            } else {
                statusContainer.innerHTML = '<span class="text-slate-400 font-semibold">Visibility Status</span><span class="text-slate-400 font-bold flex items-center status-badge"><i class="fa-solid fa-circle text-[8px] mr-1 text-slate-300"></i> Hidden from TV</span>';
            }
        });
    });

    // Async AJAX Form Submit handler
    document.getElementById('menuForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('saveMenuBtn');
        const btnText = document.getElementById('saveBtnText');
        const originalText = btnText.innerText;

        btn.disabled = true;
        btnText.innerText = 'Syncing Realtime...';
        btn.classList.add('opacity-80');

        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                Toast.fire({
                    icon: 'success',
                    title: 'Realtime TV Sync Complete! ⚡'
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message || 'Failed to update settings.'
                });
            }
        } catch (error) {
            console.error('Save error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Network error while syncing.'
            });
        } finally {
            btn.disabled = false;
            btnText.innerText = originalText;
            btn.classList.remove('opacity-80');
        }
    });
</script>
@endsection
