<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [

        'name', 'email', 'password',
    ];

    protected $hidden = [

        'password', 'remember_token',
    ];

    public function role(){

        return $this->belongsTo('App\Role');
    }

    public function images(){

        return $this->hasMany('App\Image');
    }

    public function received_images(){

        return $this->hasMany('App\SendImage', 'to_user');
    }

    //Provjera da li je user admin (Admin Middleware)
    public function isAdmin(){

        return ($this->role->name == "Administrator") ? true : false;
    }

}

