@extends('super_admin.layouts.app')

@section('title', 'Global Commission Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Global Commission Settings</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('super-admin.commissions.settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="default_percentage">Default Commission Percentage (%)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control" id="default_percentage" name="default_percentage" value="{{ old('default_percentage', $setting->default_percentage ?? 10.00) }}" required>
                            <small class="form-text text-muted">This percentage applies to all distributors unless an individual override is configured.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
