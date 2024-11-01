<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spasification extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_id',
        'spasification',
    ];
    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }
}
