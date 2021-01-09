@extends('layouts.facebook')
@section('seo')
    <meta property="og:type" content="image/jpeg">
    <meta property="og:image:width" content="1250"/>
    <meta property="og:image:height" content="983"/>
    <meta property="og:url" content="https://vocit.io/facebookpost" />
@endsection
@section('content')
    <facebook-post-component></facebook-post-component>
@endsection