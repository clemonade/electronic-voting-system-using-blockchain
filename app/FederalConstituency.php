<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FederalConstituency extends Model
{
    public $table = "federalconstituencies";

    protected $fillable = [
        'code',
        'name',
        'state',
        'nostate',
    ];
}
