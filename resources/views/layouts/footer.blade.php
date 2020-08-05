<footer class="ui container-fluid">
    <div class="ui two column grid">
        <div class="column text-left">
            <ul class="text-left">
                <li><span>&copy; {{date('Y')}} {{env('APP_NAME')}}</span></li>
            </ul>

        </div>
        <div class="column">
            <ul>
                <li><a href="/">HOME</a></li>
                <li><a href="{{url('about')}}">ABOUT</a></li>
                <li><a href="{{url('help')}}">HELP</a></li>
{{--                <li><a href="{{url('privacy')}}">PRIVACY</a></li>--}}
                <li><a href="{{url('terms')}}">TERMS & CONDITION</a></li>
            </ul>
        </div>
    </div>
</footer>
