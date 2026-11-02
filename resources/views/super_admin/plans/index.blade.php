@extends('layouts.super_admin')

@section('title', 'Subscription Plans - Super Admin')
@section('page_title', 'Subscription Plans')

@section('content')
<div class="space-y-6">
    <!-- Header & Action CTA -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Subscription & Pricing Tiers</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Structure hotel subscription packages, smart TV room caps, and bundled OTT platforms.</p>
        </div>
        <a href="{{ route('super-admin.plans.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/25 transition-all hover:-translate-y-0.5 space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Pricing Tier</span>
        </a>
    </div>

    <!-- Plans Responsive Grid (1-col on mobile, 2-col on tablet, 3-col on desktop) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($plans as $plan)
            <div id="plan-card-{{ $plan->id }}" class="bg-white border border-slate-200/90 rounded-[26px] p-6 shadow-xs hover:shadow-xl hover:border-purple-300 transition-all duration-200 flex flex-col justify-between space-y-6 {{ !$plan->status ? 'opacity-60 bg-slate-50/50' : '' }}">
                <div class="space-y-4">
                    <!-- Title & Limit Pill -->
                    <div class="border-b border-slate-100 pb-4 flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight font-sans">{{ $plan->name }}</h3>
                            <span class="inline-flex items-center mt-1.5 px-2.5 py-0.5 rounded-full bg-purple-50 text-[#150A42] font-extrabold text-[10px] border border-purple-100 uppercase font-mono tracking-wider">
                                <i class="fa-solid fa-hotel mr-1 text-[9px]"></i> Up to {{ $plan->room_count }} Rooms/TVs
                            </span>
                        </div>
                        @if($plan->status)
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 mt-2" title="Active Plan"></span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300 shrink-0 mt-2" title="Disabled Plan"></span>
                        @endif
                    </div>

                    <!-- Price in Space Mono -->
                    <div class="pt-1">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#150A42] font-mono tracking-tight leading-none">
                            ₹{{ number_format($plan->price, 0) }}<span class="text-xs text-slate-400 font-sans font-medium ml-1">/ month</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-500 leading-relaxed font-medium min-h-[36px]">
                        {{ $plan->description ?? 'Standard hotel TV launcher licensing plan with streaming apps and management dashboard.' }}
                    </p>

                    <!-- OTT Platforms Pill -->
                    @if(!empty($plan->ott_platforms))
                        <div class="pt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-purple-50/80 text-purple-700 font-bold text-[11px] border border-purple-100 font-mono">
                                <i class="fa-solid fa-tv mr-1.5 text-xs"></i> {{ count($plan->ott_platforms) }} OTT Apps Bundled
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
                        <form action="{{ route('super-admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this pricing plan?');" class="inline">
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
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Create subscription packages with custom room limits and pricing tiers to onboard hotel clients.</p>
                <a href="{{ route('super-admin.plans.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create First Plan</span>
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
