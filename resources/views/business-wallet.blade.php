@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui grid">
            <div class="column post-create">
                <request-component
                    :api-token="{{auth()->check() ? json_encode(auth()->user()->makeVisible('api_token')->api_token) : json_encode(null)}}"
                    :editable-post="{{collect($post)}}"
                    :access-role="{{json_encode(auth()->user()->active_role)}}"
                    :post-limit="{{json_encode($postLimit)}}"
                    :active-business-name="{{json_encode(auth()->user()->active_business ? auth()->user()->active_business->subdomain : '')}}"
                ></request-component>
            </div>
        </div>
        <div class="ui grid">
            <div class="column">
                <business-wallet-component
                    :access-role="{{json_encode(auth()->user()->active_role)}}"
                    :active-business-name="{{json_encode(auth()->user()->active_business ? auth()->user()->active_business->subdomain : '')}}"
                >
                </business-wallet-component>
            </div>
        </div>
    </div>
@endsection
