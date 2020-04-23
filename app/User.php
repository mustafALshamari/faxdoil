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
 *      property="photo_name",
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
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'age', 'phone_number', 'location', 'introduction', 'photo_name'
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

    public function stylist()
    {
        return $this->hasOne('App\Stylist');
    }
}
