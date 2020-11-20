@extends('layouts.static')

@section('content')
    <div class="container-fluid">
        <div class="ui two column grid left-container" style="min-height: 800px !important;">
            <div class="ten wide column">
                <div class="ui two column grid page-header">
                    <div class="column">
                        <a href="{{url('/')}}" class="logo">
                            <img src="{{asset('assets/images/logo.png')}}">
                        </a>
                    </div>
                    <div class="column text-right">
                        <a href="{{url('login')}}" class="ui button btn-primary-outline">SIGN IN</a>
                    </div>
                </div>
                <div class="ui two column centered grid mt-5 form-content" style=" height: auto !important;">
                    <div class="column">
                        <h2>Create  Your Account</h2>
                        <div class="ui red message">
                            <h3>Warning!</h3>
                            <p>This is the sandbox edition as we are in process of signing up businesses for launch.</p>
                        </div>

                        <!-- <form class="ui form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="two fields">
                                <div class="field @error('first_name') error @enderror">
                                    <input type="text" name="first_name" placeholder="First Name" value="{{ request()->has('first_name') ? request()->get('first_name') : old('first_name') }}" required>
                                    @error('first_name')
                                    <label class="ui header red mt-3">
                                        {{ $message }}
                                    </label>
                                    @enderror
                                </div>
                                <div class="field @error('last_name') error @enderror">
                                    <input type="text" name="last_name" placeholder="Last Name" value="{{ request()->has('last_name') ? request()->get('last_name') : old('last_name') }}" required>
                                    @error('last_name')
                                    <label class="ui header red mt-3">
                                        {{ $message }}
                                    </label>
                                    @enderror
                                </div>
                            </div>
                            <div class="field @error('email') error @enderror">
                                <input type="email" name="email" placeholder="Enter your email" value="{{ request()->get('email') ? request()->get('email') :  old('email') }}" required >
                                @error('email')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="field @error('username') error @enderror">
                                <input type="text" name="username" placeholder="User Name" value="{{ old('username') }}" required >
                                @error('username')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="field mt-4 @error('password') error @enderror">
                                <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
                                @error('password')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="field mt-4">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            </div>
                            <div class="two fields mt-4">
                                <div class="six wide field text-center">
                                    {!! captcha_img('mini') !!}
                                </div>
                                <div class="ten wide field">
                                    <input type="text" name="captcha" placeholder="Enter Captcha Text">
                                    @error('captcha')
                                    <label class="ui header red mt-3">
                                        Invalid Captcha
                                    </label>
                                    @enderror
                                </div>
                            </div>
                            <div class="two fields mt-4">
                                <div class="ten wide field">
                                    <div class="ui checkbox mt-2">
                                        <input type="checkbox" tabindex="0" class="hidden" required>
                                        <label>I agree <a href="{{url('terms')}}">Terms & Conditions</a></label>
                                    </div>
                                </div>
                                <div class="six wide field text-right">
                                    <button class="ui fluid button btn-primary" type="submit">SIGN UP</button>
                                </div>
                            </div>
                            <div class="field mt-4">
                                <span>Or sign up with </span>
                                <a href="{{url('register/google')}}" class="social-icon"><img src="{{asset('assets/images/google.png')}}"></a>
                                <a href="{{url('register/facebook')}}" class="social-icon"><img src="{{asset('assets/images/facebook.png')}}"></a>
                            </div>

                        </form> -->
                        <span><b>Sign Up With</b></span><a href="{{url('register/facebook')}}" class="social-icon"><img src="{{asset('assets/images/facebook.png')}}"></a>
                    </div>
                </div>
                <div class="ui two column grid footer">
                    <ul class="text-center">
                        <li><span>&copy; {{date('Y')}} {{env('APP_NAME')}}</span></li>
                        <li><a href="{{url('terms')}}">Terms & Conditions</a></li>
                        <li><a href="{{url('privacy')}}">Privacy Policy</a></li>
                        <li><a href="{{url('help')}}">Help</a></li>
                    </ul>
                </div>
            </div>
            <div class="six wide column right-container">
                <img src="{{asset('assets/images/auth-banner.svg')}}">
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function(){
            $('.ui.checkbox').checkbox()
        })
    </script>
@endsection
