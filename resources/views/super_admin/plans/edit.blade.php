@extends('layouts.super_admin')

@section('title', 'Modify Pricing Plan - Super Admin')
@section('page_title', 'Edit Subscription Plan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('super-admin.plans.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#150A42] transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Plans List
        </a>
        <span class="text-[11px] font-mono text-slate-400">Plan ID: #{{ $plan->id }}</span>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-[26px] p-6 sm:p-8 shadow-xs space-y-6">
        <form action="{{ route('super-admin.plans.update', $plan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Plan Title / Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name', $plan->name) }}" class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Max Room / TV Limit <span class="text-rose-500">*</span></label>
                    <input type="number" name="room_count" required min="1" value="{{ old('room_count', $plan->room_count) }}" class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 focus:bg-white transition-all font-mono">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Monthly Cost (INR) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" required min="0" step="1" value="{{ old('price', intval($plan->price)) }}" class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 focus:bg-white transition-all font-mono">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Plan Description <span class="text-xs font-normal text-slate-400">(Optional)</span></label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 focus:bg-white transition-all resize-y">{{ old('description', $plan->description) }}</textarea>
            </div>

            <!-- Features / OTT Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 font-mono uppercase tracking-wider">Included OTT Platforms</h4>
                        <p class="text-[11px] text-slate-500 font-medium">Streaming services enabled under this package.</p>
                    </div>
                    <label class="inline-flex items-center space-x-2 text-xs font-bold text-[#150A42] hover:text-[#201063] cursor-pointer select-none">
                        <input type="checkbox" id="selectAllOtt" class="rounded border-slate-300 text-[#150A42] focus:ring-[#150A42]/20">
                        <span>Select All</span>
                    </label>
                </div>

                @php
                    $selectedPlatforms = old('ott_platforms', $plan->ott_platforms ?? []);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-[#F8F9FD] border border-slate-200/80 rounded-2xl">
                    @foreach($ottPlatforms as $ott)
                        <label class="p-3 bg-white border border-slate-200/90 rounded-xl flex items-start space-x-2.5 cursor-pointer hover:border-purple-200 transition-colors shadow-2xs">
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox mt-0.5 rounded border-slate-300 text-[#150A42] focus:ring-[#150A42]/20" {{ in_array($ott['package'], $selectedPlatforms) ? 'checked' : '' }}>
                            <div class="overflow-hidden">
                                <h5 class="text-xs font-bold text-slate-800">{{ $ott['name'] }}</h5>
                                <span class="text-[10px] font-mono text-slate-400 truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('super-admin.plans.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="btn-sa-primary px-7 py-2.5 text-xs shadow-md">Update Pricing Plan</button>
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
