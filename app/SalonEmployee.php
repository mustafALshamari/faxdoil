<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="SalonEmployee",
 *  @SWG\Property(
 *      property="salon_id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="stylist_id",
 *      type="integer"
 *  ),
 * )
 */
class SalonEmployee extends Model
{
    protected $fillable =
    [
       'salon_id',
       'stylist_id',
   ];
}