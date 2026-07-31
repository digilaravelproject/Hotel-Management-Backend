@extends('layouts.landing')

@section('title', 'Welcome to HotelTV - Premium Hotel TV & Management System')

@section('content')
<div class="relative overflow-hidden bg-slate-50 text-slate-800 font-sans min-h-screen">
    <!-- Subtle Background Glow -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-32 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Glassmorphism Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-slate-200/80 transition-all px-6 md:px-12 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 text-slate-900 font-extrabold text-xl tracking-tight">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-tv text-lg"></i>
                </div>
                <span>Hotel<span class="text-indigo-600">TV</span> Connect</span>
            </a>

            <!-- Status Flash notifications -->
            @if(session('error'))
                <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center space-x-3">
                @if(Auth::guard('hotel_admin')->check())
                    <a href="{{ route('hotel.dashboard') }}" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="relative group">
                        <button class="px-4 py-2.5 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs flex items-center space-x-2">
                            <span>Account</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 z-50">
                            <a href="{{ route('hotel.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-gauge text-indigo-600"></i>
                                <span>Dashboard</span>
                            </a>
                            <form action="{{ route('hotel.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 text-left">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('hotel.login') }}" class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all">
                        Hotel Login
                    </a>
                    
                    <div class="relative group">
                        <button class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                            <span>Access Portal</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 z-50 space-y-1">
                            <button onclick="openRegisterModal()" class="w-full flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 text-left">
                                <i class="fa-solid fa-user-plus text-indigo-600"></i>
                                <span>Register Hotel</span>
                            </button>
                            <a href="{{ route('hotel.login') }}" class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-right-to-bracket text-emerald-600"></i>
                                <span>Hotel Login</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('super-admin.login') }}" class="flex items-center space-x-2 px-3 py-2 rounded-xl text-[11px] font-semibold text-slate-400 hover:text-slate-800">
                                <i class="fa-solid fa-lock"></i>
                                <span>Super Admin</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-16 md:py-24 px-6 md:px-12 text-center max-w-5xl mx-auto space-y-8">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
            <span>Next Generation Hospitality TV System</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
            Connect Your Rooms with <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-rose-600 bg-clip-text text-transparent">Premium Smart TV Services</span>
        </h1>

        <p class="text-base sm:text-lg text-slate-600 font-medium max-w-2xl mx-auto leading-relaxed">
            An all-in-one entertainment and custom branding dashboard portal for modern hotels. Seamlessly register your hotel, purchase custom licenses, and manage guest engagement with a few clicks.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
            <button onclick="openRegisterModal()" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xl shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                Register Your Hotel Now <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
            <a href="#plans" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-sm transition-all">
                View Plans & Pricing
            </a>
        </div>

        <!-- TV Mockup Card -->
        <div class="mt-12 max-w-3xl mx-auto bg-slate-950 p-3 sm:p-4 rounded-3xl border-8 sm:border-12 border-slate-800 shadow-2xl shadow-slate-300 aspect-video relative overflow-hidden text-left text-white">
            <div class="h-full bg-gradient-to-br from-slate-900 to-indigo-950 p-6 rounded-2xl flex flex-col justify-between border border-white/10">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-hotel text-indigo-400"></i>
                        <span class="font-extrabold text-xs sm:text-sm">Grand Palace & Spa</span>
                    </div>
                    <span class="text-[10px] font-bold bg-white/10 px-3 py-1 rounded-full">Welcome, Room 204!</span>
                </div>

                <div class="text-center space-y-2 my-auto">
                    <i class="fa-regular fa-circle-play text-4xl sm:text-5xl text-indigo-400 cursor-pointer hover:scale-110 transition-transform"></i>
                    <h4 class="text-base sm:text-lg font-extrabold tracking-tight">Press Play to Start Streaming</h4>
                    <p class="text-[11px] text-slate-400">Access 100+ live HD channels & hotel services</p>
                </div>

                <div class="grid grid-cols-3 gap-2 border-t border-white/10 pt-3">
                    <div class="p-2 rounded-xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-bold text-[10px] sm:text-xs text-center"><i class="fa-solid fa-tv mr-1.5"></i>Live TV</div>
                    <div class="p-2 rounded-xl bg-white/5 border border-white/10 font-semibold text-[10px] sm:text-xs text-center text-slate-300"><i class="fa-solid fa-utensils mr-1.5"></i>Room Service</div>
                    <div class="p-2 rounded-xl bg-white/5 border border-white/10 font-semibold text-[10px] sm:text-xs text-center text-slate-300"><i class="fa-solid fa-bell mr-1.5"></i>Concierge</div>
                </div>
            </div>
        </div>

        <!-- Highlights Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto pt-6">
            <div class="p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm space-y-1">
                <h3 class="text-3xl font-extrabold text-indigo-600">100%</h3>
                <p class="text-xs font-bold text-slate-500">Cloud Managed</p>
            </div>
            <div class="p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm space-y-1">
                <h3 class="text-3xl font-extrabold text-indigo-600">10k+</h3>
                <p class="text-xs font-bold text-slate-500">Active Screens</p>
            </div>
            <div class="p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm space-y-1">
                <h3 class="text-3xl font-extrabold text-indigo-600">24/7</h3>
                <p class="text-xs font-bold text-slate-500">Priority Support</p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="plans" class="py-20 px-6 md:px-12 bg-white border-t border-slate-200/80">
        <div class="max-w-6xl mx-auto space-y-12 text-center">
            <div class="space-y-3 max-w-2xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Flexible Pricing For Any Scale</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500">
                    Choose a plan designed for your hotel's room count. Every registration grants an instant 16-digit license key to authorize and link your guest rooms.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all flex flex-col justify-between space-y-6 text-left relative {{ $plan->room_count === 50 ? 'border-2 border-indigo-600 shadow-indigo-600/10' : '' }}">
                        @if($plan->room_count === 50)
                            <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 font-extrabold text-[10px] uppercase">Most Suggested</span>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900">{{ $plan->name }}</h3>
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-extrabold text-[10px] uppercase">
                                    Up to {{ $plan->room_count }} Rooms
                                </span>
                            </div>

                            <div class="text-3xl font-extrabold text-slate-900">
                                ₹{{ number_format($plan->price, 0) }}<span class="text-xs text-slate-400 font-normal">/month</span>
                            </div>

                            <ul class="space-y-2.5 text-xs text-slate-600 font-semibold">
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Authorize up to {{ $plan->room_count }} TVs</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Instant License Code</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span>Custom Guest Welcome Text</span>
                                </li>
                                @if($plan->room_count >= 50)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                        <span>Priority Dashboard Analytics</span>
                                    </li>
                                @endif
                                @if($plan->room_count >= 100)
                                    <li class="flex items-center space-x-2">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                        <span>Dedicated API & Support</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <button onclick="openRegisterModalWithPlan({{ $plan->id }}, {{ $plan->room_count }})" class="w-full py-3 px-4 rounded-xl font-bold text-xs transition-all {{ $plan->room_count === 50 ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200' }}">
                            Select {{ $plan->name }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 px-6 md:px-12 bg-white border-t border-slate-200/80">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-xs text-slate-500">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-tv"></i>
                </div>
                <span class="text-slate-900 font-extrabold text-base">HotelTV Connect</span>
            </div>

            <p class="font-semibold text-center">© {{ date('Y') }} HotelTV Management System. All rights reserved.</p>

            <div class="flex items-center space-x-3">
                <a href="{{ route('hotel.login') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold">
                    Hotel Login
                </a>
                <a href="{{ route('super-admin.login') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold">
                    Super Admin
                </a>
            </div>
        </div>
    </footer>
</div>

<!-- Registration Modal Overlay -->
<div id="registerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-xl p-6 sm:p-8 space-y-6 shadow-2xl my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-extrabold text-slate-900">Hotel Registration</h3>
            <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form id="registerForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div id="registerError" class="hidden p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold"></div>

            <!-- Owner Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">Personal Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Owner Name</label>
                        <input type="text" name="owner_name" required placeholder="e.g. John Doe" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" required placeholder="e.g. 9876543210" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Email Address</label>
                        <input type="email" name="email" required placeholder="username@example.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Password</label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Hotel Section -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">Hotel Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Name</label>
                        <input type="text" name="hotel_name" required placeholder="e.g. Grand Resort" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Location / City</label>
                        <input type="text" name="hotel_location" required placeholder="e.g. Mumbai, India" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Logo</label>
                        <input type="file" name="hotel_logo" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Hotel Cover Image</label>
                        <input type="file" name="hotel_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Total Room Count</label>
                    <input type="number" name="room_count" id="roomCountInput" min="1" required placeholder="e.g. 50" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-500">
                    
                    <div id="suggestedPlanBox" class="hidden p-4 rounded-2xl bg-indigo-50 border border-indigo-200 space-y-1 mt-2">
                        <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider block">Suggested Subscription Plan</span>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                            <span id="suggestedPlanName">-</span>
                            <span id="suggestedPlanPrice" class="text-indigo-600 font-extrabold">-</span>
                        </div>
                        <input type="hidden" name="plan_id" id="suggestedPlanId">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeRegisterModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30">Pay & Complete Registration</button>
            </div>
        </form>
    </div>
</div>

<!-- Simulated Sandbox Payment Modal Overlay -->
<div id="paymentLoader" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex flex-col items-center justify-center p-4 text-center text-white">
    <div class="w-12 h-12 border-4 border-white/20 border-t-indigo-400 rounded-full animate-spin mb-4"></div>
    <h3 id="loaderTitle" class="text-xl font-extrabold">Processing Order Request</h3>
    <p id="loaderMessage" class="text-xs text-slate-400 font-medium mt-1">Talking to payment gateway. Please do not close this window...</p>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const registerModal = document.getElementById('registerModal');
    const roomCountInput = document.getElementById('roomCountInput');
    const suggestedPlanBox = document.getElementById('suggestedPlanBox');
    const suggestedPlanName = document.getElementById('suggestedPlanName');
    const suggestedPlanPrice = document.getElementById('suggestedPlanPrice');
    const suggestedPlanId = document.getElementById('suggestedPlanId');
    const paymentLoader = document.getElementById('paymentLoader');

    function openRegisterModal() {
        registerModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openRegisterModalWithPlan(planId, rooms) {
        openRegisterModal();
        roomCountInput.value = rooms;
        fetchSuggestedPlan(rooms);
    }

    function closeRegisterModal() {
        registerModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('registerForm').reset();
        suggestedPlanBox.classList.add('hidden');
    }

    let debounceTimer;
    roomCountInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const rooms = parseInt(this.value);
        if (rooms > 0) {
            debounceTimer = setTimeout(() => fetchSuggestedPlan(rooms), 300);
        } else {
            suggestedPlanBox.classList.add('hidden');
        }
    });

    function fetchSuggestedPlan(rooms) {
        fetch("{{ route('register.suggest-plan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ room_count: rooms })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.plan) {
                suggestedPlanName.textContent = data.plan.name + ` (Max ${data.plan.room_count} rooms)`;
                suggestedPlanPrice.textContent = '₹' + parseFloat(data.plan.price).toLocaleString('en-IN') + '/mo';
                suggestedPlanId.value = data.plan.id;
                suggestedPlanBox.classList.remove('hidden');
            }
        })
        .catch(err => console.error('Plan Suggestion Error:', err));
    }
</script>
@endsection
