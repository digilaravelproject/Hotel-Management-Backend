@extends('layouts.super_admin')

@section('title', 'Hotel Vendor Profile - Super Admin')
@section('page_title', 'Hotel Client Profile')

@section('content')
<div class="max-w-6xl mx-auto space-y-5 sm:space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('super-admin.hotels.index') }}" class="inline-flex items-center px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50/50 text-xs font-bold transition-all shadow-xs shrink-0 space-x-2">
            <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
            <span class="hidden sm:inline">Back to Hotels Directory</span>
            <span class="sm:hidden">Directory</span>
        </a>

        <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
            <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="inline-flex items-center px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#150A42] hover:bg-purple-50/50 text-xs font-bold transition-all shadow-xs space-x-2">
                <i class="fa-solid fa-tv text-purple-600 text-xs"></i>
                <span>TVs <span class="font-mono text-purple-600 font-extrabold">({{ $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0 }})</span></span>
            </a>
            <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="inline-flex items-center px-4 py-2 sm:px-5 sm:py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 whitespace-nowrap space-x-2">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span class="hidden sm:inline">Edit Account</span>
                <span class="sm:hidden">Edit</span>
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white border border-slate-200/80 rounded-[28px] overflow-hidden shadow-xs">
        
        <!-- Hero Header -->
        <div class="relative bg-gradient-to-b from-[#F8F9FD] to-white p-5 sm:p-8 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center space-x-4 sm:space-x-5 min-w-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white border border-slate-200/90 overflow-hidden shrink-0 flex items-center justify-center p-2 shadow-sm">
                        @if($hotel->hotel_logo)
                            <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-hotel text-2xl text-purple-400"></i>
                        @endif
                    </div>
                    <div class="min-w-0 space-y-1">
                        <div class="flex items-center space-x-2">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight truncate font-sans">{{ $hotel->hotel_name }}</h1>
                        </div>
                        <p class="text-slate-500 text-xs font-semibold flex items-center truncate">
                            <i class="fa-solid fa-location-dot mr-1.5 text-purple-600 shrink-0 text-xs"></i>
                            <span class="truncate">{{ $hotel->city ?? $hotel->hotel_location }}</span>
                            <span class="mx-2 text-slate-300">•</span>
                            <span class="font-mono text-slate-500">ID: #HTL-{{ $hotel->id }}</span>
                        </p>
                    </div>
                </div>

                <!-- Status Pills Group -->
                <div class="flex items-center flex-wrap gap-2 shrink-0">
                    @if($hotel->approval_status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                        </span>
                    @elseif($hotel->approval_status === 'disapproved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Disapproved
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending Review
                        </span>
                    @endif

                    @if($hotel->status)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 border border-purple-200/80 text-[#150A42] text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mr-1.5"></span> Live
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Inactive
                        </span>
                    @endif

                    @php
                        $subBadge = $hotel->subscription_badge;
                    @endphp

                    <span class="inline-flex items-center px-3 py-1 rounded-full {{ $subBadge['badge_class'] }} border text-[11px] font-extrabold uppercase font-mono tracking-wider shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full {{ $subBadge['dot_class'] }} mr-1.5 {{ $subBadge['key'] === 'expiring_soon' ? 'animate-pulse' : '' }}"></span>
                        {{ $subBadge['label'] }} Package
                    </span>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards (2x2 on mobile, 4 in a row on desktop) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-[#FAFBFD] border-b border-slate-100">
            <div class="p-4 sm:p-5 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Room Capacity</div>
                    <div class="text-lg sm:text-xl font-extrabold text-slate-900 font-mono tracking-tight">{{ $hotel->room_count }} Rooms</div>
                </div>
            </div>

            <div class="p-4 sm:p-5 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Authorized TVs</div>
                    <div class="text-lg sm:text-xl font-extrabold text-slate-900 font-mono tracking-tight">{{ $hotel->allowed_device_limit ?? $hotel->room_count }} Screens</div>
                </div>
            </div>

            <div class="p-4 sm:p-5 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Plan Tier</div>
                    <div class="text-sm sm:text-base font-extrabold text-[#150A42] truncate font-mono">{{ $hotel->plan->name ?? 'Custom Package' }}</div>
                </div>
            </div>

            <div class="p-4 sm:p-5 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono">Live Paired TVs</div>
                    <div class="text-lg sm:text-xl font-extrabold text-emerald-600 font-mono tracking-tight">{{ $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0 }} Active</div>
                </div>
            </div>
        </div>

        <!-- Details Content Area -->
        <div class="p-4 sm:p-8 space-y-6">
            
            <!-- Distributor Channel Card (if attached) -->
            @if($hotel->distributor)
            <div class="bg-gradient-to-r from-purple-50/60 via-white to-purple-50/40 border border-purple-100/90 rounded-2xl p-4 sm:p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-2xl bg-[#150A42] text-white flex items-center justify-center font-bold shrink-0 text-base shadow-sm">
                            <i class="fa-solid fa-handshake"></i>
                        </div>
                        <div class="min-w-0 space-y-0.5">
                            <div class="flex items-center space-x-2">
                                <span class="text-[10px] font-extrabold text-purple-600 uppercase tracking-wider font-mono">Channel Partner</span>
                                <span class="px-2 py-0.2 rounded-full text-[9px] font-extrabold font-mono {{ $hotel->distributor->status ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $hotel->distributor->status ? 'Active Partner' : 'Suspended' }}
                                </span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $hotel->distributor->name }}</h3>
                            <p class="text-xs text-slate-500 flex items-center gap-3 truncate font-mono">
                                <span><i class="fa-regular fa-envelope mr-1 text-slate-400"></i> {{ $hotel->distributor->email }}</span>
                                @if($hotel->distributor->phone)
                                    <span><i class="fa-solid fa-phone mr-1 text-slate-400"></i> {{ $hotel->distributor->phone }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-xs font-mono">
                            <i class="fa-solid fa-id-badge mr-1.5 text-purple-600"></i> #DIST-{{ $hotel->distributor->id }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- 2-Column Responsive Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- 1. Owner Credentials Box -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 space-y-3.5 shadow-xs">
                    <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold shrink-0">
                            <i class="fa-regular fa-user text-xs"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-mono">Owner & Contact Info</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Owner Name</span>
                            <span class="font-extrabold text-slate-900 text-right truncate">{{ $hotel->owner_name }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Email Address</span>
                            <a href="mailto:{{ $hotel->email }}" class="font-mono font-bold text-[#150A42] bg-purple-50/70 hover:bg-purple-100 px-2 py-0.5 rounded-lg border border-purple-100 text-[11px] sm:text-xs truncate max-w-[200px] sm:max-w-none text-right transition-colors">
                                {{ $hotel->email }}
                            </a>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Phone Line</span>
                            <a href="tel:{{ $hotel->phone }}" class="font-mono font-bold text-slate-900 hover:text-[#150A42] transition-colors text-right">
                                <i class="fa-solid fa-phone mr-1 text-[10px] text-slate-400"></i>{{ $hotel->phone }}
                            </a>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-slate-500 font-semibold shrink-0">Property Address</span>
                            <span class="font-medium text-slate-700 text-right text-xs truncate max-w-[240px]" title="{{ $hotel->hotel_location }}">
                                {{ $hotel->hotel_location ?? ($hotel->city . ', India') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. Licensing & Subscription Box -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 space-y-3.5 shadow-xs">
                    <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-mono">Subscription & Expiry</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Assigned Package</span>
                            <span class="font-extrabold font-mono text-[#150A42] text-right truncate">
                                {{ $hotel->plan->name ?? 'Custom / Trial Package' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Subscription State</span>
                            <span class="font-extrabold {{ $subBadge['badge_class'] }} px-2.5 py-0.5 rounded-full border text-[10px] uppercase font-mono tracking-wider flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full {{ $subBadge['dot_class'] }} inline-block mr-1.5 {{ $subBadge['key'] === 'expiring_soon' ? 'animate-pulse' : '' }}"></span>
                                {{ $subBadge['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100/70 pb-2.5">
                            <span class="text-slate-500 font-semibold shrink-0">Purchase Date</span>
                            <span class="font-bold text-slate-800 font-mono text-right">
                                {{ $hotel->purchase_date ? $hotel->purchase_date->format('d M Y, h:i A') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-slate-500 font-semibold shrink-0">Expiry Date</span>
                            <div class="text-right">
                                <span class="font-bold text-slate-800 font-mono block">
                                    {{ $hotel->expiry_date ? $hotel->expiry_date->format('d M Y, h:i A') : 'Perpetual / Ongoing' }}
                                </span>
                                @if($hotel->expiry_date)
                                    @if($hotel->expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-100 text-rose-700 font-mono">Expired</span>
                                    @elseif($hotel->expiry_date->diffInDays(now()) <= 7)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800 font-mono">Expires in {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} days</span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-700 font-mono">Active Service</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- License Key Callout Banner -->
            <div class="relative overflow-hidden rounded-2xl bg-[#F8F9FD] p-5 sm:p-7 text-slate-900 border border-slate-200/90 shadow-xs">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="space-y-1.5 min-w-0">
                        <span class="text-[11px] font-extrabold text-[#150A42] uppercase tracking-wider block font-mono">TV Pairing License Key</span>
                        @if($hotel->license_key)
                            <div class="flex items-center space-x-2 pt-0.5">
                                <span class="font-mono text-xl sm:text-2xl font-black text-[#150A42] tracking-wider break-all select-all">
                                    {{ $hotel->license_key }}
                                </span>
                                <button type="button" onclick="copyKey('{{ $hotel->license_key }}')" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 transition-all text-xs font-bold shrink-0 cursor-pointer flex items-center space-x-1.5 shadow-xs" title="Copy License Key">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                            @if($hotel->payment_status !== 'paid' || !$hotel->plan_id || ($hotel->expiry_date && $hotel->expiry_date->isPast()))
                                <p class="text-[11px] text-rose-600 font-bold mt-1">
                                    <i class="fa-solid fa-lock mr-1"></i> Locked: Hardware pairings remain paused until payment is authorized.
                                </p>
                            @endif
                        @else
                            <span class="text-xs text-slate-400 italic font-medium">No license key generated yet. Authorize payment to activate.</span>
                        @endif
                    </div>
                    
                    @if($hotel->payment_status === 'paid' && $hotel->plan_id && (!$hotel->expiry_date || $hotel->expiry_date->isFuture()))
                        <div class="px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-extrabold shrink-0 font-mono shadow-xs">
                            <i class="fa-solid fa-tv mr-1.5 text-emerald-600"></i> {{ $hotel->allowed_device_limit }} TV Slots Authorized
                        </div>
                    @else
                        <div class="px-4 py-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-extrabold shrink-0 font-mono shadow-xs">
                            <i class="fa-solid fa-lock mr-1.5 text-rose-600"></i> Locked (Payment Required)
                        </div>
                    @endif
                </div>
            </div>

            <!-- Management Shortcuts Quick Links -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-purple-300 hover:bg-purple-50/50 transition-all shadow-xs flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 group-hover:bg-[#150A42] group-hover:text-white transition-all flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900 truncate">Connected TVs</div>
                        <div class="text-[10px] text-slate-400 font-mono">{{ $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0 }} paired</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.hotels.menus', $hotel->id) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-amber-300 hover:bg-amber-50/50 transition-all shadow-xs flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 group-hover:bg-amber-600 group-hover:text-white transition-all flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900 truncate">TV Menus</div>
                        <div class="text-[10px] text-slate-400 font-mono">App Launcher</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.hotels.amenities', $hotel->id) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-sky-300 hover:bg-sky-50/50 transition-all shadow-xs flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-700 group-hover:bg-sky-600 group-hover:text-white transition-all flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900 truncate">Amenities</div>
                        <div class="text-[10px] text-slate-400 font-mono">Showcases</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-purple-300 hover:bg-purple-50/50 transition-all shadow-xs flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 group-hover:bg-[#150A42] group-hover:text-white transition-all flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900 truncate">Settings</div>
                        <div class="text-[10px] text-slate-400 font-mono">Edit Config</div>
                    </div>
                </a>
            </div>

            <!-- Audit Timestamps Footer -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 font-mono gap-2">
                <span><i class="fa-regular fa-calendar mr-1"></i> Registered: {{ $hotel->created_at->format('d M, Y H:i') }}</span>
                <span><i class="fa-solid fa-clock-rotate-left mr-1"></i> Last System Update: {{ $hotel->updated_at->format('d M, Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyKey(key) {
        if (!key) return;
        navigator.clipboard.writeText(key).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'License key copied to clipboard!',
                showConfirmButton: false,
                timer: 2000,
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 font-sans shadow-lg text-xs'
                }
            });
        });
    }
</script>
@endsection
