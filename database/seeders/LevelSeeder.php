<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('levels')->insert(
            [
                [
                    'level_code' => 'ADM',
                    'nama_level' => 'Admin',
                ],
                [
                    'level_code' => 'DSN',
                    'nama_level' => 'Dosen',
                ],
                [
                    'level_code' => 'MHS',
                    'nama_level' => 'Mahasiswa',
                ]
            ]
        );
    }
}
