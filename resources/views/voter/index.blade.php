<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>Voter | Dashboard</title>
@stop
@section('nav')
@include('layouts.voter')
@stop
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/constituencies.js')}}"></script>
@stop
@section('content')

<h1>Voter Dashboard</h1>
<div class="form-group">
    <label for="admin">Administrator Address:</label>
    <input type="text" id="admin" class="form-control" readonly>
</div>
<div class="form-group">
    <label for="address">Contract Address:</label>
    <input type="text" id="address" class="form-control" readonly>
</div>

<h2>Constituencies</h2>
<h3>Federal</h3>
@if (count($federals) > 0)
@foreach (Common::$states as $x => $state)
<div class="card">
    <div class="card-header">
        <a href="#F{{ $x }}" data-toggle="collapse">{{ $state }}</a>
        <table class="table-sm">
            <tr id="F{{ $x }}T">
            </tr>
        </table>
    </div>
    <div id="F{{ $x }}" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Party</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($federals as $i => $federal)
            @if ($federal->state == $x)
            <tr>
                <td>
                    {{ $i+1 . '.' }}
                </td>
                <td class="text-center">
                    {{ link_to_route(
                    'constituency.show',
                    $title = $federal->code,
                    $parameters = [ '$federal_code' => $federal->code ]
                    ) }}
                </td>
                <td>
                    {{ $federal->name }}
                </td>
                <td id="{{ $federal->code }}P" class="text-center"></td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@else
<div>
    No records found.
</div>
@endif

<h3>State</h3>
@if (count($states) > 0)
@foreach (Common::$states as $x => $state)
<div class="card">
    <div class="card-header">
        <a href="#S{{ $x }}" data-toggle="collapse">{{ $state }}</a>
        <table class="table-sm">
            <tr id="S{{ $x }}T">
            </tr>
        </table>
    </div>
    <div id="S{{ $x }}" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Party</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($states as $i => $stateconstituency)
            @if ($stateconstituency->state == $x)
            <tr>
                <td>
                    {{ $i+1 . '.' }}
                </td>
                <td class="text-center">
                    {{ link_to_route(
                    'constituency.show',
                    $title = $stateconstituency->code,
                    $parameters = [ 'id' => $stateconstituency->code ]
                    ) }}
                </td>
                <td>
                    {{ $stateconstituency->name }}
                </td>
                <td id="{{ $stateconstituency->code }}P" class="text-center"></td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@else
<div>
    No records found.
</div>
@endif

@stop
