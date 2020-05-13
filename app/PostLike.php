<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $fillable = ['user_id', 'style_post_id'];

    public function post()
    {
        return $this->belongsToMany('App\StylePost');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}