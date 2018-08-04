<?php

use App\Common;

?>
@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/voter.js')}}"></script>
@stop
@section('content')

<h1>Register Voter</h1>
<div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!--    TODO Replace all laravelcollective/html forms-->
    {!! Form::model($voter, [
    'route' => ['registervoter.store'],
    ]) !!}

    <!-- Name -->
    <div>
        {!! Form::label('voter-name', 'Name') !!}
        <div>
            {!! Form::text('name', null, [
            'id' => 'voter-name',
            'maxlength' => 100,
            ]) !!}

        </div>
    </div>

    <!-- NRIC -->
    <div>
        {!! Form::label('voter-nric', 'NRIC') !!}
        <div>
            {!! Form::text('nric', null, [
            'id' => 'voter-nric',
            'maxlength' => 12,
            ]) !!}
        </div>
    </div>


    <!-- Gender -->
    <div>
        {!! Form::label('voter-gender', 'Gender') !!}

        <div>
            @foreach(Common::$genders as $key => $val)
            {!! Form::radio('gender', $key) !!} {{$val}}
            @endforeach
        </div>
    </div>

    <!-- State -->
    <div>
        {!! Form::label('voter-state', 'State') !!}
        <div>
            {!! Form::select('state', Common::$states, null, [
            'id' => 'state',
            'onchange'=>"App.populateFederalDropdown(this.value)",
            'placeholder' => '- Select State -',
            ]) !!}
        </div>
    </div>

    <!-- Federal Constituency -->
    <div>
        {!! Form::label('federalconstituency', 'Federal Constituency') !!}
        <div>
            {!! Form::select('federalconstituency',
            [],
            null, [
            'id' => 'federalconstituency',
            'onchange'=>"App.populateStateDropdown(this.value)",
            'placeholder' => '- Select Federal Constituency -',
            ]) !!}
        </div>
    </div>

    <!-- State Constituency -->
    <div class="stateconstituency">
        {!! Form::label('stateconstituency', 'State Constituency') !!}
        <div>
            {!! Form::select('stateconstituency',
            [],
            null, [
            'id' => 'stateconstituency',
            'placeholder' => '- Select State Constituency -',
            ]) !!}
        </div>
    </div>

    <!-- Submit Button -->
    <div>
        <div>
            {!! Form::button('Register', [
            'type' => 'submit',
            ]) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<span id="status"></span>
<span id="list"></span>

@endsection
