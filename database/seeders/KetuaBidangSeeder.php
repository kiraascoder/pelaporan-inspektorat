<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KetuaBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Ketua Investigasi
            [
                'user_id' => 1,
                'nik' => '1234567890',
                'email' => 'ketuabidanginvestigasi@gmail.com',
                'nama_lengkap' => 'Ketua Investigasi Inspektorat',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Ketua_Bidang_Investigasi',
                'nip' => '1234567890',
                'jabatan' => 'Ketua Bidang Investigasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('users')->insert($users);
    }
}
