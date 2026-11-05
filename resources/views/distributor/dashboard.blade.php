@extends('layouts.distributor')

@section('title', 'Dashboard - Distributor Hub')
@section('page_title', 'Distributor Control Hub')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner & Quick Action Buttons (Signature #150A42 Design System) -->
    <div class="bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-purple-50/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-sparkles text-[10px] text-purple-600"></i>
                <span>Authorized Distributor Portal</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                Welcome back, {{ auth()->user()->name }}!
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
                Register hotel properties, activate smart TV licenses, and manage your recurring portfolio revenue in real-time.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 shrink-0">
            <!-- Primary Action: Onboard Hotel -->
            <a href="{{ route('distributor.hotels.create') }}" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-hotel text-xs"></i>
                <span>+ Onboard Hotel</span>
            </a>
            <!-- Secondary Action: Sell Package -->
            <a href="{{ route('distributor.sales.create') }}" class="px-5 py-3 rounded-2xl bg-purple-50 hover:bg-purple-100/90 text-[#150A42] border border-purple-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-box-open text-xs text-purple-600"></i>
                <span>Sell Package</span>
            </a>
        </div>
    </div>

    <!-- 4 Metrics Grid (Super Admin Signature Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric 1: Total Hotels -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Hotels Onboarded</p>
                    <div class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalHotels }}</div>
                    <div class="text-xs text-emerald-600 font-bold mt-1.5 flex items-center space-x-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        <span>{{ $activeHotels }} Active Currently</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-hotel"></i>
                </div>
            </div>
        </div>

        <!-- Metric 2: Packages Sold -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Packages Sold</p>
                    <div class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalSalesCount }}</div>
                    <div class="text-xs text-slate-400 font-medium mt-1.5">Completed transactions</div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>

        <!-- Metric 3: Total Sales Volume -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Total Sales Volume</p>
                    <div class="text-3xl font-black text-slate-900 mt-1 tracking-tight">₹{{ number_format($totalRevenue, 0) }}</div>
                    <div class="text-xs text-emerald-600 font-bold mt-1.5 flex items-center space-x-1">
                        <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>
                        <span>Cumulative client sales</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
        </div>

        <!-- Metric 4: Wallet Balance -->
        @php
            $dashWallet = auth()->user()->wallet;
            $dashBalance = $dashWallet ? $dashWallet->available_balance : 0.00;
        @endphp
        <a href="{{ route('distributor.wallet.index') }}" class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md hover:border-purple-200 transition-all relative overflow-hidden group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Wallet Balance</p>
                    <div class="text-3xl font-black text-purple-700 mt-1 tracking-tight">₹{{ number_format($dashBalance, 2) }}</div>
                    <div class="text-xs text-purple-600 font-bold mt-1.5 flex items-center space-x-1 group-hover:text-purple-800">
                        <span>Rate: {{ auth()->user()->effective_commission_percentage }}% • Payout</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- ================= ANALYTICS ROW: 2 GRAPHS + PROGRESS METRICS ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Graph 1: Subscription Tier Adoption (Doughnut Chart) -->
        <div class="lg:col-span-5 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="text-base font-black text-slate-900 tracking-tight">Subscription Tier Adoption</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Distribution by package tier</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-purple-50 text-xs font-bold text-purple-700 border border-purple-100">All Tiers</span>
                </div>

                <!-- Doughnut Chart Canvas with Center Sum Metric -->
                <div class="relative h-56 w-full flex items-center justify-center my-2">
                    <canvas id="planDoughnutChart"></canvas>
                    
                    <!-- Center Overlay Metric -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black text-slate-900 font-mono">
                            {{ $planDistribution['total'] ?? array_sum($planDistribution['data'] ?? []) }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">
                            Hotels
                        </span>
                    </div>
                </div>
            </div>

            <!-- Legend Pills underneath -->
            <div class="grid grid-cols-2 gap-2.5 pt-4 border-t border-slate-100 text-xs">
                @php
                    $planColors = ['#06B6D4', '#10B981', '#F59E0B', '#150A42'];
                    $labels = $planDistribution['labels'] ?? [];
                    $data = $planDistribution['data'] ?? [];
                @endphp
                @forelse($labels as $idx => $label)
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $planColors[$idx % 4] }};"></span>
                        <span class="text-slate-600 font-semibold truncate">{{ $label }}:</span>
                        <span class="font-black text-slate-900 font-mono">{{ $data[$idx] ?? 0 }}</span>
                    </div>
                @empty
                    <div class="col-span-2 text-center text-slate-400 text-xs">No active plan data</div>
                @endforelse
            </div>
        </div>

        <!-- Graph 2: Regional TV & Room Capacity Bar Chart + Dynamic City Filter -->
        <div class="lg:col-span-7 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                    <div>
                        <h4 class="text-base font-black text-slate-900 tracking-tight">Regional TV & Room Capacity</h4>
                        <p id="cityChartSubtitle" class="text-[11px] text-slate-400 font-medium">Licensed rooms deployed by top metropolitan city</p>
                    </div>
                    <!-- Interactive City Filter Dropdown -->
                    <div class="relative shrink-0">
                        <select id="cityChartFilter" onchange="onCityFilterChange(this.value)" 
                                class="text-xs font-bold text-[#150A42] bg-purple-50 hover:bg-purple-100 border border-purple-200/90 rounded-full pl-3.5 pr-8 py-1.5 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#150A42]/20 transition-all font-mono shadow-xs">
                            <option value="all">TOP CITIES (All)</option>
                            @foreach($cityDistribution['citiesList'] ?? [] as $cityName)
                                <option value="{{ $cityName }}">{{ $cityName }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down text-[9px] text-[#150A42] absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Bar Chart Canvas -->
                <div class="relative h-56 w-full mt-2">
                    <canvas id="cityBarChart"></canvas>
                </div>
            </div>

            <!-- 4 Resource & TV Utilization Progress Bars -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-slate-600">TV Active</span>
                        <span id="statTvActive" class="font-extrabold text-cyan-600 font-mono">{{ $metrics['tv_coverage_pct'] ?? 66 }}%</span>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div id="barTvActive" class="h-full rounded-full bg-[#06B6D4] transition-all duration-300" style="width: {{ $metrics['tv_coverage_pct'] ?? 66 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-slate-600">Verified</span>
                        <span id="statVerified" class="font-extrabold text-[#150A42] font-mono">{{ $metrics['verified_hotels_pct'] ?? 31 }}%</span>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div id="barVerified" class="h-full rounded-full bg-[#150A42] transition-all duration-300" style="width: {{ $metrics['verified_hotels_pct'] ?? 31 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-slate-600">Paid Plans</span>
                        <span id="statPaidPlans" class="font-extrabold text-emerald-600 font-mono">{{ $metrics['paid_hotels_pct'] ?? 18 }}%</span>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div id="barPaidPlans" class="h-full rounded-full bg-[#10B981] transition-all duration-300" style="width: {{ $metrics['paid_hotels_pct'] ?? 18 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-slate-600">Channel Cap</span>
                        <span class="font-extrabold text-amber-600 font-mono">85%</span>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-[#F59E0B]" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hotels & Sales Grids (Modern Rounded [28px] Containers) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Hotels -->
        <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Recent Hotels Onboarded</h3>
                        <p class="text-xs text-slate-400 font-medium">Hotels active under your distributor account</p>
                    </div>
                    <a href="{{ route('distributor.hotels.index') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 flex items-center space-x-1">
                        <span>View All</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentHotels as $hotel)
                        <div class="p-5 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-hotel"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-900 text-sm truncate">{{ $hotel->hotel_name }}</h4>
                                    <p class="text-[11px] text-slate-400 font-semibold truncate">
                                        {{ $hotel->hotel_location }} • {{ $hotel->room_count }} Rooms
                                    </p>
                                    <span class="inline-block font-mono text-[10px] text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-lg mt-0.5 font-bold">
                                        Key: {{ $hotel->license_key }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-right shrink-0 pl-3">
                                @if($hotel->plan)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                                        {{ $hotel->plan->name }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        No Plan
                                    </span>
                                @endif
                                <div class="mt-1.5">
                                    <a href="{{ route('distributor.sales.create', ['hotel_id' => $hotel->id]) }}" class="text-[11px] font-black text-purple-700 hover:text-purple-900 flex items-center justify-end space-x-1">
                                        <i class="fa-solid fa-plus text-[9px]"></i>
                                        <span>Sell Package</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 text-xs">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200/60 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">No hotels onboarded yet</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by registering your first partner hotel.</p>
                            <div class="mt-4">
                                <a href="{{ route('distributor.hotels.create') }}" class="px-4 py-2 rounded-xl bg-[#150A42] text-white font-bold text-xs inline-flex items-center space-x-1.5 shadow-sm">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>Onboard Hotel</span>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('distributor.hotels.index') }}" class="text-xs font-black text-purple-700 hover:text-purple-900">
                    View all partner hotels <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Recent Sales Ledger -->
        <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Recent Package Sales</h3>
                        <p class="text-xs text-slate-400 font-medium">Transactions processed by your account</p>
                    </div>
                    <a href="{{ route('distributor.sales.index') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 flex items-center space-x-1">
                        <span>View All</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentSales as $sale)
                        <div class="p-5 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-900 text-sm truncate">{{ $sale->hotel->hotel_name ?? 'Hotel Client' }}</h4>
                                    <p class="text-[11px] text-slate-400 font-semibold truncate">
                                        {{ $sale->plan->name ?? 'Package' }} • <span class="capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</span>
                                    </p>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $sale->created_at->format('M d, Y • h:i A') }}</span>
                                </div>
                            </div>

                            <div class="text-right shrink-0 pl-3">
                                <div class="text-sm font-black text-slate-900">₹{{ number_format($sale->amount, 0) }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mt-1 shadow-2xs">
                                    <i class="fa-solid fa-circle-check text-[9px] mr-1 text-emerald-600"></i> Completed
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 text-xs">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200/60 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">No packages sold yet</p>
                            <p class="text-xs text-slate-400 mt-1">Sell licensing packages to earn commissions.</p>
                            <div class="mt-4">
                                <a href="{{ route('distributor.sales.create') }}" class="px-4 py-2 rounded-xl bg-[#150A42] text-white font-bold text-xs inline-flex items-center space-x-1.5 shadow-sm">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>Sell Package</span>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('distributor.sales.index') }}" class="text-xs font-black text-purple-700 hover:text-purple-900">
                    View complete sales ledger <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ================= 1. Subscription Tier Doughnut Chart =================
    const ctxPlan = document.getElementById('planDoughnutChart');
    if (ctxPlan) {
        const planLabels = @json($planDistribution['labels'] ?? []);
        const planData = @json($planDistribution['data'] ?? []);

        new Chart(ctxPlan, {
            type: 'doughnut',
            data: {
                labels: planLabels,
                datasets: [{
                    data: planData.length ? planData : [1],
                    backgroundColor: [
                        '#06B6D4', // Economy / Cyan
                        '#10B981', // Deluxe / Emerald
                        '#F59E0B', // Executive / Amber
                        '#150A42', // Presidential / Midnight Navy
                    ],
                    borderWidth: 4,
                    borderColor: '#FFFFFF',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#FFFFFF',
                        titleColor: '#0F172A',
                        bodyColor: '#334155',
                        borderColor: 'rgba(226, 232, 240, 0.9)',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 11 }
                    }
                }
            }
        });
    }

    // ================= 2. Regional Hubs Bar Chart with Dynamic City Filter =================
    const ctxCity = document.getElementById('cityBarChart');
    if (ctxCity) {
        const allCitiesLabels = @json($cityDistribution['labels'] ?? []);
        const allCitiesRoomCounts = @json($cityDistribution['roomCounts'] ?? []);
        const cityBreakdowns = @json($cityDistribution['byCity'] ?? []);
        const globalMetrics = {
            tv_coverage: {{ $metrics['tv_coverage_pct'] ?? 66 }},
            verified: {{ $metrics['verified_hotels_pct'] ?? 31 }},
            paid: {{ $metrics['paid_hotels_pct'] ?? 18 }}
        };

        const defaultPalette = [
            '#150A42', '#2E1065', '#06B6D4', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#3B82F6'
        ];

        const cityBarChartInstance = new Chart(ctxCity, {
            type: 'bar',
            data: {
                labels: allCitiesLabels.length ? allCitiesLabels : ['Mumbai', 'New Delhi', 'Bengaluru'],
                datasets: [{
                    label: 'Licensed Room Capacity',
                    data: allCitiesRoomCounts.length ? allCitiesRoomCounts : [40, 250, 100],
                    backgroundColor: defaultPalette,
                    borderRadius: 10,
                    barPercentage: 0.55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#FFFFFF',
                        titleColor: '#0F172A',
                        bodyColor: '#334155',
                        borderColor: 'rgba(226, 232, 240, 0.9)',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                        callbacks: {
                            label: function(context) {
                                return ' ' + (context.dataset.label || 'Capacity') + ': ' + context.parsed.y + ' rooms';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            color: '#64748B'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(241, 245, 249, 1)',
                            borderDash: [4, 4]
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            color: '#94A3B8'
                        }
                    }
                }
            }
        });

        window.onCityFilterChange = function(selectedCity) {
            if (!cityBarChartInstance) return;

            const subtitleEl = document.getElementById('cityChartSubtitle');
            const statTv = document.getElementById('statTvActive');
            const barTv = document.getElementById('barTvActive');
            const statVer = document.getElementById('statVerified');
            const barVer = document.getElementById('barVerified');
            const statPaid = document.getElementById('statPaidPlans');
            const barPaid = document.getElementById('barPaidPlans');

            if (selectedCity === 'all' || !cityBreakdowns[selectedCity]) {
                // Restore to All Cities
                cityBarChartInstance.data.labels = allCitiesLabels.length ? allCitiesLabels : ['Mumbai', 'New Delhi', 'Bengaluru'];
                cityBarChartInstance.data.datasets[0].label = 'Licensed Room Capacity';
                cityBarChartInstance.data.datasets[0].data = allCitiesRoomCounts.length ? allCitiesRoomCounts : [40, 250, 100];
                cityBarChartInstance.data.datasets[0].backgroundColor = defaultPalette;

                if (subtitleEl) {
                    subtitleEl.textContent = 'Licensed rooms deployed by top metropolitan city';
                }

                // Restore global bottom bars
                if (statTv) statTv.textContent = globalMetrics.tv_coverage + '%';
                if (barTv) barTv.style.width = globalMetrics.tv_coverage + '%';
                if (statVer) statVer.textContent = globalMetrics.verified + '%';
                if (barVer) barVer.style.width = globalMetrics.verified + '%';
                if (statPaid) statPaid.textContent = globalMetrics.paid + '%';
                if (barPaid) barPaid.style.width = globalMetrics.paid + '%';
            } else {
                // Switch to specific city hotels breakdown
                const cityData = cityBreakdowns[selectedCity];
                const hotelLabels = cityData.labels && cityData.labels.length ? cityData.labels : [selectedCity];
                const hotelRooms = cityData.roomCounts && cityData.roomCounts.length ? cityData.roomCounts : [cityData.total_rooms];

                cityBarChartInstance.data.labels = hotelLabels;
                cityBarChartInstance.data.datasets[0].label = 'Rooms in ' + selectedCity;
                cityBarChartInstance.data.datasets[0].data = hotelRooms;
                cityBarChartInstance.data.datasets[0].backgroundColor = defaultPalette.slice(0, Math.max(hotelLabels.length, 1));

                if (subtitleEl) {
                    subtitleEl.textContent = 'Hotels in ' + selectedCity + ' (' + (cityData.total_rooms || 0) + ' total rooms across ' + (cityData.hotels ? cityData.hotels.length : 1) + ' hotel' + ((cityData.hotels && cityData.hotels.length > 1) ? 's' : '') + ')';
                }

                // Update bottom metrics for this specific city
                if (statTv) statTv.textContent = (cityData.tv_coverage_pct || 0) + '%';
                if (barTv) barTv.style.width = (cityData.tv_coverage_pct || 0) + '%';
                if (statVer) statVer.textContent = (cityData.verified_hotels_pct || 0) + '%';
                if (barVer) barVer.style.width = (cityData.verified_hotels_pct || 0) + '%';
                if (statPaid) statPaid.textContent = (cityData.paid_hotels_pct || 0) + '%';
                if (barPaid) barPaid.style.width = (cityData.paid_hotels_pct || 0) + '%';
            }

            cityBarChartInstance.update();
        };
    }
});
</script>
@endsection
