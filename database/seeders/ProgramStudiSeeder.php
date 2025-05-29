<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('program_studis')->insert(
            [
                [
                    'name'      => 'Teknik Informatika',
                    'jurusan'   => 'Teknologi Informasi',
                ],
                [
                    'name'      => 'Sistem Informasi Bisnis',
                    'jurusan'   => 'Teknologi Informasi',
                ],
            ]
        );
    }
}
