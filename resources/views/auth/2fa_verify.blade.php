@extends('layouts.app')

@section('title', 'Two-Factor Authentication - ' . app_name())

@section('styles')
<!-- Typography & Custom Theme Styling -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<style>
    .font-display, .font-mono {
        font-family: 'Space Mono', monospace;
    }
    .purple-mesh-bg {
        background: radial-gradient(circle at 15% 15%, rgba(124, 58, 237, 0.22) 0%, transparent 40%),
                    radial-gradient(circle at 85% 85%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                    radial-gradient(circle at 50% 50%, rgba(147, 51, 234, 0.08) 0%, transparent 60%),
                    #0D091B;
    }
    .glow-orb {
        filter: blur(64px);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col justify-between purple-mesh-bg text-slate-100 relative selection:bg-purple-600 selection:text-white">

    <!-- Ambient Glowing Orbs in Background -->
    <div class="absolute -top-28 -left-28 w-96 h-96 bg-purple-600/20 rounded-full glow-orb pointer-events-none"></div>
    <div class="absolute -bottom-28 -right-28 w-96 h-96 bg-indigo-600/18 rounded-full glow-orb pointer-events-none"></div>

    <!-- Geometric Accent Rings -->
    <div class="absolute top-16 right-16 w-60 h-60 border border-purple-500/10 rounded-full pointer-events-none hidden xl:block"></div>
    <div class="absolute bottom-16 left-12 w-48 h-48 border border-indigo-500/10 rounded-full pointer-events-none hidden xl:block"></div>

    <!-- Top Navigation Header -->
    <header class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/70 backdrop-blur-xl border-b border-white/[0.08] relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <picture>
                        <source srcset="{{ asset('images/logo/pax-tv-launcher.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo/pax-tv-launcher.png') }}" alt="{{ app_name() }}" class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                    </picture>
                </a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-purple-500/15 text-purple-200 border border-purple-400/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                    2-Step Verification
                </span>
            </div>

            <a href="{{ route('landing') }}" class="px-3.5 py-1.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-semibold transition-all inline-flex items-center space-x-2 backdrop-blur-md">
                <i class="fa-solid fa-arrow-left text-xs text-purple-400"></i>
                <span>Back to Website</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-4 lg:py-6 w-full flex items-center justify-center relative z-10">
        <div class="w-full max-w-[440px] mx-auto">
            <div class="bg-white rounded-3xl p-7 sm:p-9 shadow-2xl shadow-purple-950/40 border border-purple-100/60 relative text-center">

                <!-- Badge Icon -->
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-purple-600/30 border border-purple-400/30 mb-4">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>

                <!-- Header Title -->
                <div class="space-y-1 mb-5">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Two-Factor Authentication</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed">
                        Enter the 6-digit verification code from your Authenticator app to authorize this session.
                    </p>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1 text-left">
                        <div class="flex items-center space-x-1.5 font-bold text-red-800">
                            <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                            <span>Verification error:</span>
                        </div>
                        <ul class="list-disc pl-4 space-y-0.5 font-medium text-red-700 text-xs">
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
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono" autofocus>
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-field w-11 sm:w-12 h-14 rounded-xl border-2 border-slate-200 bg-slate-50 text-center text-xl font-bold text-slate-900 focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 focus:outline-none transition-all font-mono">
                        </div>
                        <div class="pt-1">
                            <button type="button" class="text-xs font-semibold text-purple-600 hover:text-purple-700 hover:underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(true)">
                                Lost authenticator? Use emergency recovery code
                            </button>
                        </div>
                    </div>

                    <!-- Recovery Code Mode -->
                    <div id="recoverySection" style="display: none;" class="space-y-3">
                        <div class="my-2">
                            <input type="text" id="recoveryCodeInput" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-mono" placeholder="Enter emergency code (e.g. xxxxx-xxxxx)">
                        </div>
                        <div>
                            <button type="button" class="text-xs font-semibold text-purple-600 hover:text-purple-700 hover:underline transition-colors cursor-pointer" onclick="toggleRecoveryMode(false)">
                                Return to 6-digit Authenticator code
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 hover:from-purple-500 hover:via-violet-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-lock-open text-xs"></i>
                        <span>Verify & Access Account</span>
                    </button>
                </form>

                <!-- Help Note -->
                <div class="mt-4 pt-3.5 border-t border-slate-100 text-center">
                    <p class="text-[11px] text-slate-500">
                        Protected by enterprise multi-factor authentication
                    </p>
                </div>

            </div>
        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="w-full px-6 lg:px-12 py-3 bg-[#0D091B]/80 backdrop-blur-md border-t border-white/[0.08] text-xs text-slate-400 relative z-20 shrink-0">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2.5 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-purple-300 hover:underline">Digi Emperor</a></p>
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span class="text-white/20">|</span>
                <a href="{{ route('contact-us') }}" class="hover:text-white transition-colors">Support</a>
            </div>
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
