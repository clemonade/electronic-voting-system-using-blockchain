<!DOCTYPE html>
<html lang="en">
<head>
    <title>Election</title>
    <script src="{{asset('js/app.js')}}"></script>
<!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"-->
<!--              integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">-->
    @yield('script')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container"></div>
<div class="wrapper">@yield('content')</div>
</body>
</html>
