@extends('layouts.super_admin')

@section('title', 'TV Templates Management - Super Admin')
@section('page_title', 'TV Offline Templates')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

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
        
        <form action="{{ route('super-admin.templates.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            @csrf
            <div class="form-group" style="flex: 1; min-width: 280px; margin-bottom: 0;">
                <label class="form-label" style="font-weight: 500;">Select Template Package (.zip)</label>
                <input type="file" name="template_file" required class="form-control" accept=".zip" style="padding: 10px 12px;">
                <small style="color: var(--text-muted); display: block; margin-top: 6px;">The system will automatically calculate the next version number (+0.5 step).</small>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 42px; display: flex; align-items: center; gap: 8px;">
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
                                    <a href="{{ asset($template->file_path) }}" target="_blank" style="text-decoration: underline; color: var(--secondary);">
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
@endsection
