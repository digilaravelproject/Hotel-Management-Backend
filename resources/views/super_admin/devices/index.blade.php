@extends('layouts.super_admin')

@section('title', 'Connected Devices - Super Admin')
@section('page_title', 'Connected Devices (TVs)')

@section('styles')
<style>
    .filter-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-select {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        background-color: white;
        outline: none;
        min-width: 250px;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 20px 0;
        gap: 8px;
    }

    .page-item {
        display: inline;
    }

    .page-link, .page-item span {
        padding: 8px 16px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--text-main);
        background-color: var(--bg-card);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .page-link:hover {
        background-color: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    .page-item.disabled span {
        color: var(--text-light);
        background-color: #f1f5f9;
        cursor: not-allowed;
        border-color: var(--border-color);
    }

    .page-item.active span {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="filter-card">
    <div class="filter-group">
        <label for="hotelFilter" style="font-weight: 600; color: var(--text-main);">Filter by Hotel:</label>
        <select id="hotelFilter" class="filter-select" onchange="filterByHotel(this.value)">
            <option value="">All Hotels</option>
            @foreach($hotels as $hotel)
                <option value="{{ $hotel->id }}" {{ isset($selectedHotel) && $selectedHotel->id == $hotel->id ? 'selected' : '' }}>
                    {{ $hotel->hotel_name }} ({{ $hotel->room_count }} Rooms)
                </option>
            @endforeach
        </select>
    </div>

    @if(isset($selectedHotel))
        <div style="font-size: 14px; font-weight: 500; color: var(--text-muted);">
            Active limit: <strong style="color: var(--bg-dark);">{{ $devices->total() }} / {{ $selectedHotel->allowed_device_limit }}</strong> devices connected.
        </div>
    @endif
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sr Number</th>
                <th>Hotel Name</th>
                <th>Room No</th>
                <th>License Key</th>
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
                        <span style="font-weight: 600; color: var(--bg-dark);">
                            {{ $device->hotelAdmin->hotel_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: var(--primary);">
                            {{ $device->room_no }}
                        </span>
                    </td>
                    <td>
                        <code style="background-color: var(--primary-light); color: var(--primary-hover); padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 13px;">
                            {{ $device->hotelAdmin->license_key ?? 'N/A' }}
                        </code>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px; color: var(--text-main);">
                            {{ $device->device_id }}
                        </span>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 13px;">
                            {{ $device->mac_address ?? 'N/A' }}
                        </span>
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
                        <span style="font-family: monospace; font-size: 13px;">
                            {{ $device->ip_address ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ $device->created_at->format('d M, Y H:i') }}</td>
                    <td>
                        <form action="{{ route('super-admin.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect this device?');" style="display: inline;">
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
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No connected devices found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $devices->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>

<script>
    function filterByHotel(hotelId) {
        let url = new URL(window.location.href);
        if (hotelId) {
            url.searchParams.set('hotel_id', hotelId);
        } else {
            url.searchParams.delete('hotel_id');
        }
        window.location.href = url.toString();
    }
</script>
@endsection
