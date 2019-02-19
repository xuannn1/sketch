<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable=0", >
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '废文网') - 有趣有品有点丧</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="shortcut icon" href="{{ secure_asset('img/So-logo.ico') }}" >
</head>
<body>
    @include('layouts._header')
    @include('shared.messages')
    @include('layouts._search')
    @yield('content')
    @include('layouts._footer')
    <script src="{{ mix('/js/all.js') }}"></script>
</body>
</html>
