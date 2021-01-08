@extends('layouts.facebook')
@section('seo')
    <meta property="og:title" content="Facebook Post Title" />
    <meta property="og:type" content="image/jpeg">
    <meta property="og:image:width" content="1250"/>
    <meta property="og:image:height" content="983"/>
    <meta property="og:url" content="https://vocit.io/facebookpost" />
    <meta property="og:image" content="https://s3.us-east-2.amazonaws.com/cdn.vocit/facebook/1610119245/mobile-phone-1917737_1920.jpg" />
@endsection
@section('content')
    <facebook-post-component></facebook-post-component>
@endsection