<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_type_id',
        'status_id',
        'description',
        'price'
    ];
}
