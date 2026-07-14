@extends('layouts.hotel_admin')

@section('title', 'Connected TVs - Hotel Admin')
@section('page_title', 'Connected TVs')

@section('styles')
<style>
    .info-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .limit-display {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-muted);
    }

    .limit-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
    }

    .progress-bar-container {
        width: 100%;
        max-width: 300px;
        background-color: #e2e8f0;
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--primary);
        transition: width 0.3s ease;
    }

    .progress-bar-fill.warning {
        background-color: var(--warning);
    }

    .progress-bar-fill.danger {
        background-color: var(--danger);
    }

    /* Table enhancements */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        background-color: var(--bg-card);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        margin-top: 20px;
        box-shadow: var(--shadow-sm);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    
    .table th, .table td {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }
    
    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }
    
    .table tr:last-child td {
        border-bottom: none;
    }
    
    }
</style>
@endsection

@section('content')
<div class="info-card">
    <div>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 6px;">TV Connection License Limit</h3>
        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Authorized and connected smart TV screens in your hotel rooms.</p>
    </div>
    
    <div>
        @php
            $connectedCount = $devices->total();
            $allowedLimit = $hotel->allowed_device_limit;
            $percent = $allowedLimit > 0 ? min(100, ($connectedCount / $allowedLimit) * 100) : 0;
            
            $progressClass = '';
            if ($percent >= 90) {
                $progressClass = 'danger';
            } elseif ($percent >= 75) {
                $progressClass = 'warning';
            }
        @endphp
        <div class="limit-display">
            TV Limit Status: <span class="limit-value">{{ $connectedCount }}</span> / <strong style="color: var(--bg-dark);">{{ $allowedLimit }}</strong> TVs
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill {{ $progressClass }}" style="width: {{ $percent }}%;"></div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sr Number</th>
                <th>Room No</th>
                <th>Device ID</th>
                <th>MAC Address</th>
                <th>Hardware Info</th>
                <th>IP Address</th>
                <th>Connection Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $index => $device)
                <tr>
                    <td>{{ $devices->firstItem() + $index }}</td>
                    <td>
                        @if($activeGuest = $activeGuests->get($device->room_no))
                            <div style="font-weight: 600;">
                                <a href="{{ route('hotel.guests.index') }}?room={{ urlencode($device->room_no) }}" 
                                   style="color: var(--primary); text-decoration: underline;" 
                                   title="Occupied by {{ $activeGuest->name }}">
                                    Room {{ $device->room_no }}
                                </a>
                            </div>
                            <small style="color: var(--success); font-weight: 500;">
                                <i class="fa-solid fa-user" style="font-size: 10px;"></i> {{ $activeGuest->name }}
                            </small>
                        @else
                            <div style="font-weight: 600; color: var(--text-muted);">
                                Room {{ $device->room_no }}
                            </div>
                            <small style="color: var(--text-light); font-style: italic;">
                                Vacant
                            </small>
                        @endif
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px; color: var(--text-main);">{{ $device->device_id }}</span>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px;">{{ $device->mac_address }}</span>
                    </td>
                    <td>
                        @if($device->brand || $device->model)
                            <div style="font-size: 13px; font-weight: 500; color: var(--bg-dark);">
                                {{ ucfirst($device->brand) }} {{ $device->model }}
                            </div>
                            @if($device->os_version)
                                <small style="color: var(--text-muted);">OS: {{ $device->os_version }}</small>
                            @endif
                        @else
                            <span style="color: var(--text-light); font-style: italic;">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px;">{{ $device->ip_address ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $device->created_at->format('d M, Y H:i') }}</td>
                    <td>
                        <form action="{{ route('hotel.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect this TV screen?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm btn-danger-hover" title="Disconnect device" style="padding: 6px 10px; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                <i class="fa-solid fa-power-off"></i> Disconnect
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No connected TVs found. Make sure to log in from your TV app.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $devices->links('pagination::bootstrap-4') }}
</div>
@endsection
