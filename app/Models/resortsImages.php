<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resortsImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_url',
        'resort_id',
    ];

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }
}
