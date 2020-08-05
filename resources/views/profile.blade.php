@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui two column centered grid">
            <div class="sixteen wide tablet eight wide computer column">
                <profile-component :current-user="{{collect($user)}}"></profile-component>
            </div>
        </div>
    </div>
@endsection
