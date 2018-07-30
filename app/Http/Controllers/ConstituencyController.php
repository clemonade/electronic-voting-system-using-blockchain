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
        //
    }

    public function show($code)
    {
        return view('voter.constituency', ['code' => $code]);
    }

    public function login()
    {
        //
    }
}
