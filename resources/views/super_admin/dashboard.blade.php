@extends('layouts.super_admin')

@section('title', 'Super Admin Executive Dashboard')
@section('page_title', 'Master Control Center')

@section('content')
<div class="space-y-8">

    <!-- Executive Greeting & Action Header -->
    <!-- Executive Greeting & Action Header (colors.md: Pure White #FFFFFF Card, Primary CTA Gold #C5A880) -->
    <div class="relative overflow-hidden rounded-3xl bg-[#FFFFFF] text-[#111827] p-6 sm:p-8 md:p-10 border border-[#E5E7EB] shadow-xs">
        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#F5F2EB] border border-[#C5A880]/40 text-[#8C7250] text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                    <span>System Operational • {{ now()->format('l, F j, Y') }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111827]">
                    Platform Performance & Health
                </h2>
                <p class="text-[#6B7280] text-xs sm:text-sm font-medium leading-relaxed">
                    Centralized management of hotel client accounts, smart TV hardware fleets, distributor sales channels, and OTA software deployments.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto shrink-0">
                <!-- Primary CTA Button (colors.md: Gold Accent #C5A880, hover #8C7250, text #111827) -->
                <a href="{{ route('super-admin.hotels.create') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] font-extrabold text-xs shadow-md shadow-[#C5A880]/25 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>+ Onboard Hotel</span>
                </a>
                <!-- Secondary Button (colors.md: Background #FFFFFF, Border #CBD5E1, Text #111827) -->
                <a href="{{ route('super-admin.settings.index') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-[#FFFFFF] hover:bg-[#F4F5F7] border border-[#CBD5E1] text-[#111827] font-bold text-xs transition-all">
                    <i class="fa-solid fa-sliders text-xs text-[#C5A880]"></i>
                    <span>Branding Settings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Actionable System Alerts (colors.md Status Colors: Pending/Warning Orange #EA580C on #FFEDD5) -->
    @if(!empty($alerts))
        <div class="space-y-3">
            @foreach($alerts as $alert)
                <div class="p-4 sm:p-5 rounded-2xl bg-[#FFFBEB] border border-[#EA580C]/30 text-[#111827] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-9 h-9 rounded-xl bg-[#EA580C]/20 text-[#B45309] flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-[#111827]">{{ $alert['title'] }}</h4>
                            <p class="text-xs text-[#6B7280] font-medium mt-0.5">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                    @if(!empty($alert['action_url']))
                        <a href="{{ $alert['action_url'] }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] font-bold text-xs transition-all shrink-0 self-start sm:self-auto">
                            <span>{{ $alert['action_label'] ?? 'View Details' }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Primary Executive KPI Metrics Grid -->
    <!-- Primary Executive KPI Metrics Grid (colors.md: Pure White #FFFFFF Cards, Dark Slate #111827 Numbers, Soft Gold Tint #F5F2EB Icon Box with Gold #C5A880) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-5">
        <!-- 1. Total Properties -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Properties</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-hotel text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">{{ number_format($metrics['total_hotels'] ?? 0) }}</div>
                <div class="flex items-center space-x-1.5 text-[11px] text-[#15803D] font-bold mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    <span>{{ $metrics['active_hotels'] ?? 0 }} Active / Verified</span>
                </div>
            </div>
        </div>

        <!-- 2. TV Devices Fleet -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Smart TV Fleet</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-tv text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">{{ number_format($metrics['total_devices'] ?? 0) }}</div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1">
                    Across {{ number_format($metrics['total_licensed_rooms'] ?? 0) }} licensed rooms
                </div>
            </div>
        </div>

        <!-- 3. Authorized Distributors -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Distributors</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-briefcase text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">{{ number_format($metrics['total_distributors'] ?? 0) }}</div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1">
                    Partner sales channels
                </div>
            </div>
        </div>

        <!-- 4. Estimated Monthly Volume -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Monthly Run-Rate</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-indian-rupee-sign text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                    ₹{{ number_format($metrics['total_revenue'] ?? 0, 0) }}
                </div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1">
                    {{ $metrics['sales_count'] ?? 0 }} packages logged
                </div>
            </div>
        </div>

        <!-- 5. Pending Approvals -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Pending Action</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-clock text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold {{ ($metrics['pending_approvals'] ?? 0) > 0 ? 'text-[#B45309]' : 'text-[#111827]' }} tracking-tight">
                    {{ number_format($metrics['pending_approvals'] ?? 0) }}
                </div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1">
                    Awaiting authorization
                </div>
            </div>
        </div>

        <!-- 6. Expiring Licenses (Next 30 Days) -->
        <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-[#6B7280] uppercase tracking-wider">Renewals (30D)</span>
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                    {{ number_format($metrics['expiring_soon'] ?? 0) }}
                </div>
                <div class="text-[11px] text-[#6B7280] font-medium mt-1">
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
    <!-- Production-Ready Table: Recent Properties Directory (colors.md: Pure White Card, Soft Gray Border) -->
    <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl shadow-xs overflow-hidden space-y-4">
        <!-- Table Header & Filter Bar -->
        <div class="p-6 sm:p-7 border-b border-[#E5E7EB] flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#111827] tracking-tight">Recent Property Onboardings</h3>
                <p class="text-xs text-[#6B7280] font-medium mt-0.5">Verified overview of hotel accounts, license status, and hardware allocations.</p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-[#F4F5F7] border border-[#E5E7EB] rounded-2xl text-xs font-semibold self-start md:self-auto">
                <a href="{{ route('super-admin.dashboard', ['status' => 'all']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? 'all') === 'all' ? 'bg-[#FFFFFF] text-[#111827] border border-[#E5E7EB] shadow-xs font-bold' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    All Properties
                </a>
                <a href="{{ route('super-admin.dashboard', ['status' => 'approved']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? '') === 'approved' ? 'bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/30 shadow-xs font-bold' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    Active & Approved
                </a>
                <a href="{{ route('super-admin.dashboard', ['status' => 'pending']) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all {{ ($currentFilter ?? '') === 'pending' ? 'bg-[#FFFBEB] text-[#B45309] border border-[#EA580C]/30 shadow-xs font-bold' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    Pending ({{ $metrics['pending_approvals'] ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Responsive Table Container (colors.md: Table Header #F4F5F7, Body #FFFFFF, Hover #F4F5F7) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#F4F5F7] text-[#6B7280] font-bold uppercase tracking-wider text-[10px] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB] text-[#111827] bg-[#FFFFFF]">
                    @forelse($recentHotels as $hotel)
                        <tr class="hover:bg-[#F4F5F7] transition-colors">
                            <!-- Property -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/35 flex items-center justify-center font-extrabold text-[#C5A880] shrink-0 text-sm">
                                        {{ strtoupper(substr($hotel->hotel_name ?? 'H', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="font-bold text-[#111827] hover:text-[#8C7250] transition-colors block text-sm">
                                            {{ $hotel->hotel_name }}
                                        </a>
                                        <span class="text-[11px] text-[#6B7280] block truncate max-w-xs">
                                            <i class="fa-solid fa-location-dot text-[10px] mr-1 text-[#C5A880]"></i>{{ $hotel->city ?: $hotel->hotel_location }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-[#111827]">{{ $hotel->owner_name }}</div>
                                <div class="text-[11px] text-[#6B7280]">{{ $hotel->phone ?: $hotel->email }}</div>
                            </td>

                            <!-- Distributor -->
                            <td class="py-4 px-6">
                                @if($hotel->distributor)
                                    <span class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#111827]">
                                        <i class="fa-solid fa-briefcase text-[#C5A880] text-[10px]"></i>
                                        <span>{{ $hotel->distributor->name }}</span>
                                    </span>
                                @else
                                    <span class="text-[#6B7280] font-medium">Direct Platform</span>
                                @endif
                            </td>

                            <!-- Plan Tier -->
                            <td class="py-4 px-6">
                                @if($hotel->plan)
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl bg-[#F5F2EB] text-[#8C7250] font-bold text-[11px] border border-[#C5A880]/35">
                                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                                        <span>{{ $hotel->plan->name }}</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-xl bg-[#F4F5F7] text-[#6B7280] font-medium text-[11px] border border-[#E5E7EB]">
                                        Custom / Unassigned
                                    </span>
                                @endif
                            </td>

                            <!-- Hardware Devices -->
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-xl bg-[#F4F5F7] border border-[#E5E7EB] text-[#111827] font-extrabold text-xs">
                                    <i class="fa-solid fa-tv text-[#6B7280] text-[10px]"></i>
                                    <span>{{ $hotel->connected_devices_count ?? 0 }}</span>
                                    <span class="text-[#6B7280] font-normal">/ {{ $hotel->room_count }}</span>
                                </div>
                            </td>

                            <!-- Approval (colors.md Status Badges) -->
                            <td class="py-4 px-6">
                                @if($hotel->approval_status === 'approved')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-[#F0FDF4] text-[#15803D] font-bold text-[11px] border border-[#16A34A]/30">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Approved</span>
                                    </span>
                                @elseif($hotel->approval_status === 'pending')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-[#FFFBEB] text-[#B45309] font-bold text-[11px] border border-[#EA580C]/30 animate-pulse">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        <span>Pending Review</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-[#FEF2F2] text-[#B91C1C] font-bold text-[11px] border border-[#DC2626]/30">
                                        <i class="fa-solid fa-ban text-[10px]"></i>
                                        <span>Disapproved</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Expiry -->
                            <td class="py-4 px-6 font-medium">
                                @if($hotel->expiry_date)
                                    @if($hotel->expiry_date->isPast())
                                        <span class="text-[#B91C1C] font-bold">
                                            Expired ({{ $hotel->expiry_date->format('M d, Y') }})
                                        </span>
                                    @elseif($hotel->expiry_date->diffInDays(now()) <= 15)
                                        <span class="text-[#B45309] font-bold">
                                            {{ $hotel->expiry_date->format('M d, Y') }} ({{ $hotel->expiry_date->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="text-[#111827]">
                                            {{ $hotel->expiry_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-[#6B7280] font-normal">No Expiry Set</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('super-admin.hotels.show', $hotel->id) }}" class="p-2 rounded-xl text-[#6B7280] hover:text-[#111827] hover:bg-[#F4F5F7] transition-all" title="View Property Details">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="p-2 rounded-xl text-[#6B7280] hover:text-[#8C7250] hover:bg-[#F5F2EB] transition-all" title="Edit Configuration">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-[#6B7280]">
                                <div class="w-12 h-12 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] text-[#6B7280] flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-hotel text-xl"></i>
                                </div>
                                <div class="font-bold text-[#111827]">No properties found matching current criteria</div>
                                <p class="text-xs text-[#6B7280] mt-1">Onboard a new hotel client to begin monitoring.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-5 border-t border-[#E5E7EB] flex items-center justify-between text-xs">
            <span class="text-[#6B7280] font-medium">Showing recent {{ count($recentHotels) }} recorded properties</span>
            <a href="{{ route('super-admin.hotels.index') }}" class="font-bold text-[#C5A880] hover:text-[#8C7250] inline-flex items-center space-x-1.5 transition-colors">
                <span>View Complete Directory</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- Secondary Row: Distributor Package Sales Transactions -->
    <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-3xl shadow-xs overflow-hidden space-y-4">
        <div class="p-6 sm:p-7 border-b border-[#E5E7EB] flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-[#111827] tracking-tight">Recent Distributor Package Transactions</h3>
                <p class="text-xs text-[#6B7280] font-medium mt-0.5">Real-time ledger of licenses and packages sold by authorized partners.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-[#F4F5F7] border border-[#E5E7EB] text-[#6B7280] text-xs font-semibold">
                Ledger Activity
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#F4F5F7] text-[#6B7280] font-bold uppercase tracking-wider text-[10px] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB] text-[#111827] bg-[#FFFFFF]">
                    @forelse($recentTransactions as $sale)
                        <tr class="hover:bg-[#F4F5F7] transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-[#111827]">#SALE-{{ $sale->id }}</td>
                            <td class="py-4 px-6 font-semibold text-[#111827]">{{ $sale->distributor->name ?? 'Unknown Partner' }}</td>
                            <td class="py-4 px-6 font-bold text-[#111827]">{{ $sale->hotel->hotel_name ?? 'Property #' . $sale->hotel_id }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-xl bg-[#F5F2EB] text-[#8C7250] font-semibold text-[11px] border border-[#C5A880]/35">
                                    {{ $sale->plan->name ?? 'Custom Package' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-[#111827]">₹{{ number_format($sale->amount, 2) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full bg-[#F0FDF4] text-[#15803D] font-bold text-[10px] border border-[#16A34A]/30">
                                    {{ strtoupper($sale->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[#6B7280] font-medium">{{ $sale->created_at->format('M d, Y • h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-[#6B7280]">
                                <div class="w-10 h-10 rounded-2xl bg-[#F4F5F7] border border-[#E5E7EB] text-[#6B7280] flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                                <div class="font-semibold text-[#111827]">No partner sales transactions recorded yet</div>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Sales completed through the Distributor Portal will automatically log here.</p>
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
    // 1. Onboarding Velocity Chart (colors.md Royal Gold #C5A880 theme)
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
                    borderColor: '#C5A880',
                    backgroundColor: 'rgba(212, 175, 55, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#C5A880',
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
                        backgroundColor: '#111827',
                        titleColor: '#C5A880',
                        bodyColor: '#CBD5E1',
                        padding: 10,
                        cornerRadius: 12,
                        displayColors: false,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#6B7280' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#6B7280' },
                        grid: { color: 'rgba(226, 232, 240, 0.7)' }
                    }
                }
            }
        });
    }

    // 2. Subscription Plan Distribution Doughnut (colors.md Executive Navy & Royal Gold accents)
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
                        '#111827',
                        '#1F2937',
                        '#C5A880',
                        '#8C7250',
                        '#6B7280',
                        '#CBD5E1'
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
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
                            color: '#111827'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#C5A880',
                        bodyColor: '#CBD5E1',
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
