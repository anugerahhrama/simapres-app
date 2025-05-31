<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('minats')->insert([
            'nama_minat' => 'Teknologi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
?>