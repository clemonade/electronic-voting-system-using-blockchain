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

{!! Form::open([
'route' => ['admin.postvote'],
'class' => 'form-horizontal',
]) !!}

<div class="form-group">
    <label for="hash">Voter Hash:</label>
    <input type="text" id="hash" name="hash" value="<?php echo $hash ?>" class="form-control" maxlength="64" disabled>
</div>

<div class="form-group">
    <label for="federal">Federal Constituency:</label>
    <input type="text" id="federal" name="federal"
           value="<?php echo json_decode($federal, true)['code'] . ' ' . json_decode($federal, true)['name'] ?>"
           class="form-control"
           disabled>
</div>

<div class="form-group">
    <label for="state">State Constituency:</label>
    <input type="text" id="state" name="state"
           value="<?php echo isset($state) ? json_decode($state, true)['code'] . ' ' . json_decode($state, true)['name'] : 'INAPPLICABLE' ?>"
           class="form-control"
           disabled>
</div>

<div class="card form-group">
    <div class="card-header">Federal Candidates</div>
    <div class="card-body">
        <table id="federalcandidates" class="table table-bordered table-hover" style="width:100%;height:100%;">
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="statecandidatediv" class="card form-group">
    <div class="card-header">State Candidates</div>
    <div class="card-body">
        <table id="statecandidates" class="table table-bordered table-hover" style="width:100%;height:100%;">
            <tbody></tbody>
        </table>
    </div>
</div>

<button type="submit" class="btn btn-primary btn-lg btn-block" onclick="App.vote()">Cast Vote</button>

{{ Form::hidden('id', $id) }}
{{ Form::hidden('federal', $federal) }}
{{ Form::hidden('state', (isset($state)) ? $state : null) }}

{!! Form::close() !!}

@endsection


