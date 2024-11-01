<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_url',
        'resort_id',
    ];

    public function admin()
    {
        return $this->belongsTo(admin::class);
    }
}
