@extends('layouts.super_admin')

@section('title', 'Connected Devices - Super Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6">
    <!-- Filter Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <label for="hotelFilter" class="text-xs font-bold text-slate-700 shrink-0">Filter by Hotel:</label>
            <select id="hotelFilter" onchange="filterByHotel(this.value)" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:outline-none focus:border-rose-500 w-full sm:w-72">
                <option value="">All Hotels</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}" {{ isset($selectedHotel) && $selectedHotel->id == $hotel->id ? 'selected' : '' }}>
                        {{ $hotel->hotel_name }} ({{ $hotel->room_count }} Rooms)
                    </option>
                @endforeach
            </select>
        </div>

        @if(isset($selectedHotel))
            <div class="text-xs font-semibold text-slate-600">
                Active limit: <strong class="text-slate-900 font-extrabold">{{ $devices->total() }} / {{ $selectedHotel->allowed_device_limit }}</strong> TVs connected
            </div>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Hotel Client</th>
                        <th class="px-6 py-4">Room No</th>
                        <th class="px-6 py-4">License Key</th>
                        <th class="px-6 py-4">Device ID</th>
                        <th class="px-6 py-4">MAC Address</th>
                        <th class="px-6 py-4">Hardware Info</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Connection Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $index => $device)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-400">{{ $devices->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-extrabold text-slate-900">{{ $device->hotelAdmin->hotel_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-indigo-600">Room {{ $device->room_no }}</td>
                            <td class="px-6 py-4 font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-[11px]">
                                    {{ $device->hotelAdmin->license_key ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-medium text-slate-600">{{ $device->device_id }}</td>
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $device->mac_address ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($device->brand || $device->model)
                                    <div class="font-bold text-slate-800 capitalize">{{ $device->brand }} {{ $device->model }}</div>
                                    @if($device->os_version)<span class="text-[10px] text-slate-400 font-medium">Android {{ $device->os_version }}</span>@endif
                                @else
                                    <span class="text-slate-400 italic">Generic TV</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $device->ip_address ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $device->created_at->format('d M, Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('super-admin.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Disconnect this device?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-[11px] transition-colors">
                                        <i class="fa-solid fa-power-off mr-1"></i> Disconnect
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-tv text-3xl block mb-2 text-slate-300"></i>
                                No connected TV screens found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-2">
        {{ $devices->appends(request()->query())->links() }}
    </div>
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
