<!DOCTYPE html>
<html lang="en">
<head>
    @yield('title')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .container {
            padding-bottom: 40px
        }
    </style>
</head>
<body>
@yield('nav')
<div class="container">
    @yield('content')
</div>
<script src="{{asset('js/app.js')}}"></script>
@yield('script')
</body>
</html>
