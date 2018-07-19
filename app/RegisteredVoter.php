<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredVoter extends Model
{
    public $table = "registeredvoters";

    protected $fillable = [
        'name',
        'nric',
        'gender',
//        'locality',
//        'votingDistrict',
        'federalconstituency',
        'stateconstituency',
        'state',
        'valid',
    ];
}
