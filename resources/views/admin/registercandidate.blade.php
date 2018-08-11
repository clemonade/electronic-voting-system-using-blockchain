<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>Candidate | Register</title>
@stop
@section('nav')
@include('layouts.admin')
@stop
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/candidate.js')}}"></script>
@stop
@section('content')

<h1>Register Candidate</h1>

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
    <div class="invalid-feedback">
        Name is required.
    </div>
</div>

<!--TODO Display party logo in dropdown?-->
<div class="form-group">
    <label for="party">Party:</label>
    <select id="party" name="party" class="form-control">
        <option disabled selected hidden>- Select Party -</option>
    </select>
    <div class="invalid-feedback">
        Party is required.
    </div>
</div>

<div class="form-group">
    <label for="state">State:</label>
    <select id="state" name="state" class="form-control">
        <option disabled selected hidden>- Select State -</option>
        @foreach(Common::$states as $key => $val)
        <option value="{{ $key }}">{{$val}}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        State is required.
    </div>
</div>

<div class="form-group">
    <label for="level">Level:</label>
    <select id="level" name="level" onchange="App.populateConstituencies(this.value)" class="form-control">
        <option disabled selected hidden>- Select Level -</option>
        @foreach(Common::$types as $key => $val)
        <option value="{{ $key }}">{{$val}}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        Level is required.
    </div>
</div>

<div class="form-group">
    <label for="constiteuncy">Constituency:</label>
    <select id="constiteuncy" name="constiteuncy" class="form-control">
        <option disabled selected hidden>- Select Constituency -</option>
    </select>
    <div class="invalid-feedback">
        Constituency is required.
    </div>
</div>

<button class="btn btn-primary" id="register" onclick="App.validate()">Register</button>

@endsection
