@extends('layouts.app')
@section('script')
@stop
@section('content')
<h1>Voter Verification</h1>
<div class="panel-body">
    {!! Form::open([
    'route' => ['admin.vote'],
    'class' => 'form-horizontal'
    ]) !!}

    <!-- Name -->
    <div class="form-group row">
        {!! Form::label('name', 'Name', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('name', $voter->name , [
            'id' => 'name',
            'class' => 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- NRIC -->
    <div class="form-group row">
        {!! Form::label('nric', 'NRIC', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('nric', $voter->nric, [
            'id' => 'nric',
            'class' => 'form-control',
            'maxlength' => 12,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- Federal -->
    <div class="form-group row">
        {!! Form::label('federal', 'Federal Constituency', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('federal', $federal-> code . ' ' . $federal->name, [
            'id' => 'federal',
            'class' =>a 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- State -->
    <div class="form-group row">
        {!! Form::label('state', 'State Constituency', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('state', $state-> code . ' ' . $state->name, [
            'id' => 'state',
            'class' => 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- Eligibility -->
    <div class="form-group row">
        {!! Form::label('eligible', 'Eligibility', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('eligible', $voter->valid ? 'ELIGIBLE' : 'INELIGIBLE', [
            'id' => 'eligible',
            'class' => 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- Nonce -->
    <div class="form-group row">
        {!! Form::label('nonce', 'Nonce', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('nonce', null, [
            'id' => 'nonce',
            'class' => 'form-control',
            'maxlength' => 100,
            ]) !!}
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group row">
        <div>
            {!! Form::button('Proceed', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            ]) !!}
        </div>
    </div>

    {{ Form::hidden('federal', $federal) }}
    {{ Form::hidden('state', $state) }}
    {{ Form::hidden('voter', $voter) }}

    {!! Form::close() !!}
</div>

<br>
<span id="status"></span>
<span id="list"></span>
@endsection

