<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pax TV Launcher - Smart TV Experiences for Hotels')</title>
    <meta name="description" content="An all-in-one Android TV & Google TV hospitality solution designed to elevate guest entertainment and simplify hotel TV management.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Elementor Layout & Component Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/frontend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-8.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-44.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-69.css') }}">

    <!-- Popup Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/post-33.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-242.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-248.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post-274.css') }}">

    <!-- Pax TV Custom & Modal Enhancements -->
    <link rel="stylesheet" href="{{ asset('css/paxtv-custom.css') }}">

    @stack('styles')
</head>
<body class="home page-template-default page wp-embed-responsive elementor-default elementor-kit-4 elementor-page elementor-page-69">

    <!-- Header -->
    @include('web.components.header')

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    @include('web.components.footer')

    <!-- Modals / Popups -->
    @include('web.components.popup-demo')
    @include('web.components.popup-hotel-tv')
    @include('web.components.popup-integration')
    @include('web.components.popup-support')

    <!-- Pax TV Interactive Scripts -->
    <script src="{{ asset('js/paxtv.js') }}"></script>
    @stack('scripts')
</body>
</html>

