<?php use App\Common; ?>

@section('title')
<title>Voter | Verify</title>
@stop
@extends('layouts.app')
@section('script')
<script src="{{asset('js/verify.js')}}"></script>
@stop
@include('layouts.admin')
@section('content')

<h1>Verify Voter</h1>
<h2>{{ Common::$states[$federal['state']] }}</h2>
<h2>{{ $federal['code'] }}
    <small>{{ $federal['name'] }}</small>
</h2>
<h2>{{ (isset($state)) ? $state['code'] : '' }}
    <small>{{ (isset($state)) ? $state['name'] : '' }}</small>
</h2>

{!! Form::open([
'route' => ['admin.prevote'],
'class' => 'form-horizontal',
'onsubmit' => 'return App.validate()',
]) !!}

<div class="form-group">
    <label for="name">Name:</label>
    {!! Form::text('name', null, [
    'id' => 'name',
    'class' => 'form-control' . (($errors->has('name')) ? ' is-invalid' : ''),
    'maxlength' => 100,
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('name')) ? $errors->first('name') : 'Name is required.'}}
    </div>
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    {!! Form::text('nric', null, [
    'id' => 'nric',
    'class' => 'form-control' . (($errors->has('nric')) ? ' is-invalid' : ''),
    'maxlength' => 12,
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('nric')) ? $errors->first('nric') : ''}}
        <span id="nricfeedback"></span>
    </div>
</div>

{{ Form::hidden('federal', $federal) }}
{{ Form::hidden('state', (isset($state)) ? $state : null) }}

<button type="submit" class="btn btn-primary">Verify</button>

{!! Form::close() !!}

@endsection
