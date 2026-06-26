<?php

namespace App\Http\Controllers;

use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenandatanganController extends Controller
{
    public function index()
    {
        $penandatangan = Penandatangan::orderBy('urutan')
            ->orderBy('jabatan')
            ->paginate(10);

        return view('ketua_bidang.penandatangan.index', compact('penandatangan'));
    }

    public function create()
    {
        return view('ketua_bidang.penandatangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'ttd_image' => 'nullable|image|mimes:png|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['urutan'] = $validated['urutan'] ?? 0;

        if ($request->hasFile('ttd_image')) {
            $validated['ttd_image'] = $request->file('ttd_image')->store('penandatangan', 'public');
        }

        \App\Models\Penandatangan::create($validated);

        return redirect()
            ->route('ketua_bidang.penandatangan.index')
            ->with('success', 'Data penandatangan berhasil ditambahkan.');
    }

    public function edit(Penandatangan $penandatangan)
    {
        return view('ketua_bidang.penandatangan.edit', compact('penandatangan'));
    }

    public function update(Request $request, Penandatangan $penandatangan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'ttd_image' => 'nullable|image|mimes:png|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['urutan'] = $validated['urutan'] ?? 0;

        if ($request->hasFile('ttd_image')) {
            if ($penandatangan->ttd_image && Storage::disk('public')->exists($penandatangan->ttd_image)) {
                Storage::disk('public')->delete($penandatangan->ttd_image);
            }

            $validated['ttd_image'] = $request->file('ttd_image')->store('penandatangan', 'public');
        }

        $penandatangan->update($validated);

        return redirect()
            ->route('ketua_bidang.penandatangan.index')
            ->with('success', 'Data penandatangan berhasil diperbarui.');
    }

    public function destroy(Penandatangan $penandatangan)
    {
        $penandatangan->delete();

        return redirect()
            ->route('ketua_bidang.penandatangan.index')
            ->with('success', 'Data penandatangan berhasil dihapus.');
    }
}
