<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;



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
        $user = auth()->user();

        $kategoriPengaduan = [
            'Penyalahgunaan Wewenang',
            'Korupsi/Pungutan Liar',
            'Pelayanan Publik',
            'Kepegawaian',
            'Pengadaan Barang/Jasa',
            'Aset/Keuangan Daerah',
            'Disiplin Aparatur',
            'Bantuan Sosial/Hibah',
            'Infrastruktur/Pembangunan',
            'Lainnya',
        ];

        $validated = $request->validate([
            'no_pengaduan'       => 'nullable|string|max:100',
            'tanggal_pengaduan'  => 'nullable|date',

            'kategori_pengaduan' => ['required', Rule::in($kategoriPengaduan)],

            // Data Pelapor
            'pelapor_pekerjaan'  => 'nullable|string|max:255',

            // Data Terlapor
            'terlapor_nama'      => 'nullable|string|max:255',
            'terlapor_pekerjaan' => 'nullable|string|max:255',
            'terlapor_alamat'    => 'nullable|string|max:500',
            'terlapor_telp'      => 'nullable|string|max:50',

            // Substansi
            'permasalahan'       => 'required|string',
            'harapan'            => 'nullable|string',
            'agreement'          => 'required',

            // File bukti
            'bukti_pendukung'    => 'nullable|array',
            'bukti_pendukung.*'  => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'kategori_pengaduan.required' => 'Kategori pengaduan wajib dipilih.',
            'kategori_pengaduan.in'       => 'Kategori pengaduan tidak valid.',
            'permasalahan.required'       => 'Permasalahan wajib diisi.',
            'agreement.required'          => 'Pernyataan wajib disetujui.',
            'bukti_pendukung.*.mimes'    => 'Bukti harus PDF/JPG/JPEG/PNG/DOC/DOCX.',
            'bukti_pendukung.*.max'      => 'Bukti maksimal 10MB per file.',
        ]);

        $userId = $user->user_id ?? $user->id;
        $today  = now()->toDateString();

        $noPengaduan = $request->filled('no_pengaduan')
            ? $request->input('no_pengaduan')
            : 'PD-' . now()->format('Ymd-His') . '-' . str_pad((string) $userId, 4, '0', STR_PAD_LEFT);

        $buktiPaths = [];

        if ($request->hasFile('bukti_pendukung')) {
            foreach ($request->file('bukti_pendukung') as $file) {
                if ($file && $file->isValid()) {
                    $buktiPaths[] = $file->store('bukti_pendukung', 'public');
                }
            }
        }

        LaporanPengaduan::create([
            'user_id'             => $userId,
            'no_pengaduan'        => $noPengaduan,
            'tanggal_pengaduan'   => $request->date('tanggal_pengaduan')?->toDateString() ?? $today,
            'kategori_pengaduan'  => $validated['kategori_pengaduan'],

            // Data pelapor otomatis dari akun
            'pelapor_nama'        => $user->nama_lengkap,
            'pelapor_pekerjaan'   => $validated['pelapor_pekerjaan'] ?? null,
            'pelapor_alamat'      => $user->alamat,
            'pelapor_telp'        => $user->no_telepon,

            // Data terlapor dari form
            'terlapor_nama'       => $validated['terlapor_nama'] ?? null,
            'terlapor_pekerjaan'  => $validated['terlapor_pekerjaan'] ?? null,
            'terlapor_alamat'     => $validated['terlapor_alamat'] ?? null,
            'terlapor_telp'       => $validated['terlapor_telp'] ?? null,

            'permasalahan'        => $validated['permasalahan'],
            'harapan'             => $validated['harapan'] ?? null,
            'bukti_pendukung'     => $buktiPaths,
            'status'              => 'Pending',
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

        return view('warga.laporan', compact('stats', 'laporanTerbaru', 'user'));
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
    public function downloadFormat()
    {
        // Jika view membutuhkan data, kirimkan di sini
        $html = view('pdf.format-pengaduan')->render();

        // load view menjadi PDF
        $pdf = Pdf::loadHTML($html)
            ->setPaper('F4', 'portrait');

        // kembalikan sebagai file download
        return $pdf->download('Formulir_Pengaduan.pdf');
    }
    public function tambahBukti(Request $request, $laporan_id)
    {
        $laporan = LaporanPengaduan::findOrFail($laporan_id);

        $userId = auth()->user()->user_id ?? auth()->id();

        if ($laporan->user_id != $userId) {
            abort(403);
        }

        $request->validate([
            'bukti_pendukung'   => 'required|array',
            'bukti_pendukung.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'bukti_pendukung.required' => 'File bukti wajib dipilih.',
            'bukti_pendukung.*.mimes' => 'Bukti harus PDF/JPG/JPEG/PNG/DOC/DOCX.',
            'bukti_pendukung.*.max'   => 'Bukti maksimal 10MB per file.',
        ]);

        $buktiLama = $laporan->bukti_pendukung ?? [];
        $buktiBaru = [];

        foreach ($request->file('bukti_pendukung') as $file) {
            if ($file && $file->isValid()) {
                $buktiBaru[] = $file->store('bukti_pendukung', 'public');
            }
        }

        $laporan->update([
            'bukti_pendukung' => array_merge($buktiLama, $buktiBaru),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Bukti pengaduan berhasil ditambahkan.');
    }
}
