@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="column">
            <div class="centered-container">
                <div>
                    <h3>Join, Business Name</h3>
                    <p>Click join to be member of this organization</p>
                    <form method="post">
                        @csrf
                        <input type="hidden" name="email" value="{{$email}}">
                        <div>
                            <a href="{{url('/')}}" class="ui button">Cancel</a>
                            <button class="ui green button">Join</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
