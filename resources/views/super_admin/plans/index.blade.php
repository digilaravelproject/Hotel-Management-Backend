@extends('layouts.super_admin')

@section('title', 'Subscription Plans - Super Admin')
@section('page_title', 'Subscription Plans')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header & Action CTA -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <span>Hospitality Hub</span>
                <span>/</span>
                <span class="text-[#150A42]">Per-TV Plans</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Per-TV Subscription Plans</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Configure per-device monthly & yearly licensing tiers with flexible GST/tax computation.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <a href="{{ route('super-admin.taxes.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 font-bold text-xs transition-all shadow-xs space-x-2">
                <i class="fa-solid fa-percent text-purple-600 text-xs"></i>
                <span>Tax Management</span>
            </a>
            <a href="{{ route('super-admin.invoices.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 font-bold text-xs transition-all shadow-xs space-x-2">
                <i class="fa-solid fa-file-invoice text-emerald-600 text-xs"></i>
                <span>Tax Invoices</span>
            </a>
            <a href="{{ route('super-admin.plans.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Create New Plan</span>
            </a>
        </div>
    </div>

    <!-- Interactive Live TV Fleet Cost Calculator Bar -->
    <div class="bg-gradient-to-br from-[#150A42] to-[#26156E] rounded-[28px] p-5 sm:p-7 text-white shadow-xl shadow-[#150A42]/15 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-mono font-bold tracking-wider uppercase text-amber-300">
                    <i class="fa-solid fa-calculator text-xs"></i>
                    <span>Live Pricing Simulator</span>
                </div>
                <h3 class="text-lg sm:text-xl font-extrabold tracking-tight">How much will a hotel pay?</h3>
                <p class="text-xs text-purple-200/80 leading-relaxed">
                    Slide or type the number of Connected TVs in a hotel property to simulate the exact invoice totals for each plan in real-time.
                </p>
            </div>

            <!-- TV Count Controller -->
            <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-4 shrink-0">
                <div>
                    <label class="block text-[11px] font-bold text-purple-200 uppercase tracking-wider mb-1">Connected TVs</label>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="adjustSimTvs(-5)" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 active:scale-95 flex items-center justify-center font-bold text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <input type="number" id="sim-tv-input" min="1" max="1000" value="30" oninput="updateLiveCalculations()" class="w-24 text-center py-1.5 px-2 bg-white text-[#150A42] font-mono font-black text-lg rounded-xl focus:outline-none shadow-inner">
                        <button type="button" onclick="adjustSimTvs(5)" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 active:scale-95 flex items-center justify-center font-bold text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center sm:text-left border-t sm:border-t-0 sm:border-l border-white/15 pt-3 sm:pt-0 sm:pl-4">
                    <span class="text-[10px] text-purple-200/70 font-semibold block uppercase">Quick Select</span>
                    <div class="flex items-center gap-1.5 mt-1">
                        <button type="button" onclick="setSimTvs(15)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold font-mono">15</button>
                        <button type="button" onclick="setSimTvs(30)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold font-mono">30</button>
                        <button type="button" onclick="setSimTvs(50)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold font-mono">50</button>
                        <button type="button" onclick="setSimTvs(100)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-bold font-mono">100</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Responsive Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @forelse($plans as $plan)
            @php
                $cycle = $plan->billing_cycle ?? 'monthly';
                $rate = (float) $plan->effective_price_per_tv;
                $tax = $plan->tax;
                $taxType = $plan->tax_type ?? 'exclusive';
            @endphp
            <div id="plan-card-{{ $plan->id }}" 
                 data-rate="{{ $rate }}" 
                 data-cycle="{{ $cycle }}"
                 data-tax-rate="{{ $tax ? $tax->rate : 0 }}"
                 data-tax-type="{{ $taxType }}"
                 class="bg-white border border-slate-200/90 rounded-[28px] p-6 shadow-xs hover:shadow-xl hover:border-purple-300 transition-all duration-200 flex flex-col justify-between space-y-6 relative overflow-hidden {{ !$plan->status ? 'opacity-60 bg-slate-50/50' : '' }}">
                
                <!-- Billing Cycle Accent Ribbon -->
                <div class="absolute top-0 right-0">
                    @if($cycle === 'yearly')
                        <span class="inline-block px-4 py-1 rounded-bl-2xl bg-amber-500 text-slate-900 font-extrabold text-[10px] uppercase font-mono tracking-wider shadow-xs">
                            <i class="fa-solid fa-crown mr-1 text-[9px]"></i> Yearly Plan
                        </span>
                    @else
                        <span class="inline-block px-4 py-1 rounded-bl-2xl bg-purple-100 text-[#150A42] font-extrabold text-[10px] uppercase font-mono tracking-wider">
                            <i class="fa-solid fa-calendar-days mr-1 text-[9px]"></i> Monthly Plan
                        </span>
                    @endif
                </div>

                <div class="space-y-4 pt-2">
                    <!-- Title & Plan Info -->
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight font-sans">{{ $plan->name }}</h3>
                            @if($plan->status)
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0" title="Active Plan"></span>
                            @else
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300 shrink-0" title="Disabled Plan"></span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            {{ $plan->description ?? 'Smart Hotel TV launcher subscription per connected display.' }}
                        </p>
                    </div>

                    <!-- Per-TV Price in Space Mono -->
                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-slate-200/80 space-y-1">
                        <div class="flex items-baseline space-x-1">
                            <span class="text-3xl sm:text-4xl font-black text-[#150A42] font-mono tracking-tight leading-none">
                                ₹{{ number_format($rate, 0) }}
                            </span>
                            <span class="text-xs text-slate-500 font-sans font-semibold">/ TV / {{ $cycle === 'yearly' ? 'year' : 'month' }}</span>
                        </div>
                        
                        <!-- Tax Badge -->
                        <div class="pt-1 flex items-center space-x-2">
                            @if($tax && $tax->rate > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold font-mono {{ $taxType === 'inclusive' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    <i class="fa-solid fa-shield-halved mr-1 text-[9px]"></i>
                                    {{ $tax->name }} ({{ number_format($tax->rate, 0) }}% {{ ucfirst($taxType) }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold font-mono bg-slate-100 text-slate-600">
                                    No Tax Applied
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Dynamic Live Cost Card for this Plan -->
                    <div class="p-3.5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Simulated for <span class="sim-tv-display font-mono text-[#150A42] font-black">30</span> TVs:</span>
                            <span class="plan-subtotal-display font-mono font-bold text-slate-800">₹0</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500">
                            <span>Tax Breakdown:</span>
                            <span class="plan-tax-display font-mono text-slate-600">₹0</span>
                        </div>
                        <div class="pt-1.5 border-t border-purple-200/60 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-tight">Total Payable:</span>
                            <span class="plan-total-display text-base font-black text-[#150A42] font-mono">₹0</span>
                        </div>
                    </div>

                    <!-- OTT Platforms Pill -->
                    @if(!empty($plan->ott_platforms))
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-slate-100 text-slate-700 font-bold text-[11px] font-mono">
                                <i class="fa-solid fa-tv mr-1.5 text-xs text-purple-600"></i> {{ count($plan->ott_platforms) }} Apps Authorized
                            </span>
                        </div>
                    @endif
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
                    <div class="inline-flex items-center space-x-1.5">
                        <a href="{{ route('super-admin.plans.edit', $plan->id) }}" class="w-8 h-8 rounded-xl border border-slate-200 text-slate-600 hover:text-[#150A42] hover:bg-purple-50 hover:border-purple-200 flex items-center justify-center transition-all shadow-xs" title="Edit Plan">
                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                        </a>
                        <form action="{{ route('super-admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this pricing tier?');" class="inline">
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
                <h3 class="text-sm font-extrabold text-slate-800">No Per-TV Pricing Plans Created Yet</h3>
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
    function adjustSimTvs(delta) {
        const input = document.getElementById('sim-tv-input');
        let current = parseInt(input.value) || 1;
        current = Math.max(1, Math.min(1000, current + delta));
        input.value = current;
        updateLiveCalculations();
    }

    function setSimTvs(val) {
        const input = document.getElementById('sim-tv-input');
        input.value = val;
        updateLiveCalculations();
    }

    function updateLiveCalculations() {
        const input = document.getElementById('sim-tv-input');
        const count = Math.max(1, parseInt(input.value) || 1);

        document.querySelectorAll('.sim-tv-display').forEach(el => el.textContent = count);

        document.querySelectorAll('[id^="plan-card-"]').forEach(card => {
            const rate = parseFloat(card.getAttribute('data-rate')) || 0;
            const taxRate = parseFloat(card.getAttribute('data-tax-rate')) || 0;
            const taxType = card.getAttribute('data-tax-type') || 'exclusive';

            let subtotal = 0;
            let taxAmount = 0;
            let total = 0;

            if (taxType === 'inclusive') {
                total = Math.round(count * rate * 100) / 100;
                subtotal = Math.round((total / (1 + (taxRate / 100))) * 100) / 100;
                taxAmount = Math.round((total - subtotal) * 100) / 100;
            } else {
                subtotal = Math.round(count * rate * 100) / 100;
                taxAmount = Math.round((subtotal * (taxRate / 100)) * 100) / 100;
                total = Math.round((subtotal + taxAmount) * 100) / 100;
            }

            const subtotalEl = card.querySelector('.plan-subtotal-display');
            const taxEl = card.querySelector('.plan-tax-display');
            const totalEl = card.querySelector('.plan-total-display');

            if (subtotalEl) subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
            if (taxEl) taxEl.textContent = (taxRate > 0 ? ('+₹' + taxAmount.toLocaleString('en-IN') + ' (' + taxType + ')') : '₹0');
            if (totalEl) totalEl.textContent = '₹' + total.toLocaleString('en-IN');
        });
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', updateLiveCalculations);

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
