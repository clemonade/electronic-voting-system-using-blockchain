<?php use App\Common; ?>
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
<!--    @if ($errors->any())-->
<!--    <div class="alert alert-danger">-->
<!--        <ul>-->
<!--            @foreach ($errors->all() as $error)-->
<!--            <li>{{ $error }}</li>-->
<!--            @endforeach-->
<!--        </ul>-->
<!--    </div>-->
<!--    @endif-->

{!! Form::model($voter, [
'route' => ['registervoter.store']
]) !!}

<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control" maxlength="100">
</div>

<div class="form-group">
    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" class="form-control" maxlength="12">
</div>

<div class="form-group">
    <label for="gender">Gender:</label><br>
    @foreach(Common::$genders as $key => $val)
    <div class="form-check-inline">
        <label class="form-check-label">
            <input type="radio" name="gender" value="<?php echo $key ?>" class="form-check-input">
            {{$val}}
        </label>
    </div>
    @endforeach
</div>

<div class="form-group">
    <label for="state">State:</label>
    <select id="state" name="state" onchange="App.populateFederalDropdown(this.value)" class="form-control">
        <option disabled selected hidden>- Select State -</option>
        @foreach(Common::$states as $key => $val)
        <option value="<?php echo $key ?>">{{$val}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="federalconstituency">Federal Constituency:</label>
    <select id="federalconstituency" name="federalconstituency" onchange="App.populateStateDropdown(this.value)"
            class="form-control">
        <option disabled selected hidden>- Select Federal Constituency -</option>
    </select>
</div>

<div class="stateconstituency form-group">
    <label for="stateconstituency">State Constituency:</label>
    <select id="stateconstituency" name="stateconstituency" onchange="App.populateStateDropdown(this.value)"
            class="form-control">
        <option disabled selected hidden>- Select State Constituency -</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Register</button>
{!! Form::close() !!}

<span id="status"></span>

@endsection
