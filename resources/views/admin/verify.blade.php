@section('title')
<title>Voter | Verify</title>
@stop
@extends('layouts.app')
@section('script')
@stop
@include('layouts.admin')
@section('content')

<h1>Verify Voter</h1>
<h2>{{ $federal['code'] }}
    <small>{{ $federal['name'] }}</small>
</h2>
<h2>{{ (isset($state)) ? $state['code'] : '' }}
    <small>{{ (isset($state)) ? $state['name'] : '' }}</small>
</h2>
<!--TODO Show state-->
{!! Form::open([
'route' => ['admin.prevote'],
'class' => 'form-horizontal'
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

{{ Form::hidden('federal', $federal) }}
{{ Form::hidden('state', (isset($state)) ? $state : null) }}

<button type="submit" class="btn btn-primary">Verify</button>

{!! Form::close() !!}

@endsection
