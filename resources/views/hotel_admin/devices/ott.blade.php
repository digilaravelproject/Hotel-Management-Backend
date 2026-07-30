@extends('layouts.hotel_admin')

@section('title', 'Room OTT Configuration - Hotel Admin')
@section('page_title', 'Room ' . $device->room_no . ' OTT Access Configuration')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('hotel.devices.index') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to Connected TVs
        </a>

        @if($hasOverride)
            <form action="{{ route('hotel.devices.ott.reset', $device->id) }}" method="POST" onsubmit="return confirm('Reset Room {{ $device->room_no }} to inherit Hotel Global Default OTT settings?');">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--warning); border-color: rgba(245, 158, 11, 0.4);">
                    <i class="fa-solid fa-rotate-left" style="margin-right: 6px;"></i>Reset to Hotel Global Default
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--bg-dark);">
                    Room {{ $device->room_no }} Specific OTT Access
                </h3>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
                    Device ID: <span style="font-family: monospace; font-weight: 600;">{{ $device->device_id }}</span> | MAC: <span style="font-family: monospace;">{{ $device->mac_address }}</span>
                </p>
            </div>
            
            <div>
                @if($hasOverride)
                    <span style="font-size: 12px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-sliders"></i> Custom Room Override Active
                    </span>
                @else
                    <span style="font-size: 12px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-globe"></i> Inheriting Hotel Global Default
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ url('/hotel/devices/' . $device->id . '/ott') }}" method="POST">
            @csrf
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <span style="font-size: 14px; font-weight: 600; color: var(--text-dark);">
                    Available Plan Platforms:
                </span>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer; user-select: none;">
                    <input type="checkbox" id="selectAllOtt" style="width: 16px; height: 16px; cursor: pointer;">
                    Select All
                </label>
            </div>

            @if(count($availablePlatforms) > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                    @foreach($availablePlatforms as $ott)
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer;">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox" style="margin-top: 3px; width: 16px; height: 16px; cursor: pointer;" {{ in_array($ott['package'], $currentDeviceSettings) ? 'checked' : '' }}>
                            <div style="display: flex; flex-direction: column; overflow: hidden;">
                                <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">{{ $ott['name'] }}</span>
                                <span style="font-size: 11px; color: #64748b; font-family: monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; color: var(--text-muted); padding: 30px; background: #f8fafc; border-radius: 8px; margin-bottom: 24px;">
                    No OTT platforms available in your current Subscription Plan.
                </div>
            @endif

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('hotel.devices.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary" {{ count($availablePlatforms) === 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i>Save Room OTT Configuration
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
            ottCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
        });
    }

    ottCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectAllState);
    });

    updateSelectAllState();
});
</script>
@endsection
