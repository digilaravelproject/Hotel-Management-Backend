@extends('layouts.distributor')

@section('title', 'Sales Ledger - Distributor Hub')
@section('page_title', 'Package Sales Ledger')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Button (1-line responsive) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Sales & Revenue Ledger</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Complete transaction audit trail of all smart TV packages sold to your partner hotels.</p>
        </div>
        <a href="{{ route('distributor.sales.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 shrink-0 cursor-pointer">
            <i class="fa-solid fa-box-open text-xs"></i>
            <span>Sell New Package</span>
        </a>
    </div>

    <!-- Sales Table Container -->
    <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800">
                <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Transaction Date</th>
                        <th class="px-6 py-4">Hotel Property</th>
                        <th class="px-6 py-4">Package Plan</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4">License Key</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-black text-slate-900">{{ $sale->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $sale->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-900 text-sm">{{ $sale->hotel->hotel_name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold">{{ $sale->hotel->hotel_location ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($sale->plan)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                                        <i class="fa-solid fa-layer-group mr-1.5 text-[9px] text-blue-600"></i>
                                        <span>{{ $sale->plan->name }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Custom Package</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-900">₹{{ number_format($sale->amount, 0) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize font-bold text-slate-700 text-[11px] bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/80">
                                    {{ str_replace('_', ' ', $sale->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200/80 font-mono text-[11px] font-black text-slate-800 tracking-wider">
                                    {{ $sale->license_key_issued ?? ($sale->hotel->license_key ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                    <i class="fa-solid fa-circle-check text-[9px] mr-1 text-emerald-600"></i> Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px] max-w-xs truncate">
                                {{ $sale->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-14">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">No sales transactions recorded yet</p>
                                    <p class="text-xs text-slate-400">Sell your first licensing package to an onboarded hotel.</p>
                                    <div class="pt-3">
                                        <a href="{{ route('distributor.sales.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 inline-flex items-center space-x-1.5 active:scale-95">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Sell Package Now</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
