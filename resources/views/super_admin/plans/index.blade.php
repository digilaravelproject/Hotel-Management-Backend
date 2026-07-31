@extends('layouts.super_admin')

@section('title', 'Manage Plans - Super Admin')
@section('page_title', 'Subscription Plans Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 font-medium">Define, structure, and toggle licensing pricing plans for hotel smart TVs.</p>
        </div>
        <a href="{{ route('super-admin.plans.create') }}" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Pricing Plan</span>
        </a>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plans as $plan)
            <div id="plan-card-{{ $plan->id }}" class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-rose-500/40 transition-all flex flex-col justify-between space-y-6 {{ !$plan->status ? 'opacity-60' : '' }}">
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $plan->name }}</h3>
                        <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px] border border-rose-200 uppercase">
                            Up to {{ $plan->room_count }} Rooms/TVs
                        </span>
                    </div>

                    <div class="text-3xl font-extrabold text-rose-600 tracking-tight">
                        ₹{{ number_format($plan->price, 0) }}<span class="text-xs text-slate-400 font-normal">/month</span>
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        {{ $plan->description ?? 'Standard system licensing plan with dashboard access.' }}
                    </p>

                    @if(!empty($plan->ott_platforms))
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-sky-50 text-sky-700 font-bold text-[11px] border border-sky-200">
                                <i class="fa-solid fa-tv mr-1 text-[10px]"></i> {{ count($plan->ott_platforms) }} OTT Apps Included
                            </span>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="togglePlanStatus({{ $plan->id }})" {{ $plan->status ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-600"></div>
                        </label>
                        <span class="text-xs font-bold text-slate-600">Active</span>
                    </div>

                    <div class="inline-flex items-center space-x-1.5">
                        <a href="{{ route('super-admin.plans.edit', $plan->id) }}" class="p-2 rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit plan text">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('super-admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Delete this pricing plan?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors" title="Delete plan">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white border border-slate-200/80 rounded-3xl text-slate-400 space-y-2">
                <i class="fa-solid fa-tags text-4xl text-slate-300 block"></i>
                <p class="text-xs font-medium">No subscription plans created yet. Click 'Add New Pricing Plan' to start.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePlanStatus(id) {
        const card = document.getElementById(`plan-card-${id}`);
        fetch(`/super-admin/plans/${id}/toggle-status`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && card) {
                card.classList.toggle('opacity-60', !data.status);
            }
        });
    }
</script>
@endsection
