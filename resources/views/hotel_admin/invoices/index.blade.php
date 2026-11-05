@extends('layouts.hotel_admin')

@section('title', 'Tax Invoices & Billing - Hotel Admin')
@section('page_title', 'Tax Invoices & Billing History')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#F7F3F5] text-[#2A1820] border border-[#E5E7EB] font-mono">
                    <i class="fa-solid fa-file-invoice mr-1 text-[#8C7250]"></i> GST Tax Invoices
                </span>
                <span class="text-xs text-[#6B7280]">Official Invoices & Tax Receipts</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-[#111827] tracking-tight mt-1">Subscription Invoices & Receipts</h2>
            <p class="text-xs text-[#6B7280] font-medium mt-0.5">Download, view, and print official GST-compliant tax invoices for your hotel's Smart TV subscriptions and add-ons.</p>
        </div>

        <div class="flex items-center space-x-2.5 shrink-0 w-full sm:w-auto">
            <a href="{{ route('hotel.subscription') }}" class="flex-1 sm:flex-initial px-5 py-2.5 rounded-2xl bg-[#2A1820] hover:bg-[#3D242F] text-white font-extrabold text-xs shadow-md transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-credit-card text-xs text-[#C5A880]"></i>
                <span>Upgrade / Renew Plan</span>
            </a>
        </div>
    </div>

    <!-- 3 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- 1. Total Invoiced -->
        <div class="bg-white rounded-3xl p-5 border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Total Billed</span>
                <div class="w-9 h-9 rounded-2xl bg-[#F7F3F5] text-[#2A1820] flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-[#111827] tracking-tight leading-none">
                    ₹{{ number_format($stats['total_invoiced'] ?? 0, 2) }}
                </div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1.5">Lifetime subscription billing</div>
            </div>
        </div>

        <!-- 2. Total Paid -->
        <div class="bg-white rounded-3xl p-5 border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Paid / Settled</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-emerald-700 tracking-tight leading-none">
                    ₹{{ number_format($stats['total_paid'] ?? 0, 2) }}
                </div>
                <div class="flex items-center space-x-1.5 mt-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] text-emerald-800 font-semibold">Active & verified receipts</span>
                </div>
            </div>
        </div>

        <!-- 3. Total Invoices Count -->
        <div class="bg-white rounded-3xl p-5 border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Invoices Issued</span>
                <div class="w-9 h-9 rounded-2xl bg-[#F7F3F5] text-[#8C7250] flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-[#111827] tracking-tight leading-none">
                    {{ number_format($stats['invoices_count'] ?? 0) }}
                </div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1.5">Tax receipts in ledger</div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-3xl p-4 border border-[#E5E7EB] shadow-xs">
        <form action="{{ route('hotel.invoices.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by Invoice number, plan name, or transaction ID..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-[#F4F5F7] border border-[#CBD5E1] text-xs text-[#111827] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2A1820]/20 focus:border-[#2A1820] transition-all">
            </div>

            <div class="flex items-center space-x-2.5">
                <x-dropdown 
                    name="status" 
                    id="hotelInvoiceStatusFilter" 
                    label="All Statuses" 
                    :selected="request('status')" 
                    :options="['paid' => 'Paid', 'pending' => 'Pending']" 
                    selectClass="px-3.5 py-2.5 text-xs font-bold"
                    wrapClass="shrink-0"
                    onchange="this.form.submit()"
                />

                <button type="submit" class="px-4 py-2.5 rounded-2xl bg-[#F4F5F7] hover:bg-slate-200 text-[#111827] font-bold text-xs transition-all cursor-pointer">
                    Filter
                </button>

                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('hotel.invoices.index') }}" class="px-3 py-2.5 rounded-2xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition-all" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Invoices Table Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-[30px] overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#111827]">
                <thead>
                    <tr class="bg-[#F4F5F7] border-b border-[#E5E7EB] text-[#6B7280] font-black uppercase tracking-wider text-[10px] font-mono">
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Issue Date</th>
                        <th class="px-6 py-4">Subscription Plan</th>
                        <th class="px-6 py-4">Screens</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($invoices as $inv)
                        @php
                            $isPaid = ($inv->payment_status === 'paid');
                        @endphp
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <span class="font-mono font-black text-xs text-[#111827]">{{ $inv->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="font-bold text-xs text-[#111827]">{{ $inv->created_at->format('d M, Y') }}</div>
                                <div class="text-[10px] text-[#6B7280] font-mono">{{ $inv->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-black bg-[#F7F3F5] text-[#2A1820] border border-[#E5E7EB]">
                                    <i class="fa-solid fa-cube mr-1 text-[9px] text-[#8C7250]"></i>
                                    <span>{{ $inv->plan_name }}</span>
                                </span>
                                <span class="text-[10px] text-[#6B7280] font-mono block mt-0.5">
                                    {{ ucfirst($inv->billing_cycle ?? 'monthly') }} Plan
                                </span>
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <span class="font-mono font-bold text-xs text-[#2A1820]">{{ $inv->tv_count }} TVs</span>
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="font-mono font-black text-xs text-[#111827]">₹{{ number_format($inv->total_amount, 2) }}</div>
                                @if($inv->tax_amount > 0)
                                    <span class="text-[10px] text-[#6B7280] font-mono">Incl. ₹{{ number_format($inv->tax_amount, 2) }} Tax</span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase font-mono bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ strtoupper($inv->payment_method ?? 'ONLINE') }}
                                </span>
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($isPaid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200 font-mono uppercase">
                                        <i class="fa-solid fa-clock mr-1 text-[9px]"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-right">
                                <a href="{{ route('hotel.invoices.show', $inv->id) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-[#2A1820] hover:bg-[#3D242F] text-white font-bold text-xs shadow-xs transition-all active:scale-95">
                                    <i class="fa-solid fa-file-invoice text-[10px] text-[#C5A880] mr-1.5"></i>
                                    <span>View / Print</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-14">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-[#F7F3F5] text-[#2A1820] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                    <p class="font-black text-[#111827] text-sm">No tax invoices recorded yet</p>
                                    <p class="text-xs text-[#6B7280]">Official GST invoices will appear here once subscription or add-on payments are settled.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-[#E5E7EB] bg-[#F4F5F7] flex items-center justify-between text-xs text-[#6B7280]">
                <div>Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} receipts</div>
                <div>{{ $invoices->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection

