@extends('layouts.dashboard')

@section('title', 'Laporan Masuk')

@section('content')
    <div class="space-y-6">

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Reports -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laporan Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_pending'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Perlu review segera</p>
                    </div>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                @if ($stats['laporan_pending'] > 5)
                    <div class="absolute top-2 right-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            High Volume
                        </span>
                    </div>
                @endif
            </div>

            <!-- Active Teams -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laporan Diterima</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_diterima'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dari {{ $stats['semuaTim'] }} total tim</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>


            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dalam Investigasi</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_dalam_investigasi'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dalam pelaksanaan</p>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Performance Score -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laporan Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_selesai'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Selesai</p>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Laporan Masuk</h3>
                </div>
            </div>
            <div class="p-6">
                @if ($laporan->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($laporan as $report)
                            <div
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $report->judul_laporan }}</h4>
                                    <x-status-badge :status="$report->status" />
                                </div>

                                <p class="text-xs text-gray-600 mb-3">
                                    {{ Str::limit($report->isi_laporan, 60) }}
                                </p>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-gray-500">
                                        <strong>Kategori:</strong> {{ $report->kategori }}
                                    </span>
                                    <span
                                        class="text-xs font-medium 
                                        {{ $report->prioritas === 'Urgent'
                                            ? 'text-red-600'
                                            : ($report->prioritas === 'Tinggi'
                                                ? 'text-orange-600'
                                                : 'text-blue-600') }}">
                                        {{ $report->prioritas }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-gray-500">
                                        <strong>Lokasi:</strong> {{ Str::limit($report->lokasi_kejadian, 20) }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $report->tanggal_kejadian->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        <strong>Pelapor:</strong> {{ $report->user->nama_lengkap ?? 'Anonymous' }}
                                    </span>
                                    <a href="{{ route('ketua_bidang.laporan.show', $report->laporan_id) }}"
                                        class="text-primary-600 hover:text-primary-700 font-medium">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination or Load More -->
                    {{-- <div class="mt-6 flex justify-center">
                        <a href=""
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Lihat Semua Laporan
                        </a>
                    </div> --}}
                @else
                    <x-empty-state title="Belum ada laporan masuk">
                        <x-slot name="description">
                            Belum ada laporan pengaduan yang masuk ke sistem. Laporan akan muncul di sini setelah masyarakat
                            mengirimkan pengaduan.
                        </x-slot>
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </x-slot>
                    </x-empty-state>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Optional: Add interactivity for status filtering
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.querySelector('select');
                if (statusSelect) {
                    statusSelect.addEventListener('change', function() {
                        // You can implement AJAX filtering here
                        // or redirect with query parameters
                        const status = this.value;
                        if (status) {
                            window.location.href = ``;
                        } else {
                            window.location.href = ``;
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
