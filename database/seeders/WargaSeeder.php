<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_id' => 13,
                'username' => 'dewix',
                'nik' => '312093021930',
                'email' => 'dewinibos@gmail.com',
                'nama_lengkap' => 'Dewi Ang',
                'password' => Hash::make('password123'),
                'no_telepon' => '1234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Warga',
                'nip' => '1234567890',
                'jabatan' => 'Warga',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
