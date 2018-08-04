@extends('layouts.app')
@section('script')
<script src="{{asset('js/party.js')}}"></script>
@stop
@section('content')

<h1>Register Party</h1>
<div class="panel-body">
    {!! Form::open([
    'route' => ['party.store'],
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data',
    ]) !!}

    <!-- Logo -->
    <div class="form-group row">
        {!! Form::label('logo', 'Logo:', [
        'class' => 'control-label col-sm-3',
        ]) !!}
        <div class="col-sm-9">
            {!! Form::file('image', [
            'id' => 'logo-file',
            'class' => 'form-control',
            ]) !!}
        </div>
    </div>

    <label for="name">Party Name:</label>
    <br><input type="text" id="name" name="name"/>
    <br><label for="abbreviation">Party Abbreviation:</label>
    <br><input type="text" id="abbreviation" name="abbreviation"/>

    <!-- Submit Button -->
    <div class="form-group row">
        <div class="col-sm-offset-3 col-sm-6">
            {!! Form::button('Upload', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'onclick' => 'App.registerParty()',
            ]) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<br>
<span id="status"></span>

@endsection
