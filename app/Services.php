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
 *  *  @SWG\Property(
 *      property="salon_id",
 *      type="integer"
 *  ),
 * )
 */
class Services extends Model
{
    
    protected $fillable = 
    [
        'name',
        'salon_id',
    ];

    public function salon()
    {
        return $this->belongsTo('App\Salon');
    }
}
