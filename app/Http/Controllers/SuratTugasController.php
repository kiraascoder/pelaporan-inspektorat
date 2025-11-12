<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratTugas;
use Illuminate\Http\Request;

class SuratTugasController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'nomor_surat'      => ['required', 'string', 'max:120', 'unique:pengajuan_surat_tugas,nomor_surat'],
            'laporan_id'       => ['required', 'exists:laporan_pengaduan,laporan_id'],
            'penandatangan_id' => ['required', 'exists:users,user_id'],
            'deskripsi_umum'   => ['nullable', 'string'], // tiap baris = 1 poin "Untuk"
            'status'           => ['required', 'in:Pending,Dibuat,Selesai'],
            // anggota tim (opsional)
            'anggota.pegawai_id.*'  => ['nullable', 'exists:users,user_id'],
            'anggota.role.*'        => ['nullable', 'string', 'max:100'],
            'anggota.deskripsi.*'   => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data, $r) {
            $pengajuan = PengajuanSuratTugas::create([
                'nomor_surat'      => $data['nomor_surat'],
                'laporan_id'       => $data['laporan_id'],
                'penandatangan_id' => $data['penandatangan_id'],
                'deskripsi_umum'   => $data['deskripsi_umum'] ?? null,
                'status'           => $data['status'],
            ]);

            // simpan anggota jika ada
            $pegIds = (array)($r->input('anggota.pegawai_id') ?? []);
            $roles  = (array)($r->input('anggota.role') ?? []);
            $desks  = (array)($r->input('anggota.deskripsi') ?? []);

            foreach ($pegIds as $i => $pid) {
                if (!$pid) continue;
                PengajuanSuratTugas::create([
                    'pengajuan_id'    => $pengajuan->pengajuan_surat_id,
                    'pegawai_id'      => $pid,
                    'role_dalam_tim'  => $roles[$i] ?? 'Anggota',
                    'deskripsi_tugas' => $desks[$i] ?? null,
                ]);
            }
        });

        return back()->with('success', 'Surat tugas berhasil dibuat.');
    }
}
