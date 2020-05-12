<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="SalonMenu",
 *  @SWG\Property(
 *      property="item_name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="item_price",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="salon_id",
 *      type="integer"
 *  ),
 * )
 */
class SalonMenu extends Model
{
    protected $fillable = 
    [
        'item_name',
        'item_price',
        'salon_id',
    ];

    public function salon()
    {
        return $this->belongsTo('App\Salon');
    }
}