<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Sign In - HotelTV</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            padding: 48px 40px;
            color: var(--text-white);
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
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }

        .login-header h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 14px;
            color: var(--text-light);
        }

        .form-label {
            color: var(--text-light);
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.1);
            color: var(--text-white);
        }

        .form-control:focus {
            border-color: var(--primary);
            background-color: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="icon-box">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1>Super Admin</h1>
            <p>Access control center details</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 24px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px;">
                <ul style="margin: 0; padding-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super-admin.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="form-control" placeholder="admin@hotel.com" autocomplete="email" autofocus>
            </div>
            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" required class="form-control" placeholder="••••••••" autocomplete="current-password">
                    <i class="fa-regular fa-eye toggle-password" style="color: var(--text-light);"></i>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px 20px;">
                Sign In to Panel
            </button>
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
