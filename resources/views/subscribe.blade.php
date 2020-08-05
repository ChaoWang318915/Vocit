@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui grid">
            <div class="column subscribe-container">
                <div>
                    <div class="business-logo">
                        <img src="{{$business->logo}}">
                    </div>
                    <h2>{{$business->name}}</h2>
                    <p>Do you own this business, go ahead subscribe to have an active public profile where you can shows your requets and exchanges.</p>
                    <a href="{{url($business->subdomain . '/profile')}}" class="ui button btn-primary">Subscribe</a>
                </div>
            </div>
        </div>
    </div>
@endsection

