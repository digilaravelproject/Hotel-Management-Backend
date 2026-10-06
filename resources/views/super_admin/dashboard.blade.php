@extends('layouts.super_admin')

@section('title', 'Super Admin Executive Dashboard')
@section('page_title', 'Master Control Center')

@section('content')
<div class="space-y-8">

    <!-- Executive Greeting & Action Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-white p-6 sm:p-8 md:p-10 border border-slate-800 shadow-xl">
        <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -top-12 right-32 w-64 h-64 bg-indigo-600/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>System Operational • {{ now()->format('l, F j, Y') }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Platform Performance & Health
                </h2>
                <p class="text-slate-400 text-xs sm:text-sm font-medium leading-relaxed">
                    Centralized management of hotel client accounts, smart TV hardware fleets, distributor sales channels, and OTA software deployments.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto shrink-0">
                <a href="{{ route('super-admin.hotels.create') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Onboard Hotel</span>
                </a>
                <a href="{{ route('super-admin.settings.index') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-slate-800/80 hover:bg-slate-700/90 border border-slate-700 text-slate-200 hover:text-white font-bold text-xs transition-all">
                    <i class="fa-solid fa-sliders text-xs text-rose-400"></i>
                    <span>Branding Settings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Actionable System Alerts (if any) -->
    @if(!empty($alerts))
        <div class="space-y-3">
            @foreach($alerts as $alert)
                <div class="p-4 sm:p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $alert['title'] }}</h4>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                    @if(!empty($alert['action_url']))
                        <a href="{{ $alert['action_url'] }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-500 transition-all shrink-0 self-start sm:self-auto">
                            <span>{{ $alert['action_label'] ?? 'View Details' }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Primary Executive KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-5">
        <!-- 1. Total Properties -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Properties</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-hotel text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($metrics['total_hotels'] ?? 0) }}</div>
                <div class="flex items-center space-x-1.5 text-[11px] text-emerald-600 font-semibold mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    <span>{{ $metrics['active_hotels'] ?? 0 }} Active / Verified</span>
                </div>
            </div>
        </div>

        <!-- 2. TV Devices Fleet -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Smart TV Fleet</span>
                <div class="w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                    <i class="fa-solid fa-tv text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($metrics['total_devices'] ?? 0) }}</div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">
                    Across {{ number_format($metrics['total_licensed_rooms'] ?? 0) }} licensed rooms
                </div>
            </div>
        </div>

        <!-- 3. Authorized Distributors -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Distributors</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fa-solid fa-briefcase text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($metrics['total_distributors'] ?? 0) }}</div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">
                    Partner sales channels
                </div>
            </div>
        </div>

        <!-- 4. Estimated Monthly Volume -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Monthly Run-Rate</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-indian-rupee-sign text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    ₹{{ number_format($metrics['total_revenue'] ?? 0, 0) }}
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">
                    {{ $metrics['sales_count'] ?? 0 }} packages logged
                </div>
            </div>
        </div>

        <!-- 5. Pending Approvals -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Action</span>
                <div class="w-10 h-10 rounded-2xl {{ ($metrics['pending_approvals'] ?? 0) > 0 ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }} flex items-center justify-center">
                    <i class="fa-solid fa-clock text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold {{ ($metrics['pending_approvals'] ?? 0) > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight">
                    {{ number_format($metrics['pending_approvals'] ?? 0) }}
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">
                    Awaiting authorization
                </div>
            </div>
        </div>

        <!-- 6. Expiring Licenses (Next 30 Days) -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Renewals (30D)</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($metrics['expiring_soon'] ?? 0) }}
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">
                    {{ $metrics['already_expired'] ?? 0 }} already expired
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Intelligence Row (Charts & Distribution) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <!-- Chart 1: Onboarding Velocity Trend (8 cols) -->
        <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-xs space-y-6 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Client Onboarding Growth</h3>
                    <p class="text-xs text-slate-500 font-medium">Monthly trajectory of properties registered on the network</p>
                </div>
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold self-start sm:self-auto">
                    <i class="fa-regular fa-calendar text-[11px]"></i>
                    <span>Last 6 Months</span>
                </span>
            </div>

            <!-- Canvas Container -->
            <div class="relative w-full h-64 sm:h-72">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Plan Adoption Distribution (4 cols) -->
        <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-7 shadow-xs space-y-6 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Subscription Tier Adoption</h3>
                <p class="text-xs text-slate-500 font-medium">Distribution of tier packages across clients</p>
            </div>

            <!-- Donut Container -->
            <div class="relative w-full h-56 flex items-center justify-center">
                <canvas id="planChart"></canvas>
            </div>

            <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2 text-center text-xs">
                <div class="p-2.5 rounded-xl bg-slate-50">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Active Plans</span>
                    <span class="font-extrabold text-slate-800 text-sm">{{ $metrics['total_plans'] ?? 0 }}</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">OTA Builds</span>
                    <span class="font-extrabold text-slate-800 text-sm">{{ $metrics['total_templates'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Production-Ready Table: Recent Properties Directory -->
    <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-hidden space-y-4">
        <!-- Table Header & Filter Bar -->
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Recent Property Onboardings</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Verified overview of hotel accounts, license status, and hardware allocations.</p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-slate-100 rounded-2xl text-xs font-semibold self-start md:self-auto">
                <a href="{{ route('super-admin.dashboard', ['status' => 'all']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? 'all') === 'all' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    All Properties
                </a>
                <a href="{{ route('super-admin.dashboard', ['status' => 'approved']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? '') === 'approved' ? 'bg-white text-emerald-700 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Active & Approved
                </a>
                <a href="{{ route('super-admin.dashboard', ['status' => 'pending']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? '') === 'pending' ? 'bg-white text-rose-600 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Pending ({{ $metrics['pending_approvals'] ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Property / Brand</th>
                        <th class="py-3.5 px-6">Primary Contact</th>
                        <th class="py-3.5 px-6">Partner Distributor</th>
                        <th class="py-3.5 px-6">Plan Tier</th>
                        <th class="py-3.5 px-6 text-center">Smart TVs / Rooms</th>
                        <th class="py-3.5 px-6">Approval Status</th>
                        <th class="py-3.5 px-6">License Expiry</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentHotels as $hotel)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Property -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center font-extrabold text-indigo-700 shrink-0 text-sm">
                                        {{ strtoupper(substr($hotel->hotel_name ?? 'H', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-bold text-slate-900 hover:text-rose-600 transition-colors block text-sm">
                                            {{ $hotel->hotel_name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block truncate max-w-xs">
                                            <i class="fa-solid fa-location-dot text-[10px] mr-1"></i>{{ $hotel->city ?: $hotel->hotel_location }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $hotel->phone ?: $hotel->email }}</div>
                            </td>

                            <!-- Distributor -->
                            <td class="py-4 px-6">
                                @if($hotel->distributor)
                                    <span class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-800">
                                        <i class="fa-solid fa-briefcase text-amber-500 text-[10px]"></i>
                                        <span>{{ $hotel->distributor->name }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 font-medium">Direct Platform</span>
                                @endif
                            </td>

                            <!-- Plan Tier -->
                            <td class="py-4 px-6">
                                @if($hotel->plan)
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-[11px] border border-indigo-100">
                                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                                        <span>{{ $hotel->plan->name }}</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-600 font-medium text-[11px]">
                                        Custom / Unassigned
                                    </span>
                                @endif
                            </td>

                            <!-- Hardware Devices -->
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-800 font-extrabold text-xs">
                                    <i class="fa-solid fa-tv text-slate-500 text-[10px]"></i>
                                    <span>{{ $hotel->connected_devices_count ?? 0 }}</span>
                                    <span class="text-slate-400 font-normal">/ {{ $hotel->room_count }}</span>
                                </div>
                            </td>

                            <!-- Approval -->
                            <td class="py-4 px-6">
                                @if($hotel->approval_status === 'approved')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Approved</span>
                                    </span>
                                @elseif($hotel->approval_status === 'pending')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px] border border-amber-200 animate-pulse">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        <span>Pending Review</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px] border border-rose-200">
                                        <i class="fa-solid fa-ban text-[10px]"></i>
                                        <span>Disapproved</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Expiry -->
                            <td class="py-4 px-6 font-medium">
                                @if($hotel->expiry_date)
                                    @if($hotel->expiry_date->isPast())
                                        <span class="text-rose-600 font-bold">
                                            Expired ({{ $hotel->expiry_date->format('M d, Y') }})
                                        </span>
                                    @elseif($hotel->expiry_date->diffInDays(now()) <= 15)
                                        <span class="text-amber-600 font-bold">
                                            {{ $hotel->expiry_date->format('M d, Y') }} ({{ $hotel->expiry_date->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="text-slate-600">
                                            {{ $hotel->expiry_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-normal">No Expiry Set</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all" title="View Property Details">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-all" title="Edit Configuration">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-hotel text-xl"></i>
                                </div>
                                <div class="font-bold text-slate-700">No properties found matching current criteria</div>
                                <p class="text-xs text-slate-400 mt-1">Onboard a new hotel client to begin monitoring.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500 font-medium">Showing recent {{ count($recentHotels) }} recorded properties</span>
            <a href="{{ route('super-admin.hotels.index') }}" class="font-bold text-rose-600 hover:text-rose-700 inline-flex items-center space-x-1.5 transition-colors">
                <span>View Complete Directory</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- Secondary Row: Distributor Package Sales Transactions -->
    <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-hidden space-y-4">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Recent Distributor Package Transactions</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Real-time ledger of licenses and packages sold by authorized partners.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                Ledger Activity
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Transaction ID</th>
                        <th class="py-3.5 px-6">Distributor Partner</th>
                        <th class="py-3.5 px-6">Client Hotel</th>
                        <th class="py-3.5 px-6">Package Plan</th>
                        <th class="py-3.5 px-6">Amount</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentTransactions as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-slate-900">#SALE-{{ $sale->id }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-900">{{ $sale->distributor->name ?? 'Unknown Partner' }}</td>
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $sale->hotel->hotel_name ?? 'Property #' . $sale->hotel_id }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 font-semibold text-[11px]">
                                    {{ $sale->plan->name ?? 'Custom Package' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-slate-900">₹{{ number_format($sale->amount, 2) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                    {{ strtoupper($sale->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-500 font-medium">{{ $sale->created_at->format('M d, Y • h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                                <div class="font-semibold text-slate-600">No partner sales transactions recorded yet</div>
                                <p class="text-[11px] text-slate-400 mt-0.5">Sales completed through the Distributor Portal will automatically log here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- Chart.js for Business Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Onboarding Velocity Chart
    const trendCtx = document.getElementById('growthChart');
    if (trendCtx) {
        const trendLabels = @json($trends['labels'] ?? []);
        const trendData = @json($trends['counts'] ?? []);

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Properties Onboarded',
                    data: trendData,
                    borderColor: '#e11d48',
                    backgroundColor: 'rgba(225, 29, 72, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#e11d48',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 12,
                        displayColors: false,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' },
                        grid: { color: 'rgba(226, 232, 240, 0.7)' }
                    }
                }
            }
        });
    }

    // 2. Subscription Plan Distribution Doughnut
    const planCtx = document.getElementById('planChart');
    if (planCtx) {
        const planLabels = @json($planDistribution['labels'] ?? []);
        const planData = @json($planDistribution['data'] ?? []);

        new Chart(planCtx, {
            type: 'doughnut',
            data: {
                labels: planLabels,
                datasets: [{
                    data: planData,
                    backgroundColor: [
                        '#6366f1',
                        '#e11d48',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#94a3b8'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 12,
                    }
                }
            }
        });
    }
});
</script>
@endsection
