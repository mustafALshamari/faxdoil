<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="Stylist",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="email",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="nickanme",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="age",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="phone_number",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="location",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="introduction",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="profile_photo",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="background_photo",
 *      type="string"
 *  )
 * )
 */
class Stylist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stylistFollower()
    {
        return $this->hasMany('App\StylistFollower');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stylistReview()
    {
        return $this->hasMany('App\StylistReview');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salon()
    {
        return $this->hasOne('App\Salon');
    }

    public function stylePost()
    {
        return $this->hasMany('App\StylePost')->latest();
    }
}
