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
    <link href="{{ asset('assets/css/style.less') }}" rel="stylesheet/less" type="text/css">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <style>
        main{
            margin-top: 79px !important;
        }
        .about-banner{
            position: relative;
            float: left;
            width: 100%;
            height: calc(100vh - 79px);
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }
        .about-info{
            padding: 20px;
            background-color: #ffffff;
            min-height: 400px;
            box-shadow: 0px 3px 8px #00000029;
            border-radius: 10px;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }
        .video-info{
            justify-content: center;
            align-items: center;
            display: flex;
        }
        .banner-1{
            background: url("{{asset('assets/images/banne-3.jpg')}}");
        }
        .banner-2{
            background: url("{{asset('assets/images/banne-3-02.jpg')}}");
        }
        .banner-3{
            background: url("{{asset('assets/images/sandbox-banner-3.png')}}");
        }
        .about-info-1{
            background: url("{{asset('assets/images/about-info-1.jpg')}}");
        }
        .about-info-2{
            background: url("{{asset('assets/images/about-info-2.jpg')}}");
        }
        .about-info-3{
            background: url("{{asset('assets/images/about-info-3.jpg')}}");
        }
        .modal-dialog {
            max-width: 800px;
            margin: 30px auto;
        }
        .video-info-1{
            background: url("{{asset('assets/images/video-info-1.png')}}");
        }
        .video-info-2{
            background: url("{{asset('assets/images/video-info-2.png')}}");
        }

        .modal-body {
            position:relative;
            padding:0px;
        }
        .close {
            position:absolute;
            right:-30px;
            top:0;
            z-index:999;
            font-size:2rem;
            font-weight: normal;
            color:#fff;
            opacity:1;
        }
        .signup-banner{
            background-color: #F26421;
        }
        .signup-banner .form{
            height: 400px;
            justify-content: center;
            align-items: center;
            display: flex;
            text-align: center;
            color: #ffffff;
        }
        .floating-btn{
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #F26421;
            border-radius: 50%;
            height: 50px;
            width: 50px;
            border: none;
            outline: 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
            color: #ffffff;
        }
        .send-message-popup{
            position: fixed !important;
            bottom: 70px !important;
        }
        .send-message-popup .form{
            min-width: 200px;
        }
        .banner-get-started-form{
            min-width:300px;
        }
        .send-message-popup .card{
            box-shadow: none !important;
        }
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
        .banner-content{
            position: absolute;
            right: 0;
            width: 40%;
            top: 100px;
            height: calc(90vh - 200px);
            justify-content: center;
            align-items: center;
            display: flex;
            text-align: left;
        }
        .banner-content h1{
            font-size: 35px !important;
            color: #F26421 !important;
        }
        @media only screen and (max-width: 600px) {
            .banner-content{
                position: absolute;
                right: 0;
                width: 100%;
                top: 100px;
                padding: 20px;
                height: calc(90vh - 200px);
                justify-content: center;
                align-items: center;
                display: flex;
                text-align: center;
            }
            .banner-content h1{
                font-size: 35px !important;
                color: #ffffff !important;
            }
        }
    </style>
</head>
<body>
<div id="loader">
    <img src="{{asset('assets/images/loader.svg')}}" alt="loader">
</div>
<header class="ui container-fluid">
    <div class="ui three column grid">
        <div class="column text-left">
            <a href="{{url('/')}}" class="logo">
                <img src="{{asset('assets/images/logo.png')}}">
            </a>
        </div>
        <div class="column text-center res-d-none">
            <div class="ui header mt-3 tada">Your Story Has Value - Capture It and Be Rewarded</div>
        </div>
        <div class="column text-right">
            @if(\Illuminate\Support\Facades\Auth::check())
                @php $user = auth()->user() @endphp
                <div class="ui top left pointing dropdown mt-4">
                    <div class="text">
                        <img class="ui avatar image" src="{{$user->profile_pic}}">
                    </div>
                    <div class="menu">
                        <a href="{{url('logout')}}" class="item">Logout</a>
                    </div>
                </div>
            @else
                <a href="{{url('sandbox/login')}}" class="ui btn-primary-outline button">SIGN IN</a>
            @endif
        </div>
    </div>
    <div class="container-fluid res-d-block tagline">
        <div class="ui header">Your Story Has Value - Capture It and Be Rewarded</div>
    </div>

