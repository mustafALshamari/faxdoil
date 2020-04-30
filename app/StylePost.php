<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylePost extends Model
{

/**
 * @SWG\Definition(
 *  definition="StylePost",
 *  @SWG\Property(
 *      property="stylist_id",
 *      type="integer"
 *  ),

 *  @SWG\Property(
 *      property="description",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="media",
 *      type="text"
 *  ),
 *  @SWG\Property(
 *      property="brand_name",
 *      type="string"
 *  ),
 *   @SWG\Property(
 *      property="style_name",
 *      type="string"
 *  ),
 *   @SWG\Property(
 *      property="tags",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="color",
 *      type="string"
 *  ),
 * )
 */
    protected $fillable = 
    [
         'description',
         'stylist_id',
         'media',
         'tags',
         'brand_name',
         'style_name',
         'color'

    ];

    public function stylist()
    {
        $this->belongsTo('App\Stylist');
    }
}
