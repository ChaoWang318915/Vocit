@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <admin-posts-component :posts-list="{{collect($posts)}}" :companies-list="{{collect($companies)}}"></admin-posts-component>
@endsection
