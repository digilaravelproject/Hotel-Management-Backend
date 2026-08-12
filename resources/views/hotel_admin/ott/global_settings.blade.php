@extends('layouts.hotel_admin')

@section('title', 'Global OTT Settings - Hotel Admin')
@section('page_title', 'Global OTT Access Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('hotel.package') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs">
            <i class="fa-solid fa-arrow-left mr-2 text-indigo-600"></i> Back to My Package
        </a>
    </div>

    <!-- Main Clean Container -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-5 gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-brands fa-google-play text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Hotel Default OTT Access</h3>
                    <p class="text-xs text-slate-500 font-medium">Enable or disable streaming apps across all hotel room TVs by default.</p>
                </div>
            </div>
            
            @if(count($availablePlatforms) > 0)
                <label class="inline-flex items-center space-x-2 text-xs font-bold text-indigo-600 cursor-pointer select-none bg-indigo-50 px-3.5 py-1.5 rounded-full border border-indigo-100">
                    <input type="checkbox" id="selectAllOtt" class="w-4 h-4 rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
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
                        <label class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:border-indigo-300 flex items-center justify-between cursor-pointer transition-all hover:bg-white group">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0">
                                    <i class="fa-solid fa-film text-sm"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-xs font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $ott['name'] }}</h4>
                                    <span class="text-[10px] font-mono text-slate-400 truncate block" title="{{ $ott['package'] }}">{{ $ott['package'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Toggle switch -->
                            <input type="checkbox" name="ott_platforms[]" value="{{ $ott['package'] }}" class="ott-checkbox w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ in_array($ott['package'], $currentGlobalSettings) ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-xs text-slate-400 font-semibold bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <i class="fa-solid fa-circle-exclamation text-xl block mb-2 text-slate-300"></i>
                    No OTT platforms available in your current Subscription Plan.
                </div>
            @endif

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('hotel.package') }}" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">Cancel</a>
                <button type="submit" id="saveOttBtn" {{ count($availablePlatforms) === 0 ? 'disabled' : '' }} class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5 disabled:opacity-50 flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Global Settings</span>
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

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    // Async AJAX submit for OTT settings
    const ottForm = document.getElementById('ottForm');
    if (ottForm) {
        ottForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('saveOttBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i><span>Syncing Realtime...</span>';
            btn.classList.add('opacity-90');
            if (typeof showGlobalLoader === 'function') showGlobalLoader();

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
                        title: 'OTT Settings Synced in Realtime! ⚡'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result.message || 'Failed to save settings.'
                    });
                }
            } catch (error) {
                console.error('Save error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Network error while syncing.'
                });
            } finally {
                if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i><span>Save Global Settings</span>';
                btn.classList.remove('opacity-90');
            }
        });
    }
});
</script>
</script>
@endsection
