<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PaxTV Hospitality</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(21, 10, 66, 0.08); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #150A42 0%, #3B1C8C 100%); padding: 36px 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 8px 0 0; font-size: 13px; color: #e9d5ff; font-weight: 500; }
        .content { padding: 32px 30px; }
        .lead-text { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 24px; }
        .card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
        .card-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; font-size: 13px; }
        .card-row:last-child { border-bottom: none; }
        .card-label { color: #64748b; font-weight: 600; }
        .card-value { color: #0f172a; font-weight: 700; text-align: right; }
        .cred-box { background: #fdf4ff; border: 1px solid #f0abfc; border-radius: 12px; padding: 16px; margin: 20px 0; }
        .cred-item { margin: 6px 0; font-size: 13px; }
        .cred-label { font-weight: 600; color: #701a75; }
        .cred-val { font-family: monospace; font-size: 14px; font-weight: 700; color: #86198f; background: #ffffff; padding: 3px 8px; border-radius: 6px; border: 1px solid #f5d0fe; display: inline-block; }
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
                <h1>PaxTV Hospitality Network</h1>
                <p>Welcome to in-room Smart TV Management Platform</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="lead-text">
                    Hello <strong>{{ $hotelAdmin->owner_name ?? $hotelAdmin->hotel_name }}</strong>,<br><br>
                    Your hotel account for <strong>{{ $hotelAdmin->hotel_name }}</strong> has been successfully created by {{ $creatorType ?? 'Administrator' }}. You can now manage connected in-room smart TVs, guest interactive menus, live flights, and OTT streaming visibility.
                </p>

                <!-- Credentials Card -->
                <div class="cred-box">
                    <div style="font-weight: 800; color: #701a75; font-size: 13px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                        🔐 Your Hotel Admin Login Credentials
                    </div>
                    <div class="cred-item">
                        <span class="cred-label">Login Portal URL:</span><br>
                        <a href="{{ route('hotel.login') }}" style="color: #4f46e5; font-weight: 700; word-break: break-all;">{{ route('hotel.login') }}</a>
                    </div>
                    <div class="cred-item" style="margin-top: 10px;">
                        <span class="cred-label">Registered Email:</span> 
                        <span class="cred-val">{{ $hotelAdmin->email }}</span>
                    </div>
                    <div class="cred-item" style="margin-top: 8px;">
                        <span class="cred-label">Temporary Password:</span> 
                        <span class="cred-val">{{ $plainPassword }}</span>
                    </div>
                </div>

                <!-- Hotel Property Info -->
                <div class="card">
                    <div class="card-row">
                        <span class="card-label">Property Name:</span>
                        <span class="card-value">{{ $hotelAdmin->hotel_name }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">City / Location:</span>
                        <span class="card-value">{{ $hotelAdmin->city ?? $hotelAdmin->hotel_location ?? 'India' }}</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Allowed TV Screens:</span>
                        <span class="card-value">{{ $hotelAdmin->room_count ?? 1 }} Smart TVs</span>
                    </div>
                    <div class="card-row">
                        <span class="card-label">Subscription Status:</span>
                        <span class="card-value" style="color: {{ $hotelAdmin->payment_status === 'paid' ? '#16a34a' : '#d97706' }};">
                            {{ ucfirst($hotelAdmin->payment_status ?? 'Pending Activation') }}
                        </span>
                    </div>
                </div>

                <div class="btn-container">
                    <a href="{{ route('hotel.login') }}" class="btn">Sign In to Hotel Dashboard →</a>
                </div>

                <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin-top: 24px;">
                    <strong>Security Recommendation:</strong> For security reasons, please change your password after your first login via the Profile settings tab in your admin panel.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px;">© {{ date('Y') }} PaxTV Hospitality Network. All rights reserved.</p>
                <p style="margin: 0;">Need help? Contact support at <a href="mailto:{{ config('mail.from.address', 'support@paxtvnetwork.com') }}" style="color: #4f46e5;">{{ config('mail.from.address', 'support@paxtvnetwork.com') }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
