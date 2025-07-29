@extends('layouts.dashboard')

@section('title', 'Dashboard Ketua Bidang')

@section('content')
    <div class="space-y-6">
        <!-- Executive Summary -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Dashboard Ketua Bidang Investigasi</h2>
                    <p class="text-primary-100">Koordinasi tim investigasi dan pengawasan kualitas penyelidikan.</p>
                    <div class="mt-3 flex items-center space-x-4 text-sm text-primary-200">
                        <span>{{ $stats['tim_aktif'] }} Tim Aktif</span>
                        <span>•</span>
                        <span>{{ $stats['laporan_pending'] }} Laporan Menunggu Review</span>
                    </div>
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
                        <p class="text-sm font-medium text-gray-600">Tim Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['tim_aktif'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dari {{ $stats['tim_dipimpin'] }} total tim</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['surat_tugas_aktif'] }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Skor Kinerja</p>
                        <p class="text-2xl font-semibold text-gray-900">94%</p>
                        <p class="text-xs text-green-600 mt-1">↑ 5% dari bulan lalu</p>
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

        <!-- Priority Actions -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Prioritas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href=""
                    class="group flex items-center p-4 border border-gray-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Review Laporan Urgent</h4>
                        <p class="text-xs text-gray-500">{{ $stats['laporan_pending'] }} laporan pending</p>
                    </div>
                </a>

                <a href=""
                    class="group flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Kelola Tim</h4>
                        <p class="text-xs text-gray-500">{{ $stats['tim_aktif'] }} tim aktif</p>
                    </div>
                </a>

                <a href=""
                    class="group flex items-center p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Generate Surat Tugas</h4>
                        <p class="text-xs text-gray-500">Untuk tim yang sudah dibentuk</p>
                    </div>
                </a>

                <a href=""
                    class="group flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Review Laporan Tugas</h4>
                        <p class="text-xs text-gray-500">Approve hasil investigasi</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Teams Performance Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Team Performance Chart -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kinerja Tim</h3>
                <div class="h-64">
                    <canvas id="teamPerformanceChart"></canvas>
                </div>
            </div>

            <!-- Recent Decisions -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Keputusan Terbaru</h3>
                <div class="space-y-4">
                    <div class="flex items-start border-l-4 border-green-400 pl-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Laporan #LP-001 Disetujui</p>
                            <p class="text-xs text-gray-500">Tim Alpha berhasil menyelesaikan investigasi korupsi</p>
                            <p class="text-xs text-gray-400 mt-1">2 jam lalu</p>
                        </div>
                    </div>

                    <div class="flex items-start border-l-4 border-blue-400 pl-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Tim Beta Dibentuk</p>
                            <p class="text-xs text-gray-500">Untuk menangani kasus pelayanan publik Dinas XYZ</p>
                            <p class="text-xs text-gray-400 mt-1">5 jam lalu</p>
                        </div>
                    </div>

                    <div class="flex items-start border-l-4 border-yellow-400 pl-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Surat Tugas ST-003 Diterbitkan</p>
                            <p class="text-xs text-gray-500">Investigasi dugaan mark-up anggaran</p>
                            <p class="text-xs text-gray-400 mt-1">1 hari lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Tim Yang Dipimpin</h3>
                    <a href=""
                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if ($timDipimpin->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($timDipimpin as $tim)
                            <div
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $tim->nama_tim }}</h4>
                                    <x-status-badge :status="$tim->status_tim" />
                                </div>
                                <p class="text-xs text-gray-600 mb-3">
                                    {{ Str::limit($tim->laporanPengaduan->judul_laporan, 60) }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $tim->anggotaAktif->count() }} anggota</span>
                                    <a href="{{ route('ketua_bidang.tim.show', $tim) }}"
                                        class="text-primary-600 hover:text-primary-700 font-medium">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state title="Belum ada tim">
                        description="Mulai dengan mereview laporan masuk dan bentuk tim investigasi." :actionHref="">
                        <x-slot name="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </x-slot>
                    </x-empty-state>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Team Performance Chart
            const teamPerformanceCtx = document.getElementById('teamPerformanceChart').getContext('2d');
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
        </script>
    @endpush
@endsection
