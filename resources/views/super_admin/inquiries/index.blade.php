@extends('layouts.super_admin')

@section('title', 'Website Inquiries & Leads - Super Admin')
@section('page_title', 'Website Demo Requests & Leads')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- 1. Total Leads -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Leads</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1 font-mono">{{ $totalCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- 2. New / Pending -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">New Leads</p>
                <h3 class="text-2xl font-black text-emerald-700 mt-1 font-mono">{{ $newCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-envelope-open"></i>
            </div>
        </div>

        <!-- 3. In Progress / Contacted -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Contacted</p>
                <h3 class="text-2xl font-black text-blue-700 mt-1 font-mono">{{ $contactedCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-phone"></i>
            </div>
        </div>

        <!-- 4. Converted -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Converted</p>
                <h3 class="text-2xl font-black text-amber-700 mt-1 font-mono">{{ $convertedCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-trophy"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Segmented Status Tabs -->
        <div class="inline-flex p-1 bg-slate-100 rounded-2xl border border-slate-200/80 overflow-x-auto scrollbar-none">
            @php $q = request('search') ? ['search' => request('search')] : []; @endphp
            <a href="{{ route('super-admin.inquiries.index', array_merge($q, ['status' => 'all'])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                All ({{ $totalCount }})
            </a>
            <a href="{{ route('super-admin.inquiries.index', array_merge($q, ['status' => 'new'])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === 'new' ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                New ({{ $newCount }})
            </a>
            <a href="{{ route('super-admin.inquiries.index', array_merge($q, ['status' => 'contacted'])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === 'contacted' ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Contacted
            </a>
            <a href="{{ route('super-admin.inquiries.index', array_merge($q, ['status' => 'converted'])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === 'converted' ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Converted
            </a>
            <a href="{{ route('super-admin.inquiries.index', array_merge($q, ['status' => 'closed'])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === 'closed' ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Closed
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('super-admin.inquiries.index') }}" class="flex items-center gap-2">
            @if($status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="relative w-full md:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Search name, hotel, email..."
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 shadow-xs">
            </div>
            <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-[#150A42] text-white hover:bg-purple-900 transition shadow-xs">
                Filter
            </button>
            @if($search || $status !== 'all')
                <a href="{{ route('super-admin.inquiries.index') }}" class="px-3 py-2 text-xs font-semibold rounded-xl bg-slate-200 text-slate-700 hover:bg-slate-300 transition" title="Clear Filters">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 uppercase font-mono tracking-wider text-[10px] text-slate-400">
                    <tr>
                        <th class="py-3 px-4">Lead #</th>
                        <th class="py-3 px-4">Prospect / Contact</th>
                        <th class="py-3 px-4">Hotel / Property</th>
                        <th class="py-3 px-4">Rooms</th>
                        <th class="py-3 px-4">Message Snippet</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Received</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $lead)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <!-- ID -->
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                            #{{ $lead->id }}
                        </td>

                        <!-- Contact -->
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $lead->name }}</div>
                            <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                <i class="fa-regular fa-envelope text-[10px]"></i>
                                <a href="mailto:{{ $lead->email }}" class="hover:text-purple-600 hover:underline">{{ $lead->email }}</a>
                            </div>
                            @if($lead->phone)
                            <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-phone text-[9px]"></i>
                                <a href="tel:{{ $lead->phone }}" class="hover:text-purple-600 hover:underline">{{ $lead->phone }}</a>
                            </div>
                            @endif
                        </td>

                        <!-- Property -->
                        <td class="py-3.5 px-4">
                            @if($lead->hotel_name)
                                <span class="font-semibold text-slate-800">{{ $lead->hotel_name }}</span>
                            @else
                                <span class="text-slate-400 italic">Not specified</span>
                            @endif
                        </td>

                        <!-- Rooms -->
                        <td class="py-3.5 px-4">
                            @if($lead->rooms)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $lead->rooms }} Rooms
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        <!-- Message -->
                        <td class="py-3.5 px-4 max-w-[220px]">
                            <p class="truncate text-slate-700" title="{{ $lead->message ?? 'No additional message' }}">
                                {{ $lead->message ?: ($lead->subject ?: 'Demo Request') }}
                            </p>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4">
                            @php
                                $statusClasses = [
                                    'new'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'contacted'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'converted'   => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'closed'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                                $badgeClass = $statusClasses[$lead->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                {{ str_replace('_', ' ', $lead->status) }}
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                            <div>{{ $lead->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $lead->created_at->diffForHumans() }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <!-- View / Edit Modal Trigger -->
                                <button type="button" 
                                        onclick="openInquiryModal({{ json_encode($lead) }})"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-[#150A42] hover:bg-purple-50 transition"
                                        title="View Details & Update Status">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>

                                <!-- Direct Email Reply -->
                                <a href="mailto:{{ $lead->email }}?subject=Re: {{ urlencode($lead->subject ?? 'Pax TV Launcher Demo Request') }}"
                                   class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition"
                                   title="Reply via Email">
                                    <i class="fa-solid fa-reply text-xs"></i>
                                </a>

                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('super-admin.inquiries.destroy', $lead->id) }}" onsubmit="return confirm('Are you sure you want to delete lead #{{ $lead->id }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete Inquiry">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p class="text-sm font-semibold text-slate-600">No website demo inquiries found.</p>
                            <p class="text-xs text-slate-400 mt-0.5">When visitors fill the demo form on paxtvlauncher.com, they will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
        <div class="p-4 border-t border-slate-200/80">
            {{ $inquiries->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: View Inquiry Details & Update Status -->
<div id="inquiry-detail-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-200">
        <!-- Modal Header -->
        <div class="p-5 bg-gradient-to-r from-[#150A42] to-purple-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold" id="modal-title">Inquiry Details #--</h3>
                <p class="text-[11px] text-purple-200" id="modal-date">Received: --</p>
            </div>
            <button type="button" onclick="closeInquiryModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Modal Body & Update Form -->
        <form id="inquiry-update-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-5 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto">
                <!-- Prospect Info Box -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Name:</span>
                        <span class="font-bold text-slate-900" id="modal-name">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Email:</span>
                        <span class="font-bold text-purple-700" id="modal-email">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Phone:</span>
                        <span class="font-bold text-slate-900" id="modal-phone">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Hotel/Property:</span>
                        <span class="font-bold text-slate-900" id="modal-hotel">--</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Rooms:</span>
                        <span class="font-bold text-slate-900" id="modal-rooms">--</span>
                    </div>
                </div>

                <!-- Message Box -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Message from Prospect:</label>
                    <div class="p-3 bg-slate-100 rounded-xl border border-slate-200 text-slate-800 leading-relaxed whitespace-pre-line" id="modal-message">
                        --
                    </div>
                </div>

                <!-- Update Status Dropdown -->
                <div>
                    <label for="modal-status-select" class="block font-bold text-slate-700 mb-1">Lead Status:</label>
                    <select name="status" id="modal-status-select" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 focus:outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 text-xs font-semibold">
                        <option value="new">New (Uncontacted)</option>
                        <option value="in_progress">In Progress</option>
                        <option value="contacted">Contacted</option>
                        <option value="converted">Converted (Deal Won)</option>
                        <option value="closed">Closed / Not Interested</option>
                    </select>
                </div>

                <!-- Internal Admin Notes -->
                <div>
                    <label for="modal-admin-notes" class="block font-bold text-slate-700 mb-1">Internal Admin Notes:</label>
                    <textarea name="admin_notes" id="modal-admin-notes" rows="3" placeholder="Add follow-up notes, call summary, or quote details..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 focus:outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 text-xs"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2">
                <button type="button" onclick="closeInquiryModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200 transition">
                    Close
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#150A42] text-white hover:bg-purple-900 transition shadow-xs">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openInquiryModal(lead) {
    document.getElementById('modal-title').textContent = 'Inquiry Details #' + lead.id;
    document.getElementById('modal-date').textContent = 'Received: ' + (new Date(lead.created_at)).toLocaleString();
    document.getElementById('modal-name').textContent = lead.name || '--';
    document.getElementById('modal-email').textContent = lead.email || '--';
    document.getElementById('modal-phone').textContent = lead.phone || 'Not provided';
    document.getElementById('modal-hotel').textContent = lead.hotel_name || 'Not specified';
    document.getElementById('modal-rooms').textContent = lead.rooms ? (lead.rooms + ' Rooms') : 'Not specified';
    document.getElementById('modal-message').textContent = lead.message || (lead.subject || 'No message provided.');
    
    document.getElementById('modal-status-select').value = lead.status || 'new';
    document.getElementById('modal-admin-notes').value = lead.admin_notes || '';

    const form = document.getElementById('inquiry-update-form');
    form.action = "{{ url('super-admin/inquiries') }}/" + lead.id + "/status";

    document.getElementById('inquiry-detail-modal').classList.remove('hidden');
}

function closeInquiryModal() {
    document.getElementById('inquiry-detail-modal').classList.add('hidden');
}
</script>
@endsection

