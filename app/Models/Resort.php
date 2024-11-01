<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resort extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'admin_id',
        'images_url',
        'number_of_rooms',
        'price_per_room',
        'number_of_poeple',
    ];





    public function owner(){
        return $this->belongsTo(admin::class);
    }
    public function reservations(){
        return $this->hasMany(Resevations::class);
    }
    public function images(){
        return $this->hasMany(resortsImages::class);
    }
    public function spasifications(){
        return $this->hasMany(spasification::class);
    }
    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
}
