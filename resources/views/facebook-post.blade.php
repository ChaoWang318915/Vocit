@extends('layouts.facebook')
@section('seo')
    @php $desc = "I received a '" . $serviceName . "' from '" . $businessName . "'" @endphp 
    <meta property="og:title" content="{{$businessName}}" />
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{$desc}}" />    
    <meta property="og:image" content="{{$facebookUrl}}" />
    <meta property="og:url" content="{{'https://vocit.io/facebookpost/'.$postId}}" />    
@endsection
@section('content')
    <div id="app">
        <span>{{json_encode($facebookUrl)}}</span>
        <br />
        <span>{{json_encode($desc)}}</span>
    </div>
@endsection