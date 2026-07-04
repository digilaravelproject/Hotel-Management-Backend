<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - HotelTV</title>
    <link class="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 440px;
            padding: 48px 40px;
            color: var(--text-main);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header .icon-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 16px auto;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .login-header h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
            color: var(--bg-dark);
        }

        .login-header p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="icon-box">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1>Verification Required</h1>
            <p>We've sent a 6-digit OTP code to <strong>{{ $email }}</strong>. Enter the OTP code and set your new password below.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 24px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('hotel.reset-password') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label">6-Digit OTP Code</label>
                <input type="text" name="otp_code" required class="form-control" placeholder="e.g. 123456" maxlength="6" style="text-align: center; font-size: 20px; font-weight: 700; letter-spacing: 4px;" autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" required class="form-control" placeholder="••••••••">
                    <i class="fa-regular fa-eye toggle-password" style="color: var(--text-muted);"></i>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" required class="form-control" placeholder="••••••••">
                    <i class="fa-regular fa-eye toggle-password" style="color: var(--text-muted);"></i>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px 20px; font-weight: 600;">
                Verify & Reset Password
            </button>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('hotel.forgot-password') }}" style="font-size: 13px; font-weight: 600; color: var(--text-muted);">
                    <i class="fa-solid fa-arrow-left" style="margin-right: 4px;"></i> Request New OTP
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const wrapper = btn.closest('.password-wrapper') || btn.parentElement;
                    const input = wrapper.querySelector('input');
                    if (input.type === 'password') {
                        input.type = 'text';
                        btn.className = 'fa-regular fa-eye-slash toggle-password';
                    } else {
                        input.type = 'password';
                        btn.className = 'fa-regular fa-eye toggle-password';
                    }
                });
            });
        });
    </script>
</body>
</html>
