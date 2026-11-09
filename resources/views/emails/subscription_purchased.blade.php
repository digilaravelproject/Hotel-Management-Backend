<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmed</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(21, 10, 66, 0.08); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #150A42 0%, #059669 100%); padding: 36px 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 8px 0 0; font-size: 13px; color: #d1fae5; font-weight: 500; }
        .content { padding: 32px 30px; }
        .lead-text { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 24px; }
        .card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
        .card-row { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px dashed #e2e8f0; font-size: 13px; }
        .card-row:last-child { border-bottom: none; }
        .card-label { color: #64748b; font-weight: 600; }
        .card-value { color: #0f172a; font-weight: 700; text-align: right; }
        .license-box { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px; margin: 20px 0; text-align: center; }
        .license-key { font-family: monospace; font-size: 18px; font-weight: 800; color: #065f46; letter-spacing: 1.5px; background: #ffffff; padding: 8px 16px; border-radius: 8px; border: 1px solid #6ee7b7; display: inline-block; margin-top: 6px; }
        .btn-container { text-align: center; margin: 30px 0 20px; }
        .btn { background: #150A42; color: #ffffff !important; padding: 14px 32px; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none; display: inline-block; box-shadow: 0 4px 12px rgba(21, 10, 66, 0.25); }
        .footer { background: #f1f5f9; padding: 20px 30px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <!-- Header -->
            <div class="header">
                <h1>Payment & Subscription Confirmed!</h1>
                <p>PaxTV Smart Hospitality Platform</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="lead-text">
                    Hello <strong>{{ $hotelAdmin->owner_name ?? $hotelAdmin->hotel_name }}</strong>,<br><br>
                    Thank you for your business. Your TV subscription for <strong>{{ $hotelAdmin->hotel_name }}</strong> has been successfully <strong>{{ $context['action'] ?? 'activated' }}</strong>. All connected guest room TVs and hotel panel features are active.
                </p>

                <!-- Subscription Details Card -->
                <div class="card">
                    <div class="card-row">
                        <span class="card-label">Plan Tier:</span>
                        <span class="card-value" style="color: #4f46e5;">{{ $plan->name }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Transaction Type:</span>
                        <span class="card-value">{{ ucfirst($context['action'] ?? 'Purchase / Renewal') }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Allowed Smart TVs:</span>
                        <span class="card-value">{{ $hotelAdmin->room_count ?? 1 }} TV Screens</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Amount Paid:</span>
                        <span class="card-value" style="color: #059669; font-size: 15px;">
                            ₹{{ number_format((float) ($context['amount'] ?? $plan->price), 2) }}
                        </span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Validity Expiry Date:</span>
                        <span class="card-value" style="color: #1e1b4b;">
                            {{ $hotelAdmin->expiry_date ? $hotelAdmin->expiry_date->format('d M, Y') : 'Active' }}
                        </span>
                    </div>
                    @if(!empty($context['transaction_id']))
                        <div class="card-row">
                            <span class="card-label">Transaction Reference:</span>
                            <span class="card-value" style="font-family: monospace; font-size: 11px;">{{ $context['transaction_id'] }}</span>
                        </div>
                    @endif
                </div>

                <!-- License Key Box -->
                @if(!empty($hotelAdmin->license_key))
                    <div class="license-box">
                        <div style="font-size: 12px; font-weight: 700; color: #047857; text-transform: uppercase;">
                            Property OTA License Key
                        </div>
                        <div class="license-key">{{ $hotelAdmin->license_key }}</div>
                        <p style="margin: 6px 0 0; font-size: 11px; color: #065f46;">Use this key to pair and authenticate Android Smart TVs across rooms.</p>
                    </div>
                @endif

                <div class="btn-container">
                    <a href="{{ route('hotel.dashboard') }}" class="btn">Open Hotel Dashboard →</a>
                </div>

                <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin-top: 24px;">
                    Tax invoices can be downloaded anytime from the <strong>Billing & Plan &gt; Tax Invoices</strong> section of your hotel portal.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px;">© {{ date('Y') }} PaxTV Hospitality Network. All rights reserved.</p>
                <p style="margin: 0;">Support Desk: <a href="mailto:{{ config('mail.from.address', 'support@paxtvnetwork.com') }}" style="color: #4f46e5;">{{ config('mail.from.address', 'support@paxtvnetwork.com') }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>

