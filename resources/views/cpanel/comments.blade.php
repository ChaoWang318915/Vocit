@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <admin-comments-component :comments-list="{{collect($comments)}}"></admin-comments-component>
@endsection
