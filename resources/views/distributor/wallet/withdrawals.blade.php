@extends('layouts.distributor')

@section('title', 'Payout Requests - Distributor Hub')
@section('page_title', 'Withdrawal Requests History')

@section('content')
<div class="space-y-8">

    <!-- Top Navigation Header -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-money-bill-transfer text-[11px] text-purple-600"></i>
                <span>Payout Settlement Trail</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Withdrawal Requests & Settlements</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium leading-relaxed">
                Check the real-time status of your payouts, bank UTR reference numbers provided by administration, and processing remarks.
            </p>
        </div>

        <div class="flex items-center space-x-3 relative z-10 shrink-0">
            <a href="{{ route('distributor.wallet.index') }}" class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Wallet</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('distributor.wallet.withdrawals') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ !request()->filled('status') ? 'bg-[#150A42] text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    All Requests
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Pending Review
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Approved & Settled
                </a>
                <a href="{{ route('distributor.wallet.withdrawals', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95 {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    Rejected
                </a>
            </div>
            <div class="text-xs text-slate-400 font-bold">
                Showing {{ $withdrawals->total() }} withdrawal records
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs text-slate-800">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Request ID</th>
                        <th class="px-6 py-4">Requested On</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Payout Method & Details</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Transaction UTR / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $item)
                        <tr class="hover:bg-purple-50/20 transition-colors group">
                            <td class="px-6 py-4 font-mono font-bold text-slate-600">
                                <div class="inline-flex items-center space-x-1.5">
                                    <span>#WTH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <button type="button" 
                                            onclick="copyText('#WTH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}')" 
                                            title="Copy Request ID" 
                                            class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-purple-700 transition-opacity cursor-pointer">
                                        <i class="fa-regular fa-copy text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium">
                                <div class="font-black text-slate-900">{{ $item->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $item->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-900">₹{{ number_format($item->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg bg-slate-100 border border-slate-200/80 text-slate-700 font-black text-[10px] uppercase mb-1">
                                    <i class="fa-solid {{ str_contains(strtolower($item->payment_method), 'upi') ? 'fa-mobile-screen-button text-purple-600' : 'fa-building-columns text-slate-500' }} text-[9px]"></i>
                                    <span>{{ str_replace('_', ' ', $item->payment_method ?? 'Bank') }}</span>
                                </span>
                                <div class="text-[11px] text-slate-600 font-mono break-words leading-tight">
                                    {{ $item->account_details }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Under Review
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                                        <i class="fa-solid fa-circle-check text-[10px] mr-1.5 text-emerald-600"></i> Settled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-800 border border-rose-200/80 shadow-2xs">
                                        <i class="fa-solid fa-circle-xmark text-[10px] mr-1.5 text-rose-600"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->transaction_number)
                                    <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl bg-emerald-50/80 border border-emerald-200/70 text-emerald-800 text-[10px] font-mono font-bold">
                                        <i class="fa-solid fa-receipt text-[9px] text-emerald-600"></i>
                                        <span>UTR: {{ $item->transaction_number }}</span>
                                        <button type="button" 
                                                onclick="copyText('{{ $item->transaction_number }}')" 
                                                title="Copy UTR Reference" 
                                                class="ml-1 text-emerald-600 hover:text-emerald-950 cursor-pointer">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                @endif
                                @if($item->admin_notes)
                                    <div class="text-[11px] text-slate-500 italic mt-1 bg-slate-50 border border-slate-200/60 rounded-xl px-2.5 py-1">
                                        <span class="font-bold text-slate-600 not-italic">Admin:</span> "{{ $item->admin_notes }}"
                                    </div>
                                @endif
                                @if(!$item->transaction_number && !$item->admin_notes)
                                    <span class="text-slate-400 font-mono">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <div class="w-14 h-14 rounded-3xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                    </div>
                                    <p class="font-black text-slate-900 text-sm">No payout requests found</p>
                                    <p class="text-xs text-slate-400">No withdrawal records match the selected status.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Floating Copy Notification Pill -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 bg-slate-900/95 text-white px-4 py-2.5 rounded-2xl shadow-xl text-xs font-bold flex items-center space-x-2 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="copyToastText">Copied to clipboard!</span>
</div>

<script>
    function copyText(text) {
        if (!navigator.clipboard) {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showCopyToast(text);
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            showCopyToast(text);
        }).catch(() => {
            showCopyToast(text);
        });
    }

    function showCopyToast(text) {
        const toast = document.getElementById('copyToast');
        const toastText = document.getElementById('copyToastText');
        toastText.textContent = `Copied: ${text}`;
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }, 2200);
    }
</script>
@endsection
