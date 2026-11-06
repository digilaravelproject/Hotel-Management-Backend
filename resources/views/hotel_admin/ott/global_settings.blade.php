@extends('layouts.hotel_admin')

@section('title', 'Global OTT Settings - Hotel Admin')
@section('page_title', 'Global OTT Access Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('hotel.package') }}" class="hover:text-[#150A42] transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5 text-[11px]"></i>
                    <span>My Package</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Global OTT Visibility</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Hotel Default OTT Visibility</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Control which streaming applications appear on guest room Smart TVs across the entire hotel.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('hotel.package') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Package</span>
            </a>
        </div>
    </div>

    <!-- Main Clean Container -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-5 gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 text-xl shadow-2xs">
                    <i class="fa-brands fa-google-play"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">In-Room TV Streaming Platforms</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Checked platforms will be visible to guests. Per-room custom overrides can also be configured under Connected TVs.</p>
                </div>
            </div>
            
            @if(count($availablePlatforms) > 0)
                <label class="inline-flex items-center space-x-2 text-xs font-black text-purple-700 cursor-pointer select-none bg-purple-50 hover:bg-purple-100 px-3.5 py-1.5 rounded-full border border-purple-200/80 transition-colors shadow-2xs">
                    <input type="checkbox" id="selectAllOtt" class="w-4 h-4 rounded-lg border-purple-300 text-[#150A42] focus:ring-purple-500">
                    <span>Select All Included</span>
                </label>
            @endif
        </div>

        <!-- Form -->
        <form id="ottForm" action="{{ url('/hotel/ott-settings') }}" method="POST" class="space-y-6" data-swal-bypass="true" data-ajax-form="true">
            @csrf

            @if(count($availablePlatforms) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availablePlatforms as $ott)
                        <label class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 hover:border-purple-300 flex items-center justify-between cursor-pointer transition-all hover:bg-white group shadow-2xs">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-[#150A42] group-hover:bg-[#150A42] group-hover:text-white transition-colors shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-film text-sm"></i>
                                </div>
                                <div class="overflow-hidden min-w-0">
                                    <h4 class="text-xs font-black text-slate-900 group-hover:text-purple-700 transition-colors truncate">{{ $ott['name'] }}</h4>
                                    <span class="text-[10px] font-mono text-slate-400 truncate block mt-0.5" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Toggle switch -->
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox w-4 h-4 rounded border-slate-300 text-[#150A42] focus:ring-purple-500" {{ in_array($ott['package'], $currentGlobalSettings) ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-xs text-slate-400 font-bold bg-[#F8F9FD] rounded-2xl border border-dashed border-slate-200">
                    <i class="fa-solid fa-circle-exclamation text-2xl block mb-2 text-slate-300"></i>
                    No OTT platforms available in your current Subscription Plan.
                </div>
            @endif

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                <a href="{{ route('hotel.package') }}" class="px-6 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all shadow-2xs">Cancel</a>
                <button type="submit" id="saveOttBtn" {{ count($availablePlatforms) === 0 ? 'disabled' : '' }} class="px-8 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs flex items-center justify-center space-x-2 shadow-md shadow-[#150A42]/15 transition-all active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Global Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
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
