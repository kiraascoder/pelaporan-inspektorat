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
        // Create users
        $users = [
            // ADMIN
            [
                'user_id' => 2,
                'nik' => '1234567891',
                'email' => 'admininspektorat@gmail.com',
                'nama_lengkap' => 'Admin Inspektorat',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'admin',
                'nip' => '1234567890',
                'jabatan' => 'Admin Inspektorat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('users')->insert($users);
    }
}
