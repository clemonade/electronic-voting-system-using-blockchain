<?php

namespace App\Http\Controllers;

use App\FederalConstituency;
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

        if (isset($state_code)) {
            $constituency = StateConstituency::where([
                ['code', '=', $federal_code . $separator . $state_code]
            ])->first();
        } else {
            $constituency = FederalConstituency::where([
                ['code', '=', $federal_code]
            ])->first();
        }

        $code = $constituency['code'];
        $state = $constituency['state'];
//        $separator = '_';
//
//        if (strpos($code, $separator) !== false) {
//            $constituency = StateConstituency::where([
//                ['code', '=', $code]
//            ])->first();
//
//            $state = $constituency['state'];
//        } else {
//            $constituency = FederalConstituency::where([
//                ['code', '=', $code]
//            ])->first();
//
//            $state = $constituency['state'];
//        }

        return view('voter.constituency', [
            'code' => $code,
            'state' => $state,
        ]);
    }

    public function login()
    {
        return redirect()->route('admin.index');
    }
}
