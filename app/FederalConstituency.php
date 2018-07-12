<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FederalConstituency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'state',
    ];
}
