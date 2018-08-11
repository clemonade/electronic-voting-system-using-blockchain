<?php use App\Common; ?>
@section('title')
<title>Constituency | {{ $code }}</title>
@stop
@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let code = "<?php echo $code ?>";
    let count = "<?php echo $count ?>";
    let state = "<?php echo $state ?>";
    let types = <?php echo json_encode(Common::$types)?>;
</script>
<script src="{{ asset('js/constituency.js') }}"></script>
@stop
@include('layouts.voter')
@section('content')

<h1 id="code">{{ $code }}</h1>
<h1>
    <small id="name"></small>
</h1>

<table class="table">
    <tbody>
    <tr>
        <td>State</td>
        <td id="state">{{ Common::$states[$state] }}</td>
    </tr>
    <tr>
        <td>Level</td>
        <td id="level"></td>
    </tr>
    <tr>
        <td>
            @if(isset($relation))
            State Constituencies
            @else
            Federal Constituency
            @endif
        </td>
        <td>
            @if(isset($relation))
            @if(count($relation) == 0)
            FEDERAL TERRITORY
            @else
            <ul class="list-unstyled">

                @foreach($relation as $x => $stateconstituency)
                <li>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $stateconstituency,
                    $parameters = [ '$code' => $stateconstituency ]
                    ) }}
                </li>
                @endforeach
            </ul>
            @endif
            @else
            {{ link_to_route(
            'constituency.show',
            $title = explode('/', $code)[0],
            $parameters = [ '$code' => explode('/', $code)[0] ]
            ) }}
            @endif
        </td>
    </tr>
    <tr>
        <td>Total Voters</td>
        <td id="total">{{ $count }}</td>
    </tr>
    <tr>
        <td>Tally</td>
        <td>
            <table id="candidates" class="table table-hover">
                <thead>
                <tr>
                    <td class="text-center">Party</td>
                    <td>Name</td>
                    <td class="text-center">Votes</td>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

            <label for="turnout">Turnout:</label>
            <span id="turnout"></span><br>
            <label for="spoilt">Spoilt:</label>
            <span id="spoilt"></span>
        </td>
    </tr>
    <tr>
        <td>Status</td>
        <td id="init"></td>
    </tr>
    </tbody>
</table>

@endsection
