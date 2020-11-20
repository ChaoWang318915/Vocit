<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

@yield('seo')

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/semantic/semantic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/static-style.less') }}" rel="stylesheet/less" type="text/css">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom/custom.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    @yield('styles')
</head>
<body>
@include('layouts.static.header')
@yield('content')
@include('layouts.static.footer')
<script src="{{ asset('assets/js/less.js') }}" defer></script>
<script src="{{ asset('assets/semantic/semantic.js') }}" defer></script>
<script src="{{ asset('assets/js/scripts.js') }}" defer></script>
@yield('scripts')
</body>
</html>
