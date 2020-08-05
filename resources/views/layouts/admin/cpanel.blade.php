<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,500,600,700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/semantic/semantic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.less') }}" rel="stylesheet/less" type="text/css">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    @yield('styles')
    <style>
        #loader{
            position: fixed;
            top:0;
            left: 0;
            justify-content: center;
            align-items: center;
            display: flex;
            height: 100vh;
            width: 100vw;
            z-index: 9999;
            font-size: 70px;
            background-color: #ffffff;
            color: #0d71bb;
        }
        @media only screen and (max-width: 767px){
            .main-container{
                margin-top: 140px !important;
            }
            .mobile-menu{
                text-align: center;
            }
        }
    </style>
</head>
<body>
<div id="loader">
    <img src="{{asset('assets/images/loader.svg')}}" alt="loader">
</div>
<div id="app" class="{{isset($pageClass) ? $pageClass : ''}}">
@if(auth()->check())
    @php
        $user = auth()->check() ? auth()->user()->makeVisible('api_token') : [];
            $token = $user->api_token ? $user->api_token : '';
            $activeBusinessId = $user->active_business ? $user->active_business->id : '';

    @endphp
@endif
<!--Header-->
    @include('layouts.header')

    <parent-component
        v-bind:api-token="{{isset($token) ? json_encode($token) : 'null'}}"
        :current-organization="{{isset($activeBusinessId) ? json_encode($activeBusinessId) : 'null' }}"
        base-url="{{url('api')}}"></parent-component>

    <div class="min-vh-80 main-container" style="margin-top: 110px">
        <div class="container">
            <div class="ui two column grid">
                <div class="sixteen wide tablet three wide computer column">
                    <div class="ui link list sidebar-menu mobile-menu">
                        <a href="{{url('admin')}}" class="{{Route::currentRouteName() == 'admin.home' ? 'active' : ''}} item font-weight-bold py-2">Home</a>
                        <a href="{{url('admin/users')}}" class="{{Route::currentRouteName() == 'admin.users' ? 'active' : ''}} item font-weight-bold py-2">Users</a>
                        <a href="{{url('admin/posts')}}" class="{{Route::currentRouteName() == 'admin.posts' ? 'active' : ''}} item font-weight-bold py-2">Posts</a>
                        <a href="{{url('admin/comments')}}" class="{{Route::currentRouteName() == 'admin.comments' ? 'active' : ''}} item font-weight-bold py-2">Comments</a>
                        <a href="{{url('admin/businesses')}}" class="{{Route::currentRouteName() == 'admin.companies' ? 'active' : ''}} item font-weight-bold py-2">Businesses</a>
                        <a href="{{url('admin/payments')}}" class="{{Route::currentRouteName() == 'admin.payments' ? 'active' : ''}} item font-weight-bold py-2">Payments</a>
                        <a href="{{url('logout')}}" class="item py-2 font-weight-bold">Logout</a>
                    </div>
                </div>
                <div class="sixteen wide tablet thirteen wide computer column">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('assets/js/less.js') }}" defer></script>
<script src="{{ asset('assets/semantic/semantic.js') }}" defer></script>
<script src="{{ asset('assets/js/scripts.js') }}" defer></script>
<script>
    let apiToken = '';
    let baseWebUrl = "{{url('/')}}"
</script>
<script>
    window.addEventListener("load", function() {
        $('#loader').fadeOut('fast');
    }, false);
</script>
@yield('scripts')
</body>
</html>
