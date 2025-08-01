<?php

namespace App\Http\Controllers;

use App\Models\AnggotaTim;
use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
use App\Models\LaporanTugas;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function laporan()
    {
        $stats = [
            'laporan_pending' => LaporanPengaduan::pending()->count(),
            'laporan_diterima' => LaporanPengaduan::diterima()->count(),
            'laporan_dalam_investigasi' => LaporanPengaduan::dalamInvestigasi()->count(),
            'laporan_selesai' => LaporanPengaduan::selesai()->count(),
            'semuaTim' => TimInvestigasi::count(),
            'surat_tugas_aktif' => SuratTugas::where('status_surat', 'Aktif')->count(),
        ];

        $laporan = LaporanPengaduan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(12) // Limit to show recent 12 reports
            ->get();

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
        return (view('ketua_bidang.surat'));
    }

    public function review()
    {
        return view('ketua_bidang.review');
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
            'nama_tim' => 'required|string|max:255|unique:tim_investigasi,nama_tim',
            'deskripsi_tim' => 'nullable|string|max:1000',
            'pegawai_id' => 'required|array|min:1',
            'pegawai_id.*' => 'required|exists:users,user_id',
            'ketua_tim_id' => 'required|exists:users,user_id',
            'laporan_id' => 'nullable|exists:laporan_pengaduan,laporan_id',
            'status_tim' => 'required|in:aktif,nonaktif'
        ], [
            'nama_tim.required' => 'Nama tim harus diisi',
            'nama_tim.unique' => 'Nama tim sudah digunakan',
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
                'nama_tim' => $request->nama_tim,
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

    public function suratTugas()
    {
        $user = auth()->user();

        $suratTugas = SuratTugas::where('dibuat_oleh', $user->user_id)
            ->with(['timInvestigasi', 'laporanPengaduan'])
            ->latest()
            ->paginate(10);

        return view('ketua_bidang.surat_tugas.index', compact('suratTugas'));
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
        $request->validate([
            'nomor_surat' => 'required|string|unique:surat_tugas,nomor_surat',
            'perihal' => 'required|string|max:255',
            'deskripsi_tugas' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['tim_id'] = $tim->tim_id;
        $data['laporan_id'] = $tim->laporan_id;
        $data['dibuat_oleh'] = auth()->id();
        $data['status_surat'] = 'Diterbitkan';

        SuratTugas::create($data);

        // Update status tim menjadi aktif
        $tim->update(['status_tim' => 'Aktif']);

        return redirect()->route('ketua_bidang.surat_tugas.index')
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
}
