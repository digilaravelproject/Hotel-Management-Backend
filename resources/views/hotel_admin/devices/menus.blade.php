@extends('layouts.hotel_admin')

@section('title', 'Room Menu Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' Menu Configuration')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.devices.index') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>Connected TVs</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Room {{ $device->room_no }} Menus</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Room {{ $device->room_no }} Navigation Menus</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Control which TV menu items are visible in Room {{ $device->room_no }} (MAC: {{ $device->mac_address }}).</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if($hasOverride)
                <form action="{{ route('hotel.devices.menus.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default Menu settings?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-2xl border border-amber-200 text-amber-800 bg-amber-50 hover:bg-amber-100 font-black text-xs transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer active:scale-95">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset to Global Default</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('hotel.devices.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Connected TVs</span>
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Active Menu Items for Room {{ $device->room_no }}</h3>
                <p class="text-xs text-slate-400 font-medium font-mono">Device ID: {{ $device->device_id }} | Screen Name: {{ $device->screen_name ?? 'In-Room TV' }}</p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-black border border-amber-200 font-mono shadow-2xs">
                        <i class="fa-solid fa-sliders mr-1.5 text-xs text-amber-600"></i> Custom Override Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-[#150A42] text-xs font-black border border-purple-200/80 font-mono shadow-2xs">
                        <i class="fa-solid fa-globe mr-1.5 text-xs text-purple-600"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form id="deviceMenuForm" action="{{ url('/hotel/devices/' . $device->id . '/menus') }}" method="POST" class="space-y-6" data-swal-bypass="true" data-ajax-form="true">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($catalog as $menuId => $menu)
                    @php
                        $isShown = !isset($currentSettings[$menuId]) || $currentSettings[$menuId] !== 'hide';
                    @endphp
                    <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 flex items-center justify-between shadow-2xs hover:border-purple-300 transition-all">
                        <div class="overflow-hidden min-w-0 pr-3">
                            <h4 class="text-xs font-black text-slate-900 truncate">{{ $menu['name'] }}</h4>
                            <span class="text-[10px] font-mono text-slate-400 block mt-0.5">id: {{ $menuId }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="menus[{{ $menuId }}]" value="1" {{ $isShown ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.devices.index') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">Cancel</a>
                <button type="submit" id="saveRoomMenuBtn" class="px-8 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-md shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
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
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-xs"></i><span>Syncing Realtime...</span>';
            btn.classList.add('opacity-90');
            if (typeof showGlobalLoader === 'function') showGlobalLoader();

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Room menu preferences synchronized with TV screen.'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Failed to update preferences.'
                    });
                }
            } catch (err) {
                console.error(err);
                Toast.fire({
                    icon: 'error',
                    title: 'A network error occurred while updating.'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i><span>Save Room Menu Settings</span>';
                btn.classList.remove('opacity-90');
                if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
            }
        });
    }
});
</script>
@endsection
