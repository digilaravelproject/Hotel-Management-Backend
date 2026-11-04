@extends('layouts.super_admin')

@section('title', 'Firebase Realtime Engine - Super Admin')
@section('page_title', 'Firebase Realtime Engine')

@section('content')
<div class="space-y-6 sm:space-y-7">

    <!-- Header & Action Bar: Strict 1-Line on Desktop + Smooth responsive on Mobile -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-sans">Firebase Realtime Engine</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5 max-w-2xl">Configure Google Firebase service credentials for Firestore Database syncing and HTTP v1 silent push updates across all in-room Android TV suites.</p>
        </div>
        
        <!-- Action Buttons: Strict 1 Line (flex-nowrap) -->
        <div class="flex items-center justify-start sm:justify-end gap-2 sm:gap-2.5 shrink-0 flex-nowrap overflow-x-auto no-scrollbar py-1 w-full sm:w-auto">
            <a href="#testFirestoreSection" 
               class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200/90 shadow-2xs transition-all hover:border-slate-300 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-database text-emerald-600 text-xs"></i>
                <span>Test Firestore</span>
            </a>

            <a href="#credentialsCard" 
               class="inline-flex items-center justify-center px-4.5 py-2.5 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-lg shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 space-x-1.5 cursor-pointer whitespace-nowrap shrink-0">
                <i class="fa-solid fa-shield-halved text-xs"></i>
                <span>Manage Credentials</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                <span>Please fix the following errors:</span>
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
        <!-- 1. Engine Operational Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ ($setting && $setting->is_active && $parsedConfig) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-satellite-dish"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold {{ ($setting && $setting->is_active && $parsedConfig) ? 'text-emerald-600' : 'text-amber-600' }} tracking-tight leading-tight flex items-center space-x-1.5">
                    @if($setting && $setting->is_active && $parsedConfig)
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Active</span>
                    @else
                        <span>Inactive</span>
                    @endif
                </div>
                <div class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Realtime Push Engine</div>
            </div>
        </div>

        <!-- 2. Firebase Project ID -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-fire"></i>
            </div>
            <div class="min-w-0">
                <div class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight truncate" title="{{ $parsedConfig['project_id'] ?? 'Not Configured' }}">
                    {{ $parsedConfig ? $parsedConfig['project_id'] : 'Not Setup' }}
                </div>
                <div class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate mt-0.5">Google Cloud Project</div>
            </div>
        </div>

        <!-- 3. Connected In-Room TVs -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-purple-50 text-[#150A42] border border-purple-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-tv"></i>
            </div>
            <div class="min-w-0">
                <div class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $totalDevices }}</div>
                <div class="text-[10px] sm:text-xs font-semibold text-purple-700 truncate mt-0.5">
                    {{ $activeTokens }} with FCM Tokens
                </div>
            </div>
        </div>

        <!-- 4. Credential Encryption -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-5 shadow-xs flex items-center space-x-2.5 sm:space-x-3.5 hover:shadow-md transition-all">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-sm sm:text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="min-w-0">
                <div class="text-lg sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">AES-256</div>
                <div class="text-[10px] sm:text-xs font-semibold text-emerald-600 truncate mt-0.5">Encrypted in DB</div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Left 2 Cols (Credentials & Upload) | Right 1 Col (Active Info & Live Testing) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-7">
        
        <!-- Left: Upload & Credentials Form -->
        <div class="lg:col-span-2 space-y-6">
            <div id="credentialsCard" class="bg-white rounded-[26px] p-5 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 border border-purple-100 text-[#150A42] flex items-center justify-center font-bold shadow-2xs">
                            <i class="fa-solid fa-file-shield text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Upload Service Account Credentials</h3>
                            <p class="text-xs text-slate-500">Provide Firebase private key JSON for Google HTTP v1 Push API</p>
                        </div>
                    </div>
                    @if($setting && $setting->is_active && $parsedConfig)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Live
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider font-mono">
                            Unconfigured
                        </span>
                    @endif
                </div>

                <form action="{{ route('super-admin.firebase-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="firebaseForm">
                    @csrf
                    
                    <!-- Drag & Drop / File Input Zone -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider font-mono flex items-center justify-between">
                            <span>Service Account JSON File <span class="text-rose-500">*</span></span>
                            <span class="text-[10px] text-slate-400 font-normal">service-account.json</span>
                        </label>
                        
                        <div class="relative border-2 border-dashed border-slate-200 hover:border-[#150A42] rounded-2xl p-6 bg-[#F8F9FD] hover:bg-purple-50/20 transition-all text-center cursor-pointer group" onclick="document.getElementById('credentials_file').click()">
                            <input id="credentials_file" 
                                   name="credentials_file" 
                                   type="file" 
                                   accept=".json,application/json" 
                                   class="hidden"
                                   onchange="handleJsonFileSelection(this)">
                            
                            <div id="fileUploadPlaceholder" class="space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#150A42] flex items-center justify-center mx-auto text-xl group-hover:scale-110 transition-transform shadow-2xs">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="text-xs font-bold text-slate-800">Choose service-account.json file</div>
                                <p class="text-[11px] text-slate-400">Drag and drop or click to browse. Max 2MB, automatically encrypted.</p>
                            </div>

                            <div id="fileUploadSelected" class="hidden items-center justify-center space-x-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-[#150A42] text-white flex items-center justify-center text-xl shadow-2xs shrink-0">
                                    <i class="fa-solid fa-file-code"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <div id="selectedFileName" class="text-xs font-extrabold text-slate-900 truncate max-w-[260px]"></div>
                                    <div id="selectedFileMeta" class="text-[11px] text-emerald-600 font-bold flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Valid Service Account Key Ready</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct JSON Text Input (Alternative / Collapsible) -->
                    <div class="space-y-2">
                        <button type="button" onclick="toggleRawJsonBox()" class="text-xs font-bold text-[#150A42] hover:text-[#201063] flex items-center space-x-1.5 cursor-pointer">
                            <i id="rawJsonChevron" class="fa-solid fa-chevron-right text-[10px] transition-transform"></i>
                            <span>Or Paste Raw JSON Content Directly</span>
                        </button>
                        
                        <div id="rawJsonContainer" class="hidden space-y-1.5 pt-1">
                            <textarea name="json_text" 
                                      rows="5" 
                                      placeholder='{"type": "service_account", "project_id": "...", "private_key": "...", "client_email": "..."}' 
                                      class="w-full text-xs font-mono p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-[#150A42]/15 focus:border-[#150A42] bg-[#0A051B] text-purple-200 resize-y leading-relaxed"></textarea>
                            <span class="text-[11px] text-slate-400 font-medium block">Paste the full JSON object downloaded from Firebase Console &rarr; Project Settings &rarr; Service Accounts.</span>
                        </div>
                    </div>

                    <!-- Status Toggle Card -->
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-[#F8F9FD] border border-slate-200/80">
                        <div class="pr-4">
                            <span class="text-xs font-bold text-slate-900 block">Enable Real-Time Engine (FCM & Firestore)</span>
                            <span class="text-[11px] text-slate-500">When enabled, any catalog, hotel, or template update dispatches real-time silent sync events to TVs.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="is_active" value="1" {{ ($setting->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#150A42]"></div>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-lock text-purple-300"></i>
                        <span>Save & Encrypt Firebase Credentials</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Status Info & Live Testing Diagnostics -->
        <div class="space-y-6">
            <!-- Current Credentials Status Card -->
            <div class="bg-white rounded-[26px] p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-key text-purple-600"></i>
                        <span>Active Credentials Info</span>
                    </h3>
                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-purple-50 text-[#150A42] border border-purple-100">
                        HTTP v1
                    </span>
                </div>

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
                            <span class="font-bold text-xs flex items-center space-x-1.5">
                                <i class="fa-solid fa-shield-check text-purple-600"></i>
                                <span>Storage Security</span>
                            </span>
                            <span class="font-extrabold text-[10px] bg-purple-200/60 text-[#150A42] px-2 py-0.5 rounded-md font-mono">
                                AES-256 Encrypted
                            </span>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold space-y-1.5">
                        <p class="font-bold flex items-center space-x-1.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                            <span>No Active Firebase Credentials</span>
                        </p>
                        <p class="text-[11px] text-amber-700 font-normal leading-relaxed">
                            Upload your Firebase service-account.json file to activate real-time silent push updates and Firestore synchronization.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Live Test 1: Firestore Database Sync -->
            <div id="testFirestoreSection" class="bg-white rounded-[26px] p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-2xs">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Live Test Firestore Sync</h3>
                        <p class="text-[11px] text-slate-400">Direct write into Firestore collection</p>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Sends a test document write directly into Firestore collection <code class="bg-[#F8F9FD] px-1.5 py-0.5 rounded font-mono text-[#150A42] border border-slate-200 text-[10px]">hotels/hotel_1/rooms</code> to verify end-to-end communication.
                </p>

                <form action="{{ route('super-admin.firebase-settings.test-firestore') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-rotate"></i>
                        <span>Run Live Firestore Test</span>
                    </button>
                </form>
            </div>

            <!-- Live Test 2: Silent FCM Push -->
            <div class="bg-white rounded-[26px] p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-[#150A42] flex items-center justify-center text-sm shadow-2xs">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Live Test Silent FCM Push</h3>
                        <p class="text-[11px] text-slate-400">Silent data notification to TVs</p>
                    </div>
                </div>

                <form action="{{ route('super-admin.firebase-settings.test-push') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">Dispatch Target</label>
                        <select name="target_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 bg-[#F8F9FD]">
                            <option value="topic">Topic: all_tvs (Broadcast to all TVs)</option>
                            <option value="token">Specific Device FCM Token</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">Topic Name or Device Token</label>
                        <input type="text" name="target_value" value="all_tvs" required placeholder="all_tvs or device registration token" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-medium text-slate-800 bg-[#F8F9FD]">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#150A42] hover:bg-[#201063] text-white font-extrabold text-xs shadow-md shadow-[#150A42]/20 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Dispatch Test Silent Push</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleRawJsonBox() {
        const container = document.getElementById('rawJsonContainer');
        const chevron = document.getElementById('rawJsonChevron');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            chevron.classList.add('rotate-90');
        } else {
            container.classList.add('hidden');
            chevron.classList.remove('rotate-90');
        }
    }

    function handleJsonFileSelection(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeKb = (file.size / 1024).toFixed(1);
            
            document.getElementById('selectedFileName').textContent = file.name + ' (' + sizeKb + ' KB)';
            document.getElementById('fileUploadPlaceholder').classList.add('hidden');
            document.getElementById('fileUploadSelected').classList.remove('hidden');
            document.getElementById('fileUploadSelected').classList.add('flex');
        }
    }
</script>
@endsection
