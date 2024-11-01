<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resevations extends Model
{
    use HasFactory;

    // app/Models/Reservation.php

    protected $fillable = [
        'user_id',
        'resort_id',
        'start_date',
        'end_date',
        'status',
        'method',
        'amount',
        'adults',
        'kids'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }

}
