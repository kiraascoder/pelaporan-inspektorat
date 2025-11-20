<?php

namespace App\Http\Controllers;

use App\Models\LaporanTugas;
use App\Models\User;
use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik umum
        $user = auth()->user();

        $stats = [
            'total_laporan' => LaporanPengaduan::all()->count(),
            'laporan_pending' => LaporanPengaduan::pending()->count(),
            'laporan_dalam_investigasi' => LaporanPengaduan::dalamInvestigasi()->count(),
            'laporan_selesai' => $user->laporanPengaduan()->selesai()->count(),
        ];



        $laporanTerbaru = LaporanPengaduan::latest()->limit(5)->get();


        return view('admin.dashboard', compact('stats', 'laporanTerbaru'));
    }

    public function users(Request $request)
    {
        // ---- Stats (tanpa memuat semua row) ----
        $stats = [
            'total_user'  => User::count(),
            'user_aktif'  => User::where('is_active', 1)->count(),   // atau User::active()->count()
            'user_nonaktif' => User::where('is_active', 0)->count(), // atau User::inactive()->count()
        ];

        // ---- Filters dari query string ----
        $status = $request->query('status');   // '1' | '0' | null
        $role   = $request->query('role');     // 'Admin' | 'Pegawai' | ...
        $q      = $request->query('q');        // kata kunci
        $sort   = $request->query('sort', 'latest'); // 'latest' | 'name' | 'oldest'

        // ---- Query dasar ----
        $query = User::query()->select([
            'user_id',
            'nama_lengkap',
            'username',
            'email',
            'jabatan',
            'nip',
            'role',
            'is_active',
            'alamat',
            'created_at'
        ]);

        if ($status !== null && $status !== '') {
            $query->where('is_active', (int) $status);
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'name':
                $query->orderBy('nama_lengkap');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
        }


        $users = $query->paginate(10)->withQueryString();


        return view('admin.users', compact('stats', 'users'));
    }


    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:Admin,Pegawai,Warga,Ketua_Bidang_Investigasi',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function showUser(User $user)
    {
        $user->load(['laporanPengaduan', 'timInvestigasiDipimpin', 'timInvestigasiDiikuti']);

        return view('admin.users.show', compact('user'));
    }


    public function destroyUser(User $user)
    {
        // Jangan hapus admin terakhir
        if ($user->role === 'Admin' && User::admin()->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
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

    public function reportPegawai(Request $request)
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

        return view('admin.reports.index', compact('stats', 'laporan'));
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

    public function review()
    {
        $user = auth()->user();

        $laporanList = LaporanTugas::all();

        return view('admin.reports.index', compact('laporanList'));
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

            return view('admin.tim', compact(
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


    public function showTimInvestigasi($tim_id)
    {
        try {
            $tim = TimInvestigasi::with([
                'ketuaTim',
                'anggotaAktif',
                'laporanPengaduan',
                'suratTugas'
            ])->findOrFail($tim_id);

            return view('admin.detail.tim', compact('tim'));
        } catch (Exception $e) {
            return back()->with('error', 'Tim tidak ditemukan');
        }
    }
    public function showLaporan(LaporanPengaduan $laporan)
    {
        $laporan->load(['user', 'timInvestigasi.anggotaAktif', 'suratTugas']);

        return view('admin.laporan.show', compact('laporan'));
    }


    public function showTim(TimInvestigasi $tim)
    {
        $tim->load(['ketua', 'laporanPengaduan.user', 'anggotaAktif', 'suratTugas']);

        return view('admin.tim.show', compact('tim'));
    }

    public function suratTugas()
    {
        $suratTugas = SuratTugas::with(['timInvestigasi.ketua', 'laporanPengaduan', 'pembuat'])
            ->latest()
            ->paginate(10);

        return view('admin.surat_tugas.index', compact('suratTugas'));
    }

    public function showSuratTugas(SuratTugas $surat)
    {
        $surat->load(['timInvestigasi.anggotaAktif', 'laporanPengaduan.user', 'pembuat', 'laporanTugas.pegawai']);

        return view('admin.surat_tugas.show', compact('surat'));
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function laporanBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $data = [
            'laporan_masuk' => LaporanPengaduan::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count(),
            'laporan_selesai' => LaporanPengaduan::selesai()
                ->whereMonth('updated_at', $bulan)
                ->whereYear('updated_at', $tahun)
                ->count(),
            'tim_dibentuk' => TimInvestigasi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count(),
        ];

        return view('admin.reports.bulanan', compact('data', 'bulan', 'tahun'));
    }

    public function userDestroy($user)
    {
        try {
            User::destroy($user);
            return back()->with('success', 'User berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        }
    }
    public function userToggle(User $user)
    {
        try {
            $user->update([
                'is_active' => $user->is_active ? 0 : 1
            ]);

            return back()->with('success', 'Status user berhasil diubah.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function buatUser(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:users,nik',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:Admin,Pegawai,Warga,Ketua_Bidang_Investigasi',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }
    public function updateUser(Request $request, User $user)
    {
        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:Admin,Pegawai,Warga,Ketua_Bidang_Investigasi',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Update data user        
        $user->username = $request->username;
        $user->email = $request->email;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->no_telepon = $request->no_telepon;
        $user->alamat = $request->alamat;
        $user->role = $request->role;
        $user->nip = $request->nip;
        $user->jabatan = $request->jabatan;
        $user->is_active = $request->has('is_active') ? (bool)$request->is_active : false;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    public function detailUser(User $user)
    {
        return view('admin.detail.user', compact('user'));
    }
}
