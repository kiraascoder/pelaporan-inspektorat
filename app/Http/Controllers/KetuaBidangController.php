<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use App\Models\TimInvestigasi;
use App\Models\SuratTugas;
use App\Models\LaporanTugas;
use App\Models\User;
use Illuminate\Http\Request;

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

        // Tim yang dipimpin
        $timDipimpin = $user->timInvestigasiDipimpin()
            ->with(['laporanPengaduan', 'anggotaAktif'])
            ->latest()
            ->limit(5)
            ->get();

        return view('ketua_bidang.dashboard', compact('stats', 'timDipimpin'));
    }

    public function laporanMasuk()
    {
        $laporan = LaporanPengaduan::whereIn('status', ['Pending', 'Diterima'])
            ->with(['user'])
            ->latest()
            ->paginate(10);

        return view('ketua_bidang.laporan.masuk', compact('laporan'));
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
