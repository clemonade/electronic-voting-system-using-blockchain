<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredVoter extends Model
{
    protected $fillable = [
        'name',
        'nric',
        'gender',
        'locality',
        'votingDistrict',
        'federalConstituency',
        'stateConstituency',
        'state',
        'valid',
    ];
}
