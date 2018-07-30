@extends('layouts.app')
@section('script')
<script src="{{asset('js/voter.js')}}"></script>
@stop
@section('content')

<h1>Voter Verification</h1>
<div class="form">
    <label for="name">Name:</label>
    <br><input type="text" id="name"/>
    <br><label for="nric">NRIC:</label>
    <br><input type="text" id="nric"/>
    <br><label for="nonce">Nonce:</label>
    <br><input type="text" id="nonce"/>
    <br>
    <button id="verify" onclick="App.verify()">Verify</button>
</div>

<div class="content">
    <label for="hash">Hash:</label>
    <br><input type="text" id="hash" disabled/>
    <br><label for="federal">Federal Constituency Vote:</label>
    <br><input type="text" id="federal" disabled/>
    <br><label for="state">State Constituency Vote:</label>
    <br><input type="text" id="state" disabled/>
</div>

<br>
<span id="status"></span>
<span id="list"></span>

@endsection
