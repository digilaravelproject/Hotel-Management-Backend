@extends('layouts.landing')

@section('title', 'Registration Successful - HotelTV Connect')

@section('styles')
<style>
    .success-container {
        max-width: 750px;
        margin: 60px auto;
        padding: 0 20px;
    }
    
    .success-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        padding: 50px 40px;
        text-align: center;
        margin-bottom: 40px;
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
        background-color: var(--success-light);
        color: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 24px auto;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
    }
    
    .license-box {
        background: #f8fafc;
        border: 2px dashed var(--primary);
        padding: 20px;
        border-radius: var(--radius-md);
        font-size: 26px;
        font-family: monospace;
        font-weight: 700;
        color: var(--primary-hover);
        letter-spacing: 3px;
        margin: 24px 0;
        display: inline-block;
        width: 100%;
        max-width: 480px;
    }
    
    .details-table {
        width: 100%;
        margin: 30px auto;
        border-collapse: collapse;
        text-align: left;
        max-width: 550px;
    }
    
    .details-table th, .details-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .details-table th {
        font-weight: 600;
        color: var(--text-muted);
        width: 40%;
    }
    
    .details-table td {
        color: var(--bg-dark);
        font-weight: 500;
    }
    
    /* Simulated Email Preview Box */
    .email-preview {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: var(--radius-md);
        margin: 30px auto;
        text-align: left;
        max-width: 600px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .email-preview-header {
        background-color: #f1f5f9;
        padding: 12px 20px;
        border-bottom: 1px solid #cbd5e1;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .email-preview-header-row {
        margin-bottom: 4px;
    }
    
    .email-preview-header-row strong {
        color: var(--bg-dark);
    }
    
    .email-preview-body {
        padding: 24px;
        font-size: 14px;
        line-height: 1.6;
        color: #334155;
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <div class="success-card">
        <div class="icon-circle">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h1 class="section-title" style="margin-bottom: 8px;">Registration Completed!</h1>
        <p style="color: var(--text-muted); font-size: 17px; max-width: 550px; margin: 0 auto;">
            Your payment was successful and subscription plan is activated. Below is your unique license key to connect your smart TV screens.
        </p>
        
        <!-- License key -->
        <div>
            <div class="license-box">{{ $licenseKey }}</div>
            <p style="font-size: 12px; font-weight: 700; color: var(--secondary); text-transform: uppercase;">
                <i class="fa-solid fa-tv" style="margin-right: 6px;"></i> Applicable for exactly {{ $hotel->room_count }} TVs / Rooms limit
            </p>
        </div>

        <!-- Details Grid -->
        <table class="details-table">
            <tr>
                <th>Hotel Owner</th>
                <td>{{ $hotel->owner_name }}</td>
            </tr>
            <tr>
                <th>Hotel Registered</th>
                <td>{{ $hotel->hotel_name }} ({{ $hotel->hotel_location }})</td>
            </tr>
            <tr>
                <th>Selected Plan</th>
                <td>{{ $hotel->plan->name ?? 'Economy Plan' }} (₹{{ number_format($hotel->plan->price ?? 999, 0) }}/mo)</td>
            </tr>
            <tr>
                <th>Registration Username</th>
                <td><code>{{ $hotel->email }}</code></td>
            </tr>
            <tr>
                <th>Registration Password</th>
                <td><code>{{ $plainPassword }}</code></td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td><span style="font-family: monospace; font-size: 13px; color: var(--text-muted);">{{ $hotel->razorpay_payment_id }}</span></td>
            </tr>
        </table>

        <!-- Warning alert for pending approval -->
        <div class="alert alert-warning" style="max-width: 550px; margin: 20px auto; border-left-width: 4px; text-align: left;">
            <i class="fa-solid fa-circle-info" style="margin-right: 12px; font-size: 18px; color: var(--warning-dark);"></i>
            <div>
                <strong>Awaiting Super Admin Approval:</strong> Your registration is paid, but the account is currently pending. Once the Super Admin reviews and approves it, you can log in to your portal.
            </div>
        </div>

        <a href="{{ route('landing') }}" class="btn btn-outline" style="margin-top: 10px;">
            <i class="fa-solid fa-house"></i> Return to Homepage
        </a>
    </div>

    <!-- Email Log Preview Section -->
    <div class="anim-slide-up" style="margin-bottom: 60px;">
        <h3 style="text-align: center; font-size: 18px; font-weight: 700; color: var(--bg-dark); margin-bottom: 12px;">
            <i class="fa-regular fa-envelope" style="margin-right: 8px; color: var(--primary);"></i> Sent Email Log Preview
        </h3>
        <p style="text-align: center; font-size: 13px; color: var(--text-muted); margin-bottom: 24px; max-width: 550px; margin-left: auto; margin-right: auto;">
            The following welcome email has been compiled and logged locally. You can copy this mock preview for testing:
        </p>
        
        <div class="email-preview">
            <div class="email-preview-header">
                <div class="email-preview-header-row"><strong>From:</strong> HotelTV Connect &lt;hello@hotel.com&gt;</div>
                <div class="email-preview-header-row"><strong>To:</strong> {{ $hotel->owner_name }} &lt;{{ $hotel->email }}&gt;</div>
                <div class="email-preview-header-row"><strong>Subject:</strong> Hotel Registration Successful - {{ $hotel->hotel_name }}</div>
            </div>
            <div class="email-preview-body">
                <p>Welcome, {{ $hotel->owner_name }}!</p>
                <p>Thank you for choosing our Hotel Management System. Below are your account details and application license key.</p>
                
                <div style="background-color: #f1f5f9; padding: 12px 16px; border-radius: 6px; margin: 16px 0;">
                    <strong>Login Credentials:</strong><br>
                    Username: {{ $hotel->email }}<br>
                    Password: {{ $plainPassword }}
                </div>
                
                <div style="background-color: #eef2ff; border: 1px dashed #6366f1; padding: 12px 16px; border-radius: 6px; margin: 16px 0; text-align: center;">
                    <strong>Your License Key:</strong><br>
                    <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #4f46e5; letter-spacing: 2px;">{{ $licenseKey }}</span><br>
                    <small style="color: #6366f1;">* Valid for exactly {{ $hotel->room_count }} connected TV screens.</small>
                </div>

                <p>Your account is currently pending review and approval by the Super Admin. You will be able to log in to the Hotel Dashboard once approved.</p>
                <p>Regards,<br>HotelTV Connect Team</p>
            </div>
        </div>
    </div>
</div>
@endsection
