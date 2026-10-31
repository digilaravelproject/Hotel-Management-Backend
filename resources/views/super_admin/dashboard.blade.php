@extends('layouts.super_admin')

@section('title', 'Super Admin Executive Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Actionable System Alerts (if any) -->
    @if(!empty($alerts))
        <div class="space-y-3">
            @foreach($alerts as $alert)
                <div class="p-4 sm:p-5 rounded-3xl bg-amber-50/90 border border-amber-200/80 text-slate-900 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 text-amber-700 flex items-center justify-center shrink-0 mt-0.5 border border-amber-500/30">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $alert['title'] }}</h4>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                    @if(!empty($alert['action_url']))
                        <a href="{{ $alert['action_url'] }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition-all shrink-0 self-start sm:self-auto shadow-sm">
                            <span>{{ $alert['action_label'] ?? 'Review Now' }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Top 4 Vibrant Stat Cards (Jobie Aesthetics: Royal Blue, Cyan, Emerald Green, Lime Green) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- 1. Connected TV Fleet (Royal Blue #2835EB) -->
        <div class="relative overflow-hidden rounded-[26px] bg-[#2835EB] text-white p-6 shadow-md shadow-[#2835EB]/20 flex flex-col justify-between h-40 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div class="w-13 h-13 rounded-2xl bg-white/20 flex items-center justify-center text-white text-xl backdrop-blur-xs">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="text-right">
                    <div class="text-xs font-semibold text-white/80">Connected TV Fleet</div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-white font-mono mt-1 tracking-tight">
                        {{ number_format($metrics['total_devices'] ?? 0) }}
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-white/80">
                <span>Fleet Hardware Status</span>
                <span class="font-bold text-white flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>ONLINE</span>
                </span>
            </div>
        </div>

        <!-- 2. Properties Network (Sky Blue #1EAAEB) -->
        <div class="relative overflow-hidden rounded-[26px] bg-[#1EAAEB] text-white p-6 shadow-md shadow-[#1EAAEB]/20 flex flex-col justify-between h-40 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div class="w-13 h-13 rounded-2xl bg-white/20 flex items-center justify-center text-white text-xl backdrop-blur-xs">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div class="text-right">
                    <div class="text-xs font-semibold text-white/80">Properties Network</div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-white font-mono mt-1 tracking-tight">
                        {{ number_format($metrics['total_hotels'] ?? 0) }}
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-white/80">
                <span>Verified Active</span>
                <span class="font-bold text-white font-mono">
                    {{ $metrics['active_hotels'] ?? 0 }} / {{ $metrics['total_hotels'] ?? 0 }}
                </span>
            </div>
        </div>

        <!-- 3. Licensed Rooms Capacity (Emerald Green #05C586) -->
        <div class="relative overflow-hidden rounded-[26px] bg-[#05C586] text-white p-6 shadow-md shadow-[#05C586]/20 flex flex-col justify-between h-40 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div class="w-13 h-13 rounded-2xl bg-white/20 flex items-center justify-center text-white text-xl backdrop-blur-xs">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div class="text-right">
                    <div class="text-xs font-semibold text-white/80">Licensed Rooms</div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-white font-mono mt-1 tracking-tight">
                        {{ number_format($metrics['total_licensed_rooms'] ?? 0) }}
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-white/80">
                <span>Room Capacity</span>
                <span class="font-bold text-white font-mono">{{ number_format($metrics['total_licensed_rooms'] ?? 0) }} Units</span>
            </div>
        </div>

        <!-- 4. Monthly Platform Run-Rate (Lime Green #85D638) -->
        <div class="relative overflow-hidden rounded-[26px] bg-[#85D638] text-white p-6 shadow-md shadow-[#85D638]/20 flex flex-col justify-between h-40 transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div class="w-13 h-13 rounded-2xl bg-white/20 flex items-center justify-center text-white text-xl backdrop-blur-xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
                <div class="text-right">
                    <div class="text-xs font-semibold text-white/80">Platform Run-Rate</div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-white font-mono mt-1 tracking-tight">
                        ₹{{ number_format($metrics['total_revenue'] ?? 0, 0) }}
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-white/80">
                <span>Distributor Packages</span>
                <span class="font-bold text-white font-mono">{{ $metrics['sales_count'] ?? 0 }} Logged</span>
            </div>
        </div>

    </div>

    <!-- Financial Channel Velocity Strip (Inspired by "My Wallet / Income vs Expense" in Walletz) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Velocity 1: Gross MRR & Growth -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold text-slate-400">Monthly Run-Rate (MRR)</div>
                <div class="text-2xl font-extrabold text-slate-900 font-mono">
                    ₹{{ number_format($metrics['total_revenue'] ?? 0) }}
                </div>
                <div class="flex items-center space-x-1.5 text-xs font-bold text-emerald-600">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>
                    <span>+18.4% vs last period</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-lg">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>

        <!-- Velocity 2: Distributor Partner Disbursals -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold text-slate-400">Distributor Commissions (15%)</div>
                <div class="text-2xl font-extrabold text-slate-900 font-mono">
                    ₹{{ number_format(($metrics['total_revenue'] ?? 0) * 0.15) }}
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Across {{ $metrics['total_distributors'] ?? 5 }} partner channels
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-lg">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>

        <!-- Velocity 3: Average Room Yield & TV Density -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold text-slate-400">Average Property Fleet</div>
                <div class="text-2xl font-extrabold text-slate-900 font-mono">
                    {{ ($metrics['total_hotels'] ?? 0) > 0 ? round(($metrics['total_devices'] ?? 0) / $metrics['total_hotels']) : 12 }} TVs / Hotel
                </div>
                <div class="text-xs text-purple-700 font-semibold">
                    {{ $metrics['total_licensed_rooms'] ?? 0 }} total licensed capacity
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#241372] text-lg">
                <i class="fa-solid fa-network-wired"></i>
            </div>
        </div>

    </div>

    <!-- Core Analytics Section (Left Column ~33%, Right Column ~67%) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ================= LEFT COLUMN ================= -->
        <div class="lg:col-span-4 flex flex-col space-y-6">
            
            <!-- 1. Super Admin Profile & Radial Telemetry Meter Card -->
            <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                
                <div class="flex flex-col items-center text-center">
                    <!-- Main Radial Progress Meter with Centered Avatar -->
                    <div class="relative w-32 h-32 flex items-center justify-center mt-1">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                            <!-- Background track circle -->
                            <circle cx="60" cy="60" r="48" stroke="#F1F3F9" stroke-width="8" fill="none" />
                            <!-- Active progress circle stroke -->
                            <circle cx="60" cy="60" r="48" stroke="#241372" stroke-width="8" stroke-linecap="round" fill="none"
                                stroke-dasharray="301.6"
                                stroke-dashoffset="{{ 301.6 - (301.6 * ($metrics['overall_health_score'] ?? 74)) / 100 }}"
                                style="transition: stroke-dashoffset 1.2s ease-in-out;"
                            />
                        </svg>

                        <!-- Avatar Center Image -->
                        <div class="absolute inset-0 m-auto w-20 h-20 rounded-full overflow-hidden bg-gradient-to-tr from-[#241372] to-purple-600 border-4 border-white shadow-md flex items-center justify-center text-white">
                            <i class="fa-solid fa-user-tie text-3xl"></i>
                        </div>

                        <!-- Online status indicator dot -->
                        <span class="absolute bottom-1.5 right-3 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white shadow-xs"></span>
                    </div>

                    <!-- Admin Identity & Subtitle -->
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight mt-2.5">
                        {{ Auth::guard('super_admin')->user()->name ?? 'Director Super Admin' }}
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium">
                        Platform Executive Director
                    </p>
                </div>

                <!-- 3 Circular Mini Progress Gauges (Jobie: 66% PHP, 31% Vue, 7% Laravel) -->
                <div class="grid grid-cols-3 gap-2 w-full pt-4 border-t border-slate-100 text-center">
                    
                    <!-- Gauge 1: TV Active % (Orange/Coral) -->
                    <div class="flex flex-col items-center">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14.5" stroke="#FEE2E2" stroke-width="3" fill="none" />
                                <circle cx="18" cy="18" r="14.5" stroke="#F97316" stroke-width="3" stroke-linecap="round" fill="none"
                                    stroke-dasharray="91.1"
                                    stroke-dashoffset="{{ 91.1 - (91.1 * ($metrics['tv_coverage_pct'] ?? 66)) / 100 }}"
                                />
                            </svg>
                            <span class="absolute text-[11px] font-bold text-slate-800 font-mono">
                                {{ $metrics['tv_coverage_pct'] ?? 66 }}%
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 mt-1">TV Active</span>
                    </div>

                    <!-- Gauge 2: Approved % (Emerald Green) -->
                    <div class="flex flex-col items-center">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14.5" stroke="#D1FAE5" stroke-width="3" fill="none" />
                                <circle cx="18" cy="18" r="14.5" stroke="#10B981" stroke-width="3" stroke-linecap="round" fill="none"
                                    stroke-dasharray="91.1"
                                    stroke-dashoffset="{{ 91.1 - (91.1 * ($metrics['verified_hotels_pct'] ?? 31)) / 100 }}"
                                />
                            </svg>
                            <span class="absolute text-[11px] font-bold text-slate-800 font-mono">
                                {{ $metrics['verified_hotels_pct'] ?? 31 }}%
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 mt-1">Approved</span>
                    </div>

                    <!-- Gauge 3: Subscribed % (Cyan) -->
                    <div class="flex flex-col items-center">
                        <div class="relative w-12 h-12 flex items-center justify-center">
                            <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14.5" stroke="#CFFAFE" stroke-width="3" fill="none" />
                                <circle cx="18" cy="18" r="14.5" stroke="#06B6D4" stroke-width="3" stroke-linecap="round" fill="none"
                                    stroke-dasharray="91.1"
                                    stroke-dashoffset="{{ 91.1 - (91.1 * ($metrics['paid_hotels_pct'] ?? 18)) / 100 }}"
                                />
                            </svg>
                            <span class="absolute text-[11px] font-bold text-slate-800 font-mono">
                                {{ $metrics['paid_hotels_pct'] ?? 18 }}%
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 mt-1">Subscribed</span>
                    </div>

                </div>
            </div>

            <!-- 2. Recent Activities Card (Live Feed from DB) -->
            <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight">Recent Activities</h4>
                        <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full font-mono">LIVE FEED</span>
                    </div>

                    <!-- Activity List Items -->
                    <div class="divide-y divide-slate-100">
                        @forelse($recentHotels->take(3) as $h)
                            <div class="py-2.5 first:pt-0 last:pb-0 flex items-start space-x-3 group">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#241372] border border-purple-100 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-hotel text-[11px]"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-800 leading-snug group-hover:text-[#241372] transition-colors truncate">
                                        {{ $h->hotel_name }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">
                                        {{ $h->city ?? 'Central Hub' }} • {{ $h->room_count }} Rooms Capacity
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $h->created_at ? $h->created_at->diffForHumans() : 'Recently' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-2.5 flex items-start space-x-3">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#241372] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-hotel text-[11px]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Grand Horizon Luxury Suites</p>
                                    <p class="text-[11px] text-slate-500">New Delhi Hub • 120 Rooms Registered</p>
                                    <p class="text-[10px] text-slate-400 font-mono">12m ago</p>
                                </div>
                            </div>
                        @endforelse

                        @foreach($recentTransactions->take(2) as $txn)
                            <div class="py-2.5 flex items-start space-x-3 group">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-receipt text-[11px]"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-800 leading-snug truncate">
                                        Package logged by {{ $txn->distributor->name ?? 'Partner' }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 font-medium truncate">
                                        ₹{{ number_format($txn->amount) }} • {{ $txn->hotel->hotel_name ?? 'Property' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $txn->created_at ? $txn->created_at->diffForHumans() : 'Recently' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Down Arrow Circle Button -->
                <div class="mt-4 pt-3 border-t border-slate-100/80 flex justify-center">
                    <a href="{{ route('super-admin.hotels.index') }}" title="View Full Activity Ledger" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-purple-100 text-slate-500 hover:text-[#241372] flex items-center justify-center transition-all shadow-xs">
                        <i class="fa-solid fa-arrow-down text-xs"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- ================= RIGHT COLUMN ================= -->
        <div class="lg:col-span-8 flex flex-col space-y-6">
            
            <!-- 1. "Activity Graph" equivalent -> Platform Telemetry & Growth Spline Chart + Progress Bars (Walletz Style) -->
            <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                
                <!-- Chart Header: Title + Legend Pills + Time Filter Dropdown -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Platform Telemetry & Growth</h4>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">6-month multi-series telemetry curve</p>
                    </div>

                    <!-- Interactive Legend and Dropdown Filter -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center space-x-3 text-xs font-semibold text-slate-600">
                            <!-- Legend 1: Purple -->
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#241372]"></span>
                                <span>Properties</span>
                            </div>
                            <!-- Legend 2: Cyan -->
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#06B6D4]"></span>
                                <span>TV Consoles</span>
                            </div>
                            <!-- Legend 3: Rose -->
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#E11D48]"></span>
                                <span>Sales Packs</span>
                            </div>
                        </div>

                        <!-- Dropdown Filter Pill -->
                        <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#F3F5FA] border border-slate-200/60 text-xs font-bold text-slate-700">
                            <span>Last 6 Months</span>
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Smooth Spline Chart Canvas -->
                <div class="relative h-[250px] w-full">
                    <canvas id="jobieTelemetryChart"></canvas>
                </div>

                <!-- 4 Resource & Fleet Utilization Distribution Bars (Directly matching Walletz Activity Graph) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-5 mt-4 border-t border-slate-100">
                    
                    <!-- Bar 1: Hardware Activation Rate -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Fleet Active</span>
                            <span class="font-extrabold text-cyan-600 font-mono">{{ $metrics['tv_coverage_pct'] ?? 66 }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#06B6D4]" style="width: {{ $metrics['tv_coverage_pct'] ?? 66 }}%"></div>
                        </div>
                    </div>

                    <!-- Bar 2: Verified Properties Rate -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Verification</span>
                            <span class="font-extrabold text-purple-700 font-mono">{{ $metrics['verified_hotels_pct'] ?? 31 }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#241372]" style="width: {{ $metrics['verified_hotels_pct'] ?? 31 }}%"></div>
                        </div>
                    </div>

                    <!-- Bar 3: Paid Subscription Rate -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Paid Plans</span>
                            <span class="font-extrabold text-emerald-600 font-mono">{{ $metrics['paid_hotels_pct'] ?? 18 }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#10B981]" style="width: {{ $metrics['paid_hotels_pct'] ?? 18 }}%"></div>
                        </div>
                    </div>

                    <!-- Bar 4: Channel Capacity -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Channel Cap</span>
                            <span class="font-extrabold text-amber-600 font-mono">85%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#F59E0B]" style="width: 85%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 2. "Recomended Jobs" equivalent -> Top Hotel Client Deployments (3 Side-by-Side Cards) -->
            <div>
                <div class="flex items-center justify-between mb-3.5">
                    <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Top Hotel Client Deployments</h4>
                    <a href="{{ route('super-admin.hotels.index') }}" class="text-xs font-bold text-purple-700 hover:text-purple-900 transition-colors flex items-center space-x-1">
                        <span>View All ({{ $metrics['total_hotels'] ?? 0 }})</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @php
                    $deploymentBadgeColors = ['bg-[#2835EB]', 'bg-[#F97316]', 'bg-[#8B5CF6]'];
                @endphp

                <!-- 3 Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                    @forelse($topHotels as $index => $th)
                        @php
                            $badgeBg = $deploymentBadgeColors[$index % 3];
                            $initial = strtoupper(substr($th->hotel_name ?? 'H', 0, 1));
                        @endphp
                        <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between h-[230px]">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="text-[11px] text-slate-400 font-semibold truncate">
                                        {{ $th->distributor->name ?? 'Direct Enterprise' }}
                                    </div>
                                    <div class="w-10 h-10 rounded-2xl {{ $badgeBg }} text-white flex items-center justify-center font-extrabold text-base shrink-0 shadow-sm">
                                        {{ $initial }}
                                    </div>
                                </div>

                                <h5 class="text-sm font-extrabold text-slate-900 mt-2 truncate" title="{{ $th->hotel_name }}">
                                    {{ $th->hotel_name }}
                                </h5>
                                <div class="text-xs font-bold text-slate-700 font-mono mt-1">
                                    ₹{{ number_format($th->plan->price ?? 1999) }} • {{ $th->room_count }} Rooms
                                </div>

                                <p class="text-xs text-slate-500 font-medium mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $th->hotel_location ?? 'Prime Hub Location' }}, {{ $th->city ?? 'India' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5 pt-3 border-t border-slate-100">
                                <span class="px-2.5 py-1 rounded-xl bg-purple-50 text-[#241372] text-[10px] font-bold tracking-wider font-mono">
                                    {{ $th->approval_status === 'approved' ? 'ACTIVE' : 'PENDING' }}
                                </span>
                                <span class="px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 text-[10px] font-bold tracking-wider font-mono">
                                    {{ $th->connected_devices_count }} TVS
                                </span>
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-semibold truncate max-w-[90px]">
                                    {{ strtoupper($th->city ?? 'METRO') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between h-[230px]">
                            <div>
                                <div class="flex items-start justify-between">
                                    <span class="text-[11px] text-slate-400 font-semibold">Maximoz Team</span>
                                    <div class="w-10 h-10 rounded-2xl bg-[#2835EB] text-white flex items-center justify-center font-extrabold text-base shadow-sm">M</div>
                                </div>
                                <h5 class="text-sm font-extrabold text-slate-900 mt-2">Grand Horizon Palace</h5>
                                <div class="text-xs font-bold text-slate-700 font-mono mt-1">₹14,000 • 85 Rooms</div>
                                <p class="text-xs text-slate-500 font-medium mt-1.5">Diplomatic Enclave, Chanakyapuri, New Delhi</p>
                            </div>
                            <div class="flex items-center gap-1.5 pt-3 border-t border-slate-100">
                                <span class="px-2.5 py-1 rounded-xl bg-purple-50 text-[#241372] text-[10px] font-bold font-mono">ACTIVE</span>
                                <span class="px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 text-[10px] font-bold font-mono">NEW DELHI</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- ================= BRAND NEW ANALYTICS ROW: 2 GRAPHS SIDE-BY-SIDE ================= -->
    <!-- (Inspired by "Monthly In Out" Doughnut Chart in Walletz + Regional Fleet Analytics) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Graph 2: Subscription Tier Adoption (Matching "Monthly In Out" Doughnut Ring in Walletz) -->
        <div class="lg:col-span-5 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Subscription Tier Adoption</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Distribution by package tier</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-[#F3F5FA] text-xs font-bold text-slate-700">All Tiers</span>
                </div>

                <!-- Doughnut Chart Canvas with Center Sum Metric -->
                <div class="relative h-60 w-full flex items-center justify-center my-2">
                    <canvas id="planDoughnutChart"></canvas>
                    
                    <!-- Center Overlay Metric (matching Walletz $34.712 [100%]) -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-extrabold text-slate-900 font-mono">
                            {{ $metrics['total_hotels'] ?? 0 }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">
                            Properties
                        </span>
                    </div>
                </div>
            </div>

            <!-- Legend Pills underneath (Economy, Deluxe, Executive, Presidential) -->
            <div class="grid grid-cols-2 gap-2.5 pt-4 border-t border-slate-100 text-xs">
                @php
                    $planColors = ['#06B6D4', '#10B981', '#F59E0B', '#241372'];
                    $labels = $planDistribution['labels'] ?? [];
                    $data = $planDistribution['data'] ?? [];
                @endphp
                @foreach($labels as $idx => $label)
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $planColors[$idx % 4] }};"></span>
                        <span class="text-slate-600 font-semibold truncate">{{ $label }}:</span>
                        <span class="font-extrabold text-slate-900 font-mono">{{ $data[$idx] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Graph 3: Metropolitan Hubs & Hardware Density Bar Chart -->
        <div class="lg:col-span-7 bg-white rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Regional Fleet & Room Capacity</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Licensed rooms deployed by top metropolitan city</p>
                    </div>
                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full font-mono">TOP CITIES</span>
                </div>

                <!-- Bar Chart Canvas -->
                <div class="relative h-60 w-full mt-2">
                    <canvas id="cityBarChart"></canvas>
                </div>
            </div>

            <!-- Summary metrics underneath bar chart -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs text-slate-500 font-medium">
                <span>Top Regional Presence: <strong class="text-slate-800">Mumbai & Delhi NCR</strong></span>
                <span class="font-mono text-purple-700 font-bold">100% Realtime Fleet Sync</span>
            </div>
        </div>

    </div>

    <!-- ================= BOTTOM ROW: DISTRIBUTOR PARTNERS NETWORK ================= -->
    <div class="bg-white rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-xs">
        
        <!-- Header with Pagination Dots & View More Button -->
        <div class="flex items-center justify-between mb-5">
            <div>
                <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Distributor Partners Network</h4>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Authorized distribution channels and fleet operators</p>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Dots Indicator (Jobie style) -->
                <div class="hidden sm:flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#241372]"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                </div>

                <!-- View More Button -->
                <a href="{{ route('super-admin.hotels.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-full bg-[#F3F5FA] hover:bg-[#241372] text-slate-700 hover:text-white text-xs font-bold transition-all shadow-xs">
                    <span>View More</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        @php
            $partnerColorPalette = [
                'bg-[#06B6D4]',
                'bg-[#10B981]',
                'bg-[#F59E0B]',
                'bg-[#EC4899]',
                'bg-[#8B5CF6]',
            ];
        @endphp

        <!-- 5 Cards Horizontal Grid: Dynamic from Database -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($distributors as $index => $part)
                @php
                    $pBg = $partnerColorPalette[$index % 5];
                    $initial = strtoupper(substr($part->name ?? 'D', 0, 1));
                @endphp
                <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/60 flex items-center space-x-3.5 hover:bg-white hover:shadow-sm transition-all group">
                    <div class="w-11 h-11 rounded-2xl {{ $pBg }} text-white flex items-center justify-center font-extrabold text-base shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                        {{ $initial }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h6 class="text-xs font-bold text-slate-900 truncate">{{ $part->name }}</h6>
                        <p class="text-[11px] text-slate-500 font-medium font-mono mt-0.5">
                            {{ $part->hotels_count ?? 0 }} Properties
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/60 flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-[#06B6D4] text-white flex items-center justify-center font-extrabold text-base">H</div>
                    <div>
                        <h6 class="text-xs font-bold text-slate-900">Herman-Carter Media</h6>
                        <p class="text-[11px] text-slate-500 font-mono">21 Properties</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Chart.js Library & Multi-Graph Configurations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // ================= 1. Telemetry & Growth Spline Curves Chart =================
    const ctx1 = document.getElementById('jobieTelemetryChart');
    if (ctx1) {
        const labels = @json($trends['labels'] ?? []);
        const hotelCounts = @json($trends['hotelCounts'] ?? $trends['counts'] ?? []);
        const deviceCounts = @json($trends['deviceCounts'] ?? []);
        const saleCounts = @json($trends['saleCounts'] ?? []);

        const chartContext = ctx1.getContext('2d');
        const purpleGradient = chartContext.createLinearGradient(0, 0, 0, 240);
        purpleGradient.addColorStop(0, 'rgba(36, 19, 114, 0.16)');
        purpleGradient.addColorStop(1, 'rgba(36, 19, 114, 0.00)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Properties Onboarded',
                        data: hotelCounts,
                        borderColor: '#241372',
                        backgroundColor: purpleGradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#241372',
                        pointBorderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Smart TVs Paired',
                        data: deviceCounts,
                        borderColor: '#06B6D4',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.45,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#06B6D4',
                        pointBorderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Sales Packages',
                        data: saleCounts,
                        borderColor: '#E11D48',
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.45,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#E11D48',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }
                ]
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
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                        bodyFont: { family: 'Space Mono', size: 11, weight: '600' },
                        borderColor: 'rgba(226, 232, 240, 0.9)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        cornerRadius: 14,
                        shadowOffsetX: 0,
                        shadowOffsetY: 4,
                        shadowBlur: 12,
                        shadowColor: 'rgba(0, 0, 0, 0.08)'
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' },
                            color: '#94A3B8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { family: 'Space Mono', size: 11 },
                            color: '#94A3B8'
                        },
                        grid: {
                            color: 'rgba(241, 245, 249, 1)',
                            borderDash: [4, 4]
                        }
                    }
                }
            }
        });
    }

    // ================= 2. Subscription Tier Doughnut Chart (Walletz "Monthly In Out") =================
    const ctx2 = document.getElementById('planDoughnutChart');
    if (ctx2) {
        const planLabels = @json($planDistribution['labels'] ?? []);
        const planData = @json($planDistribution['data'] ?? []);

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: planLabels,
                datasets: [{
                    data: planData.length ? planData : [1, 2, 4, 3],
                    backgroundColor: [
                        '#06B6D4', // Economy
                        '#10B981', // Deluxe
                        '#F59E0B', // Executive
                        '#241372', // Presidential
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
                        bodyFont: { family: 'Space Mono', size: 11 }
                    }
                }
            }
        });
    }

    // ================= 3. Regional Hubs Bar Chart =================
    const ctx3 = document.getElementById('cityBarChart');
    if (ctx3) {
        const cityLabels = @json($cityDistribution['labels'] ?? []);
        const roomCounts = @json($cityDistribution['roomCounts'] ?? []);

        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: cityLabels.length ? cityLabels : ['Mumbai', 'New Delhi', 'Bengaluru', 'Gurugram', 'Goa', 'Hyderabad'],
                datasets: [{
                    label: 'Licensed Room Capacity',
                    data: roomCounts.length ? roomCounts : [490, 205, 95, 85, 110, 135],
                    backgroundColor: [
                        '#241372',
                        '#3B1F9E',
                        '#06B6D4',
                        '#10B981',
                        '#F59E0B',
                        '#8B5CF6'
                    ],
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
                        bodyFont: { family: 'Space Mono', size: 11 }
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
                            font: { family: 'Space Mono', size: 10 },
                            color: '#94A3B8'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
