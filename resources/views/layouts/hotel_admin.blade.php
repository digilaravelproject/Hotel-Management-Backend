<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Panel') - HotelTV</title>
    
    <!-- CSS styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Panel layout styles */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background-color: var(--bg-dark);
            color: var(--text-white);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: var(--transition);
        }
        
        .sidebar-brand {
            padding: 24px;
            font-size: 20px;
            font-weight: 800;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-brand i {
            color: var(--primary);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
            flex-grow: 1;
        }
        
        .sidebar-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: var(--text-light);
            font-weight: 500;
            font-size: 15px;
            border-left: 4px solid transparent;
            transition: var(--transition);
        }
        
        .sidebar-item a:hover, .sidebar-item.active a {
            color: var(--text-white);
            background-color: var(--border-dark);
        }
        
        .sidebar-item.active a {
            border-left-color: var(--primary);
            background-color: rgba(99, 102, 241, 0.1);
        }
        
        .sidebar-item a i {
            width: 20px;
            font-size: 16px;
        }
        
        .main-panel {
            flex-grow: 1;
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .topbar {
            background-color: var(--bg-card);
            height: 70px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .topbar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--bg-dark);
        }
        
        .content-wrapper {
            padding: 40px;
            flex-grow: 1;
            background-color: var(--bg-main);
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--bg-dark);
            cursor: pointer;
        }
        
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-panel {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .topbar {
                padding: 0 20px;
            }
            .content-wrapper {
                padding: 24px 16px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-hotel"></i>
                <span>Hotel Admin</span>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item active">
                    <a href="{{ route('hotel.dashboard') }}">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
            <div style="padding: 20px; border-top: 1px solid var(--border-dark);">
                <form action="{{ route('hotel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="width: 100%; color: var(--text-light); border-color: var(--border-dark);">
                        <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Topbar -->
            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2 class="topbar-title">@yield('page_title', 'Hotel Portal')</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="font-size: 14px; color: var(--text-muted); font-weight: 500;">
                        <i class="fa-regular fa-clock" style="margin-right: 6px;"></i>{{ date('d M, Y') }}
                    </span>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="content-wrapper anim-fade-in">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Sidebar Responsive Toggle Script -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password fields visibility
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
