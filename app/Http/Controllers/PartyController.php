<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartyController extends Controller
{
    public function create()
    {
        return view('admin.registerparty');
    }
}
