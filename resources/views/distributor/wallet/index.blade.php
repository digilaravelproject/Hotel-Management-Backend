@extends('layouts.distributor')

@section('title', 'My Digital Wallet - Distributor Hub')
@section('page_title', 'Digital Wallet & Earnings')

@section('content')
<div class="space-y-8">

    <!-- Top Wallet Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/60 rounded-3xl p-6 sm:p-8 text-white border border-slate-700 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs border border-amber-500/30">
                <i class="fa-solid fa-wallet mr-1.5"></i> Distributor Revenue Wallet
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Financial Hub & Payouts</h2>
            <p class="text-xs text-slate-300 max-w-xl">
                Track earned commissions across hotel client sales, monitor your wallet balance, and submit withdrawal requests directly to the administration.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="button" 
                    onclick="openWithdrawModal()" 
                    class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-amber-500/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-money-bill-transfer text-xs"></i>
                <span>Request Payout</span>
            </button>
            <a href="{{ route('distributor.wallet.transactions') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs border border-white/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-receipt text-xs text-amber-400"></i>
                <span>Full Ledger</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-lg text-rose-600"></i>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 4 Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Available Balance -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Available Balance</p>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">₹{{ number_format($metrics['available_balance'], 2) }}</div>
                @if($metrics['locked_balance'] > 0)
                    <div class="text-xs text-amber-600 font-bold mt-1">
                        <i class="fa-solid fa-lock text-[10px]"></i> ₹{{ number_format($metrics['locked_balance'], 2) }} pending payout
                    </div>
                @else
                    <div class="text-xs text-emerald-600 font-semibold mt-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Ready for withdrawal
                    </div>
                @endif
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- Lifetime Earned -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lifetime Commission</p>
                <div class="text-3xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($metrics['total_earned'], 2) }}</div>
                <div class="text-xs text-slate-400 font-medium mt-1">Total earned commissions</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>

        <!-- Total Withdrawn -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Withdrawn</p>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">₹{{ number_format($metrics['total_withdrawn'], 2) }}</div>
                <div class="text-xs text-slate-400 font-medium mt-1">Settled to your account</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>

        <!-- Commission Rate -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Your Commission Rate</p>
                <div class="text-3xl font-extrabold text-amber-600 mt-1">{{ number_format($metrics['commission_rate'], 2) }}%</div>
                <div class="text-xs font-semibold mt-1 {{ $metrics['is_override'] ? 'text-indigo-600' : 'text-slate-500' }}">
                    <i class="fa-solid {{ $metrics['is_override'] ? 'fa-star' : 'fa-globe' }} text-[10px]"></i>
                    {{ $metrics['is_override'] ? 'Special Custom Rate' : 'Global Default Rate' }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-percent"></i>
            </div>
        </div>

    </div>

    <!-- 2 Column Section: Recent Ledger & Withdrawal Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Column 1: Recent Wallet Transactions -->
        <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Recent Wallet Activity</h3>
                        <p class="text-xs text-slate-400">Earnings & debits ledger</p>
                    </div>
                    <a href="{{ route('distributor.wallet.transactions') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
                        View All <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $tx)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                    <i class="fa-solid {{ $tx->type === 'credit' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $tx->description }}</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">
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
                        <div class="p-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-receipt text-2xl text-slate-300 mb-2 block"></i>
                            No transactions recorded yet. Earn commissions by onboarding hotels or selling packages!
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('distributor.sales.create') }}" class="text-xs font-extrabold text-slate-700 hover:text-amber-600">
                    <i class="fa-solid fa-plus-circle mr-1"></i> Make a new package sale to earn commission
                </a>
            </div>
        </div>

        <!-- Column 2: Recent Withdrawal Requests -->
        <div class="bg-white border border-slate-200/80 rounded-3xl shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Payout Requests History</h3>
                        <p class="text-xs text-slate-400">Withdrawal status & reference IDs</p>
                    </div>
                    <a href="{{ route('distributor.wallet.withdrawals') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
                        View All <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentWithdrawals as $req)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-black text-slate-900">₹{{ number_format($req->amount, 2) }}</span>
                                    @if($req->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending Admin Payout</span>
                                    @elseif($req->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Approved & Paid</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Rejected</span>
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
                            <div class="text-right text-[10px] text-slate-400 shrink-0 pl-3">
                                {{ $req->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-money-bill-transfer text-2xl text-slate-300 mb-2 block"></i>
                            No withdrawal requests placed yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <button type="button" onclick="openWithdrawModal()" class="text-xs font-extrabold text-amber-600 hover:text-amber-700">
                    <i class="fa-solid fa-arrow-up-from-bracket mr-1"></i> Request a payout now
                </button>
            </div>
        </div>

    </div>

</div>

<!-- Interactive Modal: Request Withdrawal -->
<div id="withdrawModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <form method="POST" action="{{ route('distributor.wallet.withdraw') }}" class="space-y-0">
            @csrf

            <!-- Modal Header -->
            <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold flex items-center space-x-2">
                        <i class="fa-solid fa-money-bill-transfer text-amber-400"></i>
                        <span>Request Payout from Wallet</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Funds will be manually processed to your bank or UPI</p>
                </div>
                <button type="button" onclick="closeWithdrawModal()" class="text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                <!-- Balance Status Banner -->
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Spendable Balance</span>
                        <span class="text-xl font-black text-amber-950">₹{{ number_format($metrics['available_balance'], 2) }}</span>
                    </div>
                    <button type="button" 
                            onclick="document.getElementById('amountInput').value = '{{ $metrics['available_balance'] }}'"
                            class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-xs transition-all">
                        Withdraw All
                    </button>
                </div>

                <!-- Amount Input -->
                <div class="space-y-1.5">
                    <label for="amountInput" class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                        Withdrawal Amount (₹) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-base pointer-events-none">₹</span>
                        <input type="number" 
                               step="0.01" 
                               min="100" 
                               max="{{ $metrics['available_balance'] }}" 
                               name="amount" 
                               id="amountInput" 
                               required 
                               placeholder="e.g. 5000" 
                               class="w-full pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-base font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400">Minimum withdrawal is ₹100. Maximum is ₹{{ number_format($metrics['available_balance'], 2) }}.</p>
                </div>

                <!-- Payout Method Tabs / Radio -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                        Select Payout Method <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all bg-white border-amber-500 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20" id="bankTabLabel">
                            <input type="radio" name="payment_method" value="bank_transfer" checked onchange="togglePayoutMethod('bank')" class="text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-xs font-extrabold text-slate-900 block">Bank Transfer (NEFT/IMPS)</span>
                                <span class="text-[10px] text-slate-400">Account + IFSC</span>
                            </div>
                        </label>
                        <label class="relative flex items-center space-x-3 p-3.5 rounded-2xl border-2 border-slate-200 cursor-pointer transition-all bg-white has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20" id="upiTabLabel">
                            <input type="radio" name="payment_method" value="upi" onchange="togglePayoutMethod('upi')" class="text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-xs font-extrabold text-slate-900 block">Instant UPI</span>
                                <span class="text-[10px] text-slate-400">VPA / GPay / PhonePe</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Bank Fields Container -->
                <div id="bankFields" class="space-y-3 pt-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Holder Name *</label>
                            <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}" placeholder="As per bank passbook" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Bank Name *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. HDFC Bank, SBI" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:border-amber-500 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Account Number *</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:bg-white focus:border-amber-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">IFSC Code *</label>
                            <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="e.g. HDFC0001234" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs uppercase font-mono text-slate-900 focus:bg-white focus:border-amber-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- UPI Fields Container (Hidden by default) -->
                <div id="upiFields" class="space-y-3 pt-1 hidden">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">UPI ID (VPA) *</label>
                        <input type="text" name="upi_id" value="{{ old('upi_id') }}" placeholder="e.g. yourname@okhdfcbank or 9876543210@paytm" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:bg-white focus:border-amber-500 outline-none">
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeWithdrawModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit" 
                        @if($metrics['available_balance'] < 100) disabled @endif 
                        class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 disabled:opacity-50 disabled:cursor-not-allowed text-slate-950 text-xs font-extrabold shadow-md shadow-amber-500/30">
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
