@extends('layouts.super_admin')

@section('title', 'Distributor Withdrawal Requests - Super Admin')
@section('page_title', 'Payout & Withdrawals')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-[26px] p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-100 text-[#150A42] text-xs font-bold font-mono">
                    <i class="fa-solid fa-money-bill-transfer text-purple-600"></i>
                    <span>SETTLEMENT & DISBURSEMENTS</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Withdrawal Requests</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-xl">
                    Review and settle payout requests submitted by distributors. Approve requests by attaching the payment UTR / Transaction Reference, or reject with feedback to release locked funds back to the distributor.
                </p>
            </div>
            <div class="shrink-0 flex items-center space-x-3">
                <div class="p-4 bg-purple-50/70 border border-purple-100 rounded-2xl flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white border border-purple-100 flex items-center justify-center text-[#150A42] font-bold shadow-xs">
                        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider font-mono">Pending Payouts</div>
                        <div class="text-xl font-extrabold text-slate-900 font-mono">{{ $withdrawals->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600 shrink-0"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Withdrawals Table Card -->
    <div class="bg-white rounded-[26px] border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="font-extrabold text-slate-900 text-base flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <span>All Payout Requests</span>
            </div>
            <span class="text-xs text-slate-500 font-semibold font-mono">Total {{ $withdrawals->total() }} requests</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8F9FD] border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-6">Distributor</th>
                        <th class="py-3.5 px-6">Requested Amount</th>
                        <th class="py-3.5 px-6">Payment Mode & Details</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-900 font-mono">#{{ $w->id }}</td>
                            <td class="py-3.5 px-6">
                                <div class="font-extrabold text-slate-900">{{ $w->distributor->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $w->distributor->email ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="font-extrabold text-slate-900 text-sm font-mono">₹{{ number_format($w->amount, 2) }}</span>
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="inline-block px-2 py-0.5 rounded-md bg-purple-50 border border-purple-100 font-bold text-[#150A42] text-[10px] uppercase font-mono mb-1">
                                    {{ strtoupper($w->payment_method) }}
                                </div>
                                <div class="text-slate-600 font-mono text-[11px] max-w-xs break-words">
                                    {{ $w->account_details }}
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                @if($w->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono uppercase tracking-wider">
                                        <i class="fa-solid fa-circle text-[5px] mr-1.5 animate-pulse text-amber-500"></i> Pending Review
                                    </span>
                                @elseif($w->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono uppercase tracking-wider">
                                        <i class="fa-solid fa-check text-[9px] mr-1 text-emerald-600"></i> Approved
                                    </span>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Ref: {{ $w->transaction_number }}</div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono uppercase tracking-wider">
                                        <i class="fa-solid fa-xmark text-[9px] mr-1 text-rose-600"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap font-mono text-[11px]">
                                {{ $w->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                @if($w->status === 'pending')
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button" 
                                                onclick="openApproveModal({{ $w->id }}, '{{ number_format($w->amount, 2) }}', '{{ addslashes($w->distributor->name ?? 'Distributor') }}')"
                                                class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                                            Approve
                                        </button>
                                        <button type="button" 
                                                onclick="openRejectModal({{ $w->id }}, '{{ number_format($w->amount, 2) }}', '{{ addslashes($w->distributor->name ?? 'Distributor') }}')"
                                                class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition-all cursor-pointer">
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-semibold text-[11px] font-mono uppercase">Settled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-[#F8F9FD] border border-slate-200 flex items-center justify-center mx-auto text-slate-400 text-lg mb-3">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700 mb-0.5">No Withdrawal Requests Found</h4>
                                <p class="text-[11px] text-slate-400">There are no pending or settled payout requests at this time.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="font-extrabold text-slate-900 text-base flex items-center space-x-2.5">
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-check"></i>
                </span>
                <span>Approve Withdrawal Payout</span>
            </div>
            <button type="button" onclick="closeApproveModal()" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="approveForm" method="POST" class="space-y-4">
            @csrf
            <div class="p-3.5 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 text-xs text-slate-600">
                Approving payout of <strong class="text-slate-900 font-mono font-bold" id="approveAmountText"></strong> for <strong class="text-slate-900" id="approveDistributorText"></strong>.
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-800">
                    Bank UTR / Transaction Reference Number <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="transaction_number" 
                       required 
                       placeholder="e.g. UTR123456789 / IMPS987654" 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 font-mono text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-800">Admin Internal Notes (Optional)</label>
                <textarea name="admin_notes" 
                          rows="2" 
                          placeholder="Settled via NetBanking / Corporate UPI..." 
                          class="w-full px-4 py-2 rounded-xl border border-slate-200 text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#F8F9FD] focus:bg-white transition-all resize-y"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="btn-sa-primary px-5 py-2 text-xs shadow-md">Confirm & Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-[#0A051B]/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-[26px] max-w-md w-full p-6 shadow-2xl border border-slate-200/90 space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="font-extrabold text-slate-900 text-base flex items-center space-x-2.5">
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </span>
                <span>Reject Withdrawal Request</span>
            </div>
            <button type="button" onclick="closeRejectModal()" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 leading-relaxed">
                Rejecting will unlock the held funds (<strong id="rejectAmountText" class="font-mono font-bold"></strong>) and refund them back to the distributor's wallet balance.
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-800">Rejection Reason / Notes <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" 
                          rows="3" 
                          required
                          placeholder="e.g. Invalid IFSC code, account name mismatch..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-[#F8F9FD] focus:bg-white transition-all resize-y"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition-all cursor-pointer">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(id, amount, name) {
        document.getElementById('approveForm').action = '/super-admin/withdrawals/' + id + '/approve';
        document.getElementById('approveAmountText').textContent = '₹' + amount;
        document.getElementById('approveDistributorText').textContent = name;
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    function openRejectModal(id, amount, name) {
        document.getElementById('rejectForm').action = '/super-admin/withdrawals/' + id + '/reject';
        document.getElementById('rejectAmountText').textContent = '₹' + amount;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection