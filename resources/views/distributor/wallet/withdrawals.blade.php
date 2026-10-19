@extends('layouts.distributor')

@section('title', 'Payout Requests - Distributor Hub')
@section('page_title', 'Withdrawal Requests History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7EB] shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#F1F5F9] text-[#111827] font-bold text-xs border border-[#C5A880]/30">
                <i class="fa-solid fa-money-bill-transfer mr-1.5 text-[#C5A880]"></i> Payout Audit Trail
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Withdrawal Requests & Settlements</h2>
            <p class="text-xs text-[#6B7280] max-w-xl">
                Check the current processing status of your payouts, bank UTR numbers provided by the administrator, and rejection remarks if any issue occurred.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('distributor.wallet.index') }}" class="px-5 py-3 rounded-2xl bg-[#27374D] hover:bg-[#8C7250] text-white font-extrabold text-xs shadow-md shadow-[#C5A880]/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-wallet text-xs"></i>
                <span>Wallet Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-6 border-b border-[#E5E7EB] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('distributor.wallet.withdrawals') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request()->filled('status') ? 'bg-[#111827] text-white' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    All
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'pending' ? 'bg-[#27374D] text-white font-extrabold shadow-sm' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    Pending
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'approved' ? 'bg-[#16A34A] text-white shadow-sm' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    Approved & Settled
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'rejected' ? 'bg-[#DC2626] text-white shadow-sm' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    Rejected
                </a>
            </div>
            <div class="text-xs text-[#6B7280] font-semibold">
                Showing {{ $withdrawals->total() }} withdrawal records
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F4F5F7] border-b border-[#E5E7EB] text-[11px] font-extrabold text-[#6B7280] uppercase tracking-wider">
                        <th class="px-6 py-4">Request ID</th>
                        <th class="px-6 py-4">Requested On</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payout Method & Details</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Transaction UTR / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] text-xs">
                    @forelse($withdrawals as $item)
                        <tr class="hover:bg-[#F4F5F7] transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-[#6B7280]">
                                #WTH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-[#111827] font-medium">
                                {{ $item->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-[#6B7280]">{{ $item->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-[#111827]">₹{{ number_format($item->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-[#F4F5F7] border border-[#E5E7EB] text-[#111827] font-bold text-[10px] uppercase mb-1">
                                    {{ str_replace('_', ' ', $item->payment_method ?? 'Bank') }}
                                </span>
                                <div class="text-[11px] text-[#111827] font-mono break-words leading-tight">
                                    {{ $item->account_details }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#FFFBEB] text-[#B45309] border border-[#EA580C]/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C] animate-pulse mr-1.5"></span> Under Review
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/20">
                                        <i class="fa-solid fa-check text-[10px] mr-1.5"></i> Settled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-[#FEF2F2] text-[#B91C1C] border border-[#DC2626]/20">
                                        <i class="fa-solid fa-xmark text-[10px] mr-1.5"></i> Rejected & Refunded
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                @if($item->transaction_number)
                                    <div class="font-mono text-[11px] font-bold text-emerald-700">
                                        <i class="fa-solid fa-receipt mr-1 text-[10px]"></i> Ref: {{ $item->transaction_number }}
                                    </div>
                                @endif
                                @if($item->admin_notes)
                                    <div class="text-[11px] {{ $item->status === 'rejected' ? 'text-rose-600 font-medium' : 'text-slate-500 italic' }} mt-0.5">
                                        "{{ $item->admin_notes }}"
                                    </div>
                                @endif
                                @if($item->processed_at)
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $item->processed_at->format('M d, Y h:i A') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-money-bill-transfer text-3xl mb-2 text-slate-300 block"></i>
                                No withdrawal requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $withdrawals->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
