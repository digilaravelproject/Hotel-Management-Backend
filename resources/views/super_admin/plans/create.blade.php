@extends('layouts.super_admin')

@section('title', 'Add Pricing Plan - Super Admin')
@section('page_title', 'Create Subscription Plan')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('super-admin.plans.index') }}" style="color: var(--text-muted); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back to list
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="box-shadow: var(--shadow-md);">
        <form action="{{ route('super-admin.plans.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Plan Name</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="form-control" placeholder="e.g. Deluxe Plan">
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Max Room / TV Count</label>
                    <input type="number" name="room_count" required min="1" value="{{ old('room_count') }}" class="form-control" placeholder="e.g. 50">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Monthly Cost (INR)</label>
                    <input type="number" name="price" required min="0" step="1" value="{{ old('price') }}" class="form-control" placeholder="e.g. 1999">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label">Plan Description (Optional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe what features are covered by this subscription plan..." style="resize: vertical; font-family: inherit;">{{ old('description') }}</textarea>
            </div>

            <!-- Features / OTT Platforms Section -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: var(--text-color);">Features / OTT Platforms</h4>
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Select the OTT platforms enabled for this subscription plan.</p>
                    </div>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer; user-select: none;">
                        <input type="checkbox" id="selectAllOtt" style="width: 16px; height: 16px; cursor: pointer;">
                        Select All
                    </label>
                </div>

                @php
                    $oldPlatforms = old('ott_platforms', []);
                @endphp

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                    @foreach($ottPlatforms as $ott)
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 8px 10px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer;">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox" style="margin-top: 3px; width: 16px; height: 16px; cursor: pointer;" {{ in_array($ott['package'], $oldPlatforms) ? 'checked' : '' }}>
                            <div style="display: flex; flex-direction: column; overflow: hidden;">
                                <span style="font-size: 13px; font-weight: 600; color: #1e293b;">{{ $ott['name'] }}</span>
                                <span style="font-size: 11px; color: #64748b; font-family: monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('super-admin.plans.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Pricing Plan</button>
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
        selectAllCheckbox.checked = (total > 0 && total === checkedCount);
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
