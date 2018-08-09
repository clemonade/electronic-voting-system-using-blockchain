<?php use App\Common; ?>

@section('title')
<title>Voter | Register</title>
@stop
@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/voter.js')}}"></script>
@stop
@include('layouts.admin')
@section('content')

<h1>Register Voter</h1>

<!--Illuminate/HTML used for model binding-->
{!! Form::model($voter, [
'route' => ['registervoter.store']
]) !!}

<div class="form-group">
    <label for="name">Name:</label>
    {!! Form::text('name', null, [
    'id' => 'name',
    'class' => 'form-control' . (($errors->has('name')) ? ' is-invalid' : ''),
    'maxlength' => 100,
    ]) !!}
    @if($errors->has('name'))
    <div class="invalid-feedback">
        {{$errors->first('name')}}
    </div>
    @endif
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    {!! Form::text('nric', null, [
    'id' => 'nric',
    'class' => 'form-control' . (($errors->has('nric')) ? ' is-invalid' : ''),
    'maxlength' => 12,
    ]) !!}
    @if($errors->has('nric'))
    <div class="invalid-feedback">
        {{$errors->first('nric')}}
    </div>
    @endif
</div>

<div class="form-group">
    <label for="gender">Gender:</label><br>
    <div class="form-control {{ ($errors->has('gender')) ? 'is-invalid': '' }}">
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
    @if($errors->has('gender'))
    <div class="invalid-feedback">
        {{$errors->first('gender')}}
    </div>
    @endif
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
    @if($errors->has('state'))
    <div class="invalid-feedback">
        {{$errors->first('state')}}
    </div>
    @endif
</div>

<!--Repopulation on validation error incomplete (just kill me already)-->
<div class="form-group">
    <label for="federalconstituency">Federal Constituency:</label>
    {!! Form::select('federalconstituency', ['0' => '- Select Federal Constituency -'], null, [
    'id' => 'federalconstituency',
    'class' => 'form-control' . (($errors->has('federalconstituency')) ? ' is-invalid' : ''),
    'onchange' => 'App.populateStateDropdown(this.value)',
    ],[
    '0' => ['disabled selected hidden']
    ]) !!}
    @if($errors->has('federalconstituency'))
    <div class="invalid-feedback">
        {{$errors->first('federalconstituency')}}
    </div>
    @endif
</div>

<div class="stateconstituency form-group">
    <label for="stateconstituency">State Constituency:</label>
    {!! Form::select('stateconstituency', ['0' => '- Select State Constituency -'], null, [
    'id' => 'stateconstituency',
    'class' => 'form-control' . (($errors->has('stateconstituency')) ? ' is-invalid' : ''),
    ],[
    '0' => ['disabled selected hidden']
    ]) !!}
    @if($errors->has('stateconstituency'))
    <div class="invalid-feedback">
        {{$errors->first('stateconstituency')}}
    </div>
    @endif
</div>

<button type="submit" class="btn btn-primary">Register</button>

{!! Form::close() !!}

@endsection
