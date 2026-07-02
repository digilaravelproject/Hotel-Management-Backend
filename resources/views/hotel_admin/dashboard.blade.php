@extends('layouts.hotel_admin')

@section('title', 'Hotel Admin Dashboard')
@section('page_title', 'Hotel TV Control')

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 30px 40px;
        border-radius: var(--radius-lg);
        margin-bottom: 40px;
        box-shadow: var(--shadow-md);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .welcome-text h2 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
    }
    
    .welcome-text p {
        opacity: 0.9;
        font-size: 15px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-info {
        flex-grow: 1;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--bg-dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-primary .stat-icon { background-color: var(--primary-light); color: var(--primary); }
    .stat-success .stat-icon { background-color: var(--success-light); color: var(--success); }
    .stat-warning .stat-icon { background-color: var(--warning-light); color: var(--warning); }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    @media (min-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 3fr 2fr;
        }
    }
    
    .key-box {
        background-color: #f8fafc;
        border: 2px dashed var(--primary);
        border-radius: var(--radius-md);
        padding: 24px;
        text-align: center;
        margin-top: 15px;
        position: relative;
    }
    
    .license-key-display {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-hover);
        letter-spacing: 2px;
        font-family: monospace;
        display: block;
        margin-bottom: 12px;
    }
    
    /* Live toast styling */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--bg-dark);
        color: white;
        padding: 14px 24px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Welcome to Your Portal, {{ $hotel->owner_name }}!</h2>
        <p><i class="fa-solid fa-hotel" style="margin-right: 6px;"></i> Managing <strong>{{ $hotel->hotel_name }}</strong> located in {{ $hotel->hotel_location }}</p>
    </div>
    <div style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); padding: 10px 20px; border-radius: var(--radius-md); font-weight: 600;">
        Account Status: Approved
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fa-solid fa-tv"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $hotel->room_count }}</div>
            <div class="stat-label">Licensed TV Limit</div>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fa-solid fa-plug"></i>
        </div>
        <div class="stat-info">
            <!-- Simulated connected TVs count (e.g. ~35% of total count, minimum 1) -->
            <div class="stat-value">{{ max(1, intval($hotel->room_count * 0.36)) }}</div>
            <div class="stat-label">Active TV Streams</div>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fa-solid fa-circle-plus"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ max(0, $hotel->room_count - max(1, intval($hotel->room_count * 0.36))) }}</div>
            <div class="stat-label">Remaining TV Slots</div>
        </div>
    </div>
</div>

<!-- Main Details -->
<div class="dashboard-grid">
    <!-- Left Column: Key and Connection Setup -->
    <div class="card" style="box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 8px;">Your Connected Device License Key</h3>
        <p style="color: var(--text-muted); font-size: 14px;">
            Copy the license key below and enter it on your smart TV's HotelTV Connect application to verify and enable device streaming services.
        </p>
        
        <div class="key-box">
            <span class="license-key-display" id="licenseKey">{{ $hotel->license_key }}</span>
            <button onclick="copyLicenseKey()" class="btn btn-primary btn-sm">
                <i class="fa-regular fa-copy"></i> Copy License Key
            </button>
            <p style="margin-top: 15px; font-size: 12px; color: var(--text-muted); font-weight: 500;">
                * Do not share this key with anyone. It validates exactly {{ $hotel->room_count }} simultaneous devices.
            </p>
        </div>
        
        <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-top: 30px; margin-bottom: 12px;">Quick Setup Guide</h3>
        <ul style="padding-left: 20px; font-size: 14px; color: var(--text-main); line-height: 1.8;">
            <li>Power on your smart TV and connect it to the internet.</li>
            <li>Install and open the <strong>HotelTV Connect</strong> app.</li>
            <li>When prompted, enter your 16-digit license key: <code>{{ $hotel->license_key }}</code>.</li>
            <li>The TV will automatically connect and be registered under your dashboard.</li>
        </ul>
    </div>
    
    <!-- Right Column: Subscription & Plan details -->
    <div class="card" style="box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 16px;">Subscription Information</h3>
            
            <div style="background-color: var(--primary-light); padding: 20px; border-radius: var(--radius-md); border: 1px solid rgba(99, 102, 241, 0.2); margin-bottom: 20px;">
                <div style="font-size: 13px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">Active Plan</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--bg-dark); margin: 4px 0;">{{ $hotel->plan->name ?? 'Custom Plan' }}</div>
                <div style="font-size: 14px; color: var(--text-muted);">₹{{ number_format($hotel->plan->price ?? 999, 0) }} / month</div>
            </div>
            
            <table style="width: 100%; font-size: 14px; line-height: 2.2; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <th style="text-align: left; font-weight: 600; color: var(--text-muted);">Billing Period</th>
                    <td style="text-align: right; font-weight: 500;">Monthly</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <th style="text-align: left; font-weight: 600; color: var(--text-muted);">Payment Gateway</th>
                    <td style="text-align: right; font-weight: 500;">Razorpay</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <th style="text-align: left; font-weight: 600; color: var(--text-muted);">Payment Status</th>
                    <td style="text-align: right; font-weight: 700; color: var(--success);">Paid</td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 30px; font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 16px;">
            Need help with billing or upgrading your TV room limits? Contact our support team at <a href="mailto:support@hotel.com">support@hotel.com</a>.
        </div>
    </div>
