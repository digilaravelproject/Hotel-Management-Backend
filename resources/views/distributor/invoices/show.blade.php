@extends('layouts.distributor')

@section('title', 'Tax Invoice #' . $invoice->invoice_number . ' - Distributor Hub')
@section('page_title', 'Tax Invoice Detail')

@section('styles')
<style>
@media print {
    body {
        background: #ffffff !important;
        color: #000000 !important;
    }
    aside, header, nav, #sidebar, .no-print, .action-buttons {
        display: none !important;
    }
    .flex-1.md\:pl-64 {
        padding-left: 0 !important;
    }
    .invoice-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
}
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar (Back & Print CTA) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('distributor.invoices.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-500 hover:text-[#150A42] transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Invoices</span>
        </a>

        <div class="flex items-center space-x-2.5">
            <a href="{{ route('distributor.sales.index') }}" class="px-3.5 py-2 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] text-xs font-bold transition-all shadow-2xs flex items-center space-x-1.5">
                <i class="fa-solid fa-receipt text-xs text-purple-600"></i>
                <span>Sales Ledger</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center space-x-2 cursor-pointer active:scale-95">
                <i class="fa-solid fa-print text-xs text-amber-300"></i>
                <span>Print / Download PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="invoice-card bg-white border border-slate-200/90 rounded-[32px] p-8 sm:p-12 shadow-sm space-y-8">
        
        <!-- Header: Logo, Company & Invoice Meta -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-8 border-b border-slate-100">
            <div>
                <picture>
                    <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-14 sm:h-16 w-auto object-contain">
                </picture>
                <div class="mt-3 text-xs text-slate-500 space-y-0.5">
                    <p class="font-extrabold text-slate-900">PAX TV Hospitality Network Private Limited</p>
                    <p>Executive Smart TV Solutions & Platform Operations</p>
                    <p>GSTIN: 27AAACP0123M1Z5 • support@paxtvnetwork.com</p>
                </div>
            </div>

            <div class="sm:text-right space-y-1">
                <span class="text-[11px] font-mono uppercase tracking-widest font-extrabold text-purple-700">TAX INVOICE</span>
                <h2 class="text-2xl font-black font-mono text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</h2>
                <div class="text-xs text-slate-500 font-medium space-y-0.5 pt-1">
                    <p><strong>Date of Issue:</strong> {{ $invoice->created_at->format('d F, Y') }}</p>
                    @if($invoice->paid_at)
                        <p><strong>Payment Settled:</strong> {{ $invoice->paid_at->format('d F, Y • h:i A') }}</p>
                    @endif
                    <div class="pt-1.5">
                        @if($invoice->payment_status === 'paid')
                            <span class="inline-flex px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black font-mono uppercase">
                                PAID ({{ strtoupper($invoice->payment_method ?? 'ONLINE GATEWAY') }})
                            </span>
                        @else
                            <span class="inline-flex px-3 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black font-mono uppercase">
                                PENDING PAYMENT
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Billed To & Distributor Channel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
            <div>
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 font-extrabold mb-1.5">BILLED TO (HOTEL CLIENT)</p>
                <h4 class="text-base font-extrabold text-slate-900">{{ $invoice->hotel ? $invoice->hotel->hotel_name : 'Hotel Client' }}</h4>
                <div class="text-xs text-slate-600 mt-1 space-y-0.5">
                    @if($invoice->hotel && $invoice->hotel->owner_name)
                        <p><strong>Attn:</strong> {{ $invoice->hotel->owner_name }}</p>
                    @endif
                    <p>{{ $invoice->hotel ? ($invoice->hotel->city ?? $invoice->hotel->hotel_location) : '' }}</p>
                    @if($invoice->hotel && $invoice->hotel->phone)
                        <p><strong>Phone:</strong> {{ $invoice->hotel->phone }}</p>
                    @endif
                    @if($invoice->hotel && $invoice->hotel->email)
                        <p><strong>Email:</strong> {{ $invoice->hotel->email }}</p>
                    @endif
                </div>
            </div>

            <div class="sm:text-right">
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 font-extrabold mb-1.5">AUTHORIZED CHANNEL DISTRIBUTOR</p>
                <h4 class="text-base font-extrabold text-slate-900">{{ auth()->user()->name }}</h4>
                <div class="text-xs text-slate-600 mt-1 space-y-0.5">
                    <p><strong>Channel Franchise:</strong> {{ auth()->user()->distributor_code ?? 'PAX-DIST' }}</p>
                    <p>{{ auth()->user()->email }}</p>
                    <p>Distributor Commercial Partner</p>
                </div>
            </div>
        </div>

        <!-- Subscription Itemization Table -->
        <div class="overflow-x-auto pt-4">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b-2 border-slate-900 text-[11px] font-mono font-black text-slate-900 uppercase tracking-wider">
                        <th class="py-3 px-2">Description / Package Service</th>
                        <th class="py-3 px-2 text-center">TV Slots</th>
                        <th class="py-3 px-2 text-right">Unit Rate (₹)</th>
                        <th class="py-3 px-2 text-right">Base Subtotal (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    <tr>
                        <td class="py-4 px-2">
                            <div class="font-extrabold text-slate-900 text-sm">
                                {{ $invoice->plan_name }} Tier Subscription
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Executive In-Room Smart TV Streaming & Pairing License
                            </div>
                            @if($invoice->period_start && $invoice->period_end)
                                <div class="text-[10px] font-mono text-purple-700 font-bold mt-1">
                                    Validity: {{ $invoice->period_start->format('d M, Y') }} — {{ $invoice->period_end->format('d M, Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-center font-mono font-bold text-slate-900">
                            {{ $invoice->tv_count }} Smart TVs
                        </td>
                        <td class="py-4 px-2 text-right font-mono">
                            ₹{{ number_format($invoice->price_per_tv, 2) }}
                        </td>
                        <td class="py-4 px-2 text-right font-mono font-bold text-slate-900">
                            ₹{{ number_format($invoice->subtotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pricing Totals & Statutory Tax Breakdown -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <div class="text-xs text-slate-500 space-y-2">
                <p class="font-extrabold text-slate-900 uppercase font-mono text-[11px]">Payment & Settlement Reference</p>
                @if($invoice->transaction_id)
                    <p><strong>Transaction Reference:</strong> <span class="font-mono text-slate-800 font-bold">{{ $invoice->transaction_id }}</span></p>
                @endif
                <p><strong>Settlement Channel:</strong> {{ ucwords(str_replace('_', ' ', $invoice->payment_method ?? 'Online Gateway')) }}</p>
                @if($invoice->notes)
                    <p class="italic text-[11px] text-slate-400">{{ $invoice->notes }}</p>
                @endif
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Base Amount (Taxable Value):</span>
                    <span class="font-mono font-bold text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>

                @if($invoice->tax_amount > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>{{ $invoice->tax_name ?? 'GST' }} ({{ number_format($invoice->tax_rate, 1) }}% - {{ ucfirst($invoice->tax_type) }}):</span>
                        <span class="font-mono font-bold text-slate-900">+ ₹{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>

                    @if($invoice->cgst_amount > 0)
                        <div class="flex justify-between text-[11px] text-slate-400 pl-3">
                            <span>CGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                            <span class="font-mono">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-400 pl-3">
                            <span>SGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                            <span class="font-mono">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                        </div>
                    @endif
                @else
                    <div class="flex justify-between text-slate-400">
                        <span>Tax Assessment:</span>
                        <span class="font-mono">₹0.00 (Zero Rated)</span>
                    </div>
                @endif

                <div class="pt-3 border-t-2 border-slate-900 flex justify-between items-center text-slate-900">
                    <span class="text-sm font-black">Total Amount Settled:</span>
                    <span class="text-xl font-black font-mono text-[#150A42]">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="pt-6 border-t border-slate-100 text-center text-[10px] text-slate-400 space-y-1">
            <p>This is a computer-generated tax-compliant invoice. No physical signature is required.</p>
            <p>© {{ date('Y') }} PAX TV Hospitality Network Private Limited. All rights reserved.</p>
        </div>

    </div>

</div>
@endsection
