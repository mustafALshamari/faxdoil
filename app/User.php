<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @SWG\Definition(
 *  definition="User",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="username",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="email",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="nickname",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="age",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="phone_number",
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
 *  ),
 *  @SWG\Property(
 *      property="created_at",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="whats_app",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="user_type",
 *      type="string"
 *  )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'username',
        'email',
        'password',
        'fullname',
        'provider',
        'provider_id',
        'age',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'introduction',
        'profile_photo',
        'background_photo',
        'user_type',
        'created_at',
        'whats_app',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stylist()
    {
        return $this->hasOne('App\Stylist');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stylistFollowers()
    {
        return $this->hasMany('App\StylistFollowers');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stylistReview()
    {
        return $this->hasMany('App\StylistReview');
    }

    function PostLike()
    {
        return $this->hasMany('App\PostLike');
    }

    function comment()
    {
        return $this->hasMany('App\PostComment');
    }
}
