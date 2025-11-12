<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengaduan;
use Illuminate\Http\Request;

class LaporanPengaduanController extends Controller
{
    public function updateStatus(Request $request, LaporanPengaduan $laporan)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diterima,Dalam_Investigasi,Selesai,Ditolak',
            'keterangan_admin' => 'nullable|string|max:1000',
        ]);

        $laporan->update($validated);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
