<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateConstituency extends Model
{
    public $table = "stateconstituencies";

    protected $fillable = [
        'code',
        'name',
        'state',
    ];
}
