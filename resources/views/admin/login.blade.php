<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>Admin | Login</title>
@stop
@section('nav')
@include('layouts.voter')
@stop
@section('script')
<script src="{{asset('js/login.js')}}"></script>
@stop
@section('content')

<h1>Login</h1>
<div class="form-group">
    <label for="current">Current Address:</label>
    <input type="text" id="current" class="form-control" aria-describedby="currenthelp" readonly>
    <small id="currenthelp" class="form-text text-muted">
        Addresses should match in order to proceed.
    </small>
</div>
<div class="form-group">
    <label for="admin">Administrator Address:</label>
    <input type="text" id="admin" class="form-control" readonly>
</div>

@stop
