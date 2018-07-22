<!DOCTYPE html>
<html lang="en">
<head>
    <title>Election</title>
    <script src="{{asset('js/app.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container">

</div>
@yield('content')
</body>
</html>
