<header class="ui container-fluid">
    <div class="ui three column grid">
        <div class="column text-left" style="vertical-align: middle">
            <a href="{{url('/')}}" class="logo">
                <img src="{{asset('assets/images/logo-no-rock.png')}}">
            </a>
        </div>
        <div class="column text-center res-d-none">
            <div class="ui header mt-3 tada">Your Story Has Value - Capture It and Be Rewarded</div>
        </div>
        <div class="d-sm-block d-none column text-right" style="vertical-align: middle">
            @if(\Illuminate\Support\Facades\Auth::check())
                @php $user = auth()->user() @endphp
                <a href="{{url('/about')}}" class="ui button btn-primary-outline mr-2">About</a>
                <div class="ui top left pointing dropdown mt-4">
                    <div class="text">
                        <img class="ui avatar image" src="{{$user->profile_pic}}">
                    </div>
                    <div class="menu">
                        @if(!$user->sandbox_user)
                        <a href="{{url('/profile')}}" class="item">Profile</a>
                        <a href="{{url('/wallet')}}" class="item">My Rewards</a>
                        @if($user->active_business)
                            <a href="{{url($user->active_business->subdomain .'/profile')}}" class="item">{{ucwords($user->active_business->name)}} Account</a>
                            <a href="{{url($user->active_business->subdomain .'/posts')}}" class="item">UGC Requests and Exchanges</a>
                        @else
                            <a href="{{url('/business/join')}}" class="item">Create Business Account</a>
                        @endif
                        @if(auth()->id() == 1)
                            <a href="{{url('/admin')}}" class="item">Admin Panel</a>
                        @endif
                        @if($user->active_business)
                            @php $otherBusineses = collect($user->businesses)->where('id', '!=', $user->active_business->id) @endphp
                            @if(is_countable($otherBusineses) && count($otherBusineses) > 0)
                                <div class="ui left pointing dropdown link item">
                                    <i class="dropdown icon mr-2"></i>
                                    Switch Businesses
                                    <div class="menu">
                                        @foreach($otherBusineses as $business)
                                            <a href="{{url($business->subdomain. '/switch')}}" class="item">
                                                <img class="ui avatar image" src="{{$business->logo}}">
                                                {{$business->name}}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endif
                        <a href="{{url('logout')}}" class="item">Logout</a>
                    </div>
                </div>
            @else
                <a href="{{url('/about')}}" class="ui btn-primary-outline button" style="width: 100px">About</a>
                <a href="{{url('login')}}" class="ui btn-primary-outline button" style="width: 100px">JOIN</a>                 
            @endif
        </div>
        <div class="d-sm-none d-block column text-right" style="vertical-align: middle">
            @if(\Illuminate\Support\Facades\Auth::check())
                @php $user = auth()->user() @endphp
                <div class="ui dropdown menu_btn">
                    <div class="text">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                    <div class="menu">
                        <a href="{{url('/about')}}" class="item">About</a> 
                        @if(!$user->sandbox_user)
                            <a href="{{url('/profile')}}" class="item">Profile</a>
                            <a href="{{url('/wallet')}}" class="item">My Rewards</a>
                            @if($user->active_business)
                                <a href="{{url($user->active_business->subdomain .'/profile')}}" class="item">{{ucwords($user->active_business->name)}} Account</a>
                                <a href="{{url($user->active_business->subdomain .'/posts')}}" class="item">UGC Requests and Exchanges</a>
                            @else
                                <a href="{{url('/business/join')}}" class="item">Create Business Account</a>
                            @endif
                            @if(auth()->id() == 1)
                                <a href="{{url('/admin')}}" class="item">Admin Panel</a>
                            @endif
                            @if($user->active_business)
                                @php $otherBusineses = collect($user->businesses)->where('id', '!=', $user->active_business->id) @endphp
                                @if(is_countable($otherBusineses) && count($otherBusineses) > 0)
                                    <div class="ui left pointing dropdown link item">
                                        <i class="dropdown icon mr-2"></i>
                                        Switch Businesses
                                        <div class="menu">
                                            @foreach($otherBusineses as $business)
                                                <a href="{{url($business->subdomain. '/switch')}}" class="item">
                                                    <img class="ui avatar image" src="{{$business->logo}}">
                                                    {{$business->name}}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                        <a href="{{url('logout')}}" class="item">Logout</a>
                    </div>
                </div>             
            @else                 
                <div class="ui dropdown menu_btn">
                    <div class="text">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                    <div class="menu">
                        <a href="{{url('/about')}}" class="item">About</a> 
                        <a href="{{url('/login')}}" class="item">JOIN</a> 
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="container-fluid res-d-block tagline">
        <div class="ui header">Your Story Has Value - Capture It and Be Rewarded</div>
    </div>

</header>
