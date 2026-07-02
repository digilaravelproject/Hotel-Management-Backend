<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Management System')</title>
    
    <!-- CSS Style Sheets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- FontAwesome & Outfit Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>
    
    @yield('content')

    <!-- Global Toast Alert Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Toggle password visibility
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
    
    @yield('scripts')
</body>
</html>
