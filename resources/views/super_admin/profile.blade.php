@extends('layouts.super_admin')

@section('title', 'My Profile - Super Admin')
@section('page_title', 'Account Security & Credentials')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="border-b border-[#E5E7EB] pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880]">
                <i class="fa-regular fa-user text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#111827] tracking-tight">Super Admin Credentials</h3>
                <p class="text-xs text-[#6B7280] font-medium">Manage master administrator login email address and account password.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#DC2626]/20 text-[#B91C1C] text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super-admin.profile') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-[#111827]">Master Email Address</label>
                <input type="email" name="email" value="{{ old('email', $superAdmin->email) }}" required 
                    class="w-full px-4 py-3 bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl text-sm font-medium focus:outline-none focus:border-[#C5A880] focus:bg-white transition-all text-[#111827]">
            </div>

            <div class="pt-4 border-t border-[#E5E7EB]">
                <h4 class="text-xs font-extrabold text-[#6B7280] uppercase tracking-wider mb-4">
                    Update Password <span class="text-[#6B7280] font-normal lowercase">(leave blank to keep current)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#111827]">New Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" 
                            class="w-full px-4 py-3 bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl text-sm font-medium focus:outline-none focus:border-[#C5A880] focus:bg-white transition-all text-[#111827]">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#111827]">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password" 
                            class="w-full px-4 py-3 bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl text-sm font-medium focus:outline-none focus:border-[#C5A880] focus:bg-white transition-all text-[#111827]">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-[#E5E7EB] flex items-center justify-end space-x-4">
                <a href="{{ route('super-admin.dashboard') }}" class="px-6 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#111827] hover:bg-[#F4F5F7] text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] font-bold text-xs shadow-md shadow-[#C5A880]/20 transition-all hover:-translate-y-0.5">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Google Authenticator (2FA) Security Card -->
    <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-[#F5F2EB] border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880]">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-[#111827] tracking-tight">Two-Factor Authentication</h3>
                    <p class="text-xs text-[#6B7280] font-medium">Add an extra layer of security using Google Authenticator (TOTP).</p>
                </div>
            </div>
            <div>
                @if($superAdmin->google2fa_enabled)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F0FDF4] text-[#15803D] border border-[#16A34A]/20">
                        <i class="fa-solid fa-circle-check me-1.5"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F4F5F7] text-[#6B7280] border border-[#E5E7EB]">
                        <i class="fa-solid fa-circle-minus me-1.5"></i> Disabled
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-[#111827]">Google Authenticator Verification</h4>
                <p class="text-xs text-[#6B7280] max-w-md">When enabled, you will be required to enter a 6-digit TOTP code from your mobile app every time you log in.</p>
            </div>
            <div>
                @if($superAdmin->google2fa_enabled)
                    <button type="button" onclick="openDisable2FAModal()" class="px-5 py-2.5 rounded-xl border border-[#DC2626]/20 bg-[#FEF2F2] text-[#B91C1C] hover:bg-red-100 text-xs font-bold transition-all cursor-pointer">
                        Disable 2FA
                    </button>
                @else
                    <button type="button" onclick="start2FASetup()" class="px-6 py-2.5 rounded-xl bg-[#A68A64] hover:bg-[#8C7250] text-[#111827] text-xs font-bold shadow-md shadow-[#C5A880]/20 transition-all cursor-pointer">
                        Enable 2FA
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div id="setup2FAModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-[#E5E7EB]">
        <button onclick="closeSetup2FAModal()" class="absolute top-6 right-6 text-[#6B7280] hover:text-[#111827] cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#F5F2EB] text-[#C5A880] mx-auto flex items-center justify-center text-xl border border-[#C5A880]/20">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3 class="text-xl font-extrabold text-[#111827]">Set Up Google Authenticator</h3>
            <p class="text-xs text-[#6B7280]">Scan the QR code below using Google Authenticator app or enter the setup key manually.</p>
        </div>

        <div id="qrContainer" class="flex flex-col items-center space-y-4 py-2">
            <div id="qrCodeSvg" class="p-3 bg-white border border-[#E5E7EB] rounded-2xl shadow-inner"></div>
            <div class="text-center">
                <span class="text-xs text-[#6B7280] font-semibold uppercase tracking-wider block mb-1">Manual Secret Key</span>
                <code id="secretKeyDisplay" class="px-3 py-1.5 bg-[#F4F5F7] rounded-lg text-sm font-mono text-[#111827] font-bold tracking-widest select-all border border-[#E5E7EB]">Loading...</code>
            </div>
        </div>

        <div id="setup2faError" class="hidden p-3.5 bg-[#FEF2F2] border border-[#FEE2E2] text-[#B91C1C] rounded-xl text-xs font-semibold"></div>

        <form id="enable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitEnable2FA(event)" class="space-y-4 pt-2 border-t border-[#E5E7EB]">
            <div class="space-y-1">
                <label class="text-xs font-bold text-[#111827]">Enter 6-Digit Authenticator Code</label>
                <input type="text" id="setupOtpInput" maxlength="6" required placeholder="123456" 
                    class="w-full px-4 py-3 bg-[#F4F5F7] border border-[#E5E7EB] rounded-xl text-center font-mono text-lg font-bold tracking-widest focus:outline-none focus:border-[#C5A880] text-[#111827]">
            </div>

            <button type="submit" id="setup2FASubmitBtn" class="w-full py-3 bg-[#A68A64] hover:bg-[#8C7250] text-[#FFFFFF] rounded-xl font-bold text-xs shadow-md shadow-[#C5A880]/20 transition-all cursor-pointer flex items-center justify-center space-x-2">
                <span>Verify Code & Activate 2FA</span>
            </button>
        </form>

        <!-- Recovery Codes Result View -->
        <div id="recoveryCodesSection" class="hidden space-y-4">
            <div class="p-4 bg-[#F0FDF4] border border-[#DCFCE7] rounded-2xl text-[#15803D] text-xs font-semibold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base text-[#15803D]"></i>
                <span>2FA is now active! Please copy and store these emergency recovery codes safely.</span>
            </div>
            <div id="recoveryCodesList" class="grid grid-cols-2 gap-2 font-mono text-xs text-[#111827] bg-[#F9FAFB] p-4 rounded-xl border border-[#E5E7EB] select-all"></div>
            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="copyRecoveryCodes()" id="copyCodesBtn" class="flex-1 py-3 bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-[#374151] rounded-xl font-bold text-xs transition-all">
                    <i class="fa-regular fa-copy mr-1"></i> Copy All Codes
                </button>
                <button type="button" onclick="location.reload()" class="flex-1 py-3 bg-[#111827] hover:bg-[#1F2937] text-white rounded-xl font-bold text-xs transition-all">
                    Done / Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Disable Modal -->
<div id="disable2FAModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-[#E5E7EB]">
        <button onclick="closeDisable2FAModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#FEF2F2] text-[#B91C1C] mx-auto flex items-center justify-center text-xl border border-[#FEE2E2]">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-extrabold text-[#111827]">Disable 2FA</h3>
            <p class="text-xs text-[#6B7280]">Confirm your current password and a live 6-digit TOTP code to disable Two-Factor Authentication.</p>
        </div>

        <div id="disable2faError" class="hidden p-3.5 bg-[#FEF2F2] border border-[#FEE2E2] text-[#B91C1C] rounded-xl text-xs font-semibold"></div>

        <form id="disable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitDisable2FA(event)" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">Current Password</label>
                <input type="password" id="disablePassword" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-[#C5A880]">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">6-Digit Authenticator Code</label>
                <input type="text" id="disableOtp" maxlength="6" required placeholder="123456" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono text-lg font-bold tracking-widest focus:outline-none focus:border-[#C5A880]">
            </div>

            <button type="submit" id="disable2FASubmitBtn" class="w-full py-3 bg-[#B91C1C] hover:bg-[#991B1B] text-white rounded-xl font-bold text-xs shadow-md shadow-[#B91C1C]/20 transition-all cursor-pointer flex items-center justify-center space-x-2">
                <span>Confirm & Disable 2FA</span>
            </button>
        </form>
    </div>
</div>

<script>
    let currentRecoveryCodes = [];

    function start2FASetup() {
        const modal = document.getElementById('setup2FAModal');
        modal.classList.remove('hidden');
        document.getElementById('setupOtpInput').value = '';
        
        const errorDiv = document.getElementById('setup2faError');
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        document.getElementById('qrContainer').classList.remove('hidden');
        document.getElementById('enable2FAForm').classList.remove('hidden');
        document.getElementById('recoveryCodesSection').classList.add('hidden');
        
        document.getElementById('secretKeyDisplay').innerText = 'Generating secret key...';
        document.getElementById('qrCodeSvg').innerHTML = '<div class="w-48 h-48 flex items-center justify-center text-slate-400"><i class="fa-solid fa-spinner fa-spin text-2xl text-[#A68A64]"></i></div>';

        fetch("{{ route('super-admin.2fa.generate') }}", {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || 'Failed to generate 2FA secret.');
            }
            return data;
        })
        .then(data => {
            if (data.qr_code_svg) {
                document.getElementById('qrCodeSvg').innerHTML = atob(data.qr_code_svg);
                document.getElementById('secretKeyDisplay').innerText = data.secret;
            } else {
                throw new Error('QR code generation failed.');
            }
        })
        .catch(err => {
            errorDiv.classList.remove('hidden');
            errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-1.5"></i> ${err.message}`;
        });
    }

    function closeSetup2FAModal() {
        document.getElementById('setup2FAModal').classList.add('hidden');
        if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
    }

    function submitEnable2FA(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('setup2FASubmitBtn');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        const errorDiv = document.getElementById('setup2faError');
        
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        const otp = document.getElementById('setupOtpInput').value.trim();
        if (otp.length !== 6) {
            errorDiv.classList.remove('hidden');
            errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1.5"></i> Please enter a valid 6-digit numeric code.';
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Verifying Code...';
        }

        fetch("{{ route('super-admin.2fa.enable') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ one_time_password: otp })
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Verification failed.'));
            }
            return data;
        })
        .then(data => {
            if (data.recovery_codes && data.recovery_codes.length > 0) {
                currentRecoveryCodes = data.recovery_codes;
                document.getElementById('qrContainer').classList.add('hidden');
                document.getElementById('enable2FAForm').classList.add('hidden');
                document.getElementById('recoveryCodesSection').classList.remove('hidden');
                const list = document.getElementById('recoveryCodesList');
                list.innerHTML = data.recovery_codes.map(c => `<div class="p-2 bg-white rounded-lg border border-[#E5E7EB] text-center font-mono font-bold text-xs text-[#111827]">${c}</div>`).join('');
            } else {
                throw new Error(data.message || 'Verification could not be confirmed.');
            }
        })
        .catch(err => {
            errorDiv.classList.remove('hidden');
            errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-1.5"></i> ${err.message}`;
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
            if (typeof hideGlobalLoader === 'function') {
                hideGlobalLoader();
            }
        });
    }

    function copyRecoveryCodes() {
        if (!currentRecoveryCodes.length) return;
        const text = currentRecoveryCodes.join("\n");
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('copyCodesBtn');
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check mr-1 text-[#15803D]"></i> Copied!';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        });
    }

    function openDisable2FAModal() {
        const errorDiv = document.getElementById('disable2faError');
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';
        document.getElementById('disablePassword').value = '';
        document.getElementById('disableOtp').value = '';
        document.getElementById('disable2FAModal').classList.remove('hidden');
    }

    function closeDisable2FAModal() {
        document.getElementById('disable2FAModal').classList.add('hidden');
        if (typeof hideGlobalLoader === 'function') hideGlobalLoader();
    }

    function submitDisable2FA(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('disable2FASubmitBtn');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        const errorDiv = document.getElementById('disable2faError');
        
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Disabling...';
        }

        fetch("{{ route('super-admin.2fa.disable') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: document.getElementById('disablePassword').value,
                one_time_password: document.getElementById('disableOtp').value
            })
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || 'Failed to disable 2FA.');
            }
            return data;
        })
        .then(data => {
            location.reload();
        })
        .catch(err => {
            errorDiv.classList.remove('hidden');
            errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-1.5"></i> ${err.message}`;
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
            if (typeof hideGlobalLoader === 'function') {
                hideGlobalLoader();
            }
        });
    }
</script>

@endsection