</div>

<!-- Dynamic toast container -->
<div id="copyToast" class="toast-notification">
    <i class="fa-regular fa-circle-check" style="color: var(--success); font-size: 20px;"></i>
    <span>License Key copied to clipboard!</span>
</div>

@if($hotel->payment_status !== 'paid')
    <div class="subscription-overlay">
        <div class="subscription-popup">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 class="section-title">Activate Your HotelTV Subscription</h2>
                <p class="section-desc" style="margin-bottom: 0;">
                    Your account is approved! Select a subscription plan matching your hotel's room count to generate your 16-digit TV license key.
                </p>
                <div style="margin-top: 15px;">
                    <span class="badge badge-primary" style="font-size: 14px; padding: 6px 16px; border-radius: var(--radius-sm);">
                        Your Room Count: {{ $hotel->room_count }} Rooms
                    </span>
                </div>
            </div>

            <!-- Error alert -->
            <div id="subscribeError" class="alert alert-danger" style="display: none; max-width: 800px; margin: 0 auto 20px auto;"></div>

            <div class="pricing-grid" style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px;">
                @foreach($plans as $p)
                    <div class="price-card {{ $hotel->room_count <= $p->room_count && ($loop->first || $hotel->room_count > ($plans[$loop->index - 1]->room_count ?? 0)) ? 'popular' : '' }}" id="plan-card-{{ $p->id }}">
                        <div>
                            <h3 class="plan-name">{{ $p->name }}</h3>
                            <span class="plan-rooms">Up to {{ $p->room_count }} Rooms</span>
                            <div class="plan-price">₹{{ number_format($p->price, 0) }}<span>/month</span></div>
                            <ul class="plan-features" style="margin-bottom: 20px; list-style: none; padding: 0; text-align: left;">
                                <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> <span>Supports up to {{ $p->room_count }} TVs</span></li>
                                <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> <span>Instant License Key</span></li>
                                <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> <span>HD Stream Customization</span></li>
                            </ul>
                        </div>
                        <button type="button" onclick="checkoutSubscription({{ $p->id }}, '{{ $p->name }}', {{ $p->price }})" class="btn {{ $hotel->room_count <= $p->room_count && ($loop->first || $hotel->room_count > ($plans[$loop->index - 1]->room_count ?? 0)) ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;">
                            Select & Subscribe
                        </button>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <form action="{{ route('hotel.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Simulated Sandbox Loader Overlay for subscription -->
    <div id="subscribeLoader" class="payment-loader" style="z-index: 100000;">
        <div class="spinner"></div>
        <h3 id="subLoaderTitle" style="font-size: 22px; font-weight: 700; margin-bottom: 8px;">Processing Payment Request</h3>
        <p id="subLoaderMessage" style="color: var(--text-light); font-size: 15px;">Communicating with Razorpay gateway. Please do not close this window...</p>
    </div>
