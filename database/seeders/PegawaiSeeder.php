<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Pegawai
            [
                'user_id' => 4,
                'nik' => '1987654321',
                'email' => 'pegawai04@gmail.com',
                'nama_lengkap' => 'Pegawai 04',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '1234567890',
                'jabatan' => 'Pegawai',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'nik' => '1987654322',
                'email' => 'pegawai05@gmail.com',
                'nama_lengkap' => 'Pegawai 05',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '1234567890',
                'jabatan' => 'Pegawai',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'nik' => '1987654323',
                'email' => 'pegawai06@gmail.com',
                'nama_lengkap' => 'Pegawai 06',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '1234567890',
                'jabatan' => 'Pegawai',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'nik' => '1987654324',
                'email' => 'pegawai07@gmail.com',
                'nama_lengkap' => 'Pegawai 07',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '1234567890',
                'jabatan' => 'Pegawai',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ];

        DB::table('users')->insert($users);
    }
}
