@extends('layouts.facebook')
@section('seo')
    <meta property="og:title" content="FB test title" />
    <meta property="og:description" content="FB test description" />
    <meta property="og:type" content="image/jpeg">
    <meta property="og:image:width" content="1250"/>
    <meta property="og:image:height" content="1250"/>
    <meta property="og:url" content="https://vocit.io/facebookpost" />
    <meta property="og:image" content="https://s3.us-east-2.amazonaws.com/cdn.vocit/facebook/1610097280/conference-room-768441_1920.jpg" />
@endsection
@section('content')
    <img src="https://s3.us-east-2.amazonaws.com/cdn.vocit/facebook/1610097280/conference-room-768441_1920.jpg" />
@endsection