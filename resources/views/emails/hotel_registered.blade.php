<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: 'Outfit', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            color: #334155;
        }
        .welcome-text {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
        }
        .card-title {
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .detail-label {
            display: table-cell;
            width: 35%;
            font-weight: 600;
            color: #64748b;
        }
        .detail-value {
            display: table-cell;
            color: #0f172a;
        }
        .key-badge {
            display: block;
            background: #f1f5f9;
            border: 2px dashed #6366f1;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            font-size: 22px;
            font-family: monospace;
            font-weight: 700;
            color: #4f46e5;
            letter-spacing: 2px;
            margin: 20px 0;
        }
        .tv-badge {
            display: inline-block;
            background-color: #e0e7ff;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .btn {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 30px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 4px 0;
        }
        .warning-text {
            color: #b45309;
            background-color: #fef3c7;
            border-left: 4px solid #d97706;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.5;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, {{ $hotelAdmin->owner_name }}!</h1>
            <p>Your hotel registration is successful</p>
        </div>
        <div class="content">
            <p class="welcome-text">
                Thank you for choosing our Hotel Management System. Below are your account details and application license key.
            </p>

            <div class="card">
                <h3 class="card-title">Login Credentials</h3>
                <div class="detail-row">
                    <span class="detail-label">Username (Email):</span>
                    <span class="detail-value"><strong>{{ $hotelAdmin->email }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Password:</span>
                    <span class="detail-value"><code>{{ $plainPassword }}</code></span>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Hotel & Plan Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Hotel Name:</span>
                    <span class="detail-value">{{ $hotelAdmin->hotel_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $hotelAdmin->hotel_location }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Rooms Registered:</span>
                    <span class="detail-value">{{ $hotelAdmin->room_count }} Rooms <span class="tv-badge">Applicable for {{ $hotelAdmin->room_count }} TVs</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Subscription Plan:</span>
                    <span class="detail-value">{{ $hotelAdmin->plan->name ?? 'Custom' }} (Paid)</span>
                </div>
            </div>

            <div class="card" style="border: 1px solid #c7d2fe; background-color: #eef2ff;">
                <h3 class="card-title" style="color: #4f46e5; border-bottom: 2px solid #c7d2fe;">Your License Key</h3>
                <p style="margin: 0; font-size: 14px; color: #4338ca;">Use this license key to connect your hotel smart TVs:</p>
                <div class="key-badge">
                    {{ $hotelAdmin->license_key }}
                </div>
                <p style="margin: 0; font-size: 12px; color: #6366f1; text-align: center; font-weight: 600;">
                    * Valid for exactly {{ $hotelAdmin->room_count }} connected TV screens.
                </p>
            </div>

            <div class="warning-text">
                <strong>Please Note:</strong> Your account is currently pending review and approval by the Super Admin. You will be able to log in to the Hotel Dashboard once the review is completed.
            </div>

            <a href="{{ route('landing') }}" class="btn">Go to Portal</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Hotel Management System. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
