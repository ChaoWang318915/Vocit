@extends('layouts.app')
@section('seo')
<meta property="og:title" content="Test Title" />
    <meta property="og:description" content="Test Description" />
    <meta property="og:type" content="image/jpeg">
    <meta property="og:image:width" content="800"/>
    <meta property="og:image:height" content="600"/>
    <meta property="og:url" content="https://vocit.io/facebookpost" />
    <meta property="og:image" content="https://s3.us-east-2.amazonaws.com/cdn.vocit/facebook/1610097280/conference-room-768441_1920.jpg" />
@endsection
@section('content')
    <facebook-post-component></facebook-post-component>
@endsection