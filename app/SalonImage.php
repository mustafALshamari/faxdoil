<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalonImage extends Model
{
    protected $fillable = [
        'image',
        'salon_id'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Salon');
    }
}
