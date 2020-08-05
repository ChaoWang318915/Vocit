@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui column grid">
            <div class="sixteen wide tablet sixteen wide computer column">
                @php $user = auth()->user() @endphp
                <business-profile-component
                    :current-business="{{$user ? collect($user->active_business) : ''}}"
                    :active-plan="{{collect($plan)}}"
                    :access-role="{{json_encode(auth()->user()->active_role)}}"
                    :has-subscription="{{json_encode($has_subscription)}}"
                ></business-profile-component>
            </div>
            <div class="sixteen wide tablet sixteen wide computer column">
                <subscription-component
                    :payment-plans="{{collect($plans)}}"
                    :has-subscription="{{json_encode($has_subscription)}}"
                    :post-limit="{{json_encode($post_limit)}}"
                    :access-role="{{json_encode(auth()->user()->active_role)}}"
                    :business="{{collect(auth()->user()->active_business)}}"
                ></subscription-component>
                <integration-component
                    :zapier-integrations="{{collect($integrations)}}"
                    :api-key="{{json_encode($apiKey)}}"
                >
                </integration-component>
                <settings-component
                    :member-list="{{collect($members)}}"
                    :associated-business="{{collect($businesses ?? '')}}"
                    :access-role="{{json_encode(auth()->user()->active_role)}}"
                ></settings-component>
            </div>
        </div>
    </div>
@endsection
