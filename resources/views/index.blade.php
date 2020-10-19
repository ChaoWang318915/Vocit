@extends('layouts.app')
@section('styles')
    <link href="{{ asset('assets/css/masonry.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <a href="{{url('/posts/requests')}}" class="ui container-fluid banner" style="z-index: 10;position: fixed">
        <div>
            <h3 class="ui small header text-white">
                <span style="vertical-align: middle; display: inline-block">Find Requests</span> <i class="circle icon" style="font-size: 5px;display: inline-block; vertical-align: baseline; margin: 0 5px;"></i>
                <span style="vertical-align: middle; display: inline-block">Upload Pictures</span> <i class="circle icon" style="font-size: 5px;display: inline-block; vertical-align: baseline; margin: 0 5px;"></i>
                <span style="vertical-align: middle; display: inline-block">Get Rewards</span>
            </h3>
        </div>
    </a>
    <div class="ui container mt-5" style="padding-top: 15px;">
        <!--Cards-->
        <div class="mt-4">
            <posts-component
                :posts="{{collect($posts)}}"
                :current-user="{{collect(auth()->user())}}"
                :page-params="{{collect($pageParams)}}"
            ></posts-component>
        </div>

{{--        <div class="ui filter-btn btn-primary">--}}
{{--            <i class="filter icon"></i>--}}
{{--        </div>--}}
        <div class="ui icon top right pointing dropdown button filter-btn btn-primary">
            <i class="filter icon"></i>
            <div class="menu">
                <a href="{{url('/')}}" class="item">All</a>
                <a href="{{url('posts/requests')}}" class="item">Requests</a>
                <a href="{{url('posts/exchanges')}}" class="item">Exchanges</a>
            </div>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script type="text/javascript">--}}
{{--        let showPopup = "{{\Request::route()->getName() == 'businessWallet'}}";--}}
{{--        $(function(){--}}
{{--            console.log('show popup', showPopup);--}}
{{--            let washere = getCookie('showWarning');--}}
{{--            if(!washere && showPopup){--}}
{{--                $('.info-modal.modal')--}}
{{--                    .modal('show')--}}
{{--                ;--}}
{{--            }--}}

{{--            $('.info-modal .approve').on('click', function () {--}}
{{--                setCookie('showWarning', true, 1000)--}}
{{--            })--}}
{{--        });--}}

{{--        function setCookie(cname, cvalue, exdays) {--}}
{{--            var d = new Date();--}}
{{--            d.setTime(d.getTime() + (exdays*24*60*60*1000));--}}
{{--            var expires = "expires="+ d.toUTCString();--}}
{{--            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";--}}
{{--        }--}}
{{--        function getCookie(cname) {--}}
{{--            var name = cname + "=";--}}
{{--            var decodedCookie = decodeURIComponent(document.cookie);--}}
{{--            var ca = decodedCookie.split(';');--}}
{{--            for (var i = 0; i < ca.length; i++) {--}}
{{--                var c = ca[i];--}}
{{--                while (c.charAt(0) == ' ') {--}}
{{--                    c = c.substring(1);--}}
{{--                }--}}
{{--                if (c.indexOf(name) == 0) {--}}
{{--                    return c.substring(name.length, c.length);--}}
{{--                }--}}
{{--            }--}}
{{--            return "";--}}
{{--        }--}}
{{--    </script>--}}
{{--    @endsection--}}
