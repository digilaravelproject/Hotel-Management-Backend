@extends('layouts.super_admin')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'System Dashboard')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-info {
        flex-grow: 1;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--bg-dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Theme color classes for icons */
    .stat-primary .stat-icon { background-color: var(--primary-light); color: var(--primary); }
    .stat-success .stat-icon { background-color: var(--success-light); color: var(--success); }
    .stat-warning .stat-icon { background-color: var(--warning-light); color: var(--warning); }
    .stat-secondary .stat-icon { background-color: #f3e8ff; color: #a855f7; }
    
    .dashboard-section {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow-sm);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--bg-dark);
    }
</style>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fa-solid fa-hotel"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalHotels }}</div>
            <div class="stat-label">Total Hotels</div>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $activeHotels }}</div>
            <div class="stat-label">Active Hotels</div>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingApprovals }}</div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>
    
    <div class="stat-card stat-secondary">
        <div class="stat-icon">
            <i class="fa-solid fa-indian-rupee-sign"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">₹{{ number_format($monthlyRevenue, 0) }}</div>
            <div class="stat-label">Monthly Revenue</div>
        </div>
    </div>
</div>

<!-- Recent Hotels Table -->
<div class="dashboard-section">
    <div class="section-header">
        <h3 class="section-title">Recent Hotel Registrations</h3>
        <a href="{{ route('super-admin.hotels.index') }}" class="btn btn-outline btn-sm">View All Hotels</a>
    </div>
    
    <div class="table-responsive" style="margin-top: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Hotel Details</th>
                    <th>Owner</th>
                    <th>Rooms Count</th>
                    <th>Plan</th>
                    <th>Payment</th>
                    <th>Approval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentHotels as $hotel)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--bg-dark);">{{ $hotel->hotel_name }}</div>
                            <small style="color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> {{ $hotel->hotel_location }}</small>
                        </td>
                        <td>
                            <div>{{ $hotel->owner_name }}</div>
                            <small style="color: var(--text-muted);">{{ $hotel->email }}</small>
                        </td>
                        <td><strong>{{ $hotel->room_count }}</strong> Rooms</td>
                        <td>
                            @if($hotel->plan)
                                <span class="badge badge-primary">{{ $hotel->plan->name }}</span>
                            @else
                                <span class="badge badge-outline">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($hotel->payment_status === 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-danger">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($hotel->approval_status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($hotel->approval_status === 'disapproved')
                                <span class="badge badge-danger">Disapproved</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="btn btn-outline btn-sm" title="View details">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="btn btn-outline btn-sm" title="Edit account">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            <i class="fa-regular fa-folder-open" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                            No hotel registrations recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
