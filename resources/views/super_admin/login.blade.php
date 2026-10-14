@extends('layouts.app')

@section('title', 'Super Admin Login - ' . app_name())

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-[#F8FAFC] text-[#1E293B] relative overflow-hidden">
    <!-- Subtle Luxury Header Bar -->
    <header class="w-full px-6 lg:px-12 py-5 bg-white border-b border-[#E2E8F0] shadow-xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }}" class="h-9 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#FBF8F1] text-[#0F2537] border border-[#D4AF37]/40">
                    <i class="fa-solid fa-crown text-[10px] text-[#D4AF37] mr-1.5"></i> Super Admin
                </span>
            </div>

            <a href="{{ route('landing') }}" class="px-3.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#64748B] hover:text-[#1E293B] text-xs font-semibold transition-all inline-flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Return to Website</span>
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative">
        <!-- Ambient Decorative Top Gradient -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-48 bg-gradient-to-b from-[#0F2537]/5 via-transparent to-transparent pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Login Card -->
            <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 space-y-7">
                <!-- Top Badge & Header -->
                <div class="text-center space-y-2.5">
                    <div class="w-14 h-14 rounded-2xl bg-[#0F2537] text-[#D4AF37] flex items-center justify-center mx-auto shadow-md shadow-[#0F2537]/20 border border-[#1B365D]">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-[#1E293B] tracking-tight">Super Admin Portal</h1>
                    <p class="text-xs text-[#64748B]">Executive platform control and master administration</p>
                </div>

                <!-- Error & Status Alerts -->
                @if($errors->any())
                    <div class="p-4 rounded-xl bg-[#FEE2E2] border border-[#DC2626]/20 text-[#DC2626] text-xs space-y-1.5">
                        <div class="flex items-center space-x-2 font-bold">
                            <i class="fa-solid fa-circle-exclamation text-[#DC2626]"></i>
                            <span>Authentication failed:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('status'))
                    <div class="p-4 rounded-xl bg-[#DCFCE7] border border-[#16A34A]/20 text-[#16A34A] text-xs flex items-center space-x-2 font-medium">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('super-admin.login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-[#1E293B]">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@hotel.com" autocomplete="email" autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 transition-all">
                        </div>
                    </div>

                    <div class="space-y-1.5 text-left">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-[#1E293B]">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" id="superAdminPassword" name="password" required placeholder="••••••••" autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-3 bg-[#F8FAFC] border border-[#CBD5E1] rounded-xl text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:bg-white focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 transition-all">
                            <button type="button" onclick="togglePasswordVisibility('superAdminPassword', 'superAdminEyeIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#64748B] hover:text-[#1E293B] transition-colors cursor-pointer">
                                <i id="superAdminEyeIcon" class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center space-x-2 text-[#64748B] hover:text-[#1E293B] cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#CBD5E1] text-[#0F2537] focus:ring-[#D4AF37]/40 cursor-pointer">
                            <span>Remember this terminal</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-[#D4AF37] hover:bg-[#B5952F] text-[#0F2537] font-extrabold text-sm rounded-xl shadow-lg shadow-[#D4AF37]/25 transition-all hover:-translate-y-0.5 flex items-center justify-center space-x-2 cursor-pointer">
                        <span>Sign In to Master Control</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- Alternate Portals Link -->
                <div class="pt-4 border-t border-[#E2E8F0] text-center space-y-2">
                    <p class="text-xs text-[#64748B]">Looking for property management or distribution?</p>
                    <div class="flex items-center justify-center space-x-4 text-xs font-bold">
                        <a href="{{ route('hotel.login') }}" class="text-[#6E1841] hover:underline inline-flex items-center space-x-1">
                            <i class="fa-solid fa-hotel text-[10px]"></i>
                            <span>Hotel Portal</span>
                        </a>
                        <span class="text-[#CBD5E1]">|</span>
                        <a href="{{ route('distributor.login') }}" class="text-[#004B87] hover:underline inline-flex items-center space-x-1">
                            <i class="fa-solid fa-truck-ramp-box text-[10px]"></i>
                            <span>Distributor Portal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="w-full px-6 lg:px-12 py-4 bg-white border-t border-[#E2E8F0] text-xs text-[#64748B]">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ app_name() }}. All rights reserved.</p>
            <p>Developed with precision by <a href="https://digiemperor.com" target="_blank" rel="noopener noreferrer" class="font-bold text-[#0F2537] hover:underline">Digi Emperor</a></p>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs';
        }
    }
</script>
@endsection
