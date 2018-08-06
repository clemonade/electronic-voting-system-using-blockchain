<?php use App\Common; ?>
@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/candidate.js')}}"></script>
@stop
@section('content')

<!--TODO Candidate index page-->
<h1>Register Candidate</h1>

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
</div>

<!--TODO Display party logo in dropdown-->
<div class="form-group">
    <label for="party">Party:</label>
    <select id="party" name="party" class="form-control">
        <option disabled selected hidden>- Select Party -</option>
    </select>
</div>

<div class="form-group">
    <label for="level">Level:</label>
    <select id="level" name="level" onchange="App.populateConstituencies(this.value)" class="form-control">
        <option disabled selected hidden>- Select Level -</option>
        @foreach(Common::$types as $key => $val)
        <option value="<?php echo $key ?>">{{$val}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="constiteuncy">Constituency:</label>
    <select id="constiteuncy" name="constiteuncy" class="form-control">
        <option disabled selected hidden>- Select Constituency -</option>
    </select>
</div>

<button class="btn btn-primary" id="register" onclick="App.registerCandidate()">Register</button>

<span id="status"></span>

@endsection
