<?php

use App\Common;

?>

@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let code = "<?php echo $code ?>";
    let types = <?php echo json_encode(Common::$types)?>;
</script>
<script src="{{ asset('js/constituency.js') }}"></script>
@stop
@section('content')

<div class="panel-body">
    <table class="table table-striped task-table">
        <thead>
        <tr>
            <th>Attribute</th>
            <th>Value</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>Code</td>
            <td id="code"></td>
        </tr>
        <tr>
            <td>Name</td>
            <td id="name"></td>
        </tr>
        <tr>
            <td>Type</td>
            <td id="type"></td>
        </tr>
        <tr>
            <td>Total Votes</td>
            <td id="total"></td>
        </tr>
        <tr>
            <td>Candidates</td>
            <td>
                <table id="candidates">
                    <thead>
                    <td>Name</td>
                    <td>Party</td>
                    <td>Votes</td>
                    </thead>
                    <tbody></tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td id="init"></td>
        </tr>
        </tbody>
    </table>
    <span id="status"></span>
</div>

@endsection
