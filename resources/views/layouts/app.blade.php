<!DOCTYPE html>
<html lang="en">
<head>
    <title>Election</title>
    <script src="{{asset('js/app.js')}}"></script>
    @yield('script')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container"></div>
<div class="wrapper">@yield('content')</div>
</body>
</html>
