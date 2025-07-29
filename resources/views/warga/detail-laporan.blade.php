@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Detail Laporan</h2>
                <a href="{{ route('warga.laporan') }}" class="text-sm text-green-600 hover:underline">← Kembali ke
                    Daftar laporan</a>
            </div>

            <!-- Status -->
            <div class="mb-4">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'dalam_investigasi' => 'bg-blue-100 text-blue-800',
                        'selesai' => 'bg-green-100 text-green-800',
                        'ditolak' => 'bg-red-100 text-red-800',
                    ];
                    $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm {{ $statusColor }}">
                    {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                </span>
            </div>

            <!-- Informasi Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Judul Laporan</h3>
                    <p class="text-gray-900">{{ $laporan->judul_laporan }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Kategori</h3>
                    <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $laporan->kategori) }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Prioritas</h3>
                    <p class="text-gray-900">{{ $laporan->prioritas }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Tanggal Kejadian</h3>
                    <p class="text-gray-900">
                        {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') : '-' }}
                        {{ $laporan->waktu_kejadian ? '| ' . $laporan->waktu_kejadian : '' }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Lokasi Kejadian</h3>
                    <p class="text-gray-900">{{ $laporan->lokasi_kejadian ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Isi Laporan</h3>
                    <p class="text-gray-900 whitespace-pre-line">{{ $laporan->isi_laporan }}</p>
                </div>
                @if ($laporan->pihak_terlibat)
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Pihak Yang Terlibat</h3>
                        <p class="text-gray-900 whitespace-pre-line">{{ $laporan->pihak_terlibat }}</p>
                    </div>
                @endif
                @if ($laporan->bukti_lampiran)
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Bukti Lampiran</h3>
                        <a href="{{ asset('storage/' . $laporan->bukti_lampiran) }}" target="_blank"
                            class="text-blue-600 hover:underline text-sm">Lihat/Lampiran</a>
                    </div>
                @endif
            </div>

            <!-- Created at -->
            <div class="mt-6 text-xs text-gray-500">
                Dibuat pada {{ $laporan->created_at ? \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') : '-' }}
                
            </div>
        </div>
    </div>
@endsection
