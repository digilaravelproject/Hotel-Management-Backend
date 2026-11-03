@extends('layouts.super_admin')

@section('title', 'Invoices & Billing Ledger - Super Admin')
@section('page_title', 'Invoices')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header & Action CTA -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.plans.index') }}" class="hover:text-[#150A42] transition-colors">
                    <span>Plans & Subscriptions</span>
                </a>
                <span>/</span>
                <span class="text-[#150A42]">Invoices</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Invoices & Billing Ledger</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Automated tax-compliant billing invoices generated for hotel TV subscriptions.</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('super-admin.taxes.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-xs">
                <i class="fa-solid fa-receipt text-xs"></i>
                <span>Tax Settings</span>
            </a>
            <a href="{{ route('super-admin.plans.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white text-xs font-extrabold transition-all shadow-md">
                <i class="fa-solid fa-tags text-xs"></i>
                <span>Per-TV Plans</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('super-admin.invoices.index') }}" method="GET" class="flex flex-1 items-center space-x-3 w-full">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Invoice # or Hotel name..." class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:bg-white focus:outline-hidden focus:border-purple-300">
            </div>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </form>
    </div>

    <!-- Invoices Table Card -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 font-mono">
                        <th class="py-3.5 px-6">Invoice #</th>
                        <th class="py-3.5 px-6">Hotel Client</th>
                        <th class="py-3.5 px-6">Plan & TVs</th>
                        <th class="py-3.5 px-6">Tax Applied</th>
                        <th class="py-3.5 px-6">Total Amount</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="py-4 px-6 font-mono font-extrabold text-[#150A42] text-xs">
                                <a href="{{ route('super-admin.invoices.show', $inv->id) }}" class="hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-normal font-sans">{{ $inv->created_at->format('d M, Y') }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                                    {{ $inv->hotel->hotel_name ?? 'Direct Client' }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    {{ $inv->hotel->city ?? 'Metro' }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">
                                    {{ $inv->plan_name }} ({{ ucfirst($inv->billing_cycle) }})
                                </div>
                                <div class="text-[11px] font-mono text-purple-700 font-bold">
                                    {{ $inv->tv_count }} TVs × ₹{{ number_format($inv->price_per_tv) }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($inv->tax_amount > 0)
                                    <div class="font-mono text-xs font-bold text-slate-800">
                                        ₹{{ number_format($inv->tax_amount, 2) }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono">
                                        {{ $inv->tax_name ?? 'GST' }} ({{ $inv->tax_type }})
                                    </div>
                                @else
                                    <span class="text-slate-400 font-mono text-[11px]">No Tax</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-mono text-sm font-black text-slate-900">
                                ₹{{ number_format($inv->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($inv->payment_status === 'paid')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-extrabold font-mono uppercase">
                                        PAID
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold font-mono uppercase">
                                        PENDING
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('super-admin.invoices.show', $inv->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-[#150A42] text-[#150A42] hover:text-white font-extrabold text-xs transition-all shadow-xs">
                                    <i class="fa-solid fa-file-invoice text-[11px]"></i>
                                    <span>View Invoice</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-file-invoice-dollar text-3xl mb-2 text-slate-300"></i>
                                <p class="font-bold">No Invoices generated yet.</p>
                                <p class="text-xs mt-1">Invoices are automatically generated when hotels subscribe to Monthly or Yearly per-TV plans.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

