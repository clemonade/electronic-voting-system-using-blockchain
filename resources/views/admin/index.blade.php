<?php use App\Common; ?>

@extends('layouts.app')
@section('script')
<script type="text/javascript">
    let federals = <?php echo $federals ?>;
    let states = <?php echo $states ?>;
</script>
<script src="{{asset('js/constituencies.js')}}"></script>
@stop
@section('content')

<h1>Constituencies</h1>

<h2>Federal</h2>
@if (count($federals) > 0)
@foreach (Common::$states as $x => $state)
<div class="card">
    <div class="card-header">
        <h3 href="#f{{ $x }}" data-toggle="collapse">{{ $state }}</h3>
    </div>
    <div id="f<?php echo $x ?>" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($federals as $i => $federal)
            @if ($federal->state == $x)
            <tr>
                <td>
                    {{ $i+1 . '.' }}
                </td>
                <td class="text-center">
                    {{ link_to_route(
                    'constituency.show',
                    $title = $federal->code,
                    $parameters = [ '$federal_code' => $federal->code ]
                    ) }}
                </td>
                <td>
                    {{ $federal->name }}
                </td>
                <td align="center">
                    <button id="<?php echo $federal->code ?>" class="btn btn-primary">Initialise</button>
                    @if ($federal->nostate)
                    <a href="<?php echo route('admin.verify', [$federal->code]) ?>">
                        <button id="<?php echo $federal->code ?>V" class="btn btn-primary" disabled>Verify</button>
                    </a>
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@else
<div>
    No records found.
</div>
@endif

<h2>State</h2>
@if (count($states) > 0)
@foreach (Common::$states as $x => $state)
<div class="card">
    <div class="card-header">
        <h3 href="#s<?php echo $x ?>" data-toggle="collapse">{{ $state }}</h3>
    </div>
    <div id="s<?php echo $x ?>" class="collapse">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No.</th>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($states as $i => $stateconstituency)
            @if ($stateconstituency->state == $x)
            <tr>
                <td>
                    {{ $i+1 . '.' }}
                </td>
                <td class="text-center">
                    {{ link_to_route(
                    'constituency.show',
                    $title = $stateconstituency->code,
                    $parameters = [ 'id' => $stateconstituency->code ]
                    ) }}
                </td>
                <td>
                    {{ $stateconstituency->name }}
                </td>
                <td align="center">
                    <button id="<?php echo $stateconstituency->code ?>" class="btn btn-primary">Initialise</button>
                    <a href="<?php echo route('admin.verify', [$stateconstituency->code]) ?>">
                        <button id="<?php echo $stateconstituency->code ?>V" class="btn btn-primary" disabled>
                            Verify
                        </button>
                    </a>
                </td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@else
<div>
    No records found.
</div>
@endif

@endsection
