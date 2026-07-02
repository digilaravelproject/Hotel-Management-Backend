@extends('layouts.super_admin')

@section('title', 'Modify Pricing Plan - Super Admin')
@section('page_title', 'Edit Subscription Plan')

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
        <form action="{{ route('super-admin.plans.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Plan Name</label>
                <input type="text" name="name" required value="{{ old('name', $plan->name) }}" class="form-control">
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Max Room / TV Count</label>
                    <input type="number" name="room_count" required min="1" value="{{ old('room_count', $plan->room_count) }}" class="form-control">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Monthly Cost (INR)</label>
                    <input type="number" name="price" required min="0" step="1" value="{{ old('price', intval($plan->price)) }}" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Plan Description (Optional)</label>
                <textarea name="description" class="form-control" rows="4" style="resize: vertical; font-family: inherit;">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="{{ route('super-admin.plans.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
