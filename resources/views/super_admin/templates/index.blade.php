@extends('layouts.super_admin')

@section('title', 'TV Templates Management - Super Admin')
@section('page_title', 'TV Offline Templates')

@section('content')
<style>
    /* Premium Upload Progress Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
    }
    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    .modal-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: var(--radius-lg, 12px);
        padding: 28px;
        width: 100%;
        max-width: 480px;
        box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
        transform: scale(0.9);
        transition: transform 0.3s ease-in-out;
    }
    .modal-overlay.show .modal-card {
        transform: scale(1);
    }
    .progress-bar-track {
        width: 100%;
        height: 10px;
        background-color: var(--bg-main, #f1f5f9);
        border-radius: 5px;
        overflow: hidden;
        margin: 16px 0;
        position: relative;
    }
    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--primary, #6366f1) 0%, #818cf8 100%);
        border-radius: 5px;
        transition: width 0.1s linear;
    }
    @keyframes progress-pulse {
        0% { opacity: 1; box-shadow: 0 0 5px rgba(99, 102, 241, 0.5); }
        50% { opacity: 0.7; box-shadow: 0 0 15px rgba(99, 102, 241, 0.8); }
        100% { opacity: 1; box-shadow: 0 0 5px rgba(99, 102, 241, 0.5); }
    }
    .progress-bar-fill.pulse {
        animation: progress-pulse 1.5s infinite ease-in-out;
    }
</style>

<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div id="js-alert-container"></div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Template Card -->
    <div class="card" style="box-shadow: var(--shadow-sm); background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary);"></i> Release New TV Template Update
        </h3>
        
        <form id="uploadTemplateForm" action="{{ route('super-admin.templates.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            @csrf
            <div class="form-group" style="flex: 1; min-width: 280px; margin-bottom: 0;">
                <label class="form-label" style="font-weight: 500;">Select Template Package (.zip)</label>
                <input type="file" id="templateFileField" name="template_file" required class="form-control" accept=".zip" style="padding: 10px 12px;">
                <small style="color: var(--text-muted); display: block; margin-top: 6px;">The system will automatically calculate the next version number (+0.5 step).</small>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary" style="height: 42px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-upload"></i> Upload & Deploy
            </button>
        </form>
    </div>

    <!-- Template History List -->
    <div class="card" style="box-shadow: var(--shadow-sm); background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-history" style="color: var(--primary);"></i> Template Version History
        </h3>

        @if($templates->count() > 0)
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 8px;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                            <th style="padding: 12px; font-weight: 600; color: var(--text-muted);">Version</th>
                            <th style="padding: 12px; font-weight: 600; color: var(--text-muted);">File Location</th>
                            <th style="padding: 12px; font-weight: 600; color: var(--text-muted);">Uploaded At</th>
                            <th style="padding: 12px; font-weight: 600; color: var(--text-muted); text-align: center;">Status</th>
                            <th style="padding: 12px; font-weight: 600; color: var(--text-muted); text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                            <tr style="border-bottom: 1px solid var(--border-color); transition: var(--transition);">
                                <td style="padding: 12px; font-weight: 700; color: var(--primary);">
                                    v{{ $template->version }}
                                </td>
                                <td style="padding: 12px; font-size: 13px; font-family: monospace; color: var(--text-main);">
                                    <a href="{{ Storage::url($template->file_path) }}" target="_blank" style="text-decoration: underline; color: var(--secondary);">
                                        {{ basename($template->file_path) }}
                                    </a>
                                </td>
                                <td style="padding: 12px; font-size: 14px; color: var(--text-muted);">
                                    {{ $template->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($template->is_active)
                                        <span class="badge badge-success" style="background-color: var(--success-light); color: var(--success); padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px;">Active</span>
                                    @else
                                        <span class="badge badge-secondary" style="background-color: var(--bg-main); color: var(--text-muted); padding: 4px 10px; border-radius: 20px; font-weight: 500; font-size: 12px;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right;">
                                    <form action="{{ route('super-admin.templates.toggle-active', $template->id) }}" method="POST" style="margin: 0; display: inline-block;">
                                        @csrf
                                        @if($template->is_active)
                                            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: var(--danger); padding: 4px 10px;">
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--success); border-color: var(--success); padding: 4px 10px;">
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

            <div style="margin-top: 16px;">
                {{ $templates->links() }}
            </div>
        @else
            <div style="padding: 30px; text-align: center; color: var(--text-muted); background-color: var(--bg-main); border: 1px dashed var(--border-color); border-radius: var(--radius-md);">
                No templates have been uploaded yet. Release the first version above.
            </div>
        @endif
    </div>
</div>

<!-- Real-time Upload Progress Modal Overlay -->
<div id="uploadProgressModal" class="modal-overlay">
    <div class="modal-card">
        <h4 style="margin: 0 0 8px 0; color: var(--bg-dark); font-weight: 700; font-size: 16px;">Uploading Template Package...</h4>
        <p id="uploadStatusText" style="margin: 0; font-size: 14px; color: var(--text-muted);">Preparing files to upload</p>
        
        <div class="progress-bar-track">
            <div id="progressBarFill" class="progress-bar-fill"></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
            <span id="progressBytes" style="color: var(--text-muted); font-weight: 500;">0.00 MB / 0.00 MB</span>
            <span id="progressPercent" style="color: var(--primary); font-weight: 700;">0%</span>
        </div>
    </div>
</div>

<script>
    document.getElementById('uploadTemplateForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = this;
        const fileInput = document.getElementById('templateFileField');
        const submitBtn = document.getElementById('submitBtn');
        const modal = document.getElementById('uploadProgressModal');
        const progressFill = document.getElementById('progressBarFill');
        const progressPercent = document.getElementById('progressPercent');
        const progressBytes = document.getElementById('progressBytes');
        const statusText = document.getElementById('uploadStatusText');
        const alertContainer = document.getElementById('js-alert-container');

        if (!fileInput.files || fileInput.files.length === 0) {
            return;
        }

        // Helper to format bytes to human-readable MB string
        function formatMB(bytes) {
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // 1. Debounce UI instantly
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading...';

        // 2. Open progress modal
        modal.classList.add('show');
        statusText.innerText = 'Initializing template file upload...';

        // 3. Setup AJAX / XHR multipart submit
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('Accept', 'application/json');

        // Track upload progress events
        let statusInterval = null;
        let elapsed = 0;

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                
                // Update Progress visual representation
                progressFill.style.width = percentComplete + '%';
                progressPercent.innerText = percentComplete + '%';
                progressBytes.innerText = formatMB(e.loaded) + ' / ' + formatMB(e.total);

                if (percentComplete === 100) {
                    if (!statusInterval) {
                        progressFill.classList.add('pulse');
                        statusText.innerText = 'Upload complete! Saving template file to storage disk...';
                        statusInterval = setInterval(function() {
                            elapsed += 2;
                            if (elapsed === 2) {
                                statusText.innerText = 'Writing archive to server SSD storage...';
                            } else if (elapsed === 4) {
                                statusText.innerText = 'Verifying files and registering version...';
                            } else if (elapsed === 6) {
                                statusText.innerText = 'Deactivating previous versions...';
                            } else if (elapsed === 8) {
                                statusText.innerText = 'Almost done! Finalizing deployment setup...';
                            } else if (elapsed >= 10) {
                                statusText.innerText = 'Almost done! Please wait a moment...';
                            }
                        }, 2000);
                    }
                } else {
                    statusText.innerText = 'Transferring zip file...';
                }
            }
        });

        // Helper to clear resources
        function cleanupUploadUI() {
            modal.classList.remove('show');
            progressFill.classList.remove('pulse');
            if (statusInterval) {
                clearInterval(statusInterval);
                statusInterval = null;
            }
            elapsed = 0;
        }

        // Track request response complete
        xhr.addEventListener('load', function() {
            cleanupUploadUI();
            
            if (xhr.status >= 200 && xhr.status < 300) {
                // Success: Reload page to show new version logs
                window.location.reload();
            } else {
                // Failure: display validation or server errors
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload & Deploy';
                
                let errorMessage = 'An error occurred during template upload.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch(e) {}

                alertContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> ${errorMessage}
                    </div>
                `;
            }
        });

        // Track network / connection errors
        xhr.addEventListener('error', function() {
            cleanupUploadUI();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload & Deploy';
            
            alertContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> Connection error occurred during template upload.
                </div>
            `;
        });

        xhr.send(formData);
    });
</script>
@endsection
