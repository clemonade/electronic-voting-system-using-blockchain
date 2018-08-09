@section('title')
<title>Party | Register</title>
@stop
@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@include('layouts.admin')
@section('content')

<h1>Register Party</h1>

{!! Form::open([
'id' => 'form',
'route' => ['party.store'],
'enctype' => 'multipart/form-data',
]) !!}

<!--Literally does not return the file on request validation failure-->
<div class="form-group">
    <label for="image">Logo:</label><br>
    {!! Form::file('image', [
    'id' => 'image',
    'class' => 'form-control-file border' . (($errors->has('image')) ? ' is-invalid' : ''),
    ]) !!}
    @if($errors->has('image'))
    <div class="invalid-feedback">
        {{$errors->first('image')}}
    </div>
    @endif
</div>

<div class="form-group">
    <label for="name">Party Name:</label>
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
    <label for="name">Party Abbreviation:</label>
    {!! Form::text('abbreviation', null, [
    'id' => 'abbreviation',
    'class' => 'form-control' . (($errors->has('abbreviation')) ? ' is-invalid' : ''),
    'maxlength' => 20,
    ]) !!}
    @if($errors->has('abbreviation'))
    <div class="invalid-feedback">
        {{$errors->first('abbreviation')}}
    </div>
    @endif
</div>

<button type="submit" class="btn btn-primary" onclick="App.validate()">Register</button>

{!! Form::close() !!}

@endsection
