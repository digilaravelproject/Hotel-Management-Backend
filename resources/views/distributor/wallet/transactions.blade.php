@extends('layouts.distributor')

@section('title', 'Wallet Transaction Ledger - Distributor Hub')
@section('page_title', 'Wallet Transaction History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7EB] shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#F1F5F9] text-[#111827] font-bold text-xs border border-[#C5A880]/30">
                <i class="fa-solid fa-receipt mr-1.5 text-[#C5A880]"></i> Double-Entry Ledger
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Complete Transaction History</h2>
            <p class="text-xs text-[#6B7280] max-w-xl">
                Every credit earned through client package sales and every debit settled through administrator withdrawals is permanently recorded here.
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
                <a href="{{ route('distributor.wallet.transactions') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request()->filled('type') ? 'bg-[#111827] text-white' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    All Records
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'credit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'credit' ? 'bg-[#16A34A] text-white shadow-sm' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    Credits (+ Earned)
                </a>
                <a href="{{ route('distributor.wallet.transactions', ['type' => 'debit']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('type') === 'debit' ? 'bg-[#DC2626] text-white shadow-sm' : 'text-[#6B7280] hover:bg-[#F4F5F7]' }}">
                    Debits (- Payouts)
                </a>
            </div>
            <div class="text-xs text-[#6B7280] font-semibold">
                Showing {{ $transactions->total() }} ledger entries
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F4F5F7] border-b border-[#E5E7EB] text-[11px] font-extrabold text-[#6B7280] uppercase tracking-wider">
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description / Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4 text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] text-xs">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-[#F4F5F7] transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-[#6B7280]">
                                #TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-[#111827] font-medium">
                                {{ $tx->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-[#6B7280]">{{ $tx->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->type === 'credit')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/20">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> Credit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#FEF2F2] text-[#B91C1C] border border-[#DC2626]/20">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-[#111827]">{{ $tx->description }}</div>
                                @if($tx->reference_type)
                                    <div class="text-[10px] text-[#6B7280] font-mono mt-0.5">
                                        Ref: {{ strtoupper($tx->reference_type) }} #{{ $tx->reference_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-[#15803D]' : 'text-[#111827]' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-[#111827]">
                                ₹{{ number_format($tx->balance_after, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#6B7280]">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-[#CBD5E1] block"></i>
                                No ledger transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-6 border-t border-[#E5E7EB]">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
