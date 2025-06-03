<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('keahlians')->insert([
            ['nama_keahlian' => 'Game Dev'],
            ['nama_keahlian' => 'WEB Dev'],
            ['nama_keahlian' => 'UI/UX'],
            ['nama_keahlian' => 'Desain Grafis'],
            ['nama_keahlian' => 'Frontend Dev'],
        ]);
    }
}
?>