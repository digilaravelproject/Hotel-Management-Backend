@extends('layouts.hotel_admin')

@section('title', 'Update Profile')
@section('page_title', 'Account & Security')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="border-b border-[#E2E8F0] pb-6 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 flex items-center justify-center text-[#6E1841]">
                <i class="fa-regular fa-user text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-[#1E293B] tracking-tight">Personal Account Details</h3>
                <p class="text-xs text-[#64748B] font-medium">Update your account identity and login credentials.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs space-y-1">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hotel.profile') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-[#1E293B]">Owner / Director Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name', $hotelAdmin->owner_name) }}" required 
                    class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-[#1E293B]">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $hotelAdmin->email) }}" required 
                        class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-[#1E293B]">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $hotelAdmin->phone) }}" required 
                        class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-[#E2E8F0]">
                <h4 class="text-xs font-extrabold text-[#64748B] uppercase tracking-wider mb-4">
                    Change Password <span class="text-[#64748B] font-normal lowercase">(leave blank to keep current)</span>
                </h4>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">New Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#1E293B]">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password" 
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-[#E2E8F0] flex items-center justify-end space-x-4">
                <a href="{{ route('hotel.dashboard') }}" class="px-6 py-3 rounded-xl border border-[#CBD5E1] text-[#1E293B] hover:bg-[#F8FAFC] text-xs font-bold transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#C5A059] hover:bg-[#A88440] text-white font-bold text-xs shadow-md shadow-[#C5A059]/20 transition-all hover:-translate-y-0.5">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Google Authenticator (2FA) Security Card -->
    <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 flex items-center justify-center text-[#6E1841]">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-[#1E293B] tracking-tight">Two-Factor Authentication</h3>
                    <p class="text-xs text-[#64748B] font-medium">Add an extra layer of security using Google Authenticator (TOTP).</p>
                </div>
            </div>
            <div>
                @if($hotelAdmin->google2fa_enabled)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#16A34A] border border-[#16A34A]/20">
                        <i class="fa-solid fa-circle-check me-1.5"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0]">
                        <i class="fa-solid fa-circle-minus me-1.5"></i> Disabled
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-[#1E293B]">Google Authenticator Verification</h4>
                <p class="text-xs text-[#64748B] max-w-md">When enabled, you will be required to enter a 6-digit TOTP code from your mobile app every time you log in.</p>
            </div>
            <div>
                @if($hotelAdmin->google2fa_enabled)
                    <button type="button" onclick="openDisable2FAModal()" class="px-5 py-2.5 rounded-xl border border-[#FEE2E2] text-[#DC2626] hover:bg-[#FEE2E2]/50 text-xs font-bold transition-all">
                        Disable 2FA
                    </button>
                @else
                    <button type="button" onclick="start2FASetup()" class="px-6 py-2.5 rounded-xl bg-[#C5A059] hover:bg-[#A88440] text-white text-xs font-bold shadow-md shadow-[#C5A059]/20 transition-all">
                        Enable 2FA
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div id="setup2FAModal" class="fixed inset-0 z-50 hidden bg-[#0F2537]/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-[#E2E8F0]">
        <button onclick="closeSetup2FAModal()" class="absolute top-6 right-6 text-[#64748B] hover:text-[#1E293B]">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-xl bg-[#F9F5EB] border border-[#C5A059]/30 text-[#6E1841] mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3 class="text-xl font-extrabold text-[#1E293B]">Set Up Google Authenticator</h3>
            <p class="text-xs text-[#64748B]">Scan the QR code below using Google Authenticator app or enter the setup key manually.</p>
        </div>

        <div id="qrContainer" class="flex flex-col items-center space-y-4 py-2">
            <div id="qrCodeSvg" class="p-3 bg-white border border-[#E2E8F0] rounded-xl shadow-inner"></div>
            <div class="text-center">
                <span class="text-xs text-[#64748B] font-semibold uppercase tracking-wider block mb-1">Manual Secret Key</span>
                <code id="secretKeyDisplay" class="px-3 py-1.5 bg-[#F9F5EB] border border-[#C5A059]/30 rounded-lg text-sm font-mono text-[#6E1841] font-bold tracking-widest select-all">Loading...</code>
            </div>
        </div>

        <form id="enable2FAForm" onsubmit="submitEnable2FA(event)" class="space-y-4 pt-2 border-t border-[#E2E8F0]">
            <div class="space-y-1">
                <label class="text-xs font-bold text-[#1E293B]">Enter 6-Digit Authenticator Code</label>
                <input type="text" id="setupOtpInput" maxlength="6" required placeholder="123456" 
                    class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-center font-mono text-lg font-bold tracking-widest text-[#1E293B] focus:outline-none focus:border-[#C5A059]">
            </div>

            <button type="submit" class="w-full py-3 bg-[#C5A059] hover:bg-[#A88440] text-white rounded-xl font-bold text-xs shadow-md shadow-[#C5A059]/20 transition-all">
                Verify Code & Activate 2FA
            </button>
        </form>

        <!-- Recovery Codes Result View -->
        <div id="recoveryCodesSection" class="hidden space-y-4">
            <div class="p-4 bg-[#DCFCE7] border border-[#16A34A]/20 rounded-xl text-[#16A34A] text-xs font-semibold">
                <i class="fa-solid fa-check-circle me-1"></i> 2FA is active! Save these emergency recovery codes in a safe place.
            </div>
            <div id="recoveryCodesList" class="grid grid-cols-2 gap-2 font-mono text-xs text-[#1E293B] bg-[#F8FAFC] p-4 rounded-xl border border-[#E2E8F0] select-all"></div>
            <button onclick="location.reload()" class="w-full py-3 bg-[#6E1841] hover:bg-[#8A1C56] text-white rounded-xl font-bold text-xs">
                Done / Refresh Page
            </button>
        </div>
    </div>
</div>

<!-- 2FA Disable Modal -->
<div id="disable2FAModal" class="fixed inset-0 z-50 hidden bg-[#0F2537]/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-[#E2E8F0]">
        <button onclick="closeDisable2FAModal()" class="absolute top-6 right-6 text-[#64748B] hover:text-[#1E293B]">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-xl bg-[#FEE2E2] text-[#DC2626] mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-xl font-extrabold text-[#1E293B]">Disable 2FA</h3>
            <p class="text-xs text-[#64748B]">Confirm your current password and a live 6-digit TOTP code to disable Two-Factor Authentication.</p>
        </div>

        <form id="disable2FAForm" onsubmit="submitDisable2FA(event)" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-[#1E293B]">Current Password</label>
                <input type="password" id="disablePassword" required class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm font-medium text-[#1E293B] focus:outline-none focus:border-[#C5A059]">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-[#1E293B]">6-Digit Authenticator Code</label>
                <input type="text" id="disableOtp" maxlength="6" required placeholder="123456" class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-center font-mono text-lg font-bold tracking-widest text-[#1E293B] focus:outline-none focus:border-[#C5A059]">
            </div>

            <button type="submit" class="w-full py-3 bg-[#DC2626] hover:bg-[#b91c1c] text-white rounded-xl font-bold text-xs shadow-md shadow-[#DC2626]/20 transition-all">
                Confirm & Disable 2FA
            </button>
        </form>
    </div>
</div>

<script>
    function start2FASetup() {
        document.getElementById('setup2FAModal').classList.remove('hidden');
        fetch("{{ route('hotel.2fa.generate') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('qrCodeSvg').innerHTML = atob(data.qr_code_svg);
            document.getElementById('secretKeyDisplay').innerText = data.secret;
        });
    }

    function closeSetup2FAModal() {
        document.getElementById('setup2FAModal').classList.add('hidden');
    }

    function submitEnable2FA(e) {
        e.preventDefault();
        const otp = document.getElementById('setupOtpInput').value;
        fetch("{{ route('hotel.2fa.enable') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ one_time_password: otp })
        })
        .then(res => res.json())
        .then(data => {
            if (data.recovery_codes) {
                document.getElementById('qrContainer').classList.add('hidden');
                document.getElementById('enable2FAForm').classList.add('hidden');
                document.getElementById('recoveryCodesSection').classList.remove('hidden');
                const list = document.getElementById('recoveryCodesList');
                list.innerHTML = data.recovery_codes.map(c => `<div>${c}</div>`).join('');
            } else {
                alert(data.message || 'Verification failed');
            }
        });
    }

    function openDisable2FAModal() {
        document.getElementById('disable2FAModal').classList.remove('hidden');
    }

    function closeDisable2FAModal() {
        document.getElementById('disable2FAModal').classList.add('hidden');
    }

    function submitDisable2FA(e) {
        e.preventDefault();
        fetch("{{ route('hotel.2fa.disable') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                current_password: document.getElementById('disablePassword').value,
                one_time_password: document.getElementById('disableOtp').value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message.includes('disabled')) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
</script>

@endsection
