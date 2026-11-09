@extends('layouts.super_admin')

@section('title', 'Subscription Plans - Super Admin')
@section('page_title', 'Subscription Plans')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header & Action CTA -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hospitality Hub</span>
                <span>/</span>
                <span class="text-[#150A42]">Per-TV Plans</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Subscription Pricing Plans</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Manage flexible per-device monthly & yearly licensing packages and OTT streaming bundles.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('super-admin.taxes.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 font-bold text-xs transition-all shadow-xs space-x-2">
                <i class="fa-solid fa-percent text-purple-600 text-xs"></i>
                <span>Tax Management</span>
            </a>
            <a href="{{ route('super-admin.invoices.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 font-bold text-xs transition-all shadow-xs space-x-2">
                <i class="fa-solid fa-file-invoice text-emerald-600 text-xs"></i>
                <span>Tax Invoices</span>
            </a>
            <a href="{{ route('super-admin.plans.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Plan</span>
            </a>
        </div>
    </div>

    <!-- Plans Responsive Grid (1-col on mobile, 2-col on tablet, 3-col on desktop) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @forelse($plans as $plan)
            @php
                $cycle = $plan->billing_cycle ?? 'monthly';
                $rate = (float) $plan->effective_price_per_tv;
                $tax = $plan->tax;
                $taxType = $plan->tax_type ?? 'exclusive';
            @endphp
            <div id="plan-card-{{ $plan->id }}" 
                 class="bg-white border border-slate-200/90 rounded-[28px] p-5 sm:p-6 shadow-xs hover:shadow-xl hover:border-purple-300 transition-all duration-200 flex flex-col justify-between space-y-6 relative overflow-hidden {{ !$plan->status ? 'opacity-60 bg-slate-50/50' : '' }}">
                
                <!-- Billing Cycle Accent Ribbon -->
                <div class="absolute top-0 right-0">
                    @if($cycle === 'yearly')
                        <span class="inline-block px-3.5 py-1 rounded-bl-2xl bg-amber-500 text-slate-900 font-extrabold text-[10px] uppercase font-mono tracking-wider shadow-xs">
                            <i class="fa-solid fa-crown mr-1 text-[9px]"></i> Yearly Plan
                        </span>
                    @else
                        <span class="inline-block px-3.5 py-1 rounded-bl-2xl bg-purple-100 text-[#150A42] font-extrabold text-[10px] uppercase font-mono tracking-wider">
                            <i class="fa-solid fa-calendar-days mr-1 text-[9px]"></i> Monthly Plan
                        </span>
                    @endif
                </div>

                <div class="space-y-4 pt-2">
                    <!-- Title & Plan Info -->
                    <div>
                        <div class="flex items-center space-x-2 pr-20 flex-wrap">
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight font-sans truncate">{{ $plan->name }}</h3>
                            @if($plan->status)
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0" title="Active Plan"></span>
                            @else
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300 shrink-0" title="Disabled Plan"></span>
                            @endif
                            @if($plan->is_hidden)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs" title="Hidden from Hotels & Distributors — Demo / Admin Only">
                                    <i class="fa-solid fa-eye-slash text-[9px] mr-0.5"></i> Hidden / Demo
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 min-h-[32px]">
                            {{ $plan->description ?? 'Smart Hotel TV launcher subscription per connected display.' }}
                        </p>
                    </div>

                    <!-- Per-TV Price in Space Mono -->
                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-slate-200/80 space-y-2">
                        <div class="flex items-baseline space-x-1.5 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-black text-[#150A42] font-mono tracking-tight leading-none">
                                ₹{{ number_format($plan->monthly_rate_per_tv, 0) }}
                            </span>
                            <span class="text-xs text-slate-500 font-sans font-bold">/ TV / month</span>
                        </div>

                        @if($cycle === 'yearly')
                            <div class="text-[11px] font-bold text-amber-900 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200/80 flex items-center space-x-1.5">
                                <i class="fa-solid fa-crown text-[10px] text-amber-600"></i>
                                <span>Billed annually at ₹{{ number_format($plan->annual_total_per_tv, 0) }} / TV / year</span>
                            </div>
                        @else
                            <div class="text-[11px] font-semibold text-slate-500 flex items-center space-x-1.5">
                                <i class="fa-solid fa-calendar-days text-[10px] text-purple-600"></i>
                                <span>Billed monthly</span>
                            </div>
                        @endif
                        
                        <!-- Tax Badge -->
                        <div class="pt-1 flex items-center space-x-2 flex-wrap gap-1">
                            @if($tax && $tax->rate > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold font-mono {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    <i class="fa-solid fa-shield-halved mr-1 text-[9px]"></i>
                                    {{ $tax->name }} ({{ number_format($tax->rate, 0) }}% {{ ucfirst($taxType) }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-semibold font-mono bg-slate-100 text-slate-600 border border-slate-200">
                                    No Tax Applied
                                </span>
                            @endif
                            
                            @if($plan->room_count)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold font-mono bg-purple-50 text-[#150A42] border border-purple-100">
                                    Max {{ $plan->room_count }} TVs
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Features & OTT Status Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        @if($plan->hasOtt())
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-purple-50 text-[#150A42] border border-purple-200/60 font-bold text-xs font-mono shadow-2xs">
                                <i class="fa-solid fa-play mr-1.5 text-xs text-purple-600"></i>
                                <span>OTT: {{ count($plan->ott_platforms ?? []) }} Apps</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-500 font-bold text-xs font-mono">
                                <i class="fa-solid fa-ban mr-1.5 text-xs text-slate-400"></i>
                                <span>OTT Disabled</span>
                            </span>
                        @endif

                        @php
                            $menusCount = is_array($plan->allowed_menus) ? count($plan->allowed_menus) : 10;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-xs font-mono shadow-2xs">
                            <i class="fa-solid fa-bars mr-1.5 text-xs text-slate-400"></i>
                            <span>{{ $menusCount }}/10 Menus</span>
                        </span>
                    </div>
                </div>

                <!-- Footer Interactive Controls -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <!-- Live Status Toggle -->
                    <div class="flex items-center space-x-2.5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="togglePlanStatus({{ $plan->id }})" {{ $plan->status ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                        <span id="plan-status-text-{{ $plan->id }}" class="text-xs font-bold font-mono uppercase {{ $plan->status ? 'text-emerald-700' : 'text-slate-400' }}">
                            {{ $plan->status ? 'Active' : 'Disabled' }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="inline-flex items-center space-x-2">
                        <a href="{{ route('super-admin.plans.edit', $plan->id) }}" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 flex items-center justify-center transition-all shadow-xs cursor-pointer" title="Edit Plan">
                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                        </a>
                        <form action="{{ route('super-admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this pricing tier?');" class="inline m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-all shadow-xs cursor-pointer" title="Delete Plan">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white border border-slate-200/80 rounded-[28px] text-slate-400 space-y-3 shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-[#150A42] mx-auto flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-800">No Pricing Plans Created Yet</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Create flexible monthly and yearly per-TV plans with customizable tax rates.</p>
                <a href="{{ route('super-admin.plans.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create First Per-TV Plan</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePlanStatus(id) {
        const card = document.getElementById(`plan-card-${id}`);
        const statusText = document.getElementById(`plan-status-text-${id}`);
        fetch(`/super-admin/plans/${id}/toggle-status`, {
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && card) {
                card.classList.toggle('opacity-60', !data.status);
                card.classList.toggle('bg-slate-50/50', !data.status);
                if (statusText) {
                    statusText.textContent = data.status ? 'Active' : 'Disabled';
                    statusText.className = `text-xs font-bold font-mono uppercase ${data.status ? 'text-emerald-700' : 'text-slate-400'}`;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.status ? 'Plan activated' : 'Plan disabled',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: { popup: 'rounded-2xl border border-slate-200 font-sans shadow-lg text-xs' }
                    });
                }
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endsection
