<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function images(){
        return $this->hasMany('App\Image');
    }

    //Provjera da li je user admin (Admin Middleware)
    public function isAdmin(){

       /* if($this->role->name == "Administrator")
        {
            return true;
        }

        return false;
    }*/
    // Ovo sam izmjenio treba provjeriti
        return ($this->role->name == "Administrator") ? true : false;
    }

}
