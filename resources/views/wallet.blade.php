@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui grid">
            <div class="column">
               <user-wallet-component :received-coupons="{{collect($coupons)}}" ></user-wallet-component>
            </div>
        </div>
    </div>
@endsection
