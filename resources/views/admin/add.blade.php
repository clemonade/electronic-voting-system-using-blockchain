<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>DEV | Increment</title>
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

<h1>Increment Votes</h1>

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

<div class="form-group">
    <label for="candidate">Candidate:</label>
    <select id="candidate" name="candidate" class="form-control">
        <option disabled selected hidden>- Select Candidate -</option>
    </select>
    <div class="invalid-feedback">
        Candidate is required.
    </div>
</div>

<div class="form-group">
    <label for="votes">Votes:</label>
    <input type="text" id="votes" name="votes" class="form-control" maxlength="10" aria-describedby="voteshelp">
    <small id="voteshelp" class="form-text text-muted">
        Only unsigned integer.
    </small>
    <div id="voteserror" class="invalid-feedback">
    </div>
</div>

<button class="btn btn-primary" id="register" onclick="App.validateIncrement()">Register</button>

@endsection
