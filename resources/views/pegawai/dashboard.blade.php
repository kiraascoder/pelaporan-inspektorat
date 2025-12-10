@extends('layouts.dashboard')

@section('title', 'Dashboard Pegawai')

@section('content')
    <div class="space-y-6">

        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg p-6 text-white" x-data="{
            greeting: (() => {
                const hour = new Date().getHours();
                if (hour < 12) return 'Selamat Pagi';
                if (hour < 15) return 'Selamat Siang';
                if (hour < 18) return 'Selamat Sore';
                return 'Selamat Malam';
            })()
        }">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2" x-text="greeting + ', {{ auth()->user()->nama_lengkap }}!'"></h2>

                    <p class="text-primary-100">Kelola tugas investigasi dan pantau progress pekerjaan Anda.</p>

                    <div class="mt-2 text-sm text-primary-200">
                        <span>NIP: {{ auth()->user()->nip }}</span> •
                        <span>{{ auth()->user()->jabatan }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Tim Aktif -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7..." />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Tim Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $stats['tim_aktif'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surat Tugas Aktif -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6..." />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Surat Tugas Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $stats['surat_tugas_aktif'] ?? 0 }}
                            </p>
                        </div>
                    </div>

                    @if (($stats['surat_tugas_aktif'] ?? 0) > 0)
                        <div class="text-yellow-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3..." />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Draft Laporan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2..." />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Draft Laporan</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $stats['laporan_tugas_draft'] ?? 0 }}
                            </p>
                        </div>
                    </div>

                    @if (($stats['laporan_tugas_draft'] ?? 0) > 0)
                        <div class="text-orange-600 text-xs font-medium">Perlu diselesaikan</div>
                    @endif
                </div>
            </div>

            <!-- Laporan Submitted -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6..." />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Laporan Submitted</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $stats['laporan_tugas_submitted'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Laporan Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Terbaru</h3>
            </div>

            <div class="p-6">

                @if (($laporanList->count() ?? 0) === 0)
                    <p class="text-sm text-gray-500 text-center py-4">
                        Belum ada laporan terbaru.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($laporanList as $laporan)
                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">

                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                </div>

                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $laporan->permasalahan ?? 'Tidak ada informasi' }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        @if ($laporan->surat_tugas_id)
                                            Surat Tugas #{{ $laporan->surat_tugas_id }}
                                        @else
                                            Belum ada surat tugas
                                        @endif
                                    </p>
                                </div>

                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full 
                                        text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Medium
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('pegawai.laporan') }}"
                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Lihat Semua Laporan
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
