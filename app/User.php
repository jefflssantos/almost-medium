<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }
}
