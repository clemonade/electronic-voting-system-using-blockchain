@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@section('content')

<h1>Parties</h1>

<table id="parties">
    <thead>
    <tr>
        <th>No.</th>
        <th>Name</th>
        <th>Abbreviation</th>
        <th>Logo</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<a href="<?php echo route('party.create') ?>">
    <button>Register</button>
</a>

@endsection
