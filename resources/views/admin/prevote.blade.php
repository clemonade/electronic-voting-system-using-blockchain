<?php use App\Common; ?>

@section('title')
<title>Voter | Verify</title>
@stop
@extends('layouts.app')
@section('script')
@stop
@section('content')

<h1 class="display-1 text-center">ELECTION</h1>
<h1>Voter Verification</h1>

{!! Form::open([
'route' => ['admin.vote'],
'class' => 'form-horizontal'
]) !!}

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" value="<?php echo $voter->name ?>" class="form-control" maxlength="100"
           disabled>
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    <input type="text" id="nric" value="<?php echo $voter->nric ?>" class="form-control" maxlength="12"
           disabled>
</div>

<div class="form-group">
    <label for="state">State:</label>
    <input type="text" id="state" value="<?php echo Common::$states[$voter->state] ?>" class="form-control"
           disabled>
</div>

<div class="form-group">
    <label for="federal">Federal Constituency:</label>
    <input type="text" id="federal" value="<?php echo $federal->code . ' ' . $federal->name ?>"
           class="form-control"
           disabled>
</div>

<div class="form-group">
    <label for="stateconstituency">State Constituency:</label>
    <input type="text" id="stateconstituency"
           value="<?php echo (isset($state)) ? $state->code . ' ' . $state->name : 'INAPPLICABLE' ?>"
           class="form-control"
           disabled>
</div>

<div class="form-group">
    <label for="eligible">Eligibility:</label>
    <input type="text" id="eligible"
           value="<?php echo $voter->voted ? 'INELIGIBLE' : 'ELIGIBLE' ?>"
           class="form-control"
           disabled>
</div>

<!--TODO Toggle visibility-->
<div class="form-group">
    <label for="nonce">Nonce:</label>
    <input type="password" id="nonce" name="nonce" class="form-control" maxlength="64">
</div>

{{ Form::hidden('federal', $federal) }}
{{ Form::hidden('state', (isset($state)) ? $state : null) }}
{{ Form::hidden('voter', $voter) }}

<button type="submit" class="btn btn-primary">Proceed</button>

{!! Form::close() !!}

<nav class="navbar navbar-expand bg-light fixed-bottom justify-content-center">
    <span id="status"><?php echo Session::get('status'); ?></span>
</nav>

@endsection
