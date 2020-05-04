<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylistFollower extends Model
{
    protected $fillable = ['user_id', 'stylist_id'];

    public function user(){
        return $this->belongsToMany('App\User');
    }

    public function stylist(){
        return $this->belongsToMany('App\Stylist');
    }
}
