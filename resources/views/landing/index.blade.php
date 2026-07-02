@extends('layouts.landing')

@section('title', 'Welcome to HotelTV - Premium Hotel TV & Management System')

@section('styles')
<style>
    /* Premium Landing CSS */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 8%;
        background-color: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: var(--shadow-sm);
    }
    
    .brand {
        font-size: 24px;
        font-weight: 800;
        color: var(--bg-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .brand i {
        color: var(--primary);
        animation: pulseGlow 2s infinite;
    }
    
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    /* Dropdown Styling */
    .dropdown {
        position: relative;
        display: inline-block;
    }
    
    .dropdown-btn {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 15px;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }
    
    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: var(--bg-card);
        min-width: 180px;
        box-shadow: var(--shadow-lg);
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        z-index: 101;
        overflow: hidden;
        margin-top: 8px;
        animation: fadeIn 0.2s ease-out;
    }
    
    .dropdown-content a, .dropdown-content button {
        color: var(--text-main);
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        font-weight: 500;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .dropdown-content a:hover, .dropdown-content button:hover {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    
    .dropdown:hover .dropdown-content {
        display: block;
    }
    
    /* Hero Section */
    .hero {
        background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 90.1%), var(--bg-card);
        padding: 100px 8% 80px 8%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-content {
        max-width: 800px;
        margin: 0 auto;
        z-index: 2;
        position: relative;
    }
    
    .hero h1 {
        font-size: 54px;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1.5px;
        color: var(--bg-dark);
        margin-bottom: 24px;
    }
    
    .hero h1 span {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .hero p {
        font-size: 20px;
        color: var(--text-muted);
        margin-bottom: 40px;
        font-weight: 400;
    }
    
    /* Stats & Highlights */
    .highlights {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 60px;
        flex-wrap: wrap;
    }
    
    .highlight-item {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 24px;
        border-radius: var(--radius-lg);
        min-width: 200px;
        box-shadow: var(--shadow-sm);
    }
    
    .highlight-item h3 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
    }
    
    .highlight-item p {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 0;
    }
    
    /* Plans Pricing Grid */
    .plans-section {
        padding: 80px 8%;
        text-align: center;
    }
    
    .section-title {
        font-size: 36px;
        font-weight: 800;
        color: var(--bg-dark);
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    
    .section-desc {
        font-size: 16px;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto 50px auto;
    }
    
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .price-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 40px 30px;
        text-align: center;
        position: relative;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .price-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary);
    }
    
    .price-card.popular {
        border: 2px solid var(--primary);
        box-shadow: var(--shadow-lg);
    }
    
    .price-card.popular::before {
        content: "Most Suggested";
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }
    
    .plan-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--bg-dark);
        margin-bottom: 8px;
    }
    
    .plan-rooms {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary);
        background-color: var(--primary-light);
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        margin-bottom: 24px;
    }
    
    .plan-price {
        font-size: 42px;
        font-weight: 800;
        color: var(--bg-dark);
        margin-bottom: 20px;
    }
    
    .plan-price span {
        font-size: 14px;
        color: var(--text-muted);
        font-weight: 400;
    }
    
    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 30px 0;
        text-align: left;
    }
    
    .plan-features li {
        font-size: 14px;
        color: var(--text-main);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .plan-features li i {
        color: var(--success);
    }
    
    /* Autosuggest Block */
    .suggested-box {
        background-color: var(--primary-light);
        border: 1px solid rgba(99, 102, 241, 0.2);
        padding: 16px;
        border-radius: var(--radius-md);
        margin-top: 15px;
        display: none;
        animation: fadeIn 0.4s;
        text-align: left;
    }
    
    .suggested-box-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-hover);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    
    .suggested-plan-info {
        font-size: 15px;
        font-weight: 600;
        color: var(--bg-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Mock Payment Loader Overlay */
    .payment-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(10px);
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        z-index: 2000;
        text-align: center;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255, 255, 255, 0.1);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 24px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<!-- Nav bar -->
<header class="navbar anim-fade-in">
    <div class="brand">
        <i class="fa-solid fa-hotel"></i>
        <span>HotelTV Connect</span>
    </div>
    <div class="nav-actions">
        <!-- Status Flash notifications -->
        @if(session('error'))
            <div class="alert alert-danger" style="margin: 0 15px 0 0; padding: 8px 16px; font-size: 13px;">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" style="margin: 0 15px 0 0; padding: 8px 16px; font-size: 13px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="dropdown">
            <button class="dropdown-btn">
                <span>Access Portal</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div class="dropdown-content">
                <button onclick="openRegisterModal()">
                    <i class="fa-solid fa-user-plus" style="margin-right: 8px; color: var(--primary);"></i>Register Hotel
                </button>
                <a href="{{ route('hotel.login') }}">
                    <i class="fa-solid fa-right-to-bracket" style="margin-right: 8px; color: var(--success);"></i>Login Hotel
                </a>
                <div style="border-top: 1px solid var(--border-color); margin: 4px 0;"></div>
                <a href="{{ route('super-admin.login') }}" style="color: var(--text-muted); font-size: 12px;">
                    <i class="fa-solid fa-lock" style="margin-right: 8px;"></i>Super Admin
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content anim-slide-up">
        <h1>Connect Your Rooms with <span>Premium Smart TV Services</span></h1>
        <p>
            An all-in-one entertainment and dashboard portal for modern hotels. Seamlessly register your rooms, purchase TV licensing, and manage guest engagement with a few clicks.
        </p>
        <div style="display: flex; justify-content: center; gap: 16px;">
            <button onclick="openRegisterModal()" class="btn btn-primary btn-lg">
                Register Your Hotel Now <i class="fa-solid fa-arrow-right"></i>
            </button>
            <a href="#plans" class="btn btn-outline btn-lg">View Plans & Pricing</a>
        </div>
        
        <div class="highlights">
            <div class="highlight-item">
                <h3>100%</h3>
                <p>Cloud Managed</p>
            </div>
            <div class="highlight-item">
                <h3>10k+</h3>
                <p>Connected TVs</p>
            </div>
            <div class="highlight-item">
                <h3>24/7</h3>
                <p>Support Access</p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Plans Section -->
<section id="plans" class="plans-section anim-slide-up">
    <h2 class="section-title">Transparent Pricing For Every Scale</h2>
    <p class="section-desc">
        Select a structured subscription plan that matches your hotel room counts. All plans include 16-digit TV licensing keys valid for the registered rooms limit.
    </p>
    
    <div class="pricing-grid">
        @foreach($plans as $plan)
            <div class="price-card {{ $plan->room_count === 50 ? 'popular' : '' }}">
                <div>
                    <h3 class="plan-name">{{ $plan->name }}</h3>
                    <span class="plan-rooms">Up to {{ $plan->room_count }} Rooms</span>
                    <div class="plan-price">₹{{ number_format($plan->price, 0) }}<span>/month</span></div>
                    <ul class="plan-features">
                        <li><i class="fa-solid fa-check"></i> <span>Supports up to {{ $plan->room_count }} TVs</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>Instant License Key</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>HD Stream Customization</span></li>
                        @if($plan->room_count >= 50)
                            <li><i class="fa-solid fa-check"></i> <span>Priority Dashboard</span></li>
                        @endif
                        @if($plan->room_count >= 100)
                            <li><i class="fa-solid fa-check"></i> <span>Dedicated API & Support</span></li>
                        @endif
                    </ul>
                </div>
                <button onclick="openRegisterModalWithPlan({{ $plan->id }}, {{ $plan->room_count }})" class="btn {{ $plan->room_count === 50 ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;">
                    Select {{ $plan->name }}
                </button>
            </div>
        @endforeach
    </div>
</section>

<!-- Registration Modal -->
<div id="registerModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Hotel Registration</h3>
            <button onclick="closeRegisterModal()" class="modal-close">&times;</button>
        </div>
        <form id="registerForm">
            @csrf
            <div class="modal-body">
                <div id="registerError" class="alert alert-danger" style="display: none;"></div>
                
                <h4 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 6px; color: var(--primary);">Personal Details</h4>
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" required class="form-control" placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" required class="form-control" placeholder="e.g. 9876543210">
                    </div>
                </div>
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" required class="form-control" placeholder="username@example.com">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" required class="form-control" placeholder="Min 6 characters">
                            <i class="fa-regular fa-eye toggle-password"></i>
                        </div>
                    </div>
                </div>

                <h4 style="margin: 20px 0 16px 0; border-bottom: 1px solid var(--border-color); padding-bottom: 6px; color: var(--primary);">Hotel Details</h4>
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Hotel Name</label>
                        <input type="text" name="hotel_name" required class="form-control" placeholder="e.g. Grand Resort">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Location / City</label>
                        <input type="text" name="hotel_location" required class="form-control" placeholder="e.g. Mumbai, India">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Total Room Count</label>
                    <input type="number" name="room_count" id="roomCountInput" min="1" required class="form-control" placeholder="e.g. 50">
                    
                    <!-- Suggested Plan Box -->
                    <div id="suggestedPlanBox" class="suggested-box">
                        <div class="suggested-box-title">Suggested Subscription Plan</div>
                        <div class="suggested-plan-info">
                            <span id="suggestedPlanName">-</span>
                            <span id="suggestedPlanPrice" style="color: var(--primary); font-weight: 700;">-</span>
                        </div>
                        <input type="hidden" name="plan_id" id="suggestedPlanId">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeRegisterModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Pay & Complete Registration</button>
            </div>
        </form>
    </div>
</div>

<!-- Simulated Sandbox Payment Modal Overlay -->
<div id="paymentLoader" class="payment-loader">
    <div class="spinner"></div>
    <h3 id="loaderTitle" style="font-size: 22px; font-weight: 700; margin-bottom: 8px;">Processing Order Request</h3>
    <p id="loaderMessage" style="color: var(--text-light); font-size: 15px;">Talking to payment gateway. Please do not close this window...</p>
</div>
@endsection

@section('scripts')
<!-- Razorpay Checkout library -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const registerModal = document.getElementById('registerModal');
    const roomCountInput = document.getElementById('roomCountInput');
    const suggestedPlanBox = document.getElementById('suggestedPlanBox');
    const suggestedPlanName = document.getElementById('suggestedPlanName');
    const suggestedPlanPrice = document.getElementById('suggestedPlanPrice');
    const suggestedPlanId = document.getElementById('suggestedPlanId');
    const paymentLoader = document.getElementById('paymentLoader');
    const loaderTitle = document.getElementById('loaderTitle');
    const loaderMessage = document.getElementById('loaderMessage');

    function openRegisterModal() {
        registerModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function openRegisterModalWithPlan(planId, rooms) {
        openRegisterModal();
        roomCountInput.value = rooms;
        fetchSuggestedPlan(rooms);
    }

    function closeRegisterModal() {
        registerModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('registerForm').reset();
        suggestedPlanBox.style.display = 'none';
    }

    // Auto-suggest Plan using AJAX on typing room count
    let debounceTimer;
    roomCountInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const rooms = parseInt(this.value);
        if (rooms > 0) {
            debounceTimer = setTimeout(() => fetchSuggestedPlan(rooms), 300);
        } else {
            suggestedPlanBox.style.display = 'none';
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
                suggestedPlanBox.style.display = 'block';
            }
        })
        .catch(err => console.error('Plan Suggestion Error:', err));
    }

    // Handle Form Submit (Payment & Registration)
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const errorDiv = document.getElementById('registerError');
        const paymentLoader = document.getElementById('paymentLoader');
        const loaderTitle = document.getElementById('loaderTitle');
        const loaderMessage = document.getElementById('loaderMessage');

        if (errorDiv) errorDiv.style.display = 'none';

        // 1. Show processing loader
        if (paymentLoader) paymentLoader.style.display = 'flex';
        if (loaderTitle) loaderTitle.textContent = "Initializing Secure Checkout";
        if (loaderMessage) loaderMessage.textContent = "Creating payment order details...";

        // 2. Request Razorpay Order details from backend
        fetch("{{ route('register.create-order') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(orderData => {
            if (!orderData.success) {
                throw new Error(orderData.message || 'Failed to create payment order');
            }

            formData.append('razorpay_order_id', orderData.order_id);

            // 3. Handle Sandbox Mock payment vs Real payment
            if (orderData.is_mock) {
                // Mock flow
                if (loaderTitle) loaderTitle.textContent = "Mock Payment Gateway Sandbox";
                if (loaderMessage) loaderMessage.textContent = "Simulating successful transaction for ₹" + (orderData.amount / 100) + "...";
                
                setTimeout(() => {
                    if (loaderTitle) loaderTitle.textContent = "Verifying Transaction";
                    if (loaderMessage) loaderMessage.textContent = "Completing registration details...";
                    
                    submitRegistration(formData);
                }, 1800);
            } else {
                // Real Razorpay checkout flow
                if (paymentLoader) paymentLoader.style.display = 'none';
                
                const options = {
                    "key": orderData.key_id,
                    "amount": orderData.amount,
                    "currency": "INR",
                    "name": "HotelTV Connect",
                    "description": "Subscription for " + orderData.plan_name,
                    "order_id": orderData.order_id,
                    "handler": function (response) {
                        // Payment success callback from Razorpay
                        if (paymentLoader) paymentLoader.style.display = 'flex';
                        if (loaderTitle) loaderTitle.textContent = "Confirming Payment";
                        if (loaderMessage) loaderMessage.textContent = "Securing TV license key generation...";

                        formData.append('razorpay_payment_id', response.razorpay_payment_id);
                        formData.append('razorpay_signature', response.razorpay_signature);
                        submitRegistration(formData);
                    },
                    "theme": {
                        "color": "#6366f1"
                    },
                    "modal": {
                        "ondismiss": function() {
                            alert("Payment cancelled. Registration could not be completed.");
                        }
                    }
                };
                
                const rzp = new Razorpay(options);
                rzp.open();
            }
        })
        .catch(err => {
            if (paymentLoader) paymentLoader.style.display = 'none';
            if (errorDiv) {
                errorDiv.textContent = err.message || 'An error occurred during order creation.';
                errorDiv.style.display = 'block';
            }
        });
    });

    function submitRegistration(formData) {
        const errorDiv = document.getElementById('registerError');
        const paymentLoader = document.getElementById('paymentLoader');

        fetch("{{ route('register.complete') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(completion => {
            if (paymentLoader) paymentLoader.style.display = 'none';
            if (completion.success && completion.redirect) {
                window.location.href = completion.redirect;
            } else {
                if (errorDiv) {
                    errorDiv.textContent = completion.message || 'Registration failed.';
                    errorDiv.style.display = 'block';
                }
            }
        })
        .catch(err => {
            if (paymentLoader) paymentLoader.style.display = 'none';
            if (errorDiv) {
                errorDiv.textContent = 'Server registration validation error: ' + err.message;
                errorDiv.style.display = 'block';
            }
        });
    }
</script>
@endsection
