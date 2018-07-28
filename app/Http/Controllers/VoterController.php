<?php

namespace App\Http\Controllers;

use App\FederalConstituency;
use App\RegisteredVoter;
use App\StateConstituency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VoterController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $voter = new RegisteredVoter();

        return view('admin.registervoter', ['voter' => $voter]);
    }

    public function store(Request $request)
    {
        $voter = new RegisteredVoter();
        $voter->fill($request->all());
        $voter->save();

        return redirect()->route('registervoter.create');
    }

    public function verify($federalCode, $stateCode)
    {
        $column = 'code';
        $federal = FederalConstituency::where($column, '=', $federalCode)->first();
        $state = StateConstituency::where($column, '=', $federalCode . '_' . $stateCode)->first();

        return view('admin.verify', [
            'federal' => $federal,
            'state' => $state,
        ]);
    }

    public function check(Request $request)
    {
        $name = $request->input('name');
        $nric = $request->input('nric');
        $currentFederal = $request->input('federal');
        $currentState = $request->input('state');

        $registeredVoter = RegisteredVoter::where([
            ['name', '=', $name],
            ['nric', '=', $nric]
        ])->get();

        if (sizeof($registeredVoter) == 1) {
            $voterFederalConstituency = FederalConstituency::find($registeredVoter[0]['federalconstituency']);
            $voterStateConstituency = StateConstituency::find($registeredVoter[0]['stateconstituency']);

            if ($voterFederalConstituency == $currentFederal && $voterStateConstituency == $currentState) {
                return view('admin.prevote', [
                    'federal' => $voterFederalConstituency,
                    'state' => $voterStateConstituency,
                    'voter' => $registeredVoter[0],
                ]);
            }

            return Redirect::back()->with('status', 'Invalid constituency.');
        }

        return Redirect::back()->with('status', 'Invalid credentials.');
    }

    public function prevote()
    {
        return view('admin.prevote');
    }

    public function vote(Request $request)
    {
        $federal = $request->input('federal');
        $state = $request->input('state');

        $voter = json_decode($request->input('voter'), true);
        $name = strtoupper($voter['name']);
        $nric = $voter['nric'];
        $nonce = $request->input('nonce');

        $hash = hash('sha256', $name . $nric . hash('sha256', $nonce));

        return view('admin.vote', [
            'federal' => $federal,
            'state' => $state,
            'hash' => $hash,
        ]);
    }
}
