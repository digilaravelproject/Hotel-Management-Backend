@extends('layouts.hotel_admin')

@section('title', 'Room Menu Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' Menu Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('hotel.devices.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Connected TVs
        </a>

        @if($hasOverride)
            <form action="{{ route('hotel.devices.menus.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default Menu settings?');">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl border border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold text-xs transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset to Hotel Global Default</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Room {{ $device->room_no }} Menu Items</h3>
                <p class="text-xs text-slate-500 font-medium font-mono">Device ID: {{ $device->device_id }} | MAC: {{ $device->mac_address }}</p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200">
                        <i class="fa-solid fa-sliders mr-1.5 text-xs"></i> Custom Override Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-sky-100 text-sky-800 text-xs font-bold border border-sky-200">
                        <i class="fa-solid fa-globe mr-1.5 text-xs"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form id="deviceMenuForm" action="{{ url('/hotel/devices/' . $device->id . '/menus') }}" method="POST" class="space-y-6" data-swal-bypass="true" data-ajax-form="true">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($defaultMenus as $menu)
                    @php
                        $isShown = !isset($currentSettings[$menu['id']]) || $currentSettings[$menu['id']] !== 'hide';
                    @endphp
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $menu['name'] }}</h4>
                            <span class="text-[10px] font-mono text-slate-400">id: {{ $menu['id'] }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="menus[{{ $menu['id'] }}]" value="1" {{ $isShown ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.devices.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" id="saveRoomMenuBtn" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Room Menu Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    const form = document.getElementById('deviceMenuForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('saveRoomMenuBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i><span>Syncing Realtime...</span>';
            btn.classList.add('opacity-90');

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
                        title: 'Room Menu Settings Synced in Realtime! ⚡'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message || 'Failed to save room menu settings.'
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
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i><span>Save Room Menu Settings</span>';
                btn.classList.remove('opacity-90');
            }
        });
    }
});
</script>
