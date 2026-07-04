<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
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
        .otp-badge {
            display: block;
            background: #f1f5f9;
            border: 2px dashed #6366f1;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            font-size: 28px;
            font-family: monospace;
            font-weight: 700;
            color: #4f46e5;
            letter-spacing: 4px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hotel Portal</h1>
            <p>Password Recovery Request</p>
        </div>
        <div class="content">
            <p class="welcome-text">Hello {{ $hotelAdmin->owner_name }},</p>
            <p>We received a request to reset your password for the HotelTV Connect Portal managing <strong>{{ $hotelAdmin->hotel_name }}</strong>.</p>
            <p>Use the following 6-digit One-Time Password (OTP) to complete the verification process. This OTP is valid for 15 minutes.</p>
            
            <div class="otp-badge">{{ $otpCode }}</div>
            
            <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} HotelTV Connect. All rights reserved.
        </div>
    </div>
</body>
</html>
