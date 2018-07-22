<?php

use App\Common;

?>
@extends('layouts.app')
@section('content')

<div class="panel-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {!! Form::model($voter, [
    'route' => ['registervoter.store'],
    'class' => 'form-horizontal'
    ]) !!}

    <!-- Name -->
    <div class="form-group row">
        {!! Form::label('voter-name', 'Name', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('name', null, [
            'id' => 'voter-name',
            'class' => 'form-control',
            'maxlength' => 100,
            ]) !!}

        </div>
    </div>

    <!-- NRIC -->
    <div class="form-group row">
        {!! Form::label('voter-nric', 'NRIC', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('nric', null, [
            'id' => 'voter-nric',
            'class' => 'form-control',
            'maxlength' => 12,
            ]) !!}
        </div>
    </div>


    <!-- Gender -->
    <div class="form-group row">
        {!! Form::label('voter-gender', 'Gender', [
        'class' => 'control-label col-sm-3',
        ]) !!}

        <div>
            @foreach(Common::$genders as $key => $val)
            {!! Form::radio('gender', $key) !!} {{$val}}
            @endforeach
        </div>
    </div>

    <!-- State -->
    <div class="form-group row">
        {!! Form::label('voter-state', 'State', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>

            {!! Form::select('state', Common::$states, null, [
            'class' => 'form-control',
            'placeholder' => '- Select State -',
            ]) !!}
        </div>
    </div>

    <!-- Federal Constituency -->
    <div class="form-group row">
        {!! Form::label('federalconstituency', 'Federal Constituency', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::select('federalconstituency',
            \App\FederalConstituency::pluck('name', 'id'),
            null, [
            'class' => 'form-control',
            'placeholder' => '- Select Federal Constituency -',
            ]) !!}
        </div>
    </div>

    <!-- State Constituency -->
    <div class="form-group row">
        {!! Form::label('stateconstituency', 'State Constituency', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::select('stateconstituency',
            \App\StateConstituency::pluck('name', 'id'),
            null, [
            'class' => 'form-control',
            'placeholder' => '- Select State Constituency -',
            ]) !!}
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group row">
        <div>
            {!! Form::button('Save', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            ]) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection
