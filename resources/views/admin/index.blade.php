<?php use App\Common; ?>
@extends('layouts.app')
@section('title')
<title>Admin | Dashboard</title>
@stop
@section('nav')
@include('layouts.admin')
@stop
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/constituencies.js')}}"></script>
@stop
@section('content')

<h1>Admin Dashboard</h1>
<div class="form-group">
    <label for="current">Current Address:</label>
    <input type="text" id="current" class="form-control" readonly>
</div>
<div class="form-group">
    <label for="admin">Administrator Address:</label>
    <input type="text" id="admin" class="form-control" readonly>
</div>
<div class="form-group">
    <label for="balance">Administrator Balance:</label>
    <input type="text" id="balance" class="form-control" readonly>
</div>
<div class="form-group">
    <label for="address">Contract Address:</label>
    <input type="text" id="address" class="form-control" readonly>
</div>

<!--TODO Number of constituencies won for each party by state and level-->
<h2>Constituencies</h2>
<h3>Federal</h3>
@if (count($federals) > 0)
@foreach (Common::$states as $x => $state)
<div class="card">
    <div class="card-header">
        <a href="#f{{ $x }}" data-toggle="collapse">{{ $state }}</a>
    </div>
    <div id="f{{ $x }}" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
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
                    $parameters = [ '$code' => $federal->code ]
                    ) }}
                </td>
                <td>
                    {{ $federal->name }}
                </td>
                <td align="center">
                    <button id="{{ $federal->code }}" class="btn btn-primary btn-block">Initialise</button>
                    @if ($federal->nostate)
                    <a href="{{ route('admin.verify', [$federal->code]) }}">
                        <button id="{{ $federal->code }}V" class="btn btn-outline-primary btn-block" disabled>Verify</button>
                    </a>
                    @endif
                </td>
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
        <a href="#s{{ $x }}" data-toggle="collapse">{{ $state }}</a>
    </div>
    <div id="s{{ $x }}" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
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
                    $parameters = [ 'code' => $stateconstituency->code ]
                    ) }}
                </td>
                <td>
                    {{ $stateconstituency->name }}
                </td>
                <td align="center">
                    <button id="{{ $stateconstituency->code }}" class="btn btn-primary btn-block">Initialise</button>
                    <a href="{{ route('admin.verify', [$stateconstituency->code]) }}">
                        <button id="{{ $stateconstituency->code }}V" class="btn btn-primary btn-block" disabled>
                            Verify
                        </button>
                    </a>
                </td>
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
