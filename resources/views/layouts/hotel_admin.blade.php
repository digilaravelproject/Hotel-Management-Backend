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

        /* Profile Dropdown Styling */
        .topbar .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .topbar .dropdown-btn {
            background: none;
            border: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }
        
        .topbar .dropdown-btn:hover {
            background-color: var(--bg-main);
        }
        
        .topbar .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--bg-card);
            min-width: 190px;
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            z-index: 101;
            overflow: hidden;
            margin-top: 8px;
            animation: fadeIn 0.2s ease-out;
        }
        
        .topbar .dropdown-content a {
            color: var(--text-main);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .topbar .dropdown-content a:hover {
            background-color: var(--primary-light);
            color: var(--primary);
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
                <i class="fa-solid fa-hotel"></i>
                <span>Hotel Admin</span>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ Request::routeIs('hotel.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('hotel.dashboard') }}">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.package') ? 'active' : '' }}">
                    <a href="{{ route('hotel.package') }}">
                        <i class="fa-solid fa-box-archive"></i>
                        <span>My Package</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.ott-settings') ? 'active' : '' }}">
                    <a href="{{ route('hotel.ott-settings') }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Global OTT Settings</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.amenities.*') ? 'active' : '' }}">
                    <a href="{{ route('hotel.amenities.index') }}">
                        <i class="fa-solid fa-spa"></i>
                        <span>Manage Aminities</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.devices.*') ? 'active' : '' }}">
                    <a href="{{ route('hotel.devices.index') }}">
                        <i class="fa-solid fa-tv"></i>
                        <span>Connected TVs</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.guests.*') ? 'active' : '' }}">
                    <a href="{{ route('hotel.guests.index') }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Guests</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::routeIs('hotel.hotel-info') ? 'active' : '' }}">
                    <a href="{{ route('hotel.hotel-info') }}">
                        <i class="fa-solid fa-hotel"></i>
                        <span>Hotel Profile</span>
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
                <div style="display: flex; align-items: center; gap: 20px;">
                    <span style="font-size: 14px; color: var(--text-muted); font-weight: 500;">
                        <i class="fa-regular fa-clock" style="margin-right: 6px;"></i>{{ date('d M, Y') }}
                    </span>
                    
                    <div class="dropdown">
                        <button class="dropdown-btn" id="profileDropdownBtn">
                            @if(Auth::guard('hotel_admin')->user()->hotel_logo)
                                <img src="{{ asset(Auth::guard('hotel_admin')->user()->hotel_logo) }}" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                            @else
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                    {{ substr(Auth::guard('hotel_admin')->user()->owner_name, 0, 1) }}
                                </div>
                            @endif
                            <span>{{ Auth::guard('hotel_admin')->user()->owner_name }}</span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 11px; color: var(--text-muted);"></i>
                        </button>
                        <div class="dropdown-content" id="profileDropdownContent">
                            <a href="{{ route('hotel.profile') }}">
                                <i class="fa-regular fa-user" style="margin-right: 8px; color: var(--primary);"></i>Update Profile
                            </a>
                            <a href="{{ route('hotel.hotel-info') }}">
                                <i class="fa-solid fa-hotel" style="margin-right: 8px; color: var(--secondary);"></i>Update Hotel Info
                            </a>
                            <div style="border-top: 1px solid var(--border-color); margin: 4px 0;"></div>
                            <form action="{{ route('hotel.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="color: var(--danger); padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; font-weight: 500; background: none; border: none; width: 100%; text-align: left; cursor: pointer; transition: var(--transition);">
                                    <i class="fa-solid fa-right-from-bracket" style="margin-right: 8px;"></i>Sign Out
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

    <!-- Sidebar Responsive Toggle Script -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle profile dropdown
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownContent = document.getElementById('profileDropdownContent');
            if (dropdownBtn && dropdownContent) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = dropdownContent.style.display === 'block';
                    dropdownContent.style.display = isVisible ? 'none' : 'block';
                });
                
                document.addEventListener('click', function() {
                    dropdownContent.style.display = 'none';
                });
            }

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