</header>
<main>
    <div class="container-fluid p-0">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
{{--                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>--}}
            </ol>
            <div class="carousel-inner">
                <a href="{{env('APP_SANDBOX')}}" class="carousel-item banner-1 active about-banner">
                    <div class="banner-content">
                        <div>
                            <h1 class="ui large header text-primary">Test Vocit On The Sandbox Exchange to Start Uploading Picture For Rewards</h1>
{{--                            <p class="text-fade">Signup now to be on our waitlist</p>--}}
                            <button class="ui button primary">Test Now</button>
                        </div>
                    </div>
                </a>
                <a href="#signupnow" class="carousel-item banner-2 about-banner">
                    <div class="banner-content">
                        <div>
                            <h1>Have Thousands Of Pictures On Social Media that Are Doing Nothing For You?</h1>
                            <p class="text-fade">Signup now to be on our waitlist</p>
                            <button class="ui button primary">Sign Up</button>
                        </div>
                    </div>
                </a>
{{--                <div class="carousel-item banner-3 about-banner"></div>--}}
            </div>
        </div>
    </div>
    <div class="container-fluid signup-banner" id="signupnow">
        <div class="container">
            <div class="ui grid">
                <div class="column">
                    <div>
                        <form method="post" action="{{url('sandbox/signup')}}" class="ui get-started-form form banner-get-started-form">
                            @csrf
                            <div>
                                <div class="field">
                                    <h2 class="mb-3">Sign up now to be on the wait
                                        list.</h2>
                                    <p class="mb-3">Be a sandbox user to get exciting rewards</p>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <input type="text" name="first_name" class="first-name" placeholder="First Name" value="{{old('first_name')}}">
                                    </div>
                                    <div class="field">
                                        <input type="text" name="last_name" class="last-name" placeholder="Last Name" value="{{old('last_name')}}">
                                    </div>
                                </div>

                                <div class="field">
                                    <input type="email" name="email" class="email" placeholder="Email" value="{{old('email')}}">
                                </div>
                                <div class="field">
                                    <p class="ui small header email-message"></p>
                                </div>

                                @if($errors->any())
                                    <div class="ui  message">
                                        <p>{{$errors->first()}}</p>
                                    </div>
                                @endif

                                <div class="field">
                                    <button type="button" class="ui green button get-started-btn">Join Wait List</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container">
        <div class="ui two column grid mt-5">
            <div class="sixteen wide tablet eight wide computer column align-items-center d-flex">
                <div>
                    <h1 class="ui header">Why Vocit?</h1>
                    <p>Do you have thousands of pictures on your mobile and social media that could be working for you? Join the wait list and start taking pictures and using your pictures to get discounts and rewards from local Businesses. </p>
                </div>
            </div>
            <div class="sixteen wide tablet eight wide computer column">
                <div class="about-info about-info-1"></div>
            </div>
        </div>
        <div class="ui two column grid mt-5">
            <div class="sixteen wide tablet eight wide computer column">
                <div class="about-info about-info-2"></div>
            </div>
            <div class="sixteen wide tablet eight wide computer column align-items-center align-items-end d-flex">
                <div>
                    <h1 class="ui header">What is Vocit? </h1>
                    <p>A “Picture for Reward” Exchange Platform. Vocit is a new exchange platform that is turning the data world upside down by connecting users and businesses together to exchange data directly with each other. </p>
                </div>
            </div>
        </div>
        <div class="ui two column grid mt-5">
            <div class="sixteen wide tablet eight wide computer column align-items-center d-flex">
                <div>
                    <h1 class="ui header">How does Vocit Work?</h1>
                    <div class="ui bulleted list">
                        <div class="item">Find a Request Post from a business </div>
                        <div class="item">See what picture the business wants and what reward the business is giving.  </div>
                        <div class="item">Upload a picture </div>
                        <div class="item">You receive the award and the business receives your picture.</div>
                    </div>
                </div>
            </div>
            <div class="sixteen wide tablet eight wide computer column">
                <div class="about-info about-info-3"></div>
            </div>
        </div>

        <div class="ui grid mt-5">
            <div class="column">
                <h1 class="ui header">Learn More about Vocit</h1>
            </div>
        </div>

        <div class="ui two column grid mt-5">
            <div class="sixteen wide tablet eight wide computer column">
                <div class="about-info video-info video-info-1">
                    <button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/rtANiUHz1HM" data-target="#myModal">
                        <i class="play icon"></i>
                    </button>
                </div>
            </div>
            <div class="sixteen wide tablet eight wide computer column">
                <div class="about-info video-info video-info-2">
                    <button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/ZH9fO3sqQI0" data-target="#myModal">
                        <i class="play icon"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="ui grid mt-5">
            <div class="column text-center">
                <h1 class="ui large header">Meet Our Team</h1>
                <p class="text-fade">
                    The VOCIT team integrates multidisciplinary skill sets across software, blockchain algorithms, digital assets, sales and business.
                </p>
            </div>
        </div>

        <div class="ui grid mt-5">
            <div class="column">
                <div class="ui four stackable cards">
                    <a href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
                        <div class="image">
                            <img src="{{asset('assets/images/team/justinembry.jpg')}}">
                        </div>
                        <div class="content">
                            <div class="header">Justin Embry</div>
                            <div class="meta">
                                <span class="date">Founder & CEO</span>
                            </div>
                        </div>
                    </a>
                    <a href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
                        <div class="image">
                            <img src="{{asset('assets/images/team/caseyletter.jpg')}}">
                        </div>
                        <div class="content">
                            <div class="header">Casey Stettler</div>
                            <div class="meta">
                                <span class="date">Co-Founder & Attorney</span>
                            </div>
                        </div>
                    </a>
                    <a  v-show="false"  href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
                        <div class="image">
                            <img src="{{asset('assets/images/team/member03.jpg')}}">
                        </div>
                        <div class="content">
                            <div class="header">Lee Guang</div>
                            <div class="meta">
                                <span class="date">Software Advisor</span>
                            </div>
                        </div>
                    </a>
                    <a href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
                        <div class="image">
                            <img src="{{asset('assets/images/team/nataliepeacock.jpg')}}">
                        </div>
                        <div class="content">
                            <div class="header">Natalie Peacock</div>
                            <div class="meta">
                                <span class="date">Marketing Advisor</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{--    <div class="ui grid mt-5 mb-5">--}}
        {{--        <div class="ui fluid card">--}}
        {{--            <div class="content text-center">--}}
        {{--                <h1 class="ui header mt-5">Read Our Significant Accomplishments</h1>--}}
        {{--                <a target="_blank" href="{{asset('assets/pdf/Vocit Executive Summary.pdf')}}" class="ui button btn-primary mt-5 mb-5">Read Here</a>--}}
        {{--            </div>--}}

        {{--        </div>--}}
        {{--    </div>--}}
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="floating-btn open-message"><i class="chat icon"></i></button>
    <div class="ui popup top left transition hidden send-message-popup">
        <div class="ui card">
            <div class="content">
                <div class="ui get-started-form form">
                    <div class="field">
                        <h3>Sign up now to be on the wait
                            list.</h3>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="first_name" class="first-name" placeholder="First Name" value="{{old('first_name')}}">
                        </div>
                        <div class="field">
                            <input type="text" name="last_name" class="last-name" placeholder="Last Name" value="{{old('last_name')}}">
                        </div>
                    </div>

                    <div class="field">
                        <input type="email" name="email" class="email" placeholder="Email" value="{{old('email')}}">
                    </div>
                    <div class="field">
                        <p class="ui small red header email-message"></p>
                    </div>

                    @if($errors->any())
                        <div class="ui red message">
                            <p>{{$errors->first()}}</p>
                        </div>
                    @endif

                    <div class="field">
                        <a class="ui green fluid button get-started-btn">Get Started</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<footer class="ui container-fluid">
    <div class="ui two column grid">
        <div class="column text-left">
            <ul class="text-left">
                <li><span>&copy; {{date('Y')}} {{env('APP_NAME')}}</span></li>
            </ul>

        </div>
        <div class="column">
            <ul>
                <li><a href="/">HOME</a></li>
                <li><a href="{{url('about')}}">ABOUT</a></li>
                <li><a href="{{url('help')}}">HELP</a></li>
                {{--                <li><a href="{{url('privacy')}}">PRIVACY</a></li>--}}
                <li><a href="{{url('terms')}}">TERMS & CONDITION</a></li>
            </ul>
        </div>
    </div>
