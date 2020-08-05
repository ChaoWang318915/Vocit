@extends('layouts.app')
@section('styles')
    <link href="{{ asset('assets/css/masonry.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="ui container-fluid m-0 p-0 business-banner-wrapper">
        <business-public-component
            :current-business="{{collect($business)}}"
            :current-user="{{collect(auth()->user())}}"
        ></business-public-component>
    </div>
    <div class="ui container position-relative mt-5">
        <!--Cards-->
        <div class="mt-4">
            <posts-component
                :posts="{{collect($posts)}}"
                :current-user="{{collect(auth()->user())}}"
                :page-params="{{collect($pageParams)}}"
            ></posts-component>
        </div>
    </div>
@endsection
