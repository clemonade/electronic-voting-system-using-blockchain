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
        <tr>
            <td>
                <div>{{ $i+1 }}</div>
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
        @foreach ($states as $i => $state)
        <tr>
            <td>
                <div>{{ $i+1 }}</div>
            </td>
            <td>
                <div>
                    {{ link_to_route(
                    'constituency.show',
                    $title = $state->code,
                    $parameters = [ 'id' => $state->code ]
                    ) }}
                </div>
            </td>
            <td>
                <div>{{ $state->name }}</div>
            </td>
            <td>
                <div>
                    <button id="<?php echo $state->code ?>">Initialise</button>
                    <a href="<?php echo route('admin.verify', [$state->code]) ?>">
                        <button id="<?php echo $state->code ?>V" disabled='disabled'>Verify</button>
                    </a>
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
