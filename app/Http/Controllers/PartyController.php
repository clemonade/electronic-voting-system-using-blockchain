<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PartyController extends Controller
{
    public function index()
    {
        return view('admin.party');
    }

    public function create()
    {
        return view('admin.registerparty');
    }

    public function store(StoreParty $request)
    {
        $name = $request->input('name');
        $abbreviation = $request->input('abbreviation');
        $file = $request->file('image');

        $file->storeAs('public/parties', $name . $abbreviation . '.jpg');

        return Redirect::back()->with('status', 'text-success Party ' . $abbreviation . ' registered successfully.');
    }
}
