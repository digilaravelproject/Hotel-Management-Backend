@extends('layouts.distributor')

@section('title', 'Tax Invoices & Billing Ledger - Distributor Hub')
@section('page_title', 'Tax Invoices')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('distributor.dashboard') }}" class="hover:text-[#150A42] transition-colors">
                    <span>Distributor Hub</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Tax Invoices</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Tax Invoices & Billing Ledger</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Statutory tax invoices and payment settlement receipts generated for partner hotels.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0">
            <a href="{{ route('distributor.sales.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-receipt text-xs text-purple-600"></i>
                <span>Sales Ledger</span>
            </a>
            <a href="{{ route('distributor.sales.create') }}" class="px-4 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white text-xs font-extrabold transition-all shadow-md shadow-[#150A42]/15 flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-cart-plus text-xs text-amber-300"></i>
                <span>+ Sell Package</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- 1. Total Invoices -->
        <div class="p-5 rounded-[24px] bg-white border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Issued Invoices</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalInvoicesCount }}</h3>
                <span class="text-[11px] text-slate-400 font-medium">Billed across partner properties</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        <!-- 2. Total Billed Volume -->
        <div class="p-5 rounded-[24px] bg-white border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Billed (Paid)</span>
                <h3 class="text-2xl font-black text-[#150A42] mt-1 font-mono">₹{{ number_format($totalPaidAmount, 2) }}</h3>
                <span class="text-[11px] text-emerald-600 font-bold">100% Tax Compliant</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- 3. Active Hotels Billed -->
        <div class="p-5 rounded-[24px] bg-white border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Compliance Format</span>
                <h3 class="text-base font-black text-slate-900 mt-1">GST & Tax Invoices</h3>
                <span class="text-[11px] text-slate-400 font-medium">Auto-generated with CGST / SGST</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 border border-sky-100 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-building-shield"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-[22px] p-3.5 border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('distributor.invoices.index') }}" method="GET" class="flex flex-1 items-center space-x-3 w-full">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Invoice # or Hotel name..." class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-[#150A42] transition-all">
            </div>

            <x-dropdown 
                name="status" 
                id="invoiceStatusFilter" 
                label="All Statuses" 
                :selected="request('status')" 
                :options="['paid' => 'Paid', 'pending' => 'Pending']" 
                selectClass="px-3.5 py-2.5 text-xs font-bold"
                wrapClass="shrink-0"
                onchange="this.form.submit()"
            />

            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('distributor.invoices.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all" title="Reset Filters">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Invoices Ledger Table Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400 font-mono">
                        <th class="py-4 px-6">Invoice #</th>
                        <th class="py-4 px-6">Billed Hotel</th>
                        <th class="py-4 px-6">Plan & TV Slots</th>
                        <th class="py-4 px-6">Base Subtotal</th>
                        <th class="py-4 px-6">Tax Breakdown</th>
                        <th class="py-4 px-6">Total Amount</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-purple-50/30 transition-colors">
                            <td class="py-4 px-6 font-mono font-black text-[#150A42] text-xs">
                                <a href="{{ route('distributor.invoices.show', $inv->id) }}" class="hover:underline flex items-center space-x-1.5 group">
                                    <i class="fa-solid fa-file-invoice text-purple-600 group-hover:scale-110 transition-transform"></i>
                                    <span>{{ $inv->invoice_number }}</span>
                                </a>
                                <div class="text-[10px] text-slate-400 font-normal font-sans mt-0.5">
                                    {{ $inv->created_at->format('d M, Y • h:i A') }}
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                                    {{ $inv->hotel ? $inv->hotel->hotel_name : 'Partner Hotel' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium flex items-center mt-0.5">
                                    <i class="fa-solid fa-location-dot mr-1 text-slate-400 text-[10px]"></i>
                                    <span>{{ $inv->hotel ? ($inv->hotel->city ?? $inv->hotel->hotel_location) : 'Location' }}</span>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">
                                    {{ $inv->plan_name }}
                                </div>
                                <div class="text-[11px] font-mono text-purple-700 font-bold mt-0.5">
                                    {{ $inv->tv_count }} TVs × ₹{{ number_format($inv->price_per_tv, 2) }}
                                </div>
                            </td>

                            <td class="py-4 px-6 font-mono font-bold text-slate-800">
                                ₹{{ number_format($inv->subtotal, 2) }}
                            </td>

                            <td class="py-4 px-6">
                                @if($inv->tax_amount > 0)
                                    <div class="font-mono text-xs font-bold text-emerald-700">
                                        + ₹{{ number_format($inv->tax_amount, 2) }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono">
                                        {{ $inv->tax_name ?? 'GST' }} ({{ number_format($inv->tax_rate, 1) }}% {{ ucfirst($inv->tax_type) }})
                                    </div>
                                @else
                                    <span class="text-slate-400 font-mono text-[11px]">₹0.00 (Zero Rated)</span>
                                @endif
                            </td>

                            <td class="py-4 px-6 font-mono font-black text-slate-900 text-sm">
                                ₹{{ number_format($inv->total_amount, 2) }}
                            </td>

                            <td class="py-4 px-6 text-center">
                                @if($inv->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] font-black font-mono uppercase">
                                        <i class="fa-solid fa-check text-[9px] mr-1"></i> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black font-mono uppercase">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('distributor.invoices.show', $inv->id) }}" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white text-xs font-bold transition-all inline-flex items-center space-x-1.5 shadow-2xs">
                                        <i class="fa-solid fa-eye text-[11px]"></i>
                                        <span>View Invoice</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-xl mx-auto mb-3 shadow-2xs">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <h4 class="font-bold text-slate-700 text-sm">No Tax Invoices Found</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Invoices are automatically created whenever you sell a subscription package to a hotel property.</p>
                                <div class="mt-4">
                                    <a href="{{ route('distributor.sales.create') }}" class="px-4 py-2 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        <span>Sell First Package</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

