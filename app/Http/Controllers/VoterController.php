<?php

namespace App\Http\Controllers;

use App\FederalConstituency;
use App\Http\Requests\StoreVoter;
use App\Http\Requests\VerifyVoter;
use App\RegisteredVoter;
use App\StateConstituency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VoterController extends Controller
{
    public function index()
    {
        return view('voter.voter');
    }

    public function create()
    {
        $voter = new RegisteredVoter();
        $federals = FederalConstituency::orderBy('code', 'asc')->get();
        $states = StateConstituency::orderBy('code', 'asc')->get();

        return view('admin.registervoter', [
            'voter' => $voter,
            'federals' => $federals,
            'states' => $states,
        ]);
    }

    public function store(StoreVoter $request)
    {
        $voter = new RegisteredVoter();
        $voter->fill($request->all());
        $voter['name'] = strtoupper($request->input('name'));
        $voter->save();

        return Redirect::back()->with('status', 'text-success Voter registered successfully.');
    }

    public function verify($federal_code, $state_code = null)
    {
        $column = 'code';
        $delimiter = '/';

        $federal = FederalConstituency::where($column, '=', $federal_code)->first();
        if (isset($state_code)) {
            $state = StateConstituency::where($column, '=', $federal_code . $delimiter . $state_code)->first();
        } else {
            $state = $state_code;
        }

        return view('admin.verify', [
            'federal' => $federal,
            'state' => $state,
        ]);
    }

    public function prevote(VerifyVoter $request)
    {
        $name = strtoupper($request->input('name'));
        $nric = $request->input('nric');
        $current_federal = $request->input('federal');
        $current_state = $request->input('state');

        $registered_voter = RegisteredVoter::where([
            ['name', '=', $name],
            ['nric', '=', $nric]
        ])->get();

        if (sizeof($registered_voter) == 1) {
            $voter_federal = FederalConstituency::find($registered_voter[0]['federalconstituency']);

            if (isset($current_state)) {
                $voter_state = StateConstituency::find($registered_voter[0]['stateconstituency']);
            } else {
                $voter_state = null;
            }

            if ($registered_voter[0]['voted']) {
                return Redirect::back()->with('status', 'text-danger Already voted.');
            }

            if ($voter_federal == $current_federal && $voter_state == $current_state) {
                return view('admin.prevote', [
                    'federal' => $voter_federal,
                    'state' => $voter_state,
                    'voter' => $registered_voter[0],
                ]);
            }

            return Redirect::back()->with('status', 'text-danger Wrong constituency.');
        }

        return Redirect::back()->with('status', 'text-danger Invalid voter credentials.');
    }

    public function vote(Request $request)
    {
        $federal = $request->input('federal');
        $state = $request->input('state');

        $voter = json_decode($request->input('voter'), true);
        $id = $voter['id'];
        $name = strtoupper($voter['name']);
        $nric = $voter['nric'];
        $nonce = $request->input('nonce');

        $hash = hash('sha256', $name . $nric . hash('sha256', $nonce));

        return view('admin.vote', [
            'id' => $id,
            'hash' => $hash,
            'federal' => $federal,
            'state' => $state,
        ]);
    }

    public function postvote(Request $request)
    {
        $delimiter = '/';
        $id = $request->input('id');
        $state = $request->input('state');

        DB::table('registeredvoters')
            ->where('id', $id)
            ->update(['voted' => true]);

        $federal = json_decode($request->input('federal'), true)['code'];

        if (isset($state)) {
            $state = explode($delimiter, json_decode($request->input('state'), true)['code'])[1];
        }

        return redirect()->route('admin.verify', [
            'federal' => $federal,
            'state' => $state,
        ]);
    }
}
