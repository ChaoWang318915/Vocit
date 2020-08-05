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
                </div>
                <div class="ui two column centered grid mt-5 form-content left floated">
                    <div class="column">
                        <h3>Please confirm your password before continuing.</h3>
                        <form class="ui form mt-5" method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="field mt-4 @error('password') error @enderror">
                                <input type="password" name="password" placeholder="Password" required>
                                @error('password')
                                <label class="ui header red mt-3">
                                    {{ $message }}
                                </label>
                                @enderror
                            </div>
                            <div class="field mt-4">
                                <a href="">Forgot Password?</a>
                            </div>
                            <div class="field mt-4">
                                <button class="ui fluid button btn-primary" type="submit">SIGN IN</button>
                            </div>
                        </form>
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
