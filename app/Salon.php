<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $fillable = ['name' ,'address','images','latitude','longitude'];

}
