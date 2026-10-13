@extends('layouts.distributor')

@section('title', 'Payout Requests - Distributor Hub')
@section('page_title', 'Withdrawal Requests History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/60 rounded-3xl p-6 sm:p-8 text-white border border-slate-700 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs border border-amber-500/30">
                <i class="fa-solid fa-money-bill-transfer mr-1.5"></i> Payout Audit Trail
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Withdrawal Requests & Settlements</h2>
            <p class="text-xs text-slate-300 max-w-xl">
                Check the current processing status of your payouts, bank UTR numbers provided by the administrator, and rejection remarks if any issue occurred.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('distributor.wallet.index') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-wallet text-xs"></i>
                <span>Wallet Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('distributor.wallet.withdrawals') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request()->filled('status') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    All
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950 font-extrabold shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Pending
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Approved & Settled
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Rejected
                </a>
            </div>
            <div class="text-xs text-slate-400 font-semibold">
                Showing {{ $withdrawals->total() }} withdrawal records
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Request ID</th>
                        <th class="px-6 py-4">Requested On</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payout Method & Details</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Transaction UTR / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($withdrawals as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-500">
                                #WTH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $item->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-slate-400">{{ $item->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-900">₹{{ number_format($item->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] uppercase mb-1">
                                    {{ str_replace('_', ' ', $item->payment_method ?? 'Bank') }}
                                </span>
                                <div class="text-[11px] text-slate-700 font-mono break-words leading-tight">
                                    {{ $item->account_details }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1.5"></span> Under Review
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[10px] mr-1.5"></i> Settled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
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
