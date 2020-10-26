@extends('layouts.app')
@section('styles')
    <style>
        main{
            margin-top: 90px !important;
        }
        .about-banner{
            position: relative;
            float: left;
            width: 100%;
            height: 400px;
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
            background: url("{{asset('assets/images/about-bg-1.jpg')}}");
        }
        .banner-2{
            background: url("{{asset('assets/images/about-bg-2.png')}}");
        }
        .banner-3{
            background: url("{{asset('assets/images/about-bg-3.png')}}");
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
    </style>
@endsection
@section('content')
    <div class="container-fluid p-0">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="5000">
            <div class="carousel-inner">
                <div class="carousel-item banner-2 active about-banner"></div>
                <div class="carousel-item banner-1 about-banner"></div>
                <div class="carousel-item banner-3 about-banner"></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="ui two column grid mt-5">
            <div class="sixteen wide tablet eight wide computer column align-items-center d-flex">
                <div>
                    <h1 class="ui header">Why Vocit?</h1>
                    <p>Be rewarded For Your Pictures</p>
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
                <div class="ui three stackable cards">
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
                    <a style="display:none"  href="https://www.linkedin.com/company/vocitofficial/" class="ui card">
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

        <div class="ui grid mt-5 mb-5">
            <div class="ui fluid card">
                <div class="content text-center">
                    <h1 class="ui header mt-5">Read Our Significant Accomplishments</h1>
                    <a target="_blank" href="{{asset('assets/pdf/Vocit Executive Summary.pdf')}}" class="ui button btn-primary mt-5 mb-5">Read Here</a>
                </div>

            </div>
        </div>
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
@endsection

@section('scripts')
    <script>
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
        });
    </script>




@endsection
