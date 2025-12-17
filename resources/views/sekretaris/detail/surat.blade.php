@extends('layouts.dashboard')

@section('title', 'Detail Pengajuan Surat Tugas')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan Surat Tugas</h1>
                <p class="text-gray-600 text-sm">
                    Pengajuan untuk laporan:
                    <span class="font-semibold text-gray-800">
                        {{ $pengajuanSurat->laporan->judul ?? ($pengajuanSurat->laporan->permasalahan ?? '—') }}
                    </span>
                </p>
            </div>

            <a href="{{ route('sekretaris.surat_tugas') }}"
                class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                ← Kembali
            </a>
        </div>

        {{-- ================= FLASH MESSAGE ================= --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= RINGKASAN UTAMA ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- ===== KIRI ===== --}}
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Nomor Surat</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $pengajuanSurat->nomor_surat ?? 'Belum dibuat' }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-500 uppercase">Status</p>
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                        @if ($pengajuanSurat->status === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif ($pengajuanSurat->status === 'Selesai') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-700 @endif">
                            {{ $pengajuanSurat->status }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Laporan Pengaduan</p>
                        <p class="mt-1 text-gray-800">
                            #{{ $pengajuanSurat->laporan->laporan_id ?? '—' }} —
                            {{ $pengajuanSurat->laporan->judul ?? ($pengajuanSurat->laporan->permasalahan ?? '—') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Penandatangan</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->penandatangan->nama_lengkap ?? '—' }}<br>
                            <span class="text-xs text-gray-500">
                                {{ $pengajuanSurat->penandatangan->jabatan ?? ($pengajuanSurat->penandatangan->role ?? '') }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Dibuat</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->created_at?->format('d M Y H:i') ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Terakhir Diperbarui</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->updated_at?->format('d M Y H:i') ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ===== KANAN: AKSI SURAT ===== --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-semibold text-gray-900">Surat Tugas</h2>

                @if (!$pengajuanSurat->surat_tugas_path)
                    <div class="p-3 rounded-lg bg-yellow-50 border border-yellow-200 text-sm text-yellow-800">
                        Surat tugas belum dibuat.
                        Nomor surat akan dibuat otomatis saat PDF digenerate.
                    </div>

                    <a href="{{ route('sekretaris-surat.cetak-pdf', $pengajuanSurat) }}"
                        class="w-full inline-flex justify-center items-center px-3 py-2 rounded-lg text-sm
                          bg-primary-600 text-white hover:bg-primary-700 transition">
                        📝 Buat Surat Tugas (PDF)
                    </a>
                @else
                    <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-800">
                        Surat tugas sudah dibuat dengan nomor:
                        <div class="font-semibold mt-1">
                            {{ $pengajuanSurat->nomor_surat }}
                        </div>
                    </div>

                    <a href="{{ asset('storage/' . $pengajuanSurat->surat_tugas_path) }}"
                        class="w-full inline-flex justify-center items-center px-3 py-2 rounded-lg text-sm
                          border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        📥 Download Surat Tugas
                    </a>
                @endif
            </div>
        </div>

        {{-- ================= NAMA-NAMA YANG DITUGASKAN ================= --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-900">Nama-nama yang Ditugaskan</h2>

                @php
                    $rawNama = $pengajuanSurat->nama_ditugaskan ?? [];

                    if (is_string($rawNama)) {
                        $decoded = json_decode($rawNama, true);
                        $namaList = is_array($decoded) ? $decoded : [$rawNama];
                    } elseif (is_null($rawNama)) {
                        $namaList = [];
                    } else {
                        $namaList = is_array($rawNama) ? $rawNama : [];
                    }

                    $totalNama = count($namaList);
                @endphp

                @if ($totalNama > 0)
                    <span class="text-xs text-gray-500">Total: {{ $totalNama }} orang</span>
                @endif
            </div>

            @if ($totalNama > 0)
                <ol class="list-decimal list-inside space-y-1 text-sm text-gray-800">
                    @foreach ($namaList as $item)
                        @if (is_array($item))
                            <li>
                                {{ $item['nama'] ?? ($item[0] ?? '-') }}
                                @if (!empty($item['jabatan']))
                                    <span class="text-xs text-gray-500">— {{ $item['jabatan'] }}</span>
                                @endif
                            </li>
                        @else
                            <li>{{ $item }}</li>
                        @endif
                    @endforeach
                </ol>
            @else
                <p class="text-sm text-gray-500">
                    Belum ada nama yang ditugaskan.
                </p>
            @endif
        </div>

        {{-- ================= HAPUS PENGAJUAN ================= --}}
        <div class="bg-white rounded-xl border border-red-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-red-700">Hapus Pengajuan</h2>
                    <p class="text-xs text-red-500 mt-1">
                        Tindakan ini akan menghapus pengajuan surat tugas dari sistem.
                    </p>
                </div>

                <form action="{{ route('sekretaris-surat.destroy', $pengajuanSurat) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-2 text-sm rounded-lg border border-red-400 text-red-700 hover:bg-red-50">
                        Hapus Pengajuan
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
