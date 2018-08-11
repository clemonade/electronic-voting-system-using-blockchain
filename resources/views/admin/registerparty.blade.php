@extends('layouts.app')
@section('title')
<title>Party | Register</title>
@stop
@section('nav')
@include('layouts.admin')
@stop
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@section('content')

<h1>Register Party</h1>

{!! Form::open([
'id' => 'form',
'route' => ['party.store'],
'enctype' => 'multipart/form-data',
'onsubmit' => 'return App.validate()',
]) !!}

<!--Literally does not return the file on request validation failure-->
<div class="form-group">
    <label for="image">Logo:</label><br>
    {!! Form::file('image', [
    'id' => 'image',
    'class' => 'form-control-file border' . (($errors->has('image')) ? ' is-invalid' : ''),
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('image')) ? $errors->first('image') : 'Logo is required.'}}
    </div>
</div>

<div class="form-group">
    <label for="name">Party Name:</label>
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
    <label for="name">Party Abbreviation:</label>
    {!! Form::text('abbreviation', null, [
    'id' => 'abbreviation',
    'class' => 'form-control' . (($errors->has('abbreviation')) ? ' is-invalid' : ''),
    'maxlength' => 20,
    ]) !!}
    <div class="invalid-feedback">
        {{($errors->has('abbreviation')) ? $errors->first('abbreviation') : ''}}
        <span id="abbreviationfeedback"></span>
    </div>
</div>

<button type="submit" class="btn btn-primary">Register</button>

{!! Form::close() !!}

@stop
