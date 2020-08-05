@extends('layouts.admin.cpanel', ['pageClass' => 'admin-page fixed-header page'])

@section('styles')
@endsection

@section('content')
    <div class="ui grid">
        <div class="column">
            <div class="ui four stackable cards">
                <div class="ui red card">
                    <div class="content">
                        <div class="ui red large header">{{$users_count}}</div>
                    </div>
                    <div class="extra content">
                        <div class="ui small primary header">Users</div>
                    </div>
                </div>
                <div class="ui orange card">
                    <div class="content">
                        <div class="ui orange large header">{{$requests_count}}</div>
                    </div>
                    <div class="extra content">
                        <div class="ui small primary header">Requests</div>
                    </div>
                </div>
                <div class="ui orange card">
                    <div class="content">
                        <div class="ui orange large header">{{$exhcanges_count}}</div>
                    </div>
                    <div class="extra content">
                        <div class="ui small primary header">Exchanges</div>
                    </div>
                </div>
                <div class="ui yellow card">
                    <div class="content">
                        <div class="ui yellow large header">{{$business_count}}</div>
                    </div>
                    <div class="extra content">
                        <div class="ui small primary header">Businesses</div>
                    </div>
                </div>
                <div class="ui olive card">
                    <div class="content">
                        <div class="ui olive large header">{{$comments_count}}</div>
                    </div>
                    <div class="extra content">
                        <div class="ui small primary header">Comments</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
