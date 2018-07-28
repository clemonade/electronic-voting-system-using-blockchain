@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/constituencies.js')}}"></script>
@stop
@section('content')

<div class="panel-body">
    <h1>Admin Dashboard</h1>
    <h2>Federal Constituencies</h2>
    @if (count($federals) > 0)
    <table class="table table-striped task-table">
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
        <tr>
            <td class="table-text">
                <div>{{ $i+1 }}</div>
            </td>
            <td class="table-text">
                <div>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $federal->code,
                    $parameters = [ 'id' => $federal->code ]
                    ) }}
                </div>
            </td>
            <td class="table-text">
                <div>{{ $federal->name }}</div>
            </td>
            <td class="table-text">
                <div>
                    <button id="<?php echo $federal->code ?>">Initialise</button>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <div>
        No records found
    </div>
    @endif

    <h2>State Constituencies</h2>
    @if (count($states) > 0)
    <table class="table table-striped task-table">
        <thead>
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($states as $i => $state)
        <tr>
            <td class="table-text">
                <div>{{ $i+1 }}</div>
            </td>
            <td class="table-text">
                <div>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $state->code,
                    $parameters = [ 'id' => $state->code ]
                    ) }}
                </div>
            </td>
            <td class="table-text">
                <div>{{ $state->name }}</div>
            </td>
            <td class="table-text">
                <div>
                    <button id="<?php echo $state->code ?>">Initialise</button>
                    <button id="<?php echo $state->code ?>V" disabled='disabled'>Verify</button>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <div>
        No records found
    </div>
    @endif
    <span id="status"></span>
</div>

@endsection
