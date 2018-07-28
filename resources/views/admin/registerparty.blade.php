@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@section('content')

<h1>Register Party</h1>
<div class="form">
    <label for="name">Party Name:</label>
    <br><input type="text" id="name" name="name"/>
    <br><label for="abbreviation">Party Abbreviation:</label>
    <br><input type="text" id="abbreviation" name="abbreviation"/>
    <br>
    <button id="register" onclick="App.registerParty()">Register</button>
</div>

<br>
<span id="status"></span>
<span id="list"></span>

@endsection
