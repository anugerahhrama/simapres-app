<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManajemenPeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periodes')->insert(
            [
                [
                    'tahun_ajaran'      => '2021/2022',
                    'semester'          => '8',
                    'tanggal_mulai'     => '2025-02-10',
                    'tanggal_selesai'   => '2025-06-20',
                    'status'            => '1',
                ],
                [
                    'tahun_ajaran'      => '2022/2023',
                    'semester'          => '6',
                    'tanggal_mulai'     => '2025-02-10',
                    'tanggal_selesai'   => '2025-06-20',
                    'status'            => '1',
                ],
                [
                    'tahun_ajaran'      => '2023/2024',
                    'semester'          => '4',
                    'tanggal_mulai'     => '2025-02-10',
                    'tanggal_selesai'   => '2025-06-20',
                    'status'            => '1',
                ],
            ]
        );
    }
}
