<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pax TV Launcher - Smart TV Experiences for Hotels')</title>
    <meta name="description" content="An all-in-one Android TV & Google TV hospitality solution designed to elevate guest entertainment and simplify hotel TV management.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('uploads/2026/11/PAX-TV-LAUNCHER-2-2.png') }}">

    <!-- Google Fonts: Alexandria & DM Sans & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite Tailwind Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Core Luxury Hospitality Stylesheet -->
    <style>
        :root {
            --pax-teal: #1A9C9E;
            --pax-teal-hover: #22B5B7;
            --pax-teal-glow: rgba(26, 156, 158, 0.4);
            --pax-navy-dark: #050B18;
            --pax-navy-card: rgba(13, 27, 54, 0.7);
            --pax-navy-surface: #0B1E38;
            --pax-card-border: rgba(255, 255, 255, 0.08);
            --pax-cyan-accent: #38bdf8;
        }

        body {
            font-family: 'DM Sans', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #050B18;
            color: #E2E8F0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Alexandria', 'Plus Jakarta Sans', sans-serif;
        }

        /* Glassmorphism helpers */
        .glass-panel {
            background: rgba(11, 30, 56, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: rgba(14, 37, 86, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card:hover {
            border-color: rgba(26, 156, 158, 0.5);
            box-shadow: 0 10px 30px -10px rgba(26, 156, 158, 0.25);
            transform: translateY(-2px);
        }

        /* Ambient Glow effects */
        .glow-teal {
            box-shadow: 0 0 25px rgba(26, 156, 158, 0.35);
        }

        .text-glow-teal {
            text-shadow: 0 0 20px rgba(26, 156, 158, 0.5);
        }

        /* Modal Overlay & Dialog Animations */
        .paxtv-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(5, 11, 24, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .paxtv-modal-overlay.active {
            opacity: 1;
        }

        .paxtv-modal-dialog {
            background: #0B1E38;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 1.5rem;
            max-width: 980px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 35px rgba(26, 156, 158, 0.2);
            transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .paxtv-modal-overlay.active .paxtv-modal-dialog {
            transform: scale(1) translateY(0);
        }

        .paxtv-modal-close {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 10;
        }

        .paxtv-modal-close:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* Custom scrollbar on desktop only */
        @media (min-width: 768px) {
            ::-webkit-scrollbar {
                width: 7px;
            }
            ::-webkit-scrollbar-track {
                background: #050B18;
            }
            ::-webkit-scrollbar-thumb {
                background: #1A9C9E;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #22B5B7;
            }
        }

        /* On mobile, completely hide custom scrollbars for sleek, native feel */
        @media (max-width: 767px) {
            ::-webkit-scrollbar {
                width: 0px !important;
                height: 0px !important;
                display: none !important;
            }
            * {
                -ms-overflow-style: none !important;
                scrollbar-width: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-[#050B18] text-slate-100 antialiased selection:bg-[#1A9C9E] selection:text-white">

    <!-- Sticky Glassmorphic Header -->
    @include('web.components.header')

    <!-- Main Landing Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Enterprise Footer -->
    @include('web.components.footer')

    <!-- Interactive Popups / Modals -->
    @include('web.components.popup-hotel-tv')
    @include('web.components.popup-integration')
    @include('web.components.popup-support')
    @include('web.components.popup-demo')

    <!-- Scripts -->
    <script src="{{ asset('js/paxtv.js') }}?v={{ filemtime(public_path('js/paxtv.js')) }}"></script>
    @stack('scripts')
</body>
</html>
