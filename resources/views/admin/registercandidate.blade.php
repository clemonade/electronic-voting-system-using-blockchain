@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/candidate.js')}}"></script>
@stop
@section('content')

<h1 class="display-4">Register Candidate</h1>
<div class="row">
    <div class="col-3">
        <label for="name">Name:</label>
    </div>
    <div class="col-9">
        <input type="text" id="name" name="name"/>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <label for="party">Party:</label>
    </div>
    <div class="col-9">
        <select id="party" name="party"> </select>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <label for="level">Level:</label>
    </div>
    <div class="col-9">
        <select id="level" name="level" onchange="App.populateConstituencies(this.value)">
            <option value="0">Federal</option>
            <option value="1">State</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <label for="constiteuncy">Constituency:</label>
    </div>
    <div class="col-9">
        <select id="constiteuncy" name="constiteuncy"></select>
    </div>
</div>

<button class="btn btn-primary btn-block" id="register" onclick="App.registerCandidate()">Register</button>

<span id="status"></span>

@endsection
