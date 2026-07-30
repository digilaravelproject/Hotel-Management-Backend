@extends('layouts.super_admin')

@section('title', 'Manage Plans - Super Admin')
@section('page_title', 'Subscription Plan Management')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .plan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }
    
    .plan-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: var(--transition);
        position: relative;
    }
    
    .plan-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }
    
    .plan-card.inactive {
        opacity: 0.65;
        border-color: var(--border-color);
    }
    
    .plan-card-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 16px;
        margin-bottom: 20px;
    }
    
    .plan-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--bg-dark);
        margin-bottom: 4px;
    }
    
    .plan-rooms-badge {
        display: inline-block;
        background-color: var(--primary-light);
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        margin-top: 6px;
    }
    
    .plan-cost {
        font-size: 32px;
        font-weight: 800;
        color: var(--bg-dark);
        margin-bottom: 16px;
    }
    
    .plan-cost span {
        font-size: 14px;
        font-weight: 400;
        color: var(--text-muted);
    }
    
    .plan-desc {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 24px;
        flex-grow: 1;
    }
    
    .plan-footer-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--border-color);
        padding-top: 16px;
    }
    
    /* Live toast styling */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--bg-dark);
        color: white;
        padding: 14px 24px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="action-header">
    <div>
        <p style="color: var(--text-muted); font-size: 14px;">Define, structure, and toggle licensing pricing plans for hotel smart TVs.</p>
    </div>
    <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Pricing Plan
    </a>
</div>

<!-- Plans Grid -->
<div class="plan-grid">
    @forelse($plans as $plan)
        <div class="plan-card {{ !$plan->status ? 'inactive' : '' }}" id="plan-card-{{ $plan->id }}">
            <div>
                <div class="plan-card-header">
                    <h3 class="plan-title">{{ $plan->name }}</h3>
                    <span class="plan-rooms-badge">Up to {{ $plan->room_count }} Rooms/TVs</span>
                </div>
                
                <div class="plan-cost">
                    ₹{{ number_format($plan->price, 0) }}<span>/month</span>
                </div>
                
                <p class="plan-desc">
                    {{ $plan->description ?? 'Standard system licensing plan with dashboard access.' }}
                </p>

                @if(!empty($plan->ott_platforms))
                    <div style="margin-bottom: 16px;">
                        <span style="font-size: 11px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-tv" style="font-size: 10px;"></i> {{ count($plan->ott_platforms) }} OTT Platforms Enabled
                        </span>
                    </div>
                @endif
            </div>
            
            <div class="plan-footer-actions">
                <!-- Status Toggle Switch -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label class="switch">
                        <input type="checkbox" onchange="togglePlanStatus({{ $plan->id }})" {{ $plan->status ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">Active State</span>
                </div>
                
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('super-admin.plans.edit', $plan->id) }}" class="btn btn-outline btn-sm" title="Edit plan text">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    
                    <form action="{{ route('super-admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pricing plan? This action is permanent.');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline btn-sm" title="Delete plan" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 0; background-color: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
            <i class="fa-solid fa-tags" style="font-size: 40px; display: block; margin-bottom: 16px; color: var(--text-light);"></i>
            No subscription plans found. Create one to get started.
        </div>
    @endforelse
</div>

<!-- Dynamic toast container -->
<div id="planToast" class="toast-notification">
    <i class="fa-regular fa-circle-check" style="color: var(--success); font-size: 20px;"></i>
    <span id="toastMessage">Plan status updated</span>
</div>
@endsection

@section('scripts')
<script>
    const toast = document.getElementById('planToast');
    const toastMsg = document.getElementById('toastMessage');

    function showToast(message, isSuccess = true) {
        toastMsg.textContent = message;
        const icon = toast.querySelector('i');
        if (isSuccess) {
            icon.className = 'fa-regular fa-circle-check';
            icon.style.color = 'var(--success)';
        } else {
            icon.className = 'fa-regular fa-circle-xmark';
            icon.style.color = 'var(--danger)';
        }
        
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Toggle Plan Active Status AJAX
    function togglePlanStatus(id) {
        const card = document.getElementById(`plan-card-${id}`);
        
        fetch(`/super-admin/plans/${id}/toggle-status`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.status) {
                    card.classList.remove('inactive');
                } else {
                    card.classList.add('inactive');
                }
                showToast(data.message);
            } else {
                showToast('Failed to update status', false);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Server error while updating status', false);
        });
    }
</script>
@endsection
