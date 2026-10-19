@extends('layouts.app')

@section('title', 'Two-Factor Authentication - ' . app_name())

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#F4F5F7] text-[#111827] relative overflow-hidden">
    <!-- Header Bar -->
    <header class="w-full px-6 lg:px-12 py-5 bg-white border-b border-[#E5E7EB] shadow-xs relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105">
            </a>

            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#F5F2EB] text-[#111827] border border-[#C5A880]/40">
                <i class="fa-solid fa-shield-halved text-[10px] text-[#C5A880] mr-1.5"></i> 2-Step Verification
            </span>
        </div>
    </header>

    <!-- Main Card -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
        <div class="w-full max-w-md relative z-10">
            <div class="bg-white border border-[#E5E7EB] rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 space-y-7 text-center">
                
                <div class="space-y-2.5">
                    <div class="w-16 h-16 rounded-2xl bg-[#111827] text-[#C5A880] flex items-center justify-center mx-auto shadow-md shadow-[#111827]/20 border border-[#1F2937]">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-[#111827] tracking-tight">Two-Factor Authentication</h1>
                    <p class="text-xs text-[#6B7280] leading-relaxed">
                        Enter the 6-digit verification code from your Authenticator app to authorize this session.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-[#FEF2F2] border border-[#DC2626]/20 text-[#B91C1C] text-xs space-y-1 text-left">
                        <ul class="list-disc pl-5 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('2fa.verify') }}" method="POST" id="verifyForm" class="space-y-5">
                    @csrf
                    <input type="hidden" name="one_time_password" id="otpValue">

                    <!-- TOTP Mode -->
                    <div id="totpSection" class="space-y-3">
                        <div class="flex items-center justify-center gap-2 sm:gap-2.5 my-2" id="otpInputs">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono" autofocus>
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-[#CBD5E1] bg-[#F4F5F7] text-center text-xl font-bold text-[#111827] focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 focus:outline-none transition-all font-mono">
                        </div>
                        <div class="pt-1">
                            <button type="button" class="text-xs font-semibold text-[#6B7280] hover:text-[#111827] underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(true)">
                                Lost authenticator? Use emergency recovery code
                            </button>
                        </div>
                    </div>

                    <!-- Recovery Code Mode -->
                    <div id="recoverySection" style="display: none;" class="space-y-3">
                        <div class="my-2">
                            <input type="text" id="recoveryCodeInput" class="w-full px-4 py-3 bg-[#F4F5F7] border border-[#CBD5E1] rounded-xl text-center text-sm font-bold text-[#111827] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#C5A880] focus:ring-2 focus:ring-[#C5A880]/20 transition-all font-mono" placeholder="Enter emergency code (e.g. xxxxx-xxxxx)">
                        </div>
                        <div>
                            <button type="button" class="text-xs font-semibold text-[#6B7280] hover:text-[#111827] underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(false)">
                                Return to 6-digit Authenticator code
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] font-extrabold text-sm rounded-xl shadow-lg shadow-[#C5A880]/25 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-lock-open text-xs"></i>
                        <span>Verify & Access Account</span>
                    </button>
                </form>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full px-6 lg:px-12 py-4 bg-white border-t border-[#E5E7EB] text-xs text-[#6B7280] relative z-20">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Protected by multi-tier enterprise 2FA</p>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    const fields = document.querySelectorAll('.otp-field');
    const otpValueInput = document.getElementById('otpValue');
    const form = document.getElementById('verifyForm');
    let isRecoveryMode = false;

    fields.forEach((field, index) => {
        field.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < fields.length - 1) {
                fields[index + 1].focus();
            }
            updateOtpValue();
        });

        field.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !field.value && index > 0) {
                fields[index - 1].focus();
            }
        });

        field.addEventListener('paste', (e) => {
            e.preventDefault();
            const data = (e.clipboardData || window.clipboardData).getData('text').trim();
            if (data.length === 6 && /^\d+$/.test(data)) {
                data.split('').forEach((char, i) => {
                    if (fields[i]) fields[i].value = char;
                });
                updateOtpValue();
                fields[5].focus();
            }
        });
    });

    function updateOtpValue() {
        if (!isRecoveryMode) {
            let code = '';
            fields.forEach(f => code += f.value);
            otpValueInput.value = code;
        }
    }

    function toggleRecoveryMode(enable) {
        isRecoveryMode = enable;
        document.getElementById('totpSection').style.display = enable ? 'none' : 'block';
        document.getElementById('recoverySection').style.display = enable ? 'block' : 'none';
        
        if (enable) {
            document.getElementById('recoveryCodeInput').focus();
        } else {
            fields[0].focus();
        }
    }

    form.addEventListener('submit', (e) => {
        if (isRecoveryMode) {
            otpValueInput.value = document.getElementById('recoveryCodeInput').value.trim();
        } else {
            updateOtpValue();
        }
    });
</script>
@endsection
