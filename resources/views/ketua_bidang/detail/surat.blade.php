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

            <div class="flex gap-2 items-center">
                <a href="{{ route('ketua-bidang-surat.cetak-pdf', $pengajuanSurat) }}"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    📥 Download Surat (PDF)
                </a>

                <a href="{{ route('ketua_bidang.surat') }}"
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
        <div class="grid grid-cols-1  gap-4">
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
        </div>

        {{-- Nama-nama yang Ditugaskan --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Nama yang Ditugaskan</h2>

                    {{-- Siapkan variable PHP untuk menangani berbagai format --}}
                    @php
                        // Ambil nilai raw
                        $rawNama = $pengajuanSurat->nama_ditugaskan ?? [];

                        // Jika kolom disimpan sebagai string JSON di DB, decode dulu
                        if (is_string($rawNama)) {
                            $decoded = json_decode($rawNama, true);
                            $namaList = is_array($decoded) ? $decoded : [$rawNama];
                        } else {
                            $namaList = is_array($rawNama) ? $rawNama : [];
                        }

                        // Hitung total (aman meski item berupa objek/array/string)
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
                                {{-- item bisa dalam bentuk associative ['nama' => ..., 'jabatan' => ...] --}}
                                <li class="flex items-baseline justify-between gap-2">
                                    <div>
                                        {{ $item['nama'] ?? ($item[0] ?? '-') }}
                                        @if (!empty($item['jabatan']))
                                            <span class="text-xs text-gray-500">— {{ $item['jabatan'] }}</span>
                                        @endif
                                    </div>
                                </li>
                            @else
                                {{-- item adalah string (legacy) --}}
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

            {{-- Deskripsi Umum / Untuk --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">
                    Deskripsi Umum / Poin "Untuk"
                </h2>

                @php
                    $deskripsiLines = preg_split("/\r\n|\n|\r/", $pengajuanSurat->deskripsi_umum ?? '');
                    $deskripsiLines = array_filter($deskripsiLines, fn($line) => trim($line) !== '');
                @endphp

                @if (!empty($deskripsiLines))
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-800">
                        @foreach ($deskripsiLines as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">
                        Belum ada deskripsi umum. Deskripsi ini akan menjadi poin-poin "Untuk" di surat tugas.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
