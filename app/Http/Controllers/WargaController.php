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

        $totalLaporan = LaporanPengaduan::where('user_id', auth()->id())->count();
        $laporanPending = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Pending')->count();
        $laporanDalamInvestigasi = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Dalam Investigasi')->count();
        $laporanSelesai = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Selesai')->count();

        $stats = [
            'total_laporan' => $totalLaporan,
            'laporan_pending' => $laporanPending,
            'laporan_dalam_investigasi' => $laporanDalamInvestigasi,
            'laporan_selesai' => $laporanSelesai,
        ];


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
            'no_pengaduan'       => 'nullable|string|max:100',
            'tanggal_pengaduan'  => 'nullable|date',

            // Data Pelapor
            'pelapor_nama'       => 'required|string|max:255',
            'pelapor_pekerjaan'  => 'nullable|string|max:255',
            'pelapor_alamat'     => 'nullable|string|max:500',
            'pelapor_telp'       => 'nullable|string|max:50',

            // Data Terlapor
            'terlapor_nama'      => 'nullable|string|max:255',
            'terlapor_pekerjaan' => 'nullable|string|max:255',
            'terlapor_alamat'    => 'nullable|string|max:500',
            'terlapor_telp'      => 'nullable|string|max:50',

            // Substansi
            'permasalahan'       => 'required|string',
            'harapan'            => 'nullable|string',

            // File bukti (multiple)
            'bukti_pendukung'    => 'nullable',
            'bukti_pendukung.*'  => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // maks 10MB
        ], [
            'pelapor_nama.required'  => 'Nama pelapor wajib diisi.',
            'permasalahan.required'  => 'Permasalahan wajib diisi.',
            'bukti_pendukung.*.mimes' => 'Bukti harus PDF/JPG/JPEG/PNG/DOC/DOCX.',
            'bukti_pendukung.*.max'  => 'Bukti maksimal 10MB per file.',
        ]);

        // Siapkan data
        $userId = auth()->id(); // pastikan model User punya $primaryKey = 'user_id' jika kolomnya user_id
        $today  = now()->toDateString();

        // Auto generate no_pengaduan jika kosong (opsional)
        $noPengaduan = $request->filled('no_pengaduan')
            ? $request->string('no_pengaduan')->toString()
            : 'PD-' . now()->format('Ymd-His') . '-' . str_pad((string)$userId, 4, '0', STR_PAD_LEFT);

        // Upload bukti (bisa 0..n file)
        $buktiPaths = [];
        if ($request->hasFile('bukti_pendukung')) {
            foreach ((array) $request->file('bukti_pendukung') as $file) {
                if ($file && $file->isValid()) {
                    $buktiPaths[] = $file->store('bukti_pendukung', 'public');
                }
            }
        }

        // Simpan
        LaporanPengaduan::create([
            'user_id'            => $userId,
            'no_pengaduan'       => $noPengaduan,
            'tanggal_pengaduan'  => $request->date('tanggal_pengaduan')?->toDateString() ?? $today,
            'pelapor_nama'       => $request->string('pelapor_nama'),
            'pelapor_pekerjaan'  => $request->string('pelapor_pekerjaan'),
            'pelapor_alamat'     => $request->string('pelapor_alamat'),
            'pelapor_telp'       => $request->string('pelapor_telp'),
            'terlapor_nama'      => $request->string('terlapor_nama'),
            'terlapor_pekerjaan' => $request->string('terlapor_pekerjaan'),
            'terlapor_alamat'    => $request->string('terlapor_alamat'),
            'terlapor_telp'      => $request->string('terlapor_telp'),
            'permasalahan'       => $request->input('permasalahan'),
            'harapan'            => $request->input('harapan'),
            'bukti_pendukung'    => $buktiPaths, // cast ke array di model            
            'status'             => 'Pending',
        ]);

        return redirect()
            ->route('warga.laporan')
            ->with('success', 'Laporan berhasil diajukan.');
    }


    public function show(LaporanPengaduan $laporan)
    {
        return view('warga.detail-laporan', compact('laporan'));
    }

    public function edit(LaporanPengaduan $laporan)
    {

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
        return view(' ', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['nama_lengkap', 'email', 'no_telepon', 'alamat', 'username']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('warga.profile')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    public function laporanView()
    {
        $user = auth()->user();

        $totalLaporan = LaporanPengaduan::where('user_id', auth()->id())->count();
        $laporanPending = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Pending')->count();
        $laporanDalamInvestigasi = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Dalam Investigasi')->count();
        $laporanSelesai = LaporanPengaduan::where('user_id', auth()->id())->where('status', 'Selesai')->count();

        $stats = [
            'total_laporan' => $totalLaporan,
            'laporan_pending' => $laporanPending,
            'laporan_dalam_investigasi' => $laporanDalamInvestigasi,
            'laporan_selesai' => $laporanSelesai,
        ];


        $laporanTerbaru = LaporanPengaduan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);


        return view('warga.laporan', compact('stats', 'laporanTerbaru'));
    }

    public function profileView()
    {
        $user = auth()->user();
        return view('warga.profile', compact('user'));
    }


    public function deleteLaporan($id)
    {
        $laporan = LaporanPengaduan::find($id);
        $laporan->delete();
        return redirect()->route('warga.laporan')->with('success', 'Laporan berhasil dihapus.');
    }
}
