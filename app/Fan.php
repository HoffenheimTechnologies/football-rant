<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    //
    protected $fillable = [
        'id', 'user', 'club',
    ];
}
