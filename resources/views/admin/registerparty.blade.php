@section('title')
<title>Party | Register</title>
@stop
@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@include('layouts.admin')
@section('content')

<h1>Register Party</h1>

{!! Form::open([
'route' => ['party.store'],
'class' => 'form-horizontal',
'enctype' => 'multipart/form-data',
]) !!}

<div class="form-group">
    <label for="logo">Logo:</label>
    <input type="file" id="logo" name="image" class="form-control-file border">
</div>

<div class="form-group">
    <label for="name">Party Name:</label>
    <input type="text" id="name" name="name" class="form-control">
</div>

<div class="form-group">
    <label for="abbreviation">Party Abbreviation:</label>
    <input type="text" id="abbreviation" name="abbreviation" class="form-control">
</div>

<button type="submit" class="btn btn-primary" onclick="App.registerParty()">Register</button>

{!! Form::close() !!}

<span id="status"></span>

@endsection
