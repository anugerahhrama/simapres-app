<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Prestasi Mahasiswa Teknik Informatika ---
        DB::table('prestasis')->insert([
            [
                'mahasiswa_id' => 1, // Contoh: Mahasiswa 3
                'lomba_id' => 1,     // Lomba: Kompetisi Pemrograman Nasional (Kopertinas)
                'nama_kegiatan' => 'Juara 3 Web Dev',
                'deskripsi' => 'Meraih juara ketiga dalam Kompetisi Pemrograman Nasional.',
                'tanggal' => '2024-04-05',
                'kategori' => 'Akademik',
                'pencapaian' => 'Juara 3',
                'evaluasi_diri' => 'Perlu lebih banyak latihan soal-soal algoritma kompleks.',
                'status_verifikasi' => 'verified',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        //     [
        //         'mahasiswa_id' => 1, // Contoh: Mahasiswa 4
        //         'lomba_id' => 5,     // Lomba: Lomba Desain UI/UX Aplikasi Mobile
        //         'nama_kegiatan' => 'Finalis Desain UI/UX',
        //         'deskripsi' => 'Tim masuk final dalam Lomba Desain UI/UX Aplikasi Mobile.',
        //         'tanggal' => '2024-05-10',
        //         'kategori' => 'Non Akademik',
        //         'pencapaian' => 'Finalis',
        //         'evaluasi_diri' => 'Desain inovatif, namun presentasi bisa lebih menarik.',
        //         'status_verifikasi' => 'approved',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 6, // Contoh: Mahasiswa 5
        //         'lomba_id' => 6,     // Lomba: Kompetisi Data Science & Machine Learning
        //         'nama_kegiatan' => 'Peserta Kompetisi Data Science',
        //         'deskripsi' => 'Berpartisipasi dalam Kompetisi Data Science & Machine Learning.',
        //         'tanggal' => '2024-06-01',
        //         'kategori' => 'Akademik',
        //         'pencapaian' => 'Peserta',
        //         'evaluasi_diri' => 'Memahami dasar, perlu pendalaman pada teknik optimasi model.',
        //         'status_verifikasi' => 'pending',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 7, // Contoh: Mahasiswa 6
        //         'lomba_id' => 7,     // Lomba: Web Development Challenge 2024
        //         'nama_kegiatan' => 'Juara Harapan Web Dev Challenge',
        //         'deskripsi' => 'Meraih juara harapan dalam Web Development Challenge 2024.',
        //         'tanggal' => '2024-07-12',
        //         'kategori' => 'Non Akademik',
        //         'pencapaian' => 'Juara Harapan',
        //         'evaluasi_diri' => 'Pengembangan cepat, perlu perbaikan pada aspek keamanan.',
        //         'status_verifikasi' => 'approved',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 8, // Contoh: Mahasiswa 7
        //         'lomba_id' => 8,     // Lomba: Competitive Programming League (CPL)
        //         'nama_kegiatan' => 'Peringkat Top 10 CPL',
        //         'deskripsi' => 'Mencapai peringkat 10 besar dalam Competitive Programming League bulanan.',
        //         'tanggal' => '2024-08-15',
        //         'kategori' => 'Akademik',
        //         'pencapaian' => 'Top 10',
        //         'evaluasi_diri' => 'Konsisten dalam performa, perlu meningkatkan kecepatan debugging.',
        //         'status_verifikasi' => 'approved',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 9, // Contoh: Mahasiswa 8
        //         'lomba_id' => 9,     // Lomba: Mobile App Innovation Challenge
        //         'nama_kegiatan' => 'Juara 1 Mobile App Challenge',
        //         'deskripsi' => 'Meraih juara pertama dalam Mobile App Innovation Challenge dengan aplikasi sosial.',
        //         'tanggal' => '2024-10-10',
        //         'kategori' => 'Non Akademik',
        //         'pencapaian' => 'Juara 1',
        //         'evaluasi_diri' => 'Ide brilian dan implementasi solid, sangat puas.',
        //         'status_verifikasi' => 'approved',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 10, // Contoh: Mahasiswa 9
        //         'lomba_id' => 10,    // Lomba: Cyber Security Capture The Flag (CTF)
        //         'nama_kegiatan' => 'Peringkat 5 CTF',
        //         'deskripsi' => 'Tim meraih peringkat 5 dalam kompetisi Cyber Security Capture The Flag.',
        //         'tanggal' => '2024-10-25',
        //         'kategori' => 'Non Akademik',
        //         'pencapaian' => 'Top 5',
        //         'evaluasi_diri' => 'Kemampuan forensik digital perlu ditingkatkan.',
        //         'status_verifikasi' => 'pending',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 11, // Contoh: Mahasiswa 10
        //         'lomba_id' => 11,    // Lomba: Artificial Intelligence Challenge (AIC)
        //         'nama_kegiatan' => 'Finalis AI Challenge',
        //         'deskripsi' => 'Tim masuk final dalam Artificial Intelligence Challenge dengan solusi inovatif.',
        //         'tanggal' => '2024-12-15',
        //         'kategori' => 'Akademik',
        //         'pencapaian' => 'Finalis',
        //         'evaluasi_diri' => 'Model AI sudah canggih, perlu presentasi yang lebih persuasif.',
        //         'status_verifikasi' => 'approved',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
         ]);
    }
}
