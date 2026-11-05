@extends('layouts.hotel_admin')

@section('title', 'Tax Invoice #' . $invoice->invoice_number . ' - Hotel Admin')
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
        <a href="{{ route('hotel.invoices.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#6B7280] hover:text-[#2A1820] transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Invoices</span>
        </a>

        <div class="flex items-center space-x-2.5">
            <a href="{{ route('hotel.subscription') }}" class="px-3.5 py-2 rounded-2xl bg-white border border-[#E5E7EB] text-[#111827] hover:bg-[#F7F3F5] text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-credit-card text-xs text-[#8C7250]"></i>
                <span>Plan & Billing</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 rounded-2xl bg-[#2A1820] hover:bg-[#3D242F] text-white font-extrabold text-xs shadow-md transition-all flex items-center space-x-2 cursor-pointer active:scale-95">
                <i class="fa-solid fa-print text-xs text-[#C5A880]"></i>
                <span>Print / Download PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="invoice-card bg-white border border-[#E5E7EB] rounded-[32px] p-8 sm:p-12 shadow-xs space-y-8">
        
        <!-- Header: Logo, Company & Invoice Meta -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-8 border-b border-[#E5E7EB]">
            <div>
                <picture>
                    <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-14 sm:h-16 w-auto object-contain">
                </picture>
                <div class="mt-3 text-xs text-[#6B7280] space-y-0.5">
                    <p class="font-extrabold text-[#111827]">PAX TV Hospitality Network Private Limited</p>
                    <p>Executive Smart TV Solutions & Platform Operations</p>
                    <p>GSTIN: 27AAACP0123M1Z5 • support@paxtvnetwork.com</p>
                </div>
            </div>

            <div class="sm:text-right space-y-1">
                <span class="text-[11px] font-mono uppercase tracking-widest font-extrabold text-[#8C7250]">TAX INVOICE</span>
                <h2 class="text-2xl font-black font-mono text-[#111827] tracking-tight">{{ $invoice->invoice_number }}</h2>
                <div class="text-xs text-[#6B7280] font-medium space-y-0.5 pt-1">
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
                <p class="text-[10px] font-mono uppercase tracking-wider text-[#6B7280] font-extrabold mb-1.5">BILLED TO (HOTEL PROPERTY)</p>
                <h4 class="text-base font-extrabold text-[#111827]">{{ $hotel->hotel_name }}</h4>
                <div class="text-xs text-[#6B7280] mt-1 space-y-0.5">
                    @if($hotel->owner_name)
                        <p><strong>Attn:</strong> {{ $hotel->owner_name }}</p>
                    @endif
                    <p>{{ $hotel->hotel_location ?? ($hotel->city . ', India') }}</p>
                    @if($hotel->phone)
                        <p><strong>Phone:</strong> {{ $hotel->phone }}</p>
                    @endif
                    @if($hotel->email)
                        <p><strong>Email:</strong> {{ $hotel->email }}</p>
                    @endif
                </div>
            </div>

            <div class="sm:text-right">
                <p class="text-[10px] font-mono uppercase tracking-wider text-[#6B7280] font-extrabold mb-1.5">AUTHORIZED PARTNER / CHANNEL</p>
                <h4 class="text-base font-extrabold text-[#111827]">
                    {{ $invoice->distributor ? $invoice->distributor->name : 'Pax TV Direct Hospitality' }}
                </h4>
                <div class="text-xs text-[#6B7280] mt-1 space-y-0.5">
                    @if($invoice->distributor && $invoice->distributor->email)
                        <p>{{ $invoice->distributor->email }}</p>
                    @endif
                    <p>Channel Tier: Authorized Regional Partner</p>
                    @if($invoice->transaction_id)
                        <p class="font-mono text-[10px] text-slate-500 pt-1">
                            Ref / Txn ID: <strong class="text-[#111827]">{{ $invoice->transaction_id }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-hidden rounded-2xl border border-[#E5E7EB]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F4F5F7] border-b border-[#E5E7EB] text-[#6B7280] font-black uppercase tracking-wider text-[10px] font-mono">
                    <tr>
                        <th class="py-3.5 px-4">Item Description</th>
                        <th class="py-3.5 px-4 text-center">Billing Cycle</th>
                        <th class="py-3.5 px-4 text-center">Screens (Qty)</th>
                        <th class="py-3.5 px-4 text-right">Unit Rate</th>
                        <th class="py-3.5 px-4 text-right">Taxable Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] text-[#111827]">
                    <tr>
                        <td class="py-4 px-4">
                            <p class="font-black text-sm text-[#111827]">{{ $invoice->plan_name }}</p>
                            <p class="text-[11px] text-[#6B7280] mt-0.5">
                                In-Room TV Launcher, Dynamic OTT streaming apps, and Cloud CMS access.
                            </p>
                            @if($invoice->period_start && $invoice->period_end)
                                <p class="text-[10px] font-mono text-[#8C7250] mt-1">
                                    Validity: {{ $invoice->period_start->format('d M, Y') }} — {{ $invoice->period_end->format('d M, Y') }}
                                </p>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center font-bold font-mono uppercase text-[11px]">
                            {{ $invoice->billing_cycle }}
                        </td>
                        <td class="py-4 px-4 text-center font-mono font-bold text-sm">
                            {{ $invoice->tv_count }} Smart TVs
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-bold text-xs">
                            ₹{{ number_format($invoice->price_per_tv, 2) }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-black text-sm">
                            ₹{{ number_format($invoice->subtotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Calculation & Statutory Taxes Summary -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 pt-2">
            <div class="text-xs text-[#6B7280] space-y-2 max-w-sm">
                <p class="font-bold text-[#111827] uppercase tracking-wider text-[10px] font-mono">TERMS & STATUTORY NOTES</p>
                <p class="text-[11px] leading-relaxed">
                    This is an electronically generated Tax Invoice under Section 31 of CGST Act. Valid for input tax credit (ITC) as applicable. All fees settled through authorized payment gateway.
                </p>
                @if($invoice->notes)
                    <div class="p-3 rounded-xl bg-[#F7F3F5] border border-[#E5E7EB] text-[11px]">
                        <strong>Notes:</strong> {{ $invoice->notes }}
                    </div>
                @endif
            </div>

            <!-- Tax Calculation Stack -->
            <div class="w-full sm:w-80 space-y-2.5 text-xs text-[#111827]">
                <div class="flex justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#6B7280]">Taxable Amount (Subtotal):</span>
                    <span class="font-mono font-bold">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>

                @if($invoice->cgst_amount > 0 || $invoice->sgst_amount > 0)
                    <div class="flex justify-between py-1 border-b border-[#E5E7EB] text-[#6B7280]">
                        <span>CGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                        <span class="font-mono font-bold text-[#111827]">+ ₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-[#E5E7EB] text-[#6B7280]">
                        <span>SGST ({{ number_format($invoice->tax_rate / 2, 1) }}%):</span>
                        <span class="font-mono font-bold text-[#111827]">+ ₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                    </div>
                @elseif($invoice->tax_amount > 0)
                    <div class="flex justify-between py-1 border-b border-[#E5E7EB] text-[#6B7280]">
                        <span>{{ $invoice->tax_name ?: 'GST' }} ({{ number_format($invoice->tax_rate, 1) }}%):</span>
                        <span class="font-mono font-bold text-[#111827]">+ ₹{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between py-2 border-b-2 border-[#111827] text-sm sm:text-base font-black">
                    <span class="text-[#111827]">Total Invoice Amount:</span>
                    <span class="font-mono text-[#2A1820]">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>

                <div class="text-[10px] text-right text-emerald-700 font-bold uppercase font-mono pt-1">
                    <i class="fa-solid fa-circle-check mr-1"></i> Paid in Full via {{ strtoupper($invoice->payment_method ?? 'ONLINE') }}
                </div>
            </div>
        </div>

        <!-- Footer Signatory & Contact -->
        <div class="pt-8 border-t border-[#E5E7EB] flex flex-col sm:flex-row items-center justify-between text-xs text-[#6B7280] gap-4">
            <div>
                <p>Questions? Reach hospitality support at <a href="mailto:support@paxtvnetwork.com" class="text-[#8C7250] font-bold">support@paxtvnetwork.com</a></p>
                <p class="text-[10px] mt-0.5">Pax TV Hospitality Platform • All rights reserved</p>
            </div>
            <div class="text-center sm:text-right">
                <div class="w-32 h-10 border-b border-dashed border-[#CBD5E1] mx-auto sm:ml-auto"></div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#111827] mt-1">Authorized Signatory</p>
            </div>
        </div>

    </div>

</div>
@endsection