</footer>


<script src="{{ asset('assets/js/less.js') }}" defer></script>
<script src="{{ asset('assets/semantic/semantic.js') }}" defer></script>
<script src="{{ asset('assets/js/scripts.js') }}" defer></script>
<script>
    let baseUrl = "{{env('APP_SANDBOX')}}";
    window.addEventListener("load", function() {
        $('#loader').fadeOut('fast');
    }, false);
    $(document).ready(function() {

        var $videoSrc;
        $('.video-btn').click(function() {
            $videoSrc = $(this).data( "src" );
        });

        $('#myModal').on('shown.bs.modal', function (e) {

            $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" );
        })

        $('#myModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src',$videoSrc);
        })

        $('.open-message')
            .popup({
                popup : $('.send-message-popup'),
                boundary: '.send-message-popup',
                on    : 'click',
                position   : 'top right',
                inline: true,
                lastResort: 'top right',
            })
        ;

        $(document).on('click', '.get-started-btn', function(){
            let email = $('.get-started-form').find('.email').val();
            let firstName = $('.get-started-form').find('.first-name').val();
            let lastName = $('.get-started-form').find('.last-name').val();
            if(email.length === 0 || firstName.length === 0 || lastName.length === 0){
                $('.email-message').html('All fields are required.').stop(0).slideDown('fast');
                return false;
            }

            $('.get-started-form').submit();
        })
    });
</script>
</body>
</html>
