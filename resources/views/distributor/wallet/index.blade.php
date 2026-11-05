@extends('layouts.distributor')

@section('title', 'My Digital Wallet - Distributor Hub')
@section('page_title', 'Digital Wallet & Earnings')

@section('content')
<div class="space-y-8">

    <!-- Top Wallet Header Card (Signature Super Admin Theme) -->
    <div class="bg-white rounded-[28px] p-6 sm:p-7 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-purple-50/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-xs border border-purple-200/60 shadow-2xs">
                <i class="fa-solid fa-wallet text-[10px] text-purple-600"></i>
                <span>Distributor Revenue Wallet</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Financial Hub & Payouts</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
                Track earned commissions across hotel client sales, monitor your wallet balance, and submit withdrawal requests directly to administration.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 shrink-0">
            <button type="button" 
                    onclick="openWithdrawModal()" 
                    class="px-5 py-3 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/15 transition-all flex items-center space-x-2 active:scale-95 cursor-pointer">
                <i class="fa-solid fa-money-bill-transfer text-xs"></i>
                <span>Request Payout</span>
            </button>
            <a href="{{ route('distributor.wallet.transactions') }}" class="px-5 py-3 rounded-2xl bg-purple-50 hover:bg-purple-100/90 text-[#150A42] border border-purple-200/80 font-black text-xs transition-all flex items-center space-x-2 active:scale-95 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-receipt text-xs text-purple-600"></i>
                <span>Full Ledger</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-5 rounded-[24px] bg-rose-50/90 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-2xs">
            <div class="font-black flex items-center space-x-2 text-rose-900">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 4 Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Available Balance -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Available Balance</p>
                    <div class="text-3xl font-black text-slate-900 mt-1 tracking-tight">₹{{ number_format($metrics['available_balance'], 2) }}</div>
                    @if($metrics['locked_balance'] > 0)
                        <div class="text-xs text-amber-600 font-bold mt-1.5 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span>₹{{ number_format($metrics['locked_balance'], 2) }} pending payout</span>
                        </div>
                    @else
                        <div class="text-xs text-emerald-600 font-bold mt-1.5 flex items-center space-x-1">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span>Ready for withdrawal</span>
                        </div>
                    @endif
                </div>
                <div class="w-13 h-13 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Lifetime Earned -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Lifetime Commission</p>
                    <div class="text-3xl font-black text-emerald-600 mt-1 tracking-tight">₹{{ number_format($metrics['total_earned'], 2) }}</div>
                    <div class="text-xs text-slate-400 font-medium mt-1.5">Total earned commissions</div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>
        </div>

        <!-- Total Withdrawn -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Total Settled</p>
                    <div class="text-3xl font-black text-slate-900 mt-1 tracking-tight">₹{{ number_format($metrics['total_withdrawn'], 2) }}</div>
                    <div class="text-xs text-slate-400 font-medium mt-1.5">Settled to your bank/UPI</div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
        </div>

        <!-- Commission Rate -->
        <div class="bg-white border border-slate-100 rounded-[28px] p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Your Commission Rate</p>
                    <div class="text-3xl font-black text-purple-700 mt-1 tracking-tight">{{ number_format($metrics['commission_rate'], 2) }}%</div>
                    <div class="text-xs font-bold mt-1.5 flex items-center space-x-1 {{ $metrics['is_override'] ? 'text-purple-700' : 'text-slate-400' }}">
                        <i class="fa-solid {{ $metrics['is_override'] ? 'fa-star text-amber-500' : 'fa-globe' }} text-[10px]"></i>
                        <span>{{ $metrics['is_override'] ? 'Special Custom Rate' : 'Global Default Rate' }}</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- 2 Column Section: Recent Ledger & Withdrawal Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Column 1: Recent Wallet Transactions -->
        <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Recent Wallet Activity</h3>
                        <p class="text-xs text-slate-400 font-medium">Earnings & debits ledger</p>
                    </div>
                    <a href="{{ route('distributor.wallet.transactions') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 flex items-center space-x-1">
                        <span>View All</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $tx)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    <i class="fa-solid {{ $tx->type === 'credit' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-black text-slate-900 truncate">{{ $tx->description }}</h4>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                        {{ $tx->created_at->format('M d, Y h:i A') }} • Balance after: ₹{{ number_format($tx->balance_after, 2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 pl-3">
                                <span class="text-sm font-black {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 text-xs">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200/60 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">No transactions recorded yet</p>
                            <p class="text-xs text-slate-400 mt-1">Earn commissions by onboarding hotels or selling packages!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('distributor.sales.create') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 inline-flex items-center space-x-1">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Make a package sale to earn commission</span>
                </a>
            </div>
        </div>

        <!-- Column 2: Recent Withdrawal Requests -->
        <div class="bg-white border border-slate-100 rounded-[28px] shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Payout Requests History</h3>
                        <p class="text-xs text-slate-400 font-medium">Withdrawal status & reference IDs</p>
                    </div>
                    <a href="{{ route('distributor.wallet.withdrawals') }}" class="text-xs font-black text-purple-700 hover:text-purple-900 flex items-center space-x-1">
                        <span>View All</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentWithdrawals as $req)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50/60 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-black text-slate-900">₹{{ number_format($req->amount, 2) }}</span>
                                    @if($req->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1 inline-block animate-pulse"></span> Under Review
                                        </span>
                                    @elseif($req->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                            <i class="fa-solid fa-check text-[9px] mr-1 text-emerald-600"></i> Settled
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-800 border border-rose-200/60">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-500 font-mono truncate max-w-xs">
                                    {{ $req->account_details }}
                                </div>
                                @if($req->transaction_number)
                                    <div class="text-[10px] font-bold text-emerald-700 font-mono">
                                        <i class="fa-solid fa-receipt mr-1"></i> UTR/Ref: {{ $req->transaction_number }}
                                    </div>
                                @endif
                                @if($req->admin_notes)
                                    <div class="text-[10px] text-slate-400 italic">
                                        Note: "{{ $req->admin_notes }}"
                                    </div>
                                @endif
                            </div>
                            <div class="text-right text-[10px] text-slate-400 font-medium shrink-0 pl-3">
                                {{ $req->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 text-xs">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200/60 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">No payout requests placed yet</p>
                            <p class="text-xs text-slate-400 mt-1">Submit your first payout request anytime!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <button type="button" onclick="openWithdrawModal()" class="text-xs font-black text-purple-700 hover:text-purple-900 inline-flex items-center space-x-1 cursor-pointer">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span>Request a payout now</span>
                </button>
            </div>
        </div>

    </div>

</div>

<!-- Interactive Modal: Request Withdrawal -->
<div id="withdrawModal" class="fixed inset-0 z-50 bg-[#0B0424]/75 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
        <form method="POST" action="{{ route('distributor.wallet.withdraw') }}" class="space-y-0">
            @csrf

            <!-- Modal Header -->
            <div class="p-6 bg-white border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center text-base font-black shadow-2xs shrink-0">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Request Payout from Wallet</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Funds will be manually processed to your bank or UPI</p>
                    </div>
                </div>
                <button type="button" onclick="closeWithdrawModal()" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                <!-- Balance Status Banner -->
                <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/60 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-purple-700 uppercase tracking-wider block">Spendable Balance</span>
                        <span class="text-2xl font-black text-slate-900">₹{{ number_format($metrics['available_balance'], 2) }}</span>
                    </div>
                    <button type="button" 
                            onclick="document.getElementById('amountInput').value = '{{ $metrics['available_balance'] }}'"
                            class="px-3.5 py-1.5 rounded-xl bg-[#150A42] hover:bg-[#1f105e] text-white font-black text-xs shadow-xs transition-all cursor-pointer active:scale-95">
                        Withdraw All
                    </button>
                </div>

                <!-- Amount Input -->
                <div class="space-y-1.5">
                    <label for="amountInput" class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                        Withdrawal Amount (₹) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-base pointer-events-none">₹</span>
                        <input type="number" 
                               step="0.01" 
                               min="100" 
                               max="{{ $metrics['available_balance'] }}" 
                               name="amount" 
                               id="amountInput" 
                               required 
                               placeholder="e.g. 5000" 
                               class="w-full pl-9 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-2xl text-base font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] outline-none transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Minimum withdrawal is ₹100. Maximum is ₹{{ number_format($metrics['available_balance'], 2) }}.</p>
                </div>

                <!-- Payout Method Tabs / Radio -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                        Select Payout Method <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all bg-white border-[#150A42] has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 shadow-2xs" id="bankTabLabel">
                            <input type="radio" name="payment_method" value="bank_transfer" checked onchange="togglePayoutMethod('bank')" class="text-[#150A42] focus:ring-purple-500">
                            <div>
                                <span class="text-xs font-black text-slate-900 block">Bank Transfer</span>
                                <span class="text-[10px] text-slate-400 font-medium">NEFT / IMPS</span>
                            </div>
                        </label>
                        <label class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-slate-200 cursor-pointer transition-all bg-white has-[:checked]:border-[#150A42] has-[:checked]:bg-purple-50/30 shadow-2xs" id="upiTabLabel">
                            <input type="radio" name="payment_method" value="upi" onchange="togglePayoutMethod('upi')" class="text-[#150A42] focus:ring-purple-500">
                            <div>
                                <span class="text-xs font-black text-slate-900 block">Instant UPI</span>
                                <span class="text-[10px] text-slate-400 font-medium">VPA / GPay / PhonePe</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Bank Fields Container -->
                <div id="bankFields" class="space-y-3 pt-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Holder Name *</label>
                            <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}" placeholder="As per bank passbook" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Bank Name *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. HDFC Bank, SBI" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Number *</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">IFSC Code *</label>
                            <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="e.g. HDFC0001234" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs uppercase font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                        </div>
                    </div>
                </div>

                <!-- UPI Fields Container (Hidden by default) -->
                <div id="upiFields" class="space-y-3 pt-1 hidden">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">UPI ID (VPA) *</label>
                        <input type="text" name="upi_id" value="{{ old('upi_id') }}" placeholder="e.g. yourname@okhdfcbank or 9876543210@paytm" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-[#150A42] outline-none">
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeWithdrawModal()" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition-all active:scale-95 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" 
                        @if($metrics['available_balance'] < 100) disabled @endif 
                        class="px-6 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/15 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    Submit Payout Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openWithdrawModal() {
        document.getElementById('withdrawModal').classList.remove('hidden');
        document.getElementById('withdrawModal').classList.add('flex');
    }

    function closeWithdrawModal() {
        document.getElementById('withdrawModal').classList.add('hidden');
        document.getElementById('withdrawModal').classList.remove('flex');
    }

    function togglePayoutMethod(type) {
        const bankFields = document.getElementById('bankFields');
        const upiFields = document.getElementById('upiFields');

        if (type === 'bank') {
            bankFields.classList.remove('hidden');
            upiFields.classList.add('hidden');
        } else {
            bankFields.classList.add('hidden');
            upiFields.classList.remove('hidden');
        }
    }
</script>
@endsection
