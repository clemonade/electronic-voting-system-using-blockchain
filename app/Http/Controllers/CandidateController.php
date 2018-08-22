<?php

namespace App\Http\Controllers;

use App\FederalConstituency;
use App\StateConstituency;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function create()
    {
        $federals = FederalConstituency::orderBy('code', 'asc')->get();
        $states = StateConstituency::orderBy('code', 'asc')->get();

        return view('admin.registercandidate', [
            'federals' => $federals,
            'states' => $states,
        ]);
    }

    public function add()
    {
        $federals = FederalConstituency::orderBy('code', 'asc')->get();
        $states = StateConstituency::orderBy('code', 'asc')->get();

        return view('admin.add', [
            'federals' => $federals,
            'states' => $states,
        ]);
    }
}
