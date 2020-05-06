<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class StylePost extends Model
{
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
      return $this->belongsTo('App\Stylist');
    }

    public function PostLike()
    {
        return $this->hasMany('App\PostLike');
    }
    
    public function comment()
    {
        return $this->hasMany('App\PostComment');
    }
}