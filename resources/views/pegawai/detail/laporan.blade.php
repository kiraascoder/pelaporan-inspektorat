@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Detail Laporan</h2>
                <a href="{{ route('pegawai.laporan') }}" class="text-sm text-green-600 hover:underline">
                    ← Kembali ke Daftar laporan
                </a>
            </div>

            {{-- Status --}}
            <div class="mb-4">
                @php
                    $statusColors = [
                        'Pending' => 'bg-yellow-100 text-yellow-800',
                        'Dalam_Investigasi' => 'bg-blue-100 text-blue-800',
                        'Selesai' => 'bg-green-100 text-green-800',
                        'Ditolak' => 'bg-red-100 text-red-800',
                    ];
                    $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm {{ $statusColor }}">
                    {{ str_replace('_', ' ', $laporan->status) }}
                </span>
            </div>

            {{-- Info ringkas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Tanggal Pengaduan</h3>
                    <p class="text-gray-900">
                        {{ optional($laporan->tanggal_pengaduan)->format('d M Y') ?? $laporan->created_at->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">No. Pengaduan</h3>
                    <p class="text-gray-900">{{ $laporan->no_pengaduan ?? '-' }}</p>
                </div>
            </div>

            {{-- Data Pelapor & Terlapor --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-4 py-2 text-sm font-semibold">DATA PELAPOR</div>
                    <div class="p-4 space-y-2 text-sm">
                        <div><span class="text-gray-500">Nama:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_nama }}</span></div>
                        <div><span class="text-gray-500">Pekerjaan/Jabatan:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_pekerjaan ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Alamat:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_alamat ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Telp/HP:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_telp ?? '-' }}</span></div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-4 py-2 text-sm font-semibold">DATA TERLAPOR</div>
                    <div class="p-4 space-y-2 text-sm">
                        <div><span class="text-gray-500">Nama:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_nama ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Pekerjaan/Jabatan:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_pekerjaan ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Alamat:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_alamat ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Telp/HP:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_telp ?? '-' }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Substansi Pengaduan --}}
            <div class="space-y-5">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Permasalahan yang Diadukan</h3>
                    <p class="text-gray-900 whitespace-pre-line">{{ $laporan->permasalahan }}</p>
                </div>

                @if (!empty($laporan->harapan))
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Harapan</h3>
                        <p class="text-gray-900 whitespace-pre-line">{{ $laporan->harapan }}</p>
                    </div>
                @endif

                {{-- Lampiran (multiple) --}}
                @php
                    $lampiran = $laporan->bukti_pendukung;
                    if (is_string($lampiran)) {
                        // jika tersimpan sebagai JSON string
                        $decoded = json_decode($lampiran, true);
                        $lampiran = is_array($decoded) ? $decoded : ($lampiran ? [$lampiran] : []);
                    } elseif (!is_array($lampiran)) {
                        $lampiran = $lampiran ? (array) $lampiran : [];
                    }
                @endphp
                @if (count($lampiran) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Bukti Pendukung</h3>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($lampiran as $i => $path)
                                <li>
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        Lampiran {{ $i + 1 }}
                                    </a>
                                    <span class="text-gray-400 ml-1">({{ basename($path) }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Keterangan Admin (opsional) --}}
            @if (!empty($laporan->keterangan_admin))
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Keterangan Admin</h3>
                    <p class="text-gray-900 whitespace-pre-line text-sm">{{ $laporan->keterangan_admin }}</p>
                </div>
            @endif

            {{-- Created at --}}
            <div class="mt-6 text-xs text-gray-500">
                Dibuat pada {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}
            </div>                        
        </div>
    </div>
@endsection
