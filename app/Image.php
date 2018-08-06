<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function user(){
    	
    	return $this->belongsTo('App\User');
    }

    public function sent(){

    	return $this->hasMany('App\SendImage');
    }
}
