@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/candidate.js')}}"></script>
@stop
@section('content')

<h1>Register Candidate</h1>
<div class="form">
    <label for="name">Candidate Name:</label>
    <br><input type="text" id="name" name="name"/>
    <br><label for="party">Party:</label>
    <br><select id="party" name="party"> </select>
    <br><label for="level">Level:</label>
    <br>
    <select id="level" name="level" onchange="App.populateConstituencies(this.value)">
        <option value="0">Federal</option>
        <option value="1">State</option>
    </select>
    <br><label for="constiteuncy">Constituency:</label>
    <br><select id="constiteuncy" name="constiteuncy"></select>
    <br>
    <button id="register" onclick="App.registerCandidate()">Register</button>
</div>

<br>
<span id="status"></span>
<span id="list"></span>

@endsection
