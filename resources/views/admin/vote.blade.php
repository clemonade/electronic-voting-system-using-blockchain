@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federal = <?php echo $federal ?>;
    let state = <?php echo(isset($state) ? $state : json_encode(null))?>;
    let hash = '<?php echo $hash ?>';
</script>
<script src="{{asset('js/vote.js')}}"></script>
@stop
@section('content')

<h1>Vote Casting</h1>
<div class="panel-body">
    {!! Form::open([
    'route' => ['admin.postvote'],
    'class' => 'form-horizontal',
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
            {!! Form::text('federal',
            json_decode($federal, true)['code'] . ' ' . json_decode($federal, true)['name'] ,
            [
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
            {!! Form::text('state',
            isset($state) ? json_decode($state, true)['code'] . ' ' . json_decode($state, true)['name'] :
            'INAPPLICABLE', [
            'id' => 'state',
            'class' => 'form-control',
            'maxlength' => 100,
            'disabled',
            ]) !!}
        </div>
    </div>

    <!-- Federal Candidates -->
    <div class="form-group row">
        <table id="federalcandidates">
            <caption>Federal Constituency</caption>
            <tbody></tbody>
        </table>
    </div>

    <!-- State Candidates -->
    <div class="form-group row">
        <table id="statecandidates">
            <caption>State Constituency</caption>
            <tbody></tbody>
        </table>
    </div>
    <!--TODO Change onclick-->
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

    {{ Form::hidden('id', $id) }}
    {{ Form::hidden('federal', $federal) }}
    {{ Form::hidden('state', (isset($state)) ? $state : null) }}

    {!! Form::close() !!}
</div>

<br>
<span id="status"></span>
<span id="list"></span>
@endsection


