@extends('layouts.distributor')

@section('title', 'Payout Requests - Distributor Hub')
@section('page_title', 'Withdrawal Requests History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-purple-50/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-money-bill-transfer text-[10px] text-purple-600"></i>
                <span>Payout Settlement Trail</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Withdrawal Requests & Settlements</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
                Check the current status of your payouts, bank UTR numbers provided by administration, and processing remarks.
            </p>
        </div>

        <div class="flex items-center space-x-3 relative z-10 shrink-0">
            <a href="{{ route('distributor.wallet.index') }}" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-wallet text-xs"></i>
                <span>Wallet Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center space-x-2">
                <a href="{{ route('distributor.wallet.withdrawals') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ !request()->filled('status') ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    All
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Pending
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Approved & Settled
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Rejected
                </a>
            </div>
            <div class="text-xs text-slate-400 font-bold">
                Showing {{ $withdrawals->total() }} withdrawal records
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs text-slate-800">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Request ID</th>
                        <th class="px-6 py-4">Requested On</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payout Method & Details</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Transaction UTR / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-500">
                                #WTH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                <div class="font-black text-slate-900">{{ $item->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $item->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-900">₹{{ number_format($item->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-slate-100 border border-slate-200/80 text-slate-700 font-black text-[10px] uppercase mb-1">
                                    {{ str_replace('_', ' ', $item->payment_method ?? 'Bank') }}
                                </span>
                                <div class="text-[11px] text-slate-600 font-mono break-words leading-tight">
                                    {{ $item->account_details }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-200/60 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Under Review
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200/60 shadow-2xs">
                                        <i class="fa-solid fa-check text-[10px] mr-1.5 text-emerald-600"></i> Settled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-800 border border-rose-200/60 shadow-2xs">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->transaction_number)
                                    <div class="font-mono font-black text-emerald-700 text-xs">
                                        <i class="fa-solid fa-receipt mr-1 text-[10px]"></i> {{ $item->transaction_number }}
                                    </div>
                                @endif
                                @if($item->admin_notes)
                                    <div class="text-[11px] text-slate-400 italic mt-0.5">
                                        Note: "{{ $item->admin_notes }}"
                                    </div>
                                @endif
                                @if(!$item->transaction_number && !$item->admin_notes)
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-14">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">No payout requests found</p>
                                    <p class="text-xs text-slate-400">No withdrawal records match the selected status.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
