@extends('layouts.dashboard')

@section('title', 'Detail Pengajuan Surat Tugas')

@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb + Aksi --}}
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

            <div class="flex gap-2">
                <a href="{{ route('sekretaris.surat_tugas') }}"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>

        {{-- Alert Flash --}}
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

        {{-- Ringkasan Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nomor Surat</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $pengajuanSurat->nomor_surat ?? 'Belum diisi' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if ($pengajuanSurat->status === 'Pending') bg-yellow-100 text-yellow-800
                            @elseif($pengajuanSurat->status === 'Dibuat') bg-blue-100 text-blue-800
                            @elseif($pengajuanSurat->status === 'Selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $pengajuanSurat->status }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Laporan Pengaduan</p>
                        <p class="mt-1 text-gray-800">
                            #{{ $pengajuanSurat->laporan->laporan_id ?? '—' }} —
                            {{ $pengajuanSurat->laporan->judul ?? ($pengajuanSurat->laporan->permasalahan ?? '—') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Penandatangan</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->penandatangan->nama_lengkap ?? '—' }}<br>
                            <span class="text-xs text-gray-500">
                                {{ $pengajuanSurat->penandatangan->jabatan ?? ($pengajuanSurat->penandatangan->role ?? '') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->created_at?->format('d M Y H:i') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir Diperbarui</p>
                        <p class="mt-1 text-gray-800">
                            {{ $pengajuanSurat->updated_at?->format('d M Y H:i') ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-3">
                <h2 class="text-sm font-semibold text-gray-900">Set Nomor Surat & Selesaikan</h2>
                <p class="text-xs text-gray-500">
                    Dipakai oleh Sekretaris untuk mengisi nomor surat resmi dan mengubah status menjadi
                    <span class="font-semibold">Selesai</span>.
                </p>

                {{-- STATUS LOGIC --}}
                @php
                    $hasNomor = filled($pengajuanSurat->nomor_surat);
                    $hasPDF = filled($pengajuanSurat->file_surat); // ganti jika field kamu berbeda
                @endphp

                {{-- Jika PDF sudah dibuat --}}
                @if ($hasPDF)
                    <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-800">
                        Surat Tugas Sudah Dibuat
                    </div>
                    <a href="{{ asset('storage/surat_tugas/' . $pengajuanSurat->file_surat) }}"
                        class="block w-full text-center px-3 py-2 mt-2 rounded-lg text-sm border border-primary-600 text-primary-700 hover:bg-primary-50">
                        Lihat Surat Tugas
                    </a>

                    {{-- Jika nomor sudah diisi tetapi PDF belum dibuat --}}
                @elseif ($hasNomor)
                    <div class="p-3 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-800">
                        Nomor Surat Resmi Sudah Diisi
                    </div>

                    {{-- tombol generate PDF --}}
                    <a href="{{ route('sekretaris-surat.cetak-pdf', $pengajuanSurat) }}"
                        class="block w-full text-center px-3 py-2 rounded-lg mt-2 text-sm border border-primary-600 text-primary-700 hover:bg-primary-50">
                        Buat Surat Tugas (PDF)
                    </a>

                    {{-- Jika belum isi nomor → tampil form --}}
                @else
                    <form action="{{ route('sekretaris-surat.update-status', $pengajuanSurat) }}" method="POST"
                        class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-medium text-gray-700">Nomor Surat Resmi</label>
                            <input type="text" name="nomor_surat" class="mt-1 w-full rounded-md border-gray-300 text-sm"
                                placeholder="700.1/62/INSPEKTORAT"
                                value="{{ old('nomor_surat', $pengajuanSurat->nomor_surat) }}">

                            @error('nomor_surat')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-3 py-2 rounded-lg text-sm
                bg-primary-600 text-white hover:bg-primary-700">
                            Simpan Nomor & Tandai Selesai
                        </button>
                    </form>
                @endif
                <form action="{{ route('sekretaris-surat.upload-surat', $pengajuanSurat->laporan->laporan_id) }}"
                    method="POST" enctype="multipart/form-data" class="space-y-3 mt-3">
                    @csrf

                    <input type="hidden" name="pengajuan_surat_id" value="{{ $pengajuanSurat->pengajuan_surat_id }}">

                    <label class="text-xs font-medium">Upload Surat ke Laporan</label>
                    <input type="file" name="surat_tugas" accept="application/pdf">

                    <button class="px-3 py-2 bg-primary-600 text-white rounded-md text-sm">
                        Upload
                    </button>
                </form>
            </div>

        </div>

        {{-- Nama-nama yang Ditugaskan --}}
        <!-- === Mulai: Nama-nama yang Ditugaskan (ganti blok lama dengan ini) === -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-900">Nama yang Ditugaskan</h2>

                @php
                    // Ambil raw dari model
                    $rawNama = $pengajuanSurat->nama_ditugaskan ?? [];

                    // Jika disimpan sebagai string JSON di DB (atau string biasa), decode dulu
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
                    @foreach ($namaList as $idx => $item)
                        @if (is_array($item))
                            {{-- mendukung struktur: ['nama' => '...', 'jabatan' => '...'] --}}
                            <li class="flex items-baseline justify-between gap-2">
                                <div>
                                    {{ $item['nama'] ?? ($item[0] ?? '-') }}
                                    @if (!empty($item['jabatan']))
                                        <span class="text-xs text-gray-500">— {{ $item['jabatan'] }}</span>
                                    @endif
                                </div>
                            </li>
                        @else
                            {{-- item string (legacy) --}}
                            <li>{{ $item }}</li>
                        @endif
                    @endforeach
                </ol>
            @else
                <p class="text-sm text-gray-500">
                    Belum ada nama yang ditugaskan. Silakan edit pengajuan untuk menambahkan nama.
                </p>
            @endif
        </div>
        <!-- === Selesai: Nama-nama yang Ditugaskan === -->


        {{-- Opsi Danger / Hapus --}}
        <div class="bg-white rounded-xl border border-red-200/60 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-red-700">Hapus Pengajuan</h2>
                    <p class="text-xs text-red-500 mt-1">
                        Tindakan ini akan menghapus pengajuan surat tugas dari sistem. Tidak disarankan jika surat sudah
                        dibuat.
                    </p>
                </div>
                <form action="{{ route('sekretaris-surat.destroy', $pengajuanSurat) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus pengajuan ini? Tindakan ini tidak dapat dibatalkan.');">
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
