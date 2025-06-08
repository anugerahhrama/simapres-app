<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DetailUser;
use App\Models\Keahlian;
use App\Models\Prestasi;
use App\Models\ProgramStudi;
use App\Models\TingkatanLomba;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            ProgramStudiSeeder::class,
            ManajemenPeriodeSeeder::class,
            MinatSeeder::class,
            TingkatanLombaSeeder::class,
            DetailUserSeeder::class,
            PrestasiSeeder::class,
            KeahlianSeeder::class,
            LombaSeeder::class,
            BimbinganSeeder::class,
        ]);
    }
}
