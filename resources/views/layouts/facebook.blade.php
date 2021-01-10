<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    @yield('seo')
</head>
<body>
    @yield('content')
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
