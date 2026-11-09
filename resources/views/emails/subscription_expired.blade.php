<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgent: Your Hotel Plan Has Expired — PaxTV Hospitality</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 620px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.12);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #150A42 0%, #3b0764 100%);
            padding: 40px 36px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .brand-pill {
            display: inline-block;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 14px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -0.5px;
            line-height: 1.25;
            color: #ffffff;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #e2e8f0;
        }
        .content {
            padding: 36px 36px;
        }
        .alert-card {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 26px;
        }
        .alert-title {
            color: #991b1b;
            font-size: 15px;
            font-weight: 900;
            margin: 0 0 6px;
        }
        .alert-desc {
            color: #7f1d1d;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 22px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 13px;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-label {
            color: #64748b;
            font-weight: 600;
        }
        .info-val {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
        }
        .cta-btn {
            display: block;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #ffffff !important;
            text-align: center;
            font-weight: 800;
            font-size: 15px;
            padding: 16px 28px;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.4);
            margin: 28px 0 16px;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 36px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="brand-pill">Action Required • Service Locked</span>
            <h1>Your Hotel Plan Has Expired</h1>
            <p>In-room TV streaming & smart hospitality features are currently suspended.</p>
        </div>

        <div class="content">
            <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 0;">
                Dear {{ $hotel->name }} Management,
            </p>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                Your PaxTV Hospitality subscription plan expired on <strong>{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'recently' }}</strong>.
                As a result, smart TV guest services, live OTT apps, and custom branding across your property have been automatically paused.
            </p>

            <div class="alert-card">
                <div class="alert-title">⚠️ What this means for your property:</div>
                <div class="alert-desc">
                    • In-room Smart TVs cannot stream premium OTT apps or sync menus.<br>
                    • Guest digital concierge and room ordering features are locked.<br>
                    • Renew today to instantly restore full service across all <strong>{{ $hotel->allowed_device_limit ?? 50 }} TVs</strong> without losing any configurations.
                </div>
            </div>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Hotel Name</span>
                    <span class="info-val">{{ $hotel->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Expired Plan</span>
                    <span class="info-val">{{ $plan ? $plan->name : 'PaxTV Pro Hotel Plan' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Expiry Date</span>
                    <span class="info-val" style="color: #dc2626;">{{ $hotel->expiry_date ? $hotel->expiry_date->format('d M, Y') : 'Expired' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Assigned TVs</span>
                    <span class="info-val">{{ $hotel->allowed_device_limit ?? 50 }} Screens</span>
                </div>
            </div>

            <a href="{{ $renewalUrl }}" class="cta-btn">
                Renew Your Subscription Now &rarr;
            </a>

            <p style="text-align: center; font-size: 12px; color: #64748b; margin-top: 10px;">
                Instant online renewal via UPI, Cards, NetBanking or contact your assigned distributor.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px;">
                Need assistance? Our support team is ready to help 24/7.
            </p>
            <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                PaxTV Hospitality Platform • Next-Generation In-Room Smart TV Experience
            </p>
        </div>
    </div>
</body>
</html>

