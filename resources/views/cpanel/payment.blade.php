@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <admin-payment-component :payment-list="{{collect($payments)}}"></admin-payment-component>
@endsection
