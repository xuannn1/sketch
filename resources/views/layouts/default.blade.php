<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable=0", >
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>@yield('title', '废文网') - 每日一丧</title>
      <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
      <script src="{{ mix('/js/theme.js') }}"></script>
   </head>
   <body>
         @include('layouts._header')
         @include('shared.messages')
         @yield('content')
         @include('layouts._footer')
        <script src="{{ mix('/js/all.js') }}"></script>
   </body>
</html>
