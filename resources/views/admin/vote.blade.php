@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federal = <?php echo $federal ?>;
    let state = <?php echo $state ?>;
    let hash = '<?php echo $hash ?>';
</script>
<script src="{{asset('js/vote.js')}}"></script>
@stop
@section('content')

<h1>Vote Casting</h1>
<div class="panel-body">
    {!! Form::open([
    'route' => [
    'admin.verify',
    json_decode($federal, true)['code'],
    explode("_", json_decode($state, true)['code'])[1]
    ],
    'class' => 'form-horizontal',
    'method' => 'get',
    ]) !!}

    <!-- Hash -->
    <div class="form-group row">
        {!! Form::label('hash', 'Voter Hash', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div>
            {!! Form::text('hash', $hash , [
            'id' => 'hash',
            'class' => 'form-control',
            'maxlength' => 100,
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
            {!! Form::text('federal', null , [
            'id' => 'federal',
            'class' => 'form-control',
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
            {!! Form::text('state', null , [
            'id' => 'state',
            'class' => 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- Federal Candidates -->
    <div class="form-group row">
        <fieldset id="federalcandidates">
            <legend>Federal Constituency</legend>
        </fieldset>
    </div>

    <!-- State Candidates -->
    <div class="form-group row">
        <fieldset id="statecandidates">
            <legend>State Constituency</legend>
        </fieldset>
    </div>

    <!-- Submit Button -->
    <div class="form-group row">
        <div>
            {!! Form::button('Cast Vote', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'onclick' => 'App.vote()',
            ]) !!}
        </div>
    </div>

    {!! Form::close() !!}
</div>

<br>
<span id="status"></span>
<span id="list"></span>
@endsection


