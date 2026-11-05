@extends('layouts.distributor')

@section('title', 'My Digital Wallet - Distributor Hub')
@section('page_title', 'Digital Wallet & Earnings')

@section('content')
<div class="space-y-8">

    <!-- Top Wallet Header Card (Signature Luxury Midnight Navy & Purple Theme) -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-purple-100/50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-12 w-48 h-48 bg-purple-50/40 rounded-full blur-2xl pointer-events-none"></div>

        <div class="space-y-2.5 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-wallet text-[11px] text-purple-600"></i>
                <span>Distributor Revenue Wallet</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse ml-1 inline-block"></span>
                <span class="text-[10px] text-emerald-700 font-black">Live Vault</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Financial Hub & Payouts</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl font-medium leading-relaxed">
                Track real-time commissions earned across your hotel client portfolio, review your double-entry transaction ledger, and initiate fast withdrawal requests directly to your verified bank or UPI.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 shrink-0">
            <button type="button" 
                    onclick="openWithdrawModal()" 
                    class="px-5 sm:px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 hover:shadow-xl hover:shadow-[#150A42]/25 transition-all flex items-center space-x-2.5 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-paper-plane text-xs text-amber-300"></i>
                <span>Request Payout</span>
            </button>
            <a href="{{ route('distributor.wallet.transactions') }}" 
               class="px-4 sm:px-5 py-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/90 text-slate-700 border border-slate-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-receipt text-xs text-purple-600"></i>
                <span>Full Ledger</span>
            </a>
            <a href="{{ route('distributor.wallet.withdrawals') }}" 
               class="px-4 sm:px-5 py-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/90 text-slate-700 border border-slate-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-clock-rotate-left text-xs text-indigo-600"></i>
                <span>Payout Trail</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-5 rounded-[24px] bg-rose-50/90 border border-rose-200 text-rose-800 text-xs space-y-1.5 shadow-2xs animate-in fade-in">
            <div class="font-black flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1 font-medium text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 4 Metrics Cards: Modern High-Impact Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Available Balance (Hero Card in Midnight Purple) -->
        <div class="bg-gradient-to-br from-[#150A42] via-[#1d0e57] to-[#2B1270] text-white rounded-[28px] p-6 shadow-xl shadow-[#150A42]/15 border border-purple-900/40 relative overflow-hidden group flex flex-col justify-between">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-purple-200/70">Available Balance</span>
                    <span class="w-9 h-9 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-sm text-amber-300">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                </div>
                <div class="text-3xl font-black text-white mt-2 tracking-tight">
                    ₹{{ number_format($metrics['available_balance'], 2) }}
                </div>
            </div>

            <div class="mt-4 pt-3.5 border-t border-white/10 flex items-center justify-between text-xs">
                @if($metrics['locked_balance'] > 0)
                    <div class="inline-flex items-center space-x-1.5 text-amber-300 font-bold text-[11px]">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                        <span>₹{{ number_format($metrics['locked_balance'], 2) }} pending review</span>
                    </div>
                @else
                    <div class="inline-flex items-center space-x-1.5 text-emerald-400 font-bold text-[11px]">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        <span>100% ready for payout</span>
                    </div>
                @endif
                <button type="button" 
                        onclick="openWithdrawModal()" 
                        class="text-[11px] font-black text-white hover:text-amber-300 underline underline-offset-2 transition-colors cursor-pointer">
                    Withdraw ↗
                </button>
            </div>
        </div>

        <!-- Card 2: Lifetime Earned Commission -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Lifetime Earnings</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-sm shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600 mt-2 tracking-tight">
                    ₹{{ number_format($metrics['total_earned'], 2) }}
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-semibold">Cumulative hotel sales</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px] border border-emerald-100">
                    Gross Inflow
                </span>
            </div>
        </div>

        <!-- Card 3: Total Settled / Disbursed -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Settled</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-2 tracking-tight">
                    ₹{{ number_format($metrics['total_withdrawn'], 2) }}
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-semibold">Transferred to Bank/UPI</span>
                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-100">
                    Settled
                </span>
            </div>
        </div>

        <!-- Card 4: Active Commission Tier -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Your Commission Tier</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-100 text-purple-700 flex items-center justify-center text-sm shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-percent"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-purple-700 mt-2 tracking-tight">
                    {{ number_format($metrics['commission_rate'], 2) }}%
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="font-bold {{ $metrics['is_override'] ? 'text-purple-700' : 'text-slate-500' }}">
                    {{ $metrics['is_override'] ? 'VIP Partner Override' : 'Network Standard' }}
                </span>
                <span class="text-[10px] text-slate-400 font-medium">Per Renewal/Sale</span>
            </div>
        </div>

    </div>

    <!-- Payout SLA & Info Ribbon -->
    <div class="bg-gradient-to-r from-purple-50/70 via-indigo-50/50 to-purple-50/70 rounded-[24px] p-4 sm:p-5 border border-purple-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#150A42] text-white flex items-center justify-center text-sm shrink-0 shadow-xs">
                <i class="fa-solid fa-shield-halved text-amber-300"></i>
            </div>
            <div class="text-xs">
                <div class="font-black text-slate-900">Direct Payout Guarantee & SLA</div>
                <div class="text-slate-500 font-medium mt-0.5">
                    Payout requests are reviewed and settled via IMPS / NEFT / UPI within 24-48 business hours with official bank UTR reference. Zero processing deductions.
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3 shrink-0">
            <button type="button" 
                    onclick="openWithdrawModal()" 
                    class="px-4 py-2 rounded-xl bg-white hover:bg-purple-100/80 border border-purple-200 text-[#150A42] font-black text-xs transition-all active:scale-95 cursor-pointer shadow-2xs flex items-center space-x-1.5">
                <i class="fa-solid fa-bolt text-amber-500 text-[11px]"></i>
                <span>Instant Withdrawal</span>
            </button>
        </div>
    </div>

    <!-- 2 Column Section: Recent Ledger & Withdrawal Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        <!-- Column 1: Recent Wallet Transactions Ledger -->
        <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Card Header with Filter Pills -->
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-black text-slate-900 text-base">Recent Wallet Activity</h3>
                            <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 font-mono font-bold text-[10px] border border-purple-100">
                                {{ $recentTransactions->count() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Real-time credit & debit ledger entries</p>
                    </div>

                    <div class="flex items-center space-x-1.5">
                        <button type="button" 
                                onclick="filterTransactions('all')" 
                                id="btnFilterAll" 
                                class="px-3 py-1 rounded-xl text-[11px] font-black bg-[#150A42] text-white transition-all cursor-pointer shadow-2xs">
                            All
                        </button>
                        <button type="button" 
                                onclick="filterTransactions('credit')" 
                                id="btnFilterCredit" 
                                class="px-3 py-1 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-200/60 transition-all cursor-pointer">
                            Credits (+)
                        </button>
                        <button type="button" 
                                onclick="filterTransactions('debit')" 
                                id="btnFilterDebit" 
                                class="px-3 py-1 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-200/60 transition-all cursor-pointer">
                            Debits (-)
                        </button>
                    </div>
                </div>

                <!-- Transaction List -->
                <div class="divide-y divide-slate-100" id="transactionsContainer">
                    @forelse($recentTransactions as $tx)
                        <div class="tx-item p-4 sm:p-5 flex items-center justify-between hover:bg-purple-50/20 transition-colors group" data-type="{{ $tx->type }}">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    <i class="fa-solid {{ $tx->type === 'credit' ? 'fa-arrow-down-left' : 'fa-arrow-up-right' }} text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-xs font-black text-slate-900 truncate">{{ $tx->description }}</h4>
                                        <button type="button" 
                                                onclick="copyText('#TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}')" 
                                                title="Copy ID" 
                                                class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-400 hover:text-purple-700 text-[10px] cursor-pointer">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            {{ $tx->created_at->format('M d, Y • h:i A') }}
                                        </span>
                                        <span class="text-[10px] text-slate-300">•</span>
                                        <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.2 rounded-md">
                                            Bal: ₹{{ number_format($tx->balance_after, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0 pl-3">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                    #TXN-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400 text-xs">
                            <div class="w-14 h-14 rounded-3xl bg-purple-50/70 border border-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-3 text-xl shadow-2xs">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <p class="font-black text-slate-800 text-sm">No transactions recorded yet</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                                Once you sell subscription packages or onboard hotels, your earned commissions will appear here.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('distributor.sales.create') }}" class="px-4 py-2 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs inline-flex items-center space-x-2 transition-all active:scale-95 shadow-xs">
                                    <i class="fa-solid fa-plus-circle text-[11px]"></i>
                                    <span>Sell First Package</span>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Link -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('distributor.sales.create') }}" class="font-extrabold text-purple-700 hover:text-purple-900 inline-flex items-center space-x-1.5 transition-colors">
                    <i class="fa-solid fa-circle-plus text-[11px]"></i>
                    <span>Sell package to earn commission</span>
                </a>
                <a href="{{ route('distributor.wallet.transactions') }}" class="font-black text-slate-500 hover:text-slate-900 inline-flex items-center space-x-1 transition-colors">
                    <span>Full Ledger</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Column 2: Recent Withdrawal Requests -->
        <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Card Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-black text-slate-900 text-base">Payout Requests History</h3>
                            <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 font-mono font-bold text-[10px] border border-purple-100">
                                {{ $recentWithdrawals->count() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Withdrawal settlement status & UTR references</p>
                    </div>
                    <a href="{{ route('distributor.wallet.withdrawals') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 flex items-center space-x-1 transition-colors">
                        <span>View All</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Withdrawal List -->
                <div class="divide-y divide-slate-100">
                    @forelse($recentWithdrawals as $req)
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-purple-50/20 transition-colors group">
                            <div class="space-y-1.5 min-w-0 flex-1">
                                <div class="flex items-center space-x-2.5">
                                    <span class="text-sm font-black text-slate-900">₹{{ number_format($req->amount, 2) }}</span>
                                    
                                    @if($req->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200/80 inline-flex items-center shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 inline-block animate-pulse"></span>
                                            Under Review
                                        </span>
                                    @elseif($req->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200/80 inline-flex items-center shadow-2xs">
                                            <i class="fa-solid fa-circle-check text-[9px] mr-1.5 text-emerald-600"></i>
                                            Settled & Paid
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-800 border border-rose-200/80 inline-flex items-center shadow-2xs">
                                            <i class="fa-solid fa-circle-xmark text-[9px] mr-1.5 text-rose-600"></i>
                                            Rejected
                                        </span>
                                    @endif

                                    <span class="text-[10px] font-mono text-slate-400">
                                        #WTH-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-2 text-[11px] text-slate-600 font-mono">
                                    <i class="fa-solid {{ str_contains(strtolower($req->payment_method), 'upi') ? 'fa-mobile-screen-button text-purple-600' : 'fa-building-columns text-slate-500' }} text-[10px]"></i>
                                    <span class="truncate max-w-sm">{{ $req->account_details }}</span>
                                </div>

                                @if($req->transaction_number)
                                    <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl bg-emerald-50/80 border border-emerald-200/70 text-emerald-800 text-[10px] font-mono font-bold">
                                        <i class="fa-solid fa-receipt text-[9px] text-emerald-600"></i>
                                        <span>Bank UTR: {{ $req->transaction_number }}</span>
                                        <button type="button" 
                                                onclick="copyText('{{ $req->transaction_number }}')" 
                                                title="Copy UTR Reference" 
                                                class="ml-1 text-emerald-600 hover:text-emerald-950 cursor-pointer">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                @endif

                                @if($req->admin_notes)
                                    <div class="text-[11px] text-slate-500 bg-slate-50 border border-slate-200/60 rounded-xl px-2.5 py-1 italic max-w-md">
                                        <span class="font-bold text-slate-600 not-italic">Admin Remark:</span> "{{ $req->admin_notes }}"
                                    </div>
                                @endif
                            </div>

                            <div class="text-right text-[10px] text-slate-400 font-medium shrink-0">
                                <div class="font-bold text-slate-700">{{ $req->created_at->format('M d, Y') }}</div>
                                <div class="font-mono">{{ $req->created_at->format('h:i A') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400 text-xs">
                            <div class="w-14 h-14 rounded-3xl bg-purple-50/70 border border-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-3 text-xl shadow-2xs">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <p class="font-black text-slate-800 text-sm">No payout requests placed yet</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                                You can request a payout anytime directly to your registered bank account or instant UPI VPA.
                            </p>
                            <div class="mt-4">
                                <button type="button" 
                                        onclick="openWithdrawModal()" 
                                        class="px-4 py-2 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs inline-flex items-center space-x-2 transition-all active:scale-95 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-arrow-up-from-bracket text-[11px]"></i>
                                    <span>Request Payout Now</span>
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <button type="button" 
                        onclick="openWithdrawModal()" 
                        class="font-black text-purple-700 hover:text-purple-900 inline-flex items-center space-x-1.5 transition-colors cursor-pointer">
                    <i class="fa-solid fa-plus-circle text-[11px]"></i>
                    <span>Submit new payout request</span>
                </button>
                <a href="{{ route('distributor.wallet.withdrawals') }}" class="font-black text-slate-500 hover:text-slate-900 inline-flex items-center space-x-1 transition-colors">
                    <span>Full History</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Interactive Modal: Request Withdrawal (Ultra-Clean, Step-Aware & Verified UX) -->
<div id="withdrawModal" class="fixed inset-0 z-50 bg-[#0B0424]/75 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-150">
        <form method="POST" action="{{ route('distributor.wallet.withdraw') }}" id="withdrawForm" onsubmit="return validateWithdrawForm(event)" class="space-y-0">
            @csrf

            <!-- Modal Header -->
            <div class="p-6 bg-white border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-purple-500 to-[#150A42] text-white flex items-center justify-center text-base font-black shadow-md shadow-[#150A42]/15 shrink-0">
                        <i class="fa-solid fa-paper-plane text-amber-300"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Request Payout from Wallet</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Direct disbursement to your Bank Account or UPI VPA</p>
                    </div>
                </div>
                <button type="button" onclick="closeWithdrawModal()" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[78vh] overflow-y-auto">

                <!-- Spendable Balance Card with Quick Amount Chips -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-[#150A42] to-[#2B1270] text-white shadow-lg shadow-[#150A42]/10 space-y-3 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-purple-200 uppercase tracking-wider block">Spendable Balance</span>
                            <span class="text-2xl sm:text-3xl font-black text-white" id="availableBalanceDisplay">₹{{ number_format($metrics['available_balance'], 2) }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-black font-mono">
                            Zero Deduction
                        </span>
                    </div>

                    <!-- Quick Amount Chips -->
                    <div class="pt-2 border-t border-white/10 flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] text-purple-200/70 font-bold mr-1">Quick Add:</span>
                        @php
                            $avail = (float) $metrics['available_balance'];
                        @endphp
                        @if($avail >= 500)
                            <button type="button" onclick="setWithdrawAmount(500)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-mono font-bold text-[10px] transition-all cursor-pointer">
                                ₹500
                            </button>
                        @endif
                        @if($avail >= 1000)
                            <button type="button" onclick="setWithdrawAmount(1000)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-mono font-bold text-[10px] transition-all cursor-pointer">
                                ₹1,000
                            </button>
                        @endif
                        @if($avail >= 5000)
                            <button type="button" onclick="setWithdrawAmount(5000)" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-mono font-bold text-[10px] transition-all cursor-pointer">
                                ₹5,000
                            </button>
                        @endif
                        @if($avail >= 100)
                            <button type="button" onclick="setWithdrawAmount({{ round($avail * 0.5, 2) }})" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-mono font-bold text-[10px] transition-all cursor-pointer">
                                50%
                            </button>
                            <button type="button" onclick="setWithdrawAmount({{ $avail }})" class="px-3 py-1 rounded-lg bg-amber-400 hover:bg-amber-300 text-slate-950 font-mono font-black text-[10px] transition-all cursor-pointer ml-auto shadow-xs">
                                Max All (₹{{ number_format($avail, 0) }})
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="space-y-1.5">
                    <label for="amountInput" class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                        Withdrawal Amount (₹) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-lg pointer-events-none">₹</span>
                        <input type="number" 
                               step="0.01" 
                               min="100" 
                               max="{{ $metrics['available_balance'] }}" 
                               name="amount" 
                               id="amountInput" 
                               required 
                               placeholder="e.g. 5000" 
                               oninput="handleAmountInput(this.value)"
                               class="w-full pl-9 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-2xl text-base font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] outline-none transition-all">
                    </div>

                    <!-- Live Calculation & Validation Alert -->
                    <div id="balanceFeedback" class="text-[11px] font-semibold text-slate-400 flex items-center justify-between pt-1">
                        <span>Min payout: ₹100</span>
                        <span id="remainingBalanceText" class="font-mono text-slate-600">Remaining: ₹{{ number_format($metrics['available_balance'], 2) }}</span>
                    </div>
                </div>

                <!-- Payout Method Selector -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                        Select Payout Channel <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label id="bankTabLabel" 
                               onclick="selectPayoutMethod('bank')"
                               class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-[#150A42] bg-purple-50/40 cursor-pointer transition-all shadow-2xs">
                            <input type="radio" name="payment_method" value="bank_transfer" checked class="sr-only">
                            <div class="w-8 h-8 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black text-slate-900 block">Bank Transfer</span>
                                <span class="text-[10px] text-slate-400 font-medium">NEFT / RTGS / IMPS</span>
                            </div>
                        </label>

                        <label id="upiTabLabel" 
                               onclick="selectPayoutMethod('upi')"
                               class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all shadow-2xs">
                            <input type="radio" name="payment_method" value="upi" class="sr-only">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black text-slate-900 block">Instant UPI VPA</span>
                                <span class="text-[10px] text-slate-400 font-medium">GPay / PhonePe / Paytm</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Bank Fields Container -->
                <div id="bankFields" class="space-y-3 pt-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Holder Name *</label>
                            <input type="text" 
                                   name="account_holder_name" 
                                   id="accountHolderName" 
                                   value="{{ old('account_holder_name', auth()->user()->name) }}" 
                                   placeholder="As per bank passbook" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Bank Name *</label>
                            <input type="text" 
                                   name="bank_name" 
                                   id="bankName" 
                                   value="{{ old('bank_name') }}" 
                                   placeholder="e.g. HDFC Bank, SBI, ICICI" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Number *</label>
                            <input type="text" 
                                   name="account_number" 
                                   id="accountNumber" 
                                   value="{{ old('account_number') }}" 
                                   placeholder="Enter account number" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">IFSC Code *</label>
                            <input type="text" 
                                   name="ifsc_code" 
                                   id="ifscCode" 
                                   value="{{ old('ifsc_code') }}" 
                                   placeholder="e.g. HDFC0001234" 
                                   oninput="this.value = this.value.toUpperCase()" 
                                   maxlength="11"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs uppercase font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                    </div>
                </div>

                <!-- UPI Fields Container (Hidden by default) -->
                <div id="upiFields" class="space-y-3 pt-1 hidden">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">UPI ID / Virtual Payment Address (VPA) *</label>
                        <input type="text" 
                               name="upi_id" 
                               id="upiIdInput" 
                               value="{{ old('upi_id') }}" 
                               placeholder="e.g. yourname@okhdfcbank or 9876543210@paytm" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                    </div>
                    <!-- Quick Handle Chips -->
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[10px] text-slate-400 font-bold mr-1">Common handles:</span>
                        <button type="button" onclick="appendUpiSuffix('@okhdfcbank')" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-mono cursor-pointer">@okhdfcbank</button>
                        <button type="button" onclick="appendUpiSuffix('@okaxis')" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-mono cursor-pointer">@okaxis</button>
                        <button type="button" onclick="appendUpiSuffix('@okicici')" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-mono cursor-pointer">@okicici</button>
                        <button type="button" onclick="appendUpiSuffix('@paytm')" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-mono cursor-pointer">@paytm</button>
                        <button type="button" onclick="appendUpiSuffix('@ybl')" class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-purple-100 text-slate-700 text-[10px] font-mono cursor-pointer">@ybl</button>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="closeWithdrawModal()" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition-all active:scale-95 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" 
                        id="submitWithdrawBtn"
                        @if($metrics['available_balance'] < 100) disabled @endif 
                        class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer flex items-center space-x-2">
                    <i class="fa-solid fa-check text-xs text-amber-300"></i>
                    <span id="submitWithdrawBtnText">Submit Payout Request</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Floating Copy Notification Pill -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 bg-slate-900/95 text-white px-4 py-2.5 rounded-2xl shadow-xl text-xs font-bold flex items-center space-x-2 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="copyToastText">Copied to clipboard!</span>
</div>

<script>
    const availableBalance = {{ (float) $metrics['available_balance'] }};
    let currentMethod = 'bank';

    function openWithdrawModal() {
        const modal = document.getElementById('withdrawModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeWithdrawModal() {
        const modal = document.getElementById('withdrawModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeWithdrawModal();
        }
    });

    // Close on click outside
    document.getElementById('withdrawModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeWithdrawModal();
        }
    });

    function setWithdrawAmount(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return;
        const input = document.getElementById('amountInput');
        input.value = num.toFixed(2);
        handleAmountInput(input.value);
    }

    function handleAmountInput(val) {
        const num = parseFloat(val) || 0;
        const remaining = Math.max(0, availableBalance - num);
        const remainingText = document.getElementById('remainingBalanceText');
        const submitBtn = document.getElementById('submitWithdrawBtn');
        const submitBtnText = document.getElementById('submitWithdrawBtnText');

        if (num > availableBalance) {
            remainingText.innerHTML = `<span class="text-rose-600 font-bold">Exceeds available (₹${availableBalance.toFixed(2)})</span>`;
            submitBtn.disabled = true;
        } else if (num > 0 && num < 100) {
            remainingText.innerHTML = `<span class="text-amber-600 font-bold">Min amount is ₹100.00</span>`;
            submitBtn.disabled = true;
        } else {
            remainingText.innerHTML = `Remaining: ₹${remaining.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            submitBtn.disabled = (num <= 0);
        }

        if (num >= 100 && num <= availableBalance) {
            submitBtnText.textContent = `Withdraw ₹${num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        } else {
            submitBtnText.textContent = 'Submit Payout Request';
        }
    }

    function selectPayoutMethod(type) {
        currentMethod = type;
        const bankTab = document.getElementById('bankTabLabel');
        const upiTab = document.getElementById('upiTabLabel');
        const bankFields = document.getElementById('bankFields');
        const upiFields = document.getElementById('upiFields');

        const bankRadio = bankTab.querySelector('input[type="radio"]');
        const upiRadio = upiTab.querySelector('input[type="radio"]');

        if (type === 'bank') {
            bankRadio.checked = true;
            upiRadio.checked = false;

            bankTab.className = "relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-[#150A42] bg-purple-50/40 cursor-pointer transition-all shadow-2xs";
            bankTab.querySelector('.w-8').className = "w-8 h-8 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0";

            upiTab.className = "relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all shadow-2xs";
            upiTab.querySelector('.w-8').className = "w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0";

            bankFields.classList.remove('hidden');
            upiFields.classList.add('hidden');
        } else {
            upiRadio.checked = true;
            bankRadio.checked = false;

            upiTab.className = "relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-[#150A42] bg-purple-50/40 cursor-pointer transition-all shadow-2xs";
            upiTab.querySelector('.w-8').className = "w-8 h-8 rounded-xl bg-purple-100 text-[#150A42] flex items-center justify-center text-sm shrink-0";

            bankTab.className = "relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all shadow-2xs";
            bankTab.querySelector('.w-8').className = "w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0";

            bankFields.classList.add('hidden');
            upiFields.classList.remove('hidden');
        }
    }

    function appendUpiSuffix(suffix) {
        const input = document.getElementById('upiIdInput');
        let current = input.value.trim();
        if (current.includes('@')) {
            current = current.split('@')[0];
        }
        input.value = current + suffix;
        input.focus();
    }

    function validateWithdrawForm(e) {
        const amount = parseFloat(document.getElementById('amountInput').value) || 0;
        if (amount < 100) {
            alert('Minimum withdrawal amount is ₹100.');
            return false;
        }
        if (amount > availableBalance) {
            alert('Requested amount exceeds your available spendable balance.');
            return false;
        }

        if (currentMethod === 'bank') {
            const accNum = document.getElementById('accountNumber').value.trim();
            const ifsc = document.getElementById('ifscCode').value.trim();
            if (!accNum || !ifsc) {
                alert('Please enter your Bank Account Number and valid IFSC Code.');
                return false;
            }
        } else {
            const upi = document.getElementById('upiIdInput').value.trim();
            if (!upi || !upi.includes('@')) {
                alert('Please enter a valid UPI ID (Virtual Payment Address).');
                return false;
            }
        }

        const submitBtn = document.getElementById('submitWithdrawBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Submitting Request...</span>`;
        return true;
    }

    // Filter transactions dynamically
    function filterTransactions(type) {
        const items = document.querySelectorAll('.tx-item');
        const btnAll = document.getElementById('btnFilterAll');
        const btnCredit = document.getElementById('btnFilterCredit');
        const btnDebit = document.getElementById('btnFilterDebit');

        const activeClass = "px-3 py-1 rounded-xl text-[11px] font-black bg-[#150A42] text-white transition-all cursor-pointer shadow-2xs";
        const inactiveClass = "px-3 py-1 rounded-xl text-[11px] font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-200/60 transition-all cursor-pointer";

        btnAll.className = (type === 'all') ? activeClass : inactiveClass;
        btnCredit.className = (type === 'credit') ? activeClass : inactiveClass;
        btnDebit.className = (type === 'debit') ? activeClass : inactiveClass;

        items.forEach(item => {
            if (type === 'all' || item.dataset.type === type) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Toast copy helper
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
