<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use App\Models\LaporanTugas;
use App\Models\TimInvestigasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanTugasController extends Controller
{
    public function store(Request $request)
    {
        // Opsi yang muncul di checkbox temuan_pemeriksaan
        $allowedTemuan = [
            'Terlapor Tidak ada di Rumah',
            'Alamat Tidak Ditemukan',
            'Dokumen Tidak Lengkap',
            'Tidak Kooperatif saat dimintai keterangan',
            'Kooperatif dan Bersedia Memberikan Keterangan',
            'Butuh Pemeriksaan Lanjutan',
            'Selesai Diperiksa Di Tempat',
        ];

        $validated = $request->validate([
            'laporan_pengaduan_id' => 'nullable|integer', // pakai exists:<tabel>,id kalau sudah pasti tabelnya
            'judul_laporan'        => 'required|string|max:255',
            'isi_laporan'          => 'required|string',
            'temuan'               => 'nullable|string',
            'rekomendasi'          => 'nullable|string',

            // Hanya dua status sesuai <select> di form
            'status_laporan'       => 'required|string|in:Draft,Submitted',

            // Checkbox temuan pemeriksaan
            'temuan_pemeriksaan'   => 'nullable|array',
            'temuan_pemeriksaan.*' => 'in:' . implode(',', $allowedTemuan),

            // File input: nama field dari form adalah bukti_pendukung[]
            // Samakan mimes dengan accept (termasuk xls/xlsx), ukuran 5MB
            'bukti_pendukung'      => 'nullable|array',
            'bukti_pendukung.*'    => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $auth      = Auth::user();
        $sekretarisId = $auth->user_id ?? $auth->id; // sesuaikan dengan PK users (migration kamu pakai user_id)

        $storedPaths = [];
        if ($request->hasFile('bukti_pendukung')) {
            foreach ($request->file('bukti_pendukung') as $file) {
                $storedPaths[] = $file->store('bukti_pendukung', 'public'); // storage/app/public/bukti_pendukung
            }
        }

        DB::beginTransaction();
        try {
            $tanggalSubmit = $validated['status_laporan'] === 'Submitted' ? now() : null;
            
            $payload = [
                'sekretaris_id'         => $sekretarisId,
                'judul_laporan'      => $validated['judul_laporan'],
                'isi_laporan'        => $validated['isi_laporan'],
                'temuan'             => $validated['temuan'] ?? null,
                'rekomendasi'        => $validated['rekomendasi'] ?? null,
                'temuan_pemeriksaan' => $validated['temuan_pemeriksaan'] ?? null, // cast: array
                'bukti_pendukung'    => $storedPaths ?: null,                     // cast: array
                'status_laporan'     => $validated['status_laporan'],
                'tanggal_submit'     => $tanggalSubmit,
                'pegawai_id' => $auth->user_id ?? $auth->id,

            ];

            // Simpan jika kamu memang punya kolom ini di tabel
            if (!empty($validated['laporan_pengaduan_id'])) {
                $payload['laporan_pengaduan_id'] = $validated['laporan_pengaduan_id'];
            }

            LaporanTugas::create($payload);

            DB::commit();

            return redirect()
                ->route('pegawai.laporan_tugas')
                ->with('success', 'Laporan berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Bersihkan file yang terlanjur ter-upload
            foreach ($storedPaths as $path) {
                try {
                    Storage::disk('public')->delete($path);
                } catch (\Throwable $e2) {
                }
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    private function recalculateAndMaybeFinish(int $timId): void
    {
        // Ambil tim & kunci laporan terkait
        $tim = TimInvestigasi::query()->find($timId);
        if (!$tim || !$tim->laporan_id) return;

        // Anggota aktif (sekretaris_id)
        $activesekretarisIds = DB::table('anggota_tim')
            ->where('tim_id', $timId)
            ->where(function ($q) {
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->pluck('sekretaris_id')
            ->unique();

        if ($activesekretarisIds->isEmpty()) return;


        $submittedsekretarisIds = DB::table('laporan_tugas')
            ->where('tim_id', $timId)
            ->whereIn('status_laporan', ['Submitted', 'Reviewed', 'Approved'])
            ->distinct()
            ->pluck('sekretaris_id')
            ->unique();

        $allSubmitted = $activesekretarisIds->diff($submittedsekretarisIds)->isEmpty();

        if ($allSubmitted) {
            $laporan = LaporanPengaduan::lockForUpdate()->find($tim->laporan_id);
            if ($laporan && $laporan->status !== 'Selesai') {
                $laporan->update(['status' => 'Selesai']);
            }
        }
    }
    public function showLaporan(LaporanPengaduan $laporan)
    {
        $laporan->load('laporanTugas.pegawai');
        $tim = TimInvestigasi::with('anggotaAktif')
            ->where('laporan_id', $laporan->laporan_id)
            ->first();

        return view('pegawai.detail.laporan-tugas', [
            'laporan'      => $laporan,
            'laporanTugas' => $laporan->laporanTugas,
            'tim'          => $tim,
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanTugas::findOrFail($id);

        // hanya pemilik laporan
        if ($laporan->pegawai_id !== Auth::id()) {
            abort(403, 'Tidak diizinkan');
        }

        // hanya boleh edit jika masih Draft
        if ($laporan->status_laporan !== 'Draft') {
            return back()->with('error', 'Laporan tidak dapat diedit setelah disubmit');
        }

        $data = $request->validate([
            'judul_laporan'        => 'required|string',
            'isi_laporan'          => 'required|string',
            'temuan'               => 'nullable|string',
            'rekomendasi'          => 'nullable|string',
            'temuan_pemeriksaan'   => 'nullable|array',
            'bukti_pendukung.*'    => 'nullable|file|max:5120',
            'status_laporan'       => 'required|in:Draft,Submitted',
        ]);

        // upload lampiran baru
        if ($request->hasFile('bukti_pendukung')) {
            $files = [];
            foreach ($request->file('bukti_pendukung') as $file) {
                $files[] = $file->store('laporan_tugas', 'public');
            }
            $data['bukti_pendukung'] = $files;
        }

        if ($data['status_laporan'] === 'Submitted') {
            $data['tanggal_submit'] = now();
        }

        $laporan->update($data);

        return back()->with('success', 'Laporan tugas berhasil diperbarui');
    }



    /* =====================================================
 | APPROVAL (KETUA TIM)
 ===================================================== */
    public function setStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Reviewed,Approved,Rejected',
        ]);

        $laporan = LaporanTugas::with('laporanPengaduan.timInvestigasi.anggotaAktif')
            ->findOrFail($id);

        // hanya Ketua Tim
        if (!$this->isKetuaTim($laporan)) {
            abort(403, 'Hanya Ketua Tim yang dapat melakukan aksi ini');
        }

        $laporan->update([
            'status_laporan' => $request->status,
        ]);

        return back()->with(
            'success',
            "Status laporan berhasil diubah menjadi {$request->status}"
        );
    }

    public function destroy($id)
    {
        $laporan = LaporanTugas::findOrFail($id);

        if ($laporan->pegawai_id !== Auth::id()) {
            abort(403);
        }

        if ($laporan->status_laporan !== 'Draft') {
            return back()->with('error', 'Hanya laporan Draft yang bisa dihapus');
        }

        // hapus file
        if (is_array($laporan->bukti_pendukung)) {
            foreach ($laporan->bukti_pendukung as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $laporan->delete();

        return back()->with('success', 'Laporan tugas berhasil dihapus');
    }

    /* =====================================================
     | DOWNLOAD LAPORAN
     ===================================================== */
    public function download($id)
    {
        $laporan = LaporanTugas::with('pegawai')->findOrFail($id);

        // contoh: download PDF sederhana (HTML → PDF bisa ditambah)
        $filename = 'laporan_tugas_' . $laporan->laporan_tugas_id . '.txt';

        $content = "
        LAPORAN TUGAS PEGAWAI

        Pegawai : {$laporan->pegawai->nama_lengkap}
        Judul   : {$laporan->judul_laporan}

        Isi:
        {$laporan->isi_laporan}

        Temuan:
        {$laporan->temuan}

        Rekomendasi:
        {$laporan->rekomendasi}
        ";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /* =====================================================
     | HELPER: CEK KETUA TIM
     ===================================================== */
    protected function isKetuaTim(LaporanTugas $laporan): bool
    {
        $tim = $laporan->laporanPengaduan
            ->timInvestigasi()
            ->with('anggotaAktif')
            ->first();

        if (!$tim) return false;

        $anggota = $tim->anggotaAktif
            ->firstWhere('user_id', Auth::id());

        return $anggota && $anggota->pivot->role_dalam_tim === 'Ketua';
    }
}
