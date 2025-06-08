<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bimbingan;

class BimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data bimbingan, sesuaikan id dosen & mahasiswa sesuai data user di tabel users
        Bimbingan::create([
            'dosen_id' => 2,
            'mahasiswa_id' => 1,
            'tanggal_mulai' => now()->subDays(10)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(20)->format('Y-m-d'),
            'catatan_bimbingan' => 'Bimbingan awal proposal.',
            'status' => 0, // 0 = Berjalan
        ]);

        Bimbingan::create([
            'dosen_id' => 2,
            'mahasiswa_id' => 1,
            'tanggal_mulai' => now()->subDays(30)->format('Y-m-d'),
            'tanggal_selesai' => now()->subDays(5)->format('Y-m-d'),
            'catatan_bimbingan' => 'Bimbingan selesai, siap sidang.',
            'status' => 1, // 1 = Selesai
        ]);
    }
}
