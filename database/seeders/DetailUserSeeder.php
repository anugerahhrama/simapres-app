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
        function getProdiIdByName(string $name): ?int
        {
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
                'no_induk'      => '-',
                'name'          => 'Admin Simapres',
                'phone'         => '081234567890',
                'jenis_kelamin' => 'L',
                'prodi_id'      => getProdiIdByName('D4-Teknik Informatika'),
                'photo_file'    => null,
            ]
        );
    }
}
