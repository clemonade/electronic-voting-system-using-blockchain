@extends('layouts.app')
@section('title')
<title>Voter | Verify</title>
@stop
@section('nav')
@include('layouts.voter')
@stop
@section('script')
<script src="{{asset('js/voter.js')}}"></script>
@stop
@section('content')

<h1>Voter Verification</h1>

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
    <div class="invalid-feedback">
        Name is required.
    </div>
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" class="form-control" maxlength="12" aria-describedby="nrichelp">
    <small id="noncehelp" class="form-text text-muted">
        Without dashes.
    </small>
    <div class="invalid-feedback">
        NRIC is required.
    </div>
</div>

<div class="form-group">
    <label for="nonce">Nonce:</label>
    <input type="password" id="nonce" name="nonce" class="form-control" maxlength="64">
    <div class="invalid-feedback">
        Nonce is required.
    </div>
</div>

<button id="verify" onclick="App.validate()" class="btn btn-primary btn-block">Verify</button><br><br>

<div class="card">
    <div class="card-header">Authentication Card</div>
    <div class="card-body">
        <div class="form-group">
            <label for="bool">Status:</label>
            <input type="text" id="bool" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="hash">Voter Hash:</label>
            <input type="text" id="hash" class="form-control" maxlength="64" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="federal">Federal Constituency Vote:</label>
            <input type="text" id="federal" class="form-control" readonly>
        </div>

        <div class="form-group" hidden>
            <label for="state">State Constituency Vote:</label>
            <input type="text" id="state" class="form-control" readonly>
        </div>
    </div>
    <div class="card-footer">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>

@stop
