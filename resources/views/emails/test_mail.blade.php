<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaxTV SMTP Test Connection</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
        }
        .container {
            max-width: 580px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.12);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #150A42 0%, #2563eb 100%);
            padding: 40px 36px;
            text-align: center;
            color: #ffffff;
        }
        .brand-pill {
            display: inline-block;
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #a7f3d0;
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
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
        }
        .content {
            padding: 36px 36px;
        }
        .success-box {
            background: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            border-radius: 16px;
            padding: 18px 22px;
            color: #065f46;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .meta-table td {
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .meta-table td.label {
            color: #64748b;
            font-weight: 600;
            width: 45%;
        }
        .meta-table td.val {
            color: #0f172a;
            font-weight: 800;
            font-family: monospace;
            text-align: right;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 36px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="brand-pill">✓ SMTP Verified</span>
            <h1>PaxTV SMTP Connection Test</h1>
        </div>

        <div class="content">
            <div class="success-box">
                <strong>🎉 Mail Delivery Successful!</strong><br>
                Your Super Admin SMTP credentials and mail driver are operating properly. PaxTV Hospitality system can now dispatch automated event notifications.
            </div>

            <table class="meta-table">
                <tr>
                    <td class="label">Mailer Driver</td>
                    <td class="val">{{ $details['mailer'] ?? 'smtp' }}</td>
                </tr>
                <tr>
                    <td class="label">SMTP Host</td>
                    <td class="val">{{ $details['host'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Port</td>
                    <td class="val">{{ $details['port'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Encryption</td>
                    <td class="val">{{ strtoupper($details['encryption'] ?? 'None') }}</td>
                </tr>
                <tr>
                    <td class="label">From Address</td>
                    <td class="val">{{ $details['from_address'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">From Name</td>
                    <td class="val" style="font-family: inherit;">{{ $details['from_name'] ?? 'PaxTV' }}</td>
                </tr>
                <tr>
                    <td class="label">Dispatch Time</td>
                    <td class="val">{{ now()->format('Y-m-d H:i:s T') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            PaxTV Hospitality Super Admin Platform • Automated System Diagnostics
        </div>
    </div>
</body>
</html>
