@extends('layouts.super_admin')

@section('title', 'Profile & Account Security - Super Admin')
@section('page_title', 'Profile & Account Security')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Smooth responsive on Mobile -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Profile & Account Security</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Manage master administrator login credentials, security email, password, and Google Authenticator Two-Factor Authentication (2FA).</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-0.5 w-full sm:w-auto">
            @if($superAdmin->google2fa_enabled)
                <button type="button" 
                        onclick="openDisable2FAModal()" 
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 shadow-2xs transition-all space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-shield-xmark text-rose-500 text-xs"></i>
                    <span>Disable 2FA</span>
                </button>
            @else
                <button type="button" 
                        onclick="start2FASetup()" 
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200 shadow-2xs transition-all space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-shield-check text-emerald-600 text-xs"></i>
                    <span>Enable 2FA</span>
                </button>
            @endif

            <button type="submit" 
                    form="profileForm" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Profile</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Alert (Success is handled globally by layout) -->
    @if($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Stats Metric Cards (2x2 on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- 1. Administrator Role -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">Master Admin</div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Full System Access</div>
            </div>
        </div>

        <!-- 2. Master Account Email -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="min-w-0">
                <div class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight leading-tight truncate" title="{{ $superAdmin->email }}">
                    {{ $superAdmin->email }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Login Email ID</div>
            </div>
        </div>

        <!-- 3. Two-Factor Authentication -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ $superAdmin->google2fa_enabled ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold {{ $superAdmin->google2fa_enabled ? 'text-emerald-600' : 'text-amber-600' }} tracking-tight leading-tight flex items-center space-x-1.5">
                    @if($superAdmin->google2fa_enabled)
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>2FA Active</span>
                    @else
                        <span>Disabled</span>
                    @endif
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Google Authenticator</div>
            </div>
        </div>

        <!-- 4. Security Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $superAdmin->google2fa_enabled ? 'Enhanced' : 'Standard' }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-emerald-600 truncate mt-0.5">Bcrypt & Salt Protected</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-7 items-start">
        
        <!-- Left 7 Cols: Master Credentials & Password Form -->
        <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                        <i class="fa-regular fa-user text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Super Admin Credentials</h3>
                        <p class="text-xs text-slate-500 font-medium">Update administrator login email address and account access password.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-mono font-extrabold rounded-md bg-purple-50 text-[#150A42] border border-purple-100">
                    Primary
                </span>
            </div>

            <form id="profileForm" action="{{ route('super-admin.profile') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Master Email Address <span class="text-rose-500">*</span></span>
                        <span class="text-[10px] text-slate-400 font-mono">Used for login & 2FA</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $superAdmin->email) }}" 
                               required 
                               class="w-full pl-10 pr-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400">All high-level security verification notices and session alerts will be sent here.</p>
                </div>

                <!-- Password Update Section -->
                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider font-mono">
                            Change Password
                        </h4>
                        <p class="text-[11px] text-slate-400">Leave both fields empty if you do not wish to modify your current password.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" 
                                       id="newPasswordInput"
                                       name="password" 
                                       placeholder="Min 6 characters" 
                                       class="w-full pl-10 pr-10 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                                <button type="button" onclick="togglePasswordVisibility('newPasswordInput', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Confirm New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" 
                                       id="confirmPasswordInput"
                                       name="password_confirmation" 
                                       placeholder="Repeat password" 
                                       class="w-full pl-10 pr-10 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] transition-all">
                                <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save Account Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right 5 Cols: Google Authenticator (2FA) Security Card -->
        <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-[26px] p-5 sm:p-8 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-[#150A42] shrink-0 shadow-2xs">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Two-Factor Authentication</h3>
                        <p class="text-xs text-slate-500 font-medium">Google Authenticator (TOTP)</p>
                    </div>
                </div>
                <div>
                    @if($superAdmin->google2fa_enabled)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider font-mono">
                            Disabled
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                    When 2FA is active, logging in requires entering a dynamic 6-digit TOTP code generated on your mobile device, protecting your master account even if your password is compromised.
                </p>

                <div class="p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80 space-y-2">
                    <div class="flex items-center space-x-2 text-xs font-bold text-slate-800">
                        <i class="fa-solid fa-mobile-screen-button text-[#150A42]"></i>
                        <span>Compatible Authenticator Apps:</span>
                    </div>
                    <ul class="text-[11px] text-slate-500 space-y-1 pl-5 list-disc font-medium">
                        <li>Google Authenticator (iOS & Android)</li>
                        <li>Microsoft Authenticator</li>
                        <li>Authy / 1Password</li>
                    </ul>
                </div>

                <div class="pt-2">
                    @if($superAdmin->google2fa_enabled)
                        <button type="button" 
                                onclick="openDisable2FAModal()" 
                                class="w-full py-2.5 px-4 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs transition-all flex items-center justify-center space-x-2 cursor-pointer shadow-2xs">
                            <i class="fa-solid fa-shield-xmark text-xs text-rose-500"></i>
                            <span>Disable Two-Factor Authentication</span>
                        </button>
                    @else
                        <button type="button" 
                                onclick="start2FASetup()" 
                                class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition-all flex items-center justify-center space-x-2 cursor-pointer shadow-md shadow-emerald-600/20">
                            <i class="fa-solid fa-shield-check text-xs"></i>
                            <span>Enable Google Authenticator (2FA)</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div id="setup2FAModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-[28px] max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-slate-200">
        <button onclick="closeSetup2FAModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 cursor-pointer p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] mx-auto flex items-center justify-center text-xl border border-purple-100 shadow-xs">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 tracking-tight">Set Up Google Authenticator</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto font-medium">Scan the QR code below using your authenticator app or enter the manual key.</p>
        </div>

        <div id="qrContainer" class="flex flex-col items-center space-y-4 py-2">
            <div id="qrCodeSvg" class="p-3 bg-white border border-slate-200 rounded-2xl shadow-inner"></div>
            <div class="text-center">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1 font-mono">Manual Secret Key</span>
                <code id="secretKeyDisplay" class="px-3 py-1.5 bg-[#F8F9FD] rounded-lg text-xs font-mono text-[#150A42] font-extrabold tracking-widest select-all border border-slate-200">Loading...</code>
            </div>
        </div>

        <div id="setup2faError" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold"></div>

        <form id="enable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitEnable2FA(event)" class="space-y-4 pt-2 border-t border-slate-100">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">Enter 6-Digit Authenticator Code</label>
                <input type="text" 
                       id="setupOtpInput" 
                       maxlength="6" 
                       required 
                       placeholder="123456" 
                       class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-center font-mono text-xl font-bold tracking-widest focus:outline-none focus:border-[#150A42] focus:ring-2 focus:ring-[#150A42]/15 text-slate-900">
            </div>

            <button type="submit" 
                    id="setup2FASubmitBtn" 
                    class="w-full py-3 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                <span>Verify Code & Activate 2FA</span>
            </button>
        </form>

        <!-- Recovery Codes Result View -->
        <div id="recoveryCodesSection" class="hidden space-y-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0"></i>
                <span>2FA is now active! Please copy and store these emergency recovery codes safely.</span>
            </div>
            <div id="recoveryCodesList" class="grid grid-cols-2 gap-2 font-mono text-xs text-slate-900 bg-[#F8F9FD] p-4 rounded-xl border border-slate-200 select-all"></div>
            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="copyRecoveryCodes()" id="copyCodesBtn" class="flex-1 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i class="fa-regular fa-copy"></i>
                    <span>Copy All Codes</span>
                </button>
                <button type="button" onclick="location.reload()" class="flex-1 py-2.5 bg-[#150A42] hover:bg-[#201063] text-white rounded-xl font-bold text-xs transition-all cursor-pointer">
                    Done / Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Disable Modal -->
<div id="disable2FAModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-6 relative border border-slate-200">
        <button onclick="closeDisable2FAModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 cursor-pointer p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="space-y-2 text-center">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center text-xl border border-rose-100 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 tracking-tight">Disable Two-Factor Authentication</h3>
            <p class="text-xs text-slate-500">Confirm your current password and a live 6-digit TOTP code to disable 2FA.</p>
        </div>

        <div id="disable2faError" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold"></div>

        <form id="disable2FAForm" data-ajax-form="true" data-no-loader="true" onsubmit="submitDisable2FA(event)" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">Current Password</label>
                <input type="password" 
                       id="disablePassword" 
                       required 
                       class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] text-slate-900">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-700">6-Digit Authenticator Code</label>
                <input type="text" 
                       id="disableOtp" 
                       maxlength="6" 
                       required 
                       placeholder="123456" 
                       class="w-full px-4 py-2.5 bg-[#F8F9FD] border border-slate-200 rounded-xl text-center font-mono text-xl font-bold tracking-widest focus:outline-none focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] text-slate-900">
            </div>

            <button type="submit" 
                    id="disable2FASubmitBtn" 
                    class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-extrabold text-xs shadow-md shadow-rose-600/20 transition-all cursor-pointer flex items-center justify-center space-x-2">
                <span>Confirm & Disable 2FA</span>
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

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
        document.getElementById('qrCodeSvg').innerHTML = '<div class="w-48 h-48 flex items-center justify-center text-slate-400"><i class="fa-solid fa-spinner fa-spin text-2xl text-[#150A42]"></i></div>';

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
                list.innerHTML = data.recovery_codes.map(c => `<div class="p-2 bg-white rounded-lg border border-slate-200 text-center font-mono font-bold text-xs text-slate-900">${c}</div>`).join('');
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
            btn.innerHTML = '<i class="fa-solid fa-check mr-1 text-emerald-600"></i> Copied!';
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
