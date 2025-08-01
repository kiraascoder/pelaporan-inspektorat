@extends('layouts.dashboard')

@section('title', 'Detail Tim Investigasi')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Tim Investigasi</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap tim dan laporan terkait</p>
            </div>
            <a href="{{ route('ketua_bidang.tim') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Tim
            </a>
        </div>

        <!-- Status Badge -->
        <div>
            @php
                $statusConfig = [
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                    'dalam_investigasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'search'],
                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle'],
                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle'],
                ];
                $config = $statusConfig[$tim->laporanPengaduan->status] ?? [
                    'bg' => 'bg-gray-100',
                    'text' => 'text-gray-800',
                    'icon' => 'question-mark-circle',
                ];
            @endphp
            <div
                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                <div class="w-2 h-2 rounded-full bg-current mr-2"></div>
                Status: {{ ucfirst(str_replace('_', ' ', $tim->laporanPengaduan->status)) }}
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Team Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $tim->nama_tim }}</h2>
                        <p class="text-gray-600 text-sm mt-1">{{ $tim->laporanPengaduan->judul_laporan }}</p>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Informasi Laporan -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Laporan
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-600 block mb-1">Kategori</label>
                                    <p class="text-gray-900 font-medium capitalize">
                                        {{ str_replace('_', ' ', $tim->laporanPengaduan->kategori) }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-600 block mb-1">Prioritas</label>
                                    <p class="text-gray-900 font-medium">{{ $tim->laporanPengaduan->prioritas }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-600 block mb-1">Tanggal Kejadian</label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $tim->laporanPengaduan->tanggal_kejadian ? \Carbon\Carbon::parse($tim->laporanPengaduan->tanggal_kejadian)->format('d M Y') : 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Tim -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Anggota Tim ({{ count($tim->anggotaAktif) }} orang)
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-red-100 rounded-lg">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $tim->ketuaTim->nama_lengkap }}</p>
                                    <p class="text-sm text-gray-500">Ketua Tim</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @foreach ($tim->anggotaAktif as $index => $anggota)
                                @if ($anggota->nama_lengkap !== $tim->ketuaTim->nama_lengkap)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $anggota->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-500">Anggota {{ $index + 1 }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tim dibuat pada
                    {{ $tim->created_at ? \Carbon\Carbon::parse($tim->created_at)->format('d M Y, H:i') : 'Tidak tersedia' }}
                    WIB
                </div>
            </div>
        </div>
    </div>
@endsection
