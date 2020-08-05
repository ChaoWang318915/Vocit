@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <admin-companies-component :companies-list="{{collect($companies)}}"></admin-companies-component>
@endsection
