<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalonWorkTime extends Model
{
    protected $fillable =
     [
        'salon_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'monday',
        'saturday',
        'sunday'
    ];
}
