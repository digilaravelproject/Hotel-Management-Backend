@extends('layouts.super_admin')

@section('title', 'Add Pricing Plan - Super Admin')
@section('page_title', 'Create Subscription Plan')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
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

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Plan Description (Optional)</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe what features are covered by this subscription plan..." style="resize: vertical; font-family: inherit;">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('super-admin.plans.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Pricing Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
