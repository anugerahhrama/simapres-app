<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'mahasiswa@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa1@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa2@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa3@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa4@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa5@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa6@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa7@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa8@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa9@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
        DB::table('users')->insert([
            'email' => 'mahasiswa10@simapres.com',
            'password' => Hash::make('pass12345'),
            'level_id' => '3',
        ]);
    }
}
