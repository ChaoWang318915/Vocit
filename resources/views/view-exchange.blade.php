@extends('layouts.app')
@section('seo')
    @php $shareurl = (url('exchange') .'/'. $post->id) @endphp
    @php $desc = "I received a " . $serviceName . " from " . $businessName @endphp 
    <meta property="og:title" content="{{$businessName}}" />
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{$desc}}" />    
    <meta property="og:image" content="{{$post->facebook_url}}" />
    <meta property="og:url" content="{{'https://vocit.io/exchange/'.$post->id}}" />  
@endsection
@section('content')
    <div class="ui container post-details" id="stickyContainer">
        <exchange-component
            :active-post="{{collect($post)}}"
            :is-logged-in="{{json_encode(auth()->check())}}"
            :sandbox-user="{{json_encode(auth()->check() ? auth()->user()->sandbox_user : false)}}"
            :exchange-post="true"
            :share-url="{{json_encode($shareurl)}}"
        ></exchange-component>
    </div>
@endsection

