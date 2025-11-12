<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use App\Models\SuratTugas;
use App\Models\TimInvestigasi;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class SekretarisController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $laporanList = $user->laporanTugas()
            ->with([
                'suratTugas.timInvestigasi',
                'suratTugas.laporanPengaduan'
            ])
            ->latest()
            ->paginate(10);

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

        return view('sekretaris.dashboard', compact('stats', 'suratTugasTerbaru', 'laporanList'));
    }

    public function laporan(Request $request)
    {
        $stats = [
            'laporan_pending'            => LaporanPengaduan::where('status', 'Pending')->count(),
            'laporan_diterima'           => LaporanPengaduan::where('status', 'Diterima')->count(),
            'laporan_dalam_investigasi'  => LaporanPengaduan::where('status', 'Dalam_Investigasi')->count(),
            'laporan_selesai'            => LaporanPengaduan::where('status', 'Selesai')->count(),
            'semuaTim'                   => TimInvestigasi::count(),
            'surat_tugas_aktif'          => SuratTugas::where('status_surat', 'Aktif')->count(),
        ];
        $query = LaporanPengaduan::with('user')->orderByDesc('created_at');
        if ($request->filled('status')) {
            $raw = $request->string('status')->toString();
            $key = strtolower(str_replace(['_', '-'], ' ', $raw));
            $map = [
                'pending'             => 'Pending',
                'diterima'            => 'Diterima',
                'dalam investigasi'   => 'Dalam_Investigasi',
                'selesai'             => 'Selesai',
                'ditolak'             => 'Ditolak',
            ];
            if (isset($map[$key])) {
                $query->where('status', $map[$key]);
            }
        }
        $start = $request->date('start_date'); // Carbon|null
        $end   = $request->date('end_date');   // Carbon|null

        if ($start && $end) {
            $startDate = $start->copy()->startOfDay()->toDateTimeString();
            $endDate   = $end->copy()->endOfDay()->toDateTimeString();

            $query->where(function ($q) use ($start, $end, $startDate, $endDate) {
                // 1) record yang punya tanggal_pengaduan
                $q->whereBetween('tanggal_pengaduan', [$start->toDateString(), $end->toDateString()])
                    // 2) ATAU record tanpa tanggal_pengaduan -> pakai created_at
                    ->orWhere(function ($qq) use ($startDate, $endDate) {
                        $qq->whereNull('tanggal_pengaduan')
                            ->whereBetween('created_at', [$startDate, $endDate]);
                    });
            });
        } elseif ($start) {
            $startDate = $start->copy()->startOfDay()->toDateTimeString();
            $query->where(function ($q) use ($start, $startDate) {
                $q->whereDate('tanggal_pengaduan', '>=', $start->toDateString())
                    ->orWhere(function ($qq) use ($startDate) {
                        $qq->whereNull('tanggal_pengaduan')
                            ->where('created_at', '>=', $startDate);
                    });
            });
        } elseif ($end) {
            $endDate = $end->copy()->endOfDay()->toDateTimeString();
            $query->where(function ($q) use ($end, $endDate) {
                $q->whereDate('tanggal_pengaduan', '<=', $end->toDateString())
                    ->orWhere(function ($qq) use ($endDate) {
                        $qq->whereNull('tanggal_pengaduan')
                            ->where('created_at', '<=', $endDate);
                    });
            });
        }
        $laporan = $query->paginate(12)->withQueryString();

        return view('sekretaris.laporan', compact('stats', 'laporan'));
    }


    public function tim()
    {
        try {

            $dataTim = TimInvestigasi::with(['ketuaTim', 'anggotaAktif', 'laporanPengaduan'])->latest()->get();
            $totalTim = TimInvestigasi::count();
            $timAktif = TimInvestigasi::aktif()->count();
            $dalamInvestigasi = TimInvestigasi::aktif()
                ->whereHas('laporanPengaduan', function ($query) {
                    $query->where('status_tim', 'Dalam Investigasi');
                })->count();
            $kasusSelesai = TimInvestigasi::whereHas('laporanPengaduan', function ($query) {
                $query->where('status_tim', 'Selesai');
            })->count();


            $timList = TimInvestigasi::with([
                'ketuaTim',
                'anggotaAktif',
                'laporanPengaduan'
            ])->latest()->get();



            $pegawaiList = User::where('role', 'Pegawai')->get()->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'user_id' => $user->user_id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'jabatan' => $user->jabatan
                ];
            });;
            $laporanList = LaporanPengaduan::where('status', '!=', 'Selesai')
                ->whereDoesntHave('timInvestigasi')
                ->get(['laporan_id as id', 'permasalahan']);

            return view('sekretaris.investigasi', compact(
                'totalTim',
                'timAktif',
                'dalamInvestigasi',
                'kasusSelesai',
                'timList',
                'pegawaiList',
                'laporanList',
                'dataTim'
            ));
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
    public function laporanTugas(Request $request, LaporanPengaduan $laporan)
    {
        $user = auth()->user();

        // daftar laporan tugas milik user (biarkan seperti sebelumnya)
        $laporanList = $user->laporanTugas()
            ->with([
                'suratTugas.timInvestigasi',
                'suratTugas.laporanPengaduan'
            ])
            ->latest()
            ->paginate(10);

        // ====== Base query untuk Pengaduan Selesai yang terhubung ke tim user (anggota aktif) ======
        $qBase = LaporanPengaduan::selesai()
            ->whereHas('timInvestigasi.anggota', function ($q) use ($user) {
                $q->where('anggota_tim.pegawai_id', $user->user_id)
                    ->where('anggota_tim.is_active', 1);
            });

        // total untuk metric
        $totalPengaduanSelesai = (clone $qBase)->count();

        // filter pencarian q (no_pengaduan / pelapor_nama / permasalahan)
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $qBase->where(function ($qq) use ($q) {
                $qq->where('no_pengaduan', 'like', "%{$q}%")
                    ->orWhere('pelapor_nama', 'like', "%{$q}%")
                    ->orWhere('permasalahan', 'like', "%{$q}%");
            });
        }

        // data tabel (pakai paginate agar links() jalan)
        $pengaduanSelesai = $qBase
            ->orderByDesc('tanggal_pengaduan')  // fallback ke created_at kalau mau
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // metric tambahan (opsional)
        $totalLaporanTugas = $user->laporanTugas()->count();
        $laporanDraft      = $user->laporanTugas()->where('status_laporan', 'Draft')->count();
        $laporanSubmitted  = $user->laporanTugas()->where('status_laporan', 'Submitted')->count();


        // PENTING: kembalikan ke view yang sesuai dengan file Blade kamu
        return view('sekretaris.report', compact(
            'laporanList',
            'laporan',
            'pengaduanSelesai',
            'totalPengaduanSelesai',
            'totalLaporanTugas',
            'laporanDraft',
            'laporanSubmitted'
        ));
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

            return view('sekretaris.detail.tim', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
    }
  
    public function suratTugas()
    {
        $tim = TimInvestigasi::aktif()->first();
        return (view('ketua_bidang.surat',));
    }
}
