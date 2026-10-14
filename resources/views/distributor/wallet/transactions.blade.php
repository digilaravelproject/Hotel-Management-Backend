@extends('layouts.distributor')

@section('title', 'Wallet Transaction Ledger - Distributor Hub')
@section('page_title', 'Wallet Transaction History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E2E8F0] shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF9EE] text-[#004B87] font-bold text-xs border border-[#E5A93C]/30">
                <i class="fa-solid fa-receipt mr-1.5 text-[#E5A93C]"></i> Double-Entry Ledger
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B] tracking-tight">Complete Transaction History</h2>
            <p class="text-xs text-[#64748B] max-w-xl">
                Every credit earned through client package sales and every debit settled through administrator withdrawals is permanently recorded here.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('distributor.wallet.index') }}" class="px-5 py-3 rounded-2xl bg-[#E5A93C] hover:bg-[#C88E26] text-white font-extrabold text-xs shadow-md shadow-[#E5A93C]/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-wallet text-xs"></i>
                <span>Wallet Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-[#E2E8F0] rounded-3xl shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-6 border-b border-[#E2E8F0] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('distributor.wallet.transactions') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request()->filled('type') ? 'bg-[#004B87] text-white' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                    All Records
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'credit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'credit' ? 'bg-[#16A34A] text-white shadow-sm' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                    Credits (+ Earned)
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'debit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'debit' ? 'bg-[#DC2626] text-white shadow-sm' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                    Debits (- Payouts)
                </a>
            </div>
            <div class="text-xs text-[#64748B] font-semibold">
                Showing {{ $transactions->total() }} ledger entries
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[11px] font-extrabold text-[#64748B] uppercase tracking-wider">
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description / Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-xs">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-[#64748B]">
                                #TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-[#1E293B] font-medium">
                                {{ $tx->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-[#64748B]">{{ $tx->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->type === 'credit')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/20">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> Credit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#FEE2E2] text-[#DC2626] border border-[#DC2626]/20">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-[#1E293B]">{{ $tx->description }}</div>
                                @if($tx->reference_type)
                                    <div class="text-[10px] text-[#64748B] font-mono mt-0.5">
                                        Ref: {{ strtoupper($tx->reference_type) }} #{{ $tx->reference_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-[#16A34A]' : 'text-[#1E293B]' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-[#1E293B]">
                                ₹{{ number_format($tx->balance_after, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-[#CBD5E1] block"></i>
                                No ledger transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-6 border-t border-[#E2E8F0]">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
