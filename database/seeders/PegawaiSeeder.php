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

            // 2. KASPIAH SANUSI
            [
                'user_id' => 4,
                'username' => 'kaspiah.sanusi',
                'nik' => '197801182006042037',
                'email' => 'kaspiah.sanusi@inspektorat.go.id',
                'nama_lengkap' => 'KASPIAH SANUSI',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '197801182006042037',
                'jabatan' => 'Pembina / IV.c',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 3. AMANAH S SALIM EKSPENG
            [
                'user_id' => 5,
                'username' => 'amanah.ekspeng',
                'nik' => '197704302010011009',
                'email' => 'amanah.ekspeng@inspektorat.go.id',
                'nama_lengkap' => 'AMANAH SALIM EKSPENG',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '197704302010011009',
                'jabatan' => 'Pembina / IV.a',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 4. NUR MUHAMMAD JUMADIL
            [
                'user_id' => 6,
                'username' => 'nur.jumadil',
                'nik' => '198103122012011021',
                'email' => 'nur.jumadil@inspektorat.go.id',
                'nama_lengkap' => 'NUR MUHAMMAD JUMADIL',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567893',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '198103122012011021',
                'jabatan' => 'Penata Tk. I / III.d',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 5. SYAHRIWIATI DASIRAN
            [
                'user_id' => 7,
                'username' => 'syahriwiati.dasiran',
                'nik' => '197405192011012032',
                'email' => 'syahriwiati.dasiran@inspektorat.go.id',
                'nama_lengkap' => 'SYAHRIWIATI DASIRAN',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567894',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '197405192011012032',
                'jabatan' => 'Penata Tk. I / III.d',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 6. ANDI RETNO AYU LESTARI
            [
                'user_id' => 8,
                'username' => 'andi.retno',
                'nik' => '198608222011012032',
                'email' => 'andi.retno@inspektorat.go.id',
                'nama_lengkap' => 'ANDI RETNO AYU LESTARI',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567895',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '198608222011012032',
                'jabatan' => 'Penata / III.c',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Gambar 1 - Data Pegawai
            // 7. MUSDALIFAH NURUN
            [
                'user_id' => 9,
                'username' => 'musdalifah.nurun',
                'nik' => '198511042012022002',
                'email' => 'musdalifah.nurun@inspektorat.go.id',
                'nama_lengkap' => 'MUSDALIFAH NURUN',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567896',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '198511042012022002',
                'jabatan' => 'Penata Muda Tk.I / III.b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 8. MUFT. AMPI
            [
                'user_id' => 10,
                'username' => 'muft.ampi',
                'nik' => '199611242020122002',
                'email' => 'muft.ampi@inspektorat.go.id',
                'nama_lengkap' => 'MUFT. AMPI',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567897',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '199611242020122002',
                'jabatan' => 'Penata Muda Tk.I / III.b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 9. ANDI NURUN NISWAH SAID
            [
                'user_id' => 11,
                'username' => 'andi.niswah',
                'nik' => '199611062025042005',
                'email' => 'andi.niswah@inspektorat.go.id',
                'nama_lengkap' => 'ANDI NURUN NISWAH SAID',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567898',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '199611062025042005',
                'jabatan' => 'Penata Muda',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 10. FITRIATUSSAKIAH
            [
                'user_id' => 12,
                'username' => 'fitriatussakiah',
                'nik' => '199802202504202005',
                'email' => 'fitriatussakiah@inspektorat.go.id',
                'nama_lengkap' => 'FITRIATUSSAKIAH',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567899',
                'alamat' => 'Jl. Contoh, Kota Contoh',
                'role' => 'Pegawai',
                'nip' => '199802202504202005',
                'jabatan' => 'Penata Muda',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
