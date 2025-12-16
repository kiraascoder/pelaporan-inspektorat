<?php

namespace App\Http\Controllers;

use App\Models\AnggotaTim;
use App\Models\LaporanPengaduan;
use App\Models\PengajuanSuratTugas;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KetuaBidangController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $stats = [
            'laporan_pending' => LaporanPengaduan::where('status', 'Pending')->count(),
            'tim_aktif' => TimInvestigasi::where('status_tim', 'Aktif')->count(),
            'surat_tugas_aktif' => PengajuanSuratTugas::where('status', 'Selesai')->count(),
            'laporan_tugas' => LaporanTugas::where('status_laporan', 'Submitted')->count(),
        ];
        $timDipimpin = $user->timInvestigasiDipimpin()
            ->with(['laporanPengaduan', 'anggotaAktif'])
            ->latest()
            ->limit(5)
            ->get();

        $timBertugas = TimInvestigasi::with(['ketuaTim', 'anggotaAktif', 'laporanPengaduan'])->latest()->limit(5)->get();


        return view('ketua_bidang.dashboard', compact('timDipimpin', 'stats', 'timBertugas'));
    }
    public function updateStatusLaporan(Request $request, $laporan)
    {
        $request->validate([
            'status' => 'required|string',
            'keterangan_admin' => 'nullable|string',
        ]);

        $laporan = LaporanPengaduan::findOrFail($laporan);

        $laporan->update([
            'status' => $request->status,
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui'
        ]);
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
            $laporanList = LaporanPengaduan::where('status', '!=', 'Selesai')
                ->whereDoesntHave('timInvestigasi')
                ->get(['laporan_id as id', 'permasalahan']);

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

    public function storeTim(Request $request, LaporanPengaduan $laporan)
    {
        $roles = ['Ketua', 'Anggota', 'Penanggung_Jawab', 'Wakil_Penanggung_Jawab', 'Pengendali_Teknis'];

        $validator = Validator::make($request->all(), [
            'nama_tim'         => 'required|string|max:255',

            // Wajib: anggota_ids[] berisi user_id dari tabel users.user_id
            'anggota_ids'      => 'required|array|min:1',
            'anggota_ids.*'    => ['required', 'distinct', 'integer', 'exists:users,user_id'],

            // Wajib: anggota_roles[] sejajar index dengan anggota_ids[]
            'anggota_roles'    => 'required|array',
            'anggota_roles.*'  => ['required', Rule::in($roles)],

            // Ketua harus dipilih
            'ketua_tim_id'     => ['required', 'integer'],

            // Opsional jika memilih dari dropdown "Laporan Terkait"
            'laporan_id'       => 'nullable|exists:laporan_pengaduan,laporan_id',
        ], [
            'anggota_ids.required'    => 'Minimal pilih satu anggota tim.',
            'anggota_ids.min'         => 'Minimal pilih satu anggota tim.',
            'anggota_ids.*.exists'    => 'Anggota tim tidak valid.',
            'anggota_ids.*.distinct'  => 'Anggota tim ada yang duplikat.',
            'anggota_roles.required'  => 'Role untuk setiap anggota wajib diisi.',
            'anggota_roles.*.in'      => 'Role anggota tidak valid.',
            'ketua_tim_id.required'   => 'Ketua tim wajib dipilih.',
        ]);

        // Cek hubungan antar field setelah rule dasar lolos
        $validator->after(function ($v) use ($request) {
            $ids   = (array) $request->input('anggota_ids', []);
            $roles = (array) $request->input('anggota_roles', []);

            // jumlah harus sama
            if (count($ids) !== count($roles)) {
                $v->errors()->add('anggota_roles', 'Jumlah role harus sama dengan jumlah anggota.');
            }

            // ketua harus salah satu dari anggota
            $ketua = $request->input('ketua_tim_id');
            if (!in_array((string)$ketua, array_map('strval', $ids), true)) {
                $v->errors()->add('ketua_tim_id', 'Ketua tim harus berasal dari anggota yang dipilih.');
            }
        });

        $validator->validate();

        // --- Buat tim ---
        $tim = TimInvestigasi::create([
            'laporan_id'    => $request->input('laporan_id', $laporan->laporan_id),
            'ketua_tim_id'  => $request->ketua_tim_id,
            'nama_tim'      => $request->nama_tim,
            'status_tim'    => $request->input('status_tim', 'Dibentuk'),
        ]);

        // --- Siapkan & attach pivot ---
        $ids        = $request->anggota_ids;       // array of user_id (users.user_id)
        $rolesInput = $request->anggota_roles;     // array of role sejajar index

        // pastikan hanya satu 'Ketua' (yang dipilih di ketua_tim_id)
        $attachData = [];
        foreach ($ids as $i => $userId) {
            $role = $rolesInput[$i] ?? 'Anggota';
            if ((string)$userId === (string)$request->ketua_tim_id) {
                $role = 'Ketua';
            }
            $attachData[$userId] = [
                'role_dalam_tim'   => $role,
                'tanggal_bergabung' => now(),
                'is_active'        => true,
            ];
        }

        // Relasi harus sesuai (lihat catatan B)
        $tim->anggota()->attach($attachData);

        // Update status laporan
        $laporan->update(['status' => 'Dalam_Investigasi']);

        return redirect()->route('ketua_bidang.tim.show', $tim)
            ->with('success', 'Tim investigasi berhasil dibentuk.');
    }

    public function showTimInvestigasi($tim_id)
    {
        try {
            $tim = TimInvestigasi::with([
                'ketuaTim',
                'anggotaAktif',
                'laporanPengaduan',
            ])->findOrFail($tim_id);

            return view('ketua_bidang.detail.tim', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
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
            'status_tim' => 'required|in:Dibentuk,Aktif,Selesai',
        ]);

        $tim->update($request->only(['nama_tim', 'status_tim']));

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
    public function suratTugas()
    {

        $suratList = PengajuanSuratTugas::with(['laporan', 'penandatangan'])
            ->latest()
            ->get();

        $tim = TimInvestigasi::aktif()->first();
        $jabatanList = [
            'Penanggung Jawab',
            'Wakil Penanggung Jawab',
            'Pengendali Teknis',
            'Ketua Tim',
            'Anggota Tim',
        ];


        $userList = User::select('user_id', 'nama_lengkap', 'jabatan', 'role')
            ->whereIn('role', ['Ketua_Bidang_Investigasi', 'Sekretaris', 'Kepala_Inspektorat'])
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $laporanList = LaporanPengaduan::select('laporan_id', 'permasalahan', 'status', 'created_at')
            ->where('status', '!=', 'Ditolak')
            ->orderByDesc('created_at')
            ->get();


        return view('ketua_bidang.surat', [
            'tim' => $tim,
            'userList' => $userList,
            'laporanList' => $laporanList,
            'suratList' => $suratList,
            'jabatanList' => $jabatanList
        ]);
    }
    public function showSurat(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        return view('ketua_bidang.detail.surat', compact('pengajuanSurat'));
    }
    public function generatePdf(PengajuanSuratTugas $pengajuanSurat)
    {
        $pengajuanSurat->load(['laporan', 'penandatangan']);

        if ($pengajuanSurat->status !== 'Selesai') {
            return back()->with('error', 'Surat hanya bisa dibuat jika pengajuan sudah berstatus Selesai.');
        }

        $html = view('sekretaris.surat-tugas.pdf', [
            'pengajuan' => $pengajuanSurat,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'defaultFont'          => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'     => true,
        ])
            ->loadHTML($html)
            ->setPaper('A4', 'portrait');

        // ✅ SANITASI NAMA FILE (GANTI / dan \)
        $safeNomor = $pengajuanSurat->nomor_surat
            ? str_replace(['/', '\\'], '-', $pengajuanSurat->nomor_surat)
            : $pengajuanSurat->pengajuan_surat_id;

        $filename = 'Surat_Tugas_' . $safeNomor . '.pdf';

        return $pdf->download($filename);
    }
}
