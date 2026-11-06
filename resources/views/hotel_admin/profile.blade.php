@extends('layouts.hotel_admin')

@section('title', 'Update Profile')
@section('page_title', 'Account & Security')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-10 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-6 flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] text-lg shadow-2xs">
                <i class="fa-regular fa-user"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Personal Account Details</h3>
                <p class="text-xs text-slate-400 font-medium">Update your account identity and login credentials.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hotel.profile') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-900">Owner / Director Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name', $hotelAdmin->owner_name) }}" required 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] focus:bg-white transition-all">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-900">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $hotelAdmin->email) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] focus:bg-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-900">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $hotelAdmin->phone) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] focus:bg-white transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">
                    Change Password <span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-900">New Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-900">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all cursor-pointer">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white font-black text-xs shadow-md shadow-[#150A42]/15 transition-all hover:-translate-y-0.5 cursor-pointer active:scale-95">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Google Authenticator (2FA) Security Card -->
    <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-10 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-6">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] text-lg shadow-2xs">
                    <i class="fa-solid fa-shield-halved text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Two-Factor Authentication</h3>
                    <p class="text-xs text-slate-400 font-medium">Add an extra layer of security using Google Authenticator (TOTP).</p>
                </div>
            </div>
            <div>
                @if($hotelAdmin->google2fa_enabled)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fa-solid fa-circle-check me-1.5 text-emerald-600"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                        <i class="fa-solid fa-circle-minus me-1.5"></i> Disabled
                    </span>
                @endif
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-slate-900">Google Authenticator Verification</h4>
                <p class="text-xs text-slate-500 max-w-md">When enabled, you will be required to enter a 6-digit TOTP code from your mobile app every time you log in.</p>
            </div>
            <div class="shrink-0">
                @if($hotelAdmin->google2fa_enabled)
                    <button type="button" onclick="openDisable2FAModal()" class="px-5 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-all cursor-pointer">
                        Disable 2FA
                    </button>
                @else
                    <button type="button" onclick="start2FASetup()" class="px-6 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#1f105e] text-white text-xs font-black shadow-md shadow-[#150A42]/15 transition-all cursor-pointer active:scale-95">
                        Enable 2FA
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div id="setup2FAModal" class="fixed inset-0 z-50 hidden bg-[#0B0424]/75 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <button onclick="closeSetup2FAModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 p-1 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] mx-auto flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-qrcode text-purple-600"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Set Up Google Authenticator</h3>
            <p class="text-xs text-slate-400">Scan the QR code below using Google Authenticator app or enter the setup key manually.</p>
        </div>

        <div id="qrContainer" class="flex flex-col items-center space-y-4 py-2">
            <div id="qrCodeSvg" class="p-3 bg-white border border-slate-200 rounded-2xl shadow-inner"></div>
            <div class="text-center">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block mb-1">Manual Secret Key</span>
                <code id="secretKeyDisplay" class="px-3 py-1.5 bg-purple-50 border border-purple-200 rounded-xl text-sm font-mono text-[#150A42] font-black tracking-widest select-all">Loading...</code>
            </div>
        </div>

        <div id="setup2faError" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold"></div>

        <form id="enable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitEnable2FA(event)" class="space-y-4 pt-2 border-t border-slate-100">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-900">Enter 6-Digit Authenticator Code</label>
                <input type="text" id="setupOtpInput" maxlength="6" required placeholder="123456" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono text-lg font-black tracking-widest text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42]">
            </div>

            <button type="submit" id="setup2FASubmitBtn" class="w-full py-3 bg-gradient-to-r from-[#150A42] to-[#2B1270] hover:from-[#1b0d54] hover:to-[#38188e] text-white rounded-2xl font-black text-xs shadow-md shadow-[#150A42]/15 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95">
                <span>Verify Code & Activate 2FA</span>
            </button>
        </form>

        <!-- Recovery Codes Result View -->
        <div id="recoveryCodesSection" class="hidden space-y-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                <span>2FA is now active! Please copy and store these emergency recovery codes safely.</span>
            </div>
            <div id="recoveryCodesList" class="grid grid-cols-2 gap-2 font-mono text-xs text-slate-900 bg-slate-50 p-4 rounded-xl border border-slate-200 select-all"></div>
            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="copyRecoveryCodes()" id="copyCodesBtn" class="flex-1 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-xs transition-all cursor-pointer">
                    <i class="fa-regular fa-copy mr-1"></i> Copy All Codes
                </button>
                <button type="button" onclick="location.reload()" class="flex-1 py-3 bg-[#150A42] hover:bg-[#1f105e] text-white rounded-xl font-bold text-xs cursor-pointer">
                    Done / Refresh Page
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Disable Modal -->
<div id="disable2FAModal" class="fixed inset-0 z-50 hidden bg-[#0B0424]/75 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <button onclick="closeDisable2FAModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 p-1 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center text-xl border border-rose-100">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Disable 2FA</h3>
            <p class="text-xs text-slate-400">Confirm your current password and a live 6-digit TOTP code to disable Two-Factor Authentication.</p>
        </div>

        <div id="disable2faError" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold"></div>

        <form id="disable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitDisable2FA(event)" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-900">Current Password</label>
                <input type="password" id="disablePassword" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42]">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-900">6-Digit Authenticator Code</label>
                <input type="text" id="disableOtp" maxlength="6" required placeholder="123456" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center font-mono text-lg font-black tracking-widest text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42]">
            </div>

            <button type="submit" id="disable2FASubmitBtn" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-xs shadow-md shadow-rose-600/20 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95">
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
        document.getElementById('qrCodeSvg').innerHTML = '<div class="w-48 h-48 flex items-center justify-center text-slate-400"><i class="fa-solid fa-spinner fa-spin text-2xl text-purple-600"></i></div>';

        fetch("{{ route('hotel.2fa.generate') }}", {
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

        fetch("{{ route('hotel.2fa.enable') }}", {
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

        fetch("{{ route('hotel.2fa.disable') }}", {
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
