<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function scopeGetByUserId($query, $userId)
    {
        return $query
            ->select('role')
            ->where('user_id', '=', $userId)
            ->get();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
