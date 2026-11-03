@extends('layouts.super_admin')

@section('title', 'Invoice #' . $invoice->invoice_number . ' - Super Admin')
@section('page_title', 'Invoice Detail')

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
        <a href="{{ route('super-admin.invoices.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-500 hover:text-[#150A42] transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Invoices</span>
        </a>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md transition-all flex items-center space-x-2 cursor-pointer">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Print / Download PDF</span>
            </button>
            @if($invoice->payment_status !== 'paid')
                <form action="{{ route('super-admin.invoices.mark-paid', $invoice->id) }}" method="POST" onsubmit="return confirm('Mark this invoice as PAID?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition-all cursor-pointer">
                        Mark as Paid
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="invoice-card bg-white border border-slate-200/80 rounded-[32px] p-8 sm:p-12 shadow-sm space-y-8">
        
        <!-- Header: Logo, Company & Invoice Meta -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-8 border-b border-slate-100">
            <div>
                <picture>
                    <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-12 w-auto object-contain">
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
                        <p><strong>Payment Date:</strong> {{ $invoice->paid_at->format('d F, Y') }}</p>
                    @endif
                    <div class="pt-1.5">
                        @if($invoice->payment_status === 'paid')
                            <span class="inline-flex px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black font-mono uppercase">
                                PAID ({{ strtoupper($invoice->payment_method ?? 'DIRECT') }})
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

        <!-- Billed To & Subscription Scope -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
            <div>
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 font-extrabold mb-1.5">BILLED TO (HOTEL CLIENT)</p>
                <h4 class="text-base font-extrabold text-slate-900">{{ $invoice->hotel->hotel_name ?? 'Hotel Client' }}</h4>
                <div class="text-xs text-slate-600 mt-1 space-y-0.5">
                    @if($invoice->hotel->owner_name)
                        <p>Attn: {{ $invoice->hotel->owner_name }}</p>
                    @endif
                    <p>{{ $invoice->hotel->hotel_location ?? 'Prime Location' }}, {{ $invoice->hotel->city ?? 'Metro' }}</p>
                    <p>{{ $invoice->hotel->email ?? '' }} • {{ $invoice->hotel->mobile_number ?? '' }}</p>
                    @if($invoice->hotel->gstin)
                        <p class="font-mono font-bold text-slate-800">Client GSTIN: {{ $invoice->hotel->gstin }}</p>
                    @endif
                </div>
            </div>

            <div class="sm:text-right">
                <p class="text-[10px] font-mono uppercase tracking-wider text-slate-400 font-extrabold mb-1.5">SUBSCRIPTION PERIOD</p>
                <h4 class="text-base font-extrabold text-purple-900">{{ $invoice->plan_name }}</h4>
                <div class="text-xs text-slate-600 mt-1 space-y-0.5 font-medium">
                    <p>Billing Cycle: <span class="font-bold text-slate-800 uppercase">{{ $invoice->billing_cycle }}</span></p>
                    <p>Smart TV Fleet: <span class="font-mono font-bold text-slate-800">{{ $invoice->tv_count }} Connected TVs</span></p>
                    @if($invoice->period_start && $invoice->period_end)
                        <p class="font-mono text-[11px] text-slate-500">Validity: {{ $invoice->period_start->format('d M Y') }} – {{ $invoice->period_end->format('d M Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-x-auto pt-4">
            <table class="w-full text-left text-xs border border-slate-200/80 rounded-2xl overflow-hidden">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase font-mono text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Item & Description</th>
                        <th class="py-3 px-4 text-center">TV Units</th>
                        <th class="py-3 px-4 text-right">Unit Rate</th>
                        <th class="py-3 px-4 text-right">Tax Details</th>
                        <th class="py-3 px-4 text-right">Amount (INR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <tr>
                        <td class="py-4 px-4">
                            <p class="font-extrabold text-slate-900 text-xs sm:text-sm">
                                PAX TV Smart TV OS Platform License ({{ ucfirst($invoice->billing_cycle) }})
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Cloud launcher, OTA app updates, customizable guest menus & live streaming applications.
                            </p>
                        </td>
                        <td class="py-4 px-4 text-center font-mono font-bold text-slate-800">
                            {{ $invoice->tv_count }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-bold text-slate-800">
                            ₹{{ number_format($invoice->price_per_tv, 2) }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono text-[11px]">
                            @if($invoice->tax_amount > 0)
                                <div class="font-bold text-purple-900">{{ $invoice->tax_name ?? 'GST' }} ({{ $invoice->tax_rate }}%)</div>
                                <div class="text-[10px] text-slate-400 capitalize">{{ $invoice->tax_type }}</div>
                            @else
                                <span class="text-slate-400">Zero / Nil</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-black text-slate-900">
                            ₹{{ number_format($invoice->subtotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary & Tax Totals -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 pt-4">
            <div class="max-w-xs text-xs text-slate-400 leading-relaxed font-medium">
                <p class="font-bold text-slate-600 mb-1">Notes & Terms:</p>
                <p>Payment due upon invoice receipt. This invoice is digitally generated and valid for all statutory GST tax deduction purposes.</p>
            </div>

            <div class="w-full sm:w-80 space-y-2 text-xs">
                <div class="flex justify-between py-1 text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>

                @if($invoice->cgst_amount > 0 && $invoice->sgst_amount > 0)
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>CGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                        <span class="font-mono font-bold text-slate-900">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>SGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                        <span class="font-mono font-bold text-slate-900">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                    </div>
                @elseif($invoice->tax_amount > 0)
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>{{ $invoice->tax_name ?? 'Tax' }} ({{ number_format($invoice->tax_rate, 2) }}%):</span>
                        <span class="font-mono font-bold text-slate-900">₹{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between py-2.5 border-t border-slate-200 text-sm font-extrabold text-[#150A42]">
                    <span>Total Payable:</span>
                    <span class="font-mono text-base font-black text-slate-900">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signoff -->
        <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-2">
            <p>Thank you for choosing PAX TV Hospitality Solutions.</p>
            <p class="font-mono">Authorized Signatory • PAX TV Finance</p>
        </div>

    </div>

</div>
@endsection

