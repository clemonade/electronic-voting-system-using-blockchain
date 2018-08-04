@extends('layouts.app')
@section('script')
<?php use Illuminate\Support\Facades\Session; ?>
@stop
@section('content')

<h1>Verify Voter</h1>
<h3><?php echo $federal['code'] . ' ' . $federal['name'] ?></h3>
<h3><?php echo (isset($state)) ? $state['code'] . ' ' . $state['name'] : '' ?></h3>

<div class="panel-body">
    {!! Form::open([
    'route' => ['admin.prevote'],
    'class' => 'form-horizontal'
    ]) !!}

    <!-- Name -->
    <div class="form-group row">
        {!! Form::label('name', 'Name', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('name', null, [
            'id' => 'name',
            'class' => 'form-control',
            'maxlength' => 100,
            ]) !!}
        </div>
    </div>

    <!-- NRIC -->
    <div class="form-group row">
        {!! Form::label('nric', 'NRIC', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('nric', null, [
            'id' => 'nric',
            'class' => 'form-control',
            'maxlength' => 12,
            ]) !!}
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group row">
        <div>
            {!! Form::button('Verify', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            ]) !!}
        </div>
    </div>

    {{ Form::hidden('federal', $federal) }}
    {{ Form::hidden('state', (isset($state)) ? $state : null) }}

    {!! Form::close() !!}
</div>

<br>
<span id="status"><?php echo Session::get('status'); ?></span>
<span id="list"></span>
@endsection
