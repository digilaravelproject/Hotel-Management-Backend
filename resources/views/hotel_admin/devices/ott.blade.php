@extends('layouts.hotel_admin')

@section('title', 'Room OTT Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' OTT Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('hotel.devices.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Connected TVs
        </a>

        @if($hasOverride)
            <form action="{{ route('hotel.devices.ott.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default OTT settings?');">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl border border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold text-xs transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset to Hotel Global Default</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-[#E5E7EB] pb-6 gap-4">
            <div class="space-y-1">
                <h3 class="text-xl font-extrabold text-[#111827] tracking-tight">Room {{ $device->room_no }} OTT Apps Access</h3>
                <p class="text-xs text-[#6B7280] font-medium font-mono">Device ID: {{ $device->device_id }} | MAC: {{ $device->mac_address }}</p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FFFBEB] text-[#B45309] text-xs font-bold border border-[#EA580C]/20">
                        <i class="fa-solid fa-sliders mr-1.5 text-xs"></i> Custom Room Override Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#F7F3F5] text-[#2A1820] text-xs font-bold border border-[#C5A880]/30">
                        <i class="fa-solid fa-globe mr-1.5 text-xs text-[#8C7250]"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ url('/hotel/devices/' . $device->id . '/ott') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#111827]">Available Streaming Platforms:</span>
                <label class="inline-flex items-center space-x-2 text-xs font-bold text-[#2A1820] cursor-pointer select-none">
                    <input type="checkbox" id="selectAllOtt" class="rounded border-[#CBD5E1] text-[#2A1820] focus:ring-[#C5A880]">
                    <span>Select All</span>
                </label>
            </div>

            @if(count($availablePlatforms) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availablePlatforms as $ott)
                        <label class="p-4 rounded-xl bg-[#F4F5F7] border border-[#E5E7EB] flex items-start space-x-3 cursor-pointer hover:bg-white transition-colors">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox mt-0.5 rounded border-[#CBD5E1] text-[#2A1820] focus:ring-[#C5A880]" {{ in_array($ott['package'], $currentDeviceSettings) ? 'checked' : '' }}>
                            <div class="overflow-hidden">
                                <h4 class="text-xs font-bold text-[#111827]">{{ $ott['name'] }}</h4>
                                <span class="text-[10px] font-mono text-[#6B7280] truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-xs text-[#6B7280] font-medium bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl">
                    No OTT platforms available in your current Subscription Plan.
                </div>
            @endif

            <div class="pt-6 border-t border-[#E5E7EB] flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.devices.index') }}" class="px-6 py-3 rounded-xl border border-[#CBD5E1] text-[#111827] hover:bg-[#F4F5F7] text-xs font-bold transition-all">Cancel</a>
                <button type="submit" {{ count($availablePlatforms) === 0 ? 'disabled' : '' }} class="px-8 py-3 rounded-xl bg-[#3D242F] hover:bg-[#8C7250] text-white font-bold text-xs shadow-md shadow-[#C5A880]/20 transition-all hover:-translate-y-0.5 disabled:opacity-50">
                    Save Room OTT Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
});
</script>
@endsection
