<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomStatusSeeder extends Seeder
{
    public function run(): void
    {
        $status = [
            ['status' => 'Available'],
            ['status' => 'Occupied'],
            ['status' => 'Under Maintenance'],
        ];

        DB::table('room_status')->insert($status);
    }
}
