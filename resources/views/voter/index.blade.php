@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
@stop
@section('content')

<div>
    <h1>Voter Dashboard</h1>
    <h2>Federal Constituencies</h2>
    @if (count($federals) > 0)
    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Name</th>
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
                    $parameters = [ 'id' => $federal->code ]
                    ) }}
                </div>
            </td>
            <td>
                <div>{{ $federal->name }}</div>
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
