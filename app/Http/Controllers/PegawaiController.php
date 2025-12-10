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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $laporanList = $user->laporanTugas()
            ->latest()
            ->paginate(10);

        $stats = [
            'tim_aktif' => $user->timInvestigasiDiikuti()->aktif()->count(),
            'laporan_tugas_draft' => $user->laporanTugas()->draft()->count(),
            'laporan_tugas_submitted' => $user->laporanTugas()->submitted()->count(),
        ];


        return view('pegawai.dashboard', compact('laporanList'));
    }



    public function updateStatusLaporan(
        Request $request,
        TimInvestigasi $tim,
        LaporanPengaduan $laporan
    ) {
        $timId = $tim->id;

        $user = auth()->user();
        $idsCalon = array_values(array_filter([$user->id ?? null, $user->user_id ?? null]));

        $baseAnggotaQ = DB::table('anggota_tim')
            ->where('tim_id', $timId)
            ->where(function ($q) use ($idsCalon) {
                foreach ($idsCalon as $val) {
                    $q->orWhere('pegawai_id', $val);
                }
            });

        $pegawaiId = (clone $baseAnggotaQ)->value('pegawai_id');
        abort_unless($pegawaiId, 403, 'Akun ini belum terdaftar sebagai anggota tim ini.');

        $isKetua = (clone $baseAnggotaQ)
            ->whereRaw('LOWER(role_dalam_tim) = ?', ['ketua'])
            ->when(Schema::hasColumn('anggota_tim', 'is_active'), function ($q) {
                $q->where('is_active', 1);
            })
            ->exists();

        abort_unless($isKetua, 403, 'Hanya ketua tim yang boleh mengubah status.');

        $terhubung = DB::table('tim_investigasi')
            ->where('tim_id', $timId)
            ->where('laporan_id', $laporan->laporan_id ?? $laporan->id)
            ->when(Schema::hasColumn('tim_investigasi', 'status_penugasan'), function ($q) {
                $q->where('status_penugasan', 'Aktif');
            })
            ->exists();

        abort_unless($terhubung, 404, 'Laporan tidak terhubung ke tim ini.');

        $data = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Diterima', 'Dalam_Investigasi', 'Selesai', 'Ditolak'])],
        ]);

        $oldStatus = $laporan->status;
        $laporan->update(['status' => $data['status']]);

        return back()->with('success', "Status laporan diubah dari {$oldStatus} menjadi {$laporan->status}.");
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

        return view('pegawai.laporan', compact('stats', 'laporan'));
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
                ->get(['laporan_id as id', 'permasalahan']);

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

    public function laporanTugas(Request $request, LaporanPengaduan $laporan)
    {
        $user = auth()->user();

        // daftar laporan tugas milik user (biarkan seperti sebelumnya)
        $laporanList = $user->laporanTugas()            
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
        return view('pegawai.report-tugas', compact(
            'laporanList',
            'laporan',
            'pengaduanSelesai',
            'totalPengaduanSelesai',
            'totalLaporanTugas',
            'laporanDraft',
            'laporanSubmitted'
        ));
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
