<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable{

    protected $fillable = [
        'name','email','password','phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guard_name = 'super-admin';
}
