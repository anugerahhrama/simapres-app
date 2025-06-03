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
            'nama_minat' => 'Web Dev',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('minats')->insert([
            'nama_minat' => 'Game Dev',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('minats')->insert([
            'nama_minat' => 'UI/UX',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('minats')->insert([
            'nama_minat' => 'Desain Grafis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
?>