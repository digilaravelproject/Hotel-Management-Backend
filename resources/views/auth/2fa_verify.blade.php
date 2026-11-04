@extends('layouts.app')

@section('title', 'Two-Factor Authentication - ' . app_name())

@section('styles')
<style>
    .login-cosmic-bg {
        background: radial-gradient(ellipse at 50% 10%, #1e0f54 0%, #0d0628 55%, #050212 100%);
    }
    .login-card-shadow {
        box-shadow: 0 25px 60px -15px rgba(5, 2, 18, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 login-cosmic-bg relative overflow-hidden selection:bg-purple-600 selection:text-white">

    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] bg-purple-600/15 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-[430px] mx-auto relative z-10 flex flex-col items-center">

        <!-- Logo -->
        <div class="mb-7 text-center">
            <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105" title="{{ app_name() }}">
                <picture>
                    <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" 
                         alt="{{ app_name() }}" 
                         class="h-14 sm:h-16 w-auto max-w-[220px] object-contain drop-shadow-xl">
                </picture>
            </a>
        </div>

        <!-- Verification Card -->
        <div class="w-full bg-white rounded-[28px] p-7 sm:p-9 shadow-2xl border border-slate-100 login-card-shadow text-center">

            <!-- Shield Icon Badge -->
            <div class="w-13 h-13 rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center mx-auto shadow-xs mb-4">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>

            <div class="space-y-1 mb-5">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-sans">
                    2-Step Verification
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Enter the 6-digit verification code from your Authenticator app to continue.
                </p>
            </div>

            <!-- Validation Errors -->
            @if (isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 text-left">
                    <div class="flex items-center space-x-1.5 font-bold text-rose-900">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Verification error:</span>
                    </div>
                    <ul class="list-disc pl-4 space-y-0.5 font-medium text-rose-700 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('2fa.verify') }}" method="POST" id="verifyForm" class="space-y-4">
                @csrf
                <input type="hidden" name="one_time_password" id="otpValue">

                <!-- TOTP Mode -->
                <div id="totpSection" class="space-y-3">
                    <div class="flex items-center justify-center gap-2 sm:gap-2.5 my-2" id="otpInputs">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono" autofocus>
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-13 rounded-xl border-2 border-slate-200 bg-[#F8F9FD] text-center text-xl font-extrabold text-slate-900 focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 focus:outline-none transition-all font-mono">
                    </div>
                    <div class="pt-1">
                        <button type="button" class="text-xs font-semibold text-[#150A42] hover:underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(true)">
                            Lost authenticator? Use emergency recovery code
                        </button>
                    </div>
                </div>

                <!-- Recovery Code Mode -->
                <div id="recoverySection" style="display: none;" class="space-y-3">
                    <div class="my-2">
                        <input type="text" id="recoveryCodeInput" class="w-full px-4 py-3 bg-[#F8F9FD] border border-slate-200 rounded-xl text-center text-sm font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-[#150A42] focus:ring-4 focus:ring-[#150A42]/10 transition-all font-mono" placeholder="Enter emergency code (e.g. xxxxx-xxxxx)">
                    </div>
                    <div>
                        <button type="button" class="text-xs font-semibold text-[#150A42] hover:underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(false)">
                            Return to 6-digit Authenticator code
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-sm shadow-xl shadow-[#150A42]/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 cursor-pointer">
                    <i class="fa-solid fa-lock-open text-xs"></i>
                    <span>Verify & Continue</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-[#150A42] transition-colors inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Sign In</span>
                </a>
            </div>

        </div>

        <p class="mt-7 text-xs font-medium text-slate-400 text-center tracking-tight">
            &copy; {{ date('Y') }} {{ app_name() }}. All rights reserved.
        </p>

    </div>
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
