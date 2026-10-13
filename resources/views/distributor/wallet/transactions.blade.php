@extends('layouts.distributor')

@section('title', 'Wallet Transaction Ledger - Distributor Hub')
@section('page_title', 'Wallet Transaction History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/60 rounded-3xl p-6 sm:p-8 text-white border border-slate-700 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs border border-amber-500/30">
                <i class="fa-solid fa-receipt mr-1.5"></i> Double-Entry Ledger
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Complete Transaction History</h2>
            <p class="text-xs text-slate-300 max-w-xl">
                Every credit earned through client package sales and every debit settled through administrator withdrawals is permanently recorded here.
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
                <a href="{{ route('distributor.wallet.transactions') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request()->filled('type') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    All Records
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'credit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'credit' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Credits (+ Earned)
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'debit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'debit' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Debits (- Payouts)
                </a>
            </div>
            <div class="text-xs text-slate-400 font-semibold">
                Showing {{ $transactions->total() }} ledger entries
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description / Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-500">
                                #TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $tx->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-slate-400">{{ $tx->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->type === 'credit')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> Credit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900">{{ $tx->description }}</div>
                                @if($tx->reference_type)
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        Ref: {{ strtoupper($tx->reference_type) }} #{{ $tx->reference_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">
                                ₹{{ number_format($tx->balance_after, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300 block"></i>
                                No ledger transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
