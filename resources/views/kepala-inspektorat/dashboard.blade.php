@extends('layouts.dashboard')

@section('title', 'Dashboard Ketua Bidang')

@section('content')
    <div class="space-y-6">
        <!-- Executive Summary -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Dashboard Kepala Inspektorat</h2>
                    <p class="text-primary-100">Koordinasi tim investigasi dan pengawasan kualitas penyelidikan.</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Reports -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laporan Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_pending'] ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">Perlu review segera</p>
                    </div>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                @if (($stats['laporan_pending'] ?? 0) > 5)
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
                        <p class="text-sm font-medium text-gray-600">Tim Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['tim_aktif'] ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Dari {{ $stats['tim_dipimpin'] ?? '-' }} total tim
                        </p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Task Letters -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Surat Tugas Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['surat_tugas_aktif'] ?? '-' }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Laporan Tugas Pegawai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_tugas'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Laporan Tugas Pegawai Submitted</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Performance Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kalender -->
            <x-kalender />

            <!-- Recent Decisions -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tim yang Bertugas</h3>

                <div class="space-y-4">
                    @forelse ($timBertugas as $tim)
                        <div
                            class="flex items-start border-l-4 
                @if ($tim->status_tim === 'Aktif') border-green-400
                @elseif($tim->status_tim === 'Dibentuk') border-blue-400
                @else border-yellow-400 @endif pl-4">

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $tim->nama_tim ?? 'Tim ' . $tim->tim_id }}
                                    ({{ $tim->status_tim }})
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $tim->laporanPengaduan->permasalahan ?? 'Tanpa judul laporan' }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $tim->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                    @empty
                        <p class="text-sm text-gray-500">Belum ada tim yang bertugas.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // Team Performance Chart
            const teamPerformanceCtx = document.getElementById('teamPerformanceChart')?.getContext('2d');
            if (teamPerformanceCtx) {
                new Chart(teamPerformanceCtx, {
                    type: 'radar',
                    data: {
                        labels: ['Kecepatan', 'Kualitas', 'Komunikasi', 'Kepatuhan', 'Inovasi'],
                        datasets: [{
                            label: 'Tim Alpha',
                            data: [85, 90, 88, 92, 75],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(59, 130, 246)'
                        }, {
                            label: 'Tim Beta',
                            data: [78, 85, 90, 87, 82],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.2)',
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(34, 197, 94)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
