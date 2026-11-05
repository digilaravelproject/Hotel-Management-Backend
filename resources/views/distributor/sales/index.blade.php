@extends('layouts.distributor')

@section('title', 'Sales & Package Ledger - Distributor Hub')
@section('page_title', 'Package Sales Ledger')

@section('content')
<div class="space-y-6">

    <!-- Header & Top Action CTA (1-line responsive) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2.5">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-[#150A42] border border-purple-200/60 font-mono shadow-2xs">
                    <i class="fa-solid fa-chart-pie mr-1 text-[9px] text-purple-600"></i> Revenue Ledger
                </span>
                <span class="text-xs text-slate-400 font-medium">Real-Time License Audit</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">Package Sales & Revenue Ledger</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Complete transaction audit trail of all Smart TV subscription packages and add-ons sold to your partner hotels.</p>
        </div>
        <div class="flex items-center space-x-2.5 shrink-0 w-full sm:w-auto">
            <a href="{{ route('distributor.invoices.index') }}" class="flex-1 sm:flex-initial px-4 py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 text-xs font-bold transition-all shadow-2xs flex items-center justify-center space-x-2 active:scale-95">
                <i class="fa-solid fa-file-invoice-dollar text-xs text-purple-600"></i>
                <span>All Tax Invoices</span>
            </a>
            <a href="{{ route('distributor.sales.create') }}" class="flex-1 sm:flex-initial px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-cart-plus text-xs text-amber-300"></i>
                <span>Sell New Package</span>
            </a>
        </div>
    </div>

    <!-- 4 Core Performance KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Gross Sales -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between hover:border-purple-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Gross Sales Revenue</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-slate-900 tracking-tight leading-none">
                    ₹{{ number_format($stats['total_revenue'] ?? 0, 2) }}
                </div>
                <div class="flex items-center space-x-1.5 mt-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] text-slate-500 font-medium">All completed transactions</span>
                </div>
            </div>
        </div>

        <!-- 2. Total Packages Sold -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between hover:border-purple-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Packages Sold</span>
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-cube"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-slate-900 tracking-tight leading-none">
                    {{ number_format($stats['total_sales'] ?? 0) }}
                </div>
                <div class="flex items-center space-x-1 mt-2 text-[11px] text-purple-700 font-bold">
                    <i class="fa-solid fa-check-double text-[10px]"></i>
                    <span>Subscriptions & Add-Ons</span>
                </div>
            </div>
        </div>

        <!-- 3. Distinct Partner Hotels Billed -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between hover:border-purple-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Partner Clients</span>
                <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-hotel"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-slate-900 tracking-tight leading-none">
                    {{ number_format($stats['unique_hotels'] ?? 0) }}
                </div>
                <div class="flex items-center space-x-1 mt-2 text-[11px] text-slate-500 font-medium">
                    <i class="fa-solid fa-building text-[10px] text-indigo-600"></i>
                    <span>Properties billed</span>
                </div>
            </div>
        </div>

        <!-- 4. Average Order Value -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between hover:border-purple-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Avg Package Value</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black font-mono text-slate-900 tracking-tight leading-none">
                    ₹{{ number_format($stats['avg_sale'] ?? 0, 0) }}
                </div>
                <div class="flex items-center space-x-1 mt-2 text-[11px] text-slate-500 font-medium">
                    <i class="fa-solid fa-shield-halved text-[10px] text-amber-600"></i>
                    <span>Per order average</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm">
        <form action="{{ route('distributor.sales.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by Hotel name, location, license key, or remarks..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs text-slate-800 font-semibold focus:bg-white focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5">
                <!-- Hotel Filter Dropdown -->
                @php
                    $hotelOpts = ['' => 'All Partner Hotels'];
                    foreach($hotels as $h) {
                        $hotelOpts[$h->id] = $h->hotel_name . ($h->city ? ' (' . $h->city . ')' : '');
                    }
                @endphp
                <x-dropdown 
                    name="hotel_id" 
                    id="hotelFilterSelect" 
                    label="All Partner Hotels" 
                    :selected="request('hotel_id')" 
                    :options="$hotelOpts" 
                    selectClass="px-3.5 py-2.5 text-xs font-bold"
                    wrapClass="shrink-0 min-w-[190px]"
                    onchange="this.form.submit()"
                />

                <!-- Status Filter Dropdown -->
                <x-dropdown 
                    name="payment_status" 
                    id="statusFilterSelect" 
                    label="All Statuses" 
                    :selected="request('payment_status')" 
                    :options="[
                        '' => 'All Statuses',
                        'completed' => 'Completed / Paid',
                        'pending' => 'Pending',
                        'failed' => 'Failed'
                    ]" 
                    selectClass="px-3.5 py-2.5 text-xs font-bold"
                    wrapClass="shrink-0 min-w-[140px]"
                    onchange="this.form.submit()"
                />

                <!-- Submit Button -->
                <button type="submit" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all active:scale-95 cursor-pointer flex items-center space-x-1.5 shrink-0">
                    <i class="fa-solid fa-filter text-[11px] text-purple-700"></i>
                    <span>Filter</span>
                </button>

                <!-- Reset Filters Button -->
                @if(request()->filled('search') || request()->filled('hotel_id') || request()->filled('payment_status'))
                    <a href="{{ route('distributor.sales.index') }}" class="px-3 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all shrink-0 flex items-center space-x-1" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sales Ledger Table Card -->
    <div class="bg-white border border-slate-100 rounded-[30px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-800">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[10px] font-mono">
                        <th class="px-6 py-4">Transaction & Date</th>
                        <th class="px-6 py-4">Hotel Property</th>
                        <th class="px-6 py-4">Package Plan</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4">License Key</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Invoice & Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                        @php
                            $cycle = strtolower($sale->plan->billing_cycle ?? 'monthly');
                            $isYearly = ($cycle === 'yearly');
                            $hotelName = $sale->hotel ? $sale->hotel->hotel_name : 'N/A';
                            $location = $sale->hotel ? ($sale->hotel->city ?? $sale->hotel->hotel_location ?? '—') : '—';
                            $owner = $sale->hotel ? ($sale->hotel->owner_name ?? '') : '';
                            $licenseKey = $sale->license_key_issued ?: ($sale->hotel->license_key ?? 'N/A');
                            $amountFormatted = number_format($sale->amount, 2);
                            $dateFormatted = $sale->created_at->format('d M, Y');
                            $timeFormatted = $sale->created_at->format('h:i A');
                            $status = strtolower($sale->payment_status ?? 'completed');
                            $isPaid = in_array($status, ['completed', 'paid']);
                            $invoiceUrl = route('distributor.sales.invoice', $sale->id);

                            $modalData = [
                                'id' => $sale->id,
                                'date' => $dateFormatted . ' • ' . $timeFormatted,
                                'hotel_name' => $hotelName,
                                'location' => $location,
                                'owner' => $owner,
                                'phone' => $sale->hotel ? ($sale->hotel->phone ?? '') : '',
                                'plan_name' => $sale->plan ? $sale->plan->name : 'Smart TV Licensing Tier',
                                'billing_cycle' => ucfirst($cycle),
                                'amount' => '₹' . $amountFormatted,
                                'payment_method' => strtoupper(str_replace('_', ' ', $sale->payment_method ?? 'online_gateway')),
                                'license_key' => $licenseKey,
                                'status' => ucfirst($status),
                                'notes' => $sale->notes ?: 'Standard subscription package activation',
                                'invoice_url' => $invoiceUrl,
                                'invoice_number' => $sale->matched_invoice ? $sale->matched_invoice->invoice_number : null,
                                'tv_count' => $sale->matched_invoice ? $sale->matched_invoice->tv_count : ($sale->hotel->room_count ?? '—'),
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <!-- 1. Transaction Date & Time -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-xs font-black shrink-0 border border-purple-100/80">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-xs tracking-tight">{{ $dateFormatted }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono flex items-center space-x-1 mt-0.5">
                                            <i class="fa-regular fa-clock text-[9px]"></i>
                                            <span>{{ $timeFormatted }}</span>
                                            <span>•</span>
                                            <span>{{ $sale->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Hotel Property -->
                            <td class="px-6 py-4.5">
                                <div class="font-black text-slate-900 text-xs sm:text-sm tracking-tight flex items-center gap-1.5">
                                    <span>{{ $hotelName }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium flex items-center gap-2 mt-0.5">
                                    <span><i class="fa-solid fa-location-dot text-[9px] text-purple-600"></i> {{ $location }}</span>
                                    @if($owner)
                                        <span>•</span>
                                        <span><i class="fa-solid fa-user text-[9px] text-slate-400"></i> {{ $owner }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 3. Package Plan -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($sale->plan)
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-black {{ $isYearly ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-purple-50 text-[#150A42] border border-purple-200/60' }} shadow-2xs">
                                            @if($isYearly)
                                                <i class="fa-solid fa-crown mr-1.5 text-[9px] text-amber-600"></i>
                                            @else
                                                <i class="fa-solid fa-layer-group mr-1.5 text-[9px] text-purple-600"></i>
                                            @endif
                                            <span>{{ $sale->plan->name }}</span>
                                        </span>
                                        <div class="text-[10px] font-mono text-slate-400 font-semibold pl-1">
                                            {{ $isYearly ? 'Annual Billing' : 'Monthly Billing' }}
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-600 font-bold text-[11px] border border-slate-200">
                                        TV Add-On / Custom
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Amount -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="text-sm font-black font-mono text-slate-900 tracking-tight">₹{{ $amountFormatted }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Gross Total</div>
                            </td>

                            <!-- 5. Payment Method -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase font-mono tracking-wider {{ str_contains(strtolower($sale->payment_method), 'online') || str_contains(strtolower($sale->payment_method), 'razorpay') || str_contains(strtolower($sale->payment_method), 'payu') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/80' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    <i class="fa-solid fa-shield-check mr-1 text-[9px] text-emerald-600"></i>
                                    {{ str_replace('_', ' ', $sale->payment_method ?? 'Gateway') }}
                                </span>
                            </td>

                            <!-- 6. License Key -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($licenseKey && $licenseKey !== 'N/A')
                                    <div class="inline-flex items-center space-x-1.5 bg-slate-50 border border-slate-200/90 rounded-xl px-2.5 py-1 group/key">
                                        <span class="font-mono text-[11px] font-black text-slate-800 tracking-wider">
                                            {{ $licenseKey }}
                                        </span>
                                        <button type="button" 
                                                onclick="copyToClipboard('{{ $licenseKey }}', this)" 
                                                class="text-slate-400 hover:text-[#150A42] p-1 cursor-pointer transition-colors" 
                                                title="Copy License Key">
                                            <i class="fa-regular fa-copy text-[11px]"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-mono italic text-[11px]">—</span>
                                @endif
                            </td>

                            <!-- 7. Status -->
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($isPaid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs font-mono uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> Paid
                                    </span>
                                @elseif($status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs font-mono uppercase">
                                        <i class="fa-solid fa-circle-xmark mr-1 text-[9px]"></i> Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs font-mono uppercase">
                                        <i class="fa-solid fa-clock mr-1 text-[9px]"></i> Pending
                                    </span>
                                @endif
                            </td>

                            <!-- 8. Invoice & Details Actions (User Request: "yaha per invocie ka bhi option do view ka button") -->
                            <td class="px-6 py-4.5 whitespace-nowrap text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    
                                    <!-- View Details Modal Trigger -->
                                    <button type="button" 
                                            onclick='openSaleDetailModal(@json($modalData))'
                                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-[#150A42] font-black text-xs transition-all shadow-2xs flex items-center space-x-1.5 cursor-pointer active:scale-95"
                                            title="View Transaction Details">
                                        <i class="fa-solid fa-eye text-[11px] text-slate-500"></i>
                                        <span>View</span>
                                    </button>

                                    <!-- Direct Tax Invoice Button -->
                                    <a href="{{ $invoiceUrl }}" 
                                       class="px-3.5 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-xs border border-purple-200/80 transition-all shadow-2xs flex items-center space-x-1.5 active:scale-95 group/inv cursor-pointer" 
                                       title="View & Print Official GST Tax Invoice">
                                        <i class="fa-solid fa-file-invoice-dollar text-[11px] text-purple-600 group-hover/inv:scale-110 transition-transform"></i>
                                        <span>Invoice</span>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center space-y-3 text-slate-400">
                                    <div class="w-16 h-16 rounded-3xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-2xl shadow-xs">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 text-base">No Sales Transactions Found</p>
                                        <p class="text-xs text-slate-400 mt-0.5">No subscription purchases match your current search and filter criteria.</p>
                                    </div>
                                    <div class="pt-2 flex items-center space-x-2">
                                        @if(request()->filled('search') || request()->filled('hotel_id') || request()->filled('payment_status'))
                                            <a href="{{ route('distributor.sales.index') }}" class="px-4 py-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                                                Clear Filters
                                            </a>
                                        @endif
                                        <a href="{{ route('distributor.sales.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 inline-flex items-center space-x-2 active:scale-95">
                                            <i class="fa-solid fa-plus text-xs text-amber-300"></i>
                                            <span>Sell New Package Now</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Record Stats -->
        @if($sales->hasPages() || $sales->total() > 0)
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-medium">
                <div>
                    Showing <strong class="text-slate-800 font-bold">{{ $sales->firstItem() ?? 0 }}</strong> to <strong class="text-slate-800 font-bold">{{ $sales->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800 font-bold">{{ $sales->total() }}</strong> transactions
                </div>
                <div>
                    {{ $sales->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Quick View Transaction Detail Modal -->
<div id="saleDetailModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-[#0B0424]/70 backdrop-blur-xs transition-opacity" onclick="closeSaleDetailModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-xl border border-slate-100">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#150A42] to-[#251066] p-6 text-white relative">
                <button type="button" onclick="closeSaleDetailModal()" class="absolute top-5 right-5 text-white/60 hover:text-white p-2 rounded-xl transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 text-white border border-white/20 font-mono">
                        Transaction Audit Record
                    </span>
                    <span id="modalStatusBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 font-mono">
                        Paid
                    </span>
                </div>
                <h3 id="modalHotelName" class="text-xl font-black text-white tracking-tight mt-2">Hotel Property</h3>
                <p id="modalHotelLocation" class="text-xs text-white/70 font-medium mt-0.5">Location</p>
            </div>

            <!-- Modal Body: Breakdown Specs -->
            <div class="p-6 space-y-4 text-xs">
                
                <!-- Financial Highlight Box -->
                <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Amount Settled</span>
                        <div id="modalAmount" class="text-2xl font-black font-mono text-[#150A42] tracking-tight mt-0.5">₹0.00</div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Gateway</span>
                        <div id="modalPaymentMethod" class="text-xs font-black text-slate-800 uppercase font-mono mt-0.5">Online Gateway</div>
                    </div>
                </div>

                <!-- Itemized Spec Details Grid -->
                <div class="grid grid-cols-2 gap-3 text-slate-700">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Package Tier</span>
                        <span id="modalPlanName" class="font-black text-slate-900 text-xs mt-0.5 block truncate">Deluxe Plan</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Billing Cycle</span>
                        <span id="modalBillingCycle" class="font-black text-slate-900 text-xs mt-0.5 block">Monthly Plan</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Licensed TV Displays</span>
                        <span id="modalTvCount" class="font-black font-mono text-purple-700 text-xs mt-0.5 block">10 Screens</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Date & Time</span>
                        <span id="modalDate" class="font-bold text-slate-800 text-xs mt-0.5 block">Date</span>
                    </div>
                </div>

                <!-- License Key Box -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Issued Device License Key</span>
                        <span id="modalLicenseKey" class="font-mono font-black text-slate-900 text-xs tracking-wider select-all block mt-0.5">PAX-XXXX-XXXX</span>
                    </div>
                    <button type="button" 
                            id="modalCopyBtn"
                            onclick="copyModalLicenseKey()" 
                            class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] text-xs font-bold transition-all shadow-2xs flex items-center space-x-1 cursor-pointer">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Copy</span>
                    </button>
                </div>

                <!-- Notes / Remarks -->
                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Transaction Notes</span>
                    <p id="modalNotes" class="text-xs text-slate-600 font-medium mt-0.5">—</p>
                </div>
            </div>

            <!-- Modal Footer: Action Buttons -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <button type="button" onclick="closeSaleDetailModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer">
                    Close
                </button>
                
                <div class="w-full sm:w-auto flex items-center space-x-2">
                    <a id="modalInvoiceBtn" 
                       href="#" 
                       class="flex-1 sm:flex-initial px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-file-invoice-dollar text-xs text-amber-300"></i>
                        <span>Open Official Tax Invoice</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentModalLicense = '';

    window.openSaleDetailModal = function(data) {
        if (!data) return;

        document.getElementById('modalHotelName').textContent = data.hotel_name || 'Hotel Property';
        document.getElementById('modalHotelLocation').textContent = (data.location || '') + (data.owner ? ' • Attn: ' + data.owner : '');
        document.getElementById('modalAmount').textContent = data.amount || '₹0.00';
        document.getElementById('modalPaymentMethod').textContent = data.payment_method || 'Online Gateway';
        document.getElementById('modalPlanName').textContent = data.plan_name || 'Licensing Tier';
        document.getElementById('modalBillingCycle').textContent = data.billing_cycle || 'Monthly';
        document.getElementById('modalTvCount').textContent = (data.tv_count || '10') + ' Screens';
        document.getElementById('modalDate').textContent = data.date || '—';
        document.getElementById('modalLicenseKey').textContent = data.license_key || 'N/A';
        document.getElementById('modalNotes').textContent = data.notes || 'Standard subscription package';

        currentModalLicense = data.license_key || '';

        const invoiceBtn = document.getElementById('modalInvoiceBtn');
        if (invoiceBtn && data.invoice_url) {
            invoiceBtn.href = data.invoice_url;
            invoiceBtn.classList.remove('hidden');
        }

        const modal = document.getElementById('saleDetailModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeSaleDetailModal = function() {
        const modal = document.getElementById('saleDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    window.copyModalLicenseKey = function() {
        if (!currentModalLicense || currentModalLicense === 'N/A') return;
        copyToClipboard(currentModalLicense, document.getElementById('modalCopyBtn'));
    };

    window.copyToClipboard = function(text, btnElem) {
        if (!text || text === 'N/A') return;

        navigator.clipboard.writeText(text).then(() => {
            if (btnElem) {
                const originalHtml = btnElem.innerHTML;
                btnElem.innerHTML = '<i class="fa-solid fa-check text-emerald-600 text-[11px]"></i> <span class="text-emerald-700">Copied!</span>';
                setTimeout(() => {
                    btnElem.innerHTML = originalHtml;
                }, 2000);
            }
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: 'success',
                    title: 'License key copied to clipboard'
                });
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSaleDetailModal();
        }
    });
</script>
@endsection
