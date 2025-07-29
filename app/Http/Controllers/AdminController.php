<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
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

    public function users()
    {


        $stats = [
            'total_user' => User::all()->count(),
            'user_aktif' => User::active()->count(),
            'user_nonaktif' => User::inactive()->count(),
        ];

        $users = User::latest()->limit(5)->get();

        return view('admin.users', compact('stats', 'users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
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

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'required|in:Admin,Pegawai,Warga,Ketua_Bidang_Investigasi',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->except(['password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User berhasil diperbarui.');
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

    public function laporan()
    {
        $laporan = LaporanPengaduan::with(['user', 'timInvestigasi.ketua'])
            ->latest()
            ->paginate(10);

        return view('admin.laporane', compact('laporan'));
    }

    public function showLaporan(LaporanPengaduan $laporan)
    {
        $laporan->load(['user', 'timInvestigasi.anggotaAktif', 'suratTugas']);

        return view('admin.laporan.show', compact('laporan'));
    }

    public function tim()
    {
        $tim = TimInvestigasi::with(['ketua', 'laporanPengaduan', 'anggotaAktif'])
            ->latest()
            ->paginate(10);

        return view('admin.tim.index', compact('tim'));
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
}
