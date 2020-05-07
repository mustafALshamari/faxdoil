<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = 
    [  
        'name',
        'email',
        'token',
        'accepted'
    ];
}
