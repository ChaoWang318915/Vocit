@extends('layouts.app')
@section('styles')
    <style>
        .about-banner{
            position: relative;
            float: left;
            width: 100%;
            background-position: center !important;
            background-size: cover !important;
            height: auto;
            background-color: #ffffff !important;
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
        .banner-1{
            height: calc(100vh - 150px);
            margin-top: 20px;
            background: url("{{asset('assets/images/about/main-2.png')}}");
        }
        .ugc-banner-1{
            background-position: center !important;
            background-size: cover !important;
            height: 400px;
            background: url("{{asset('assets/images/about/ugc-2a.jpg')}}");
        }
        .ugc-banner-2{
            background-position: center !important;
            background-size: cover !important;
            height: 400px;
            background: url("{{asset('assets/images/about/ugc-1a.jpg')}}");
        }
        .ugc-banner-3{
            background-position: center !important;
            background-size: cover !important;
            height: 400px;
            background: url("{{asset('assets/images/about/ugc-2c.jpg')}}");
        }
        .mobile-banner{
            background-position: center !important;
            background-size: cover !important;
            height: 400px;
            background: url("{{asset('assets/images/about/iphone-vocit.png')}}");
        }
        .card-block{
            width: 200px;
            height: 200px;
            background-color: #FFFFFF;
            margin: 0 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
            position: relative;
            border-radius: 10px;

        }
        .card-block .arrow{
            position: absolute;
            bottom: 75px;
            right: calc(50% - 225px);
            width: 50px;
            height: 50px;
            background: url("{{asset('assets/images/about/refresh.png')}}");
            background-size: cover;
        }
        .column-block:last-child .arrow{
            display: none;
        }
        .banner-2{
            height: auto;
            padding: 20px 0;
            background: url("{{asset('assets/images/banner.svg')}}");
        }
        .banner-5{
            height: auto;
            overflow: hidden;
            justify-content: center;
            align-items: center;
            display: flex;
        }
        .banner-5 img{
            display: inline-block;
            height: 100%;
        }
        .video-info-1{
            background: url("{{asset('assets/images/video-info-1.png')}}");
        }
        .video-info-2{
            background: url("{{asset('assets/images/video-info-2.png')}}");
        }
        .video-info{
            justify-content: center;
            align-items: center;
            display: flex;
        }
        .small-banner-redirect{
            position: fixed;
            top: 115px;
            left:0;
            z-index: 99;
        }
        .main-banner-1{
            background: url("{{asset('assets/images/about-main-v2.jpg')}}") no-repeat;
            background-size: cover;
        }
        .input-box input{
            padding-left: 20px; background: #FFFFFF;
            box-shadow: 0px 3px 48px rgba(255, 87, 34, 0.1);
            border-radius: 20px 0 0 20px !important;
            height: 60px;
            width: 250px;
            outline: 0
        }
        .input-box button{
            background: #FF8A5B; border: 1px solid #FF8A5B;
            border-radius: 0px 20px 20px 0px;
            height: 60px;
            width: 100px;
            font-weight: bold;
            color: white
        }

        @media only screen and (max-width: 1280px){
            .first-banner .column{
               height: 600px !important;
            }
        }
        @media only screen and (max-width: 767px){
            .first-banner .column{
                height: 400px !important;
            }
            .card-block{
                width: 100%;
                margin: 10px;
            }
            .card-block .arrow{
                display: none !important;
            }
            .banner-5 img{
                width: 100%;
                height: auto;
            }

            /*New Added*/
            .small-banner-redirect{
                display: none !important;
            }
            .main-banner-1{
                background-size: cover;
                min-height: 300px;
            }
            .input-box input{
                width: 200px;
            }
            .container-smaller{
                width: 100% !important;
                text-align: center;
            }
            .full-h-col{
                height: auto !important;
            }
            .card-block-container{
                padding: 15px !important;
                text-align: center;
            }
            .card-block{
                width: 200px !important;
                height: 200px !important;
            }
        }
    </style>
@endsection
@section('content')
    <a href="{{url('/posts/requests')}}" class="ui container-fluid banner small-banner-redirect">
        <div>
            <h3 class="ui small header text-white">
                <span style="vertical-align: middle; display: inline-block">Find Requests</span> <i class="circle icon" style="font-size: 5px;display: inline-block; vertical-align: baseline; margin: 0 5px;"></i>
                <span style="vertical-align: middle; display: inline-block">Upload Pictures</span> <i class="circle icon" style="font-size: 5px;display: inline-block; vertical-align: baseline; margin: 0 5px;"></i>
                <span style="vertical-align: middle; display: inline-block">Get Rewards</span>
            </h3>
        </div>
    </a>
    <div class="container-fluid p-0" style="margin-top: 140px; background: #ffffff">
        <div class="about-banner first-banner" style="margin-bottom: 30px">
            <div class="ui stackable two column grid mt-0 mb-0" style="height: auto !important;">
                <div class="column full-h-col" style="height: calc(100vh - 100px); justify-content: center; align-items: center; display: flex">
                    <div class="container-smaller" style="width: 70%">
                        <h1 class="mb-4" style="font-size: 40px">Seize the value from <br> User Generated Content</h1>
                        <div class="input-box w-100">
                            <form class="w-100" method="GET" action="{{url('register')}}">
                                @csrf
                                <input type="email" placeholder="Enter Your Email" name="email">
                                <button type="submit">SIGN UP</button>
                            </form>
                        </div>
                        <p class="mt-4" style="color: #FF8A5C; opacity: 0.87;">Sign Up for Free Now! - No Credit Card required</p>
                    </div>

                </div>
                <div class="column full-h-col main-banner-1" style="height: calc(100vh - 100px)">

                </div>
            </div>
        </div>

        <div class="ui grid" style="background: url('{{asset("assets/images/about/BG.jpg")}}');">
            <div class="column">
                <h1 class="mt-5 mb-5 text-center" style="color: #ffffff">How Vocit Works</h1>
                <div class="ui stackable three column grid pb-5 mb-5 card-block-container" style="padding: 0 100px;">
                    <div class="column column-block" style="justify-content: center; align-items: center; display: flex; text-align: center">
                        <div>
                            <div class="card-block">
                                <div style="width: 100%; height: 100%; justify-content: center; align-items: center; display: flex; text-align: center;">
                                    <div>
                                        <img style="width: 100px; display: inline-block; margin-top: 20px" src="{{asset('assets/images/about/share.png')}}">
                                    </div>
                                </div>
                                <div class="arrow"></div>
                            </div>
                            <div>
                                <h3 class="mt-5" style="color: #ffffff"><span style="font-size: 30px">1.</span> Business ask for Content</h3>
                            </div>
                        </div>

                    </div>
                    <div class="column column-block" style="justify-content: center; align-items: center; display: flex; text-align: center">
                        <div>
                            <div class="card-block" >
                                <div style="width: 100%; height: 100%; justify-content: center; align-items: center; display: flex; text-align: center;">
                                    <div>
                                        <img  style="width: 100px; display: inline-block; margin-top: 20px" src="{{asset('assets/images/about/coupon.png')}}">
                                    </div>

                                </div>
                                <div class="arrow"></div>
                            </div>
                            <div>
                                <h3 class="mt-5" style="color: #ffffff"><span style="font-size: 30px">2.</span> Offers a Reward</h3>
                            </div>
                        </div>

                    </div>
                    <div class="column column-block" style="justify-content: center; align-items: center; display: flex; text-align: center">
                        <div>
                            <div class="card-block">
                                <div style="width: 100%; height: 100%; justify-content: center; align-items: center; display: flex; text-align: center;">
                                    <div>
                                        <img style="width: 100px; display: inline-block; margin-top: 20px" src="{{asset('assets/images/about/arrow-up.png')}}">
                                    </div>

                                </div>
                                <div class="arrow"></div>
                            </div>
                            <div>
                                <h3 class="mt-5" style="color: #ffffff"><span style="font-size: 30px">3.</span> Users create Content</h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="ui grid">
            <div class="column">
                <h1 class="mt-5 mb-5 text-center">Why User Generated Content</h1>
                <div class="ui stackable two column grid pb-5 mb-5">
                    <div class="nine wide column text-center">
                        <h1 class="mt-2 mb-2" style="text-decoration: underline">UGC</h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Builds <strong style="color: #FF5722">Trust</strong></h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Encourages <strong style="color: #FF5722">Engagement</strong></h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Reassures <strong style="color: #FF5722">Decisions</strong></h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Builds Brand-Consumer <strong style="color: #FF5722">Relationships</strong></h1>
                        <div class="mt-5">
                            <img class="thumbnail-1 d-inline-block mt-2 mb-2 mr-2" height="150px" src="{{asset('assets/images/about/thum-1.png')}}">
                            <img class="thumbnail-2 d-inline-block mt-2 mb-2 ml-2" height="150px" src="{{asset('assets/images/about/thumb-2.png')}}">
                        </div>
                    </div>
                    <div class="seven wide column text-center">
                        <h1 class="mt-2 mb-2" style="font-weight: normal">60% of Consumers</h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Believe UGC is the</h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal"><strong style="color: #FF5722">Most Authentic and Most Influencial</strong></h1>
                        <h1 class="mt-2 mb-2" style="font-weight: normal">Form of Marketing</h1>
                        <div class="mt-5">
                            <h1 style="font-weight: normal">84% are <strong style="color: #FF5722">Influenced</strong> By</h1>
                            <h1 style="font-weight: normal">UGC on Company</h1>
                            <h1 style="font-weight: normal">Websites</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui grid pl-3 pr-3" style="background: rgba(255, 87, 34, 0.1)">
            <div class="column">
                <h1 class="mt-5 mb-5 text-center">Vocit In Action</h1>
                <div class="ui stackable four column grid pb-5 mb-5">
                    <div class="column">
                        <h3 class="mt-5 mb-5 text-center" style="color: #FF5722">1. Business Posts UGC Request</h3>
                        <div class="ui fluid card" style="background-color: transparent !important;
    box-shadow: none !important;">
                            <div class="image" style="background-color: transparent !important;">
                                <img src="{{asset('assets/images/about/Group 240.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="mt-5 mb-5 text-center" style="color: #FF9800">2. Business Offers Reward for UGC</h3>
                        <div class="ui fluid card" style="background-color: transparent !important;
    box-shadow: none !important;">
                            <div class="image" style="background-color: transparent !important;">
                                <img src="{{asset('assets/images/about/Group 239.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="mt-5 mb-5 text-center" style="color: #7F47AD">3. User Exchange UGC for Reward</h3>
                        <div class="ui fluid card" style="background-color: transparent !important;
    box-shadow: none !important;">
                            <div class="image" style="background-color: transparent !important;">
                                <img src="{{asset('assets/images/about/Group 245.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="mt-5 mb-5 text-center" style="color: #00C853">4. Business Promotes Using UGC</h3>
                        <div class="ui fluid card" style="background-color: transparent !important;
    box-shadow: none !important;">
                            <div class="image" style="background-color: transparent !important;">
                                <img src="{{asset('assets/images/about/Group 243.png')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui grid pl-3 pr-3">
            <div class="column">
                <h1 class="mt-5 mb-5 text-center">How Vocit Helps Businesses</h1>
                <div class="ui stackable three column grid card-block-container" style="padding: 0 100px;">
                    <div class="column">
                        <div class="ui fluid card">
                            <div class="content text-center" style="height: 300px">
                                <h2 class="mt-5 mb-5" style="color: #FF5722">Request</h2>
                                <div class="ui bulleted list">
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Collect and Own UGC</div>
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Find Premium Online Promotors</div>
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Get User Analytics</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui fluid card">
                            <div class="content text-center" style="height: 300px">
                                <h2 class="mt-5 mb-5" style="color: #FF9800">Reward</h2>
                                <div class="ui bulleted list">
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Offer Coupons, Discounts, & Promotions </div>
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Create UGC Competitions for Creation / Sharing</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui fluid card">
                            <div class="content text-center" style="height: 300px">
                                <h2 class="mt-5 mb-5" style="color: #00C853">Promote</h2>
                                <div class="ui bulleted list">
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Save UGC to 2,000+ online platforms</div>
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Design Marketing Content</div>
                                    <div class="item mt-3 mb-3" style="font-size: 17px; font-weight: 500">Automated UGC Slideshow</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui two column grid mt-5 card-block-container" style="padding: 0 100px">
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

        <div class="ui grid mt-5 p-3">
            <div class="column">
                <div class="ui three stackable cards">
                    <a href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
                        <div class="image">
                            <img src="{{asset('assets/images/Justin.jpg')}}">
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
                    <a style="display:none !important" href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
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

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="transform: translateY(50%);">
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
    </div>

@endsection
@section('scripts')
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
@endsection
