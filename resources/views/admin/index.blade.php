<?php

use App\Common;

?>

@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/constituencies.js')}}"></script>
@stop
@section('content')

<div>
    <h1>Admin Dashboard</h1>
    <h2>Federal Constituencies</h2>
    @if (count($federals) > 0)
    @foreach (Common::$states as $x => $state)
    <h3>{{ $state }}</h3>
    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($federals as $i => $federal)
        @if ($federal->state == $x)
        <tr>
            <td>
                <div>{{ $i+1 . '.' }}</div>
            </td>
            <td>
                <div>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $federal->code,
                    $parameters = [ '$federal_code' => $federal->code ]
                    ) }}
                </div>
            </td>
            <td>
                <div>{{ $federal->name }}</div>
            </td>
            <td>
                <div>
                    <button id="<?php echo $federal->code ?>">Initialise</button>
                    @if ($federal->nostate)
                    <a href="<?php echo route('admin.verify', [$federal->code]) ?>">
                        <button id="<?php echo $federal->code ?>V" disabled='disabled'>Verify</button>
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
    @endforeach
    @else
    <div>
        No records found
    </div>
    @endif

    <h2>State Constituencies</h2>
    @if (count($states) > 0)
    @foreach (Common::$states as $x => $state)
    <h3>{{ $state }}</h3>
    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($states as $i => $stateconstituency)
        @if ($stateconstituency->state == $x)
        <tr>
            <td>
                <div>{{ $i+1 . '.' }}</div>
            </td>
            <td>
                <div>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $stateconstituency->code,
                    $parameters = [ 'id' => $stateconstituency->code ]
                    ) }}
                </div>
            </td>
            <td>
                <div>{{ $stateconstituency->name }}</div>
            </td>
            <td>
                <div>
                    <button id="<?php echo $stateconstituency->code ?>">Initialise</button>
                    <a href="<?php echo route('admin.verify', [$stateconstituency->code]) ?>">
                        <button id="<?php echo $stateconstituency->code ?>V" disabled='disabled'>Verify</button>
                    </a>
                </div>
            </td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
    @endforeach
    @else
    <div>
        No records found
    </div>
    @endif
    <span id="status"></span>
</div>

@endsection
