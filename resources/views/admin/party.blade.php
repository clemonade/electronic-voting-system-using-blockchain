@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@section('content')

<h1>Parties</h1>

<table id="parties" class="table">
    <thead>
    <tr>
        <th>No.</th>
        <th>Name</th>
        <th class=" text-center">Abbreviation</th>
        <th class="text-center">Logo</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<a href="<?php echo route('party.create') ?>">
    <button class="btn btn-primary">Register</button>
</a>

@endsection
