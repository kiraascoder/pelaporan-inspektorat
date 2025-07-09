<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class WargaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Statistik laporan warga
        $stats = [
            'total_laporan' => $user->laporanPengaduan()->count(),
            'laporan_pending' => $user->laporanPengaduan()->pending()->count(),
            'laporan_dalam_investigasi' => $user->laporanPengaduan()->dalamInvestigasi()->count(),
            'laporan_selesai' => $user->laporanPengaduan()->selesai()->count(),
        ];

        // Laporan terbaru
        $laporanTerbaru = $user->laporanPengaduan()
            ->latest()
            ->limit(5)
            ->get();

        return view('warga.dashboard', compact('stats', 'laporanTerbaru'));
    }

    public function index()
    {
        $user = auth()->user();

        $laporan = $user->laporanPengaduan()
            ->latest()
            ->paginate(10);

        return view('warga.laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('warga.laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'kategori' => 'required|string',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi,Urgent',
            'lokasi_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'bukti_dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 'Pending';

        // Handle file upload
        if ($request->hasFile('bukti_dokumen')) {
            $files = [];
            foreach ($request->file('bukti_dokumen') as $file) {
                $files[] = $file->store('bukti_dokumen', 'public');
            }
            $data['bukti_dokumen'] = $files;
        }

        LaporanPengaduan::create($data);

        return redirect()->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil diajukan.');
    }

    public function show(LaporanPengaduan $laporan)
    {
        // Middleware sudah handle ownership check
        return view('warga.laporan.show', compact('laporan'));
    }

    public function edit(LaporanPengaduan $laporan)
    {
        // Hanya bisa edit jika status masih pending
        if ($laporan->status !== 'Pending') {
            return redirect()->back()->with('error', 'Laporan tidak dapat diubah karena sudah diproses.');
        }

        return view('warga.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, LaporanPengaduan $laporan)
    {
        if ($laporan->status !== 'Pending') {
            return redirect()->back()->with('error', 'Laporan tidak dapat diubah karena sudah diproses.');
        }

        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'kategori' => 'required|string',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi,Urgent',
            'lokasi_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
        ]);

        $laporan->update($request->all());

        return redirect()->route('warga.laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('warga.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['nama_lengkap', 'email', 'no_telepon', 'alamat']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('warga.profile')
            ->with('success', 'Profile berhasil diperbarui.');
    }
}
