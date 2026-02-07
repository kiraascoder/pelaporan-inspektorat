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
            [
                'user_id' => 3,
                'username' => 'andi.kamarlang',
                'nik' => '196905202002012013',
                'email' => 'andi.kamarlang@inspektorat.go.id',
                'nama_lengkap' => 'ANDI KAMARLANG',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Ketua_Bidang_Investigasi',
                'nip' => '196905202002012013',
                'jabatan' => 'Pembina / IV.a',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
