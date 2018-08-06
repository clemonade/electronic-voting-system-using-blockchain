<?php use App\Common; ?>

@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let code = "<?php echo $code ?>";
    let state = "<?php echo $state ?>";
    let states = <?php echo json_encode(Common::$states)?>;
    let types = <?php echo json_encode(Common::$types)?>;
</script>
<script src="{{ asset('js/constituency.js') }}"></script>
@stop
@section('content')

<h1 id="code"></h1>
<h2 id="name"></h2>

<table class="table">
    <tbody>
    <tr>
        <td>State</td>
        <td id="state"></td>
    </tr>
    <tr>
        <td>Level</td>
        <td id="level"></td>
    </tr>
    <tr>
        <td>Total Voters</td>
        <td id="total"></td>
    </tr>
    <!--    TODO Additional contextual information (turnout, spoilt votes, race?)-->
    <tr>
        <td>Tally</td>
        <td>
            <table id="candidates" class="table-hover">
                <thead>
                <tr>
                    <td class="text-center">Party</td>
                    <td>Name</td>
                    <td class="text-center">Votes</td>
                </tr>
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

@endsection
