@extends('layouts.super_admin')

@section('title', 'Add Pricing Plan - Super Admin')
@section('page_title', 'Create Subscription Plan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('super-admin.plans.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Plans List
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#FEE2E2] text-[#B91C1C] text-xs space-y-1">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <form action="{{ route('super-admin.plans.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Plan Title / Name <span class="text-[#B91C1C]">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Deluxe Plan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Max Room / TV Limit <span class="text-[#B91C1C]">*</span></label>
                    <input type="number" name="room_count" required min="1" value="{{ old('room_count') }}" placeholder="e.g. 50" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:bg-white transition-all">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Monthly Cost (INR) <span class="text-[#B91C1C]">*</span></label>
                    <input type="number" name="price" required min="0" step="1" value="{{ old('price') }}" placeholder="e.g. 1999" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:bg-white transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Plan Description <span class="text-xs font-normal text-slate-400">(Optional)</span></label>
                <textarea name="description" rows="3" placeholder="Briefly describe what features are covered by this subscription plan..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:bg-white transition-all resize-y">{{ old('description') }}</textarea>
            </div>

            <!-- Features / OTT Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900">Included OTT Platforms</h4>
                        <p class="text-[11px] text-slate-500 font-medium">Select the streaming applications enabled under this plan.</p>
                    </div>
                    <label class="inline-flex items-center space-x-2 text-xs font-bold text-[#A68A64] hover:text-[#8C7250] cursor-pointer select-none">
                        <input type="checkbox" id="selectAllOtt" class="rounded border-slate-300 text-[#A68A64] focus:ring-[#C5A880]">
                        <span>Select All</span>
                    </label>
                </div>

                @php
                    $oldPlatforms = old('ott_platforms', []);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    @foreach($ottPlatforms as $ott)
                        <label class="p-3 bg-white border border-slate-200 rounded-xl flex items-start space-x-2.5 cursor-pointer hover:bg-slate-100/80 transition-colors">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox mt-0.5 rounded border-slate-300 text-[#A68A64] focus:ring-[#C5A880]" {{ in_array($ott['package'], $oldPlatforms) ? 'checked' : '' }}>
                            <div class="overflow-hidden">
                                <h5 class="text-xs font-bold text-slate-800">{{ $ott['name'] }}</h5>
                                <span class="text-[10px] font-mono text-slate-400 truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.plans.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#A68A64] hover:bg-[#8C7250] text-white font-bold text-xs shadow-xs hover:shadow-md transition-all">Create Pricing Plan</button>
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
