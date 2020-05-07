<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="Services",
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="price",
 *      type="string"
 *  ),
 * )
 */
class Services extends Model
{
    protected $fillable = 
    [
        'name',
        'price',
        'salon_id',
    ];

    public function salon()
    {
        return $this->belongsTo('App\Salon');
    }
}
