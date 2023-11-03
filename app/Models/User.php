<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'role'
    ];

    // for orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
