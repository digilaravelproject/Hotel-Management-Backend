<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - HotelTV</title>
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
            margin-bottom: 36px;
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
                <i class="fa-solid fa-key"></i>
            </div>
            <h1>Reset Password</h1>
            <p>Enter your registered email address below, and we will email you a 6-digit OTP code to verify your identity.</p>
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

        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom: 24px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('hotel.forgot-password') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="form-control" placeholder="username@example.com" autofocus autocomplete="email">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px 20px; font-weight: 600;">
                Send OTP Verification
            </button>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('hotel.login') }}" style="font-size: 13px; font-weight: 600; color: var(--text-muted);">
                    <i class="fa-solid fa-arrow-left" style="margin-right: 4px;"></i> Return to Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>
