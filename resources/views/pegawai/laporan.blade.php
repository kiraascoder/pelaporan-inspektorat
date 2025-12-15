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

        <!-- Filter dan Tabel Laporan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Laporan Masuk</h3>

                    <!-- Filter Controls -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Date Range Filter -->
                        <div class="flex items-center space-x-2">
                            <label for="start_date"
                                class="text-sm font-medium text-gray-700 whitespace-nowrap">Dari:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <div class="flex items-center space-x-2">
                            <label for="end_date"
                                class="text-sm font-medium text-gray-700 whitespace-nowrap">Sampai:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <!-- Status Filter -->
                        {{-- === Status Filter (values sesuai DB) === --}}
                        <select id="status_filter" name="status"
                            class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Diterima" {{ request('status') === 'Diterima' ? 'selected' : '' }}>Diterima
                            </option>
                            <option value="Dalam_Investigasi"
                                {{ request('status') === 'Dalam_Investigasi' ? 'selected' : '' }}>Dalam Investigasi</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                        <div class="flex space-x-2">
                            <button type="button" id="apply_filter"
                                class="bg-primary-600 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                Filter
                            </button>
                            <button type="button" id="reset_filter"
                                class="bg-gray-500 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                {{-- === Table === --}}
                @if ($laporan->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Permasalahan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terlapor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lampiran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($laporan as $report)
                                @php
                                    // Normalisasi lampiran: dukung array/JSON/string
                                    $lampiran = $report->bukti_pendukung;
                                    if (is_string($lampiran)) {
                                        $decoded = json_decode($lampiran, true);
                                        $lampiran = is_array($decoded) ? $decoded : ($lampiran ? [$lampiran] : []);
                                    } elseif (!is_array($lampiran)) {
                                        $lampiran = $lampiran ? (array) $lampiran : [];
                                    }
                                    $lampiranCount = count($lampiran);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($report->permasalahan, 60) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $report->pelapor_nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $report->terlapor_nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ optional($report->tanggal_pengaduan)->format('d M Y') ?? $report->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lampiranCount }} file
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$report->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('pegawai.laporan.show', $report->laporan_id) }}"
                                                class="text-primary-600 hover:text-primary-900">
                                                Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if (method_exists($laporan, 'links'))
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $laporan->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12">
                        <x-empty-state title="Belum ada laporan masuk">
                            <x-slot name="description">
                                @if (request()->hasAny(['start_date', 'end_date', 'status', 'prioritas']))
                                    Tidak ada laporan yang sesuai dengan filter yang dipilih. Silakan coba ubah filter atau
                                    reset filter untuk melihat semua laporan.
                                @else
                                    Belum ada laporan pengaduan yang masuk ke sistem. Laporan akan muncul di sini setelah
                                    masyarakat mengirimkan pengaduan.
                                @endif
                            </x-slot>
                            <x-slot name="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </x-slot>
                        </x-empty-state>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const applyFilterBtn = document.getElementById('apply_filter');
                const resetFilterBtn = document.getElementById('reset_filter');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const statusSelect = document.getElementById('status_filter');
                const prioritySelect = document.getElementById('priority_filter');

                // Debug: Check if elements exist
                console.log('Elements found:', {
                    applyFilterBtn,
                    resetFilterBtn,
                    startDateInput,
                    endDateInput,
                    statusSelect,
                    prioritySelect
                });

                // Apply Filter
                if (applyFilterBtn) {
                    applyFilterBtn.addEventListener('click', function() {
                        console.log('Apply filter clicked');

                        const params = new URLSearchParams(window.location.search);

                        // Clear existing filter params
                        params.delete('start_date');
                        params.delete('end_date');
                        params.delete('status');
                        params.delete('prioritas');

                        // Add new params if they have values
                        if (startDateInput && startDateInput.value) {
                            params.set('start_date', startDateInput.value);
                            console.log('Start date:', startDateInput.value);
                        }
                        if (endDateInput && endDateInput.value) {
                            params.set('end_date', endDateInput.value);
                            console.log('End date:', endDateInput.value);
                        }
                        if (statusSelect && statusSelect.value) {
                            params.set('status', statusSelect.value);
                            console.log('Status:', statusSelect.value);
                        }
                        if (prioritySelect && prioritySelect.value) {
                            params.set('prioritas', prioritySelect.value);
                            console.log('Prioritas:', prioritySelect.value);
                        }

                        const queryString = params.toString();
                        const baseUrl = window.location.pathname;
                        const newUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

                        console.log('Redirecting to:', newUrl);
                        window.location.href = newUrl;
                    });
                }

                // Reset Filter
                if (resetFilterBtn) {
                    resetFilterBtn.addEventListener('click', function() {
                        console.log('Reset filter clicked');

                        if (startDateInput) startDateInput.value = '';
                        if (endDateInput) endDateInput.value = '';
                        if (statusSelect) statusSelect.value = '';
                        if (prioritySelect) prioritySelect.value = '';

                        window.location.href = window.location.pathname;
                    });
                }

                // Auto-apply filter on Enter key for input fields
                [startDateInput, endDateInput].forEach(element => {
                    if (element) {
                        element.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                applyFilterBtn.click();
                            }
                        });
                    }
                });

                // Optional: Auto-apply on select change
                [statusSelect, prioritySelect].forEach(element => {
                    if (element) {
                        element.addEventListener('change', function() {
                            // Uncomment the line below if you want immediate filtering on select change
                            // applyFilterBtn.click();
                        });
                    }
                });

                // Date validation
                if (startDateInput) {
                    startDateInput.addEventListener('change', function() {
                        if (endDateInput && endDateInput.value && startDateInput.value > endDateInput.value) {
                            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                            startDateInput.value = '';
                        }
                    });
                }

                if (endDateInput) {
                    endDateInput.addEventListener('change', function() {
                        if (startDateInput && startDateInput.value && endDateInput.value < startDateInput
                            .value) {
                            alert('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai');
                            endDateInput.value = '';
                        }
                    });
                }

                // Set current filter values from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                if (startDateInput && urlParams.get('start_date')) {
                    startDateInput.value = urlParams.get('start_date');
                }
                if (endDateInput && urlParams.get('end_date')) {
                    endDateInput.value = urlParams.get('end_date');
                }
                if (statusSelect && urlParams.get('status')) {
                    statusSelect.value = urlParams.get('status');
                }
                if (prioritySelect && urlParams.get('prioritas')) {
                    prioritySelect.value = urlParams.get('prioritas');
                }
            });

            // Function to show report details
            function showReportDetails(reportId) {
                // Adjust the URL according to your route
                window.location.href = `/ketua_bidang/laporan/${reportId}`;
            }
        </script>
    @endpush
@endsection