@endif
@endsection

@section('scripts')
<script>
    const toast = document.getElementById('copyToast');

    function copyLicenseKey() {
        const key = document.getElementById('licenseKey').textContent;
        navigator.clipboard.writeText(key).then(() => {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
</script>

@if($hotel->payment_status !== 'paid')
    <!-- Razorpay Checkout library -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const subLoader = document.getElementById('subscribeLoader');
        const subLoaderTitle = document.getElementById('subLoaderTitle');
        const subLoaderMessage = document.getElementById('subLoaderMessage');
        const subError = document.getElementById('subscribeError');

        function checkoutSubscription(planId, planName, planPrice) {
            if (subError) subError.style.display = 'none';
            
            // 1. Show loader
            if (subLoader) subLoader.style.display = 'flex';
            if (subLoaderTitle) subLoaderTitle.textContent = "Initializing Secure Checkout";
            if (subLoaderMessage) subLoaderMessage.textContent = "Creating payment order details...";

            // 2. Request Razorpay Order details from backend
            fetch("{{ route('register.create-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ plan_id: planId })
            })
            .then(res => res.json())
            .then(orderData => {
                if (!orderData.success) {
                    throw new Error(orderData.message || 'Failed to create payment order');
                }

                // 3. Handle Sandbox Mock payment vs Real payment
                if (orderData.is_mock) {
                    // Mock flow
                    if (subLoaderTitle) subLoaderTitle.textContent = "Mock Payment Gateway Sandbox";
                    if (subLoaderMessage) subLoaderMessage.textContent = "Simulating successful transaction for ₹" + (orderData.amount / 100) + "...";
                    
                    setTimeout(() => {
                        if (subLoaderTitle) subLoaderTitle.textContent = "Verifying Transaction";
                        if (subLoaderMessage) subLoaderMessage.textContent = "Completing subscription details...";
                        
                        submitSubscription(planId, orderData.order_id, null, null);
                    }, 1800);
                } else {
                    // Real Razorpay checkout flow
                    if (subLoader) subLoader.style.display = 'none';
                    
                    const options = {
                        "key": orderData.key_id,
                        "amount": orderData.amount,
                        "currency": "INR",
                        "name": "HotelTV Connect",
                        "description": "Subscription for " + planName,
                        "order_id": orderData.order_id,
                        "handler": function (response) {
                            // Payment success callback from Razorpay
                            if (subLoader) subLoader.style.display = 'flex';
                            if (subLoaderTitle) subLoaderTitle.textContent = "Confirming Payment";
                            if (subLoaderMessage) subLoaderMessage.textContent = "Generating 16-digit TV license key...";

                            submitSubscription(planId, orderData.order_id, response.razorpay_payment_id, response.razorpay_signature);
                        },
                        "theme": {
                            "color": "#6366f1"
                        },
                        "modal": {
                            "ondismiss": function() {
                                alert("Payment cancelled. Subscription could not be completed.");
                            }
                        }
                    };
                    
                    const rzp = new Razorpay(options);
                    rzp.open();
                }
            })
            .catch(err => {
                if (subLoader) subLoader.style.display = 'none';
                if (subError) {
                    subError.textContent = err.message || 'An error occurred during order creation.';
                    subError.style.display = 'block';
                }
            });
        }

        function submitSubscription(planId, orderId, paymentId, signature) {
            fetch("{{ route('hotel.subscribe') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    plan_id: planId,
                    razorpay_order_id: orderId,
                    razorpay_payment_id: paymentId,
                    razorpay_signature: signature
                })
            })
            .then(res => res.json())
            .then(completion => {
                if (subLoader) subLoader.style.display = 'none';
                if (completion.success) {
                    window.location.reload();
                } else {
                    if (subError) {
                        subError.textContent = completion.message || 'Subscription failed.';
                        subError.style.display = 'block';
                    }
                }
            })
            .catch(err => {
                if (subLoader) subLoader.style.display = 'none';
                if (subError) {
                    subError.textContent = 'Server subscription validation error: ' + err.message;
                    subError.style.display = 'block';
                }
            });
        }
    </script>
@endif
@endsection
