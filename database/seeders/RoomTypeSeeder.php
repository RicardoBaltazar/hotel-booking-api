<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['type' => 'Standard', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Family', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Deluxe', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Suite', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('room_type')->insert($types);
    }
}
