@extends('layouts.hotel_admin')

@section('title', 'Room OTT Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' OTT Configuration')

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
                <span class="text-[#150A42] font-black">Room {{ $device->room_no }} OTT</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Room {{ $device->room_no }} OTT Apps Override</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Customize individual streaming app availability for Room {{ $device->room_no }} (MAC: {{ $device->mac_address }}).</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            @if($hasOverride)
                <form action="{{ route('hotel.devices.ott.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default OTT settings?');">
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
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Active Streaming Access for Room {{ $device->room_no }}</h3>
                <p class="text-xs text-slate-400 font-medium font-mono">Device ID: {{ $device->device_id }} | Screen Name: {{ $device->screen_name ?? 'In-Room TV' }}</p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-black border border-amber-200 font-mono shadow-2xs">
                        <i class="fa-solid fa-sliders mr-1.5 text-xs text-amber-600"></i> Custom Room Override Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-[#150A42] text-xs font-black border border-purple-200/80 font-mono shadow-2xs">
                        <i class="fa-solid fa-globe mr-1.5 text-xs text-purple-600"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ url('/hotel/devices/' . $device->id . '/ott') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-800">Available Streaming Platforms in Hotel Plan:</span>
                <label class="inline-flex items-center space-x-2 text-xs font-black text-[#150A42] cursor-pointer select-none bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-full border border-purple-200/80 transition-colors shadow-2xs">
                    <input type="checkbox" id="selectAllOtt" class="w-4 h-4 rounded-lg border-purple-300 text-[#150A42] focus:ring-purple-500">
                    <span>Select All</span>
                </label>
            </div>

            @if(count($availablePlatforms) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availablePlatforms as $ott)
                        <label class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 hover:border-purple-300 flex items-start space-x-3.5 cursor-pointer hover:bg-white transition-all shadow-2xs">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox mt-1 w-4 h-4 rounded border-slate-300 text-[#150A42] focus:ring-purple-500" {{ in_array($ott['package'], $currentDeviceSettings) ? 'checked' : '' }}>
                            <div class="overflow-hidden min-w-0">
                                <h4 class="text-xs font-black text-slate-900 truncate">{{ $ott['name'] }}</h4>
                                <span class="text-[10px] font-mono text-slate-400 truncate block mt-0.5" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-xs text-slate-400 font-bold bg-[#F8F9FD] rounded-2xl border border-dashed border-slate-200">
                    No OTT platforms available in your current Subscription Plan.
                </div>
            @endif

            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.devices.index') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-md shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Room {{ $device->room_no }} OTT Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    const selectAllCheckbox = document.getElementById('selectAllOtt');
    const ottCheckboxes = document.querySelectorAll('.ott-checkbox');

    function updateSelectAllState() {
        const total = ottCheckboxes.length;
        const checkedCount = document.querySelectorAll('.ott-checkbox:checked').length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (total > 0 && total === checkedCount);
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            ottCheckboxes.forEach(cb => { cb.checked = selectAllCheckbox.checked; });
        });
    }

    ottCheckboxes.forEach(cb => { cb.addEventListener('change', updateSelectAllState); });
    updateSelectAllState();
</script>
@endsection
@endsection
