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
                'nik' => '1987654329',
                'username' => 'pegawai04',
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
                'username' => 'pegawai05',
                'nik' => '19876543298',
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
                'username' => 'pegawai06',
                'nik' => '19876543231',
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
                'username' => 'pegawai10',
                'nik' => '1987654324',
                'email' => 'pegawai10@gmail.com',
                'nama_lengkap' => 'Pegawai 10',
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
                'user_id' => 11,
                'username' => 'pegawai11',
                'nik' => '1987654321',
                'email' => 'pegawai11@gmail.com',
                'nama_lengkap' => 'Pegawai 11',
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
                'user_id' => 12,
                'username' => 'pegawai12',
                'nik' => '1987654322',
                'email' => 'pegawai12@gmail.com',
                'nama_lengkap' => 'Pegawai 12',
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
                'user_id' => 13,
                'username' => 'pegawai13',
                'nik' => '1987654323',
                'email' => 'pegawai13@gmail.com',
                'nama_lengkap' => 'Pegawai 13',
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
                'user_id' => 14,
                'username' => 'kepalainspektorat',
                'nik' => '123456789221',
                'email' => 'kepalainspektorat@gmail.com',
                'nama_lengkap' => 'Kepala Inspektorat',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Kepala_Inspektorat',
                'nip' => '1234567890',
                'jabatan' => 'Kepala Inspektorat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        DB::table('users')->insert($users);
    }
}
