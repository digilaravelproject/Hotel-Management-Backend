<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expiring Soon</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(21, 10, 66, 0.08); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #150A42 0%, #D97706 100%); padding: 36px 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 8px 0 0; font-size: 13px; color: #fef3c7; font-weight: 500; }
        .content { padding: 32px 30px; }
        .lead-text { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 24px; }
        .alert-banner { background: #fffbeb; border: 2px solid #fde68a; border-radius: 14px; padding: 20px; margin-bottom: 24px; text-align: center; }
        .days-counter { font-size: 28px; font-weight: 900; color: #b45309; line-height: 1; }
        .days-sub { font-size: 12px; font-weight: 700; color: #92400e; text-transform: uppercase; margin-top: 4px; letter-spacing: 0.5px; }
        .card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
        .card-row { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px dashed #e2e8f0; font-size: 13px; }
        .card-row:last-child { border-bottom: none; }
        .card-label { color: #64748b; font-weight: 600; }
        .card-value { color: #0f172a; font-weight: 700; text-align: right; }
        .btn-container { text-align: center; margin: 30px 0 20px; }
        .btn { background: #150A42; color: #ffffff !important; padding: 14px 36px; font-size: 14px; font-weight: 800; border-radius: 12px; text-decoration: none; display: inline-block; box-shadow: 0 4px 12px rgba(21, 10, 66, 0.25); }
        .footer { background: #f1f5f9; padding: 20px 30px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <!-- Header -->
            <div class="header">
                <h1>⚠️ Renewal Reminder</h1>
                <p>PaxTV Smart Hospitality Subscription</p>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Days Banner -->
                <div class="alert-banner">
                    <div class="days-counter">
                        {{ $daysRemaining }} {{ $daysRemaining === 1 ? 'Day' : 'Days' }}
                    </div>
                    <div class="days-sub">Remaining Until Subscription Expiration</div>
                </div>

                <p class="lead-text">
                    Hello <strong>{{ $hotelAdmin->owner_name ?? $hotelAdmin->hotel_name }}</strong>,<br><br>
                    This is a courteous reminder that your Smart TV subscription for <strong>{{ $hotelAdmin->hotel_name }}</strong> is scheduled to expire on <strong>{{ $hotelAdmin->expiry_date ? $hotelAdmin->expiry_date->format('d M, Y') : 'soon' }}</strong>.
                </p>

                <!-- Details Card -->
                <div class="card">
                    <div class="card-row">
                        <span class="card-label">Hotel Property:</span>
                        <span class="card-value">{{ $hotelAdmin->hotel_name }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Current Active Plan:</span>
                        <span class="card-value" style="color: #4f46e5;">{{ $hotelAdmin->plan->name ?? 'Standard Plan' }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Licensed TV Screens:</span>
                        <span class="card-value">{{ $hotelAdmin->room_count ?? 1 }} Smart TVs</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Expiry Date:</span>
                        <span class="card-value" style="color: #dc2626; font-weight: 800;">
                            {{ $hotelAdmin->expiry_date ? $hotelAdmin->expiry_date->format('d M, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="btn-container">
                    <a href="{{ route('hotel.subscription') }}" class="btn">Renew Subscription Now →</a>
                </div>

                <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px; margin-top: 24px;">
                    <p style="margin: 0; font-size: 12px; color: #991b1b; line-height: 1.5;">
                        <strong>Important:</strong> Upon expiration, guest room smart TVs will display a subscription notice and admin controls will be paused until renewal payment is received. Renew today to ensure uninterrupted guest TV experience.
                    </p>
                </div>
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

