@extends('layouts.super_admin')

@section('title', 'Email & SMTP Settings - Super Admin')
@section('page_title', 'Email & SMTP Settings')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0">
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 mb-1">
                <a href="{{ route('super-admin.settings.index') }}" class="hover:text-[#150A42] transition-colors">Platform Settings</a>
                <span>/</span>
                <span class="text-[#150A42] font-black">Email & SMTP Settings</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Email & SMTP Configuration</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">
                Configure platform SMTP credentials, from address, and automated email event notification triggers for hotel welcome credentials, plan renewals, and expiry alerts.
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-0.5 w-full sm:w-auto">
            <a href="#testEmailSection" 
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-paper-plane text-purple-600 text-xs"></i>
                <span>Test SMTP</span>
            </a>

            <button type="submit" 
                    form="mailSettingsForm" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-floppy-disk text-xs text-amber-300"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Alert -->
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

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- 1. Mailer Driver -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-server"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-[#150A42] tracking-tight leading-tight uppercase font-mono truncate">
                    {{ $setting->mail_mailer ?? 'SMTP' }}
                </div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Active Driver</div>
            </div>
        </div>

        <!-- 2. Host & Port -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-network-wired"></i>
            </div>
            <div class="min-w-0">
                <div class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight leading-tight truncate">
                    {{ $setting->mail_host ? ($setting->mail_host . ':' . $setting->mail_port) : 'Not Configured' }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Host & Port</div>
            </div>
        </div>

        <!-- 3. Sender Identity -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-at"></i>
            </div>
            <div class="min-w-0">
                <div class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight leading-tight truncate" title="{{ $setting->mail_from_address }}">
                    {{ $setting->mail_from_address ?? 'Not Set' }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">From Address</div>
            </div>
        </div>

        <!-- 4. Active Notification Events -->
        @php
            $enabledEventsCount = 0;
            if ($setting->notify_hotel_welcome) $enabledEventsCount++;
            if ($setting->notify_subscription_purchased) $enabledEventsCount++;
            if ($setting->notify_subscription_expiring) $enabledEventsCount++;
            if ($setting->notify_subscription_expired) $enabledEventsCount++;
        @endphp
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $enabledEventsCount }} <span class="text-xs text-slate-400 font-bold">/ 4 Events</span>
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Automated Triggers</div>
            </div>
        </div>
    </div>

    <!-- Main Form Grid -->
    <form id="mailSettingsForm" action="{{ route('super-admin.mail-settings.update') }}" method="POST" class="space-y-6 sm:space-y-8">
        @csrf

        <!-- SMTP Server Credentials Card -->
        <div class="bg-white border border-slate-200/80 rounded-[24px] sm:rounded-[28px] p-5 sm:p-7 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="space-y-0.5">
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center space-x-2">
                        <i class="fa-solid fa-sliders text-purple-700"></i>
                        <span>SMTP Server & Transport Configuration</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Specify the outbound mail relay server credentials used to send automated messages.</p>
                </div>

                <!-- Quick Preset Selector -->
                <div class="flex items-center space-x-1.5 flex-wrap">
                    <span class="text-[11px] font-bold text-slate-400 mr-1">Presets:</span>
                    <button type="button" onclick="applyPreset('gmail')" class="px-2.5 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-[11px] transition-all cursor-pointer">
                        Gmail
                    </button>
                    <button type="button" onclick="applyPreset('outlook')" class="px-2.5 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-[11px] transition-all cursor-pointer">
                        Outlook 365
                    </button>
                    <button type="button" onclick="applyPreset('ses')" class="px-2.5 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-[11px] transition-all cursor-pointer">
                        Amazon SES
                    </button>
                    <button type="button" onclick="applyPreset('mailgun')" class="px-2.5 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#150A42] font-black text-[11px] transition-all cursor-pointer">
                        Mailgun
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <!-- Mailer Driver -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Mail Driver <span class="text-rose-500">*</span>
                    </label>
                    <select id="mail_mailer" name="mail_mailer" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                        <option value="smtp" {{ ($setting->mail_mailer ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (Recommended)</option>
                        <option value="sendmail" {{ ($setting->mail_mailer ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail (Local Server)</option>
                        <option value="log" {{ ($setting->mail_mailer ?? '') === 'log' ? 'selected' : '' }}>Log (Dev Testing / laravel.log)</option>
                    </select>
                </div>

                <!-- Host -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        SMTP Host Server <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="mail_host" 
                           name="mail_host" 
                           value="{{ old('mail_host', $setting->mail_host) }}" 
                           placeholder="e.g. smtp.gmail.com or smtp.mailgun.org" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                </div>

                <!-- Port -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Port <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           id="mail_port" 
                           name="mail_port" 
                           value="{{ old('mail_port', $setting->mail_port ?? 587) }}" 
                           placeholder="587" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                    <span class="text-[10px] text-slate-400 mt-1 block">Standard: 587 (TLS), 465 (SSL), 25</span>
                </div>

                <!-- Encryption -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Encryption Protocol
                    </label>
                    <select id="mail_encryption" name="mail_encryption" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                        <option value="tls" {{ ($setting->mail_encryption ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (STARTTLS - Port 587)</option>
                        <option value="ssl" {{ ($setting->mail_encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                        <option value="null" {{ empty($setting->mail_encryption) ? 'selected' : '' }}>None (Unencrypted - Port 25)</option>
                    </select>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        SMTP Username / Account
                    </label>
                    <input type="text" 
                           id="mail_username" 
                           name="mail_username" 
                           value="{{ old('mail_username', $setting->mail_username) }}" 
                           placeholder="user@yourdomain.com or API Key" 
                           autocomplete="off"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                </div>

                <!-- Password -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        SMTP Password / App Secret
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="mail_password" 
                               name="mail_password" 
                               placeholder="{{ $setting->mail_password ? '•••••••••••••••• (Leave blank to keep existing)' : 'Enter SMTP password or app password' }}" 
                               autocomplete="new-password"
                               class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                        <button type="button" 
                                onclick="togglePasswordVisibility('mail_password', this)" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 cursor-pointer">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">For Gmail, use a 16-character Google App Password (not your primary Google password).</span>
                </div>

                <!-- From Address -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Mail From Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="mail_from_address" 
                           name="mail_from_address" 
                           value="{{ old('mail_from_address', $setting->mail_from_address) }}" 
                           placeholder="notifications@paxtvhospitality.com" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                </div>

                <!-- From Name -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        Mail From Display Name
                    </label>
                    <input type="text" 
                           id="mail_from_name" 
                           name="mail_from_name" 
                           value="{{ old('mail_from_name', $setting->mail_from_name ?? 'PaxTV Hospitality') }}" 
                           placeholder="PaxTV Hospitality System" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
                </div>
            </div>
        </div>

        <!-- Automated Event Notification Triggers Card -->
        <div class="bg-white border border-slate-200/80 rounded-[24px] sm:rounded-[28px] p-5 sm:p-7 shadow-xs space-y-6">
            <div class="pb-4 border-b border-slate-100">
                <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-bell-ring text-amber-500"></i>
                    <span>Automated Email Event Notification Triggers</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Toggle which business events automatically send transactional emails to hotel managers.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Event 1: Hotel Welcome & Credentials -->
                <div class="p-4.5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-white hover:border-purple-300 transition-all flex items-start justify-between gap-4 shadow-2xs">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center shrink-0 text-sm shadow-2xs mt-0.5">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-black text-slate-900">Hotel Onboarding & Credentials</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                Automatically emails login URL, username, and temporary password to the newly created hotel admin when created by Super Admin or Distributor.
                            </p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" name="notify_hotel_welcome" value="1" class="sr-only peer" {{ $setting->notify_hotel_welcome ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>
                </div>

                <!-- Event 2: Subscription Purchased / Renewed -->
                <div class="p-4.5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-white hover:border-purple-300 transition-all flex items-start justify-between gap-4 shadow-2xs">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0 text-sm shadow-2xs mt-0.5">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-black text-slate-900">Subscription Purchased / Renewed</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                Dispatches confirmation email with plan name, validity dates, allowed TV count, paid amount, and license key upon payment activation.
                            </p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" name="notify_subscription_purchased" value="1" class="sr-only peer" {{ $setting->notify_subscription_purchased ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>
                </div>

                <!-- Event 3: Subscription Expiring Soon Alert -->
                <div class="p-4.5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-white hover:border-purple-300 transition-all flex items-start justify-between gap-4 shadow-2xs">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0 text-sm shadow-2xs mt-0.5">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-black text-slate-900">Subscription Expiring Soon Alert</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                Automated cron alert sent at 7 days, 3 days, and 1 day before expiration with direct 1-click renewal link to prevent TV service interruptions.
                            </p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" name="notify_subscription_expiring" value="1" class="sr-only peer" {{ $setting->notify_subscription_expiring ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>
                </div>

                <!-- Event 4: Subscription Expired Notice -->
                <div class="p-4.5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-white hover:border-purple-300 transition-all flex items-start justify-between gap-4 shadow-2xs">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center shrink-0 text-sm shadow-2xs mt-0.5">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-black text-slate-900">Subscription Expired Notice</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                Immediate notification sent once plan expires informing hotel admin that TV screens and backend features are locked with instant renewal action.
                            </p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" name="notify_subscription_expired" value="1" class="sr-only peer" {{ $setting->notify_subscription_expired ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                    </label>
                </div>
            </div>
        </div>
    </form>

    <!-- Live Test Email Card -->
    <div id="testEmailSection" class="bg-white border border-slate-200/80 rounded-[24px] sm:rounded-[28px] p-5 sm:p-7 shadow-xs space-y-4">
        <div class="pb-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane text-emerald-600"></i>
                    <span>Live SMTP Connection Test</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Send a real test email to verify your configured SMTP credentials and host connection.</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-purple-50 text-purple-700 border border-purple-200/60 self-start sm:self-auto">
                Instant Diagnostics
            </span>
        </div>

        <form action="{{ route('super-admin.mail-settings.test') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @csrf
            <div class="relative flex-1">
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="email" 
                       name="test_email" 
                       required 
                       placeholder="Enter your email to receive test message (e.g. admin@company.com)..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-[#150A42] transition-all">
            </div>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center space-x-2 active:scale-95 cursor-pointer shrink-0">
                <i class="fa-solid fa-bolt text-amber-300 text-xs"></i>
                <span>Send Live Test Mail</span>
            </button>
        </form>
    </div>

</div>

<script>
    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
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

    function applyPreset(type) {
        const mailer = document.getElementById('mail_mailer');
        const host = document.getElementById('mail_host');
        const port = document.getElementById('mail_port');
        const enc = document.getElementById('mail_encryption');

        mailer.value = 'smtp';

        if (type === 'gmail') {
            host.value = 'smtp.gmail.com';
            port.value = '587';
            enc.value = 'tls';
        } else if (type === 'outlook') {
            host.value = 'smtp.office365.com';
            port.value = '587';
            enc.value = 'tls';
        } else if (type === 'ses') {
            host.value = 'email-smtp.us-east-1.amazonaws.com';
            port.value = '587';
            enc.value = 'tls';
        } else if (type === 'mailgun') {
            host.value = 'smtp.mailgun.org';
            port.value = '587';
            enc.value = 'tls';
        }
    }
</script>
@endsection

