<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateConstituency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'state',
    ];
}
