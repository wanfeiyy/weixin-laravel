<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected  $fillable = ['casts','status'];
    protected  $casts = [
        'casts' => 'object',
        'status' => 'boolean',
    ];
}
