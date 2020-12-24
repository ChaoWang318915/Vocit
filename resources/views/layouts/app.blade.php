<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('favicon.png')}}" type="image/png" sizes="16x16">

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
    <link href="{{ asset('assets/css/style.less') }}" rel="stylesheet/less" type="text/css">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <link href="{{ asset('assets/custom/custom.css') }}" rel="stylesheet" type="text/css">
    @if(\Request::route()->getName() != 'viewPost')
        <meta
            name="description"
            content="Find Requests • Upload Pictures • Get Rewards"
        />

        <meta property="og:type" content="website" />
        <meta
            property="og:title"
            content="Your Story Has Value - Capture It and Be Rewarded"
        />
        <meta
            property="og:description"
            content="Find Requests • Upload Pictures • Get Rewards"
        />
        @if(\Request::route()->getName() == 'about')
            <meta
                property="og:image"
                content="{{asset('assets/images/abput-thumbnail.jpg')}}"
            />
            <meta property="og:url" content="https://vocit.io/about" />
        @else
            <meta
                property="og:image"
                content="{{asset('assets/images/thumbnail.jpg')}}"
            />
            <meta property="og:url" content="https://vocit.io/" />
        @endif
        <meta
            property="og:site_name"
            content="Vocit"
        />

        <meta
            name="twitter:title"
            content="Your Story Has Value - Capture It and Be Rewarded"
        />
        <meta
            name="twitter:description"
            content="Find Requests • Upload Pictures • Get Rewards"
        />
        @if(\Request::route()->getName() == 'about')
        <meta
            name="twitter:image"
            content="{{asset('assets/images/about-thumbnail.jpg')}}"
        />
        @else
            <meta
                name="twitter:image"
                content="{{asset('assets/images/thumbnail.jpg')}}"
            />
        @endif
        <meta name="twitter:site" content="@vocit" />
        <meta name="”twitter:creator”" content="@vocit" />

        <meta name="category" content="Photos" />
    @endif

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
    </style>
</head>
<body>
<div id="loader">
    <img src="{{asset('assets/images/loader.svg')}}" alt="loader">
</div>
<div id="app">
    @if(auth()->check())
        @php
            $user = auth()->check() ? auth()->user()->makeVisible('api_token') : [];
            $token = $user->api_token ? $user->api_token : '';
            $activeBusinessId = $user->active_business ? $user->active_business->id : '';
        @endphp
    @endif
    @include('layouts.header')
    <parent-component
        v-bind:api-token="{{isset($token) ? json_encode($token) : 'null'}}"
        :current-organization="{{isset($activeBusinessId) ? json_encode($activeBusinessId) : 'null' }}"
        base-url="{{url('api')}}"></parent-component>
    <main>
        @yield('content')

        <div class="ui mini modal info-modal">
            <div class="content">
                <p>You are giving the marketing rights of this picture to the business in exchange for a reward. The picture will also be posted on Vocit Home Page and your Facebook page. Do you agee?</p>
            </div>
            <div class="actions">
                <div class="ui approve green button">Yes</div>
                <div class="ui reject green button">No</div> 
                <a class="ui orange button" target="_blank" href="/terms">Learn more</a>              
            </div>            
        </div>

    </main>
    @include('layouts.footer')
</div>
<script src="{{ asset('assets/js/less.js') }}" defer></script>
<script src="{{ asset('assets/semantic/semantic.js') }}" defer></script>
<script src="{{ asset('assets/js/scripts.js') }}" defer></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '891941987965583',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v9.0',
      status           : true,
      cookie           : true,
    });    
  };
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
<script type="text/javascript">
    window.addEventListener("load", function() {
        $('#loader').fadeOut('fast');
    }, false);


    {{--let showPopup = "{{\Request::route()->getName() == 'businessWallet'}}";--}}
    {{--$(function(){--}}
    {{--    let washere = getCookie('showWarning');--}}
    {{--    if(!washere && showPopup){--}}
    {{--        $('.info-modal.modal')--}}
    {{--            .modal('show')--}}
    {{--        ;--}}
    {{--    }--}}

    {{--    // $('.info-modal .approve').on('click', function () {--}}
    {{--    //     setCookie('showWarning', true, 1000)--}}
    {{--    // })--}}
    {{--});--}}

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
</script>
@yield('scripts')
</body>
</html>
