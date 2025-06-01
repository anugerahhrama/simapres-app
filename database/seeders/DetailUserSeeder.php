<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DetailUser;

class DetailUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fungsi helper untuk cari prodi_id berdasarkan nama prodi
        function getProdiIdByName(string $name): ?int {
            $prodi = DB::table('program_studis')->where('name', $name)->first();
            return $prodi ? $prodi->id : null;
        }

        // === 1. Admin ===
        $admin = User::firstOrCreate(
            ['email' => 'admin@simapres.com'],
            [
                'password' => Hash::make('pass12345'),
                'level_id' => 1,
            ]
        );

        DetailUser::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'no_induk'      => '197605162005011001',
                'name'          => 'Admin Simapres',
                'phone'         => '081234567890',
                'jenis_kelamin' => 'L',
                'prodi_id'      => getProdiIdByName('D4-Teknik Informatika'),
                'photo_file'    => null,
            ]
        );

        // === 2. Dosen ===
        $dosenList = [
            [
                'email' => 'retnotri@simapres.com',
                'name' => 'RETNO TRI HAYATI RIRID,S.KOM.,M.KOM',
                'no_induk' => '197605162005011002',
                'phone' => '0822000001',
                'jenis_kelamin' => 'P',
                'prodi_name' => 'D4-Teknik Informatika',
            ],
            [
                'email' => 'ahmadfauzi@simapres.com',
                'name' => 'AHMAD FAUZI,S.KOM',
                'no_induk' => '197605162005011003',
                'phone' => '0822000002',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D4-Sistem Informasi Bisnis',
            ],
            [
                'email' => 'puspitasari@simapres.com',
                'name' => 'DWI PUSPITASARI,S.KOM.,M.KOM',
                'no_induk' => '197605162005011004',
                'phone' => '0822000003',
                'jenis_kelamin' => 'P',
                'prodi_name' => 'D4-Teknik Informatika',
            ],
            [
                'email' => 'ekojono@simapres.com',
                'name' => 'EKOJONO,ST.,M.KOM',
                'no_induk' => '197605162005011005',
                'phone' => '0822000004',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D4-Sistem Informasi Bisnis',
            ],
            [
                'email' => 'cahyarahmad@simapres.com',
                'name' => 'CAHYA RAHMAD,ST.,M.KOM',
                'no_induk' => '197605162005011006',
                'phone' => '0822000005',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D2-Pengembangan Piranti Lunak Situs',
            ],
            [
                'email' => 'ariefsetiawan@simapres.com',
                'name' => 'DRS.MOHAMAD ARIEF SETIAWAN,M.KOM',
                'no_induk' => '197605162005011007',
                'phone' => '0822000006',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D4-Teknik Informatika',
            ],
            [
                'email' => 'andikusuma@simapres.com',
                'name' => 'ANDI KUSUMA INDRAWAN,S.KOM.,MT',
                'no_induk' => '197605162005011008',
                'phone' => '0822000007',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D4-Sistem Informasi Bisnis',
            ],
            [
                'email' => 'faisalrahutomo@simapres.com',
                'name' => 'FAISAL RAHUTOMO,ST.,M.KOM',
                'no_induk' => '197605162005011009',
                'phone' => '0822000008',
                'jenis_kelamin' => 'P',
                'prodi_name' => 'D4-Teknik Informatika',
            ],
            [
                'email' => 'arifprasetyo@simapres.com',
                'name' => 'ARIEF PRASETYO,S.KOM',
                'no_induk' => '197605162005011010',
                'phone' => '0822000009',
                'jenis_kelamin' => 'L',
                'prodi_name' => 'D4-Sistem Informasi Bisnis',
            ],
            [
                'email' => 'yuriariyanto@simapres.com',
                'name' => 'YURI ARIYANTO,S.KOM.,M.KOM',
                'no_induk' => '197605162005011011',
                'phone' => '0822000010',
                'jenis_kelamin' => 'P',
                'prodi_name' => 'D2-Pengembangan Piranti Lunak Situs',
            ],
        ];

        foreach ($dosenList as $dsn) {
            $user = User::firstOrCreate(
                ['email' => $dsn['email']],
                [
                    'password' => Hash::make('pass12345'),
                    'level_id' => 2,
                ]
            );

            DetailUser::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'no_induk'      => $dsn['no_induk'],
                    'name'          => $dsn['name'],
                    'phone'         => $dsn['phone'],
                    'jenis_kelamin' => $dsn['jenis_kelamin'],
                    'prodi_id'      => getProdiIdByName($dsn['prodi_name']),
                    'photo_file'    => null,
                ]
            );
        }

        // === 3. Mahasiswa ===
        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@simapres.com'],
            [
                'password' => Hash::make('pass12345'),
                'level_id' => 3,
            ]
        );

        DetailUser::firstOrCreate(
            ['user_id' => $mahasiswa->id],
            [
                'no_induk'      => '2341720456',
                'name'          => 'MAHASISWA TESTING',
                'phone'         => '08123456789',
                'jenis_kelamin' => 'L',
                'prodi_id'      => getProdiIdByName('D2-Pengembangan Piranti Lunak Situs'),
                'photo_file'    => null,
            ]
        );
    }
}
