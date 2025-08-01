@extends('layouts.dashboard')

@section('title', 'Dashboard Pegawai')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section with Time-based Greeting -->
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
                <div class="hidden md:block">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards with Trend Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tim Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['tim_aktif'] }}</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Surat Tugas Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['surat_tugas_aktif'] }}</p>
                        </div>
                    </div>
                    @if ($stats['surat_tugas_aktif'] > 0)
                        <div class="text-yellow-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Draft Laporan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_tugas_draft'] }}</p>
                        </div>
                    </div>
                    @if ($stats['laporan_tugas_draft'] > 0)
                        <div class="text-orange-600">
                            <span class="text-xs font-medium">Perlu diselesaikan</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Laporan Submitted</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['laporan_tugas_submitted'] }}</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Priority Tasks -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tugas Prioritas Hari Ini</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <!-- Example Priority Tasks -->
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">Laporan investigasi korupsi deadline hari ini</p>
                            <p class="text-xs text-gray-500">Surat Tugas #ST-001 • Tim Alpha</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Urgent
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">Review dokumen keuangan Dinas ABC</p>
                            <p class="text-xs text-gray-500">Surat Tugas #ST-002 • Tim Beta</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Medium
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href=""
                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Lihat semua tugas →
                    </a>
                </div>
            </div>
        </div>                
    </div>

    @push('scripts')
        <script>
            // Workload Chart
            const workloadCtx = document.getElementById('workloadChart').getContext('2d');
            new Chart(workloadCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Tugas Selesai',
                        data: [5, 8, 6, 10, 7, 9],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }, {
                        label: 'Tugas Pending',
                        data: [2, 1, 3, 2, 4, 1],
                        backgroundColor: 'rgba(251, 191, 36, 0.8)',
                        borderColor: 'rgb(251, 191, 36)',
                        borderWidth: 1
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
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
