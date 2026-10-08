@extends('layouts.super_admin')

@section('title', 'Hotel Vendor Details - Super Admin')
@section('page_title', 'Hotel Client Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-5 sm:space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between gap-2.5">
        <a href="{{ route('super-admin.hotels.index') }}" class="inline-flex items-center px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs shrink-0">
            <i class="fa-solid fa-arrow-left mr-1.5 text-rose-500"></i>
            <span class="hidden sm:inline">Back to Hotels Directory</span>
            <span class="sm:hidden">Back</span>
        </a>
        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('super-admin.devices.index', ['hotel_id' => $hotel->id]) }}" class="hidden sm:inline-flex items-center px-3.5 py-2.5 rounded-xl bg-white border border-slate-200/80 text-slate-700 hover:text-slate-900 text-xs font-bold transition-all shadow-xs">
                <i class="fa-solid fa-tv mr-1.5 text-emerald-500"></i> TVs ({{ $hotel->connectedDevices ? $hotel->connectedDevices->count() : 0 }})
            </a>
            <a href="{{ route('super-admin.hotels.edit', $hotel->id) }}" class="inline-flex items-center px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition-all hover:-translate-y-0.5 whitespace-nowrap">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                <span class="hidden sm:inline">Edit Vendor Account</span>
                <span class="sm:hidden">Edit Account</span>
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xl shadow-slate-100/50">
        <!-- Hero Branding Header -->
        <div class="relative bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-5 sm:p-8 text-white overflow-hidden border-b border-slate-800">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6">
                <div class="flex items-center space-x-3.5 sm:space-x-5 min-w-0">
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 overflow-hidden shrink-0 flex items-center justify-center p-2 shadow-2xl">
                        @if($hotel->hotel_logo)
                            <img src="{{ asset($hotel->hotel_logo) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-hotel text-xl sm:text-2xl text-rose-400"></i>
                        @endif
                    </div>
                    <div class="min-w-0 space-y-1">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight truncate">{{ $hotel->hotel_name }}</h1>
                        <p class="text-slate-400 text-xs font-medium flex items-center truncate">
                            <i class="fa-solid fa-location-dot mr-1.5 text-rose-400 shrink-0"></i>
                            <span class="truncate">{{ $hotel->city ?? $hotel->hotel_location }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center flex-wrap gap-2 shrink-0">
                    @if($hotel->approval_status === 'approved')
                        <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[11px] sm:text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-circle-check mr-1 text-emerald-400"></i> Approved
                        </span>
                    @elseif($hotel->approval_status === 'disapproved')
                        <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-rose-500/20 border border-rose-500/30 text-rose-300 text-[11px] sm:text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-circle-xmark mr-1 text-rose-400"></i> Disapproved
                        </span>
                    @else
                        <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-[11px] sm:text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            <i class="fa-solid fa-clock mr-1 text-amber-400"></i> Pending
                        </span>
                    @endif

                    @if($hotel->status)
                        <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-[11px] sm:text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            Active
                        </span>
                    @else
                        <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-slate-500/20 border border-slate-500/30 text-slate-400 text-[11px] sm:text-xs font-extrabold tracking-wider uppercase backdrop-blur-sm">
                            Suspended
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Content -->
        <div class="p-4 sm:p-8 space-y-6">
            <!-- 2-Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Owner Credentials Box -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 sm:p-6 space-y-3.5">
                    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Owner Credentials</h3>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Full Name</span>
                            <span class="font-extrabold text-slate-900 text-right truncate">{{ $hotel->owner_name }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Email Address</span>
                            <span class="font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 text-[11px] sm:text-xs truncate max-w-[200px] sm:max-w-none text-right">{{ $hotel->email }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-slate-500 font-semibold shrink-0">Phone Line</span>
                            <span class="font-bold text-slate-900 text-right">
                                <a href="tel:{{ $hotel->phone }}" class="hover:text-rose-600 transition-colors">{{ $hotel->phone }}</a>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Licensing & Subscription Box -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 sm:p-6 space-y-3.5">
                    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-layer-group text-sm"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Licensing & Subscription</h3>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Room / TV Limit</span>
                            <span class="font-extrabold text-slate-900 bg-slate-200/70 px-2 py-0.5 rounded-md text-right whitespace-nowrap">{{ $hotel->room_count }} TVs Authorized</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Active Plan Tier</span>
                            <span class="font-extrabold text-indigo-600 text-right truncate">{{ $hotel->plan->name ?? 'Trial' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Payment State</span>
                            <span class="font-extrabold text-right {{ $hotel->payment_status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $hotel->payment_status === 'paid' ? 'Paid (Razorpay)' : 'Unpaid' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/50 pb-2">
                            <span class="text-slate-500 font-semibold shrink-0">Purchase Date</span>
                            <span class="font-bold text-slate-800 text-right">{{ $hotel->purchase_date ? $hotel->purchase_date->format('d M Y, h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-3 pt-0.5">
                            <span class="text-slate-500 font-semibold shrink-0">Expiry Date</span>
                            <div class="text-right">
                                <span class="font-bold text-slate-800 block">{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M Y, h:i A') : 'N/A' }}</span>
                                @if($hotel->expiry_date)
                                    @if($hotel->expiry_date->isPast())
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-100 text-rose-700">Expired</span>
                                    @elseif($hotel->expiry_date->diffInDays(now()) <= 7)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-100 text-amber-800">Expires in {{ ceil(now()->diffInHours($hotel->expiry_date)/24) }} days</span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-700">Active</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- License Key Callout Box -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-5 sm:p-7 text-white border border-slate-800 shadow-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="space-y-1 min-w-0">
                        <span class="text-[11px] font-bold text-rose-400 uppercase tracking-widest block">Connected TV License Key</span>
                        @if($hotel->license_key)
                            <div class="flex items-center space-x-2 pt-0.5">
                                <span class="font-mono text-xl sm:text-2xl font-extrabold text-white tracking-widest drop-shadow-md break-all select-all">
                                    {{ $hotel->license_key }}
                                </span>
                                <button type="button" onclick="copyKey('{{ $hotel->license_key }}')" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition-all text-xs shrink-0" title="Copy License Key">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic">No key generated yet. Complete payment first.</span>
                        @endif
                    </div>
                    
                    <span class="px-3.5 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs font-bold shrink-0">
                        <i class="fa-solid fa-tv mr-1"></i> Valid for {{ $hotel->room_count }} TV Connections
                    </span>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="pt-4 sm:pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 font-medium gap-2">
                <span><i class="fa-regular fa-calendar mr-1"></i> Registered: {{ $hotel->created_at->format('d M, Y H:i') }}</span>
                <span><i class="fa-solid fa-clock-rotate-left mr-1"></i> Updated: {{ $hotel->updated_at->format('d M, Y H:i') }}</span>
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
                title: 'License key copied!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }
</script>
@endsection
