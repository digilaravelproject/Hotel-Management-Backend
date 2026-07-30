@extends('layouts.hotel_admin')

@section('title', 'Global OTT Settings - Hotel Admin')
@section('page_title', 'Global OTT Access Settings')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('hotel.package') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to My Package
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--bg-card);">
        <form action="{{ url('/hotel/ott-settings') }}" method="POST">
            @csrf
            
            <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--bg-dark);">Hotel Default OTT Access</h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
                        Enable or disable OTT apps for all TVs across your hotel by default.
                    </p>
                </div>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer; user-select: none;">
                    <input type="checkbox" id="selectAllOtt" style="width: 16px; height: 16px; cursor: pointer;">
                    Select All Included
                </label>
            </div>

            @if(count($availablePlatforms) > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                    @foreach($availablePlatforms as $ott)
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer;">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox" style="margin-top: 3px; width: 16px; height: 16px; cursor: pointer;" {{ in_array($ott['package'], $currentGlobalSettings) ? 'checked' : '' }}>
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
                <a href="{{ route('hotel.package') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary" {{ count($availablePlatforms) === 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i>Save Global Settings
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
