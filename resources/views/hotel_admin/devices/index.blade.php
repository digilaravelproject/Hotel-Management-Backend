@extends('layouts.hotel_admin')

@section('title', 'Connected TVs - Hotel Admin')
@section('page_title', 'Connected TVs Network')

@section('content')
<div class="space-y-6">
    <!-- Info & License Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">TV Connection License Limit</h3>
            <p class="text-xs text-slate-500 font-medium">Authorized and synchronized Smart TV devices across hotel rooms.</p>
        </div>
        
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center gap-4">
            <button onclick="openPairModal()" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-qrcode text-sm"></i>
                <span>Pair TV via 8-Digit Code</span>
            </button>

            <div class="space-y-2">
                @php
                    $connectedCount = $devices->total();
                    $allowedLimit = $hotel->allowed_device_limit;
                    $percent = $allowedLimit > 0 ? min(100, ($connectedCount / $allowedLimit) * 100) : 0;
                @endphp
                <div class="text-xs font-semibold text-slate-600 flex items-center justify-between md:justify-end space-x-2">
                    <span>TV Limit Usage:</span>
                    <span class="text-sm font-extrabold text-indigo-600">{{ $connectedCount }}</span> / <strong class="text-slate-900">{{ $allowedLimit }} TVs</strong>
                </div>
                <div class="w-full md:w-48 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-600 rounded-full transition-all duration-500" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pair TV Modal -->
    <div id="pairModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center hidden p-4">
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Pair New TV Screen</h3>
                        <p class="text-xs text-slate-500 font-medium">Enter 8-digit code shown on TV screen</p>
                    </div>
                </div>
                <button onclick="closePairModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="pairForm" onsubmit="submitPairForm(event)" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">8-Digit Pairing Code (Shown on TV Screen)</label>
                    <input type="text" id="pairCodeInput" required placeholder="e.g. 8F2A-9K3P" maxlength="10" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono font-extrabold text-lg tracking-widest text-indigo-600 uppercase focus:outline-none focus:border-indigo-500">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Assign Room Number</label>
                    <input type="text" id="roomNoInput" required placeholder="e.g. 104" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-indigo-500">
                </div>

                <div id="pairFormAlert" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closePairModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                    <button type="submit" id="pairSubmitBtn" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/20 flex items-center space-x-2">
                        <span>Connect & Pair TV</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Devices Table -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Room No</th>
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
                            <td class="px-6 py-4">
                                @if($activeGuest = $activeGuests->get($device->room_no))
                                    <div>
                                        <a href="{{ route('hotel.guests.index') }}?room={{ urlencode($device->room_no) }}" class="font-extrabold text-indigo-600 hover:underline">
                                            Room {{ $device->room_no }}
                                        </a>
                                    </div>
                                    <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 mt-0.5">
                                        <i class="fa-solid fa-user mr-1 text-[9px]"></i> {{ $activeGuest->name }}
                                    </span>
                                @else
                                    <div class="font-bold text-slate-700">Room {{ $device->room_no }}</div>
                                    <span class="text-[11px] text-slate-400 italic">Vacant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-medium text-slate-600">{{ $device->device_id }}</td>
                            <td class="px-6 py-4 font-mono font-medium text-slate-500">{{ $device->mac_address }}</td>
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
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('hotel.devices.ott', $device->id) }}" class="px-3 py-1.5 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold text-[11px] transition-colors">
                                        <i class="fa-solid fa-sliders mr-1"></i> OTT
                                    </a>
                                    <a href="{{ route('hotel.devices.menus', $device->id) }}" class="px-3 py-1.5 rounded-lg border border-violet-200 text-violet-600 hover:bg-violet-50 font-bold text-[11px] transition-colors">
                                        <i class="fa-solid fa-list-check mr-1"></i> Menus
                                    </a>
                                    <form action="{{ route('hotel.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Disconnect this TV screen?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-[11px] transition-colors">
                                            <i class="fa-solid fa-power-off mr-1"></i> Disconnect
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                                No connected TVs found. Log in from your TV app to synchronize.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-2">
        {{ $devices->links() }}
    </div>
</div>

<script>
    function openPairModal() {
        document.getElementById('pairModal').classList.remove('hidden');
        document.getElementById('pairCodeInput').focus();
    }

    function closePairModal() {
        document.getElementById('pairModal').classList.add('hidden');
        document.getElementById('pairFormAlert').classList.add('hidden');
        document.getElementById('pairForm').reset();
    }

    // Auto-format pair code input with hyphen e.g., ABCD-EFGH
    document.getElementById('pairCodeInput').addEventListener('input', function (e) {
        let val = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        if (val.length > 4) {
            val = val.substring(0, 4) + '-' + val.substring(4, 8);
        }
        e.target.value = val;
    });

    async function submitPairForm(event) {
        event.preventDefault();
        const code = document.getElementById('pairCodeInput').value.trim();
        const roomNo = document.getElementById('roomNoInput').value.trim();
        const alertBox = document.getElementById('pairFormAlert');
        const submitBtn = document.getElementById('pairSubmitBtn');

        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Pairing...';

        try {
            const response = await fetch("{{ route('hotel.devices.pair') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pair_code: code,
                    room_no: roomNo
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerText = data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
                alertBox.innerText = data.message || 'Pairing failed. Please check the code.';
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerText = 'Connect & Pair TV';
            }
        } catch (err) {
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerText = 'Connect & Pair TV';
        }
    }
</script>
@endsection
