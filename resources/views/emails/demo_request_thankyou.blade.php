<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Pax TV Launcher</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0b1329; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #0b1329; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35); border: 1px solid #1e293b; }
        .header { background: linear-gradient(135deg, #0E2556 0%, #146A82 100%); padding: 36px 30px; text-align: center; color: #ffffff; border-bottom: 3px solid #00fbff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; color: #ffffff; }
        .header p { margin: 8px 0 0; font-size: 13px; color: #00fbff; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; }
        .content { padding: 32px 30px; }
        .lead-text { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 24px; }
        .highlight-box { background: #F0FDFA; border: 1px solid #99F6E4; border-radius: 12px; padding: 18px; margin-bottom: 24px; }
        .summary-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; font-size: 13px; }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { color: #64748b; font-weight: 600; }
        .summary-value { color: #0f172a; font-weight: 700; text-align: right; }
        .btn-container { text-align: center; margin: 30px 0 10px; }
        .btn { background: #00fbff; color: #0E2556 !important; padding: 14px 32px; font-size: 14px; font-weight: 800; border-radius: 10px; text-decoration: none; display: inline-block; box-shadow: 0 4px 18px rgba(0, 251, 255, 0.4); border: 1px solid #00fbff; }
        .footer { background: #071126; padding: 24px 30px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #1e293b; }
        .footer a { color: #00fbff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <!-- Header -->
            <div class="header">
                <h1>Pax TV Launcher</h1>
                <p>Demo &amp; Partnership Inquiry Received</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="lead-text">
                    Hi <strong>{{ $inquiry->name }}</strong>,<br><br>
                    Thank you for your interest in <strong>Pax TV Launcher</strong>. We have successfully received your demo inquiry for <strong>{{ $inquiry->hotel_name ?? 'your property' }}</strong>.
                </p>

                <div class="highlight-box">
                    <div style="font-weight: 800; color: #0F766E; font-size: 14px; margin-bottom: 4px;">
                        🎯 What Happens Next?
                    </div>
                    <div style="font-size: 13px; color: #115E59; line-height: 1.5;">
                        One of our hospitality technology specialists is reviewing your property requirements and will reach out to you within <strong>24 business hours</strong> to schedule a live, personalized walkthrough of the Pax TV platform.
                    </div>
                </div>

                <!-- APK Download Section with 7-Day Signed Link -->
                @if(!empty($downloadUrl))
                <div style="background: linear-gradient(135deg, #0E2556 0%, #15386C 100%); border: 2px solid #00fbff; border-radius: 14px; padding: 22px; margin-bottom: 24px; text-align: center; color: #ffffff;">
                    <div style="font-size: 12px; font-weight: 800; color: #00fbff; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">
                        📲 Instant Android App Access
                    </div>
                    <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 800; color: #ffffff;">
                        Download PAX TV Hospitality APK
                    </h3>
                    <p style="margin: 0 0 16px 0; font-size: 13px; color: #E2E8F0; line-height: 1.5;">
                        You can download and test our official Android TV hospitality application package directly on your smart TV or Android device.
                    </p>
                    <a href="{{ $downloadUrl }}" 
                       style="background: #00fbff; color: #0E2556 !important; padding: 13px 28px; font-size: 14px; font-weight: 800; border-radius: 10px; text-decoration: none; display: inline-block; box-shadow: 0 4px 16px rgba(0, 251, 255, 0.45);">
                        ⬇ Download PAX TV APK
                    </a>
                    <p style="margin: 14px 0 0 0; font-size: 11.5px; color: #94A3B8;">
                        🔒 <em>Note: This secure download link is cryptographically signed and valid for <strong>7 days</strong>.</em>
                    </p>
                </div>
                @endif

                <!-- Inquiry Summary -->
                <div class="summary-card">
                    <div style="font-weight: 800; color: #1e293b; font-size: 13px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                        📋 Your Submitted Details
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Contact Name:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">{{ $inquiry->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Email Address:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">{{ $inquiry->email }}</td>
                        </tr>
                        @if($inquiry->phone)
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Phone:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">{{ $inquiry->phone }}</td>
                        </tr>
                        @endif
                        @if($inquiry->hotel_name)
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Hotel / Property:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">{{ $inquiry->hotel_name }}</td>
                        </tr>
                        @endif
                        @if($inquiry->rooms)
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Number of Rooms:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">{{ $inquiry->rooms }} Rooms</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <div class="btn-container">
                    <a href="https://paxtvlauncher.com" class="btn" target="_blank">
                        Explore Pax TV Features
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0;">&copy; {{ date('Y') }} Pax TV Launcher. All rights reserved.</p>
                <p style="margin: 0;">Next-Gen In-Room Entertainment &amp; Hospitality Management.</p>
            </div>
        </div>
    </div>
</body>
</html>

