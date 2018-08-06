<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendImage extends Model
{
    public function toUser(){

    	return $this->belongsTo('App\User', 'to_user');
    }

    public function imageData(){

    	return $this->belongsTo('App\Image', 'image_id');
    }
}
