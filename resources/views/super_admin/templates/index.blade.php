@extends('layouts.super_admin')

@section('title', 'TV Templates Management - Super Admin')
@section('page_title', 'TV App OTA Release Builds')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div id="js-alert-container"></div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Template Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Release New TV Template Update</h3>
                <p class="text-xs text-slate-500 font-medium">Upload new ZIP build to deploy OTA updates to all connected TV screens.</p>
            </div>
        </div>
        
        <form id="uploadTemplateForm" action="{{ route('super-admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
            @csrf
            <div class="flex-1 space-y-1.5">
                <label class="text-xs font-bold text-slate-700">Select Template Package (.zip)</label>
                <input type="file" id="templateFileField" name="template_file" required accept=".zip" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-600 file:text-white">
                <span class="text-[11px] text-slate-400 font-medium block">The system will automatically increment version number (+0.5).</span>
            </div>
            <button type="submit" id="submitBtn" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center justify-center space-x-2 shrink-0">
                <i class="fa-solid fa-upload"></i>
                <span>Upload & Deploy</span>
            </button>
        </form>
    </div>

    <!-- Template History List -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-history text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Template Version History</h3>
                <p class="text-xs text-slate-500 font-medium">Over-the-air deployment release log and active build status.</p>
            </div>
        </div>

        @if($templates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-4">Version</th>
                            <th class="px-6 py-4">File Location</th>
                            <th class="px-6 py-4">Uploaded At</th>
                            <th class="px-6 py-4">File Size</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($templates as $template)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-extrabold text-rose-600 text-sm">v{{ $template->version }}</td>
                                <td class="px-6 py-4 font-mono">
                                    <a href="{{ Storage::url($template->file_path) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold">
                                        {{ basename($template->file_path) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $template->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">
                                    @php
                                        $sizeBytes = 0;
                                        try {
                                            if (Storage::disk('public')->exists($template->file_path)) {
                                                $sizeBytes = Storage::disk('public')->size($template->file_path);
                                            }
                                        } catch(\Exception $e) {}
                                        
                                        $sizeDisplay = '0 KB';
                                        if ($sizeBytes > 0) {
                                            if ($sizeBytes >= 1048576) {
                                                $sizeDisplay = number_format($sizeBytes / 1048576, 2) . ' MB';
                                            } else {
                                                $sizeDisplay = number_format($sizeBytes / 1024, 2) . ' KB';
                                            }
                                        }
                                    @endphp
                                    {{ $sizeDisplay }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($template->is_active)
                                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] uppercase">Active</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px] uppercase">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('super-admin.templates.toggle-active', $template->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($template->is_active)
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-[11px]">
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-600 hover:bg-emerald-50 font-bold text-[11px]">
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $templates->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400 font-medium">
                <i class="fa-solid fa-code-branch text-3xl block mb-2 text-slate-300"></i>
                No template versions uploaded yet. Select a .zip build file above to release.
            </div>
        @endif
    </div>
</div>

<!-- Upload Progress Modal Overlay -->
<div id="uploadProgressModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between">
            <h4 id="progressStatusText" class="text-sm font-extrabold text-slate-900">Uploading Template Package...</h4>
            <span id="progressPercentageText" class="text-xs font-mono font-bold text-rose-600">0%</span>
        </div>
        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
            <div id="progressBarFill" class="h-full bg-rose-600 rounded-full transition-all duration-200" style="width: 0%;"></div>
        </div>
        <p id="progressSubText" class="text-[11px] text-slate-400 font-medium">Please wait while the server extracts and deploys the build...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('uploadTemplateForm');
        const modal = document.getElementById('uploadProgressModal');
        const progressFill = document.getElementById('progressBarFill');
        const percentageText = document.getElementById('progressPercentageText');
        const statusText = document.getElementById('progressStatusText');
        const subText = document.getElementById('progressSubText');
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('js-alert-container');

        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('templateFileField');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Please select a .zip template file to upload.');
                return;
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            // Reset UI
            modal.classList.remove('hidden');
            progressFill.style.width = '0%';
            percentageText.textContent = '0%';
            statusText.textContent = 'Uploading Package...';
            subText.textContent = 'Sending template zip archive to server...';
            submitBtn.disabled = true;

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressFill.style.width = percent + '%';
                    percentageText.textContent = percent + '%';

                    if (percent === 100) {
                        statusText.textContent = 'Extracting & Validating Build...';
                        subText.textContent = 'Unzipping template assets onto production server...';
                    }
                }
            });

            xhr.addEventListener('load', function() {
                modal.classList.add('hidden');
                submitBtn.disabled = false;

                if (xhr.status >= 200 && xhr.status < 300) {
                    window.location.reload();
                } else {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        let errMsg = res.message || 'Upload failed.';
                        if (res.errors) {
                            errMsg = Object.values(res.errors).flat().join('<br>');
                        }
                        alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">${errMsg}</div>`;
                    } catch(err) {
                        alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">Upload failed with status code ${xhr.status}.</div>`;
                    }
                }
            });

            xhr.addEventListener('error', function() {
                modal.classList.add('hidden');
                submitBtn.disabled = false;
                alertContainer.innerHTML = `<div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">Network error during upload.</div>`;
            });

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    });
</script>
@endsection
