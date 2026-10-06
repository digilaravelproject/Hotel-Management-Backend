@extends('layouts.distributor')

@section('title', 'Dashboard - Distributor Hub')
@section('page_title', 'Distributor Control Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner & Quick Action Buttons -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/60 rounded-3xl p-6 sm:p-8 text-white border border-slate-700 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs border border-amber-500/30">
                <i class="fa-solid fa-sparkles mr-1.5"></i> Distributor Business Workspace
            </span>
            <h2 class="text-2xl font-extrabold tracking-tight">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-xs text-slate-300 max-w-xl">Register partner hotels under your distributor account, sell licensing packages, and manage your hotel portfolio in real-time.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-hotel text-xs"></i>
                <span>+ Onboard Hotel</span>
            </a>
            <a href="{{ route('distributor.sales.create') }}" class="px-5 py-3 rounded-2xl bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs shadow-md transition-all flex items-center space-x-2">
                <i class="fa-solid fa-box-open text-xs text-amber-600"></i>
                <span>Sell Package</span>
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric 1: Total Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hotels Onboarded</p>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ $totalHotels }}</div>
                <div class="text-xs text-emerald-600 font-bold mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $activeHotels }} Active Currently
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>

        <!-- Metric 2: Packages Sold -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Packages Sold</p>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ $totalSalesCount }}</div>
                <div class="text-xs text-slate-400 font-medium mt-1">Completed transactions</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Metric 3: Total Sales Volume -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sales Volume</p>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalRevenue, 0) }}</div>
                <div class="text-xs text-emerald-600 font-bold mt-1">Cumulative sales</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <!-- Metric 4: Distributor Status -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Account Standing</p>
                <div class="text-base font-extrabold text-emerald-600 mt-2 flex items-center space-x-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Fully Verified</span>
                </div>
                <div class="text-xs text-slate-400 font-mono mt-1">Distributor #{{ auth()->id() }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-id-badge"></i>
            </div>
        </div>
    </div>

    <!-- Recent Hotels & Sales Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Hotels -->
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Recent Hotels Onboarded</h3>
                    <p class="text-xs text-slate-400">Hotels under your distributor franchise</p>
                </div>
                <a href="{{ route('distributor.hotels.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">View All</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentHotels as $hotel)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-700 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-xs">{{ $hotel->hotel_name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $hotel->hotel_location }} • {{ $hotel->room_count }} Rooms</p>
                                <span class="font-mono text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Key: {{ $hotel->license_key }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            @if($hotel->plan)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    {{ $hotel->plan->name }}
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                    No Plan
                                </span>
                            @endif
                            <div class="mt-1">
                                <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="text-[11px] font-bold text-amber-600 hover:underline">
                                    + Sell Package
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        No hotels onboarded yet. <a href="{{ route('distributor.hotels.create') }}" class="text-amber-600 font-bold underline">Onboard your first hotel</a>.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Sales Ledger -->
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Recent Package Sales</h3>
                    <p class="text-xs text-slate-400">Transactions processed by your account</p>
                </div>
                <a href="{{ route('distributor.sales.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">View All</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentSales as $sale)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-xs">{{ $sale->hotel->hotel_name ?? 'Hotel Client' }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $sale->plan->name ?? 'Package' }} • {{ $sale->payment_method }}</p>
                                <span class="text-[10px] text-slate-400">{{ $sale->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm font-extrabold text-slate-900">₹{{ number_format($sale->amount, 0) }}</div>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Completed
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        No packages sold yet. <a href="{{ route('distributor.sales.create') }}" class="text-amber-600 font-bold underline">Sell your first package</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
