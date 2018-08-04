<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $name = $request->input('name');
        $abbreviation = $request->input('abbreviation');
        $file = $request->file('image');

        $file->storeAs('public/parties', $name . $abbreviation . '.jpg');

        return redirect()->route('party.index');
    }
}
