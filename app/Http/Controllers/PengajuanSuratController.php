<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSuratTugas;
use App\Models\LaporanPengaduan;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{
    /**
     * Simpan pengajuan baru (oleh Kepala Inspektorat)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'laporan_id'                      => 'required|exists:laporan_pengaduan,laporan_id',
            'penandatangan_id'                => 'required|exists:users,user_id',
            'nama_ditugaskan'                 => 'nullable|array',
            'nama_ditugaskan.*.nama'          => 'required|string|max:255',
            'nama_ditugaskan.*.jabatan'       => 'nullable|string|max:255',
            'deskripsi_umum'                  => 'nullable|string',
        ]);


        $validated['status'] = PengajuanSuratTugas::STATUS_PENDING;

        if (empty($validated['nama_ditugaskan'])) {
            $validated['nama_ditugaskan'] = [];
        }
        $pengajuan = PengajuanSuratTugas::create($validated);

        return redirect()
            ->route('ketua-bidang.surat.show', $pengajuan->pengajuan_surat_id)
            ->with('success', 'Pengajuan surat tugas berhasil dibuat.');
    }

    /**
     * Detail pengajuan
     */
    public function show(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        return view('ketua-bidang.detail.surat', compact('pengajuanSurat'));
    }

    /**
     * Form edit (biasanya dipakai Sekretaris untuk isi nomor surat & update status)
     */
    public function edit(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        return view('admin.pengajuan-surat.edit', compact('pengajuanSurat'));
    }

    /**
     * Update data pengajuan (nomor surat, status, nama_ditugaskan, deskripsi)
     */
    public function update(Request $request, PengajuanSuratTugas $pengajuanSurat)
    {
        $validated = $request->validate([
            'nomor_surat'       => 'nullable|string|max:100|unique:pengajuan_surat_tugas,nomor_surat,' .
                $pengajuanSurat->pengajuan_surat_id . ',pengajuan_surat_id',
            'status'            => 'required|in:Pending,Dibuat,Selesai',
            'nama_ditugaskan'   => 'nullable|array',
            'nama_ditugaskan.*' => 'string|max:255',
            'deskripsi_umum'    => 'nullable|string',
        ]);

        $pengajuanSurat->update($validated);

        return redirect()
            ->route('pengajuan-surat.show', $pengajuanSurat->pengajuan_surat_id)
            ->with('success', 'Pengajuan surat tugas berhasil diperbarui.');
    }

    /**
     * Hapus pengajuan
     */
    public function destroy(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->delete();

        return redirect()
            ->route('ketua_bidang.surat')
            ->with('success', 'Pengajuan surat tugas berhasil dihapus.');
    }

    /**
     * Endpoint opsional:
     * Sekretaris isi nomor surat dan langsung set status "Selesai"
     */
    public function setNomorDanSelesai(Request $request, PengajuanSuratTugas $pengajuanSurat)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:pengajuan_surat_tugas,nomor_surat,' .
                $pengajuanSurat->pengajuan_surat_id . ',pengajuan_surat_id',
        ]);

        $pengajuanSurat->update([
            'nomor_surat' => $validated['nomor_surat'],
            'status'      => PengajuanSuratTugas::STATUS_SELESAI,
        ]);

        return back()->with('success', 'Nomor surat di-set dan status diubah menjadi Selesai.');
    }
}
