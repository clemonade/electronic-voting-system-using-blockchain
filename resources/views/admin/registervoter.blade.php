<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>Voter | Register</title>
@stop
@section('nav')
@include('layouts.admin')
@stop
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/voter.js')}}"></script>
@stop
@section('content')

<h1>Register Voter</h1>

<!--Illuminate/HTML used for model binding-->
{!! Form::open([
'route' => ['registervoter.store'],
'onsubmit' => 'return App.validateRegister()',
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

<div class="form-group">
    <label for="gender">Gender:</label><br>
    <div id="gender" class="form-control {{ ($errors->has('gender')) ? 'is-invalid': '' }}">
        @foreach(Common::$genders as $key => $val)
        <div class="form-check-inline">
            <label class="form-check-label">
                {!! Form::radio('gender', $key, false, [
                'class' => 'form-check-input',
                ]) !!} {{$val}}
            </label>
        </div>
        @endforeach
    </div>
    <div class="invalid-feedback">
        {{($errors->has('gender')) ? $errors->first('gender') : 'Gender is required.'}}
    </div>
</>

<div class="form-group">
    <label for="state">State:</label>
    {!! Form::select('state', ['00' => '- Select State -'] + Common::$states, null, [
    'id' => 'state',
    'class' => 'form-control' . (($errors->has('state')) ? ' is-invalid' : ''),
    'onchange' => 'App.populateFederalDropdown(this.value)',
    ],[
    '00' => ['disabled selected hidden']
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('state')) ? $errors->first('state') : 'State is required.'}}
    </div>
</div>

<!--Repopulation on request validation error incomplete (just kill me already)-->
<div class="form-group">
    <label for="federalconstituency">Federal Constituency:</label>
    {!! Form::select('federalconstituency', ['0' => '- Select Federal Constituency -'], null, [
    'id' => 'federalconstituency',
    'class' => 'form-control' . (($errors->has('federalconstituency')) ? ' is-invalid' : ''),
    'onchange' => 'App.populateStateDropdown(this.value)',
    ],[
    '0' => ['disabled selected hidden']
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('federalconstituency')) ? $errors->first('federalconstituency') : 'Federal Constituency is
        required.'}}
    </div>
</div>

<div class="stateconstituency form-group">
    <label for="stateconstituency">State Constituency:</label>
    {!! Form::select('stateconstituency', ['0' => '- Select State Constituency -'], null, [
    'id' => 'stateconstituency',
    'class' => 'form-control' . (($errors->has('stateconstituency')) ? ' is-invalid' : ''),
    ],[
    '0' => ['disabled selected hidden']
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('stateconstituency')) ? $errors->first('stateconstituency') : 'State Constituency is
        required.'}}
    </div>
</div>

<button type="submit" class="btn btn-primary">Register</button>

{!! Form::close() !!}

@stop
