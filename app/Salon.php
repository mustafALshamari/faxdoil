<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *  definition="Salon",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="address",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="images",
 *      type="text"
 *  ),
 *  @SWG\Property(
 *      property="latitude",
 *      type="double"
 *  ),
 *  @SWG\Property(
 *      property="longitude",
 *      type="double"
 *  ),
 * )
 */
class Salon extends Model
{
    protected $fillable =
    [
        'name' ,
        'address',
        'images',
        'latitude',
        'longitude'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stylist()
    {
        return $this->belongsTo('App\Stylist');
    }

    public function service()
    {
        return $this->hasMany('App\Services');
    }

    public function workTime()
    {
        return $this->hasOne('App\SalonWorkTime');
    }

    public function beautyPro()
    {
        return $this->hasMany('App\SalonEmployee');
    }

    public function menu()
    {
        return $this->hasMany('App\SalonMenu');
    }
}