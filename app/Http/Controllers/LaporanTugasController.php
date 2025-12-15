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
}
