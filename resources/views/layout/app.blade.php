<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <title>Properties manager</title>
 
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.test_url')}}"></script>
 
</head>
<body>
    <div id="app">
 
        <main>
            @yield('content')
        </main>
 
    </div>
</body>
</html>