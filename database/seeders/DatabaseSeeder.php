<?php

namespace Database\Seeders;

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
            ProgramStudiSeeder::class,
            DetailUserSeeder::class,
            TingkatanLombaSeeder::class,
            SpkBobotSeeder::class,
            // ManajemenPeriodeSeeder::class,
            // LombaKeahlianSeeder::class,
        ]);
    }
}
