<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Company</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
</head>
<body>
<header>
    @yield('header')
</header>
@if (session('status'))
        <script>
            alert("{!!session('status')!!}");
        </script>
@endif
    @yield('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/crud.js')}}"></script>
</body>
</html>