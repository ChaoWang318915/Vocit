@extends('layouts.app')
@section('seo')
    <meta property="og:title" content="I got free connection" />
    <meta property="og:description" content="You can also get yours" />
    <!-- <meta property="og:type" content="image/jpeg"> -->
    <!-- <meta property="og:image:width" content="500"/>
    <meta property="og:image:height" content="300"/> -->
    <meta property="og:url" content="https://vocit.io/post/251" /> 
    <meta property="og:image" content="https://vocit.io/post/251" />
@endsection
@section('content')
    <facebook-post-component></facebook-post-component>
@endsection