<?php

namespace App\Http\Controllers;

use App\Models\AnggotaTim;
use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
use App\Models\LaporanTugas;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class KetuaBidangController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'laporan_pending' => LaporanPengaduan::pending()->count(),
            'tim_dipimpin' => $user->timInvestigasiDipimpin()->count(),
            'tim_aktif' => $user->timInvestigasiDipimpin()->aktif()->count(),
            'surat_tugas_aktif' => SuratTugas::where('dibuat_oleh', $user->user_id)
                ->dalamPelaksanaan()
                ->count(),
        ];


        $timDipimpin = $user->timInvestigasiDipimpin()
            ->with(['laporanPengaduan', 'anggotaAktif'])
            ->latest()
            ->limit(5)
            ->get();

        return view('ketua_bidang.dashboard', compact('stats', 'timDipimpin'));
    }

    public function laporan(Request $request)
    {
        $stats = [
            'laporan_pending' => LaporanPengaduan::pending()->count(),
            'laporan_diterima' => LaporanPengaduan::diterima()->count(),
            'laporan_dalam_investigasi' => LaporanPengaduan::dalamInvestigasi()->count(),
            'laporan_selesai' => LaporanPengaduan::selesai()->count(),
            'semuaTim' => TimInvestigasi::count(),
            'surat_tugas_aktif' => SuratTugas::where('status_surat', 'Aktif')->count(),
        ];

        $query = LaporanPengaduan::query();

        // Filter berdasarkan tanggal kejadian
        if ($request->filled('start_date')) {
            $query->where('tanggal_kejadian', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_kejadian', '<=', $request->end_date);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $laporan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Preserve query parameters in pagination
        $laporan->appends($request->query());

        return view('ketua_bidang.laporan', compact('stats', 'laporan'));
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

            // Get laporan list for modal
            $laporanList = LaporanPengaduan::where('status', '!=', 'Selesai')
                ->whereDoesntHave('timInvestigasi')
                ->get(['laporan_id as id', 'judul_laporan']);

            return view('ketua_bidang.tim', compact(
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

    public function surat()
    {
        $tim = TimInvestigasi::aktif()->first();        
        return (view('ketua_bidang.surat',));
    }

    public function review()
    {
        $user = auth()->user();

        $laporanList = LaporanTugas::all();

        return view('ketua_bidang.review', compact('laporanList'));
    }

    public function show(LaporanPengaduan $laporan)
    {
        return view('ketua_bidang.detail.laporan', compact('laporan'));
    }

    public function showLaporan(LaporanPengaduan $laporan)
    {
        $laporan->load(['user', 'timInvestigasi.anggotaAktif']);

        return view('ketua_bidang.laporan.show', compact('laporan'));
    }

    public function terimaDanTolakLaporan(Request $request, LaporanPengaduan $laporan)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak',
            'keterangan_admin' => 'nullable|string',
        ]);

        $status = $request->action === 'terima' ? 'Diterima' : 'Ditolak';

        $laporan->update([
            'status' => $status,
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        $message = $request->action === 'terima' ?
            'Laporan berhasil diterima.' :
            'Laporan telah ditolak.';

        return redirect()->route('ketua_bidang.laporan.show', $laporan)
            ->with('success', $message);
    }

    public function timInvestigasi()
    {
        $user = auth()->user();

        $tim = $user->timInvestigasiDipimpin()
            ->with(['laporanPengaduan', 'anggotaAktif'])
            ->latest()
            ->paginate(10);

        return view('ketua_bidang.tim.index', compact('tim'));
    }

    public function createTim(LaporanPengaduan $laporan)
    {
        if ($laporan->status !== 'Diterima') {
            return redirect()->back()->with('error', 'Laporan belum diterima atau sudah memiliki tim.');
        }

        if ($laporan->timInvestigasi) {
            return redirect()->back()->with('error', 'Laporan sudah memiliki tim investigasi.');
        }

        $pegawai = User::pegawai()->active()->get();

        return view('ketua_bidang.tim.create', compact('laporan', 'pegawai'));
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'deskripsi_tim' => 'nullable|string|max:1000',
            'pegawai_id' => 'required|array|min:1',
            'pegawai_id.*' => 'required|exists:users,user_id',
            'ketua_tim_id' => 'required|exists:users,user_id',
            'laporan_id' => 'nullable|exists:laporan_pengaduan,laporan_id',
            'status_tim' => 'required|in:aktif,nonaktif'
        ], [
            'pegawai_id.required' => 'Minimal pilih satu anggota tim',
            'pegawai_id.min' => 'Minimal pilih satu anggota tim',
            'ketua_tim_id.required' => 'Ketua tim harus dipilih',
            'ketua_tim_id.exists' => 'Ketua tim tidak valid'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi data');
        }


        if (!in_array($request->ketua_tim_id, $request->pegawai_id)) {
            return back()
                ->withInput()
                ->with('error', 'Ketua tim harus dipilih dari anggota tim');
        }

        DB::beginTransaction();

        try {
            // Create tim investigasi
            $timInvestigasi = TimInvestigasi::create([

                'deskripsi_tim' => $request->deskripsi_tim,
                'ketua_tim_id' => $request->ketua_tim_id,
                'laporan_id' => $request->laporan_id,
                'status_tim' => ucfirst($request->status_tim)
            ]);

            // Add anggota tim
            foreach ($request->pegawai_id as $pegawaiId) {
                $roleInTeam = ($pegawaiId == $request->ketua_tim_id) ? 'Ketua' : 'Anggota';

                AnggotaTim::create([
                    'tim_id' => $timInvestigasi->tim_id,
                    'pegawai_id' => $pegawaiId,
                    'role_dalam_tim' => $roleInTeam,
                    'tanggal_bergabung' => now(),
                    'is_active' => true
                ]);
            }

            if ($request->laporan_id) {
                $laporan = LaporanPengaduan::find($request->laporan_id);
                if ($laporan && $laporan->status_laporan !== 'Dalam Investigasi') {
                    $laporan->update(['status_laporan' => 'Dalam Investigasi']);
                }
            }

            DB::commit();

            return redirect()->route('ketua_bidang.tim')
                ->with('success', 'Tim investigasi berhasil dibuat');
        } catch (Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
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

            return view('ketua_bidang.detail.tim', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
    }


    public function storeTim(Request $request, LaporanPengaduan $laporan)
    {
        $request->validate([
            'nama_tim' => 'required|string|max:255',
            'deskripsi_tim' => 'nullable|string',
            'anggota' => 'required|array|min:1',
            'anggota.*' => 'exists:users,user_id',
        ], [
            'anggota.required' => 'Minimal pilih satu anggota tim',
            'anggota.min' => 'Minimal pilih satu anggota tim',
            'anggota.*.exists' => 'Anggota tim tidak valid',
        ]);

        // Buat tim investigasi
        $tim = TimInvestigasi::create([
            'laporan_id' => $laporan->laporan_id,
            'ketua_tim_id' => auth()->id(),
            'nama_tim' => $request->nama_tim,
            'deskripsi_tim' => $request->deskripsi_tim,
            'status_tim' => 'Dibentuk',
        ]);

        // Tambahkan anggota tim
        foreach ($request->anggota as $pegawaiId) {
            $tim->anggota()->attach($pegawaiId, [
                'role_dalam_tim' => 'Anggota',
                'tanggal_bergabung' => now(),
                'is_active' => true,
            ]);
        }

        // Update status laporan
        $laporan->update(['status' => 'Dalam_Investigasi']);

        return redirect()->route('ketua_bidang.tim.show', $tim)
            ->with('success', 'Tim investigasi berhasil dibentuk.');
    }

    public function showTim(TimInvestigasi $tim)
    {
        // Middleware sudah handle access check
        $tim->load(['laporanPengaduan.user', 'anggotaAktif', 'suratTugas']);

        return view('ketua_bidang.tim.show', compact('tim'));
    }

    public function editTim(TimInvestigasi $tim)
    {
        $pegawai = User::pegawai()->active()->get();
        $tim->load('anggotaAktif');

        return view('ketua_bidang.tim.edit', compact('tim', 'pegawai'));
    }

    public function updateTim(Request $request, TimInvestigasi $tim)
    {
        $request->validate([
            'nama_tim' => 'required|string|max:255',
            'deskripsi_tim' => 'nullable|string',
            'status_tim' => 'required|in:Dibentuk,Aktif,Selesai',
        ]);

        $tim->update($request->only(['nama_tim', 'deskripsi_tim', 'status_tim']));

        return redirect()->route('ketua_bidang.tim.show', $tim)
            ->with('success', 'Tim investigasi berhasil diperbarui.');
    }


    public function createSuratTugas(TimInvestigasi $tim)
    {
        if (!in_array($tim->status_tim, ['Dibentuk', 'Aktif'])) {
            return redirect()->back()->with('error', 'Tim sudah selesai, tidak bisa membuat surat tugas.');
        }

        return view('ketua_bidang.surat_tugas.create', compact('tim'));
    }

    public function storeSuratTugas(Request $request, TimInvestigasi $tim)
    {
        $validated = $request->validate([
            'nomor_surat'     => 'required|string|max:255|unique:surat_tugas,nomor_surat',
            'tim_id'          => 'nullable|exists:tim_investigasi,tim_id',
            'laporan_id'      => 'required|exists:laporan_pengaduan,laporan_id',
            'dibuat_oleh'     => 'required|exists:users,user_id',
            'perihal'         => 'required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status_surat'    => 'nullable|in:Draft,Diterbitkan,Dalam_Pelaksanaan,Selesai',
            'catatan'         => 'nullable|string|max:5000',

            // data tampilan surat
            'tanggal_surat'   => 'required|date',
            'kota_terbit'     => 'nullable|string|max:255',
            'jabatan_ttd'     => 'nullable|string|max:255',
            'nama_ttd'        => 'nullable|string|max:255',
            'pangkat_ttd'     => 'nullable|string|max:255',
            'nip_ttd'         => 'nullable|string|max:255',
            'lokasi'          => 'nullable|string|max:255',

            // list
            'dasar'           => 'nullable|array',
            'dasar.*'         => 'nullable|string',
            'untuk'           => 'nullable|array',
            'untuk.*'         => 'nullable|string',
            'tembusan'        => 'nullable|array',
            'tembusan.*'      => 'nullable|string',
            'anggota'         => 'nullable|array',
            'anggota.nama'    => 'nullable|array',
            'anggota.jabatan' => 'nullable|array',
        ]);

        // Bersihkan array kosong
        $dasar    = collect($request->input('dasar', []))->filter(fn($v) => filled($v))->values()->all();
        $untuk    = collect($request->input('untuk', []))->filter(fn($v) => filled($v))->values()->all();
        $tembusan = collect($request->input('tembusan', []))->filter(fn($v) => filled($v))->values()->all();

        // Gabungkan anggota (nama & jabatan) per indeks
        $anggotaIn = $request->input('anggota', []);
        $namaList  = collect($anggotaIn['nama'] ?? []);
        $jabList   = collect($anggotaIn['jabatan'] ?? []);
        $max = max($namaList->count(), $jabList->count());
        $anggota = ['nama' => [], 'jabatan' => []];
        for ($i = 0; $i < $max; $i++) {
            $nm = trim((string)($namaList[$i] ?? ''));
            $jb = trim((string)($jabList[$i] ?? ''));
            if ($nm !== '' || $jb !== '') {
                $anggota['nama'][]    = $nm;
                $anggota['jabatan'][] = $jb;
            }
        }

        $surat = DB::transaction(function () use ($validated, $dasar, $untuk, $tembusan, $anggota) {
            $surat = SuratTugas::create([
                'nomor_surat'     => $validated['nomor_surat'],
                'tim_id'          => $validated['tim_id'] ?? null,
                'laporan_id'      => $validated['laporan_id'],
                'dibuat_oleh'     => $validated['dibuat_oleh'],
                'perihal'         => $validated['perihal'],
                'deskripsi_tugas' => $validated['deskripsi_tugas'] ?? null,
                'tanggal_mulai'   => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'status_surat'    => $validated['status_surat'] ?? 'Draft',
                'catatan'         => $validated['catatan'] ?? null,

                'tanggal_surat'   => $validated['tanggal_surat'],
                'kota_terbit'     => $validated['kota_terbit'] ?? null,
                'jabatan_ttd'     => $validated['jabatan_ttd'] ?? null,
                'nama_ttd'        => $validated['nama_ttd'] ?? null,
                'pangkat_ttd'     => $validated['pangkat_ttd'] ?? null,
                'nip_ttd'         => $validated['nip_ttd'] ?? null,
                'lokasi'          => $validated['lokasi'] ?? null,

                'dasar'           => $dasar,
                'untuk'           => $untuk,
                'tembusan'        => $tembusan,
                'anggota'         => $anggota,
            ]);

            // Opsional: kalau skema kamu punya tim_investigasi.surat_id dan tim dipilih, sync satu arah
            if (!empty($validated['tim_id']) && Schema::hasColumn('tim_investigasi', 'surat_id')) {
                TimInvestigasi::where('tim_id', $validated['tim_id'])
                    ->whereNull('surat_id')
                    ->update(['surat_id' => $surat->surat_id]);
            }

            return $surat;
        });


        return redirect()->route('ketua_bidang.surat', $surat->surat_id)
            ->with('success', 'Surat tugas berhasil dibuat.');
    }

    public function showSuratTugas(SuratTugas $surat)
    {
        $surat->load(['timInvestigasi.anggotaAktif', 'laporanPengaduan', 'laporanTugas.pegawai']);

        return view('ketua_bidang.surat_tugas.show', compact('surat'));
    }

    public function laporanTugasReview()
    {
        $user = auth()->user();

        $laporanTugas = LaporanTugas::whereHas('suratTugas', function ($query) use ($user) {
            $query->where('dibuat_oleh', $user->user_id);
        })
            ->whereIn('status_laporan', ['Submitted', 'Reviewed'])
            ->with(['pegawai', 'suratTugas.timInvestigasi'])
            ->latest()
            ->paginate(10);

        return view('ketua_bidang.laporan_tugas.review', compact('laporanTugas'));
    }

    public function reviewLaporanTugas(Request $request, LaporanTugas $laporanTugas)
    {
        $request->validate([
            'action' => 'required|in:approve,revise',
            'catatan' => 'nullable|string',
        ]);

        $status = $request->action === 'approve' ? 'Approved' : 'Reviewed';

        $laporanTugas->update(['status_laporan' => $status]);

        $message = $request->action === 'approve' ?
            'Laporan tugas berhasil disetujui.' :
            'Laporan tugas perlu direvisi.';

        return redirect()->route('ketua_bidang.laporan_tugas.review')
            ->with('success', $message);
    }
    public function downloadPdf(\App\Models\SuratTugas $suratTugas)
    {

        $suratTugas->load([
            'timInvestigasi.ketua',    // pastikan relasi ketua didefinisikan di model TimInvestigasi
            'laporan',
            'pembuat',
        ]);

        // Atur locale tanggal Indonesia (opsional)
        Carbon::setLocale('id');

        $pdf = Pdf::loadView('template.surat_tugas', [
            'surat' => $suratTugas,
            'now'   => Carbon::now(),
        ])->setPaper('A4', 'portrait');

        $filename = 'Surat_Tugas_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $suratTugas->nomor_surat) . '.pdf';
        return $pdf->download($filename);
    }
}
