<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpkBobotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spk_bobots')->insert([
            [
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c4' => 0,
                'c5' => 0,
            ],
        ]);
    }
}
