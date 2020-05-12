<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="SalonWorkTime",
 *  @SWG\Property(
 *      property="salon_id",
 *      type="id"
 *  ),
 *  @SWG\Property(
 *      property="monday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="tuesday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="wednesday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="thursday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="friday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="saturday",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="sunday",
 *      type="string"
 *  ),
 * )
 */
class SalonWorkTime extends Model
{
    protected $fillable =
     [
        'salon_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    ];

    public function salon()
    {
        return $this->belongsTo('App\Salon');
    }
}
