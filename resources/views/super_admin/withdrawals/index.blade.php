@extends('layouts.super_admin')

@section('title', 'Distributor Withdrawal Requests - Super Admin')
@section('page_title', 'Payout & Withdrawals')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Card (Pure White with Soft Gold Tint highlight) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7EB] shadow-sm relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#F5F2EB] border border-[#C5A880]/30 text-[#111827] text-xs font-bold">
                    <i class="fa-solid fa-money-bill-transfer text-[#C5A880]"></i>
                    <span>Settlement & Disbursements</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Withdrawal Requests</h2>
                <p class="text-xs sm:text-sm text-[#6B7280] max-w-xl">
                    Review and settle payout requests submitted by distributors. Approve requests by attaching the payment UTR / Transaction Reference, or reject with feedback to release locked funds back to the distributor.
                </p>
            </div>
            <div class="shrink-0 flex items-center space-x-3">
                <div class="p-4 bg-[#F5F2EB] border border-[#C5A880]/20 rounded-2xl flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <div class="text-[11px] text-[#6B7280] uppercase font-bold tracking-wider">Pending Payouts</div>
                        <div class="text-xl font-extrabold text-[#111827]">{{ $withdrawals->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-[#F0FDF4] border border-[#16A34A]/20 text-[#15803D] text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#DC2626]/20 text-[#B91C1C] text-xs space-y-1 shadow-xs">
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
    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-[#E5E7EB] flex items-center justify-between">
            <div class="font-bold text-[#111827] text-base flex items-center space-x-2">
                <i class="fa-solid fa-list-check text-[#C5A880]"></i>
                <span>All Payout Requests</span>
            </div>
            <span class="text-xs text-[#6B7280] font-semibold">Total {{ $withdrawals->total() }} requests</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F4F5F7] border-b border-[#E5E7EB] text-[#6B7280] font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Distributor</th>
                        <th class="py-4 px-6">Requested Amount</th>
                        <th class="py-4 px-6">Payment Mode & Details</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">#{{ $w->id }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $w->distributor->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $w->distributor->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-extrabold text-slate-900 text-sm">₹{{ number_format($w->amount, 2) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="inline-block px-2 py-0.5 rounded-md bg-slate-100 font-bold text-slate-700 text-[10px] uppercase mb-1">
                                    {{ strtoupper($w->payment_method) }}
                                </div>
                                <div class="text-slate-600 font-mono text-[11px] max-w-xs break-words">
                                    {{ $w->account_details }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($w->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#FFFBEB] text-[#B45309] border border-[#EA580C]/20">
                                        <i class="fa-solid fa-circle text-[6px] mr-1.5 animate-pulse text-[#B45309]"></i> Pending Review
                                    </span>
                                @elseif($w->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/20">
                                        <i class="fa-solid fa-check text-[10px] mr-1.5 text-[#15803D]"></i> Approved
                                    </span>
                                    <div class="text-[10px] text-[#6B7280] font-mono mt-0.5">Ref: {{ $w->transaction_number }}</div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#FEF2F2] text-[#B91C1C] border border-[#DC2626]/20">
                                        <i class="fa-solid fa-xmark text-[10px] mr-1.5 text-[#B91C1C]"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-[#6B7280] whitespace-nowrap">
                                {{ $w->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($w->status === 'pending')
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button" 
                                                onclick="openApproveModal({{ $w->id }}, '{{ number_format($w->amount, 2) }}', '{{ addslashes($w->distributor->name ?? 'Distributor') }}')"
                                                class="px-3 py-1.5 rounded-xl bg-[#16A34A] hover:bg-[#15803d] text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                                            Approve
                                        </button>
                                        <button type="button" 
                                                onclick="openRejectModal({{ $w->id }}, '{{ number_format($w->amount, 2) }}', '{{ addslashes($w->distributor->name ?? 'Distributor') }}')"
                                                class="px-3 py-1.5 rounded-xl bg-[#FEF2F2] hover:bg-red-100 text-[#B91C1C] font-bold text-xs border border-[#DC2626]/20 transition-all cursor-pointer">
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[#6B7280] font-semibold text-[11px]">Settled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#6B7280]">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block text-[#CBD5E1]"></i>
                                No withdrawal requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#E5E7EB] space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
            <div class="font-extrabold text-[#111827] text-base flex items-center space-x-2">
                <span class="w-8 h-8 rounded-xl bg-[#F0FDF4] text-[#15803D] flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-check"></i>
                </span>
                <span>Approve Withdrawal Payout</span>
            </div>
            <button type="button" onclick="closeApproveModal()" class="text-[#6B7280] hover:text-[#111827] text-lg cursor-pointer">&times;</button>
        </div>

        <form id="approveForm" method="POST" class="space-y-4">
            @csrf
            <div class="p-3 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] text-xs text-[#6B7280]">
                Approving payout of <strong class="text-[#111827] font-bold" id="approveAmountText"></strong> for <strong class="text-[#111827]" id="approveDistributorText"></strong>.
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#111827]">
                    Bank UTR / Transaction Reference Number <span class="text-[#B91C1C]">*</span>
                </label>
                <input type="text" 
                       name="transaction_number" 
                       required 
                       placeholder="e.g. UTR123456789 / IMPS987654" 
                       class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-[#111827] text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#C5A880]/30 focus:border-[#C5A880]">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#111827]">Admin Internal Notes (Optional)</label>
                <textarea name="admin_notes" 
                          rows="2" 
                          placeholder="Settled via NetBanking / Corporate UPI..." 
                          class="w-full px-4 py-2 rounded-xl border border-[#E5E7EB] text-[#111827] text-xs focus:outline-none focus:ring-2 focus:ring-[#C5A880]/30 focus:border-[#C5A880]"></textarea>
            </div>

            <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-end space-x-3">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-[#111827] bg-white border border-[#CBD5E1] hover:bg-[#F4F5F7]">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] font-bold text-xs shadow-md shadow-[#C5A880]/20">Confirm & Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#E5E7EB] space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
            <div class="font-extrabold text-[#111827] text-base flex items-center space-x-2">
                <span class="w-8 h-8 rounded-xl bg-[#FEF2F2] text-[#B91C1C] flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </span>
                <span>Reject Withdrawal Request</span>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-[#6B7280] hover:text-[#111827] text-lg cursor-pointer">&times;</button>
        </div>

        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <div class="p-3 rounded-2xl bg-[#FEF2F2] border border-[#DC2626]/20 text-xs text-[#B91C1C]">
                Rejecting will unlock the held funds (<strong id="rejectAmountText"></strong>) and refund them back to the distributor's wallet balance.
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#111827]">Rejection Reason / Notes</label>
                <textarea name="admin_notes" 
                          rows="3" 
                          required
                          placeholder="e.g. Invalid IFSC code, account name mismatch..." 
                          class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-[#111827] text-xs focus:outline-none focus:ring-2 focus:ring-[#DC2626]/20 focus:border-[#DC2626]"></textarea>
            </div>

            <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-[#111827] bg-white border border-[#CBD5E1] hover:bg-[#F4F5F7]">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#DC2626] hover:bg-red-700 text-white font-bold text-xs shadow-md shadow-[#DC2626]/20">Confirm Rejection</button>
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
