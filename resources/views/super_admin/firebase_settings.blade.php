@extends('layouts.super_admin')

@section('title', 'Firebase Firestore Realtime Engine')
@section('page_title', 'Firebase Firestore Real-Time Synchronization Center')

@section('content')
<div class="space-y-8">
    <!-- Header Hero Banner -->
    <div class="p-6 md:p-8 rounded-[26px] bg-white text-slate-900 shadow-xs border border-slate-200/80 relative overflow-hidden">
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-50 text-[#150A42] text-xs font-bold mb-4 border border-purple-100 font-mono">
                <i class="fa-solid fa-bolt text-purple-600"></i>
                <span>NOTIFICATION-FREE REAL-TIME NATIVE STREAM</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 mb-2">
                Firebase Firestore Real-Time Sync Controller
            </h2>
            <p class="text-slate-500 text-xs md:text-sm leading-relaxed">
                Upload your Firebase Service Account credentials JSON file to enable native, real-time Firestore database sync across all Flutter TV devices. When hotel details, menus, amenities, or guest info change, Firestore streams trigger instant TV UI updates without any notification popups.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Upload Form & Configuration -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[26px] p-6 md:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold shadow-2xs">
                            <i class="fa-solid fa-file-shield text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Upload Service Account JSON</h3>
                            <p class="text-xs text-slate-500">Provide Firebase credentials for HTTP v1 Push API</p>
                        </div>
                    </div>
                    @if($setting && $setting->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Engine Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200 font-mono uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span> Not Configured
                        </span>
                    @endif
                </div>

                <form action="{{ route('super-admin.firebase-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- File Upload Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 font-mono">
                            Select Firebase service-account.json File <span class="text-rose-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-purple-300 transition-colors bg-[#F8F9FD]">
                            <div class="space-y-2 text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400"></i>
                                <div class="flex text-xs text-slate-500 justify-center">
                                    <label for="credentials_file" class="relative cursor-pointer bg-white rounded-xl font-bold text-[#150A42] hover:text-[#201063] focus-within:outline-none px-3 py-1.5 border border-slate-200 shadow-2xs">
                                        <span>Browse JSON File</span>
                                        <input id="credentials_file" name="credentials_file" type="file" accept=".json" class="sr-only">
                                    </label>
                                    <p class="pl-2 py-1.5 text-slate-400">or drag and drop</p>
                                </div>
                                <p class="text-[11px] text-slate-400">JSON service account file up to 2MB (Encrypted automatically in DB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Direct JSON Text Input (Alternative) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 font-mono">
                            Or Paste Raw JSON Content (Optional)
                        </label>
                        <textarea name="json_text" rows="4" placeholder='{"type": "service_account", "project_id": "...", ...}' class="w-full text-xs font-mono p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#0A051B] text-purple-200 resize-y"></textarea>
                    </div>

                    <!-- Status Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Enable FCM Real-Time Engine</span>
                            <span class="text-[11px] text-slate-500">When enabled, model updates trigger silent FCM notifications.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ ($setting->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                    </div>

                    <button type="submit" class="btn-sa-primary w-full py-3.5 px-6 text-sm shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Save & Encrypt Firebase Credentials</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Status Info & Test Push -->
        <div class="space-y-8">
            <!-- Current Credentials Status Card -->
            <div class="bg-white rounded-[26px] p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                    <span>Current Credentials Info</span>
                    <i class="fa-solid fa-circle-info text-slate-400"></i>
                </h3>

                @if($parsedConfig)
                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-xl bg-[#F8F9FD] border border-slate-200/80">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold font-mono">Firebase Project ID</span>
                            <span class="font-extrabold text-slate-900 text-sm font-mono">{{ $parsedConfig['project_id'] }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-[#F8F9FD] border border-slate-200/80">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold font-mono">Service Account Email</span>
                            <span class="font-bold text-slate-700 font-mono text-[11px] break-all">{{ $parsedConfig['client_email'] }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-between text-[#150A42]">
                            <span class="font-bold text-xs"><i class="fa-solid fa-lock text-purple-600 mr-1.5"></i> Storage Security</span>
                            <span class="font-extrabold text-[10px] bg-purple-200/60 px-2 py-0.5 rounded-md font-mono">AES-256 Encrypted</span>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold space-y-1">
                        <p class="font-bold"><i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> No Credentials Active</p>
                        <p class="text-[11px] text-amber-700">Please upload your Firebase service-account.json file to activate real-time pushes.</p>
                    </div>
                @endif
            </div>

            <!-- Live Test Firestore Database Sync Panel -->
            <div class="bg-white rounded-[26px] p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-database text-[#150A42]"></i>
                    <span>Live Test Firestore DB Sync</span>
                </h3>
                <p class="text-[11px] text-slate-500">Pushes a test document directly into Firebase Firestore Database under collection <code class="bg-[#F8F9FD] px-1 py-0.5 rounded font-mono text-[#150A42] border border-slate-200 text-[10px]">hotels/hotel_test</code>.</p>

                <form action="{{ route('super-admin.firebase-settings.test-firestore') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-rotate text-white"></i>
                        <span>Sync Test Document to Firestore</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection