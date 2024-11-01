<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image_url'
    ];

    protected $hidden=[
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function reservations(){
        return $this->hasMany(Resevations::class);
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }


}
