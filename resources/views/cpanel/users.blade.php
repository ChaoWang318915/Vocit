@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <admin-users-component :users-list="{{collect($users)}}"></admin-users-component>
@endsection
