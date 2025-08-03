<?php

namespace App\Http\Controllers;

use App\Models\LaporanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanTugasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'temuan' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
            'status_laporan' => 'required|string|in:Draft,Submitted,Reviewed,Approved',
            'attachments.*' => 'nullable|file|max:5120',
        ]);

        $filePaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filePaths[] = $file->store('bukti_pendukung', 'public');
            }
        }

        LaporanTugas::create([
            'pegawai_id' => Auth::id(),
            'judul_laporan' => $request->judul_laporan,
            'isi_laporan' => $request->isi_laporan,
            'temuan' => $request->temuan,
            'rekomendasi' => $request->rekomendasi,
            'bukti_pendukung' => $filePaths,
            'status_laporan' => $request->status_laporan,
            'tanggal_submit' => now(),
        ]);

        return redirect()->route('pegawai.report-tugas')->with('success', 'Laporan berhasil disimpan.');
    }
    public function show($id)
    {
        $user = auth()->user();


        $laporan = LaporanTugas::with([
            'suratTugas.timInvestigasi',
            'suratTugas.laporanPengaduan',
            'pegawai'
        ])
            ->where('pegawai_id', $user->user_id)
            ->findOrFail($id);

        return view('pegawai.detail.report-tugas', compact('laporan'));
    }
}
