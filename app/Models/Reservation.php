<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'user_id',
        'daily_rates',
        'reservation_date',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
