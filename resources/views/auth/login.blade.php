@extends('layouts.static')

@section('content')
    <div class="container-fluid">
        <div class="ui two column grid left-container">
            <div class="ten wide column">
                <div class="ui two column grid page-header">
                    <div class="column">
                        <a href="{{url('/')}}" class="logo">
                            <img src="{{asset('assets/images/logo.png')}}">
                        </a>
                    </div>
                    <div class="column text-right">
                        <a href="{{url('register')}}" class="ui button btn-primary-outline">SIGN UP</a>
                    </div>
                </div>
                <div class="ui two column centered grid mt-5 form-content floated">
                    <div class="column">
                        <h3>Sign In to Your Account</h3>
                        <form class="ui form mt-5" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="field @error('email') error @enderror">
                                <input type="text" name="email" placeholder="Enter your email" value="{{ old('email') }}" required >
                                @error('email')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="field mt-4 @error('password') error @enderror">
                                <input type="password" name="password" placeholder="Password" required>
                                @error('password')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="two fields mt-4">
                                <div class="field res-middle">
                                    <a href="{{url('password/reset')}}">Forgot Password?</a>
                                </div>
                                <div class="field text-right res-middle">
                                    <div class="ui checkbox">
                                        <input type="checkbox" tabindex="0" name="remember" class="hidden" {{ old('remember') ? 'checked' : '' }}>
                                        <label>Remember Me</label>
                                    </div>
                                </div>
                            </div>
                            <div class="two fields mt-4 res-reverse">
                                <div class="ten wide field float-left" style="margin-top: -5px">
                                    <span>Or Login With </span>
                                    <a href="{{url('login/google')}}" class="social-icon"><img src="{{asset('assets/images/google.png')}}"></a>
                                    <a href="{{url('login/facebook')}}" class="social-icon"><img src="{{asset('assets/images/facebook.png')}}"></a>
                                </div>
                                <div class="six wide field text-right float-right">
                                    <button class="ui fluid button btn-primary" type="submit">SIGN IN</button>
                                </div>
                            </div>

                        </form>
                        <!-- <a class="facebook"  href="{{url('login/facebook')}}"><img style="width:9%" src="{{asset('assets/images/facebook.png')}}">&nbsp;&nbsp;Login with Facebook</a> -->
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
            <div class="six wide column right-container right floated">
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

