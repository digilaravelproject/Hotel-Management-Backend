<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Dashboard') - HotelTV</title>
    
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
        
        /* Table enhancements */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            background-color: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            margin-top: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        
        .table th, .table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Responsive Burger menu for sidebar */
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

        /* Reusable Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 20px 0;
            gap: 8px;
        }

        .page-item {
            display: inline;
        }

        .page-link, .page-item span {
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-main);
            background-color: var(--bg-card);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .page-link:hover {
            background-color: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .page-item.disabled span {
            color: var(--text-light);
            background-color: #f1f5f9;
            cursor: not-allowed;
            border-color: var(--border-color);
        }

        .page-item.active span {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
    @yield('styles')
</head>
<body>
    
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                <span>Super Admin</span>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ Request::routeIs('super-admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('super-admin.dashboard') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('super-admin.hotels.*') ? 'active' : '' }}">
                    <a href="{{ route('super-admin.hotels.index') }}">
                        <i class="fa-solid fa-hotel"></i>
                        <span>Hotel Admins</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('super-admin.plans.*') ? 'active' : '' }}">
                    <a href="{{ route('super-admin.plans.index') }}">
                        <i class="fa-solid fa-tags"></i>
                        <span>Plan Management</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('super-admin.devices.*') ? 'active' : '' }}">
                    <a href="{{ route('super-admin.devices.index') }}">
                        <i class="fa-solid fa-tv"></i>
                        <span>Connected Devices</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('super-admin.templates.*') ? 'active' : '' }}">
                    <a href="{{ route('super-admin.templates.index') }}">
                        <i class="fa-solid fa-file-code"></i>
                        <span>TV Templates</span>
                    </a>
                </li>
            </ul>
            <div style="padding: 20px; border-top: 1px solid var(--border-dark);">
                <form action="{{ route('super-admin.logout') }}" method="POST">
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
                    <h2 class="topbar-title">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 24px;">
                    <span style="font-size: 14px; color: var(--text-muted); font-weight: 500;">
                        <i class="fa-regular fa-clock" style="margin-right: 6px;"></i>{{ date('d M, Y') }}
                    </span>
                    
                    <!-- Super Admin Profile Dropdown -->
                    <div class="dropdown">
                        <button class="dropdown-btn" style="background: none; color: var(--text-main); border: none; box-shadow: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                            <div style="width: 36px; height: 36px; background-color: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; box-shadow: var(--shadow-sm);">
                                SA
                            </div>
                            <span style="font-weight: 600; font-size: 14px; color: var(--text-main);">Super Admin</span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: var(--text-muted);"></i>
                        </button>
                        <div class="dropdown-content" style="right: 0; min-width: 160px;">
                            <button type="button" onclick="openProfileModal()">
                                <i class="fa-regular fa-user" style="margin-right: 8px; color: var(--primary);"></i>Update Profile
                            </button>
                            <div style="border-top: 1px solid var(--border-color); margin: 4px 0;"></div>
                            <form action="{{ route('super-admin.logout') }}" method="POST" style="margin: 0; display: inline;">
                                @csrf
                                <button type="submit" style="color: var(--danger); width: 100%;">
                                    <i class="fa-solid fa-right-from-bracket" style="margin-right: 8px; color: var(--danger);"></i>Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
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

    <!-- Super Admin Profile Modal -->
    <div id="profileModal" class="modal-overlay">
        <div class="modal-container" style="max-width: 450px;">
            <div class="modal-header">
                <h3>Update Profile</h3>
                <button type="button" onclick="closeProfileModal()" class="modal-close">&times;</button>
            </div>
            <form action="{{ route('super-admin.profile.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" required value="{{ auth()->guard('super_admin')->user()->email ?? 'admin@hotel.com' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password (Leave blank to keep current)</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters">
                            <i class="fa-regular fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                            <i class="fa-regular fa-eye toggle-password"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeProfileModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function openProfileModal() {
            document.getElementById('profileModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
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
