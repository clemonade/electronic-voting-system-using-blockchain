@extends('layouts.app')
@section('script')
<script src="{{asset('js/voter.js')}}"></script>
@stop
@section('content')

<h1>Voter Verification</h1>

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" class="form-control" maxlength="12">
</div>

<div class="form-group">
    <label for="nonce">Nonce:</label>
    <input type="password" id="nonce" name="nonce" class="form-control" maxlength="64">
</div>

<button id="verify" onclick="App.verify()" class="btn btn-primary">Verify</button><br><br>

<div class="card">
    <div class="card-body">
        <div class="form-group">
            <label for="hash">Voter Hash:</label>
            <input type="text" id="hash" class="form-control" maxlength="64" disabled>
        </div>

        <div class="form-group">
            <label for="federal">Federal Constituency Vote:</label>
            <input type="text" id="federal" class="form-control" disabled>
        </div>

        <div class="form-group">
            <label for="state">State Constituency Vote:</label>
            <input type="text" id="state" class="form-control" disabled>
        </div>
    </div>
</div>

<span id="status"></span>

@endsection
