@extends('layouts.dashboard')

@section('title', 'Dashboard Warga')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold mb-1">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
                    <p class="text-green-100 text-sm">Kelola laporan Anda dengan mudah di sini.</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="text-xl font-bold">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $colors = [
                    'bg-blue-100 text-blue-800',
                    'bg-yellow-100 text-yellow-800',
                    'bg-purple-100 text-purple-800',
                    'bg-green-100 text-green-800',
                ];
                $titles = ['Total Laporan', 'Pending', 'Dalam Investigasi', 'Selesai'];
                $values = [
                    $stats['total_laporan'],
                    $stats['laporan_pending'],
                    $stats['laporan_dalam_investigasi'],
                    $stats['laporan_selesai'],
                ];
            @endphp
            @foreach ($titles as $index => $title)
                <div class="p-4 rounded-lg shadow bg-white border border-gray-200">
                    <div class="text-sm font-medium text-gray-700">{{ $title }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $values[$index] }}</div>
                </div>
            @endforeach
        </div>
        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Laporan Terbaru</h3>
                <a href="{{ route('warga.laporan') }}" class="text-sm text-green-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="p-4">
                @if ($laporanTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach ($laporanTerbaru as $laporan)
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $laporan->judul_laporan }}</h4>
                                    <p class="text-xs text-gray-500">{{ $laporan->created_at->format('d M Y H:i') }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($laporan->isi_laporan, 100) }}</p>
                                </div>
                                <div class="ml-4">
                                    <span
                                        class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">{{ ucfirst($laporan->status) }}</span>
                                    <a href="{{ route('warga.laporan.show', $laporan->laporan_id) }}"
                                        class="ml-2 text-green-600 hover:underline text-sm">Lihat</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 text-sm">
                        Belum ada laporan. <a href="#" class="text-green-600 hover:underline">Buat laporan</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800">Tips Laporan Efektif</h4>
                    <ul class="list-disc pl-4 text-sm text-green-700 mt-1 space-y-1">
                        <li>Detail kejadian secara lengkap</li>
                        <li>Tambahkan bukti jika tersedia</li>
                        <li>Tentukan waktu & lokasi</li>
                        <li>Gunakan bahasa sopan dan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
