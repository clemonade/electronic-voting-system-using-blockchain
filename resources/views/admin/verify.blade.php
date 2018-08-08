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
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" class="form-control" maxlength="12">
</div>

{{ Form::hidden('federal', $federal) }}
{{ Form::hidden('state', (isset($state)) ? $state : null) }}

<button type="submit" class="btn btn-primary">Verify</button>

{!! Form::close() !!}

@endsection
