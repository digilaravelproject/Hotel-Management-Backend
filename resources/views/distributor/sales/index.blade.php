@extends('layouts.distributor')

@section('title', 'Sales Ledger - Distributor Hub')
@section('page_title', 'Package Sales & Revenue Ledger')

@section('content')
<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">Complete transaction history of all smart TV packages sold to your partner hotels.</p>
        </div>
        <a href="{{ route('distributor.sales.create') }}" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-box-open text-xs"></i>
            <span>Sell New Package</span>
        </a>
    </div>

    <!-- Sales Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Transaction Date</th>
                        <th class="px-6 py-4">Hotel Client</th>
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
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-medium whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $sale->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $sale->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $sale->hotel->hotel_name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $sale->hotel->hotel_location ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($sale->plan)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        <i class="fa-solid fa-layer-group mr-1 text-[9px]"></i> {{ $sale->plan->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Custom Package</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-extrabold text-slate-900">₹{{ number_format($sale->amount, 0) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize font-semibold text-slate-700 text-[11px]">
                                    {{ str_replace('_', ' ', $sale->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-lg bg-slate-100 border border-slate-200 font-mono text-[10px] text-slate-700">
                                    {{ $sale->license_key_issued ?? ($sale->hotel->license_key ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[9px] mr-1"></i> Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px]">
                                {{ $sale->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                                        <i class="fa-solid fa-receipt text-xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">No sales transactions recorded yet</p>
                                    <p class="text-xs text-slate-400">Sell your first licensing package to an onboarded hotel.</p>
                                    <div class="pt-2">
                                        <a href="{{ route('distributor.sales.create') }}" class="px-4 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs">
                                            + Sell Package
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
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
