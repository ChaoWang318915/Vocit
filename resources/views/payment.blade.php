@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui two column centered grid">
            <div class="sixteen wide tablet eight wide computer column">
                <div class="ui green message">
                    <ul class="list">
                        <h3>Payable Amount: ${{\Illuminate\Support\Arr::get($plan, 'amount') * request()->get('quantity')}}</h3>
                    </ul>
                </div>

                @if($errors->any())
                    @php $error = $errors->first() @endphp
                @endif
                <payment-component
                    v-bind:quantity="{{json_encode(request()->get('quantity'))}}"
                    v-bind:package="{{json_encode(request()->get('package'))}}"
                    v-bind:csrf-token="{{json_encode(csrf_token())}}"
                    v-bind:session-error="{{isset($error) ? json_encode($error) : json_encode("")}}"
                    v-bind:active-business-name="{{json_encode(auth()->user()->active_business->subdomain)}}"
                >

                </payment-component>
            </div>
        </div>
    </div>
@endsection
