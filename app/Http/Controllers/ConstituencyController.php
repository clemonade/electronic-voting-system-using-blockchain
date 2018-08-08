<?php

namespace App\Http\Controllers;

use App\FederalConstituency;
use App\RegisteredVoter;
use App\StateConstituency;
use Illuminate\Http\Request;

class ConstituencyController extends Controller
{
    public function index()
    {
        $federals = FederalConstituency::orderBy('code', 'asc')->get();
        $states = StateConstituency::orderBy('code', 'asc')->get();

        return view('admin.index', [
            'federals' => $federals,
            'states' => $states,
        ]);
    }

    public function voter()
    {
        $federals = FederalConstituency::orderBy('code', 'asc')->get();
        $states = StateConstituency::orderBy('code', 'asc')->get();

        return view('voter.index', [
            'federals' => $federals,
            'states' => $states,
        ]);
    }

    public function show($federal_code, $state_code = null)
    {
        $separator = '/';
        $relation = null;
        $count = null;

        if (isset($state_code)) {
            $constituency = StateConstituency::where([
                ['code', '=', $federal_code . $separator . $state_code]
            ])->first();

            $count = RegisteredVoter::where([
                ['stateconstituency', '=', $constituency->id]
            ])->count();
        } else {
            $constituency = FederalConstituency::where([
                ['code', '=', $federal_code]
            ])->first();

            $relation = StateConstituency::where([
                ['code', 'like', $federal_code . '%']
            ])->pluck('code');

            $count = RegisteredVoter::where([
                ['federalconstituency', '=', $constituency->id]
            ])->count();
        }

        $code = $constituency['code'];
        $state = $constituency['state'];

        return view('voter.constituency', [
            'code' => $code,
            'state' => $state,
            'relation' => $relation,
            'count' => $count,
        ]);
    }

    public function login()
    {
        return redirect()->route('admin.index');
    }
}
