<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
use App\Models\LaporanTugas;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'tim_aktif' => $user->timInvestigasiDiikuti()->aktif()->count(),
            'surat_tugas_aktif' => SuratTugas::whereHas('timInvestigasi.anggota', function ($query) use ($user) {
                $query->where('anggota_tim.pegawai_id', $user->user_id)
                    ->where('anggota_tim.is_active', 1);
            })->dalamPelaksanaan()->count(),
            'laporan_tugas_draft' => $user->laporanTugas()->draft()->count(),
            'laporan_tugas_submitted' => $user->laporanTugas()->submitted()->count(),
        ];

        // Surat tugas terbaru - also fixed ambiguous column
        $suratTugasTerbaru = SuratTugas::whereHas('timInvestigasi.anggota', function ($query) use ($user) {
            $query->where('anggota_tim.pegawai_id', $user->user_id)
                ->where('anggota_tim.is_active', true);
        })
            ->with(['timInvestigasi', 'laporanPengaduan'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pegawai.dashboard', compact('stats', 'suratTugasTerbaru'));
    }

    // Alternative approach using the new anggotaAktif relationship
    public function dashboardAlternative()
    {
        $user = auth()->user();

        // Get active team IDs for the user
        $activeTeamIds = $user->timInvestigasiDiikuti()
            ->wherePivot('is_active', true)
            ->pluck('tim_investigasi.tim_id');

        $stats = [
            'tim_aktif' => $activeTeamIds->count(),
            'surat_tugas_aktif' => SuratTugas::whereIn('tim_id', $activeTeamIds)
                ->dalamPelaksanaan()
                ->count(),
            'laporan_tugas_draft' => $user->laporanTugas()->draft()->count(),
            'laporan_tugas_submitted' => $user->laporanTugas()->submitted()->count(),
        ];


        $suratTugasTerbaru = SuratTugas::whereIn('tim_id', $activeTeamIds)
            ->with(['timInvestigasi', 'laporanPengaduan'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pegawai.dashboard', compact('stats', 'suratTugasTerbaru'));
    }

    public function laporanTersedia()
    {
        $laporan = LaporanPengaduan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(12) // Limit to show recent 12 reports
            ->get();

        $stats = [
            'laporan_pending' => LaporanPengaduan::pending()->count(),
            'laporan_diterima' => LaporanPengaduan::diterima()->count(),
            'laporan_dalam_investigasi' => LaporanPengaduan::dalamInvestigasi()->count(),
            'laporan_selesai' => LaporanPengaduan::selesai()->count(),
            'semuaTim' => TimInvestigasi::count(),
            'surat_tugas_aktif' => SuratTugas::where('status_surat', 'Aktif')->count(),
        ];

        return view('pegawai.laporan', compact('laporan', 'stats'));
    }
    public function showLaporan(LaporanPengaduan $laporan)
    {
        return view('pegawai.detail.laporan', compact('laporan'));
    }
    public function showTimInvestigasi($tim_id)
    {
        try {
            $tim = TimInvestigasi::with([
                'ketuaTim',
                'anggotaAktif',
                'laporanPengaduan',
                'suratTugas'
            ])->findOrFail($tim_id);

            return view('pegawai.detail.tim', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
    }

    public function tim()
{
    try {
        $pegawaiId = Auth::user()->user_id;

        // Ambil semua tim yang memiliki anggota aktif dengan pegawai_id = user login
        $timList = TimInvestigasi::whereHas('anggotaAktif', function ($query) use ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        })
        ->with(['ketuaTim', 'anggotaAktif', 'laporanPengaduan'])
        ->latest()
        ->get();

        // Hitung total dan statistik hanya dari tim yang diikuti
        $totalTim = $timList->count();
        $timAktif = $timList->where('is_active', true)->count();

        // $dalamInvestigasi = $timList->filter(function ($tim) {
        //     return $tim->laporanPengaduan->contains('status_tim', 'Dalam Investigasi');
        // })->count();

        // $kasusSelesai = $timList->filter(function ($tim) {
        //     return $tim->laporanPengaduan->contains('status_tim', 'Selesai');
        // })->count();

        // Pegawai list dan laporan hanya jika kamu perlu (misalnya untuk modal tambah tim)
        $pegawaiList = User::where('role', 'Pegawai')->get()->map(function ($user) {
            return [
                'id' => $user->user_id,
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'jabatan' => $user->jabatan
            ];
        });

        $laporanList = LaporanPengaduan::where('status', '!=', 'Selesai')
            ->whereDoesntHave('timInvestigasi')
            ->get(['laporan_id as id', 'judul_laporan']);

        return view('pegawai.tim', compact(
            'totalTim',
            'timAktif',
            // 'dalamInvestigasi',
            // 'kasusSelesai',
            'timList',
            'pegawaiList',
            'laporanList'
        ));
    } catch (Exception $e) {
        return back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
    }
}
    public function timSaya()
    {
        $user = auth()->user();

        $tim = $user->timInvestigasiDiikuti()
            ->with(['ketua', 'laporanPengaduan', 'anggotaAktif'])
            ->paginate(10);

        return view('pegawai.tim.index', compact('tim'));
    }

    public function showTim(TimInvestigasi $tim)
    {
        // Middleware sudah handle access check
        $tim->load(['ketua', 'laporanPengaduan', 'anggotaAktif', 'suratTugas']);

        return view('pegawai.tim.show', compact('tim'));
    }

    public function suratTugas()
    {
        $user = auth()->user();

        $suratTugas = SuratTugas::whereHas('timInvestigasi.anggota', function ($query) use ($user) {
            $query->where('pegawai_id', $user->user_id)->where('is_active', true);
        })
            ->with(['timInvestigasi', 'laporanPengaduan'])
            ->latest()
            ->paginate(10);

        return view('pegawai.surat_tugas.index', compact('suratTugas'));
    }

    public function showSuratTugas(SuratTugas $surat)
    {
        // Middleware sudah handle access check
        $surat->load(['timInvestigasi.anggotaAktif', 'laporanPengaduan', 'laporanTugas']);

        return view('pegawai.surat_tugas.show', compact('surat'));
    }

    public function laporanTugas()
    {
        $user = auth()->user();

        $laporanTugas = $user->laporanTugas()
            ->with(['suratTugas.timInvestigasi', 'suratTugas.laporanPengaduan'])
            ->latest()
            ->paginate(10);

        return view('pegawai.laporan_tugas.index', compact('laporanTugas'));
    }

    public function createLaporanTugas(SuratTugas $surat)
    {
        // Cek apakah user adalah anggota tim
        $user = auth()->user();
        $isAnggota = $surat->timInvestigasi
            ->anggota()
            ->where('pegawai_id', $user->user_id)
            ->where('is_active', true)
            ->exists();

        if (!$isAnggota) {
            abort(403, 'Anda bukan anggota tim untuk surat tugas ini.');
        }

        return view('pegawai.laporan_tugas.create', compact('surat'));
    }

    public function storeLaporanTugas(Request $request, SuratTugas $surat)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'temuan' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
            'bukti_pendukung.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        $data['surat_id'] = $surat->surat_id;
        $data['pegawai_id'] = auth()->id();
        $data['status_laporan'] = 'Draft';

        // Handle file upload
        if ($request->hasFile('bukti_pendukung')) {
            $files = [];
            foreach ($request->file('bukti_pendukung') as $file) {
                $files[] = $file->store('bukti_pendukung', 'public');
            }
            $data['bukti_pendukung'] = $files;
        }

        LaporanTugas::create($data);

        return redirect()->route('pegawai.laporan_tugas.index')
            ->with('success', 'Laporan tugas berhasil dibuat.');
    }

    public function showLaporanTugas(LaporanTugas $laporanTugas)
    {
        // Middleware sudah handle access check
        $laporanTugas->load(['suratTugas.timInvestigasi', 'suratTugas.laporanPengaduan']);

        return view('pegawai.laporan_tugas.show', compact('laporanTugas'));
    }

    public function editLaporanTugas(LaporanTugas $laporanTugas)
    {
        if (!in_array($laporanTugas->status_laporan, ['Draft', 'Reviewed'])) {
            return redirect()->back()->with('error', 'Laporan tugas tidak dapat diubah.');
        }

        return view('pegawai.laporan_tugas.edit', compact('laporanTugas'));
    }

    public function updateLaporanTugas(Request $request, LaporanTugas $laporanTugas)
    {
        if (!in_array($laporanTugas->status_laporan, ['Draft', 'Reviewed'])) {
            return redirect()->back()->with('error', 'Laporan tugas tidak dapat diubah.');
        }

        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'temuan' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
        ]);

        $laporanTugas->update($request->all());

        return redirect()->route('pegawai.laporan_tugas.show', $laporanTugas)
            ->with('success', 'Laporan tugas berhasil diperbarui.');
    }

    public function submitLaporanTugas(LaporanTugas $laporanTugas)
    {
        if ($laporanTugas->status_laporan !== 'Draft') {
            return redirect()->back()->with('error', 'Laporan tugas sudah disubmit.');
        }

        $laporanTugas->update([
            'status_laporan' => 'Submitted',
            'tanggal_submit' => now(),
        ]);

        return redirect()->route('pegawai.laporan_tugas.index')
            ->with('success', 'Laporan tugas berhasil disubmit.');
    }
}
