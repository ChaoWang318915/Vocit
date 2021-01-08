@extends('layouts.app')
@section('seo')
    @php $shareurl = $post->is_request ? (url('post').'/'. $post->id) : (url('exchange') .'/'. $post->id) @endphp
    @php $shareurl = $post->facebook_url ? $post->facebook_url : $shareurl @endphp
    @php $baseURL = (url('exchange') .'/') @endphp 
    <meta property="og:title" content="{!! $post->is_request ? $post->short_description : $post->parent_short_description !!} from {{$post->business->name}}" />
    <meta property="og:description" content="{{$post->content ? $post->content:'.'}}" />
    <meta property="og:type" content="image/jpeg">
    <meta property="og:image:width" content="800"/>
    <meta property="og:image:height" content="600"/>
    <meta property="og:url" content="https://vocit.io/facebookpost" />
    <meta property="og:image" content="https://s3.us-east-2.amazonaws.com/cdn.vocit/facebook/1610097280/conference-room-768441_1920.jpg" />
    <!-- <meta property="og:url" content="{{$shareurl}}" /> -->
    <!-- <meta property="og:image" content="{{$post->facebook_url ? $post->facebook_url : $post->lg_url}}" /> -->
@endsection
@section('content')
    <div class="ui container post-details" id="stickyContainer">
        <exchange-component
            :active-post="{{collect($post)}}"
            :is-logged-in="{{json_encode(auth()->check())}}"
            :sandbox-user="{{json_encode(auth()->check() ? auth()->user()->sandbox_user : false)}}"
            :exchange-post="false"
            :share-url="{{json_encode($shareurl)}}"
            :base-url="{{json_encode($baseURL)}}"
            :can-share="{{json_encode($canShare)}}"
        ></exchange-component>
    </div>
@endsection
