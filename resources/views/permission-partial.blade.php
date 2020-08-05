@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="column">
            <div class="centered-container">
                <div>
                    <img class="center-image" src="{{asset('assets/images/block.png')}}">
                    <h3 class="ui header d-block">Your account has been suspended</h3>
                    <p>Contact your admin to know more <a href="mailto: admin@vocit.info">admin@vocit.info</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
