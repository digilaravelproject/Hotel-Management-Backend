@extends('layouts.distributor')

@section('title', 'Wallet Transaction Ledger - Distributor Hub')
@section('page_title', 'Wallet Transaction History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-purple-50/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-receipt text-[10px] text-purple-600"></i>
                <span>Double-Entry Financial Ledger</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Complete Transaction History</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
                Every credit earned through client package sales and every debit settled through administrator payouts is permanently logged here.
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
                <a href="{{ route('distributor.wallet.transactions') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ !request()->filled('type') ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    All Records
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'credit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('type') === 'credit' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Credits (+ Earned)
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'debit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('type') === 'debit' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Debits (- Payouts)
                </a>
            </div>
            <div class="text-xs text-slate-400 font-bold">
                Showing {{ $transactions->total() }} ledger entries
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs text-slate-800">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description / Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-500">
                                #TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                <div class="font-black text-slate-900">{{ $tx->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $tx->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->type === 'credit')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                        <i class="fa-solid fa-arrow-down mr-1 text-[9px] text-emerald-600"></i> Credit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 shadow-2xs">
                                        <i class="fa-solid fa-arrow-up mr-1 text-[9px] text-rose-600"></i> Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-900">{{ $tx->description }}</div>
                                @if($tx->reference_type)
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5 font-bold">
                                        Ref: {{ strtoupper($tx->reference_type) }} #{{ $tx->reference_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($tx->balance_after, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-14">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">No transactions found</p>
                                    <p class="text-xs text-slate-400">No ledger activity matches the selected filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
