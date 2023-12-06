<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $fillable = ['name', 'location', 'amenities', 'user_id', 'release_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
