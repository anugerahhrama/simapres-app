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
                    'name'      => 'D4-Teknik Informatika',
                    'jurusan'   => 'Teknologi Informasi',
                ],
                [
                    'name'      => 'D4-Sistem Informasi Bisnis',
                    'jurusan'   => 'Teknologi Informasi',
                ],
                [
                    'name'      => 'D2-Pengembangan Piranti Lunak Situs',
                    'jurusan'   => 'Teknologi Informasi',
                ],
            ]
        );
    }
}
