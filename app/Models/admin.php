<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'images_url',
        'description',
        'phone_numbers',
        'for_families',
        'location',
    ];

    protected $hidden = [
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
    public function resorts()
    {
        return $this->hasMany(Resort::class);
    }
    public function images()
    {
        return $this->hasMany(images::class);
    }

}
