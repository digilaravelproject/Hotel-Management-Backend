@extends('layouts.distributor')

@section('title', 'Dashboard - Distributor Hub')
@section('page_title', 'Distributor Control Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner & Quick Action Buttons (colors.md: Pure White #FFFFFF Card, Warm Amber Gold #E5A93C CTA) -->
    <div class="bg-[#FFFFFF] rounded-3xl p-6 sm:p-8 text-[#1E293B] border border-[#E2E8F0] shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF9EE] text-[#E5A93C] font-bold text-xs border border-[#E5A93C]/40">
                <i class="fa-solid fa-sparkles mr-1.5"></i> Distributor Business Workspace
            </span>
            <h2 class="text-2xl font-extrabold tracking-tight text-[#1E293B]">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-xs text-[#64748B] max-w-xl">Register partner hotels under your distributor account, sell licensing packages, and manage your hotel portfolio in real-time.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Primary CTA Button (colors.md: Warm Amber Gold #E5A93C, Hover #C88E26) -->
            <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-3 rounded-2xl bg-[#E5A93C] hover:bg-[#C88E26] text-[#FFFFFF] font-extrabold text-xs shadow-md shadow-[#E5A93C]/25 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-hotel text-xs"></i>
                <span>+ Onboard Hotel</span>
            </a>
            <!-- Secondary Button (colors.md: White #FFFFFF, Border #CBD5E1, Text #1E293B) -->
            <a href="{{ route('distributor.sales.create') }}" class="px-5 py-3 rounded-2xl bg-[#FFFFFF] hover:bg-[#F8FAFC] text-[#1E293B] border border-[#CBD5E1] font-bold text-xs transition-all flex items-center space-x-2">
                <i class="fa-solid fa-box-open text-xs text-[#E5A93C]"></i>
                <span>Sell Package</span>
            </a>
        </div>
    </div>

    <!-- Metrics Grid (colors.md: Pure White Cards, Dark Slate Numbers, Cream Gold #FEF9EE Icon Box with Gold #E5A93C) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric 1: Total Hotels -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#64748B] uppercase tracking-wider">Hotels Onboarded</p>
                <div class="text-3xl font-extrabold text-[#1E293B] mt-1">{{ $totalHotels }}</div>
                <div class="text-xs text-[#16A34A] font-bold mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $activeHotels }} Active Currently
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/35 text-[#E5A93C] flex items-center justify-center text-lg">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>

        <!-- Metric 2: Packages Sold -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#64748B] uppercase tracking-wider">Packages Sold</p>
                <div class="text-3xl font-extrabold text-[#1E293B] mt-1">{{ $totalSalesCount }}</div>
                <div class="text-xs text-[#64748B] font-medium mt-1">Completed transactions</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/35 text-[#E5A93C] flex items-center justify-center text-lg">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Metric 3: Total Sales Volume -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#64748B] uppercase tracking-wider">Total Sales Volume</p>
                <div class="text-3xl font-extrabold text-[#1E293B] mt-1">₹{{ number_format($totalRevenue, 0) }}</div>
                <div class="text-xs text-[#16A34A] font-bold mt-1">Cumulative sales</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/35 text-[#E5A93C] flex items-center justify-center text-lg">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <!-- Metric 4: Wallet Balance -->
        @php
            $dashWallet = auth()->user()->wallet;
            $dashBalance = $dashWallet ? $dashWallet->available_balance : 0.00;
        @endphp
        <a href="{{ route('distributor.wallet.index') }}" class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl p-6 shadow-xs flex items-center justify-between hover:border-[#E5A93C]/60 transition-all group">
            <div>
                <p class="text-[11px] font-bold text-[#64748B] uppercase tracking-wider">Wallet Balance</p>
                <div class="text-3xl font-extrabold text-[#E5A93C] mt-1">₹{{ number_format($dashBalance, 2) }}</div>
                <div class="text-xs text-[#C88E26] font-bold mt-1 group-hover:underline">
                    Rate: {{ auth()->user()->effective_commission_percentage }}% • Payout <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/35 text-[#E5A93C] flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </a>
    </div>

    <!-- Recent Hotels & Sales Grids (colors.md: Pure White Cards, Soft Gray Borders) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Hotels -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl overflow-hidden shadow-xs">
            <div class="p-6 border-b border-[#E2E8F0] flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-[#1E293B] text-base">Recent Hotels Onboarded</h3>
                    <p class="text-xs text-[#64748B]">Hotels under your distributor franchise</p>
                </div>
                <a href="{{ route('distributor.hotels.index') }}" class="text-xs font-bold text-[#E5A93C] hover:text-[#C88E26]">View All</a>
            </div>

            <div class="divide-y divide-[#E2E8F0]">
                @forelse($recentHotels as $hotel)
                    <div class="p-5 flex items-center justify-between hover:bg-[#F8FAFC] transition-colors">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-[#FEF9EE] border border-[#E5A93C]/35 text-[#E5A93C] flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-[#1E293B] text-xs">{{ $hotel->hotel_name }}</h4>
                                <p class="text-[11px] text-[#64748B]">{{ $hotel->hotel_location }} • {{ $hotel->room_count }} Rooms</p>
                                <span class="font-mono text-[10px] text-[#64748B] bg-[#F8FAFC] border border-[#E2E8F0] px-1.5 py-0.5 rounded">Key: {{ $hotel->license_key }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            @if($hotel->plan)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF9EE] text-[#C88E26] border border-[#E5A93C]/35">
                                    {{ $hotel->plan->name }}
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0]">
                                    No Plan
                                </span>
                            @endif
                            <div class="mt-1">
                                <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="text-[11px] font-bold text-[#E5A93C] hover:text-[#C88E26]">
                                    + Sell Package
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[#64748B] text-xs">
                        No hotels onboarded yet. <a href="{{ route('distributor.hotels.create') }}" class="text-[#E5A93C] font-bold hover:underline">Onboard your first hotel</a>.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Sales Ledger -->
        <div class="bg-[#FFFFFF] border border-[#E2E8F0] rounded-3xl overflow-hidden shadow-xs">
            <div class="p-6 border-b border-[#E2E8F0] flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-[#1E293B] text-base">Recent Package Sales</h3>
                    <p class="text-xs text-[#64748B]">Transactions processed by your account</p>
                </div>
                <a href="{{ route('distributor.sales.index') }}" class="text-xs font-bold text-[#E5A93C] hover:text-[#C88E26]">View All</a>
            </div>

            <div class="divide-y divide-[#E2E8F0]">
                @forelse($recentSales as $sale)
                    <div class="p-5 flex items-center justify-between hover:bg-[#F8FAFC] transition-colors">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-[#DCFCE7] border border-[#16A34A]/30 text-[#16A34A] flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-[#1E293B] text-xs">{{ $sale->hotel->hotel_name ?? 'Hotel Client' }}</h4>
                                <p class="text-[11px] text-[#64748B]">{{ $sale->plan->name ?? 'Package' }} • {{ $sale->payment_method }}</p>
                                <span class="text-[10px] text-[#64748B]">{{ $sale->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm font-extrabold text-[#1E293B]">₹{{ number_format($sale->amount, 0) }}</div>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/30">
                                Completed
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[#64748B] text-xs">
                        No packages sold yet. <a href="{{ route('distributor.sales.create') }}" class="text-[#E5A93C] font-bold hover:underline">Sell your first package</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
