<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use App\Models\PengajuanSuratTugas;
use App\Models\TimInvestigasi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekretarisController extends Controller
{

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
    public function generatePdf(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        if ($pengajuanSurat->status !== 'Selesai') {
            return back()->with('error', 'Surat hanya bisa dibuat jika pengajuan sudah berstatus Selesai.');
        }

        // 1. RENDER VIEW JADI HTML DULU
        $html = view('sekretaris.surat-tugas.pdf', [
            'pengajuan' => $pengajuanSurat,
        ])->render();


        // 3. Kalau HTML normal, baru generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'defaultFont'            => 'DejaVu Sans',
            'enable_font_subsetting' => false,
            'isHtml5ParserEnabled'   => true,
            'isRemoteEnabled'        => true,
        ])
            ->loadHTML($html)
            ->setPaper('A4', 'portrait');

        $filename = 'Surat_Tugas_' . ($pengajuanSurat->nomor_surat ?? $pengajuanSurat->pengajuan_surat_id) . '.pdf';

        return $pdf->download($filename);
    }
    public function uploadSuratTugas(Request $request, LaporanPengaduan $laporan)
    {
        $request->validate([
            'surat_tugas'       => 'required|mimes:pdf|max:2048',
            'pengajuan_surat_id' => 'required|exists:pengajuan_surat_tugas,pengajuan_surat_id',
        ]);

        $file = $request->file('surat_tugas');
        $path = $file->store('surat_tugas', 'public'); // hasil: 'surat_tugas/namafile.pdf'

        $laporan->update([
            'surat_tugas_file' => $path,
            'surat_tugas_id'   => $request->pengajuan_surat_id,
        ]);

        return back()->with('success', 'Surat tugas berhasil diunggah ke laporan.');
    }



    public function dashboard()
    {
        $user = auth()->user();
        return view('sekretaris.dashboard');
    }

    public function laporan(Request $request)
    {
        $stats = [
            'laporan_pending'            => LaporanPengaduan::where('status', 'Pending')->count(),
            'laporan_diterima'           => LaporanPengaduan::where('status', 'Diterima')->count(),
            'laporan_dalam_investigasi'  => LaporanPengaduan::where('status', 'Dalam_Investigasi')->count(),
            'laporan_selesai'            => LaporanPengaduan::where('status', 'Selesai')->count(),
            'semuaTim'                   => TimInvestigasi::count(),
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


    public function tim(Request $request)
    {
        try {
            // Query dasar dengan relasi
            $query = TimInvestigasi::with(['ketuaTim', 'anggotaAktif', 'laporanPengaduan']);

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    // Cari di judul laporan
                    $q->whereHas('laporanPengaduan', function ($subQ) use ($search) {
                        $subQ->where('judul', 'like', "%{$search}%")
                            ->orWhere('kategori', 'like', "%{$search}%")
                            ->orWhere('no_pengaduan', 'like', "%{$search}%");
                    })
                        // Cari di nama ketua tim
                        ->orWhereHas('ketuaTim', function ($subQ) use ($search) {
                            $subQ->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('jabatan', 'like', "%{$search}%");
                        });
                });
            }

            // Filter status tim
            if ($request->filled('status_tim')) {
                $query->where('status_tim', $request->status_tim);
            }

            // Filter status laporan
            if ($request->filled('status_laporan')) {
                $query->whereHas('laporanPengaduan', function ($q) use ($request) {
                    $q->where('status', $request->status_laporan);
                });
            }

            // Data untuk tabel dengan pagination
            $dataTim = $query->latest()->paginate(10)->withQueryString();

            // Statistik
            $totalTim = TimInvestigasi::count();

            $timAktif = TimInvestigasi::aktif()->count();

            $dalamInvestigasi = TimInvestigasi::aktif()
                ->whereHas('laporanPengaduan', function ($query) {
                    $query->where('status', 'dalam_investigasi');
                })->count();

            $kasusSelesai = TimInvestigasi::whereHas('laporanPengaduan', function ($query) {
                $query->where('status', 'selesai');
            })->count();

            // List untuk form/modal
            $timList = TimInvestigasi::with([
                'ketuaTim',
                'anggotaAktif',
                'laporanPengaduan'
            ])
                ->latest()
                ->get();

            $pegawaiList = User::where('role', 'Pegawai')->get()->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'user_id' => $user->user_id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'jabatan' => $user->jabatan
                ];
            });

            $laporanList = LaporanPengaduan::where('status', '!=', 'selesai')
                ->whereDoesntHave('timInvestigasi')
                ->get(['laporan_id as id', 'no_pengaduan']);

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

            return view('sekretaris.detail.investigasi', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
    }
    public function show(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        return view('sekretaris.detail.surat', compact('pengajuanSurat'));
    }

    public function destroy(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->delete();

        return redirect()
            ->route('kepala.surat_tugas')
            ->with('success', 'Pengajuan surat tugas berhasil dihapus.');
    }

    public function suratTugas()
    {
        $suratList = PengajuanSuratTugas::with(['laporan', 'penandatangan'])
            ->latest()
            ->get();


        $laporanList = LaporanPengaduan::latest()->get();


        $userList = User::select('user_id', 'nama_lengkap', 'jabatan', 'role')
            ->whereIn('role', ['Kepala_Dinas', 'Ketua_Bidang', 'Sekretaris'])
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('sekretaris.surat', compact(
            'suratList',
            'laporanList',
            'userList'
        ));
    }
}
