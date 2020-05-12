<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'likes_quantity',
        'views_quantity',
    ];

    public function categories(){
        return $this->belongsToMany('App\Category');
    }
}

